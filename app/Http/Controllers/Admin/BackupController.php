<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Models\SiteSetting;
use App\Services\BackupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function index(Request $request, BackupService $backups): JsonResponse
    {
        $this->authorize('viewAny', SiteSetting::class);

        return response()->json(['data' => $backups->panel($request->boolean('trashed'))]);
    }

    public function update(Request $request, BackupService $backups): JsonResponse
    {
        $this->authorize('viewAny', SiteSetting::class);

        $data = $request->validate([
            'hour' => ['required', 'integer', 'between:0,23'],
            'retention_days' => ['required', 'integer', 'between:1,90'],
        ]);

        return response()->json([
            'data' => $backups->updateSettings((int) $data['hour'], (int) $data['retention_days']),
        ]);
    }

    public function run(Request $request, BackupService $backups): JsonResponse
    {
        $this->authorize('viewAny', SiteSetting::class);

        $backup = $backups->run($request->user(), 'manual');

        if ($backup->status !== 'ok') {
            return response()->json([
                'message' => $backup->error ?: 'Falha ao gerar o backup.',
                'data' => $backups->panel(),
            ], 500);
        }

        return response()->json(['data' => $backups->panel()]);
    }

    public function download(Backup $backup, BackupService $backups): StreamedResponse
    {
        $this->authorize('viewAny', SiteSetting::class);

        return $backups->download($backup);
    }

    public function restore(Backup $backup, BackupService $backups): JsonResponse
    {
        $this->authorize('viewAny', SiteSetting::class);

        try {
            return response()->json(['data' => $backups->restore($backup)]);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function destroy(Backup $backup, BackupService $backups): JsonResponse
    {
        $this->authorize('viewAny', SiteSetting::class);

        return response()->json(['data' => $backups->delete($backup)]);
    }

    public function undelete(int $backup, BackupService $backups): JsonResponse
    {
        $this->authorize('viewAny', SiteSetting::class);

        $model = Backup::onlyTrashed()->findOrFail($backup);

        return response()->json(['data' => $backups->recover($model)]);
    }
}
