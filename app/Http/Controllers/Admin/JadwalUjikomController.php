<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalUjikom;
use App\Models\Kelompok;
use App\Models\Tuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalUjikomController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $bulan  = $request->get('bulan');

        $query = JadwalUjikom::with(['tuk', 'skema']);

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

        if ($bulan) {
            $query->where(function ($q) use ($bulan) {
                $q->whereRaw("DATE_FORMAT(tanggal_mulai, '%Y-%m') = ?", [$bulan])
                  ->orWhereRaw("DATE_FORMAT(tanggal_selesai, '%Y-%m') = ?", [$bulan]);
            });
        }

        $jadwals = $query->orderBy('tanggal_mulai', 'desc')->orderBy('waktu_mulai')->paginate(10);

        $stats = [
            'total'       => JadwalUjikom::count(),
            'dijadwalkan' => JadwalUjikom::where('status', 'dijadwalkan')->count(),
            'berlangsung' => JadwalUjikom::where('status', 'berlangsung')->count(),
            'selesai'     => JadwalUjikom::where('status', 'selesai')->count(),
            'bulan_ini'   => JadwalUjikom::where(function ($q) {
                $bulan = now()->format('Y-m');
                $q->whereRaw("DATE_FORMAT(tanggal_mulai, '%Y-%m') = ?", [$bulan])
                  ->orWhereRaw("DATE_FORMAT(tanggal_selesai, '%Y-%m') = ?", [$bulan]);
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

    public function create()
    {
        $tuks      = Tuk::where('status', 'aktif')->orderBy('nama_tuk')->get();
        $kelompoks = Kelompok::with(['skema', 'asesors', 'asesis'])->orderBy('nama_kelompok')->get();

        // Build JS-friendly data: kelompok_id => { skema, asesor_nama, asesi_count, asesi_niks }
        $kelompokData = $kelompoks->mapWithKeys(fn($k) => [
            $k->id => [
                'nama_skema'  => $k->skema?->nama_skema ?? '-',
                'asesor_nama' => $k->asesors->first()?->nama ?? '-',
                'asesi_count' => $k->asesis->count(),
                'asesi_niks'  => $k->asesis->pluck('NIK')->values(),
            ]
        ]);

        return view('admin.jadwal-ujikom.create', compact('tuks', 'kelompoks', 'kelompokData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_jadwal'    => 'required|string|max:255',
            'kelompok_id'     => 'required|exists:kelompok,id',
            'tuk_id'          => 'required|exists:tuk,id',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai'     => 'required',
            'waktu_selesai'   => 'required|after:waktu_mulai',
            'status'          => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan',
            'keterangan'      => 'nullable|string',
        ], [
            'judul_jadwal.required'          => 'Judul jadwal wajib diisi.',
            'kelompok_id.required'           => 'Kelompok wajib dipilih.',
            'kelompok_id.exists'             => 'Kelompok tidak ditemukan.',
            'tuk_id.required'                => 'TUK wajib dipilih.',
            'tuk_id.exists'                  => 'TUK tidak ditemukan.',
            'tanggal_mulai.required'         => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required'       => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'waktu_mulai.required'           => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required'         => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after'            => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        // Derive skema, asesor, and peserta from kelompok
        $kelompok = Kelompok::with(['skema', 'asesors', 'asesis'])->findOrFail($validated['kelompok_id']);
        $validated['skema_id']  = $kelompok->skema_id;
        $validated['asesor_id'] = $kelompok->asesors->first()?->ID_asesor;

        $niks = $kelompok->asesis->pluck('NIK')->toArray();
        $validated['peserta_terdaftar'] = count($niks);
        $validated['kuota']             = max(1, count($niks));

        $jadwal = JadwalUjikom::create($validated);

        if (!empty($niks)) {
            $now  = now();
            $rows = array_map(fn($nik) => [
                'jadwal_id'  => $jadwal->id,
                'asesi_nik'  => $nik,
                'created_at' => $now,
                'updated_at' => $now,
            ], $niks);
            DB::table('jadwal_peserta')->insert($rows);
        }

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
        $jadwal    = JadwalUjikom::with(['peserta', 'kelompok'])->findOrFail($id);
        $tuks      = Tuk::where('status', 'aktif')->orderBy('nama_tuk')->get();
        $kelompoks = Kelompok::with(['skema', 'asesors', 'asesis'])->orderBy('nama_kelompok')->get();

        $kelompokData = $kelompoks->mapWithKeys(fn($k) => [
            $k->id => [
                'nama_skema'  => $k->skema?->nama_skema ?? '-',
                'asesor_nama' => $k->asesors->first()?->nama ?? '-',
                'asesi_count' => $k->asesis->count(),
                'asesi_niks'  => $k->asesis->pluck('NIK')->values(),
            ]
        ]);

        return view('admin.jadwal-ujikom.edit', compact('jadwal', 'tuks', 'kelompoks', 'kelompokData'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalUjikom::findOrFail($id);

        $validated = $request->validate([
            'judul_jadwal'    => 'required|string|max:255',
            'kelompok_id'     => 'required|exists:kelompok,id',
            'tuk_id'          => 'required|exists:tuk,id',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai'     => 'required',
            'waktu_selesai'   => 'required|after:waktu_mulai',
            'status'          => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan',
            'keterangan'      => 'nullable|string',
        ], [
            'judul_jadwal.required'          => 'Judul jadwal wajib diisi.',
            'kelompok_id.required'           => 'Kelompok wajib dipilih.',
            'kelompok_id.exists'             => 'Kelompok tidak ditemukan.',
            'tuk_id.required'                => 'TUK wajib dipilih.',
            'tanggal_mulai.required'         => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required'       => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'waktu_mulai.required'           => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required'         => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after'            => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        // Derive skema, asesor, and peserta from kelompok
        $kelompok = Kelompok::with(['skema', 'asesors', 'asesis'])->findOrFail($validated['kelompok_id']);
        $validated['skema_id']  = $kelompok->skema_id;
        $validated['asesor_id'] = $kelompok->asesors->first()?->ID_asesor;

        $niks = $kelompok->asesis->pluck('NIK')->toArray();
        $validated['kuota']             = max(1, count($niks));
        $validated['peserta_terdaftar'] = count($niks);

        $jadwal->update($validated);

        // Sync peserta
        DB::table('jadwal_peserta')->where('jadwal_id', $jadwal->id)->delete();
        if (!empty($niks)) {
            $now  = now();
            $rows = array_map(fn($nik) => [
                'jadwal_id'  => $jadwal->id,
                'asesi_nik'  => $nik,
                'created_at' => $now,
                'updated_at' => $now,
            ], $niks);
            DB::table('jadwal_peserta')->insert($rows);
        }

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
