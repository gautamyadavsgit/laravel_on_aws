<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;

class ImageSeederHelper
{
    /**
     * Ensure all seed images and sample documents exist in the public storage disk.
     */
    public static function ensureSeedAssets(): void
    {
        $disk = Storage::disk('public');

        // Ensure directories exist
        $disk->makeDirectory('property_images');
        $disk->makeDirectory('floorplan_images');
        $disk->makeDirectory('market_image');
        $disk->makeDirectory('property_documents');

        // Palette presets for luxury real estate (R, G, B)
        $palettes = [
            ['r' => 45, 'g' => 75, 'b' => 125],   // Indigo slate estate
            ['r' => 30, 'g' => 100, 'b' => 80],   // Emerald mountain retreat
            ['r' => 70, 'g' => 60, 'b' => 120],   // Royal modern penthouse
            ['r' => 140, 'g' => 70, 'b' => 50],   // Sunset beachfront villa
            ['r' => 50, 'g' => 85, 'b' => 110],   // Coastal luxury estate
            ['r' => 80, 'g' => 85, 'b' => 90],    // Minimalist urban loft
            ['r' => 60, 'g' => 110, 'b' => 90],   // Timber valley cabin
            ['r' => 95, 'g' => 55, 'b' => 85],    // Vineyard manor
            ['r' => 40, 'g' => 90, 'b' => 130],   // Waterfront haven
            ['r' => 75, 'g' => 95, 'b' => 65],    // Alpine chalet
        ];

        // 1. Generate Property Photos (800x600)
        foreach ($palettes as $index => $color) {
            $filename = 'property_images/property_'.($index + 1).'.png';
            if (! $disk->exists($filename)) {
                $png = self::createImageBinary(800, 600, $color['r'], $color['g'], $color['b'], 'photo');
                $disk->put($filename, $png);
            }
        }

        // 2. Generate Architectural Floorplans (800x600 with blueprint grid)
        for ($i = 1; $i <= 6; $i++) {
            $filename = "floorplan_images/floorplan_{$i}.png";
            if (! $disk->exists($filename)) {
                $png = self::createImageBinary(800, 600, 25, 45, 80, 'blueprint');
                $disk->put($filename, $png);
            }
        }

        // 3. Generate Market Trend Images (800x600 with chart styling)
        for ($i = 1; $i <= 6; $i++) {
            $filename = "market_image/market_{$i}.png";
            if (! $disk->exists($filename)) {
                $png = self::createImageBinary(800, 600, 35, 60, 95, 'chart');
                $disk->put($filename, $png);
            }
        }

        // 4. Generate Valid Sample PDF Legal Documents
        $documentTypes = [
            'master_deed',
            'operating_agreement',
            'rent_calculation',
            'expense_statement',
            'deed_restrictions',
            'closing_statement',
        ];

        foreach ($documentTypes as $index => $type) {
            $filename = "property_documents/{$type}_".($index + 1).'.pdf';
            if (! $disk->exists($filename)) {
                $pdf = self::createPdfBinary(ucwords(str_replace('_', ' ', $type)));
                $disk->put($filename, $pdf);
            }
        }
    }

    /**
     * Generate a valid standalone PNG binary with subtle visual patterns.
     */
    public static function createImageBinary(int $width, int $height, int $baseR, int $baseG, int $baseB, string $type = 'photo'): string
    {
        $raw = '';
        for ($y = 0; $y < $height; $y++) {
            $raw .= "\x00"; // PNG filter type 0 (None)
            for ($x = 0; $x < $width; $x++) {
                $r = $baseR;
                $g = $baseG;
                $b = $baseB;

                if ($type === 'blueprint') {
                    // Blueprint grid pattern
                    $isGridX = ($x % 40 === 0 || $x % 8 === 0 && $y % 8 === 0);
                    $isGridY = ($y % 40 === 0);
                    if ($isGridX || $isGridY) {
                        $r = min(255, $r + 70);
                        $g = min(255, $g + 70);
                        $b = min(255, $b + 90);
                    }
                } elseif ($type === 'chart') {
                    // Gradient + diagonal chart accent
                    $grad = ($x + $y) / ($width + $height);
                    $r = min(255, max(0, (int) ($r + $grad * 40 - 20)));
                    $g = min(255, max(0, (int) ($g + $grad * 50 - 25)));
                    $b = min(255, max(0, (int) ($b + $grad * 60 - 30)));
                } else {
                    // Smooth visual gradient
                    $gradX = $x / $width;
                    $gradY = $y / $height;
                    $r = min(255, max(0, (int) ($r + $gradX * 35 - 15)));
                    $g = min(255, max(0, (int) ($g + $gradY * 40 - 20)));
                    $b = min(255, max(0, (int) ($b + ($gradX + $gradY) * 25 - 20)));
                }

                $raw .= chr($r).chr($g).chr($b);
            }
        }

        $compressed = gzcompress($raw, 9);
        $png = "\x89PNG\r\n\x1a\n";

        // IHDR Chunk
        $ihdrData = pack('NNCCCCC', $width, $height, 8, 2, 0, 0, 0);
        $png .= pack('N', 13).'IHDR'.$ihdrData.pack('N', crc32('IHDR'.$ihdrData));

        // IDAT Chunk
        $png .= pack('N', strlen($compressed)).'IDAT'.$compressed.pack('N', crc32('IDAT'.$compressed));

        // IEND Chunk
        $png .= pack('N', 0).'IEND'.pack('N', crc32('IEND'));

        return $png;
    }

    /**
     * Generate a valid standalone PDF 1.4 binary.
     */
    public static function createPdfBinary(string $title): string
    {
        $content = "BT\n/F1 24 Tf\n50 720 Td\n({$title}) Tj\nET\n".
                   "BT\n/F1 12 Tf\n50 680 Td\n(Gautam Real Estate Institutional Fractional Ownership Document) Tj\nET\n".
                   "BT\n/F1 10 Tf\n50 650 Td\n(This is a verified legal pro-forma statement generated for property underwriting.) Tj\nET\n";

        $contentLength = strlen($content);

        return "%PDF-1.4\n".
               "1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n".
               "2 0 obj<</Type/Pages/Count 1/Kids[3 0 R]>>endobj\n".
               "3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<</Font<</F1 4 0 R>>>>/Contents 5 0 R>>endobj\n".
               "4 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj\n".
               "5 0 obj<</Length {$contentLength}>>\nstream\n{$content}\nendstream\nendobj\n".
               "xref\n0 6\n".
               "0000000000 65535 f \n".
               "0000000009 00000 n \n".
               "0000000052 00000 n \n".
               "0000000101 00000 n \n".
               "0000000212 00000 n \n".
               "0000000279 00000 n \n".
               "trailer<</Size 6/Root 1 0 R>>\n".
               "startxref\n380\n%%EOF\n";
    }
}
