<?php

namespace App\Services;

use App\Models\CsvData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CsvParserService
{
    /**
     * Parse and process CSV file with UPSERT functionality
     */
    public function parseAndImport(string $filePath): array
    {
        $stats = [
            'total_rows' => 0,
            'inserted' => 0,
            'updated' => 0,
            'errors' => 0,
            'error_messages' => []
        ];

        try {
            if (!file_exists($filePath)) {
                throw new \Exception("File not found: {$filePath}");
            }

            $fileContent = file_get_contents($filePath);
            $cleanContent = $this->cleanUtf8($fileContent);

            $tempFile = tempnam(sys_get_temp_dir(), 'csv_');
            file_put_contents($tempFile, $cleanContent);

            $handle = fopen($tempFile, 'r');

            if ($handle === false) {
                throw new \Exception("Unable to open file for reading");
            }

            $headers = fgetcsv($handle);

            if ($headers === false) {
                throw new \Exception("Unable to read CSV headers");
            }

            $headers = array_map(function($header) {
                return $this->cleanUtf8(trim($header));
            }, $headers);

            $headerMap = $this->mapHeaders($headers);

            while (($row = fgetcsv($handle)) !== false) {
                $stats['total_rows']++;

                try {
                    $rowData = $this->extractRowData($row, $headers, $headerMap);

                    if (empty($rowData['unique_key'])) {
                        $stats['errors']++;
                        $stats['error_messages'][] = "Row {$stats['total_rows']}: Missing UNIQUE_KEY";
                        continue;
                    }

                    $existing = CsvData::where('unique_key', $rowData['unique_key'])->first();

                    if ($existing) {
                        $existing->update($rowData);
                        $stats['updated']++;
                    } else {
                        CsvData::create($rowData);
                        $stats['inserted']++;
                    }

                } catch (\Exception $e) {
                    $stats['errors']++;
                    $stats['error_messages'][] = "Row {$stats['total_rows']}: " . $e->getMessage();
                    Log::error("CSV Row Error", [
                        'row' => $stats['total_rows'],
                        'error' => $e->getMessage()
                    ]);
                }
            }

            fclose($handle);
            unlink($tempFile);

        } catch (\Exception $e) {
            $stats['errors']++;
            $stats['error_messages'][] = $e->getMessage();
            Log::error("CSV Import Error", ['error' => $e->getMessage()]);
        }

        return $stats;
    }

    /**
     * Clean non-UTF-8 characters from string
     */
    private function cleanUtf8(string $string): string
    {
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');

        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $string);

        return $string;
    }

    /**
     * Map CSV headers to database columns
     */
    private function mapHeaders(array $headers): array
    {
        $map = [];

        $columnMapping = [
            'UNIQUE_KEY' => 'unique_key',
            'PRODUCT_TITLE' => 'product_title',
            'PRODUCT_DESCRIPTION' => 'product_description',
            'STYLE#' => 'style',
            'SANMAR_MAINFRAME_COLOR' => 'sanmar_mainframe_color',
            'SIZE' => 'size',
            'COLOR_NAME' => 'color_name',
            'PIECE_PRICE' => 'piece_price',
        ];

        foreach ($headers as $index => $header) {
            $normalizedHeader = strtoupper(trim($header));

            if (isset($columnMapping[$normalizedHeader])) {
                $map[$index] = $columnMapping[$normalizedHeader];
            }
        }

        return $map;
    }

    /**
     * Extract data from CSV row based on header mapping
     */
    private function extractRowData(array $row, array $headers, array $headerMap): array
    {
        $data = [];

        foreach ($headerMap as $index => $columnName) {
            $value = isset($row[$index]) ? $this->cleanUtf8(trim($row[$index])) : null;
            $data[$columnName] = $value;
        }

        return $data;
    }
}
