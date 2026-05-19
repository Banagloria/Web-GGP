<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegistrationSubmissionExcelExport
{
    private const HEADER_BG = '122A4A';

    private const HEADER_FG = 'FFFFFF';

    private const ROW_BG = 'FFFFFF';

    private const ROW_ZEBRA_BG = 'F0F4F8';

    private const ROW_FG = '0F1F33';

    private const BORDER = 'D0D7E2';

    /**
     * @param  list<array{name: string, label: string, type?: string}>  $columns
     */
    public static function download(
        Builder $query,
        array $columns,
        string $filename,
        ?string $title = null,
    ): StreamedResponse {
        return response()->streamDownload(function () use ($query, $columns, $title): void {
            echo self::renderHtml($query, $columns, $title);
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * @param  list<array{name: string, label: string, type?: string}>  $columns
     */
    private static function renderHtml(Builder $query, array $columns, ?string $title = null): string
    {
        $headers = RegistrationSubmissionService::exportHeaders($columns);
        $html = [];
        $html[] = '<!DOCTYPE html><html><head><meta charset="UTF-8">';
        $html[] = '<style>';
        $html[] = 'body{font-family:Segoe UI,Arial,sans-serif;color:#'.self::ROW_FG.';}';
        $html[] = 'h1{font-size:14pt;font-weight:700;color:#'.self::HEADER_BG.';margin:0 0 12px;padding:0;}';
        $html[] = 'table{border-collapse:collapse;width:100%;font-family:Segoe UI,Arial,sans-serif;font-size:11pt;}';
        $html[] = 'th{background:#'.self::HEADER_BG.';color:#'.self::HEADER_FG.';font-weight:700;text-align:left;padding:10px 12px;border:1px solid #'.self::BORDER.';}';
        $html[] = 'td{color:#'.self::ROW_FG.';padding:8px 12px;border:1px solid #'.self::BORDER.';vertical-align:top;mso-number-format:"\@";}';
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

            foreach ($values as $index => $value) {
                $column = $index === 0 ? null : ($columns[$index - 1] ?? null);
                $style = self::cellStyle($column);
                $html[] = '<td'.($style !== '' ? ' style="'.$style.'"' : '').'>'.self::escape((string) $value).'</td>';
            }

            $html[] = '</tr>';
        }

        $html[] = '</tbody></table></body></html>';

        return implode('', $html);
    }

    /**
     * @param  array{name: string, label: string, type?: string}|null  $column
     */
    private static function cellStyle(?array $column): string
    {
        $parts = ['mso-number-format:"@"'];

        if ($column !== null && RegistrationSubmissionService::isTextExportColumn($column)) {
            $parts[] = 'mso-number-format:"@"';
        }

        return implode(';', array_unique($parts));
    }

    private static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
