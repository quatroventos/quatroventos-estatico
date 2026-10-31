<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\PublicContent;
use App\Services\Seo\OgImageCapture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class OgImageController extends Controller
{
    public function page(Request $request, Page $page, PublicContent $content, LivePreviewController $preview, OgImageCapture $capture): JsonResponse
    {
        $this->authorize('update', $page);

        $payload = $preview->buildPage($request, $page, $content);

        try {
            $path = $capture->capture($payload, $content->site($page->localeCode()), $page->slug ?: 'home');
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json([
            'path' => $path,
            'width' => OgImageCapture::WIDTH,
            'height' => OgImageCapture::HEIGHT,
        ]);
    }
}
