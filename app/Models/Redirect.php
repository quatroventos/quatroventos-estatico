<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class Redirect extends Model
{
    public const CACHE_KEY = 'site:redirects';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'automatic' => 'boolean',
            'hits' => 'integer',
            'last_hit_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Caminho sem query, âncora nem barra final: "/Foo/bar/?x=1" vira "/foo/bar".
     */
    public static function normalizePath(string $path): string
    {
        $path = (string) (parse_url(trim($path), PHP_URL_PATH) ?? '');

        return '/'.trim(strtolower($path), '/');
    }

    /**
     * Destino interno vira caminho normalizado; URL absoluta fica como veio.
     */
    public static function normalizeTarget(string $target): string
    {
        $target = trim($target);

        return preg_match('#^https?://#i', $target) === 1 ? $target : self::normalizePath($target);
    }

    /**
     * @return array<string, string>
     */
    public static function map(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, fn () => static::query()->pluck('target', 'source')->all());
        } catch (Throwable) {
            return [];
        }
    }

    public static function targetFor(string $path): ?string
    {
        $target = self::map()[self::normalizePath($path)] ?? null;

        return is_string($target) ? $target : null;
    }

    public static function recordHit(string $source): void
    {
        static::query()->where('source', $source)->update([
            'hits' => DB::raw('hits + 1'),
            'last_hit_at' => now(),
        ]);
    }

    /**
     * O endereço mudou: quem apontava para o antigo passa a apontar para o novo, o novo deixa
     * de ser origem (está publicado) e o antigo vira um 301 automático. Mantém um salto só.
     */
    public static function moved(string $from, string $to): void
    {
        $from = self::normalizePath($from);
        $to = self::normalizePath($to);

        if ($from === $to) {
            return;
        }

        static::query()->where('target', $from)->get()->each(function (self $redirect) use ($to): void {
            $redirect->source === $to ? $redirect->delete() : $redirect->update(['target' => $to]);
        });

        self::released($to);

        static::query()->updateOrCreate(['source' => $from], ['target' => $to, 'automatic' => true]);
    }

    /**
     * Caminho publicado não pode ser origem de redirect, senão a página some atrás do 301.
     */
    public static function released(string $path): void
    {
        static::query()->where('source', self::normalizePath($path))->get()->each->delete();
    }
}
