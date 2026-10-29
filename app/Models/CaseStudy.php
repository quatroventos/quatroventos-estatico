<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\Locales;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class CaseStudy extends Model
{
    use HasTranslations;
    use LogsActivity;
    use SoftDeletes;

    public const SECTORS = [
        'financas' => 'Finanças',
        'educacao' => 'Educação',
        'varejo' => 'Varejo',
        'saude' => 'Saúde',
        'servicos' => 'Serviços',
        'industria' => 'Indústria',
    ];

    protected $guarded = [];

    protected $attributes = [
        'locale' => 'pt-BR',
    ];

    /**
     * @return list<string>
     */
    public function translatableHashFields(): array
    {
        return ['slug', 'title', 'summary', 'cover_alt', 'gallery', 'challenge', 'solution', 'deliverables', 'results', 'testimonial', 'seo_title', 'seo_description'];
    }

    protected function casts(): array
    {
        return [
            'translated_at' => 'datetime',
            'services' => 'array',
            'gallery' => 'array',
            'deliverables' => 'array',
            'stack' => 'array',
            'results' => 'array',
            'testimonial' => 'array',
            'featured' => 'boolean',
            'year' => 'integer',
            'sort' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('featured')->orderBy('sort')->orderByDesc('year')->orderByDesc('id');
    }

    public function hasService(string $slug): bool
    {
        return in_array($slug, $this->services ?? [], true);
    }

    public function publicPath(): string
    {
        return Locales::path($this->localeCode(), '/cases/'.$this->slug);
    }

    public function sectorLabel(): ?string
    {
        return self::SECTORS[$this->sector] ?? null;
    }
}
