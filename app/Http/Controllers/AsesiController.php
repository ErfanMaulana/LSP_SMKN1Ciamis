<?php

namespace App\Http\Controllers;

use App\Models\Asesi;
use App\Models\Jurusan;
use Illuminate\Http\Request;

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
     * Display a listing of asesi for verification.
     */
    public function verifikasi(Request $request)
    {
        // Get the filter status from query parameter
        $status = $request->query('status');
        
        // Get search query
        $search = $request->query('search');
        
        // Build the query
        $query = Asesi::with('jurusan');
        
        // Apply status filter if provided
        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }
        
        // Apply search filter if provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('NIK', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        // Get paginated results
        $asesi = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get counts for stats
        $counts = [
            'pending' => Asesi::where('status', 'pending')->count(),
            'approved' => Asesi::where('status', 'approved')->count(),
            'rejected' => Asesi::where('status', 'rejected')->count(),
            'total' => Asesi::count(),
        ];
        
        return view('admin.asesi.verifikasi', compact('asesi', 'status', 'counts'));
    }

    /**
     * Show the verification detail page for a specific asesi.
     */
    public function showVerifikasi($nik)
    {
        $asesi = Asesi::with(['jurusan', 'buktiPendukung', 'verifiedBy'])
            ->findOrFail($nik);
        
        return view('admin.asesi.verifikasi-detail', compact('asesi'));
    }

    /**
     * Approve an asesi registration.
     */
    public function approve(Request $request, $nik)
    {
        $asesi = Asesi::findOrFail($nik);
        
        // Generate unique no_reg based on birth date and index
        $noReg = $this->generateNoReg($asesi);
        
        // Create plain password (same as no_reg)
        $plainPassword = $noReg;
        
        // Update asesi status and no_reg
        $asesi->update([
            'no_reg' => $noReg,
            'status' => 'approved',
            'catatan_admin' => $request->input('catatan_admin'),
            'verified_at' => now(),
            'verified_by' => auth('admin')->id(),
        ]);
        
        // Create account for asesi to login
        \App\Models\Account::create([
            'no_reg' => $noReg,
            'password' => $plainPassword, // Will be automatically hashed by the model
            'role' => 'asesi',
        ]);
        
        // Send approval email with login credentials if asesi has email
        if ($asesi->email) {
            try {
                \Mail::to($asesi->email)->send(new \App\Mail\AsesiApprovedMail($asesi, $noReg, $plainPassword));
            } catch (\Exception $e) {
                // Log error but don't fail the approval
                \Log::error('Failed to send approval email: ' . $e->getMessage());
            }
        }
        
        return redirect()->route('admin.asesi.verifikasi')
            ->with('success', 'Pendaftaran asesi ' . $asesi->nama . ' telah disetujui dan akun login telah dibuat!');
    }

    /**
     * Generate unique no_reg for asesi based on birth date and sequential index.
     * Format: YYYYMMDD + 3-digit sequential number (e.g., 20000101001)
     */
    private function generateNoReg(Asesi $asesi): string
    {
        // Get birth date or use current date if not available
        if ($asesi->tanggal_lahir) {
            // Convert to date string: YYYYMMDD
            $dateObj = $asesi->tanggal_lahir;
            $birthDate = date('Ymd', strtotime($dateObj));
        } else {
            $birthDate = date('Ymd');
        }
        
        // Find the highest existing index for this birth date
        $prefix = $birthDate;
        $lastNoReg = \App\Models\Asesi::where('no_reg', 'like', $prefix . '%')
            ->orderBy('no_reg', 'desc')
            ->value('no_reg');
        
        if ($lastNoReg) {
            // Extract the sequential number and increment
            $lastIndex = (int) substr($lastNoReg, -3);
            $newIndex = $lastIndex + 1;
        } else {
            // First registration for this birth date
            $newIndex = 1;
        }
        
        // Format: YYYYMMDD + 3-digit index (001, 002, etc.)
        return $prefix . str_pad($newIndex, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Reject an asesi registration.
     */
    public function reject(Request $request, $nik)
    {
        $request->validate([
            'catatan_admin' => 'required|string',
        ], [
            'catatan_admin.required' => 'Catatan penolakan wajib diisi.',
        ]);
        
        $asesi = Asesi::findOrFail($nik);
        
        $asesi->update([
            'status' => 'rejected',
            'catatan_admin' => $request->input('catatan_admin'),
            'verified_at' => now(),
            'verified_by' => auth('admin')->id(),
        ]);
        
        // Send rejection email if asesi has email
        if ($asesi->email) {
            try {
                \Mail::to($asesi->email)->send(new \App\Mail\AsesiRejectedMail($asesi));
            } catch (\Exception $e) {
                // Log error but don't fail the rejection
                \Log::error('Failed to send rejection email: ' . $e->getMessage());
            }
        }
        
        return redirect()->route('admin.asesi.verifikasi')
            ->with('success', 'Pendaftaran asesi ' . $asesi->nama . ' telah ditolak.');
    }
}
