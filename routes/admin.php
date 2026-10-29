<?php

use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\CareerSubmissionController;
use App\Http\Controllers\Admin\CaseStudyController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\ContactSubmissionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\FormOptionController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MediaOptimizationController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PagePreviewController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\SpaController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

$admin = trim((string) config('cms.admin_path'), '/');

if ($admin === '') {
    return;
}

Route::prefix($admin)->group(function () {
    Route::prefix('api')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:8,1');

        Route::middleware('cms')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
            Route::patch('tour', [TourController::class, 'update']);
            Route::post('impersonation/leave', [AuthController::class, 'leaveImpersonation']);
            Route::get('dashboard', [DashboardController::class, 'show']);
            Route::post('dashboard/lighthouse', [DashboardController::class, 'refreshLighthouse']);
            Route::post('media', [MediaController::class, 'store']);

            Route::get('media-optimizations', [MediaOptimizationController::class, 'show']);
            Route::put('media-optimizations/settings', [MediaOptimizationController::class, 'updateSettings']);
            Route::post('media-optimizations/optimize', [MediaOptimizationController::class, 'optimize']);
            Route::post('media-optimizations/revert', [MediaOptimizationController::class, 'revert']);

            Route::get('pages', [PageController::class, 'index']);
            Route::post('pages', [PageController::class, 'store']);
            Route::get('pages/{page}', [PageController::class, 'show']);
            Route::put('pages/{page}', [PageController::class, 'update']);
            Route::delete('pages/{page}', [PageController::class, 'destroy']);
            Route::get('pages/{page}/revisions', [PageController::class, 'revisions']);
            Route::post('pages/{page}/revisions/{revision}/restore', [PageController::class, 'restoreRevision']);

            Route::get('translations/config', [TranslationController::class, 'config']);
            Route::get('translations/status', [TranslationController::class, 'show']);
            Route::post('pages/{page}/translations/{locale}', [TranslationController::class, 'page'])->middleware('throttle:20,1');
            Route::post('cases/{caseStudy}/translations/{locale}', [TranslationController::class, 'caseStudy'])->whereNumber('caseStudy')->middleware('throttle:20,1');
            Route::post('menu-items/translations/{locale}', [TranslationController::class, 'menus'])->middleware('throttle:10,1');

            Route::apiResource('cases', CaseStudyController::class)->parameters(['cases' => 'caseStudy'])->whereNumber('caseStudy');
            Route::post('cases/{caseStudy}/restore', [CaseStudyController::class, 'restore'])->whereNumber('caseStudy');
            Route::apiResource('team-members', TeamMemberController::class)->except(['show']);
            Route::post('team-members/{teamMember}/restore', [TeamMemberController::class, 'restore'])->whereNumber('teamMember');
            Route::apiResource('branches', BranchController::class)->except(['show']);
            Route::post('branches/{branch}/restore', [BranchController::class, 'restore'])->whereNumber('branch');
            Route::get('menu-items', [MenuItemController::class, 'index']);
            Route::post('menu-items', [MenuItemController::class, 'store']);
            Route::put('menu-items/sync', [MenuItemController::class, 'sync']);
            Route::put('menu-items/reorder', [MenuItemController::class, 'reorder']);
            Route::put('menu-items/{menuItem}', [MenuItemController::class, 'update']);
            Route::delete('menu-items/{menuItem}', [MenuItemController::class, 'destroy']);
            Route::apiResource('certifications', CertificationController::class)->except(['show']);
            Route::post('certifications/{certification}/restore', [CertificationController::class, 'restore'])->whereNumber('certification');
            Route::get('forms', [FormController::class, 'index']);
            Route::get('forms/{form}', [FormController::class, 'show'])->where('form', 'contact');
            Route::put('forms/{form}', [FormController::class, 'update'])->where('form', 'contact');
            Route::apiResource('form-options', FormOptionController::class)->except(['show']);
            Route::post('form-options/{formOption}/restore', [FormOptionController::class, 'restore'])->whereNumber('formOption');

            Route::get('cache', [CacheController::class, 'show']);
            Route::put('cache', [CacheController::class, 'update']);
            Route::post('cache/flush', [CacheController::class, 'flush']);
            Route::post('cache/warm', [CacheController::class, 'warm']);

            Route::get('backup', [BackupController::class, 'index']);
            Route::put('backup', [BackupController::class, 'update']);
            Route::post('backup/run', [BackupController::class, 'run']);
            Route::get('backup/{backup}/download', [BackupController::class, 'download']);
            Route::post('backup/{backup}/restore', [BackupController::class, 'restore']);
            Route::post('backup/{backup}/undelete', [BackupController::class, 'undelete'])->whereNumber('backup');
            Route::delete('backup/{backup}', [BackupController::class, 'destroy']);

            Route::get('site', [SiteSettingController::class, 'show']);
            Route::put('site', [SiteSettingController::class, 'update']);
            Route::get('tags', [SiteSettingController::class, 'tags']);
            Route::put('tags', [SiteSettingController::class, 'updateTags']);
            Route::get('seo', [SeoController::class, 'show']);
            Route::put('seo', [SeoController::class, 'update']);
            Route::get('sitemap', [SiteSettingController::class, 'sitemap']);
            Route::put('sitemap', [SiteSettingController::class, 'updateSitemap']);

            Route::get('messages', [ContactSubmissionController::class, 'index']);
            Route::get('messages/{contactSubmission}', [ContactSubmissionController::class, 'show']);
            Route::delete('messages/{contactSubmission}', [ContactSubmissionController::class, 'destroy']);
            Route::post('messages/{contactSubmission}/restore', [ContactSubmissionController::class, 'restore'])->whereNumber('contactSubmission');

            Route::get('careers', [CareerSubmissionController::class, 'index']);
            Route::get('careers/{careerSubmission}', [CareerSubmissionController::class, 'show']);
            Route::get('careers/{careerSubmission}/download', [CareerSubmissionController::class, 'download']);
            Route::delete('careers/{careerSubmission}', [CareerSubmissionController::class, 'destroy']);
            Route::post('careers/{careerSubmission}/restore', [CareerSubmissionController::class, 'restore'])->whereNumber('careerSubmission');

            Route::get('profile', [UserController::class, 'profile']);
            Route::put('profile', [UserController::class, 'updateProfile']);

            Route::apiResource('users', UserController::class)->except(['show']);
            Route::post('users/{user}/restore', [UserController::class, 'restore'])->whereNumber('user');
            Route::post('users/{user}/impersonate', [AuthController::class, 'impersonate']);
            Route::get('audit', [AuditController::class, 'index']);
        });
    });

    Route::get('preview/{page}', PagePreviewController::class);
    Route::get('/{any?}', SpaController::class)->where('any', '^(?!api(?:/|$)|preview(?:/|$)).*');
});
