<?php

namespace App\Services;

use App\Models\Auction;
use App\Models\SystemSetting;

class ReferenceCodeService
{
    /**
     * Generate a unique reference code for an auction.
     *
     * Format: {PREFIX}-{YEAR}-{SEQUENCE}
     * Example: MB-2026-0042
     */
    public static function generate(): string
    {
        $prefix   = self::getPrefix();
        $year     = now()->year;
        $sequence = self::nextSequence($prefix, $year);

        return "{$prefix}-{$year}-" . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the configured prefix from system settings.
     * Falls back to 'MB' (Motor Bazar) if not set.
     */
    public static function getPrefix(): string
    {
        try {
            $setting = SystemSetting::where('key', 'ref_code_prefix')->first();
            $prefix  = $setting ? strtoupper(trim($setting->value)) : 'MB';
        } catch (\Exception $e) {
            $prefix = 'MB';
        }

        return preg_replace('/[^A-Z0-9]/', '', $prefix) ?: 'MB';
    }

    /**
     * Get all available prefix options.
     */
    public static function getPrefixOptions(): array
    {
        return [
            'MB'  => 'MB — Motor Bazar (Default)',
            'AUC' => 'AUC — Auction',
            'VEH' => 'VEH — Vehicle',
            'CAR' => 'CAR — Car Deal',
            'DL'  => 'DL — Deal',
            'REF' => 'REF — Reference',
        ];
    }

    /**
     * Calculate the next sequence number for this prefix+year combination.
     */
    private static function nextSequence(string $prefix, int $year): int
    {
        $pattern = "{$prefix}-{$year}-%";

        $last = Auction::where('reference_code', 'like', $pattern)
            ->orderByDesc('reference_code')
            ->value('reference_code');

        if (!$last) {
            return 1;
        }

        // Extract numeric part after the last dash
        $parts    = explode('-', $last);
        $lastNum  = (int) end($parts);

        return $lastNum + 1;
    }

    /**
     * Assign a fresh reference code to an auction (only if it doesn't have one).
     */
    public static function assignTo(Auction $auction): string
    {
        if ($auction->reference_code) {
            return $auction->reference_code;
        }

        $code = self::generate();

        $auction->update(['reference_code' => $code]);

        return $code;
    }
}
