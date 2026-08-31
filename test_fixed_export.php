<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use PhpOffice\PhpWord\TemplateProcessor;

$templatePath = storage_path('app/template/fr_ak_01_fixed.docx');
if (!file_exists($templatePath)) {
    die("Template not found: $templatePath\n");
}

$tp = new TemplateProcessor($templatePath);

$check = '☑';
$uncheck = '☐';

$tp->setValue('judul_skema', 'Pemrograman Web');
$tp->setValue('type_skema', 'KKNI Level II');
$tp->setValue('nomor_skema', 'SKM-001/LSP/2026');
$tp->setValue('tuk_sewaktu/tempatkerja/mandiri', 'Sewaktu');
$tp->setValue('nama_asesor', 'John Asesor');
$tp->setValue('nama_asesi', 'Jane Asesi');

// Let's match Image 3: Observasi & Pertanyaan Tertulis checked
$tp->setValue('portfolio', $uncheck);
$tp->setValue('review_produk', $uncheck);
$tp->setValue('observasi', $check);
$tp->setValue('kegiatan_terstruktur', $uncheck);
$tp->setValue('pertanyaan_lisan', $uncheck);
$tp->setValue('pertanyaan_tertulis', $check);
$tp->setValue('lainnya', $uncheck);
$tp->setValue('wawancara', $uncheck);

$tp->setValue('hari/tanggal_jadwal', 'Senin, 10 Maret 2026');
$tp->setValue('waktu_jadwal', '08:00 - 12:00 WIB');
$tp->setValue('tuk', 'Lab Komputer 1');

$tp->setValue('ttd_asesor', '');
$tp->setValue('tanggal_ttd_asesor', '10 Maret 2026');
$tp->setValue('ttd_asesi', '');
$tp->setValue('tanggal_ttd_asesi', '10 Maret 2026');

$outPath = storage_path('app/temp/test_fixed_out.docx');
$tp->saveAs($outPath);

echo "Successfully generated: $outPath (size: " . filesize($outPath) . " bytes)\n";

// Let's inspect the text of generated document
$zip = new ZipArchive();
if ($zip->open($outPath) === true) {
    $xml = $zip->getFromName('word/document.xml');
    preg_match_all('/<w:tr\b[^>]*>(.*?)<\/w:tr>/s', $xml, $rows);
    echo "Total rows in output: " . count($rows[0]) . "\n";
    for ($i = 7; $i <= 10; $i++) {
        echo "Row $i text: " . trim(preg_replace('/\s+/', ' ', strip_tags($rows[0][$i]))) . "\n";
    }
}
