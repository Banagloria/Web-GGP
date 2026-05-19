<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class RegistrationSubmissionExportTable
{
    public const HEADER_BG = '122A4A';

    public const HEADER_FG = 'FFFFFF';

    public const ROW_BG = 'FFFFFF';

    public const ROW_ZEBRA_BG = 'F0F4F8';

    public const ROW_FG = '0F1F33';

    public const BORDER = 'D0D7E2';

    /**
     * @param  list<array{name: string, label: string, type?: string}>  $columns
     */
    public static function renderHtml(Builder $query, array $columns, ?string $title = null): string
    {
        $headers = RegistrationSubmissionService::exportHeaders($columns);
        $html = [];
        $html[] = '<!DOCTYPE html><html><head><meta charset="UTF-8">';
        $html[] = '<style>';
        $html[] = '@page{margin:12mm;}';
        $html[] = 'body{font-family:DejaVu Sans,sans-serif;font-size:9pt;color:#'.self::ROW_FG.';}';
        $html[] = 'h1{font-size:13pt;margin:0 0 12px;color:#'.self::HEADER_BG.';}';
        $html[] = 'table{border-collapse:collapse;width:100%;}';
        $html[] = 'th{background:#'.self::HEADER_BG.';color:#'.self::HEADER_FG.';font-weight:700;text-align:left;padding:8px 10px;border:1px solid #'.self::BORDER.';font-size:8.5pt;}';
        $html[] = 'td{color:#'.self::ROW_FG.';padding:6px 10px;border:1px solid #'.self::BORDER.';vertical-align:top;font-size:8.5pt;word-wrap:break-word;}';
        $html[] = 'tr.zebra td{background:#'.self::ROW_ZEBRA_BG.';}';
        $html[] = 'tr.data td{background:#'.self::ROW_BG.';}';
        $html[] = '</style></head><body>';

        if ($title !== null && $title !== '') {
            $html[] = '<h1>'.self::escape($title).'</h1>';
        }

        $html[] = '<table><thead><tr>';

        foreach ($headers as $header) {
            $html[] = '<th>'.self::escape($header).'</th>';
        }
        $html[] = '</tr></thead><tbody>';

        $rowNumber = 0;
        foreach ($query->cursor() as $row) {
            $rowNumber++;
            $zebra = $rowNumber % 2 === 0 ? ' zebra' : ' data';
            $values = RegistrationSubmissionService::exportRowValues($row, $columns, $rowNumber);
            $html[] = '<tr class="'.trim($zebra).'">';

            foreach ($values as $value) {
                $html[] = '<td>'.self::escape((string) $value).'</td>';
            }

            $html[] = '</tr>';
        }

        $html[] = '</tbody></table></body></html>';

        return implode('', $html);
    }

    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
