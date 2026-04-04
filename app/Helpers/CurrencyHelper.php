<?php

namespace App\Helpers;

use App\Models\SystemSetting;

class CurrencyHelper
{
    /**
     * Full currency list: code => [symbol, name, flag]
     */
    public static function all(): array
    {
        return [
            // Middle East
            'AED' => ['symbol' => 'AED', 'name' => 'UAE Dirham',          'flag' => '🇦🇪'],
            'SAR' => ['symbol' => 'SAR', 'name' => 'Saudi Riyal',         'flag' => '🇸🇦'],
            'KWD' => ['symbol' => 'KD',  'name' => 'Kuwaiti Dinar',       'flag' => '🇰🇼'],
            'QAR' => ['symbol' => 'QR',  'name' => 'Qatari Riyal',        'flag' => '🇶🇦'],
            'BHD' => ['symbol' => 'BD',  'name' => 'Bahraini Dinar',      'flag' => '🇧🇭'],
            'OMR' => ['symbol' => 'OMR', 'name' => 'Omani Rial',          'flag' => '🇴🇲'],
            'JOD' => ['symbol' => 'JD',  'name' => 'Jordanian Dinar',     'flag' => '🇯🇴'],
            'EGP' => ['symbol' => 'EGP', 'name' => 'Egyptian Pound',      'flag' => '🇪🇬'],
            'IQD' => ['symbol' => 'IQD', 'name' => 'Iraqi Dinar',         'flag' => '🇮🇶'],
            'LBP' => ['symbol' => 'LBP', 'name' => 'Lebanese Pound',      'flag' => '🇱🇧'],
            'SYP' => ['symbol' => 'SYP', 'name' => 'Syrian Pound',        'flag' => '🇸🇾'],
            'MAD' => ['symbol' => 'MAD', 'name' => 'Moroccan Dirham',     'flag' => '🇲🇦'],
            'TND' => ['symbol' => 'TND', 'name' => 'Tunisian Dinar',      'flag' => '🇹🇳'],
            'DZD' => ['symbol' => 'DZD', 'name' => 'Algerian Dinar',      'flag' => '🇩🇿'],
            'LYD' => ['symbol' => 'LYD', 'name' => 'Libyan Dinar',        'flag' => '🇱🇾'],
            'SDG' => ['symbol' => 'SDG', 'name' => 'Sudanese Pound',      'flag' => '🇸🇩'],
            'YER' => ['symbol' => 'YER', 'name' => 'Yemeni Rial',         'flag' => '🇾🇪'],
            // Major Global
            'USD' => ['symbol' => '$',   'name' => 'US Dollar',           'flag' => '🇺🇸'],
            'EUR' => ['symbol' => '€',   'name' => 'Euro',                'flag' => '🇪🇺'],
            'GBP' => ['symbol' => '£',   'name' => 'British Pound',       'flag' => '🇬🇧'],
            'CHF' => ['symbol' => 'CHF', 'name' => 'Swiss Franc',         'flag' => '🇨🇭'],
            'CAD' => ['symbol' => 'C$',  'name' => 'Canadian Dollar',     'flag' => '🇨🇦'],
            'AUD' => ['symbol' => 'A$',  'name' => 'Australian Dollar',   'flag' => '🇦🇺'],
            'JPY' => ['symbol' => '¥',   'name' => 'Japanese Yen',        'flag' => '🇯🇵'],
            'CNY' => ['symbol' => '¥',   'name' => 'Chinese Yuan',        'flag' => '🇨🇳'],
            'INR' => ['symbol' => '₹',   'name' => 'Indian Rupee',        'flag' => '🇮🇳'],
            'RUB' => ['symbol' => '₽',   'name' => 'Russian Ruble',       'flag' => '🇷🇺'],
            'TRY' => ['symbol' => '₺',   'name' => 'Turkish Lira',        'flag' => '🇹🇷'],
            'PKR' => ['symbol' => '₨',   'name' => 'Pakistani Rupee',     'flag' => '🇵🇰'],
            'BDT' => ['symbol' => '৳',   'name' => 'Bangladeshi Taka',    'flag' => '🇧🇩'],
            'MYR' => ['symbol' => 'RM',  'name' => 'Malaysian Ringgit',   'flag' => '🇲🇾'],
            'SGD' => ['symbol' => 'S$',  'name' => 'Singapore Dollar',    'flag' => '🇸🇬'],
            'HKD' => ['symbol' => 'HK$', 'name' => 'Hong Kong Dollar',    'flag' => '🇭🇰'],
            'KRW' => ['symbol' => '₩',   'name' => 'South Korean Won',    'flag' => '🇰🇷'],
            'BRL' => ['symbol' => 'R$',  'name' => 'Brazilian Real',      'flag' => '🇧🇷'],
            'ZAR' => ['symbol' => 'R',   'name' => 'South African Rand',  'flag' => '🇿🇦'],
            'NGN' => ['symbol' => '₦',   'name' => 'Nigerian Naira',      'flag' => '🇳🇬'],
        ];
    }

    /**
     * Get the active currency code from settings.
     */
    public static function code(): string
    {
        return SystemSetting::get('site_currency', 'AED');
    }

    /**
     * Get the active currency symbol.
     */
    public static function symbol(): string
    {
        $code = self::code();
        return self::all()[$code]['symbol'] ?? $code;
    }

    /**
     * Get the active currency name.
     */
    public static function name(): string
    {
        $code = self::code();
        return self::all()[$code]['name'] ?? $code;
    }

    /**
     * Format a number with the active currency.
     * e.g. format(1500) → "AED 1,500" or "1,500 AED"
     */
    public static function format(float|int|string $amount, int $decimals = 0): string
    {
        $symbol   = self::symbol();
        $position = SystemSetting::get('currency_position', 'before');
        $formatted = number_format((float) $amount, $decimals, '.', ',');

        return $position === 'after'
            ? "{$formatted} {$symbol}"
            : "{$symbol} {$formatted}";
    }

    /**
     * Return just the symbol (for prefix/suffix inputs).
     */
    public static function prefix(): string
    {
        $position = SystemSetting::get('currency_position', 'before');
        return $position === 'before' ? self::symbol() : '';
    }

    public static function suffix(): string
    {
        $position = SystemSetting::get('currency_position', 'before');
        return $position === 'after' ? self::symbol() : '';
    }
}
