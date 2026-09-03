<?php

namespace App\Services;

use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Skema;
use Illuminate\Support\Collection;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;

class FrApl02ExportService
{
    /**
     * Export FR.APL.02 docx from template.
     *
     * @param Asesi $asesi
     * @param Skema $skema
     * @param Collection $answers
     * @param object $pivot
     * @param Asesor|null $asesor
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Asesi $asesi, Skema $skema, Collection $answers, $pivot, ?Asesor $asesor = null)
    {
        $templatePath = storage_path('app/template/fr_apl_02.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template fr_apl_02.docx tidak ditemukan.');
        }

        if (!$asesor && !empty($pivot->reviewed_by)) {
            $asesor = Asesor::where('no_met', (string) $pivot->reviewed_by)->first();
        }

        $intermediateFile = tempnam(sys_get_temp_dir(), 'frapl02_step1_') . '.docx';
        copy($templatePath, $intermediateFile);

        $zip = new ZipArchive();
        if ($zip->open($intermediateFile) !== true) {
            abort(500, 'Gagal membuka template FR.APL.02.');
        }

        $xml = $zip->getFromName('word/document.xml');

        // Un-fragment and normalize any Word-split placeholders
        $xml = preg_replace('/<w:proofErr[^>]*\/>/s', '', $xml);
        $vars = [
            'type_skema',
            'judul_skema',
            'nomor_skema',
            'unit_kompetensi',
            'kode_unit',
            'judul_unit',
            'pertanyaan_unit',
            'elemen',
            'kuk',
            'k',
            'bk',
            'bukti_yg_relevan',
            'rekomendasi',
            'rekomendasi_asesi',
            'nama_asesi',
            'ttd_asesi',
            'tanggal_ttd_asesi',
            'nama_asesor',
            'noreg_asesor',
            'no_reg_asesor',
            'ttd_asesor',
            'tanggal_ttd_asesor'
        ];

        foreach ($vars as $var) {
            $pattern = '/\$\{\s*(?:<\/w:t>.*?<w:t[^>]*>\s*)?' . preg_quote($var, '/') . '\s*(?:<\/w:t>.*?<w:t[^>]*>\s*)?\}/s';
            $xml = preg_replace($pattern, '${' . $var . '}', $xml);

            $pattern2 = '/<w:r\b[^>]*>(?:(?!<\/w:r>).)*?\$\{\s*<\/w:t>.*?<w:t[^>]*>\s*' . preg_quote($var, '/') . '\s*<\/w:t>.*?<\/w:r>/s';
            $xml = preg_replace($pattern2, '<w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t>${' . $var . '}</w:t></w:r>', $xml);
        }

        // 1. Skema Type formatting
        $skemaType = strtolower(trim((string) ($skema->jenis_skema ?? '')));
        $isKkni = str_contains($skemaType, 'kkni');
        $isOkupasi = str_contains($skemaType, 'okupasi');
        $isKlaster = str_contains($skemaType, 'klaster') || str_contains($skemaType, 'cluster');
        if (!$isKkni && !$isOkupasi && !$isKlaster) {
            $isKkni = true; // default
        }

        $typeSkemaXml = '<w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t>(</w:t></w:r>' .
                        '<w:r><w:rPr><w:color w:val="000000"/>' . ($isKkni ? '' : '<w:strike/>') . '</w:rPr><w:t>KKNI</w:t></w:r>' .
                        '<w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t>/</w:t></w:r>' .
                        '<w:r><w:rPr><w:color w:val="000000"/>' . ($isOkupasi ? '' : '<w:strike/>') . '</w:rPr><w:t>Okupasi</w:t></w:r>' .
                        '<w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t>/</w:t></w:r>' .
                        '<w:r><w:rPr><w:color w:val="000000"/>' . ($isKlaster ? '' : '<w:strike/>') . '</w:rPr><w:t>Klaster</w:t></w:r>' .
                        '<w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t>)</w:t></w:r>';

        $xml = preg_replace('/<w:r\b[^>]*>(?:(?!<\/w:r>).)*?\$\{type_skema\}.*?<\/w:r>/s', $typeSkemaXml, $xml);
        $xml = str_replace('${judul_skema}', htmlspecialchars($skema->nama_skema ?? '-', ENT_XML1), $xml);
        $xml = str_replace('${nomor_skema}', htmlspecialchars($skema->nomor_skema ?? '-', ENT_XML1), $xml);

        // 2. Generate all Unit Tables
        $allUnitTablesXml = '';
        $units = $skema->units ?? collect();

        foreach ($units as $uIdx => $unit) {
            $unitNum = $uIdx + 1;
            $kodeUnit = htmlspecialchars($unit->kode_unit ?? '-', ENT_XML1);
            $judulUnit = htmlspecialchars($unit->judul_unit ?? '-', ENT_XML1);
            $pertanyaanUnit = htmlspecialchars($unit->pertanyaan_unit ?? ('Dapatkah Saya ' . ($unit->judul_unit ?? 'melakukan unit ini') . '?'), ENT_XML1);

            $tblXml = '<w:tbl>' .
                '<w:tblPr><w:tblStyle w:val="a1"/><w:tblW w:w="9793" w:type="dxa"/><w:tblInd w:w="124" w:type="dxa"/><w:tblBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="000000"/><w:left w:val="single" w:sz="4" w:space="0" w:color="000000"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="000000"/><w:right w:val="single" w:sz="4" w:space="0" w:color="000000"/><w:insideH w:val="single" w:sz="4" w:space="0" w:color="000000"/><w:insideV w:val="single" w:sz="4" w:space="0" w:color="000000"/></w:tblBorders><w:tblLayout w:type="fixed"/><w:tblLook w:val="0000" w:firstRow="0" w:lastRow="0" w:firstColumn="0" w:lastColumn="0" w:noHBand="0" w:noVBand="0"/></w:tblPr>' .
                '<w:tblGrid><w:gridCol w:w="2126"/><w:gridCol w:w="1346"/><w:gridCol w:w="307"/><w:gridCol w:w="1572"/><w:gridCol w:w="535"/><w:gridCol w:w="540"/><w:gridCol w:w="3367"/></w:tblGrid>' .
                // Row 0: Unit Header
                '<w:tr w:rsidTr="00E77E4E">' .
                    '<w:trPr><w:trHeight w:val="395"/></w:trPr>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="2126" w:type="dxa"/><w:vMerge w:val="restart"/></w:tcPr>' .
                        '<w:p><w:pPr><w:spacing w:before="11"/><w:rPr><w:b/><w:color w:val="000000"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr></w:p>' .
                        '<w:p><w:pPr><w:spacing w:before="1"/><w:ind w:left="136"/><w:rPr><w:b/><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>Unit Kompetensi ' . $unitNum . '</w:t></w:r></w:p>' .
                    '</w:tc>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="1346" w:type="dxa"/></w:tcPr>' .
                        '<w:p><w:pPr><w:spacing w:before="52"/><w:ind w:left="107"/><w:rPr><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>Kode Unit</w:t></w:r></w:p>' .
                    '</w:tc>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="307" w:type="dxa"/></w:tcPr>' .
                        '<w:p><w:pPr><w:spacing w:line="291" w:lineRule="auto"/><w:ind w:left="105"/><w:rPr><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>:</w:t></w:r></w:p>' .
                    '</w:tc>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="6014" w:type="dxa"/><w:gridSpan w:val="4"/><w:vAlign w:val="center"/></w:tcPr>' .
                        '<w:p><w:pPr><w:ind w:left="54"/><w:rPr><w:color w:val="000000"/></w:rPr></w:pPr><w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t>' . $kodeUnit . '</w:t></w:r></w:p>' .
                    '</w:tc>' .
                '</w:tr>' .
                // Row 1: Judul Unit
                '<w:tr w:rsidTr="00E77E4E">' .
                    '<w:trPr><w:trHeight w:val="398"/></w:trPr>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="2126" w:type="dxa"/><w:vMerge/></w:tcPr>' .
                        '<w:p><w:pPr><w:spacing w:line="276" w:lineRule="auto"/><w:rPr><w:color w:val="000000"/></w:rPr></w:pPr></w:p>' .
                    '</w:tc>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="1346" w:type="dxa"/></w:tcPr>' .
                        '<w:p><w:pPr><w:spacing w:before="52"/><w:ind w:left="107"/><w:rPr><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>Judul Unit</w:t></w:r></w:p>' .
                    '</w:tc>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="307" w:type="dxa"/></w:tcPr>' .
                        '<w:p><w:pPr><w:spacing w:before="1"/><w:ind w:left="105"/><w:rPr><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>:</w:t></w:r></w:p>' .
                    '</w:tc>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="6014" w:type="dxa"/><w:gridSpan w:val="4"/><w:vAlign w:val="center"/></w:tcPr>' .
                        '<w:p><w:pPr><w:ind w:left="54"/><w:rPr><w:color w:val="000000"/></w:rPr></w:pPr><w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t>' . $judulUnit . '</w:t></w:r></w:p>' .
                    '</w:tc>' .
                '</w:tr>' .
                // Row 2: Question Header
                '<w:tr w:rsidTr="00C37FD1">' .
                    '<w:trPr><w:trHeight w:val="412"/></w:trPr>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="5351" w:type="dxa"/><w:gridSpan w:val="4"/></w:tcPr>' .
                        '<w:p><w:pPr><w:tabs><w:tab w:val="left" w:pos="2667"/></w:tabs><w:spacing w:before="59"/><w:ind w:left="107"/><w:rPr><w:b/><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $pertanyaanUnit . '</w:t></w:r></w:p>' .
                    '</w:tc>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="535" w:type="dxa"/></w:tcPr>' .
                        '<w:p><w:pPr><w:spacing w:before="59"/><w:ind w:left="106"/><w:rPr><w:b/><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>K</w:t></w:r></w:p>' .
                    '</w:tc>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="540" w:type="dxa"/></w:tcPr>' .
                        '<w:p><w:pPr><w:spacing w:before="59"/><w:ind w:left="90" w:right="134"/><w:jc w:val="center"/><w:rPr><w:b/><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>BK</w:t></w:r></w:p>' .
                    '</w:tc>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="3367" w:type="dxa"/></w:tcPr>' .
                        '<w:p><w:pPr><w:spacing w:before="59"/><w:ind w:left="735"/><w:rPr><w:b/><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:b/><w:color w:val="000000"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>Bukti yang relevan</w:t></w:r></w:p>' .
                    '</w:tc>' .
                '</w:tr>';

            // Element Rows
            $elemens = $unit->elemens ?? collect();
            foreach ($elemens as $eIdx => $elemen) {
                $elemenNum = $eIdx + 1;
                $namaElemen = htmlspecialchars($elemen->nama_elemen ?? '-', ENT_XML1);

                $ans = $answers->get($elemen->id);
                $isK = ($ans && $ans->status === 'K');
                $isBk = ($ans && $ans->status === 'BK');
                $bukti = htmlspecialchars($ans->bukti ?? '', ENT_XML1);

                $kSymbol = $isK ? '☑' : '☐';
                $bkSymbol = $isBk ? '☑' : '☐';

                $elemenParagraphs = '<w:p><w:pPr><w:spacing w:before="60" w:after="20"/><w:ind w:left="360" w:hanging="360"/><w:rPr><w:color w:val="000000"/></w:rPr></w:pPr><w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t>' . $elemenNum . '.</w:t></w:r><w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:tab/><w:t>Elemen : ' . $namaElemen . '</w:t></w:r></w:p>' .
                                    '<w:p><w:pPr><w:spacing w:before="20" w:after="20"/><w:ind w:left="440" w:hanging="240"/><w:rPr><w:color w:val="000000"/></w:rPr></w:pPr><w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t>•</w:t></w:r><w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:tab/><w:t>Kriteria Unjuk Kerja:</w:t></w:r></w:p>';

                $kriteriaList = $elemen->kriteria ?? collect();
                foreach ($kriteriaList as $kIdx => $kriteria) {
                    $kukNum = $elemenNum . '.' . ($kIdx + 1);
                    $kukDesc = htmlspecialchars($kriteria->deskripsi_kriteria ?? '-', ENT_XML1);
                    $elemenParagraphs .= '<w:p><w:pPr><w:spacing w:before="15" w:after="15"/><w:ind w:left="840" w:hanging="400"/><w:rPr><w:color w:val="000000"/></w:rPr></w:pPr><w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t>' . $kukNum . '</w:t></w:r><w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:tab/><w:t>' . $kukDesc . '</w:t></w:r></w:p>';
                }

                $tblXml .= '<w:tr w:rsidTr="00C37FD1">' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="5351" w:type="dxa"/><w:gridSpan w:val="4"/></w:tcPr>' .
                        $elemenParagraphs .
                    '</w:tc>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="535" w:type="dxa"/></w:tcPr>' .
                        '<w:p><w:pPr><w:spacing w:before="480"/><w:ind w:left="130"/><w:rPr><w:rFonts w:ascii="MS Gothic" w:eastAsia="MS Gothic" w:hAnsi="MS Gothic" w:cs="MS Gothic"/><w:color w:val="000000"/><w:sz w:val="32"/><w:szCs w:val="32"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii="MS Gothic" w:eastAsia="MS Gothic" w:hAnsi="MS Gothic" w:cs="MS Gothic"/><w:color w:val="000000"/><w:sz w:val="32"/><w:szCs w:val="32"/></w:rPr><w:t>' . $kSymbol . '</w:t></w:r></w:p>' .
                    '</w:tc>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="540" w:type="dxa"/></w:tcPr>' .
                        '<w:p><w:pPr><w:spacing w:before="480"/><w:ind w:left="14"/><w:jc w:val="center"/><w:rPr><w:rFonts w:ascii="MS Gothic" w:eastAsia="MS Gothic" w:hAnsi="MS Gothic" w:cs="MS Gothic"/><w:color w:val="000000"/><w:sz w:val="32"/><w:szCs w:val="32"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii="MS Gothic" w:eastAsia="MS Gothic" w:hAnsi="MS Gothic" w:cs="MS Gothic"/><w:color w:val="000000"/><w:sz w:val="32"/><w:szCs w:val="32"/></w:rPr><w:t>' . $bkSymbol . '</w:t></w:r></w:p>' .
                    '</w:tc>' .
                    '<w:tc>' .
                        '<w:tcPr><w:tcW w:w="3367" w:type="dxa"/></w:tcPr>' .
                        '<w:p><w:pPr><w:ind w:left="100"/><w:rPr><w:rFonts w:ascii="Times New Roman" w:eastAsia="Times New Roman" w:hAnsi="Times New Roman" w:cs="Times New Roman"/><w:color w:val="000000"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Times New Roman" w:eastAsia="Times New Roman" w:hAnsi="Times New Roman" w:cs="Times New Roman"/><w:color w:val="000000"/></w:rPr><w:t>' . $bukti . '</w:t></w:r></w:p>' .
                    '</w:tc>' .
                '</w:tr>';
            }

            $tblXml .= '</w:tbl>';
            $allUnitTablesXml .= $tblXml . '<w:p><w:pPr><w:spacing w:before="0" w:after="0"/></w:pPr></w:p>';
        }

        // Replace Table 2 in document XML with all generated unit tables
        preg_match_all('/<w:tbl\b[^>]*>(.*?)<\/w:tbl>/s', $xml, $tables);
        if (isset($tables[0][2])) {
            $origTable2 = $tables[0][2];
            $xml = str_replace($origTable2, $allUnitTablesXml, $xml);
        }

        // 3. Fill Table 3 (Rekomendasi & Tanda Tangan)
        $rekVal = (string) ($pivot->rekomendasi ?? '');
        if ($rekVal === 'lanjut') {
            $rekomendasiXml = '<w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t xml:space="preserve">Asesmen dapat / </w:t></w:r>' .
                              '<w:r><w:rPr><w:color w:val="000000"/><w:strike/></w:rPr><w:t>tidak dapat</w:t></w:r>' .
                              '<w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t xml:space="preserve"> dilanjutkan</w:t></w:r>';
        } elseif ($rekVal === 'tidak_lanjut') {
            $rekomendasiXml = '<w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t xml:space="preserve">Asesmen </w:t></w:r>' .
                              '<w:r><w:rPr><w:color w:val="000000"/><w:strike/></w:rPr><w:t>dapat</w:t></w:r>' .
                              '<w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t xml:space="preserve"> / tidak dapat dilanjutkan</w:t></w:r>';
        } else {
            $rekomendasiXml = '<w:r><w:rPr><w:color w:val="000000"/></w:rPr><w:t>Asesmen dapat / tidak dapat dilanjutkan</w:t></w:r>';
        }

        $tglAsesi = $pivot->reviewed_at ? date('d-m-Y', strtotime($pivot->reviewed_at)) : ($pivot->updated_at ? date('d-m-Y', strtotime($pivot->updated_at)) : '-');
        $tglAsesor = $pivot->tanggal_tanda_tangan_asesor ? date('d-m-Y', strtotime($pivot->tanggal_tanda_tangan_asesor)) : ($pivot->reviewed_at ? date('d-m-Y', strtotime($pivot->reviewed_at)) : '-');

        $xml = preg_replace('/<w:r\b[^>]*>(?:(?!<\/w:r>).)*?\$\{(?:rekomendasi|rekomendasi_asesi)\}.*?<\/w:r>/s', $rekomendasiXml, $xml);
        $xml = str_replace('${rekomendasi}', $rekomendasiXml, $xml);
        $xml = str_replace('${rekomendasi_asesi}', $rekomendasiXml, $xml);
        $xml = str_replace('${nama_asesi}', htmlspecialchars($asesi->nama ?? '-', ENT_XML1), $xml);
        $xml = str_replace('${tanggal_ttd_asesi}', htmlspecialchars($tglAsesi, ENT_XML1), $xml);
        $xml = str_replace('${nama_asesor}', htmlspecialchars($asesor->nama ?? '-', ENT_XML1), $xml);
        $xml = str_replace('${no_reg_asesor}', htmlspecialchars($asesor->no_met ?? '-', ENT_XML1), $xml);
        $xml = str_replace('${noreg_asesor}', htmlspecialchars($asesor->no_met ?? '-', ENT_XML1), $xml);
        $xml = str_replace('${tanggal_ttd_asesor}', htmlspecialchars($tglAsesor, ENT_XML1), $xml);

        // Make Table 3 (Rekomendasi & TTD) stay united on the same page (no splitting across pages)
        preg_match_all('/<w:tbl\b[^>]*>(.*?)<\/w:tbl>/s', $xml, $tablesAfter);
        if (!empty($tablesAfter[0])) {
            $t3Current = end($tablesAfter[0]);
            $t3KeepTogether = preg_replace_callback('/<w:tr\b([^>]*)>(.*?)<\/w:tr>/s', function($trMatches) {
                $trAttr = $trMatches[1];
                $trContent = $trMatches[2];

                if (preg_match('/<w:trPr>(.*?)<\/w:trPr>/s', $trContent, $prMatches)) {
                    if (!str_contains($prMatches[1], '<w:cantSplit')) {
                        $newPr = '<w:trPr><w:cantSplit/>' . $prMatches[1] . '</w:trPr>';
                        $trContent = str_replace($prMatches[0], $newPr, $trContent);
                    }
                } else {
                    $trContent = '<w:trPr><w:cantSplit/></w:trPr>' . $trContent;
                }

                $trContent = preg_replace_callback('/<w:p\b([^>]*)>(.*?)<\/w:p>/s', function($pMatches) {
                    $pAttr = $pMatches[1];
                    $pContent = $pMatches[2];
                    if (preg_match('/<w:pPr>(.*?)<\/w:pPr>/s', $pContent, $pPrMatches)) {
                        if (!str_contains($pPrMatches[1], '<w:keepNext')) {
                            $newPPr = '<w:pPr><w:keepNext/><w:keepLines/>' . $pPrMatches[1] . '</w:pPr>';
                            $pContent = str_replace($pPrMatches[0], $newPPr, $pContent);
                        }
                    } else {
                        $pContent = '<w:pPr><w:keepNext/><w:keepLines/></w:pPr>' . $pContent;
                    }
                    return '<w:p' . $pAttr . '>' . $pContent . '</w:p>';
                }, $trContent);

                return '<w:tr' . $trAttr . '>' . $trContent . '</w:tr>';
            }, $t3Current);

            $xml = str_replace($t3Current, $t3KeepTogether, $xml);
        }

        $zip->addFromString('word/document.xml', $xml);
        $zip->close();

        // 4. Load with TemplateProcessor to embed signatures cleanly
        $tp = new TemplateProcessor($intermediateFile);

        $ttdAsesiImage = $this->resolveSignatureImage($pivot->tanda_tangan ?? null);
        if ($ttdAsesiImage) {
            $tp->setImageValue('ttd_asesi', [
                'path' => $ttdAsesiImage,
                'width' => 140,
                'height' => 55,
                'ratio' => false,
            ]);
        } else {
            $tp->setValue('ttd_asesi', '');
        }

        $ttdAsesorImage = $this->resolveSignatureImage($pivot->tanda_tangan_asesor ?? null);
        if ($ttdAsesorImage) {
            $tp->setImageValue('ttd_asesor', [
                'path' => $ttdAsesorImage,
                'width' => 140,
                'height' => 55,
                'ratio' => false,
            ]);
        } else {
            $tp->setValue('ttd_asesor', '');
        }

        $finalOutput = storage_path('app/temp/' . uniqid('fr_apl_02_') . '.docx');
        if (!is_dir(dirname($finalOutput))) {
            mkdir(dirname($finalOutput), 0755, true);
        }
        $tp->saveAs($finalOutput);
        @unlink($intermediateFile);

        $fileSkema = preg_replace('/[^A-Za-z0-9\-]+/', '-', (string) ($skema->nomor_skema ?? $skema->id));
        $fileName = 'FR.APL.02-' . $asesi->NIK . '-' . trim($fileSkema, '-') . '.docx';

        return response()->download($finalOutput, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Resolve signature to temporary local image path.
     */
    private function resolveSignatureImage(?string $signatureValue): ?string
    {
        if (empty($signatureValue)) {
            return null;
        }

        if (str_starts_with($signatureValue, 'data:image')) {
            $parts = explode('base64,', $signatureValue);
            $binary = base64_decode(end($parts), true);
            if ($binary && strlen($binary) > 50) {
                $tempPath = storage_path('app/temp/sig_' . uniqid() . '.png');
                if (!is_dir(dirname($tempPath))) {
                    mkdir(dirname($tempPath), 0755, true);
                }
                file_put_contents($tempPath, $binary);
                return $tempPath;
            }
            return null;
        }

        $filePath = storage_path('app/public/' . ltrim($signatureValue, '/'));
        if (file_exists($filePath)) {
            return $filePath;
        }

        return null;
    }
}
