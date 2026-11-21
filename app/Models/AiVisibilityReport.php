<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AiVisibilityReport extends Model
{
    public const PATH = 'como-aparecer-no-chatgpt';

    public const LEGACY_PATH = 'meu-site-aparece-na-ia';

    protected $fillable = [
        'public_id',
        'url',
        'host',
        'score',
        'band',
        'niche',
        'checks',
        'fixes',
        'response_ms',
        'ip',
    ];

    protected function casts(): array
    {
        return [
            'checks' => 'array',
            'fixes' => 'array',
            'score' => 'integer',
            'response_ms' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function publicPath(): string
    {
        return '/'.self::PATH.'/'.$this->public_id;
    }

    public static function mintId(): string
    {
        do {
            $id = Str::replace(['/', '+', '='], '', base64_encode(random_bytes(16)));
        } while (static::query()->where('public_id', $id)->exists());

        return $id;
    }

    public static function bandFor(int $score): string
    {
        return match (true) {
            $score >= 70 => 'alto',
            $score >= 40 => 'medio',
            default => 'baixo',
        };
    }
}
