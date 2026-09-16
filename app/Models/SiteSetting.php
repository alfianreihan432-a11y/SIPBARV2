<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'type', 'value', 'group', 'label', 'description'];

    public static function get(string $key, $default = null)
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return $default;
            }

            return Cache::remember("site_setting_{$key}", 3600, function () use ($key, $default) {
                $setting = self::query()->where('key', $key)->first();

                return $setting?->value ?? $default;
            });
        } catch (Throwable) {
            return $default;
        }
    }

    public static function html(string $key, string $default = ''): string
    {
        return strip_tags((string) self::get($key, $default), '<br><em><span><strong><i><b>');
    }

    public static function getJson(string $key, $default = [])
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return $default;
            }

            return Cache::remember("site_setting_json_{$key}", 3600, function () use ($key, $default) {
                $setting = self::query()->where('key', $key)->first();
                if (! $setting || $setting->value === null || $setting->value === '') {
                    return $default;
                }

                $decoded = json_decode($setting->value, true);

                return is_array($decoded) ? $decoded : $default;
            });
        } catch (Throwable) {
            return $default;
        }
    }

    public static function set(string $key, $value, string $type = 'text', string $group = 'general'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );

        self::forgetKeyCache($key, $group);
    }

    public static function setJson(string $key, array $value, string $group = 'general'): void
    {
        self::set($key, json_encode($value, JSON_UNESCAPED_UNICODE), 'json', $group);
    }

    public static function clearCache(): void
    {
        $keys = self::query()->pluck('key');
        $groups = self::query()->pluck('group')->unique();

        foreach ($keys as $key) {
            Cache::forget("site_setting_{$key}");
            Cache::forget("site_setting_json_{$key}");
        }

        foreach ($groups as $group) {
            Cache::forget("site_settings_group_{$group}");
        }
    }

    public static function getByGroup(string $group): array
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return [];
            }

            return Cache::remember("site_settings_group_{$group}", 3600, function () use ($group) {
                return self::query()
                    ->where('group', $group)
                    ->get()
                    ->pluck('value', 'key')
                    ->toArray();
            });
        } catch (Throwable) {
            return [];
        }
    }

    public static function deleteUploadedFile(?string $path): void
    {
        if (! $path || ! str_starts_with($path, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $path), '/'));
    }

    protected static function forgetKeyCache(string $key, string $group): void
    {
        Cache::forget("site_setting_{$key}");
        Cache::forget("site_setting_json_{$key}");
        Cache::forget("site_settings_group_{$group}");
    }
}
