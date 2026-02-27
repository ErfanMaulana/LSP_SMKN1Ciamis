<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\JadwalUjikom;
use App\Models\Tuk;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalUjikomController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $bulan  = $request->get('bulan', now()->format('Y-m'));

        $query = JadwalUjikom::with(['tuk', 'skema']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_jadwal', 'like', "%{$search}%")
                  ->orWhereHas('tuk', fn($t) => $t->where('nama_tuk', 'like', "%{$search}%"))
                  ->orWhereHas('skema', fn($s) => $s->where('nama_skema', 'like', "%{$search}%"));
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($bulan) {
            $query->where(function ($q) use ($bulan) {
                $q->whereRaw("DATE_FORMAT(tanggal_mulai, '%Y-%m') = ?", [$bulan])
                  ->orWhereRaw("DATE_FORMAT(tanggal_selesai, '%Y-%m') = ?", [$bulan]);
            });
        }

        $jadwals = $query->orderBy('tanggal_mulai', 'desc')->orderBy('waktu_mulai')->paginate(10)->withQueryString();

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

        return view('admin.jadwal-ujikom.index', compact('jadwals', 'stats', 'search', 'status', 'bulan'));
    }

    public function create()
    {
        $tuks   = Tuk::where('status', 'aktif')->orderBy('nama_tuk')->get();
        $skemas = Skema::orderBy('nama_skema')->get();
        return view('admin.jadwal-ujikom.create', compact('tuks', 'skemas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_jadwal'  => 'required|string|max:255',
            'tuk_id'        => 'required|exists:tuk,id',
            'skema_id'      => 'required|exists:skemas,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'kuota'         => 'required|integer|min:1',
            'status'        => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan',
            'keterangan'    => 'nullable|string',
            'peserta_niks'   => 'nullable|array',
            'peserta_niks.*' => 'exists:asesi,NIK',
        ], [
            'judul_jadwal.required'   => 'Judul jadwal wajib diisi.',
            'tuk_id.required'         => 'TUK wajib dipilih.',
            'tuk_id.exists'           => 'TUK tidak ditemukan.',
            'skema_id.required'       => 'Skema wajib dipilih.',
            'skema_id.exists'         => 'Skema tidak ditemukan.',
            'tanggal_mulai.required'  => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'waktu_mulai.required'    => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required'  => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after'     => 'Waktu selesai harus setelah waktu mulai.',
            'kuota.required'          => 'Kuota wajib diisi.',
            'kuota.min'               => 'Kuota minimal 1.',
        ]);

        $niks = $request->input('peserta_niks', []);
        $validated['peserta_terdaftar'] = count($niks);

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
        $jadwal       = JadwalUjikom::with('peserta')->findOrFail($id);
        $tuks         = Tuk::where('status', 'aktif')->orderBy('nama_tuk')->get();
        $skemas       = Skema::orderBy('nama_skema')->get();
        $selectedNiks = $jadwal->peserta->pluck('NIK')->toArray();
        return view('admin.jadwal-ujikom.edit', compact('jadwal', 'tuks', 'skemas', 'selectedNiks'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalUjikom::findOrFail($id);

        $validated = $request->validate([
            'judul_jadwal'      => 'required|string|max:255',
            'tuk_id'            => 'required|exists:tuk,id',
            'skema_id'          => 'required|exists:skemas,id',
            'tanggal_mulai'     => 'required|date',
            'tanggal_selesai'   => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai'       => 'required',
            'waktu_selesai'     => 'required|after:waktu_mulai',
            'kuota'             => 'required|integer|min:1',
            'status'            => 'required|in:dijadwalkan,berlangsung,selesai,dibatalkan',
            'keterangan'        => 'nullable|string',
            'peserta_niks'      => 'nullable|array',
            'peserta_niks.*'    => 'exists:asesi,NIK',
        ], [
            'judul_jadwal.required'  => 'Judul jadwal wajib diisi.',
            'tuk_id.required'        => 'TUK wajib dipilih.',
            'skema_id.required'      => 'Skema wajib dipilih.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'waktu_mulai.required'   => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after'    => 'Waktu selesai harus setelah waktu mulai.',
            'kuota.required'         => 'Kuota wajib diisi.',
            'kuota.min'              => 'Kuota minimal 1.',
        ]);

        $niks = $request->input('peserta_niks', []);
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
