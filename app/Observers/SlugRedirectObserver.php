<?php

namespace App\Observers;

use App\Models\CaseStudy;
use App\Models\Page;
use App\Models\Redirect;

class SlugRedirectObserver
{
    public function created(Page|CaseStudy $model): void
    {
        if ($model->status === 'published') {
            Redirect::released($model->publicPath());
        }
    }

    public function updated(Page|CaseStudy $model): void
    {
        if ($model->status !== 'published') {
            return;
        }

        if ($model->wasChanged('slug') && $model->getRawOriginal('status') === 'published') {
            Redirect::moved($model->newFromBuilder($model->getRawOriginal())->publicPath(), $model->publicPath());

            return;
        }

        Redirect::released($model->publicPath());
    }
}
