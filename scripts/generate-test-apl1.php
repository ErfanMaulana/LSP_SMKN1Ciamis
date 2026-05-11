<?php
// Generate test PDFs for up to 3 approved asesi to validate signature scaling.
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Asesi;
use Barryvdh\DomPDF\Facade\Pdf;

// fetch up to 3 approved asesi
$asesis = Asesi::with(['skemas','verifiedBy'])->where('status', 'approved')->take(3)->get();

if ($asesis->isEmpty()) {
    echo "No approved asesi found.\n";
    exit(0);
}

foreach ($asesis as $asesi) {
    $logoPath = public_path('images/lsp.png');
    $logoUrl = file_exists($logoPath) ? 'file://' . $logoPath : null;

    $isValidDataUri = function ($value) {
        return is_string($value)
            && preg_match('/^data:image\/(png|jpe?g);base64,[A-Za-z0-9+\/=\r\n]+$/i', $value);
    };

    $signatureRenderStyle = function (?string $dataUri, int $preferredHeight = 48, int $maxWidth = 180, int $maxHeight = 72): array {
        if (!$dataUri) {
            return ['src' => null, 'style' => null];
        }

        if (!preg_match('/^data:image\/(png|jpe?g);base64,(.*)$/si', $dataUri, $matches)) {
            return ['src' => $dataUri, 'style' => 'max-width: ' . $maxWidth . 'px; max-height: ' . $maxHeight . 'px; width: auto; height: auto;'];
        }

        $rawImage = base64_decode(str_replace(["\r", "\n"], '', $matches[2]), true);
        if ($rawImage === false) {
            return ['src' => $dataUri, 'style' => 'max-width: ' . $maxWidth . 'px; max-height: ' . $maxHeight . 'px; width: auto; height: auto;'];
        }

        $imageInfo = @getimagesizefromstring($rawImage);
        if (!$imageInfo || empty($imageInfo[0]) || empty($imageInfo[1])) {
            return ['src' => $dataUri, 'style' => 'max-width: ' . $maxWidth . 'px; max-height: ' . $maxHeight . 'px; width: auto; height: auto; display: block; margin: 0 auto;'];
        }

        [$width, $height] = [$imageInfo[0], $imageInfo[1]];
        $scale = 1;

        if ($height < $preferredHeight) {
            $scale = max($scale, $preferredHeight / max($height, 1));
        }

        if ($width * $scale > $maxWidth || $height * $scale > $maxHeight) {
            $scale = min($maxWidth / max($width, 1), $maxHeight / max($height, 1));
        }

        $renderWidth = max(1, (int) round($width * $scale));
        $renderHeight = max(1, (int) round($height * $scale));

        if ($renderWidth > $maxWidth || $renderHeight > $maxHeight) {
            $limitScale = min($maxWidth / max($renderWidth, 1), $maxHeight / max($renderHeight, 1));
            $renderWidth = max(1, (int) round($renderWidth * $limitScale));
            $renderHeight = max(1, (int) round($renderHeight * $limitScale));
        }

        return [
            'src' => $dataUri,
            'style' => "width: {$renderWidth}px; height: {$renderHeight}px; max-width: {$maxWidth}px; max-height: {$maxHeight}px; display: block; margin: 0 auto;",
        ];
    };

    $pendaftarSignature = $isValidDataUri($asesi->tanda_tangan_pendaftar ?? null)
        ? $signatureRenderStyle($asesi->tanda_tangan_pendaftar)
        : ['src' => null, 'style' => null];

    $verifikatorSignature = $isValidDataUri($asesi->tanda_tangan_admin ?? null)
        ? $signatureRenderStyle($asesi->tanda_tangan_admin)
        : ['src' => null, 'style' => null];

    $data = [
        'asesi' => $asesi,
        'skema' => $asesi->skemas->first(),
        'bukti_persyaratan' => $asesi->verifikasi_bukti_persyaratan_dasar ?? [],
        'bukti_administratif' => $asesi->verifikasi_bukti_administratif ?? [],
        'logoUrl' => $logoUrl,
        'pendaftarSignature' => $pendaftarSignature,
        'verifikatorSignature' => $verifikatorSignature,
        'adminSignerName' => optional($asesi->verifiedBy)->name ?? optional($asesi->verifiedBy)->username,
        'pendaftarSignedAt' => optional($asesi->tanggal_tanda_tangan_pendaftar)->format('d-m-Y'),
        'adminSignedAt' => optional($asesi->tanggal_tanda_tangan_admin)->format('d-m-Y'),
        'rekomendasiText' => 'Diterima',
        'catatanAdmin' => $asesi->catatan_admin,
    ];

    $pdf = Pdf::loadView('admin.asesi.pdf.formulir', $data)->setPaper('a4', 'portrait');
    $output = $pdf->output();

    $fileName = 'test_apl1_' . ($asesi->NIK ?? uniqid()) . '.pdf';
    $filePath = storage_path('app/' . $fileName);
    file_put_contents($filePath, $output);

    echo "Saved: {$filePath}\n";
}

echo "Done.\n";
