<?php
/**
 * =====================================================
 * FILE: scripts/generate_word_docx.php
 * Chuyển đổi file Markdown TEST_PLAN_AND_CASES.md sang Word (.docx & .doc)
 * =====================================================
 */

$mdFile = __DIR__ . '/../docs/TEST_PLAN_AND_CASES.md';
if (!file_exists($mdFile)) {
    die("Không tìm thấy file $mdFile\n");
}

$content = file_get_contents($mdFile);
$lines = explode("\n", $content);

// -----------------------------------------------------
// 1. TẠO TẬP TIN .DOC (HTML Word format - hỗ trợ style đầy đủ)
// -----------------------------------------------------
function generateWordDoc($content, $outputPath) {
    // Chuyển markdown cơ bản sang HTML đẹp cho Word
    $lines = explode("\n", $content);
    $htmlBody = "";
    $inTable = false;
    $isTableHeader = false;

    foreach ($lines as $line) {
        $trimmed = trim($line);

        // Bỏ qua dòng separator bảng |:---|
        if (preg_match('/^\|(?:\s*:?-+:?\s*\|)+$/', $trimmed)) {
            continue;
        }

        // Bảng
        if (str_starts_with($trimmed, '|')) {
            if (!$inTable) {
                $htmlBody .= "<table style='width: 100%; border-collapse: collapse; margin-top: 12pt; margin-bottom: 12pt; font-size: 10pt; font-family: \"Times New Roman\", serif;'>\n";
                $inTable = true;
                $isTableHeader = true;
            }

            $cells = explode('|', $trimmed);
            array_shift($cells); // bỏ phần tử đầu trống
            array_pop($cells);   // bỏ phần tử cuối trống

            $htmlBody .= "<tr>\n";
            $bg = $isTableHeader ? "background-color: #065f46; color: #ffffff; font-weight: bold; text-align: center;" : "background-color: #ffffff;";
            
            foreach ($cells as $idx => $cell) {
                $cellText = trim($cell);
                // Parse bold, code, br
                $cellText = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $cellText);
                $cellText = preg_replace('/`(.*?)`/', '<code style="color: #991b1b; background: #f1f5f9; padding: 1px 4px; font-family: Consolas, monospace;">$1</code>', $cellText);
                $cellText = str_replace(['PASS', 'CLOSED', 'FAIL'], [
                    '<span style="color: #166534; font-weight: bold; background: #dcfce7; padding: 2px 6px; border-radius: 4px;">PASS</span>',
                    '<span style="color: #1e293b; font-weight: bold; background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">CLOSED</span>',
                    '<span style="color: #991b1b; font-weight: bold; background: #fee2e2; padding: 2px 6px; border-radius: 4px;">FAIL</span>'
                ], $cellText);

                $tag = $isTableHeader ? "th" : "td";
                $align = ($isTableHeader || $idx === 0 || $idx >= count($cells) - 2) ? "center" : "left";
                $htmlBody .= "<$tag style='border: 1px solid #cbd5e1; padding: 6pt 8pt; text-align: $align; $bg'>$cellText</$tag>\n";
            }
            $htmlBody .= "</tr>\n";
            $isTableHeader = false;
            continue;
        } else {
            if ($inTable) {
                $htmlBody .= "</table>\n";
                $inTable = false;
            }
        }

        // Tiêu đề
        if (str_starts_with($trimmed, '# ')) {
            $title = substr($trimmed, 2);
            $htmlBody .= "<h1 style='color: #0f172a; text-align: center; font-family: \"Times New Roman\", serif; font-size: 20pt; margin-top: 18pt; margin-bottom: 6pt;'>$title</h1>\n";
            continue;
        }
        if (str_starts_with($trimmed, '## ')) {
            $h2 = substr($trimmed, 3);
            $htmlBody .= "<h2 style='color: #065f46; font-family: \"Times New Roman\", serif; font-size: 15pt; border-bottom: 1.5pt solid #065f46; padding-bottom: 4pt; margin-top: 18pt; margin-bottom: 8pt;'>$h2</h2>\n";
            continue;
        }
        if (str_starts_with($trimmed, '### ')) {
            $h3 = substr($trimmed, 4);
            $htmlBody .= "<h3 style='color: #047857; font-family: \"Times New Roman\", serif; font-size: 13pt; margin-top: 14pt; margin-bottom: 6pt;'>$h3</h3>\n";
            continue;
        }

        // Danh sách gạch đầu dòng
        if (str_starts_with($trimmed, '- ') || str_starts_with($trimmed, '* ')) {
            $item = substr($trimmed, 2);
            $item = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $item);
            $item = preg_replace('/`(.*?)`/', '<code style="color: #991b1b; background: #f1f5f9; padding: 1px 4px; font-family: Consolas, monospace;">$1</code>', $item);
            $htmlBody .= "<p style='margin: 3pt 0 3pt 20pt; text-indent: -12pt; font-family: \"Times New Roman\", serif; font-size: 12pt; line-height: 1.3;'>• $item</p>\n";
            continue;
        }

        // Đường kẻ ngang
        if ($trimmed === '---') {
            $htmlBody .= "<hr style='border: none; border-top: 1px solid #cbd5e1; margin: 12pt 0;' />\n";
            continue;
        }

        // Đoạn văn bình thường
        if (!empty($trimmed)) {
            $p = $trimmed;
            $p = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $p);
            $p = preg_replace('/`(.*?)`/', '<code style="color: #991b1b; background: #f1f5f9; padding: 1px 4px; font-family: Consolas, monospace;">$1</code>', $p);
            $htmlBody .= "<p style='font-family: \"Times New Roman\", serif; font-size: 12pt; line-height: 1.4; margin: 6pt 0;'>$p</p>\n";
        }
    }

    if ($inTable) {
        $htmlBody .= "</table>\n";
    }

    $docTemplate = '<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta charset="utf-8">
