<?php
$origPath = 'storage/app/template/fr_ak_01.docx.bak';
$targetPath = 'storage/app/template/fr_ak_01.docx';

$origZip = new ZipArchive();
if ($origZip->open($origPath) !== true) {
    die("Cannot open $origPath\n");
}

$xml = $origZip->getFromName('word/document.xml');
preg_match_all('/<w:tr\b[^>]*>(.*?)<\/w:tr>/s', $xml, $rows);

// ROW 1: Portofolio & Review Produk
$buktiRows = '<w:tr w:rsidR="00CC14D7" w:rsidTr="00BA6B8D">
    <w:trPr><w:trHeight w:val="380"/></w:trPr>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="3349" w:type="dxa"/>
            <w:gridSpan w:val="2"/>
            <w:vMerge w:val="restart"/>
            <w:tcBorders>
                <w:top w:val="single" w:sz="4" w:space="0" w:color="000000"/>
                <w:bottom w:val="nil"/>
                <w:right w:val="nil"/>
            </w:tcBorders>
            <w:vAlign w:val="top"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:ind w:left="111"/>
                <w:spacing w:before="60"/>
                <w:rPr><w:rFonts w:ascii="Times New Roman"/></w:rPr>
            </w:pPr>
            <w:r><w:t>Bukti yang akan dikumpulkan :</w:t></w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="340" w:type="dxa"/>
            <w:tcBorders>
                <w:top w:val="single" w:sz="4" w:space="0" w:color="000000"/>
                <w:left w:val="nil"/>
                <w:bottom w:val="nil"/>
                <w:right w:val="single" w:sz="8" w:space="0" w:color="000000"/>
            </w:tcBorders>
            <w:vAlign w:val="top"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:spacing w:before="60"/>
                <w:ind w:right="96"/>
                <w:jc w:val="right"/>
            </w:pPr>
            <w:r><w:t>:</w:t></w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="2902" w:type="dxa"/>
            <w:gridSpan w:val="3"/>
            <w:tcBorders>
                <w:top w:val="single" w:sz="4" w:space="0" w:color="000000"/>
                <w:left w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                <w:bottom w:val="nil"/>
                <w:right w:val="nil"/>
            </w:tcBorders>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:ind w:left="114"/>
                <w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr>
            </w:pPr>
            <w:r><w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr><w:t xml:space="preserve">${portfolio}  Hasil Verifikasi Portofolio</w:t></w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="3195" w:type="dxa"/>
            <w:gridSpan w:val="2"/>
            <w:tcBorders>
                <w:top w:val="single" w:sz="4" w:space="0" w:color="000000"/>
                <w:left w:val="nil"/>
                <w:bottom w:val="nil"/>
                <w:right w:val="single" w:sz="4" w:space="0" w:color="000000"/>
            </w:tcBorders>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:ind w:left="114"/>
                <w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr>
            </w:pPr>
            <w:r><w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr><w:t xml:space="preserve">${review_produk}  Hasil Reviu Produk</w:t></w:r>
        </w:p>
    </w:tc>
</w:tr>';

// ROW 2: Observasi & Kegiatan Terstruktur
$buktiRows .= '<w:tr w:rsidR="00CC14D7" w:rsidTr="00BA6B8D">
    <w:trPr><w:trHeight w:val="380"/></w:trPr>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="3349" w:type="dxa"/>
            <w:gridSpan w:val="2"/>
            <w:vMerge w:val="continue"/>
            <w:tcBorders>
                <w:top w:val="nil"/>
                <w:bottom w:val="nil"/>
                <w:right w:val="nil"/>
            </w:tcBorders>
        </w:tcPr>
        <w:p><w:pPr><w:pStyle w:val="TableParagraph"/></w:pPr></w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="340" w:type="dxa"/>
            <w:tcBorders>
                <w:top w:val="nil"/>
                <w:left w:val="nil"/>
                <w:bottom w:val="nil"/>
                <w:right w:val="single" w:sz="8" w:space="0" w:color="000000"/>
            </w:tcBorders>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:ind w:right="96"/>
                <w:jc w:val="right"/>
            </w:pPr>
            <w:r><w:t>:</w:t></w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="2902" w:type="dxa"/>
            <w:gridSpan w:val="3"/>
            <w:tcBorders>
                <w:top w:val="nil"/>
                <w:left w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                <w:bottom w:val="nil"/>
                <w:right w:val="nil"/>
            </w:tcBorders>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:ind w:left="114"/>
                <w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr>
            </w:pPr>
            <w:r><w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr><w:t xml:space="preserve">${observasi}  Hasil Observasi Langsung</w:t></w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="3195" w:type="dxa"/>
            <w:gridSpan w:val="2"/>
            <w:tcBorders>
                <w:top w:val="nil"/>
                <w:left w:val="nil"/>
                <w:bottom w:val="nil"/>
                <w:right w:val="single" w:sz="4" w:space="0" w:color="000000"/>
            </w:tcBorders>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:ind w:left="114"/>
                <w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr>
            </w:pPr>
            <w:r><w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr><w:t xml:space="preserve">${kegiatan_terstruktur}  Hasil Kegiatan Terstruktur</w:t></w:r>
        </w:p>
    </w:tc>
</w:tr>';

