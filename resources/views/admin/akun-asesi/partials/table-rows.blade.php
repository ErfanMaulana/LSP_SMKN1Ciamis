@forelse($accounts as $index => $account)
    @php
        $asesi = \App\Models\Asesi::where('NIK', $account->NIK)->first();
        $statusLabel = 'Belum Mendaftar';
        $statusClass = 'badge-secondary';
        $statusIcon  = 'bi-dash-circle';

        if ($asesi) {
            if ($asesi->status === 'approved') {
                $statusLabel = 'Terverifikasi';
                $statusClass = 'badge-approved';
                $statusIcon  = 'bi-check-circle-fill';
            } elseif ($asesi->status === 'pending') {
                $statusLabel = 'Menunggu Verifikasi';
                $statusClass = 'badge-pending';
                $statusIcon  = 'bi-clock';
            } elseif ($asesi->status === 'rejected') {
                $statusLabel = 'Ditolak';
                $statusClass = 'badge-rejected';
                $statusIcon  = 'bi-x-circle-fill';
            } else {
                $statusLabel = 'Sudah Daftar';
                $statusClass = 'badge-info';
                $statusIcon  = 'bi-person-check';
            }
        }
    @endphp
    <tr>
        <td style="text-align:center;">{{ ($accounts->currentPage() - 1) * $accounts->perPage() + $index + 1 }}</td>
        <td>
            <span class="code-badge">{{ $account->NIK }}</span>
        </td>
        <td><strong>{{ $account->nama ?? ($asesi->nama ?? '-') }}</strong></td>
        <td>
            <span class="badge {{ $statusClass }}">
                <i class="bi {{ $statusIcon }}"></i>
                {{ $statusLabel }}
            </span>
        </td>
        <td>{{ $account->created_at?->locale('id') ? \Carbon\Carbon::parse($account->created_at)->locale('id')->translatedFormat('d M Y H:i') : '-' }}</td>
        <td style="text-align:center;">
            <div class="actions-wrapper">
                <button class="action-btn" onclick="toggleActionsDropdown(event, this)">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="actions-dropdown">
                    <form action="{{ route('admin.akun-asesi.reset-password', $account->id) }}" method="POST"
                          onsubmit="return confirm('Reset password akun NIK {{ $account->NIK }} ke NIK sebagai password?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-key"></i>
                            Reset Password
                        </button>
                    </form>

                    <form action="{{ route('admin.akun-asesi.destroy', $account->id) }}" method="POST"
                          onsubmit="return confirm('Hapus akun NIK {{ $account->NIK }}? Akun akan dihapus permanen.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item danger">
                            <i class="bi bi-trash"></i>
                            Hapus Akun
                        </button>
                    </form>
                </div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" style="text-align:center;padding:40px 20px;">
            <i class="bi bi-inbox" style="font-size:48px;color:#cbd5e1;display:block;margin-bottom:12px;"></i>
            <span style="color:#64748b;font-size:14px;">Tidak ada data akun asesi ditemukan</span>
        </td>
    </tr>
@endforelse
