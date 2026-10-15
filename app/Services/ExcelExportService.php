<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExportService
{
    /**
     * Stream a professionally formatted Excel spreadsheet (.xls)
     *
     * @param string $filename File name with .xls extension
     * @param string $reportTitle Title displayed on the formal campus letterhead
     * @param array $headers List of column header names
     * @param array $rows Array of rows, where each cell can be a string or ['value' => ..., 'type' => 'text'|'badge-green'|'badge-amber'|'badge-red'|'badge-blue'|'center'|'left'|'right']
     * @param array $metadata Key-value pairs for metadata banner (e.g. ['Divisi' => 'Semua', 'Waktu Unduh' => '...'])
     * @param string|null $summaryText Text displayed in summary row
     */
    public static function streamTable(
        string $filename,
        string $reportTitle,
        array $headers,
        array $rows,
        array $metadata = [],
        ?string $summaryText = null
    ): StreamedResponse {
        if (!str_ends_with(strtolower($filename), '.xls')) {
            $filename .= '.xls';
        }

        $colCount = max(count($headers), 1);

        return response()->streamDownload(function () use ($reportTitle, $headers, $rows, $metadata, $summaryText, $colCount) {
            $output = fopen('php://output', 'w');

            // Header template with Microsoft Office XML namespaces and rich CSS styling
            $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' . "\n";
            $html .= '<head>' . "\n";
            $html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
            $html .= '<!--[if gte mso 9]>' . "\n";
            $html .= '<xml>' . "\n";
            $html .= '  <x:ExcelWorkbook>' . "\n";
            $html .= '    <x:ExcelWorksheets>' . "\n";
            $html .= '      <x:ExcelWorksheet>' . "\n";
            $html .= '        <x:Name>Data Laporan</x:Name>' . "\n";
            $html .= '        <x:WorksheetOptions>' . "\n";
            $html .= '          <x:DisplayGridlines/>' . "\n";
            $html .= '        </x:WorksheetOptions>' . "\n";
            $html .= '      </x:ExcelWorksheet>' . "\n";
            $html .= '    </x:ExcelWorksheets>' . "\n";
            $html .= '  </x:ExcelWorkbook>' . "\n";
            $html .= '</xml>' . "\n";
            $html .= '<![endif]-->' . "\n";
            $html .= '<style>' . "\n";
            $html .= '  body { font-family: Arial, "Segoe UI", Calibri, sans-serif; font-size: 10pt; color: #0f172a; margin: 0; padding: 10px; }' . "\n";
            $html .= '  table { border-collapse: collapse; width: 100%; }' . "\n";
            $html .= '  .kop-inst { font-size: 13pt; font-weight: bold; color: #0c2340; text-align: left; }' . "\n";
            $html .= '  .kop-sub { font-size: 10pt; font-weight: bold; color: #0284c7; text-align: left; }' . "\n";
            $html .= '  .kop-title { font-size: 12pt; font-weight: bold; color: #0f172a; text-align: left; padding-top: 4px; padding-bottom: 8px; }' . "\n";
            $html .= '  .meta-td { font-size: 9pt; color: #475569; padding: 2px 0; }' . "\n";
            $html .= '  th { background-color: #0c2340; color: #ffffff; font-weight: bold; font-size: 9.5pt; text-align: center; border: 1.5px solid #001226; padding: 10px 8px; vertical-align: middle; }' . "\n";
            $html .= '  td { font-size: 9.5pt; border: 1px solid #cbd5e1; padding: 7px 8px; vertical-align: middle; }' . "\n";
            $html .= '  .row-even { background-color: #ffffff; }' . "\n";
            $html .= '  .row-odd { background-color: #f8fafc; }' . "\n";
            $html .= '  .align-center { text-align: center; }' . "\n";
            $html .= '  .align-left { text-align: left; }' . "\n";
            $html .= '  .align-right { text-align: right; }' . "\n";
            $html .= '  .num-text { mso-number-format:"\@"; }' . "\n";
            $html .= '  .badge-green { background-color: #dcfce7; color: #166534; font-weight: bold; text-align: center; border: 1px solid #86efac; }' . "\n";
            $html .= '  .badge-amber { background-color: #fef3c7; color: #92400e; font-weight: bold; text-align: center; border: 1px solid #fde68a; }' . "\n";
            $html .= '  .badge-red { background-color: #fee2e2; color: #991b1b; font-weight: bold; text-align: center; border: 1px solid #fca5a5; }' . "\n";
            $html .= '  .badge-blue { background-color: #e0f2fe; color: #0369a1; font-weight: bold; text-align: center; border: 1px solid #bae6fd; }' . "\n";
            $html .= '  .tfoot-cell { background-color: #f1f5f9; font-weight: bold; font-size: 9.5pt; border: 1.5px solid #94a3b8; padding: 8px; }' . "\n";
            $html .= '</style>' . "\n";
            $html .= '</head>' . "\n";
            $html .= '<body>' . "\n";

            // KOP Resmi Kampus
            $html .= '<table style="margin-bottom: 12px;">' . "\n";
            $html .= '  <tr><td colspan="' . $colCount . '" class="kop-inst" style="border:none;">UNIVERSITAS BINA BANGSA GETSEMPENA (UBBG)</td></tr>' . "\n";
            $html .= '  <tr><td colspan="' . $colCount . '" class="kop-sub" style="border:none;">FAKULTAS SAINS, TEKNOLOGI, DAN ILMU KESEHATAN &bull; UKM ILMU KOMPUTER</td></tr>' . "\n";
            $html .= '  <tr><td colspan="' . $colCount . '" class="kop-title" style="border:none;">' . htmlspecialchars($reportTitle, ENT_QUOTES, 'UTF-8') . '</td></tr>' . "\n";

            if (!empty($metadata)) {
                foreach ($metadata as $label => $val) {
                    $html .= '  <tr><td colspan="' . $colCount . '" class="meta-td" style="border:none;"><strong>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . ':</strong> ' . htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8') . '</td></tr>' . "\n";
                }
            }
            $html .= '  <tr><td colspan="' . $colCount . '" style="border:none; height: 10px;"></td></tr>' . "\n";
            $html .= '</table>' . "\n";

            // Main Data Table
            $html .= '<table>' . "\n";
            $html .= '  <thead>' . "\n";
            $html .= '    <tr>' . "\n";
            foreach ($headers as $h) {
                $html .= '      <th>' . htmlspecialchars($h, ENT_QUOTES, 'UTF-8') . '</th>' . "\n";
            }
            $html .= '    </tr>' . "\n";
            $html .= '  </thead>' . "\n";
            $html .= '  <tbody>' . "\n";

            fputs($output, $html);

            // Stream rows
            foreach ($rows as $idx => $row) {
                $rowClass = ($idx % 2 === 0) ? 'row-even' : 'row-odd';
                $rowHtml = '    <tr class="' . $rowClass . '">' . "\n";

                foreach ($row as $cell) {
                    $val = is_array($cell) ? ($cell['value'] ?? '') : (string)$cell;
                    $type = is_array($cell) ? ($cell['type'] ?? 'left') : 'left';

                    $classes = [];
                    if ($type === 'text') {
                        $classes[] = 'num-text';
                        $classes[] = 'align-center';
                    } elseif ($type === 'center') {
                        $classes[] = 'align-center';
                    } elseif ($type === 'right') {
                        $classes[] = 'align-right';
                    } elseif ($type === 'badge-green') {
                        $classes[] = 'badge-green';
                    } elseif ($type === 'badge-amber') {
                        $classes[] = 'badge-amber';
                    } elseif ($type === 'badge-red') {
                        $classes[] = 'badge-red';
                    } elseif ($type === 'badge-blue') {
                        $classes[] = 'badge-blue';
                    } else {
                        $classes[] = 'align-left';
                    }

                    $classAttr = !empty($classes) ? ' class="' . implode(' ', $classes) . '"' : '';
                    $escaped = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
                    $rowHtml .= '      <td' . $classAttr . '>' . $escaped . '</td>' . "\n";
                }

                $rowHtml .= '    </tr>' . "\n";
                fputs($output, $rowHtml);
            }

            // Summary Footer
            $footerHtml = '  </tbody>' . "\n";
            $footerHtml .= '  <tfoot>' . "\n";
            $footerHtml .= '    <tr>' . "\n";
            $footerHtml .= '      <td colspan="' . $colCount . '" class="tfoot-cell">' . htmlspecialchars($summaryText ?? ('Total Data: ' . count($rows) . ' baris tercatat.'), ENT_QUOTES, 'UTF-8') . '</td>' . "\n";
            $footerHtml .= '    </tr>' . "\n";
            $footerHtml .= '  </tfoot>' . "\n";
            $footerHtml .= '</table>' . "\n";
            $footerHtml .= '</body>' . "\n";
            $footerHtml .= '</html>' . "\n";

            fputs($output, $footerHtml);
            fclose($output);
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0, must-revalidate',
            'Pragma' => 'public',
        ]);
    }
}