// ROW 3: Pertanyaan Lisan & Pertanyaan Tertulis
$buktiRows .= '<w:tr w:rsidR="00CC14D7" w:rsidTr="00BA6B8D">
    <w:trPr><w:trHeight w:val="380"/></w:trPr>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="3349" w:type="dxa"/>
            <w:gridSpan w:val="2"/>
            <w:vMerge w:val="continue"/>
            <w:tcBorders>
                <w:top w:val="nil"/>
                <w:bottom w:val="nil"/>
                <w:right w:val="nil"/>
            </w:tcBorders>
        </w:tcPr>
        <w:p><w:pPr><w:pStyle w:val="TableParagraph"/></w:pPr></w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="340" w:type="dxa"/>
            <w:tcBorders>
                <w:top w:val="nil"/>
                <w:left w:val="nil"/>
                <w:bottom w:val="nil"/>
                <w:right w:val="single" w:sz="8" w:space="0" w:color="000000"/>
            </w:tcBorders>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:ind w:right="96"/>
                <w:jc w:val="right"/>
            </w:pPr>
            <w:r><w:t>:</w:t></w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="2902" w:type="dxa"/>
            <w:gridSpan w:val="3"/>
            <w:tcBorders>
                <w:top w:val="nil"/>
                <w:left w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                <w:bottom w:val="nil"/>
                <w:right w:val="nil"/>
            </w:tcBorders>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:ind w:left="114"/>
                <w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr>
            </w:pPr>
            <w:r><w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr><w:t xml:space="preserve">${pertanyaan_lisan}  Hasil Pertanyaan Lisan</w:t></w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="3195" w:type="dxa"/>
            <w:gridSpan w:val="2"/>
            <w:tcBorders>
                <w:top w:val="nil"/>
                <w:left w:val="nil"/>
                <w:bottom w:val="nil"/>
                <w:right w:val="single" w:sz="4" w:space="0" w:color="000000"/>
            </w:tcBorders>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:ind w:left="114"/>
                <w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr>
            </w:pPr>
            <w:r><w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr><w:t xml:space="preserve">${pertanyaan_tertulis}  Hasil Pertanyaan Tertulis</w:t></w:r>
        </w:p>
    </w:tc>
</w:tr>';

// ROW 4: Lainnya & Wawancara
$buktiRows .= '<w:tr w:rsidR="00CC14D7" w:rsidTr="00BA6B8D">
    <w:trPr><w:trHeight w:val="380"/></w:trPr>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="3349" w:type="dxa"/>
            <w:gridSpan w:val="2"/>
            <w:vMerge w:val="continue"/>
            <w:tcBorders>
                <w:top w:val="nil"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="000000"/>
                <w:right w:val="nil"/>
            </w:tcBorders>
        </w:tcPr>
        <w:p><w:pPr><w:pStyle w:val="TableParagraph"/></w:pPr></w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="340" w:type="dxa"/>
            <w:tcBorders>
                <w:top w:val="nil"/>
                <w:left w:val="nil"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="000000"/>
                <w:right w:val="single" w:sz="8" w:space="0" w:color="000000"/>
            </w:tcBorders>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:ind w:right="96"/>
                <w:jc w:val="right"/>
            </w:pPr>
            <w:r><w:t></w:t></w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="2902" w:type="dxa"/>
            <w:gridSpan w:val="3"/>
            <w:tcBorders>
                <w:top w:val="nil"/>
                <w:left w:val="single" w:sz="8" w:space="0" w:color="000000"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="000000"/>
                <w:right w:val="nil"/>
            </w:tcBorders>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:ind w:left="114"/>
                <w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr>
            </w:pPr>
            <w:r><w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr><w:t xml:space="preserve">${lainnya}  Lainnya ......</w:t></w:r>
        </w:p>
    </w:tc>
    <w:tc>
        <w:tcPr>
            <w:tcW w:w="3195" w:type="dxa"/>
            <w:gridSpan w:val="2"/>
            <w:tcBorders>
                <w:top w:val="nil"/>
                <w:left w:val="nil"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="000000"/>
                <w:right w:val="single" w:sz="4" w:space="0" w:color="000000"/>
            </w:tcBorders>
            <w:vAlign w:val="center"/>
        </w:tcPr>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="TableParagraph"/>
                <w:ind w:left="114"/>
                <w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr>
            </w:pPr>
            <w:r><w:rPr><w:rFonts w:ascii="Times New Roman"/><w:lang w:val="en-US"/></w:rPr><w:t xml:space="preserve">${wawancara}  Hasil Pertanyaan Wawancara</w:t></w:r>
        </w:p>
    </w:tc>
</w:tr>';

$firstPart = '';
for ($i = 0; $i <= 6; $i++) {
    $firstPart .= $rows[0][$i];
}

$lastPart = '';
for ($i = 12; $i < count($rows[0]); $i++) {
    $lastPart .= $rows[0][$i];
}

$firstRowStart = strpos($xml, $rows[0][0]);
$lastRowEnd = strpos($xml, $rows[0][count($rows[0]) - 1]) + strlen($rows[0][count($rows[0]) - 1]);

$newXml = substr($xml, 0, $firstRowStart) . $firstPart . $buktiRows . $lastPart . substr($xml, $lastRowEnd);

// Write to template
$newZip = new ZipArchive();
if ($newZip->open($targetPath, ZipArchive::OVERWRITE) !== true) {
    if ($newZip->open($targetPath, ZipArchive::CREATE) !== true) {
        die("Cannot open $targetPath\n");
    }
}

for ($i = 0; $i < $origZip->numFiles; $i++) {
    $name = $origZip->getNameIndex($i);
    if ($name === 'word/document.xml') {
        $newZip->addFromString($name, $newXml);
    } else {
        $newZip->addFromString($name, $origZip->getFromIndex($i));
    }
}

$origZip->close();
$newZip->close();

echo "Successfully written clean template to $targetPath (size: " . filesize($targetPath) . ")!\n";
