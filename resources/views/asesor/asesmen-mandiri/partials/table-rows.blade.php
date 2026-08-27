@if($data->count())
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Asesi</th>
                    <th>NIK</th>
                    <th>Kelompok</th>
                    <th>Skema</th>
                    <th>Jawaban</th>
                    <th>Rekomendasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>
                            <div style="font-weight:600; color:#1e3a5f;">{{ $row->asesi?->nama ?? '-' }}</div>
                            @if($row->asesi?->jurusan)
                                <div style="font-size:11px; color:#64748b;">{{ $row->asesi->jurusan->nama_jurusan ?? ($row->asesi->jurusan->nama ?? '') }}</div>
                            @endif
                        </td>
                        <td><code style="font-size:12px;">{{ $row->asesi_nik }}</code></td>
                        <td>
                            @if($row->asesi?->kelompok)
                                <span class="badge" style="background:#e0f2fe; color:#0369a1; font-weight:600; font-size:12px; padding:4px 8px; border-radius:6px;">
                                    {{ $row->asesi->kelompok->nama_kelompok }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size:12px;">-</span>
                            @endif
                        </td>
                        <td>{{ $row->skema?->nama_skema ?? '-' }}</td>
                        <td>{{ $row->jawaban_count ? $row->jawaban_count . ' elemen' : 'Belum ada' }}</td>
                        <td>
                            @if($row->rekomendasi === 'lanjut')
                                <span class="badge badge-rekomendasi-lanjut">Lanjut</span>
                            @elseif($row->rekomendasi === 'tidak_lanjut')
                                <span class="badge badge-rekomendasi-tidak">Tidak Lanjut</span>
                            @else
                                <span class="badge badge-rekomendasi-pending">Belum Direview</span>
                            @endif
                        </td>
                        <td>
                            @if($row->has_asesmen_mandiri)
                                <a href="{{ route('asesor.asesmen-mandiri.show', ['asesiNik' => $row->asesi_nik, 'skemaId' => $row->skema_id]) }}" class="btn-review">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            @else
                                <span class="btn-review disabled">
                                    <i class="bi bi-eye"></i> Detail
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>Belum ada asesmen mandiri untuk ditampilkan.</p>
    </div>
@endif
