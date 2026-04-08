<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property array $car_details
 * @property string $status
 * @property string|null $notes
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InspectionReport> $inspections
 */
class Lead extends Model
{
    // Lead Source Options
    public const SOURCE_FACEBOOK = 'facebook';
    public const SOURCE_INSTAGRAM = 'instagram';
    public const SOURCE_GOOGLE = 'google';
    public const SOURCE_BING = 'bing';
    public const SOURCE_YAHOO = 'yahoo';
    public const SOURCE_DIRECT = 'direct';
    public const SOURCE_WHATSAPP = 'whatsapp';
    public const SOURCE_TELEGRAM = 'telegram';
    public const SOURCE_LINKEDIN = 'linkedin';
    public const SOURCE_TWITTER = 'twitter';
    public const SOURCE_TIKTOK = 'tiktok';
    public const SOURCE_SNAPCHAT = 'snapchat';
    public const SOURCE_EMAIL = 'email';
    public const SOURCE_REFERRAL = 'referral';
    public const SOURCE_OTHER = 'other';

    public static function getSourceOptions(): array
    {
        return [
            self::SOURCE_FACEBOOK => 'Facebook',
            self::SOURCE_INSTAGRAM => 'Instagram',
            self::SOURCE_GOOGLE => 'Google',
            self::SOURCE_BING => 'Bing',
            self::SOURCE_YAHOO => 'Yahoo',
            self::SOURCE_DIRECT => 'Direct',
            self::SOURCE_WHATSAPP => 'WhatsApp',
            self::SOURCE_TELEGRAM => 'Telegram',
            self::SOURCE_LINKEDIN => 'LinkedIn',
            self::SOURCE_TWITTER => 'Twitter/X',
            self::SOURCE_TIKTOK => 'TikTok',
            self::SOURCE_SNAPCHAT => 'Snapchat',
            self::SOURCE_EMAIL => 'Email',
            self::SOURCE_REFERRAL => 'Referral',
            self::SOURCE_OTHER => 'Other',
        ];
    }

    public static function getSourceLabel(?string $source): string
    {
        return self::getSourceOptions()[$source] ?? 'Unknown';
    }

    public static function getSourceColor(string $source): string
    {
        return match($source) {
            self::SOURCE_FACEBOOK => '#1877F2',
            self::SOURCE_INSTAGRAM => '#E4405F',
            self::SOURCE_GOOGLE => '#4285F4',
            self::SOURCE_BING => '#008373',
            self::SOURCE_YAHOO => '#6001D2',
            self::SOURCE_DIRECT => '#10B981',
            self::SOURCE_WHATSAPP => '#25D366',
            self::SOURCE_TELEGRAM => '#0088CC',
            self::SOURCE_LINKEDIN => '#0A66C2',
            self::SOURCE_TWITTER => '#000000',
            self::SOURCE_TIKTOK => '#000000',
            self::SOURCE_SNAPCHAT => '#FFFC00',
            self::SOURCE_EMAIL => '#6B7280',
            self::SOURCE_REFERRAL => '#F59E0B',
            default => '#9CA3AF',
        };
    }

    protected $fillable = [
        'user_id',
        'car_details',
        'status',
        'notes',
        'source',
    ];

    protected $casts = [
        'car_details' => 'array',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        $make = $this->car_details['make'] ?? null;
        if (!$make) return null;

        $localName = strtolower(str_replace([' ', '-'], ['', ''], $make));
        
        $map = [
            'mercedesbenz' => 'mercedes',
            'volkswagen' => 'volkswagen',
            'landrover' => 'land-rover',
            'astonmartin' => 'astonmartin',
            'alfaromeo' => 'alfaromeo',
            'rollsroyce' => 'rolls-royce',
        ];
        
        $searchName = $map[$localName] ?? $localName;
        
        // Try SVG then PNG locally
        $files = [$searchName . '.svg', $searchName . '.png', $localName . '.svg', $localName . '.png'];

        foreach($files as $file) {
            if(file_exists(public_path('images/brands/' . $file))) {
                return asset('images/brands/' . $file);
            }
        }

        return 'https://cdn.simpleicons.org/' . $searchName . '/000000';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inspections()
    {
        return $this->hasMany(InspectionReport::class);
    }
}
