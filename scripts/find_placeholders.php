<?php

$file = __DIR__ . '/../storage/app/template/fr_ak_02.docx';
$zip = new ZipArchive();
if ($zip->open($file) === true) {
    $xml = $zip->getFromName('word/document.xml');
    $zip->close();
    
    preg_match_all('/\$\{([^}]+)\}/', $xml, $matches);
    echo "PLACEHOLDERS FOUND:\n";
    foreach (array_unique($matches[1]) as $p) {
        echo " - \${" . $p . "}\n";
    }
}
