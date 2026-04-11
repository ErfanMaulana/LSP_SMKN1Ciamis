<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\PersetujuanAsesmen;
use App\Models\Skema;
use App\Models\Tuk;
use Illuminate\Http\Request;

class PersetujuanAsesmenController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $items = PersetujuanAsesmen::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul_skema', 'like', "%{$search}%")
                        ->orWhere('nomor_skema', 'like', "%{$search}%")
                        ->orWhere('nama_asesor', 'like', "%{$search}%")
                        ->orWhere('nama_asesi', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.persetujuan-asesmen.index', compact('items', 'search'));
    }

    public function create()
    {
        $defaults = $this->defaultContent();
        $skemaList = Skema::query()
            ->orderBy('nama_skema')
            ->get(['id', 'nama_skema', 'nomor_skema']);
        $tukList = Tuk::query()
            ->orderBy('nama_tuk')
            ->get(['id', 'nama_tuk', 'tipe_tuk', 'kota', 'status']);

        return view('admin.persetujuan-asesmen.create', compact('defaults', 'skemaList', 'tukList'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        PersetujuanAsesmen::create($data);

        return redirect()->route('admin.persetujuan-asesmen.index')
            ->with('success', 'Data persetujuan asesmen berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);
        return view('admin.persetujuan-asesmen.show', compact('item'));
    }

    public function edit($id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);
        $skemaList = Skema::query()
            ->orderBy('nama_skema')
            ->get(['id', 'nama_skema', 'nomor_skema']);
        $tukList = Tuk::query()
            ->orderBy('nama_tuk')
            ->get(['id', 'nama_tuk', 'tipe_tuk', 'kota', 'status']);

        return view('admin.persetujuan-asesmen.edit', compact('item', 'skemaList', 'tukList'));
    }

    public function update(Request $request, $id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);
        $data = $this->validatedData($request);
        $item->update($data);

        return redirect()->route('admin.persetujuan-asesmen.index')
            ->with('success', 'Data persetujuan asesmen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.persetujuan-asesmen.index')
            ->with('success', 'Data persetujuan asesmen berhasil dihapus.');
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

        return response()->json([
            'asesi' => $asesiList,
            'asesor' => $asesorList,
        ]);
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
}
