<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\Page;
use App\Models\Redirect;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RedirectController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('view', SiteSetting::current());

        return response()->json([
            'data' => Redirect::query()->orderByDesc('updated_at')->orderBy('source')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('update', SiteSetting::current());

        [$source, $target] = $this->validated($request);

        $redirect = Redirect::query()->create(['source' => $source, 'target' => $target, 'automatic' => false]);
        $this->repoint($source, $target, $redirect->id);

        return response()->json(['data' => $redirect->fresh()], 201);
    }

    public function update(Request $request, Redirect $redirect): JsonResponse
    {
        $this->authorize('update', SiteSetting::current());

        [$source, $target] = $this->validated($request, $redirect);

        $redirect->update(['source' => $source, 'target' => $target, 'automatic' => false]);
        $this->repoint($source, $target, $redirect->id);

        return response()->json(['data' => $redirect->fresh()]);
    }

    public function destroy(Redirect $redirect): JsonResponse
    {
        $this->authorize('update', SiteSetting::current());

        $redirect->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function validated(Request $request, ?Redirect $current = null): array
    {
        $request->validate([
            'source' => ['required', 'string', 'max:500', 'regex:#^/#'],
            'target' => ['required', 'string', 'max:500', 'regex:#^(/|https?://)#i'],
        ], [
            'source.regex' => 'A origem deve começar com /.',
            'target.regex' => 'O destino deve começar com / ou com http(s)://.',
        ]);

        $source = Redirect::normalizePath((string) $request->input('source'));
        $target = Redirect::normalizeTarget((string) $request->input('target'));

        $first = explode('/', trim($source, '/'))[0];
        if ($source === '/' || in_array($first, ['api', 'storage', 'build', 'assets', cms_admin_path()], true)) {
            throw ValidationException::withMessages(['source' => 'Essa origem não pode ser redirecionada.']);
        }

        if (Redirect::query()->where('source', $source)->when($current, fn ($query) => $query->whereKeyNot($current->id))->exists()) {
            throw ValidationException::withMessages(['source' => 'Já existe um redirect com essa origem.']);
        }

        if (in_array($source, $this->publishedPaths(), true)) {
            throw ValidationException::withMessages(['source' => 'Essa origem é o endereço de uma página ou case publicado.']);
        }

        $chained = Redirect::query()->where('source', $target)->when($current, fn ($query) => $query->whereKeyNot($current->id))->value('target');
        if (is_string($chained)) {
            $target = $chained;
        }

        if ($target === $source) {
            throw ValidationException::withMessages(['target' => 'O destino não pode ser igual à origem.']);
        }

        return [$source, $target];
    }

    /**
     * Quem apontava para a origem passa a ir direto ao destino, para manter um salto só.
     */
    private function repoint(string $source, string $target, int $except): void
    {
        Redirect::query()->where('target', $source)->whereKeyNot($except)->get()->each(function (Redirect $redirect) use ($target): void {
            $redirect->source === $target ? $redirect->delete() : $redirect->update(['target' => $target]);
        });
    }

    /**
     * @return list<string>
     */
    private function publishedPaths(): array
    {
        return Page::query()->published()->get()->map->publicPath()
            ->concat(CaseStudy::query()->published()->get()->map->publicPath())
            ->map(fn (string $path) => Redirect::normalizePath($path))
            ->values()
            ->all();
    }
}
