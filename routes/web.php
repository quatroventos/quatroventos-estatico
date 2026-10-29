<?php

use App\Http\Controllers\SiteController;
use App\Http\Controllers\SiteFormController;
use App\Http\Middleware\DetectLocale;
use App\Models\Page;
use Illuminate\Support\Facades\Route;

foreach (Page::formSlugs() as $index => $slug) {
    Route::get('/'.$slug, [SiteController::class, 'show'])
        ->middleware(DetectLocale::class)
        ->defaults('slug', $slug)
        ->name($index === 0 ? 'site.form' : 'site.form.'.$slug);
}

Route::post('/contato', [SiteFormController::class, 'contact'])
    ->middleware('throttle:8,1')
    ->name('contato.store');
