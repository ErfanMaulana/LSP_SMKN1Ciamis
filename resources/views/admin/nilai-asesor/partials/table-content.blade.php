<div class="table-wrap">
    @if($data->count())
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th style="width:140px;">Asesi</th>
                    <th style="width:100px;">NIK</th>
                    <th style="width:150px;">Skema</th>
                    <th style="width:100px;">Asesor</th>
                    <th style="width:80px;">Rata-rata</th>
                    <th style="width:100px;">Hasil</th>
                    <th style="width:140px;">Terakhir Dinilai</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $i => $row)
                    <tr>
                        <td>{{ $data->firstItem() + $i }}</td>
                        <td>
                            <strong>{{ $row->nama_asesi }}</strong>
                            <div style="font-size:11px;color:#94a3b8;margin-top:2px;overflow:hidden;text-overflow:ellipsis;max-width:130px;">{{ $row->email_asesi }}</div>
                        </td>
                        <td style="font-family:monospace;">{{ $row->asesi_nik }}</td>
                        <td>
                            <strong style="font-size:12px;">{{ $row->nama_skema }}</strong>
                            <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $row->nomor_skema }}</div>
                        </td>
                        <td>{{ $row->nama_asesor ?? '-' }}</td>
                        <td>{{ number_format((float) $row->rata_rata, 2) }}</td>
                        <td>
                            @if((float) $row->rata_rata >= (float) $row->kkm)
                                <span class="badge kompeten"><i class="bi bi-check-circle"></i> Kompeten</span>
                            @else
                                <span class="badge belum"><i class="bi bi-x-circle"></i> Belum Kompeten</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($row->terakhir_dinilai)->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.nilai-asesor.show', ['asesiNik' => $row->asesi_nik, 'skemaId' => $row->skema_id]) }}" class="btn-detail">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-wrapper">{{ $data->links() }}</div>
    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>Belum ada data nilai asesor.</p>
        </div>
    @endif
</div>
