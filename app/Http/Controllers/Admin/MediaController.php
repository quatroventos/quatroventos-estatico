<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\MediaPath;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $kind = $request->string('kind')->toString();

        $rules = match ($kind) {
            'video' => ['file' => ['required', 'file', 'mimetypes:video/mp4,video/webm', 'max:51200']],
            'pdf' => ['file' => ['required', 'file', 'mimetypes:application/pdf', 'max:10240']],
            default => ['file' => ['required', 'file', 'image', 'max:8192']],
        };

        $request->validate($rules);

        $path = $request->file('file')->store('media', 'public');

        return response()->json([
            'path' => MediaPath::toPublic($path),
        ]);
    }
}