<title>Báo cáo kiểm thử FreshMart</title>
<!--[if gte mso 9]>
<xml>
 <w:WordDocument>
  <w:View>Print</w:View>
  <w:Zoom>100</w:Zoom>
  <w:DoNotOptimizeForBrowser/>
 </w:WordDocument>
</xml>
<![endif]-->
<style>
@page Section1 {
    size: 210mm 297mm;
    margin: 20mm 20mm 20mm 20mm;
    mso-header-margin: 35.4pt;
    mso-footer-margin: 35.4pt;
    mso-paper-source: 0;
}
div.Section1 { page: Section1; }
body { font-family: "Times New Roman", serif; font-size: 12pt; color: #1e293b; }
table, th, td { border: 1px solid #cbd5e1; border-collapse: collapse; }
</style>
</head>
<body>
<div class="Section1">
' . $htmlBody . '
</div>
</body>
</html>';

    file_put_contents($outputPath, $docTemplate);
    echo "Đã tạo file Word: $outputPath\n";
}

// -----------------------------------------------------
// 2. TẠO TẬP TIN .DOCX (Chuẩn OpenXML Office Document)
// -----------------------------------------------------
function generateWordDocx($content, $outputPath) {
    $zip = new ZipArchive();
    if ($zip->open($outputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        die("Không thể tạo file zip $outputPath\n");
    }

    // [Content_Types].xml
    $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
</Types>';
    $zip->addFromString('[Content_Types].xml', $contentTypes);

    // _rels/.rels
    $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>';
    $zip->addFromString('_rels/.rels', $rels);

    // word/_rels/document.xml.rels
    $docRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
    $zip->addFromString('word/_rels/document.xml.rels', $docRels);

    // word/styles.xml
    $stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:docDefaults>
    <w:rPrDefault>
      <w:rPr>
        <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman" w:cs="Times New Roman"/>
        <w:sz w:val="24"/>
        <w:color w:val="1E293B"/>
      </w:rPr>
    </w:rPrDefault>
  </w:docDefaults>
</w:styles>';
    $zip->addFromString('word/styles.xml', $stylesXml);

    // Xử lý document.xml
    $docBody = "";
    $lines = explode("\n", $content);
    $inTable = false;
    $isTableHeader = false;

    function escapeXml($str) {
        return htmlspecialchars($str, ENT_XML1, 'UTF-8');
    }

    function parseFormattedRuns($text, $defaultColor = '1E293B', $defaultSize = '24') {
        // Tách nhỏ chuỗi theo bold và code
        $xml = '';
        // Chuẩn hóa br
        $parts = explode('<br>', $text);
        for ($pIdx = 0; $pIdx < count($parts); $pIdx++) {
            if ($pIdx > 0) {
                $xml .= '<w:r><w:br/></w:r>';
            }
            $lineText = $parts[$pIdx];
            
            // Xử lý các token: **bold**, `code`
            $pattern = '/(\*\*.*?\*\*|`.*?`)/';
            $tokens = preg_split($pattern, $lineText, -1, PREG_SPLIT_DELIM_CAPTURE);
            foreach ($tokens as $token) {
                if (empty($token)) continue;
                if (str_starts_with($token, '**') && str_ends_with($token, '**')) {
                    $val = substr($token, 2, -2);
                    $color = $defaultColor;
                    if ($val === 'PASS') $color = '166534';
                    elseif ($val === 'FAIL') $color = '991B1B';
                    $xml .= '<w:r><w:rPr><w:b/><w:sz w:val="' . $defaultSize . '"/><w:color w:val="' . $color . '"/></w:rPr><w:t xml:space="preserve">' . escapeXml($val) . '</w:t></w:r>';
                } elseif (str_starts_with($token, '`') && str_ends_with($token, '`')) {
                    $val = substr($token, 1, -1);
                    $xml .= '<w:r><w:rPr><w:rFonts w:ascii="Consolas" w:hAnsi="Consolas"/><w:sz w:val="' . $defaultSize . '"/><w:color w:val="991B1B"/></w:rPr><w:t xml:space="preserve">' . escapeXml($val) . '</w:t></w:r>';
                } else {
                    $xml .= '<w:r><w:rPr><w:sz w:val="' . $defaultSize . '"/><w:color w:val="' . $defaultColor . '"/></w:rPr><w:t xml:space="preserve">' . escapeXml($token) . '</w:t></w:r>';
                }
            }
        }
        return $xml;
    }

    foreach ($lines as $line) {
        $trimmed = trim($line);

        if (preg_match('/^\|(?:\s*:?-+:?\s*\|)+$/', $trimmed)) {
            continue;
        }

        // Bảng
        if (str_starts_with($trimmed, '|')) {
            if (!$inTable) {
                $docBody .= '<w:tbl>';
                $docBody .= '<w:tblPr>
                  <w:tblW w:w="0" w:type="auto"/>
                  <w:tblBorders>
                    <w:top w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
                    <w:left w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
                    <w:bottom w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
                    <w:right w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
                    <w:insideH w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>
                    <w:insideV w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>
                  </w:tblBorders>
                  <w:tblCellMar>
                    <w:top w:w="120" w:type="dxa"/>
                    <w:left w:w="160" w:type="dxa"/>
                    <w:bottom w:w="120" w:type="dxa"/>
                    <w:right w:w="160" w:type="dxa"/>
                  </w:tblCellMar>
                </w:tblPr>';
                $inTable = true;
                $isTableHeader = true;
            }

            $cells = explode('|', $trimmed);
            array_shift($cells);
            array_pop($cells);

            $docBody .= '<w:tr><w:trPr>';
            if ($isTableHeader) {
                $docBody .= '<w:tblHeader/>';
            }
            $docBody .= '<w:cantSplit/></w:trPr>';

            foreach ($cells as $idx => $cell) {
                $cellText = trim($cell);
                $bgColor = $isTableHeader ? '065F46' : 'FFFFFF';
                $textColor = $isTableHeader ? 'FFFFFF' : '1E293B';
                $fontSize = '20'; // 10pt

                $docBody .= '<w:tc>';
                $docBody .= '<w:tcPr><w:shd w:val="clear" w:color="auto" w:fill="' . $bgColor . '"/></w:tcPr>';
                
                $align = ($isTableHeader || $idx === 0 || $idx >= count($cells) - 2) ? 'center' : 'left';
                $docBody .= '<w:p><w:pPr><w:jc w:val="' . $align . '"/><w:spacing w:after="0" w:line="240" w:lineRule="auto"/></w:pPr>';
                
                if ($isTableHeader) {
                    $docBody .= '<w:r><w:rPr><w:b/><w:sz w:val="' . $fontSize . '"/><w:color w:val="' . $textColor . '"/></w:rPr><w:t xml:space="preserve">' . escapeXml($cellText) . '</w:t></w:r>';
                } else {
                    $docBody .= parseFormattedRuns($cellText, $textColor, $fontSize);
                }
                $docBody .= '</w:p></w:tc>';
            }
            $docBody .= '</w:tr>';
            $isTableHeader = false;
            continue;
        } else {
            if ($inTable) {
                $docBody .= '</w:tbl>';
                $inTable = false;
            }
        }

        // Tiêu đề
        if (str_starts_with($trimmed, '# ')) {
            $t = substr($trimmed, 2);
            $docBody .= '<w:p><w:pPr><w:jc w:val="center"/><w:spacing w:before="360" w:after="160"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="38"/><w:color w:val="0F172A"/></w:rPr><w:t>' . escapeXml($t) . '</w:t></w:r></w:p>';
            continue;
        }
        if (str_starts_with($trimmed, '## ')) {
            $t = substr($trimmed, 3);
            $docBody .= '<w:p><w:pPr><w:spacing w:before="300" w:after="120"/><w:pBdr><w:bottom w:val="single" w:sz="12" w:space="4" w:color="065F46"/></w:pBdr></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="30"/><w:color w:val="065F46"/></w:rPr><w:t>' . escapeXml($t) . '</w:t></w:r></w:p>';
            continue;
        }
        if (str_starts_with($trimmed, '### ')) {
            $t = substr($trimmed, 4);
            $docBody .= '<w:p><w:pPr><w:spacing w:before="240" w:after="80"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="26"/><w:color w:val="047857"/></w:rPr><w:t>' . escapeXml($t) . '</w:t></w:r></w:p>';
            continue;
        }

        // Danh sách gạch đầu dòng
        if (str_starts_with($trimmed, '- ') || str_starts_with($trimmed, '* ')) {
            $item = substr($trimmed, 2);
            $docBody .= '<w:p><w:pPr><w:ind w:left="400"/><w:spacing w:before="40" w:after="40" w:line="280" w:lineRule="auto"/></w:pPr>';
            $docBody .= '<w:r><w:rPr><w:b/><w:color w:val="065F46"/></w:rPr><w:t>• </w:t></w:r>';
            $docBody .= parseFormattedRuns($item);
            $docBody .= '</w:p>';
            continue;
        }

        // Đường kẻ ngang
        if ($trimmed === '---') {
            $docBody .= '<w:p><w:pPr><w:pBdr><w:bottom w:val="single" w:sz="6" w:space="1" w:color="CBD5E1"/></w:pBdr><w:spacing w:before="120" w:after="120"/></w:pPr></w:p>';
            continue;
        }

        // Đoạn văn
        if (!empty($trimmed)) {
            $docBody .= '<w:p><w:pPr><w:spacing w:before="60" w:after="80" w:line="300" w:lineRule="auto"/></w:pPr>';
            $docBody .= parseFormattedRuns($trimmed);
            $docBody .= '</w:p>';
        }
    }

    if ($inTable) {
        $docBody .= '</w:tbl>';
    }

    // Section Properties (Khổ giấy A4, lề 2cm = 1134 twips)
    $docBody .= '<w:sectPr>
      <w:pgSz w:w="11906" w:h="16838"/>
      <w:pgMar w:top="1134" w:right="1134" w:bottom="1134" w:left="1134"/>
    </w:sectPr>';

    $documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:body>
    ' . $docBody . '
  </w:body>
</w:document>';

    $zip->addFromString('word/document.xml', $documentXml);
    $zip->close();
    echo "Đã tạo file Word DOCX: $outputPath\n";
}

// Thực hiện sinh cả 2 file Word
$docxPath = __DIR__ . '/../docs/TEST_PLAN_AND_CASES.docx';
$docPath = __DIR__ . '/../docs/TEST_PLAN_AND_CASES.doc';

generateWordDoc($content, $docPath);
generateWordDocx($content, $docxPath);

echo "Hoàn thành chuyển đổi sang định dạng Word!\n";
