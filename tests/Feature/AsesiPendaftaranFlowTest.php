<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Asesi;
use App\Models\Jurusan;
use App\Models\Skema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AsesiPendaftaranFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_step1_submit_redirects_to_document_upload(): void
    {
        $jurusan = Jurusan::create([
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
            'kode_jurusan' => 'TKJ',
        ]);

        $skema = Skema::create([
            'nama_skema' => 'Tes Skema',
            'nomor_skema' => 'SKM-001',
            'jenis_skema' => 'KKNI',
            'jurusan_id' => $jurusan->ID_jurusan,
        ]);

        $account = Account::create([
            'id' => '3204010101010001',
            'NIK' => '3204010101010001',
            'nama' => 'Test User',
            'password' => 'secret-password',
            'role' => 'asesi',
        ]);

        $this->actingAs($account, 'account');

        $response = $this->post(route('asesi.pendaftaran.formulir.store'), [
            'nama' => 'Test User',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2001-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'kewarganegaraan' => 'Indonesia',
            'alamat' => 'Jl. Mawar No. 1',
            'kode_pos' => '40111',
            'telepon_hp' => '081234567890',
            'email' => 'test@example.com',
            'pekerjaan' => 'Siswa',
            'pendidikan_terakhir' => 'SMA/SMK',
            'ID_jurusan' => $jurusan->ID_jurusan,
            'kelas' => 'XII TKJ 1',
            'skema_id' => $skema->id,
            'nama_lembaga' => 'SMKN 1 Ciamis',
            'alamat_lembaga' => 'Jl. Pendidikan',
            'jabatan' => 'Siswa',
            'no_fax_lembaga' => '123',
            'telepon_rumah' => '456',
            'email_lembaga' => 'sekolah@example.com',
            'unit_lembaga' => '98765',
        ]);

        $response->assertRedirect(route('asesi.pendaftaran.dokumen'));
        $this->assertDatabaseHas('asesi', [
            'NIK' => '3204010101010001',
            'status' => 'pending',
        ]);
    }

    public function test_document_page_opens_after_step1_flow_flag_even_when_status_is_pending(): void
    {
        $jurusan = Jurusan::create([
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
            'kode_jurusan' => 'TKJ',
        ]);

        Account::create([
            'id' => '3204010101010002',
            'NIK' => '3204010101010002',
            'nama' => 'Flow User',
            'password' => 'secret-password',
            'role' => 'asesi',
        ]);

        Asesi::create([
            'NIK' => '3204010101010002',
            'nama' => 'Flow User',
            'email' => 'flow@example.com',
            'ID_jurusan' => $jurusan->ID_jurusan,
            'kelas' => 'XII TKJ 1',
            'tempat_lahir' => 'Ciamis',
            'tanggal_lahir' => '2001-01-01',
            'alamat' => 'Jl. Melati',
            'kebangsaan' => 'Indonesia',
            'kode_pos' => '46211',
            'telepon_hp' => '081234567891',
            'pendidikan_terakhir' => 'SMA/SMK',
            'nama_lembaga' => 'SMKN 1 Ciamis',
            'alamat_lembaga' => 'Jl. Pendidikan',
            'jabatan' => 'Siswa',
            'email_lembaga' => 'sekolah@example.com',
            'status' => 'pending',
        ]);

        $account = Account::where('NIK', '3204010101010002')->firstOrFail();
        $this->actingAs($account, 'account')
            ->withSession([
                'pendaftaran_nik' => '3204010101010002',
                'pendaftaran_step1_completed' => true,
            ])
            ->get(route('asesi.pendaftaran.dokumen'))
            ->assertOk()
            ->assertSee('Upload Dokumen');
    }
}
