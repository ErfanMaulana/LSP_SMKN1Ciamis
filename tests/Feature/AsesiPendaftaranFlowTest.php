<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Asesi;
use App\Models\Jurusan;
use App\Models\Skema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AsesiPendaftaranFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_step1_submit_redirects_to_document_upload_and_sets_draft_status(): void
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
            'action' => 'next',
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
            'status' => 'draft',
        ]);
    }

    public function test_step1_save_draft_allows_partial_data_and_redirects_to_formulir(): void
    {
        $jurusan = Jurusan::create([
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
            'kode_jurusan' => 'TKJ',
        ]);

        $account = Account::create([
            'id' => '3204010101010002',
            'NIK' => '3204010101010002',
            'nama' => 'Draft User',
            'password' => 'secret-password',
            'role' => 'asesi',
        ]);

        $this->actingAs($account, 'account');

        $response = $this->post(route('asesi.pendaftaran.formulir.store'), [
            'action' => 'draft',
            'nama' => 'Draft User',
            'ID_jurusan' => $jurusan->ID_jurusan,
            'tempat_lahir' => 'Ciamis',
            'alamat' => 'Jl. Ciamis No. 10',
        ]);

        $response->assertRedirect(route('asesi.pendaftaran.formulir'));
        $response->assertSessionHas('success', 'Draft formulir berhasil disimpan.');
        $this->assertDatabaseHas('asesi', [
            'NIK' => '3204010101010002',
            'tempat_lahir' => 'Ciamis',
            'status' => 'draft',
        ]);
    }

    public function test_document_page_opens_when_status_is_draft(): void
    {
        $jurusan = Jurusan::create([
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
            'kode_jurusan' => 'TKJ',
        ]);

        Account::create([
            'id' => '3204010101010003',
            'NIK' => '3204010101010003',
            'nama' => 'Flow User',
            'password' => 'secret-password',
            'role' => 'asesi',
        ]);

        Asesi::create([
            'NIK' => '3204010101010003',
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
            'status' => 'draft',
        ]);

        $account = Account::where('NIK', '3204010101010003')->firstOrFail();
        $this->actingAs($account, 'account')
            ->get(route('asesi.pendaftaran.dokumen'))
            ->assertOk()
            ->assertSee('Upload Dokumen');
    }

    public function test_step2_submit_without_bukti_kompetensi_succeeds(): void
    {
        Storage::fake('public');

        $jurusan = Jurusan::create([
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
            'kode_jurusan' => 'TKJ',
        ]);

        $skema = Skema::create([
            'nama_skema' => 'Tes Skema Submit',
            'nomor_skema' => 'SKM-004',
            'jenis_skema' => 'KKNI',
            'jurusan_id' => $jurusan->ID_jurusan,
        ]);

        Account::create([
            'id' => '3204010101010004',
            'NIK' => '3204010101010004',
            'nama' => 'Submit User',
            'password' => 'secret-password',
            'role' => 'asesi',
        ]);

        $asesiRecord = Asesi::create([
            'NIK' => '3204010101010004',
            'nama' => 'Submit User',
            'email' => 'submit@example.com',
            'ID_jurusan' => $jurusan->ID_jurusan,
            'kelas' => 'XII TKJ 1',
            'tempat_lahir' => 'Ciamis',
            'tanggal_lahir' => '2001-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'kewarganegaraan' => 'Indonesia',
            'alamat' => 'Jl. Melati',
            'kode_pos' => '46211',
            'telepon_hp' => '081234567891',
            'pekerjaan' => 'Siswa',
            'pendidikan_terakhir' => 'SMA/SMK',
            'nama_lembaga' => 'SMKN 1 Ciamis',
            'alamat_lembaga' => 'Jl. Pendidikan',
            'jabatan' => 'Siswa',
            'email_lembaga' => 'sekolah@example.com',
            'status' => 'draft',
        ]);
        $asesiRecord->skemas()->sync([$skema->id => ['status' => 'belum_mulai']]);

        $account = Account::where('NIK', '3204010101010004')->firstOrFail();
        $this->actingAs($account, 'account');

        $pasFoto = UploadedFile::fake()->image('pas_foto.jpg', 300, 400);
        $transkrip = UploadedFile::fake()->create('transkrip.pdf', 100);
        $identitas = UploadedFile::fake()->create('ktp.pdf', 100);

        $response = $this->post(route('asesi.pendaftaran.dokumen.store'), [
            'action' => 'submit',
            'pas_foto' => $pasFoto,
            'transkrip_nilai' => [$transkrip],
            'identitas_pribadi' => [$identitas],
            // bukti_kompetensi is omitted (optional)
            'tanda_tangan_pendaftar' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
        ]);

        $response->assertRedirect(route('asesi.dashboard'));
        $this->assertDatabaseHas('asesi', [
            'NIK' => '3204010101010004',
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('bukti_pendukung', [
            'NIK' => '3204010101010004',
            'jenis_dokumen' => 'transkrip_nilai',
        ]);
    }

    public function test_step2_save_draft_saves_documents_without_signature(): void
    {
        Storage::fake('public');

        $jurusan = Jurusan::create([
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
            'kode_jurusan' => 'TKJ',
        ]);

        Account::create([
            'id' => '3204010101010005',
            'NIK' => '3204010101010005',
            'nama' => 'Draft Dokumen User',
            'password' => 'secret-password',
            'role' => 'asesi',
        ]);

        Asesi::create([
            'NIK' => '3204010101010005',
            'nama' => 'Draft Dokumen User',
            'email' => 'draftdokumen@example.com',
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
            'status' => 'draft',
        ]);

        $account = Account::where('NIK', '3204010101010005')->firstOrFail();
        $this->actingAs($account, 'account');

        $transkrip = UploadedFile::fake()->create('transkrip_draft.pdf', 100);

        $response = $this->post(route('asesi.pendaftaran.dokumen.store'), [
            'action' => 'draft',
            'transkrip_nilai' => [$transkrip],
            // No signature provided!
        ]);

        $response->assertRedirect(route('asesi.pendaftaran.dokumen'));
        $response->assertSessionHas('success', 'Draft dokumen berhasil disimpan.');
        $this->assertDatabaseHas('asesi', [
            'NIK' => '3204010101010005',
            'status' => 'draft',
        ]);
        $this->assertDatabaseHas('bukti_pendukung', [
            'NIK' => '3204010101010005',
            'jenis_dokumen' => 'transkrip_nilai',
            'nama_file' => 'transkrip_draft.pdf',
        ]);
    }
}
