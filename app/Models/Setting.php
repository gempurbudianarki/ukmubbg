<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $primaryKey = 'key_name';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'key_name',
        'value',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key_name', $key)->first();
        return $setting !== null ? (string) $setting->value : (string) $default;
    }

    public static function set(string $key, $value)
    {
        return self::updateOrCreate(
            ['key_name' => $key],
            ['value' => (string) $value]
        );
    }

    public static function clearCache(): void
    {
        // No-op for interface consistency
    }
}
