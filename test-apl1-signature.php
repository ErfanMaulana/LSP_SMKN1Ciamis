<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Asesi;
use Barryvdh\DomPDF\Facade\Pdf;

$asesi = Asesi::with(['jurusan','skemas','buktiPendukung','verifiedBy'])->where('status','approved')->first();
if (!$asesi) {
    echo "NO APPROVED DATA\n";
    exit(1);
}

$logoPath = public_path('images/lsp.png');
$logoUrl = file_exists($logoPath) ? 'file://' . $logoPath : null;

$isValidDataUri = function ($value) {
    return is_string($value) && preg_match('/^data:image\/(png|jpe?g);base64,[A-Za-z0-9+\/=\r\n]+$/i', $value);
};

$data = [
    'asesi' => $asesi,
    'skema' => $asesi->skemas->first(),
    'bukti_persyaratan' => $asesi->verifikasi_bukti_persyaratan_dasar ?? [],
    'bukti_administratif' => $asesi->verifikasi_bukti_administratif ?? [],
    'logoUrl' => $logoUrl,
    'pendaftarSignature' => $isValidDataUri($asesi->tanda_tangan_pendaftar ?? null) ? $asesi->tanda_tangan_pendaftar : null,
    'verifikatorSignature' => $isValidDataUri($asesi->tanda_tangan_admin ?? null) ? $asesi->tanda_tangan_admin : null,
    'adminSignerName' => optional($asesi->verifiedBy)->nama,
];

Pdf::loadView('admin.asesi.pdf.formulir', $data)->setPaper('a4', 'portrait')->save(storage_path('app/test-apl1-signature.pdf'));

echo 'OK | pendaftar=' . ($data['pendaftarSignature'] ? 'yes' : 'no') . ' | admin=' . ($data['verifikatorSignature'] ? 'yes' : 'no') . ' | adminName=' . ($data['adminSignerName'] ?? '-') . "\n";
