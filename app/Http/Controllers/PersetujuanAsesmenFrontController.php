<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\Tuk;
use App\Models\PersetujuanAsesmen;
use App\Models\JadwalUjikom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class PersetujuanAsesmenFrontController extends Controller
{
    private function resolveAsesiFromUser($user): ?Asesi
    {
        if (!$user) {
            return null;
        }

        $asesiQuery = Asesi::query();
        $hasCondition = false;

        if (!empty($user->NIK)) {
            $asesiQuery->where('NIK', $user->NIK);
            $hasCondition = true;
        }

        if (Schema::hasColumn('asesi', 'no_reg') && !empty($user->id)) {
            if ($hasCondition) {
                $asesiQuery->orWhere('no_reg', $user->id);
            } else {
                $asesiQuery->where('no_reg', $user->id);
                $hasCondition = true;
            }
        }

        return $hasCondition ? $asesiQuery->first() : null;
    }

    private function hasAsesiNikColumn(): bool
    {
        static $hasColumn = null;

        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn('persetujuan_asesmen', 'asesi_nik');
        }

        return $hasColumn;
    }

    private function buildDefaultPayload(array $overrides = []): array
    {
        $payload = array_merge([
            'kode_form' => 'FR.AK.01.',
            'judul_form' => 'PERSETUJUAN ASESMEN DAN KERAHASIAAN',
            'pengantar' => 'Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen',
            'kategori_skema' => 'KKNI/Okupasi/Klaster',
            'tuk' => 'Sewaktu/Tempat Kerja/Mandiri*',
            'nama_asesor' => '-',
            'pernyataan_asesi_1' => 'Bahwa saya telah mendapatkan penjelasan terkait hak dan prosedur banding asesmen dari asesor.',
            'pernyataan_asesor' => 'Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.',
            'pernyataan_asesi_2' => 'Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.',
            'catatan_footer' => '* Coret yang tidak perlu',
        ], $overrides);

        if (!$this->hasAsesiNikColumn()) {
            unset($payload['asesi_nik']);
        }

        return $payload;
    }

    private function resolveJadwalForAsesiSkema(Asesi $asesi, Skema $skema): ?JadwalUjikom
    {
        // 1. Direct participant or kelompok match
        $jadwal = JadwalUjikom::query()
            ->with(['tuk', 'skema', 'kelompoks.asesors', 'asesor'])
            ->where('skema_id', $skema->id)
            ->where(function ($query) use ($asesi) {
                $query->whereHas('peserta', function ($q) use ($asesi) {
                    $q->where('NIK', $asesi->NIK);
                });

                if (!empty($asesi->kelompok_id)) {
                    $query->orWhere('kelompok_id', $asesi->kelompok_id)
                          ->orWhereHas('kelompoks', function ($q) use ($asesi) {
                              $q->where('kelompok.id', $asesi->kelompok_id);
                          });
                }
            })
            ->orderByDesc('tanggal_mulai')
            ->first();

        if ($jadwal) {
            return $jadwal;
        }

        // 2. Check if asesi has an assigned asesor or kelompok with a schedule
        if (!empty($asesi->ID_asesor)) {
            $jadwalByAsesor = JadwalUjikom::query()
                ->with(['tuk', 'skema', 'kelompoks.asesors', 'asesor'])
                ->where('skema_id', $skema->id)
                ->where(function ($q) use ($asesi) {
                    $q->where('asesor_id', $asesi->ID_asesor)
                      ->orWhereHas('kelompoks.asesors', function ($sq) use ($asesi) {
                          $sq->where('asesor.ID_asesor', $asesi->ID_asesor);
                      });
                })
                ->orderByDesc('tanggal_mulai')
                ->first();

            if ($jadwalByAsesor) {
                return $jadwalByAsesor;
            }
        }

        // 3. Fallback: Any schedule created for this skema
        return JadwalUjikom::query()
            ->with(['tuk', 'skema', 'kelompoks.asesors', 'asesor'])
            ->where('skema_id', $skema->id)
            ->orderByDesc('tanggal_mulai')
            ->first();
    }

    private function autoFillScheduleDetails(PersetujuanAsesmen $item, ?Asesi $asesi, ?Skema $skema): PersetujuanAsesmen
    {
        if (!$asesi || !$skema) {
            return $item;
        }

        $jadwal = $this->resolveJadwalForAsesiSkema($asesi, $skema);
        if (!$jadwal) {
            return $item;
        }

        $updated = false;

        // 1. Hari / Tanggal
        if (empty($item->hari_tanggal) || $item->hari_tanggal === '-' || str_contains($item->hari_tanggal, 's.d.')) {
            if (!empty($jadwal->tanggal_mulai)) {
                $tglMulai = \Carbon\Carbon::parse($jadwal->tanggal_mulai)->locale('id');
                $tglSelesai = !empty($jadwal->tanggal_selesai) ? \Carbon\Carbon::parse($jadwal->tanggal_selesai)->locale('id') : null;

                if ($tglSelesai && $tglMulai->format('Y-m-d') !== $tglSelesai->format('Y-m-d')) {
                    $item->hari_tanggal = $tglMulai->isoFormat('D MMMM YYYY') . ' s.d. ' . $tglSelesai->isoFormat('D MMMM YYYY');
                } else {
                    $item->hari_tanggal = $tglMulai->isoFormat('dddd, D MMMM YYYY');
                }
                $updated = true;
            }
        }

        // 2. Waktu
        if (empty($item->waktu) || $item->waktu === '-') {
            if (!empty($jadwal->waktu_mulai)) {
                $waktuStr = $jadwal->waktu_mulai;
                if (!empty($jadwal->waktu_selesai)) {
                    $waktuStr .= ' - ' . $jadwal->waktu_selesai;
                }
                if (!str_contains(strtoupper($waktuStr), 'WIB')) {
                    $waktuStr .= ' WIB';
                }
                $item->waktu = $waktuStr;
                $updated = true;
            }
        }

        // 3. TUK Pelaksanaan
        if (empty($item->tuk_pelaksanaan) || $item->tuk_pelaksanaan === '-') {
            if ($jadwal->tuk?->nama_tuk) {
                $item->tuk_pelaksanaan = $jadwal->tuk->nama_tuk;
                $updated = true;
            }
        }

        // 4. TUK Tipe
        if (empty($item->tuk) || $item->tuk === '-' || $item->tuk === 'Sewaktu/Tempat Kerja/Mandiri*') {
            if ($jadwal->tuk) {
                $item->tuk = match ($jadwal->tuk->tipe_tuk) {
                    'sewaktu' => 'Sewaktu',
                    'tempat_kerja' => 'Tempat Kerja',
                    'mandiri' => 'Mandiri',
                    default => $jadwal->tuk->tipe_tuk ?? 'Sewaktu',
                };
                $updated = true;
            }
        }

        // 5. Nama Asesor (if missing)
        if (empty($item->nama_asesor) || $item->nama_asesor === '-') {
            $asesorName = $jadwal->kelompoks->first()?->asesors->first()?->nama
                ?? $jadwal->asesor?->nama;
            if ($asesorName) {
                $item->nama_asesor = $asesorName;
                $updated = true;
            }
        }

        if ($updated) {
            $item->save();
        }

        return $item;
    }

    private function buildPrefillDefaults(?Asesi $asesi, ?Skema $skema): array
    {
        $defaults = [];

        if ($skema) {
            $defaults['judul_skema'] = $skema->nama_skema ?? '';
            $defaults['nomor_skema'] = $skema->nomor_skema ?? '';
        }

        if ($asesi) {
            $defaults['nama_asesi'] = $asesi->nama ?? '';
        }

        if ($asesi && $skema) {
            $jadwal = $this->resolveJadwalForAsesiSkema($asesi, $skema);

            if ($jadwal?->tuk) {
                $defaults['tuk'] = match ($jadwal->tuk->tipe_tuk) {
                    'sewaktu' => 'Sewaktu',
                    'tempat_kerja' => 'Tempat Kerja',
                    'mandiri' => 'Mandiri',
                    default => $jadwal->tuk->tipe_tuk,
                };
                $defaults['tuk_pelaksanaan'] = $jadwal->tuk->nama_tuk ?? '';
            }
        }

        return $defaults;
    }

    private function createPlaceholderForAsesiSkema(Asesi $asesi, Skema $skema): ?PersetujuanAsesmen
    {
        $useNik = $this->hasAsesiNikColumn();

        $record = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
            ->where(function ($q) use ($asesi, $useNik) {
                $q->where('nama_asesi', $asesi->nama);
                if ($useNik) {
                    $q->orWhere('asesi_nik', $asesi->NIK);
                }
            })
            ->latest()
            ->first();

        if ($record) {
            return $this->autoFillScheduleDetails($record, $asesi, $skema);
        }

        $jadwal = $this->resolveJadwalForAsesiSkema($asesi, $skema);

        if (!$jadwal) {
            return null;
        }

        $asosorName = $jadwal->kelompoks->first()?->asesors->first()?->nama
            ?? $jadwal->asesor?->nama
            ?? '-';

        $tglMulai = !empty($jadwal->tanggal_mulai) ? \Carbon\Carbon::parse($jadwal->tanggal_mulai)->locale('id') : null;
        $tglSelesai = !empty($jadwal->tanggal_selesai) ? \Carbon\Carbon::parse($jadwal->tanggal_selesai)->locale('id') : null;
        
        $hariTanggalStr = '-';
        if ($tglMulai) {
            if ($tglSelesai && $tglMulai->format('Y-m-d') !== $tglSelesai->format('Y-m-d')) {
                $hariTanggalStr = $tglMulai->isoFormat('D MMMM YYYY') . ' s.d. ' . $tglSelesai->isoFormat('D MMMM YYYY');
            } else {
                $hariTanggalStr = $tglMulai->isoFormat('dddd, D MMMM YYYY');
            }
        }

        $waktuStr = '-';
        if (!empty($jadwal->waktu_mulai)) {
            $waktuStr = $jadwal->waktu_mulai . (!empty($jadwal->waktu_selesai) ? ' - ' . $jadwal->waktu_selesai : '') . ' WIB';
        }

        $tukTipe = 'Sewaktu';
        if ($jadwal->tuk) {
            $tukTipe = match ($jadwal->tuk->tipe_tuk) {
                'sewaktu' => 'Sewaktu',
                'tempat_kerja' => 'Tempat Kerja',
                'mandiri' => 'Mandiri',
                default => $jadwal->tuk->tipe_tuk ?? 'Sewaktu',
            };
        }

        return PersetujuanAsesmen::create($this->buildDefaultPayload([
            'judul_skema' => $skema->nama_skema ?? '',
            'nomor_skema' => $skema->nomor_skema ?? '',
            'nama_asesi' => $asesi->nama,
            'asesi_nik' => $asesi->NIK,
            'nama_asesor' => $asosorName,
            'tuk' => $tukTipe,
            'hari_tanggal' => $hariTanggalStr,
            'waktu' => $waktuStr,
            'tuk_pelaksanaan' => $jadwal->tuk?->nama_tuk ?? '-',
        ]));
    }

    private function recordHasChecklist(PersetujuanAsesmen $record): bool
    {
        return (bool) (
            $record->bukti_verifikasi_portofolio ||
            $record->bukti_reviu_produk ||
            $record->bukti_observasi_langsung ||
            $record->bukti_kegiatan_terstruktur ||
            $record->bukti_pertanyaan_lisan ||
            $record->bukti_pertanyaan_tertulis ||
            $record->bukti_pertanyaan_wawancara ||
            $record->bukti_lainnya
        );
    }

    private function defaultContent(): array
    {
        return [
            'kode_form' => 'FR.AK.01.',
            'judul_form' => 'PERSETUJUAN ASESMEN DAN KERAHASIAAN',
            'pengantar' => 'Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen',
            'kategori_skema' => 'KKNI/Okupasi/Klaster',
            'tuk' => 'Sewaktu/Tempat Kerja/Mandiri*',
            'pernyataan_asesi_1' => 'Bahwa saya telah mendapatkan penjelasan terkait hak dan prosedur banding asesmen dari asesor.',
            'pernyataan_asesor' => 'Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.',
            'pernyataan_asesi_2' => 'Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.',
            'catatan_footer' => '* Coret yang tidak perlu',
        ];
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'kode_form' => 'required|string|max:20',
            'judul_form' => 'required|string|max:255',
            'pengantar' => 'required|string',
            'kategori_skema' => 'nullable|string|max:100',
            'judul_skema' => 'required|string|max:255',
            'nomor_skema' => 'required|string|max:255',
            'tuk' => 'nullable|string|max:255',
            'nama_asesor' => 'required|string|max:255',
            'nama_asesi' => 'required|string|max:255',
            'bukti_verifikasi_portofolio' => 'nullable|boolean',
            'bukti_reviu_produk' => 'nullable|boolean',
            'bukti_observasi_langsung' => 'nullable|boolean',
            'bukti_kegiatan_terstruktur' => 'nullable|boolean',
            'bukti_pertanyaan_lisan' => 'nullable|boolean',
            'bukti_pertanyaan_tertulis' => 'nullable|boolean',
            'bukti_pertanyaan_wawancara' => 'nullable|boolean',
            'bukti_lainnya' => 'nullable|boolean',
            'bukti_lainnya_keterangan' => 'nullable|string|max:255',
            'hari_tanggal' => 'nullable|string|max:120',
            'waktu' => 'nullable|string|max:120',
            'tuk_pelaksanaan' => 'nullable|string|max:255',
            'pernyataan_asesi_1' => 'required|string',
            'pernyataan_asesor' => 'required|string',
            'pernyataan_asesi_2' => 'required|string',
            'ttd_asesor_nama' => 'nullable|string|max:255',
            'ttd_asesor_tanggal' => 'nullable|date',
            'ttd_asesi_nama' => 'nullable|string|max:255',
            'ttd_asesi_tanggal' => 'nullable|date',
            'catatan_footer' => 'nullable|string|max:255',
        ]);

        foreach ([
            'bukti_verifikasi_portofolio',
            'bukti_reviu_produk',
            'bukti_observasi_langsung',
            'bukti_kegiatan_terstruktur',
            'bukti_pertanyaan_lisan',
            'bukti_pertanyaan_tertulis',
            'bukti_pertanyaan_wawancara',
            'bukti_lainnya',
        ] as $field) {
            $data[$field] = $request->boolean($field);
        }

        return $data;
    }

    public function participantsBySkema(Request $request)
    {
        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
        ]);

        $skemaId = (int) $validated['skema_id'];

        $asesiList = Asesi::query()
            ->whereHas('skemas', function ($query) use ($skemaId) {
                $query->where('skemas.id', $skemaId);
            })
            ->orderBy('nama')
            ->get(['NIK', 'nama'])
            ->map(function ($asesi) {
                return [
                    'id' => (string) $asesi->NIK,
                    'nama' => $asesi->nama,
                ];
            })
            ->values();

        $isAsesorRoute = $request->routeIs('asesor.*');
        $account = $request->user();
        $loggedAsesor = $account ? Asesor::where('no_met', $account->id)->first() : null;

        if ($isAsesorRoute && $loggedAsesor) {
            $asesorList = collect([[
                'id' => (string) $loggedAsesor->ID_asesor,
                'nama' => $loggedAsesor->nama,
            ]]);
        } else {
            $asesorList = Asesor::query()
                ->whereHas('skemas', function ($query) use ($skemaId) {
                    $query->where('skemas.id', $skemaId);
                })
                ->orderBy('nama')
                ->get(['ID_asesor', 'nama'])
                ->map(function ($asesor) {
                    return [
                        'id' => (string) $asesor->ID_asesor,
                        'nama' => $asesor->nama,
                    ];
                })
                ->values();
        }

        return response()->json([
            'asesi' => $asesiList,
            'asesor' => $asesorList,
        ]);
    }

    public function asesorCreate(Request $request)
    {
        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;
        $skema = null;
        $asesi = null;

        $skemaId = $request->query('skema_id');
        if (!empty($skemaId)) {
            $skema = Skema::find($skemaId);
        }

        $asesiNik = $request->query('asesi_nik');
        if (!empty($asesiNik)) {
            $asesi = Asesi::where('NIK', $asesiNik)->first();
        }

        $defaults = array_merge($this->defaultContent(), $this->buildPrefillDefaults($asesi, $skema), [
            'nama_asesor' => $asesor?->nama ?? '-',
        ]);

        $skemaList = Skema::query()
            ->orderBy('nama_skema')
            ->get(['id', 'nama_skema', 'nomor_skema']);

        $tukList = Tuk::query()
            ->orderBy('nama_tuk')
            ->get(['id', 'nama_tuk', 'tipe_tuk', 'kota', 'status']);

        return view('persetujuan-asesmen.create', [
            'defaults' => $defaults,
            'skemaList' => $skemaList,
            'tukList' => $tukList,
            'submitLabel' => 'Simpan Data',
            'backUrl' => route('asesor.persetujuan-asesmen.index'),
            'participantsEndpoint' => route('asesor.persetujuan-asesmen.skema-participants'),
        ]);
    }

    public function asesorStore(Request $request)
    {
        $data = $this->validatedData($request);
        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;

        if ($asesor) {
            // Keep persisted asesor name aligned with the logged-in asesor account.
            $data['nama_asesor'] = $asesor->nama;
        }

        $asesi = Asesi::where('NIK', $data['asesi_nik'])->first();
        $skema = Skema::where('nomor_skema', $data['nomor_skema'])->first();
        if ($asesi && $skema) {
            $pivot = \Illuminate\Support\Facades\DB::table('asesi_skema')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skema->id)
                ->first();
            if (!$pivot || $pivot->rekomendasi !== 'lanjut') {
                return redirect()->back()
                    ->with('error', 'Persetujuan Asesmen hanya dapat dibuat setelah Asesi direkomendasikan "Dapat Lanjut" pada Asesmen Mandiri.')
                    ->withInput();
            }
        }

        PersetujuanAsesmen::create($data);

        return redirect()->route('asesor.persetujuan-asesmen.index')
            ->with('success', 'Data persetujuan asesmen berhasil ditambahkan.');
    }

    public function asesorIndex(Request $request)
    {
        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;
        $useNik = $this->hasAsesiNikColumn();
        $search = $request->get('search');
        $statusFilter = $request->get('status');

        $items = collect();
        $viewMode = (string) $request->get('view', 'menunggu');

        if ($asesor) {
            $records = PersetujuanAsesmen::query()
                ->where('nama_asesor', $asesor->nama)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('judul_skema', 'like', "%{$search}%")
                            ->orWhere('nomor_skema', 'like', "%{$search}%")
                            ->orWhere('nama_asesi', 'like', "%{$search}%");
                    });
                })
                ->when($statusFilter, function ($query) use ($statusFilter) {
                    if ($statusFilter === 'belum_asesor') {
                        $query->where(function($q) {
                            $q->whereNull('ttd_asesor_nama')->orWhere('ttd_asesor_nama', '');
                        });
                    } elseif ($statusFilter === 'belum_asesi') {
                        $query->where(function($q) {
                            $q->whereNotNull('ttd_asesor_nama')->where('ttd_asesor_nama', '!=', '');
                        })->where(function($q) {
                            $q->whereNull('ttd_asesi_nama')->orWhere('ttd_asesi_nama', '');
                        });
                    } elseif ($statusFilter === 'sudah') {
                        $query->where(function($q) {
                            $q->whereNotNull('ttd_asesor_nama')->where('ttd_asesor_nama', '!=', '');
                        })->where(function($q) {
                            $q->whereNotNull('ttd_asesi_nama')->where('ttd_asesi_nama', '!=', '');
                        });
                    }
                })
                ->latest()
                ->get();

            $skemaByNomor = Skema::query()
                ->get(['id', 'nama_skema', 'nomor_skema'])
                ->keyBy('nomor_skema');

            $asesiByNik = Asesi::query()
                ->get(['NIK', 'nama'])
                ->keyBy('NIK');

            $items = $records->map(function ($record) use ($skemaByNomor, $asesiByNik, $useNik) {
                $skema = $skemaByNomor->get($record->nomor_skema);

                $asesiNik = null;
                if ($useNik && !empty($record->asesi_nik)) {
                    $asesiNik = $record->asesi_nik;
                }

                if (!$asesiNik) {
                    $matchingAsesi = Asesi::query()
                        ->where('nama', $record->nama_asesi)
                        ->orderBy('NIK')
                        ->first(['NIK']);
                    $asesiNik = $matchingAsesi?->NIK;
                }

                $asesiNama = $record->nama_asesi;
                if ($asesiNik && $asesiByNik->has($asesiNik)) {
                    $asesiNama = $asesiByNik->get($asesiNik)->nama;
                }

                $status = 'Belum Ditandatangani Asesor';
                if (!empty($record->ttd_asesor_nama)) {
                    $status = !empty($record->ttd_asesi_nama)
                        ? 'Sudah Ditandatangani'
                        : 'Belum Ditandatangani Asesi';
                }

                return [
                    'asesi_nik' => $asesiNik,
                    'asesi_nama' => $asesiNama,
                    'skema_id' => $skema?->id,
                    'skema_nama' => $record->judul_skema ?: ($skema?->nama_skema ?? '-'),
                    'skema_nomor' => $record->nomor_skema,
                    'status' => $status,
                ];
            })->filter(function ($item) {
                if (!$item['asesi_nik'] || !$item['skema_id']) {
                    return false;
                }
                $pivot = \Illuminate\Support\Facades\DB::table('asesi_skema')
                    ->where('asesi_nik', $item['asesi_nik'])
                    ->where('skema_id', $item['skema_id'])
                    ->first();
                return $pivot && $pivot->rekomendasi === 'lanjut';
            })->values();

            // Count totals before filtering by view
            $pendingCount = $items->filter(fn($i) => $i['status'] !== 'Sudah Ditandatangani')->count();
            $completedCount = $items->filter(fn($i) => $i['status'] === 'Sudah Ditandatangani')->count();

            // Filter by view mode
            if ($viewMode === 'selesai') {
                $items = $items->filter(fn($i) => $i['status'] === 'Sudah Ditandatangani')->values();
            } else {
                $items = $items->filter(fn($i) => $i['status'] !== 'Sudah Ditandatangani')->values();
            }
        } else {
            $pendingCount = 0;
            $completedCount = 0;
        }

        if ($request->ajax()) {
            return view('persetujuan-asesmen.partials.asesor-table-rows', compact('items'))->render();
        }

        return view('persetujuan-asesmen.asesor-index', [
            'items' => $items,
            'asesor' => $asesor,
            'search' => $search,
            'status' => $statusFilter,
            'viewMode' => $viewMode,
            'pendingCount' => $pendingCount ?? 0,
            'completedCount' => $completedCount ?? 0,
        ]);
    }

    public function asesiIndex(Request $request)
    {
        $asesi = $this->resolveAsesiFromUser($request->user());
        $useNik = $this->hasAsesiNikColumn();

        if ($asesi) {
            // find latest persetujuan record where asesor already signed and checked checklist
            $record = PersetujuanAsesmen::where(function ($q) use ($asesi, $useNik) {
                $q->where('nama_asesi', $asesi->nama);
                if ($useNik) {
                    $q->orWhere('asesi_nik', $asesi->NIK);
                }
            })
            ->whereNotNull('ttd_asesor_nama')
            ->whereNotNull('ttd_asesor_tanggal')
            ->where(function ($q) {
                $q->where('bukti_verifikasi_portofolio', 1)
                  ->orWhere('bukti_reviu_produk', 1)
                  ->orWhere('bukti_observasi_langsung', 1)
                  ->orWhere('bukti_kegiatan_terstruktur', 1)
                  ->orWhere('bukti_pertanyaan_lisan', 1)
                  ->orWhere('bukti_pertanyaan_tertulis', 1)
                  ->orWhere('bukti_pertanyaan_wawancara', 1)
                  ->orWhere('bukti_lainnya', 1);
            })
            ->latest()
            ->first();

            if (!$record) {
                return redirect()->route('asesi.dashboard')->with('error', 'Form belum tersedia. Asesor belum menyelesaikan ceklis bukti dan/atau belum menandatangani form.');
            }

            // find skema id for the record and redirect user directly to sign page
            $skema = Skema::where('nomor_skema', $record->nomor_skema)->first();
            if ($skema) {
                return redirect()->route('asesi.persetujuan.front.asesi.show', $skema->id);
            }
        }

        return redirect()->route('asesi.dashboard');
    }

    public function asesorShow(Request $request, $asesiNik, $skemaId)
    {
        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;
        $asesi = Asesi::where('NIK', $asesiNik)->first();
        $skema = Skema::find($skemaId);

        if (!$skema) {
            abort(404);
        }

        $namaAsesi = $asesi ? $asesi->nama : null;
        $useNik = $this->hasAsesiNikColumn();

        // Persetujuan Asesmen hanya bisa diakses asesor jika asesi sudah memiliki jadwal ujikon dari admin
        $hasJadwal = false;
        if ($asesi && $skema) {
            $hasJadwal = $this->resolveJadwalForAsesiSkema($asesi, $skema) !== null;
        }

        if (!$hasJadwal) {
            return redirect()->route('asesor.persetujuan-asesmen.index')
                ->with('error', 'Asesi belum memiliki jadwal ujikon dari admin; persetujuan asesmen belum dapat diakses.');
        }

        if ($asesi) {
            $pivot = \Illuminate\Support\Facades\DB::table('asesi_skema')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skemaId)
                ->first();

            if (!$pivot || $pivot->rekomendasi !== 'lanjut') {
                return redirect()->route('asesor.persetujuan-asesmen.index')
                    ->with('error', 'Persetujuan Asesmen hanya dapat diakses setelah Asesi direkomendasikan "Dapat Lanjut" pada Asesmen Mandiri.');
            }
        }

        $item = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
            ->where(function ($q) use ($namaAsesi, $asesiNik, $useNik) {
                if ($namaAsesi) {
                    $q->where('nama_asesi', $namaAsesi);
                }
                if ($useNik) {
                    $q->orWhere('asesi_nik', $asesiNik);
                }
            })->latest()->first();

        if (!$item) {
            // create placeholder record so form can be signed
            $item = PersetujuanAsesmen::create($this->buildDefaultPayload([
                'judul_skema' => $skema->nama_skema ?? '',
                'nomor_skema' => $skema->nomor_skema ?? '',
                'nama_asesi' => $namaAsesi ?? '',
                'asesi_nik' => $asesiNik,
                'nama_asesor' => $asesor?->nama ?? '-',
            ]));
        }

        if ($item && $asesi && $skema) {
            $item = $this->autoFillScheduleDetails($item, $asesi, $skema);
        }

        $tukList = Tuk::orderBy('nama_tuk')->get(['id','nama_tuk','tipe_tuk','kota']);

        $savedSignature = $asesor ? $asesor->saved_tanda_tangan : null;

        return view('persetujuan-asesmen.front', [
            'item' => $item,
            'role' => 'asesor',
            'skema' => $skema,
            'tukList' => $tukList,
            'asesiNik' => $asesiNik,
            'savedSignature' => $savedSignature,
        ]);
    }

    public function asesorSign(Request $request, $id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);

        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;

        // Ensure asesi was recommended for this skema before allowing asesor to sign
        $useNik = $this->hasAsesiNikColumn();
        $asesiForCheck = null;
        if ($useNik && !empty($item->asesi_nik)) {
            $asesiForCheck = Asesi::where('NIK', $item->asesi_nik)->first();
        } else {
            $asesiForCheck = Asesi::where('nama', $item->nama_asesi)->first();
        }

        $skemaForCheck = Skema::where('nomor_skema', $item->nomor_skema)->first();
        if ($asesiForCheck && $skemaForCheck) {
            $hasJadwal = $this->resolveJadwalForAsesiSkema($asesiForCheck, $skemaForCheck) !== null;
            if (!$hasJadwal) {
                return redirect()->back()->with('error', 'Asesi belum memiliki jadwal ujikon dari admin; persetujuan asesmen belum dapat ditandatangani.');
            }

            $pivot = \Illuminate\Support\Facades\DB::table('asesi_skema')
                ->where('asesi_nik', $asesiForCheck->NIK)
                ->where('skema_id', $skemaForCheck->id)
                ->first();

            if (!$pivot || $pivot->rekomendasi !== 'lanjut') {
                return redirect()->back()->with('error', 'Persetujuan Asesmen hanya dapat ditandatangani setelah Asesi direkomendasikan "Dapat Lanjut" pada Asesmen Mandiri.');
            }
        }

        $data = $request->validate([
            'ttd_asesor_nama' => 'required|string|max:255',
            'ttd_asesor_tanggal' => 'required|date',
            'ttd_asesor_file' => 'required|string',
            'simpan_tanda_tangan' => 'nullable|in:0,1',
            'bukti_verifikasi_portofolio' => 'nullable|boolean',
            'bukti_reviu_produk' => 'nullable|boolean',
            'bukti_observasi_langsung' => 'nullable|boolean',
            'bukti_kegiatan_terstruktur' => 'nullable|boolean',
            'bukti_pertanyaan_lisan' => 'nullable|boolean',
            'bukti_pertanyaan_tertulis' => 'nullable|boolean',
            'bukti_pertanyaan_wawancara' => 'nullable|boolean',
            'bukti_lainnya' => 'nullable|boolean',
            'bukti_lainnya_keterangan' => 'nullable|string|max:500',
        ]);

        // Convert checkbox values to boolean (checkboxes send '1' or null)
        $data['bukti_verifikasi_portofolio'] = $request->has('bukti_verifikasi_portofolio');
        $data['bukti_reviu_produk'] = $request->has('bukti_reviu_produk');
        $data['bukti_observasi_langsung'] = $request->has('bukti_observasi_langsung');
        $data['bukti_kegiatan_terstruktur'] = $request->has('bukti_kegiatan_terstruktur');
        $data['bukti_pertanyaan_lisan'] = $request->has('bukti_pertanyaan_lisan');
        $data['bukti_pertanyaan_tertulis'] = $request->has('bukti_pertanyaan_tertulis');
        $data['bukti_pertanyaan_wawancara'] = $request->has('bukti_pertanyaan_wawancara');
        $data['bukti_lainnya'] = $request->has('bukti_lainnya');
        if (!$request->has('bukti_lainnya')) {
            $data['bukti_lainnya_keterangan'] = null;
        }

        if ($request->has('ttd_asesor_file') && !empty($request->ttd_asesor_file)) {
            $savedFile = $this->processAndSaveSignature($request->ttd_asesor_file, 'asesor', $item->id);
            if ($savedFile) {
                $data['ttd_asesor_file'] = $savedFile;
                if ($request->input('simpan_tanda_tangan') === '1' && $asesor) {
                    $asesor->update(['saved_tanda_tangan' => $savedFile]);
                }
            }
        } else {
            return redirect()->back()->with('error', 'Tanda tangan asesor tidak tersimpan. Pastikan tanda tangan digambar di canvas sebelum menyimpan.');
        }

        $item->update($data);

        return redirect()->back()->with('success', 'Tanda tangan asesor tersimpan');
    }

    /**
     * Export persetujuan asesmen untuk asesor dalam format DOCX dengan logo.
     */
    public function asesorExport($asesiNik, $skemaId)
    {
        $account = auth()->guard('account')->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;
        $asesi = Asesi::where('NIK', $asesiNik)->first();
        $skema = Skema::find($skemaId);

        if (!$skema) {
            abort(404);
        }

        $namaAsesi = $asesi ? $asesi->nama : null;
        $useNik = $this->hasAsesiNikColumn();

        $item = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
            ->where(function ($q) use ($namaAsesi, $asesiNik, $useNik) {
                if ($namaAsesi) {
                    $q->where('nama_asesi', $namaAsesi);
                }
                if ($useNik) {
                    $q->orWhere('asesi_nik', $asesiNik);
                }
            })
            ->latest()
            ->first();

        if (!$item) {
            abort(404, 'Persetujuan asesmen tidak ditemukan.');
        }

        $logoPath = public_path('images/lsp.png');
        $logoDataUri = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $ttdAsesiDataUri = null;
        if (!empty($item->ttd_asesi_file)) {
            if (str_starts_with($item->ttd_asesi_file, 'data:image')) {
                $ttdAsesiDataUri = $item->ttd_asesi_file;
            } else {
                $filePath = storage_path('app/public/' . ltrim($item->ttd_asesi_file, '/'));
                if (file_exists($filePath)) {
                    $mime = mime_content_type($filePath) ?: 'image/png';
                    $ttdAsesiDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($filePath));
                }
            }
        }

        $ttdAsesorDataUri = null;
        if (!empty($item->ttd_asesor_file)) {
            if (str_starts_with($item->ttd_asesor_file, 'data:image')) {
                $ttdAsesorDataUri = $item->ttd_asesor_file;
            } else {
                $filePath = storage_path('app/public/' . ltrim($item->ttd_asesor_file, '/'));
                if (file_exists($filePath)) {
                    $mime = mime_content_type($filePath) ?: 'image/png';
                    $ttdAsesorDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($filePath));
                }
            }
        }

        $asesor = null;
        if (!empty($item->nama_asesor)) {
            $asesor = \App\Models\Asesor::where('nama', $item->nama_asesor)->first();
        }
        if (!$asesor && !empty($item->reviewed_by)) {
            $asesor = \App\Models\Asesor::where('no_met', (string) $item->reviewed_by)->first();
        }

        $html = view('persetujuan-asesmen.export-docx', [
            'item' => $item,
            'skema' => $skema,
            'asesor' => $asesor,
            'logoPath' => $logoPath,
            'logoDataUri' => $logoDataUri,
            'ttdAsesiDataUri' => $ttdAsesiDataUri,
            'ttdAsesorDataUri' => $ttdAsesorDataUri,
        ])->render();

        $fileSkema = preg_replace('/[^A-Za-z0-9\-]+/', '-', (string) ($skema->nomor_skema ?? $skema->id));
        $fileName = 'FR.AK.01-' . $asesiNik . '-' . trim($fileSkema, '-') . '.doc';

        return response($html, 200, [
            'Content-Type' => 'application/msword; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function asesiShow(Request $request, $skemaId)
    {
        $asesi = $this->resolveAsesiFromUser($request->user());
        if (!$asesi) {
            abort(403);
        }

        $skema = Skema::find($skemaId);
        if (!$skema) abort(404);
        $item = $this->createPlaceholderForAsesiSkema($asesi, $skema);

        if (!$item) {
            return redirect()->route('asesi.persetujuan-asesmen.index')
                ->with('error', 'Form belum tersedia. Jadwal untuk skema ini belum dibuat oleh admin.');
        }

        $rawTtd = $asesi->tanda_tangan_pendaftar ?? $asesi->tanda_tangan;
        $savedSignature = $this->formatSignatureToUrl($rawTtd);

        $tukList = Tuk::orderBy('nama_tuk')->get(['id','nama_tuk','tipe_tuk','kota']);

        return view('persetujuan-asesmen.front', [
            'item' => $item,
            'role' => 'asesi',
            'skema' => $skema,
            'tukList' => $tukList,
            'savedSignature' => $savedSignature,
        ]);
    }

    private function formatSignatureToUrl(?string $sig): ?string
    {
        if (empty($sig)) {
            return null;
        }

        $sig = trim($sig);

        if (str_contains($sig, '/storage/')) {
            $relativePath = ltrim(explode('/storage/', $sig)[1], '/');
            return asset('storage/' . $relativePath);
        }

        if (str_starts_with($sig, 'http://') || str_starts_with($sig, 'https://')) {
            return $sig;
        }

        if (str_starts_with($sig, 'persetujuan-asesmen/') || str_starts_with($sig, 'signatures/') || str_starts_with($sig, 'pendaftar/')) {
            return asset('storage/' . ltrim($sig, '/'));
        }

        if (str_starts_with($sig, 'data:image')) {
            return preg_replace('/\s+/', '', $sig);
        }

        $clean = preg_replace('/\s+/', '', $sig);
        return 'data:image/png;base64,' . $clean;
    }

    private function processAndSaveSignature(?string $rawSig, string $prefix, int $itemId): ?string
    {
        if (empty($rawSig)) {
            return null;
        }

        $rawSig = trim($rawSig);

        if (str_contains($rawSig, '/storage/')) {
            return ltrim(explode('/storage/', $rawSig)[1], '/');
        }

        if (str_starts_with($rawSig, 'persetujuan-asesmen/') || str_starts_with($rawSig, 'signatures/') || str_starts_with($rawSig, 'pendaftar/')) {
            return ltrim($rawSig, '/');
        }

        $b64 = $rawSig;
        if (str_contains($b64, 'base64,')) {
            $parts = explode('base64,', $b64);
            $b64 = end($parts);
        }

        $cleanB64 = preg_replace('/\s+/', '', $b64);
        $binary = base64_decode($cleanB64, true);

        if ($binary !== false && strlen($binary) > 50) {
            $filename = 'signature_' . $prefix . '_' . $itemId . '_' . time() . '.png';
            $relativePath = 'persetujuan-asesmen/signatures/' . $filename;
            Storage::disk('public')->put($relativePath, $binary);
            return $relativePath;
        }

        return $rawSig;
    }

    public function asesiSign(Request $request, $id)
    {
        $asesi = $this->resolveAsesiFromUser($request->user());
        if (!$asesi) {
            abort(403);
        }

        $item = PersetujuanAsesmen::findOrFail($id);
        $useNik = $this->hasAsesiNikColumn();

        $sameAsesi = $item->nama_asesi === $asesi->nama;
        if ($useNik && !empty($item->asesi_nik) && !empty($asesi->NIK)) {
            $sameAsesi = $sameAsesi || $item->asesi_nik === $asesi->NIK;
        }

        if (!$sameAsesi) {
            Log::error('Asesi mismatch', [
                'item_nama' => $item->nama_asesi,
                'asesi_nama' => $asesi->nama,
                'item_nik' => $item->asesi_nik,
                'asesi_nik' => $asesi->NIK ?? null,
            ]);
            abort(403);
        }

        if (empty($item->ttd_asesor_nama) || empty($item->ttd_asesor_tanggal)) {
            Log::error('Asesor not signed yet', [
                'item_id' => $item->id,
                'ttd_asesor_nama' => $item->ttd_asesor_nama,
                'ttd_asesor_tanggal' => $item->ttd_asesor_tanggal,
            ]);
            return redirect()->back()->with('error', 'Tanda tangan asesi belum bisa dilakukan sebelum asesor menandatangani.');
        }

        Log::info('Asesi sign request received', [
            'id' => $id,
            'request_data' => $request->all(),
        ]);

        $data = $request->validate([
            'ttd_asesi_tanggal' => 'required|date_format:Y-m-d',
            'simpan_tanda_tangan' => 'nullable|in:0,1',
        ], [
            'ttd_asesi_tanggal.required' => 'Tanggal harus diisi',
            'ttd_asesi_tanggal.date_format' => 'Format tanggal tidak valid',
        ]);

        $data['ttd_asesi_nama'] = $asesi->nama;

        // Handle signature image file
        if ($request->has('ttd_asesi_file') && !empty($request->ttd_asesi_file)) {
            $savedFile = $this->processAndSaveSignature($request->ttd_asesi_file, 'asesi', $item->id);
            if ($savedFile) {
                $data['ttd_asesi_file'] = $savedFile;
                if ($request->input('simpan_tanda_tangan') === '1' && $asesi) {
                    $asesi->update(['tanda_tangan_pendaftar' => $savedFile]);
                }
            }
        }

        // Fallback: if no signature image was uploaded, generate a simple SVG with the name
        if (empty($data['ttd_asesi_file']) && !empty($data['ttd_asesi_nama'])) {
            try {
                $name = htmlspecialchars($data['ttd_asesi_nama'], ENT_QUOTES, 'UTF-8');
                $svg = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"600\" height=\"140\">";
                $svg .= "<rect width=\"100%\" height=\"100%\" fill=\"#ffffff\"/>";
                $svg .= "<text x=\"50%\" y=\"60%\" dominant-baseline=\"middle\" text-anchor=\"middle\" font-family=\"Segoe UI, Arial, sans-serif\" font-size=36 fill=\"#111827\">" . $name . "</text>";
                $svg .= "</svg>";

                $filename = 'signature_asesi_name_' . $item->id . '_' . time() . '.svg';
                $path = 'persetujuan-asesmen/signatures';
                Storage::disk('public')->put($path . '/' . $filename, $svg);
                $data['ttd_asesi_file'] = $path . '/' . $filename;
                Log::info('Generated fallback signature SVG for asesi', ['file' => $data['ttd_asesi_file']]);
            } catch (\Exception $e) {
                Log::error('Failed to generate fallback signature SVG', ['error' => $e->getMessage()]);
            }
        }

        Log::info('Updating asesi signature', [
            'id' => $item->id,
            'data' => $data,
        ]);

        $item->update($data);

        Log::info('Asesi signature saved', [
            'id' => $item->id,
            'ttd_asesi_tanggal' => $item->ttd_asesi_tanggal,
            'ttd_asesi_nama' => $item->ttd_asesi_nama,
        ]);

        return redirect()->route('asesi.persetujuan-asesmen.index')->with('success', 'Tanda tangan asesi tersimpan');
    }
}

