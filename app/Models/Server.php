<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Server model
 *
 * – ستون‌های مجاز برای mass-assignment در $fillable تعریف شده‌اند.  
 * – متدهای `getAllowedFields / Filters / Sorts` را نگه می‌داریم تا
 *   در صورت استفاده از Spatie QueryBuilder یا پلاگین Tomato API قابل
 *   تشخیص باشند. (Tomato به طور ضمنی همین قرارداد را می‌خوانَد.)
 */
class Server extends Model
{
    /* -----------------------------------------------------------------
     |  Mass-assignment
     | -----------------------------------------------------------------
     */
    protected $fillable = [
        'name',
        'link',
        'country',
        'ip',
        'active',
        'user_id',
    ];

    /* -----------------------------------------------------------------
     |  Casts
     | -----------------------------------------------------------------
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /* -----------------------------------------------------------------
     |  Allowed arrays for API query (optional, used by Tomato)
     | -----------------------------------------------------------------
     */
    public static function getAllowedFields(): array
    {
        return [
            'id',
            'name',
            'link',
            'country',
            'ip',
            'active',
            'created_at',
        ];
    }

    public static function getAllowedFilters(): array
    {
        return [
            'name',
            'link',
            'country',
            'ip',
            'active',
        ];
    }

    public static function getAllowedSorts(): array
    {
        return [
            'id',
            'name',
            'created_at',
        ];
    }

    /* -----------------------------------------------------------------
     |  Accessors
     | -----------------------------------------------------------------
     */
    public function getFlagPathAttribute(): string
    {
        $code = strtolower($this->country);
        return "vendor/blade-country-flags/1x1-{$code}.svg";
    }

    /* -----------------------------------------------------------------
     |  Relations
     | -----------------------------------------------------------------
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
