<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\MailSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function show(): JsonResponse
    {
        $settings = SiteSetting::current();
        $this->authorize('view', $settings);

        return response()->json(['data' => $settings]);
    }

    public function update(Request $request, MailSettings $mailSettings): JsonResponse
    {
        $settings = SiteSetting::current();
        $this->authorize('update', $settings);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'logo_path' => ['nullable', 'string', 'max:500'],
            'favicon_path' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:64'],
            'phone_href' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email'],
            'whatsapp_url' => ['nullable', 'string', 'max:500'],
            'address' => ['nullable', 'string'],
            'map_embed_url' => ['nullable', 'string'],
            'instagram_url' => ['nullable', 'string', 'max:500'],
            'linkedin_url' => ['nullable', 'string', 'max:500'],
            'facebook_url' => ['nullable', 'string', 'max:500'],
            'og_site_name' => ['nullable', 'string', 'max:255'],
            'og_locale' => ['nullable', 'string', 'max:16'],
            'twitter_card' => ['nullable', 'string', 'max:64'],
            'smtp_enabled' => ['sometimes', 'boolean'],
            'smtp_host' => ['nullable', 'string', 'max:255'],
            'smtp_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string', 'max:255'],
            'smtp_encryption' => ['nullable', 'in:tls,ssl'],
            'smtp_from_address' => ['nullable', 'email', 'max:255'],
            'smtp_from_name' => ['nullable', 'string', 'max:255'],
        ]);

        if (! array_key_exists('smtp_password', $data) || $data['smtp_password'] === null || $data['smtp_password'] === '') {
            unset($data['smtp_password']);
        }

        if (($data['smtp_encryption'] ?? null) === '') {
            $data['smtp_encryption'] = null;
        }

        $settings->update($data);
        $mailSettings->apply($settings->fresh());

        return response()->json(['data' => $settings->fresh()]);
    }

    public function tags(): JsonResponse
    {
        $settings = SiteSetting::current();
        $this->authorize('view', $settings);

        return response()->json([
            'data' => $settings->only(['tag_header', 'tag_body', 'tag_footer']),
        ]);
    }

    public function updateTags(Request $request): JsonResponse
    {
        $settings = SiteSetting::current();
        $this->authorize('update', $settings);

        $data = $request->validate([
            'tag_header' => ['nullable', 'string'],
            'tag_body' => ['nullable', 'string'],
            'tag_footer' => ['nullable', 'string'],
        ]);

        $settings->update($data);

        return response()->json([
            'data' => $settings->fresh()->only(['tag_header', 'tag_body', 'tag_footer']),
        ]);
    }

    public function sitemap(): JsonResponse
    {
        $settings = SiteSetting::current();
        $this->authorize('view', $settings);

        return response()->json([
            'data' => ['sitemap_extra' => $settings->sitemap_extra ?? []],
        ]);
    }

    public function updateSitemap(Request $request): JsonResponse
    {
        $settings = SiteSetting::current();
        $this->authorize('update', $settings);

        $data = $request->validate([
            'sitemap_extra' => ['nullable', 'array'],
            'sitemap_extra.*' => ['string', 'max:500'],
        ]);

        $settings->update(['sitemap_extra' => $data['sitemap_extra'] ?? []]);

        return response()->json([
            'data' => ['sitemap_extra' => $settings->fresh()->sitemap_extra ?? []],
        ]);
    }
}
