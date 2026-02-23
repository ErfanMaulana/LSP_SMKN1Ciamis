<?php

namespace App\Http\Controllers;

use App\Mail\AsesiApprovedMail;
use App\Mail\AsesiRejectedMail;
use App\Models\Asesi;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AsesiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asesi = Asesi::with('jurusan')->paginate(10);
        return view('admin.asesi.index', compact('asesi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusan = Jurusan::all();
        return view('admin.asesi.create', compact('jurusan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'NIK' => 'required|string|max:255|unique:asesi,NIK',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'ID_jurusan' => 'required|exists:jurusan,ID_jurusan',
            'kelas' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'kebangsaan' => 'nullable|string|max:100',
            'kode_kota' => 'nullable|string|max:50',
            'kode_provinsi' => 'nullable|string|max:50',
            'telepon_rumah' => 'nullable|string|max:20',
            'telepon_hp' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'kode_kementrian' => 'nullable|string|max:50',
            'kode_anggaran' => 'nullable|string|max:50',
        ]);

        Asesi::create($validated);

        return redirect()->route('admin.asesi.index')->with('success', 'Data Asesi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik)
    {
        $asesi = Asesi::findOrFail($nik);
        $jurusan = Jurusan::all();
        return view('admin.asesi.edit', compact('asesi', 'jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $asesi = Asesi::findOrFail($nik);

        $validated = $request->validate([
            'NIK' => 'required|string|max:255|unique:asesi,NIK,' . $nik . ',NIK',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'ID_jurusan' => 'required|exists:jurusan,ID_jurusan',
            'kelas' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'kebangsaan' => 'nullable|string|max:100',
            'kode_kota' => 'nullable|string|max:50',
            'kode_provinsi' => 'nullable|string|max:50',
            'telepon_rumah' => 'nullable|string|max:20',
            'telepon_hp' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'kode_kementrian' => 'nullable|string|max:50',
            'kode_anggaran' => 'nullable|string|max:50',
        ]);

        $asesi->update($validated);

        return redirect()->route('admin.asesi.index')->with('success', 'Data Asesi berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $asesi = Asesi::findOrFail($nik);
        $asesi->delete();

        return redirect()->route('admin.asesi.index')->with('success', 'Data Asesi berhasil dihapus!');
    }

    /**
     * Display the verification list.
     */
    public function verifikasi(Request $request)
    {
        $status = $request->get('status', 'pending');
        $search = $request->get('search');

        $query = Asesi::with('jurusan');

        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('NIK', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $asesi = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'pending'  => Asesi::where('status', 'pending')->count(),
            'approved' => Asesi::where('status', 'approved')->count(),
            'rejected' => Asesi::where('status', 'rejected')->count(),
            'total'    => Asesi::count(),
        ];

        return view('admin.asesi.verifikasi', compact('asesi', 'status', 'counts'));
    }

    /**
     * Show a single asesi for review.
     */
    public function showVerifikasi($nik)
    {
        $asesi = Asesi::with(['jurusan', 'transkripNilai', 'identitasPribadi', 'buktiKompetensi', 'verifiedBy'])->findOrFail($nik);
        return view('admin.asesi.verifikasi-detail', compact('asesi'));
    }

    /**
     * Approve an asesi registration.
     */
    public function approve($nik)
    {
        $asesi = Asesi::findOrFail($nik);

        $asesi->update([
            'status'      => 'approved',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        if ($asesi->email) {
            Mail::to($asesi->email)->send(new AsesiApprovedMail($asesi));
        }

        return redirect()->route('admin.asesi.verifikasi.show', $nik)
            ->with('success', 'Pendaftaran asesi berhasil disetujui.');
    }

    /**
     * Reject an asesi registration.
     */
    public function reject(Request $request, $nik)
    {
        $request->validate([
            'catatan_admin' => 'required|string',
        ]);

        $asesi = Asesi::findOrFail($nik);

        $asesi->update([
            'status'        => 'rejected',
            'catatan_admin' => $request->catatan_admin,
            'verified_at'   => now(),
            'verified_by'   => auth()->id(),
        ]);

        if ($asesi->email) {
            Mail::to($asesi->email)->send(new AsesiRejectedMail($asesi));
        }

        return redirect()->route('admin.asesi.verifikasi.show', $nik)
            ->with('success', 'Pendaftaran asesi berhasil ditolak.');
    }
}
