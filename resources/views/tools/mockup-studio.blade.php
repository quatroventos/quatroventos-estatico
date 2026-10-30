<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mockup studio</title>
    <style>
        :root { color-scheme: dark; --line: #34322c; --paper: #141311; --ink: #f4f1ea; --muted: #a39e93; --accent: #ff3b30; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #0c0b0a; color: var(--ink); font: 15px/1.45 ui-sans-serif, system-ui, sans-serif; }
        main { display: grid; grid-template-columns: minmax(0, 1fr) 340px; min-height: 100vh; }
        .stage { position: relative; min-height: 70vh; background: #090908; }
        canvas { display: block; width: 100%; height: 100%; }
        #handles { position: absolute; inset: 0; width: 100%; height: 100%; touch-action: none; }
        .empty { position: absolute; inset: 0; display: grid; place-items: center; color: var(--muted); pointer-events: none; }
        .strip { display: flex; gap: 8px; overflow-x: auto; padding: 12px; border-top: 1px solid var(--line); }
        .strip img { height: 72px; border: 1px solid var(--line); }
        aside { border-left: 1px solid var(--line); padding: 20px; overflow: auto; background: var(--paper); }
        h1 { margin: 0 0 8px; font-size: 20px; }
        p { margin: 0 0 16px; color: var(--muted); }
        fieldset { border: 1px solid var(--line); margin: 0 0 16px; padding: 12px; }
        legend { padding: 0 6px; color: var(--muted); }
        label { display: grid; gap: 6px; margin-bottom: 12px; }
        select, textarea, input, button { font: inherit; color: inherit; background: #1c1b19; border: 1px solid var(--line); border-radius: 6px; padding: 8px 10px; }
        textarea { min-height: 72px; resize: vertical; }
        button { cursor: pointer; }
        button.primary { background: var(--accent); border-color: var(--accent); color: white; }
        button:disabled { opacity: .45; cursor: not-allowed; }
        .refs { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .refs label { margin: 0; font-size: 12px; }
        .refs img { width: 100%; aspect-ratio: 4/3; object-fit: cover; border-radius: 4px; }
        .row { display: flex; gap: 8px; }
        .row > * { flex: 1; }
        .drop { border: 1px dashed var(--line); border-radius: 8px; padding: 16px; text-align: center; color: var(--muted); }
        .status { min-height: 3em; color: var(--muted); white-space: pre-wrap; }
        #crop-wrap { margin: 0 0 16px; }
        #crop { width: auto; max-width: 100%; max-height: 88px; height: auto; background: #090908; display: block; margin-bottom: 8px; }
        input[type="range"] { width: 100%; padding: 0; accent-color: var(--accent); }
        #viewport-hint { margin-top: -8px; }
        @media (max-width: 900px) { main { grid-template-columns: 1fr; } aside { border-left: 0; border-top: 1px solid var(--line); } }
    </style>
</head>
<body>
<main>
    <section class="stage">
        <canvas id="photo"></canvas>
        <canvas id="handles"></canvas>
        <div class="empty" id="empty">A foto gerada aparece aqui.</div>
        <div class="strip" id="strip"></div>
    </section>
    <aside>
        <h1>Mockup studio</h1>
        <p>Sobe o print, a IA gera uma foto no estilo dos cases e o print entra na tela. O PNG sai só com a foto, sem a marcação da tela.</p>

        <label class="drop" id="drop">
            Print da interface
            <input id="prints" type="file" accept="image/*" hidden>
        </label>
        <label>URL da página
            <input id="page-url" type="url" inputmode="url" placeholder="https://">
        </label>
        <button id="capture" type="button">Capturar tela</button>
        <p id="viewport-hint"></p>
        <div id="crop-wrap" hidden>
            <canvas id="crop"></canvas>
            <label>De <span id="crop-start-label">0%</span>
                <input id="crop-start" type="range" min="0" max="100" value="0">
            </label>
            <label>Até <span id="crop-end-label">100%</span>
                <input id="crop-end" type="range" min="0" max="100" value="100">
            </label>
            <p>A largura entra inteira. Os sliders cortam só na vertical.</p>
        </div>

        <fieldset>
            <legend>Foto</legend>
            <label>Dispositivo
                <select id="device">
                    @foreach ($studio['devices'] as $option)
                        <option value="{{ $option['id'] }}" @selected($option['id'] === 'macbook')>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </label>
            <label>Ângulo
                <select id="angle">
                    @foreach ($studio['angles'] as $option)
                        <option value="{{ $option['id'] }}" @selected($option['id'] === 'three-quarter-right')>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </label>
            <label>Cenário
                <select id="scene">
                    @foreach ($studio['scenes'] as $option)
                        <option value="{{ $option['id'] }}" @selected($option['id'] === 'rail')>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </label>
            <div class="row">
                <label>Tamanho
                    <select id="size">
                        @foreach ($studio['sizes'] as $option)
                            <option value="{{ $option['id'] }}">{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Qualidade
                    <select id="quality">
                        @foreach ($studio['qualities'] as $option)
                            <option value="{{ $option['id'] }}" @selected($option['id'] === 'high')>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div class="refs">
                @foreach ($studio['references'] as $reference)
                    <label>
                        <img src="{{ $reference['src'] }}" alt="{{ $reference['label'] }}">
                        <span><input type="checkbox" name="reference" value="{{ $reference['id'] }}" data-device="{{ $reference['device'] }}" @checked($reference['device'] === 'macbook')> {{ $reference['label'] }}</span>
                    </label>
                @endforeach
            </div>
            <label>Nota
                <textarea id="note" maxlength="400" placeholder="Luz mais dura, aparelho mais perto da câmera"></textarea>
            </label>
            <p>Segura Alt e clica na foto se o encaixe errar. Os cantos não entram no arquivo.</p>
            <div class="row">
                <button class="primary" id="generate" type="button">Gerar foto</button>
                <button id="redetect" type="button">Detectar tela</button>
                <button id="export" type="button">PNG</button>
            </div>
        </fieldset>

        @if ($studio['runway'])
        <fieldset>
            <legend>Animação</legend>
            <label>Movimento
                <select id="motion">
                    <option value="turn">Girar</option>
                    <option value="open" selected>Abrir</option>
                </select>
            </label>
            <div class="row">
                <label>Passos
                    <select id="steps">
                        <option value="4">4</option>
                        <option value="6" selected>6</option>
                        <option value="8">8</option>
                    </select>
                </label>
                <label>Duração
                    <select id="duration">
                        <option value="3">3 s</option>
                        <option value="4" selected>4 s</option>
                        <option value="6">6 s</option>
                    </select>
                </label>
            </div>
            <div class="row">
                <button class="primary" id="animate" type="button">Animar mockup</button>
                <button id="record" type="button" disabled>Baixar minivídeo</button>
            </div>
        </fieldset>
        @endif
        <div class="status" id="status"></div>
    </aside>
</main>
<script id="mockup-studio" type="application/json">@json($studio)</script>
<script type="module" src="/tools/mockup-studio.js?v=6"></script>
</body>
</html>
