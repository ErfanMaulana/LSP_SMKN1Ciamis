<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\PersetujuanAsesmen;
use App\Models\JadwalUjikom;
use App\Models\Kelompok;
use App\Models\Tuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class JadwalUjikomController extends Controller
{
    private function findBentrokJadwalAsesor(?int $asesorId, string $tanggalMulai, string $tanggalSelesai, ?int $excludeJadwalId = null): ?JadwalUjikom
    {
        if (!$asesorId) {
            return null;
        }

        return JadwalUjikom::query()
            ->where('asesor_id', $asesorId)
            ->where('status', '!=', 'dibatalkan')
            ->whereDate('tanggal_mulai', '<=', $tanggalSelesai)
            ->whereDate('tanggal_selesai', '>=', $tanggalMulai)
            ->when($excludeJadwalId, function ($query, $excludeJadwalId) {
                $query->where('id', '!=', $excludeJadwalId);
            })
            ->orderBy('tanggal_mulai')
            ->first();
    }

    private function normalizeKelompokIds(array $kelompokIds): array
    {
        return collect($kelompokIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();
    }

    private function buildKelompokData($kelompoks): array
    {
        return $kelompoks->mapWithKeys(fn ($k) => [
            $k->id => [
                'nama_kelompok' => $k->nama_kelompok,
                'nama_skema'    => $k->skema?->nama_skema ?? '-',
                'asesor_nama'   => $k->asesors->first()?->nama ?? '-',
                'asesi_count'   => $k->asesis->count(),
                'asesi_niks'    => $k->asesis->pluck('NIK')->values(),
            ]
        ])->toArray();
    }

    private function loadKelompoksByIds(array $kelompokIds)
    {
        $kelompokMap = Kelompok::with(['skema', 'asesors', 'asesis'])
            ->whereIn('id', $kelompokIds)
            ->get()
            ->keyBy('id');

        return collect($kelompokIds)
            ->map(fn ($id) => $kelompokMap->get($id))
            ->filter()
            ->values();
    }

    private function getKelompokScheduleMap(?int $excludeJadwalId = null): array
    {
        $map = [];

        if (Schema::hasTable('jadwal_kelompok')) {
            $rows = DB::table('jadwal_kelompok as jk')
                ->join('jadwal_ujikom as ju', 'ju.id', '=', 'jk.jadwal_id')
                ->when($excludeJadwalId, fn ($q) => $q->where('ju.id', '!=', $excludeJadwalId))
                ->select('jk.kelompok_id', 'ju.judul_jadwal')
                ->get();

            foreach ($rows as $row) {
                $map[(int) $row->kelompok_id] = $row->judul_jadwal;
            }

            $legacyRows = DB::table('jadwal_ujikom as ju')
                ->leftJoin('jadwal_kelompok as jk', 'jk.jadwal_id', '=', 'ju.id')
                ->whereNull('jk.id')
                ->whereNotNull('ju.kelompok_id')
                ->when($excludeJadwalId, fn ($q) => $q->where('ju.id', '!=', $excludeJadwalId))
                ->select('ju.kelompok_id', 'ju.judul_jadwal')
                ->get();

            foreach ($legacyRows as $row) {
                $kelompokId = (int) $row->kelompok_id;
                if (!isset($map[$kelompokId])) {
                    $map[$kelompokId] = $row->judul_jadwal;
                }
            }

            return $map;
        }

        $legacyRows = DB::table('jadwal_ujikom')
            ->whereNotNull('kelompok_id')
            ->when($excludeJadwalId, fn ($q) => $q->where('id', '!=', $excludeJadwalId))
            ->select('kelompok_id', 'judul_jadwal')
            ->get();

        foreach ($legacyRows as $row) {
            $map[(int) $row->kelompok_id] = $row->judul_jadwal;
        }

        return $map;
    }

    private function findKelompokBentrok(array $kelompokIds, ?int $excludeJadwalId = null): ?array
    {
        if (empty($kelompokIds)) {
            return null;
        }

        if (Schema::hasTable('jadwal_kelompok')) {
            $pivotConflict = DB::table('jadwal_kelompok as jk')
                ->join('jadwal_ujikom as ju', 'ju.id', '=', 'jk.jadwal_id')
                ->join('kelompok as k', 'k.id', '=', 'jk.kelompok_id')
                ->whereIn('jk.kelompok_id', $kelompokIds)
                ->when($excludeJadwalId, fn ($q) => $q->where('ju.id', '!=', $excludeJadwalId))
                ->select('k.nama_kelompok', 'ju.judul_jadwal')
                ->orderBy('jk.kelompok_id')
                ->first();

            if ($pivotConflict) {
                return [
                    'nama_kelompok' => $pivotConflict->nama_kelompok,
                    'judul_jadwal' => $pivotConflict->judul_jadwal,
                ];
            }

            $legacyConflict = DB::table('jadwal_ujikom as ju')
                ->join('kelompok as k', 'k.id', '=', 'ju.kelompok_id')
                ->leftJoin('jadwal_kelompok as jk', 'jk.jadwal_id', '=', 'ju.id')
                ->whereNull('jk.id')
                ->whereIn('ju.kelompok_id', $kelompokIds)
                ->when($excludeJadwalId, fn ($q) => $q->where('ju.id', '!=', $excludeJadwalId))
                ->select('k.nama_kelompok', 'ju.judul_jadwal')
                ->orderBy('ju.kelompok_id')
                ->first();

            if ($legacyConflict) {
                return [
                    'nama_kelompok' => $legacyConflict->nama_kelompok,
                    'judul_jadwal' => $legacyConflict->judul_jadwal,
                ];
            }

            return null;
        }

        $legacyConflict = DB::table('jadwal_ujikom as ju')
            ->join('kelompok as k', 'k.id', '=', 'ju.kelompok_id')
            ->whereIn('ju.kelompok_id', $kelompokIds)
            ->when($excludeJadwalId, fn ($q) => $q->where('ju.id', '!=', $excludeJadwalId))
            ->select('k.nama_kelompok', 'ju.judul_jadwal')
            ->orderBy('ju.kelompok_id')
            ->first();

        if (!$legacyConflict) {
            return null;
        }

        return [
            'nama_kelompok' => $legacyConflict->nama_kelompok,
            'judul_jadwal' => $legacyConflict->judul_jadwal,
        ];
    }

    private function collectPesertaNiks($kelompoks): array
    {
        return $kelompoks
            ->flatMap(fn ($kelompok) => $kelompok->asesis->pluck('NIK'))
            ->unique()
            ->values()
            ->all();
    }

    private function syncJadwalPeserta(int $jadwalId, array $niks, ?int $skemaId = null): void
    {
        DB::table('jadwal_peserta')->where('jadwal_id', $jadwalId)->delete();

        if (empty($niks)) {
            return;
        }

        $now = now();
        $rows = array_map(function ($nik) use ($jadwalId, $skemaId, $now) {
            // Determine the asesi's current attempt for this skema
            $attempt = 1;
            if ($skemaId) {
                $attempt = (int) DB::table('asesi_skema')
                    ->where('asesi_nik', $nik)
                    ->where('skema_id', $skemaId)
                    ->max('attempt') ?: 1;
            }

            return [
                'jadwal_id'  => $jadwalId,
                'asesi_nik'  => $nik,
                'attempt'    => $attempt,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $niks);

        DB::table('jadwal_peserta')->insert($rows);
    }

    private function syncPersetujuanAsesmenForJadwal(JadwalUjikom $jadwal, $kelompoks): void
    {
        $skema = $kelompoks->first()?->skema;
        $asesor = $kelompoks->first()?->asesors->first();

        if (!$skema) {
            return;
        }

        $asesiQuery = Asesi::query()
            ->whereIn('NIK', $kelompoks->flatMap(fn ($kelompok) => $kelompok->asesis->pluck('NIK'))->unique()->values());

        $asesiList = $asesiQuery->get(['NIK', 'nama']);
        $hasAsesiNikColumn = Schema::hasColumn('persetujuan_asesmen', 'asesi_nik');
        $now = now();

        foreach ($asesiList as $asesi) {
            // Resolve current attempt for this asesi+skema
            $currentAttempt = (int) DB::table('asesi_skema')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skema->id)
                ->max('attempt') ?: 1;

            $attributes = [
                'nomor_skema' => $skema->nomor_skema,
                'nama_asesi'  => $asesi->nama,
            ];

            if ($hasAsesiNikColumn) {
                $attributes['asesi_nik'] = $asesi->NIK;
            }

            if (Schema::hasColumn('persetujuan_asesmen', 'attempt')) {
                $attributes['attempt'] = $currentAttempt;
            }

            $defaultData = [
                'kode_form' => 'FR.AK.01.',
                'judul_form' => 'PERSETUJUAN ASESMEN DAN KERAHASIAAN',
                'pengantar' => 'Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen',
                'kategori_skema' => 'KKNI/Okupasi/Klaster',
                'judul_skema' => $skema->nama_skema,
                'tuk' => $jadwal->tuk?->nama_tuk ?? '-',
                'nama_asesor' => $asesor?->nama ?? '-',
                'bukti_verifikasi_portofolio' => false,
                'bukti_reviu_produk' => false,
                'bukti_observasi_langsung' => false,
                'bukti_kegiatan_terstruktur' => false,
                'bukti_pertanyaan_lisan' => false,
                'bukti_pertanyaan_tertulis' => false,
                'bukti_pertanyaan_wawancara' => false,
                'bukti_lainnya' => false,
                'bukti_lainnya_keterangan' => null,
                'hari_tanggal' => trim((!empty($jadwal->tanggal_mulai) ? date('d/m/Y', strtotime((string) $jadwal->tanggal_mulai)) : '') . ' s.d. ' . (!empty($jadwal->tanggal_selesai) ? date('d/m/Y', strtotime((string) $jadwal->tanggal_selesai)) : '')),
                'waktu' => trim(($jadwal->waktu_mulai ?? '') . ' - ' . ($jadwal->waktu_selesai ?? '')),
                'tuk_pelaksanaan' => $jadwal->tuk?->nama_tuk ?? '-',
                'pernyataan_asesi_1' => 'Bahwa saya telah mendapatkan penjelasan terkait hak dan prosedur banding asesmen dari asesor.',
                'pernyataan_asesor' => 'Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.',
                'pernyataan_asesi_2' => 'Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.',
                'catatan_footer' => '* Coret yang tidak perlu',
                'ttd_asesor_nama' => null,
                'ttd_asesor_tanggal' => null,
                'ttd_asesor_file' => null,
                'ttd_asesi_nama' => null,
                'ttd_asesi_tanggal' => null,
                'ttd_asesi_file' => null,
            ];

            $record = PersetujuanAsesmen::firstOrCreate($attributes, $defaultData);
            
            // Update nama_asesor, nama_asesi, dan jadwal info jika record sudah ada
            if ($record->wasRecentlyCreated === false) {
                $record->update([
                    'nama_asesor' => $asesor?->nama ?? '-',
                    'nama_asesi' => $asesi->nama,
                    'judul_skema' => $skema->nama_skema,
                    'hari_tanggal' => trim((!empty($jadwal->tanggal_mulai) ? date('d/m/Y', strtotime((string) $jadwal->tanggal_mulai)) : '') . ' s.d. ' . (!empty($jadwal->tanggal_selesai) ? date('d/m/Y', strtotime((string) $jadwal->tanggal_selesai)) : '')),
                    'waktu' => trim(($jadwal->waktu_mulai ?? '') . ' - ' . ($jadwal->waktu_selesai ?? '')),
                    'tuk_pelaksanaan' => $jadwal->tuk?->nama_tuk ?? '-',
                ]);
            }
        }
    }

    private function selectedKelompokIds(JadwalUjikom $jadwal): array
    {
        if (Schema::hasTable('jadwal_kelompok')) {
            $ids = $jadwal->kelompoks->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
            if (!empty($ids)) {
                return $ids;
            }
        }

        return $jadwal->kelompok_id ? [(int) $jadwal->kelompok_id] : [];
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $bulan  = $request->get('bulan');

        $query = JadwalUjikom::with(['tuk', 'skema', 'kelompoks'])->withCount('kelompoks');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_jadwal', 'like', "%{$search}%")
                  ->orWhereHas('tuk', fn($t) => $t->where('nama_tuk', 'like', "%{$search}%"))
                  ->orWhereHas('skema', fn($s) => $s->where('nama_skema', 'like', "%{$search}%"));
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($bulan && strpos($bulan, '-') !== false) {
            $query->where(function ($q) use ($bulan) {
                $parts = explode('-', $bulan);
                $year = $parts[0];
                $month = $parts[1];
                $q->where(function ($sub) use ($year, $month) {
                    $sub->whereYear('tanggal_mulai', $year)
                        ->whereMonth('tanggal_mulai', $month);
                })->orWhere(function ($sub) use ($year, $month) {
                    $sub->whereYear('tanggal_selesai', $year)
                        ->whereMonth('tanggal_selesai', $month);
                });
            });
        }

        $jadwals = $query->orderBy('tanggal_mulai', 'desc')->orderBy('waktu_mulai')->paginate(10);

        $stats = [
            'total'       => JadwalUjikom::count(),
            'dijadwalkan' => JadwalUjikom::where('status', 'dijadwalkan')->count(),
            'berlangsung' => JadwalUjikom::where('status', 'berlangsung')->count(),
            'selesai'     => JadwalUjikom::where('status', 'selesai')->count(),
            'bulan_ini'   => JadwalUjikom::where(function ($q) {
                $now = now();
                $q->where(function ($sub) use ($now) {
                    $sub->whereYear('tanggal_mulai', $now->year)
                        ->whereMonth('tanggal_mulai', $now->month);
                })->orWhere(function ($sub) use ($now) {
                    $sub->whereYear('tanggal_selesai', $now->year)
                        ->whereMonth('tanggal_selesai', $now->month);
                });
            })->count(),
        ];

        // If AJAX request, return table rows and pagination fragments
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'rows' => view('admin.jadwal-ujikom.partials.table-rows', compact('jadwals', 'search', 'status', 'bulan'))->render(),
                'pagination' => $jadwals->hasPages() ? (string) $jadwals->links() : '',
            ]);
        }

        return view('admin.jadwal-ujikom.index', compact('jadwals', 'stats', 'search', 'status', 'bulan'));
    }

    public function show($id)
    {
        $jadwal = JadwalUjikom::with([
            'tuk',
            'skema',
            'asesor',
            'kelompoks.asesis.jurusan',
            'kelompoks.asesors',
            'peserta.jurusan',
        ])->findOrFail($id);

        return view('admin.jadwal-ujikom.show', compact('jadwal'));
    }

    public function create(Request $request)
    {
        $tuks      = Tuk::where('status', 'aktif')->orderBy('nama_tuk')->get();
        $kelompoks = Kelompok::with(['skema', 'asesors', 'asesis'])->orderBy('nama_kelompok')->get();

        $kelompokData = $this->buildKelompokData($kelompoks);
        $kelompokScheduleMap = $this->getKelompokScheduleMap();

        $prefillKelompokId = (int) $request->get('kelompok_id');
        $prefillKelompokIds = [];
        $prefillJudulJadwal = null;

        if ($prefillKelompokId > 0) {
            $prefillKelompok = $kelompoks->firstWhere('id', $prefillKelompokId);
            $isScheduled = isset($kelompokScheduleMap[$prefillKelompokId]);

            if ($prefillKelompok && !$isScheduled) {
                $prefillKelompokIds = [$prefillKelompokId];
                $prefillJudulJadwal = 'Ujikom ' . $prefillKelompok->nama_kelompok;
            }
        }

        return view('admin.jadwal-ujikom.create', compact(
            'tuks',
            'kelompoks',
            'kelompokData',
            'kelompokScheduleMap',
            'prefillKelompokIds',
            'prefillJudulJadwal'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_jadwal'    => 'required|string|max:255',
            'kelompok_ids'    => 'required|array|min:1',
            'kelompok_ids.*'  => 'required|distinct|exists:kelompok,id',
            'tuk_id'          => 'required|exists:tuk,id',
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai'     => 'required',
            'waktu_selesai'   => 'required|after:waktu_mulai',
            'status'          => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan',
            'keterangan'      => 'nullable|string',
        ], [
            'judul_jadwal.required'          => 'Judul jadwal wajib diisi.',
            'kelompok_ids.required'          => 'Kelompok wajib dipilih.',
            'kelompok_ids.array'             => 'Format kelompok tidak valid.',
            'kelompok_ids.min'               => 'Pilih minimal satu kelompok.',
            'kelompok_ids.*.exists'          => 'Kelompok tidak ditemukan.',
            'kelompok_ids.*.distinct'        => 'Kelompok yang dipilih tidak boleh duplikat.',
            'tuk_id.required'                => 'TUK wajib dipilih.',
            'tuk_id.exists'                  => 'TUK tidak ditemukan.',
            'tanggal_mulai.required'         => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.after_or_equal'   => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'tanggal_selesai.required'       => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'waktu_mulai.required'           => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required'         => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after'            => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $kelompokIds = $this->normalizeKelompokIds($validated['kelompok_ids']);

        if (!Schema::hasTable('jadwal_kelompok') && count($kelompokIds) > 1) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'kelompok_ids' => 'Fitur multi-kelompok belum aktif. Jalankan migrasi terbaru terlebih dahulu.',
                ]);
        }

        $kelompokBentrok = $this->findKelompokBentrok($kelompokIds);
        if ($kelompokBentrok) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'kelompok_ids' => 'Kelompok ' . $kelompokBentrok['nama_kelompok'] . ' sudah terdaftar pada jadwal (' . $kelompokBentrok['judul_jadwal'] . ').',
                ]);
        }

        $kelompoks = $this->loadKelompoksByIds($kelompokIds);

        if ($kelompoks->count() !== count($kelompokIds)) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'kelompok_ids' => 'Sebagian kelompok tidak ditemukan.',
                ]);
        }

        $skemaGroups = $kelompoks->map(fn ($k) => (string) ($k->skema_id ?? 'null'))->unique();
        if ($skemaGroups->count() > 1) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'kelompok_ids' => 'Semua kelompok dalam satu jadwal harus berada pada skema yang sama.',
                ]);
        }

        $asesorGroups = $kelompoks
            ->map(fn ($k) => $k->asesors->first()?->ID_asesor)
            ->map(fn ($id) => (string) ($id ?? 'null'))
            ->unique();

        if ($asesorGroups->count() > 1) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'kelompok_ids' => 'Semua kelompok dalam satu jadwal harus memiliki asesor yang sama.',
                ]);
        }

        $firstKelompok = $kelompoks->first();
        $validated['skema_id'] = $firstKelompok?->skema_id;
        $validated['asesor_id'] = $firstKelompok?->asesors->first()?->ID_asesor;
        $validated['kelompok_id'] = $kelompokIds[0] ?? null;

        $jadwalBentrok = $this->findBentrokJadwalAsesor(
            $validated['asesor_id'],
            $validated['tanggal_mulai'],
            $validated['tanggal_selesai']
        );

        if ($jadwalBentrok) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'tanggal_mulai' => 'Jadwal asesor bentrok dengan jadwal (' . $jadwalBentrok->judul_jadwal . ').',
                ]);
        }

        $niks = $this->collectPesertaNiks($kelompoks);
        $validated['peserta_terdaftar'] = count($niks);
        $validated['kuota']             = max(1, count($niks));
        unset($validated['kelompok_ids']);

        $jadwal = JadwalUjikom::create($validated);

        if (Schema::hasTable('jadwal_kelompok')) {
            $jadwal->kelompoks()->sync($kelompokIds);
        }

        $skemaId = (int) ($kelompoks->first()?->skema?->id ?? 0) ?: null;
        $this->syncJadwalPeserta($jadwal->id, $niks, $skemaId);
        $this->syncPersetujuanAsesmenForJadwal($jadwal, $kelompoks);

        return redirect()->route('admin.jadwal-ujikom.index')
            ->with('success', 'Jadwal Ujikom berhasil ditambahkan!');
    }

    public function getAsesiBySkema(Request $request)
    {
        $skemaId = $request->query('skema_id');
        if (!$skemaId) {
            return response()->json([]);
        }

        $asesiList = DB::table('asesi_skema')
            ->join('asesi', 'asesi.NIK', '=', 'asesi_skema.asesi_nik')
            ->where('asesi_skema.skema_id', $skemaId)
            ->where('asesi_skema.rekomendasi', 'lanjut')
            ->select(
                'asesi.NIK',
                'asesi.nama',
                'asesi.no_reg',
                'asesi_skema.catatan_asesor',
                'asesi_skema.reviewed_at'
            )
            ->orderBy('asesi.nama')
            ->get();

        return response()->json($asesiList);
    }

    public function edit($id)
    {
        $jadwal    = JadwalUjikom::with(['peserta', 'kelompok', 'kelompoks'])->findOrFail($id);
        $tuks      = Tuk::where('status', 'aktif')->orderBy('nama_tuk')->get();
        $kelompoks = Kelompok::with(['skema', 'asesors', 'asesis'])->orderBy('nama_kelompok')->get();

        $kelompokData = $this->buildKelompokData($kelompoks);
        $kelompokScheduleMap = $this->getKelompokScheduleMap($jadwal->id);

        $selectedKelompokIds = old('kelompok_ids');
        if (!is_array($selectedKelompokIds)) {
            $selectedKelompokIds = $this->selectedKelompokIds($jadwal);
        }
        $selectedKelompokIds = $this->normalizeKelompokIds($selectedKelompokIds);

        return view('admin.jadwal-ujikom.edit', compact('jadwal', 'tuks', 'kelompoks', 'kelompokData', 'selectedKelompokIds', 'kelompokScheduleMap'));
    }

    public function validateDates(Request $request)
    {
        $data = $request->only(['tanggal_mulai', 'tanggal_selesai', 'skip_today']);

        $skipToday = filter_var($data['skip_today'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $rules = [
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ];

        if (!$skipToday) {
            $rules['tanggal_mulai'] .= '|after_or_equal:today';
        }

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return response()->json([
                'valid' => false,
                'errors' => $v->errors()->all(),
            ]);
        }

        return response()->json(['valid' => true]);
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalUjikom::findOrFail($id);

        $validated = $request->validate([
            'judul_jadwal'    => 'required|string|max:255',
            'kelompok_ids'    => 'required|array|min:1',
            'kelompok_ids.*'  => 'required|distinct|exists:kelompok,id',
            'tuk_id'          => 'required|exists:tuk,id',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai'     => 'required',
            'waktu_selesai'   => 'required|after:waktu_mulai',
            'status'          => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan',
            'keterangan'      => 'nullable|string',
        ], [
            'judul_jadwal.required'          => 'Judul jadwal wajib diisi.',
            'kelompok_ids.required'          => 'Kelompok wajib dipilih.',
            'kelompok_ids.array'             => 'Format kelompok tidak valid.',
            'kelompok_ids.min'               => 'Pilih minimal satu kelompok.',
            'kelompok_ids.*.exists'          => 'Kelompok tidak ditemukan.',
            'kelompok_ids.*.distinct'        => 'Kelompok yang dipilih tidak boleh duplikat.',
            'tuk_id.required'                => 'TUK wajib dipilih.',
            'tanggal_mulai.required'         => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required'       => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'waktu_mulai.required'           => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required'         => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after'            => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $today = now()->toDateString();
        $newTanggalMulai = date('Y-m-d', strtotime($validated['tanggal_mulai']));
        $currentTanggalMulai = $jadwal->tanggal_mulai
            ? date('Y-m-d', strtotime((string) $jadwal->tanggal_mulai))
            : null;

        if ($newTanggalMulai !== $currentTanggalMulai && $newTanggalMulai < $today) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'tanggal_mulai' => 'Tanggal mulai tidak boleh sebelum hari ini.',
                ]);
        }

        $kelompokIds = $this->normalizeKelompokIds($validated['kelompok_ids']);

        if (!Schema::hasTable('jadwal_kelompok') && count($kelompokIds) > 1) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'kelompok_ids' => 'Fitur multi-kelompok belum aktif. Jalankan migrasi terbaru terlebih dahulu.',
                ]);
        }

        $kelompokBentrok = $this->findKelompokBentrok($kelompokIds, $jadwal->id);
        if ($kelompokBentrok) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'kelompok_ids' => 'Kelompok ' . $kelompokBentrok['nama_kelompok'] . ' sudah terdaftar pada jadwal (' . $kelompokBentrok['judul_jadwal'] . ').',
                ]);
        }

        $kelompoks = $this->loadKelompoksByIds($kelompokIds);

        if ($kelompoks->count() !== count($kelompokIds)) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'kelompok_ids' => 'Sebagian kelompok tidak ditemukan.',
                ]);
        }

        $skemaGroups = $kelompoks->map(fn ($k) => (string) ($k->skema_id ?? 'null'))->unique();
        if ($skemaGroups->count() > 1) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'kelompok_ids' => 'Semua kelompok dalam satu jadwal harus berada pada skema yang sama.',
                ]);
        }

        $asesorGroups = $kelompoks
            ->map(fn ($k) => $k->asesors->first()?->ID_asesor)
            ->map(fn ($id) => (string) ($id ?? 'null'))
            ->unique();

        if ($asesorGroups->count() > 1) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'kelompok_ids' => 'Semua kelompok dalam satu jadwal harus memiliki asesor yang sama.',
                ]);
        }

        $firstKelompok = $kelompoks->first();
        $validated['skema_id'] = $firstKelompok?->skema_id;
        $validated['asesor_id'] = $firstKelompok?->asesors->first()?->ID_asesor;
        $validated['kelompok_id'] = $kelompokIds[0] ?? null;

        $jadwalBentrok = $this->findBentrokJadwalAsesor(
            $validated['asesor_id'],
            $validated['tanggal_mulai'],
            $validated['tanggal_selesai'],
            $jadwal->id
        );

        if ($jadwalBentrok) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'tanggal_mulai' => 'Jadwal asesor bentrok dengan jadwal (' . $jadwalBentrok->judul_jadwal . ').',
                ]);
        }

        $niks = $this->collectPesertaNiks($kelompoks);
        $validated['kuota']             = max(1, count($niks));
        $validated['peserta_terdaftar'] = count($niks);
        unset($validated['kelompok_ids']);

        $jadwal->update($validated);

        if (Schema::hasTable('jadwal_kelompok')) {
            $jadwal->kelompoks()->sync($kelompokIds);
        }

        $skemaId = (int) ($kelompoks->first()?->skema?->id ?? 0) ?: null;
        $this->syncJadwalPeserta($jadwal->id, $niks, $skemaId);
        $this->syncPersetujuanAsesmenForJadwal($jadwal, $kelompoks);

        return redirect()->route('admin.jadwal-ujikom.index')
            ->with('success', 'Jadwal Ujikom berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jadwal = JadwalUjikom::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal-ujikom.index')->with('success', 'Jadwal Ujikom berhasil dihapus!');
    }

    public function updateStatus(Request $request, $id)
    {
        $jadwal = JadwalUjikom::findOrFail($id);
        $request->validate(['status' => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan']);
        $jadwal->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status jadwal berhasil diperbarui!');
    }
}
