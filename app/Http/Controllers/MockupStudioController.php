<?php

namespace App\Http\Controllers;

use App\Services\Mockup\DeviceSceneGenerator;
use App\Services\Mockup\DeviceScreenshot;
use App\Services\Mockup\MockupCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class MockupStudioController extends Controller
{
    public function show(): Response
    {
        $this->ensureLocal();

        return response()
            ->view('tools.mockup-studio', [
                'studio' => [
                    'generateUrl' => route('mockup-studio.generate'),
                    'advanceUrl' => route('mockup-studio.advance'),
                    'captureUrl' => route('mockup-studio.capture'),
                    'configured' => filled(config('services.meta.key')),
                    'runway' => filled(config('services.runway.key')),
                    'viewports' => collect(MockupCatalog::viewports())
                        ->map(fn (array $viewport): array => [
                            'label' => $viewport['label'],
                            'width' => $viewport['width'],
                            'height' => $viewport['height'],
                            'scale' => $viewport['scale'],
                        ])
                        ->all(),
                    'references' => MockupCatalog::referenceCards(),
                    'devices' => MockupCatalog::options(MockupCatalog::devices()),
                    'angles' => MockupCatalog::options(MockupCatalog::angles()),
                    'scenes' => MockupCatalog::options(MockupCatalog::scenes()),
                    'sizes' => MockupCatalog::options(MockupCatalog::sizes()),
                    'qualities' => MockupCatalog::options(MockupCatalog::qualities()),
                ],
            ])
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function generate(Request $request, DeviceSceneGenerator $generator): JsonResponse
    {
        $this->ensureLocal();

        $data = $request->validate([
            'device' => ['required', 'string', Rule::in(array_keys(MockupCatalog::devices()))],
            'angle' => ['required', 'string', Rule::in(array_keys(MockupCatalog::angles()))],
            'scene' => ['required', 'string', Rule::in(array_keys(MockupCatalog::scenes()))],
            'size' => ['required', 'string', Rule::in(array_keys(MockupCatalog::sizes()))],
            'quality' => ['required', 'string', Rule::in(array_keys(MockupCatalog::qualities()))],
            'references' => ['required', 'array', 'min:1', 'max:3'],
            'references.*' => ['string', Rule::in(array_keys(MockupCatalog::references()))],
            'note' => ['nullable', 'string', 'max:400'],
        ], [
            'device.required' => 'Escolha o dispositivo.',
            'device.in' => 'Dispositivo inválido.',
            'angle.required' => 'Escolha o ângulo.',
            'angle.in' => 'Ângulo inválido.',
            'scene.required' => 'Escolha o cenário.',
            'scene.in' => 'Cenário inválido.',
            'size.required' => 'Escolha o tamanho.',
            'size.in' => 'Tamanho inválido.',
            'quality.required' => 'Escolha a qualidade.',
            'quality.in' => 'Qualidade inválida.',
            'references.required' => 'Escolha ao menos uma referência.',
            'references.min' => 'Escolha ao menos uma referência.',
            'references.max' => 'No máximo três referências.',
            'references.*.in' => 'Referência inválida.',
            'note.max' => 'A nota passa de 400 caracteres.',
        ]);

        set_time_limit((int) config('services.meta.image_timeout', 180) + 15);

        try {
            $png = $generator->generate($data);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json([
            'image' => 'data:image/png;base64,'.base64_encode($png),
        ]);
    }

    public function advance(Request $request, DeviceSceneGenerator $generator): JsonResponse
    {
        $this->ensureLocal();

        $data = $request->validate([
            'frame' => ['required', 'file', 'mimes:png,jpg,jpeg,webp', 'max:20480'],
            'motion' => ['required', 'string', Rule::in(['turn', 'open', 'flat'])],
            'device' => ['required', 'string', Rule::in(array_keys(MockupCatalog::devices()))],
            'size' => ['required', 'string', Rule::in(array_keys(MockupCatalog::sizes()))],
            'quality' => ['required', 'string', Rule::in(array_keys(MockupCatalog::qualities()))],
            'step' => ['required', 'integer', 'min:1', 'max:12'],
            'steps' => ['required', 'integer', 'min:1', 'max:12'],
            'note' => ['nullable', 'string', 'max:400'],
        ], [
            'frame.required' => 'Envie o quadro anterior.',
            'frame.mimes' => 'O quadro precisa ser PNG, JPG ou WebP.',
            'frame.max' => 'O quadro passa de 20 MB.',
            'motion.required' => 'Escolha o movimento.',
            'motion.in' => 'Movimento inválido.',
            'device.required' => 'Escolha o dispositivo.',
            'device.in' => 'Dispositivo inválido.',
            'size.required' => 'Escolha o tamanho.',
            'size.in' => 'Tamanho inválido.',
            'quality.required' => 'Escolha a qualidade.',
            'quality.in' => 'Qualidade inválida.',
            'step.required' => 'Informe o passo.',
            'steps.required' => 'Informe a quantidade de fotos.',
            'note.max' => 'A nota passa de 400 caracteres.',
        ]);

        set_time_limit((int) config('services.meta.image_timeout', 180) + 15);

        try {
            $png = $generator->advance((string) file_get_contents($request->file('frame')->getRealPath()), $data);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json([
            'image' => 'data:image/png;base64,'.base64_encode($png),
        ]);
    }

    public function capture(Request $request, DeviceScreenshot $screenshots): JsonResponse
    {
        $this->ensureLocal();

        $data = $request->validate([
            'url' => ['required', 'string', 'url', 'max:2000'],
            'device' => ['required', 'string', Rule::in(array_keys(MockupCatalog::viewports()))],
        ], [
            'url.required' => 'Informe a URL.',
            'url.url' => 'URL inválida.',
            'url.max' => 'A URL passa de 2000 caracteres.',
            'device.required' => 'Escolha o dispositivo.',
            'device.in' => 'Dispositivo inválido.',
        ]);

        $scheme = parse_url($data['url'], PHP_URL_SCHEME);

        if (! in_array($scheme, ['http', 'https'], true)) {
            throw ValidationException::withMessages([
                'url' => 'A URL precisa ser http ou https.',
            ]);
        }

        set_time_limit(50);

        try {
            $png = $screenshots->capture($data['url'], $data['device']);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        $viewport = MockupCatalog::viewports()[$data['device']];

        return response()->json([
            'image' => 'data:image/png;base64,'.base64_encode($png),
            'width' => $viewport['width'] * $viewport['scale'],
            'height' => $viewport['height'] * $viewport['scale'],
        ]);
    }

    private function ensureLocal(): void
    {
        $allowed = app()->environment('local')
            || (app()->environment('testing') && config('mockup.studio') === true);

        abort_unless($allowed, 404);
    }
}
