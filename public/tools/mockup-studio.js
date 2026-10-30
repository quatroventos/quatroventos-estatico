const config = JSON.parse(document.getElementById('mockup-studio').textContent);
const photo = document.getElementById('photo');
const handles = document.getElementById('handles');
const cropCanvas = document.getElementById('crop');
const status = document.getElementById('status');
const ctx = photo.getContext('2d');
const ui = handles.getContext('2d');
const cropCtx = cropCanvas.getContext('2d');

let plate = null;
let print = null;
let crop = null;
let quad = null;
let frames = [];
let busy = false;
let drag = -1;
let viewScale = 1;
let showGuides = false;

bind();

function bind() {
    document.getElementById('prints').addEventListener('change', (event) => {
        const file = event.target.files?.[0];
        if (file) {
            loadPrint(file);
        }
    });
    document.getElementById('capture').addEventListener('click', capturePage);
    document.getElementById('crop-start').addEventListener('input', applyVerticalCrop);
    document.getElementById('crop-end').addEventListener('input', applyVerticalCrop);
    syncViewportHint();

    document.getElementById('generate').addEventListener('click', generatePhoto);
    document.getElementById('animate')?.addEventListener('click', animate);
    document.getElementById('redetect').addEventListener('click', () => {
        if (!plate) {
            return;
        }
        quad = detectQuad(plate) ?? defaultQuad(plate);
        render();
    });
    document.getElementById('export').addEventListener('click', exportPng);
    document.getElementById('record')?.addEventListener('click', record);
    document.getElementById('device').addEventListener('change', syncDevice);
    handles.addEventListener('pointerdown', startDrag);
    handles.addEventListener('pointermove', moveDrag);
    handles.addEventListener('pointerup', () => {
        drag = -1;
    });
    handles.addEventListener('pointerenter', () => {
        showGuides = true;
        drawHandles();
    });
    handles.addEventListener('pointerleave', () => {
        if (drag >= 0) {
            return;
        }
        showGuides = false;
        drawHandles();
    });
    handles.addEventListener('click', sampleScreen);

    document.getElementById('generate').disabled = !config.configured;
    const animateButton = document.getElementById('animate');
    if (animateButton) {
        animateButton.disabled = !config.configured;
    }
    if (!config.configured) {
        status.textContent = 'META_API_KEY não está no .env.';
    }
}

function loadPrint(file) {
    const image = new Image();
    image.onload = () => {
        URL.revokeObjectURL(image.src);
        adoptPrint(image);
    };
    image.onerror = () => {
        status.textContent = 'Não consegui ler essa imagem.';
    };
    image.src = URL.createObjectURL(file);
}

function adoptPrint(image) {
    print = image;
    document.getElementById('crop-wrap').hidden = false;
    document.getElementById('crop-start').value = '0';
    document.getElementById('crop-end').value = '100';
    applyVerticalCrop();
}

async function capturePage() {
    const url = value('page-url').trim();
    if (url === '') {
        status.textContent = 'Informe a URL.';
        return;
    }

    await run('Abrindo a página no Chrome…', async () => {
        const payload = await postJson(config.captureUrl, {
            url,
            device: value('device'),
        });
        await new Promise((resolve, reject) => {
            const image = new Image();
            image.onload = () => {
                adoptPrint(image);
                resolve();
            };
            image.onerror = () => reject(new Error('A print voltou ilegível.'));
            image.src = payload.image;
        });
        status.textContent = `Print em ${print.naturalWidth}×${print.naturalHeight}.`;
    });
}

function applyVerticalCrop() {
    if (!print) {
        return;
    }
    const startInput = document.getElementById('crop-start');
    const endInput = document.getElementById('crop-end');
    let start = Number(startInput.value);
    let end = Number(endInput.value);
    if (end < start + 1) {
        if (document.activeElement === endInput) {
            start = Math.max(0, end - 1);
            startInput.value = String(start);
        } else {
            end = Math.min(100, start + 1);
            endInput.value = String(end);
        }
    }
    document.getElementById('crop-start-label').textContent = `${start}%`;
    document.getElementById('crop-end-label').textContent = `${end}%`;
    const y = Math.round(print.naturalHeight * (start / 100));
    const bottom = Math.round(print.naturalHeight * (end / 100));
    crop = {
        x: 0,
        y,
        width: print.naturalWidth,
        height: Math.max(1, bottom - y),
    };
    paintCrop(start, end);
    render();
}

function paintCrop(start, end) {
    const scale = Math.min(220 / print.naturalWidth, 88 / print.naturalHeight, 1);
    cropCanvas.width = Math.max(1, Math.round(print.naturalWidth * scale));
    cropCanvas.height = Math.max(1, Math.round(print.naturalHeight * scale));
    cropCtx.drawImage(print, 0, 0, cropCanvas.width, cropCanvas.height);
    const top = cropCanvas.height * (start / 100);
    const bottom = cropCanvas.height * (end / 100);
    cropCtx.fillStyle = 'rgba(0, 0, 0, 0.62)';
    cropCtx.fillRect(0, 0, cropCanvas.width, top);
    cropCtx.fillRect(0, bottom, cropCanvas.width, cropCanvas.height - bottom);
}

async function generatePhoto() {
    const references = [...document.querySelectorAll('input[name="reference"]:checked')].map((input) => input.value);
    if (references.length === 0) {
        status.textContent = 'Escolha ao menos uma referência.';
        return;
    }

    await run('Gerando a foto…', async () => {
        const payload = await postJson(config.generateUrl, {
            device: value('device'),
            angle: value('angle'),
            scene: value('scene'),
            size: value('size'),
            quality: value('quality'),
            references,
            note: value('note'),
        });
        await showPlate(payload.image, true);
        status.textContent = quad ? 'Foto pronta.' : 'Não achei a tela. Segura Alt e clica na área da tela.';
    });
}

async function animate() {
    if (!plate) {
        status.textContent = 'Gere a foto antes de animar.';
        return;
    }

    const steps = Number(value('steps'));
    const motion = value('motion');
    frames = [await compositeBlob(plate, print, quad)];
    paintStrip();

    await run('Animando…', async () => {
        let current = plate;
        for (let step = 2; step <= steps; step += 1) {
            status.textContent = `Foto ${step} de ${steps}…`;
            const body = new FormData();
            body.append('frame', await toPngBlob(current), 'frame.png');
            body.append('motion', motion);
            body.append('device', value('device'));
            body.append('size', value('size'));
            body.append('quality', value('quality'));
            body.append('step', String(step));
            body.append('steps', String(steps));
            body.append('note', value('note'));
            const payload = await postForm(config.advanceUrl, body);
            current = await showPlate(payload.image, false);
            frames.push(await compositeBlob(current, print, quad));
            paintStrip();
        }
        plate = current;
        render();
        const recordButton = document.getElementById('record');
        if (recordButton) {
            recordButton.disabled = frames.length < 2;
        }
        status.textContent = `${frames.length} fotos. Baixe o minivídeo.`;
    });
}

function showPlate(dataUrl, resetQuad) {
    return new Promise((resolve, reject) => {
        const image = new Image();
        image.onload = () => {
            plate = image;
            if (resetQuad || !quad) {
                quad = detectQuad(image) ?? defaultQuad(image);
            }
            render();
            resolve(image);
        };
        image.onerror = () => reject(new Error('A imagem voltou ilegível.'));
        image.src = dataUrl;
    });
}

function render() {
    if (!plate) {
        return;
    }
    const max = 960;
    const scale = Math.min(1, max / plate.naturalWidth, max / plate.naturalHeight);
    viewScale = scale;
    photo.width = handles.width = Math.round(plate.naturalWidth * scale);
    photo.height = handles.height = Math.round(plate.naturalHeight * scale);
    ctx.drawImage(plate, 0, 0, photo.width, photo.height);
    if (print && quad) {
        const scaled = quad.map((point) => ({ x: point.x * scale, y: point.y * scale }));
        drawPrint(ctx, print, scaled);
        scrubScreenKey(photo, scaled);
    }
    drawHandles();
    document.getElementById('empty').hidden = true;
}

function drawHandles() {
    ui.clearRect(0, 0, handles.width, handles.height);
    if (!quad || !showGuides) {
        return;
    }
    const scale = viewScale;
    ui.strokeStyle = 'rgba(255, 59, 48, 0.9)';
    ui.fillStyle = '#ff3b30';
    ui.lineWidth = 1.5;
    ui.beginPath();
    quad.forEach((point, index) => {
        const x = point.x * scale;
        const y = point.y * scale;
        if (index === 0) {
            ui.moveTo(x, y);
        } else {
            ui.lineTo(x, y);
        }
    });
    ui.closePath();
    ui.stroke();
    quad.forEach((point) => {
        ui.beginPath();
        ui.arc(point.x * scale, point.y * scale, 6, 0, Math.PI * 2);
        ui.fill();
    });
}

function startDrag(event) {
    if (!quad || !plate) {
        return;
    }
    const point = localPoint(event);
    const scale = photo.width / plate.naturalWidth;
    drag = quad.findIndex((corner) => Math.hypot(corner.x * scale - point.x, corner.y * scale - point.y) < 16);
    if (drag >= 0) {
        handles.setPointerCapture(event.pointerId);
    }
}

function moveDrag(event) {
    if (drag < 0 || !plate) {
        return;
    }
    const point = localPoint(event);
    const scale = photo.width / plate.naturalWidth;
    quad[drag] = { x: point.x / scale, y: point.y / scale };
    render();
}

function sampleScreen(event) {
    if (!event.altKey || !plate) {
        return;
    }
    const point = localPoint(event);
    const sample = document.createElement('canvas');
    sample.width = plate.naturalWidth;
    sample.height = plate.naturalHeight;
    const sampleContext = sample.getContext('2d', { willReadFrequently: true });
    sampleContext.drawImage(plate, 0, 0);
    const pixel = sampleContext.getImageData(
        Math.min(plate.naturalWidth - 1, Math.max(0, Math.round(point.x / viewScale))),
        Math.min(plate.naturalHeight - 1, Math.max(0, Math.round(point.y / viewScale))),
        1,
        1,
    ).data;
    quad = detectQuad(plate, { r: pixel[0], g: pixel[1], b: pixel[2] }) ?? quad;
    render();
}

async function exportPng() {
    if (!plate) {
        return;
    }
    download(await compositeBlob(plate, print, quad), 'mockup.png');
}

function paintStrip() {
    const strip = document.getElementById('strip');
    strip.replaceChildren();
    frames.forEach((blob) => {
        const image = document.createElement('img');
        image.src = URL.createObjectURL(blob);
        strip.append(image);
    });
}

async function record() {
    if (frames.length < 2) {
        return;
    }
    const seconds = Number(value('duration'));
    const images = await Promise.all(frames.map(loadBlob));
    const film = document.createElement('canvas');
    const width = Math.min(1280, images[0].naturalWidth);
    const height = Math.round(images[0].naturalHeight * (width / images[0].naturalWidth));
    film.width = width;
    film.height = height;
    const filmCtx = film.getContext('2d');
    const stream = film.captureStream(30);
    const mime = ['video/mp4;codecs=avc1.42E01E', 'video/webm;codecs=vp9', 'video/webm;codecs=vp8', 'video/webm']
        .find((type) => MediaRecorder.isTypeSupported(type));
    if (!mime) {
        status.textContent = 'Este navegador não grava vídeo.';
        return;
    }
    const recorder = new MediaRecorder(stream, { mimeType: mime, videoBitsPerSecond: 6_000_000 });
    const chunks = [];
    recorder.ondataavailable = (event) => {
        if (event.data.size) {
            chunks.push(event.data);
        }
    };
    const done = new Promise((resolve) => {
        recorder.onstop = () => resolve(new Blob(chunks, { type: mime }));
    });
    recorder.start();
    const started = performance.now();
    await new Promise((resolve) => {
        const tick = (now) => {
            const progress = Math.min(1, (now - started) / (seconds * 1000));
            const index = Math.min(images.length - 1, Math.floor(progress * images.length));
            filmCtx.drawImage(images[index], 0, 0, width, height);
            if (progress < 1) {
                requestAnimationFrame(tick);
            } else {
                resolve();
            }
        };
        requestAnimationFrame(tick);
    });
    recorder.stop();
    download(await done, mime.includes('mp4') ? 'mockup.mp4' : 'mockup.webm');
}

function compositeBlob(base, screen, corners) {
    const canvas = document.createElement('canvas');
    canvas.width = base.naturalWidth;
    canvas.height = base.naturalHeight;
    const context = canvas.getContext('2d');
    context.drawImage(base, 0, 0);
    if (screen && corners) {
        drawPrint(context, screen, corners);
        scrubScreenKey(canvas, corners);
    }
    return toPngBlob(canvas);
}

function toPngBlob(source) {
    return new Promise((resolve) => {
        if (source instanceof HTMLCanvasElement) {
            source.toBlob((blob) => resolve(blob), 'image/png');
            return;
        }
        const canvas = document.createElement('canvas');
        canvas.width = source.naturalWidth;
        canvas.height = source.naturalHeight;
        canvas.getContext('2d').drawImage(source, 0, 0);
        canvas.toBlob((blob) => resolve(blob), 'image/png');
    });
}

function isScreenPixel(r, g, b, sample) {
    if (sample) {
        return Math.hypot(r - sample.r, g - sample.g, b - sample.b) < 48;
    }
    const magenta = Math.min(r, b);
    return magenta > 80 && g < 130 && magenta > g * 1.35 && Math.abs(r - b) < 110;
}

function detectQuad(image, sample) {
    const max = 420;
    const scale = Math.min(1, max / image.naturalWidth, max / image.naturalHeight);
    const canvas = document.createElement('canvas');
    canvas.width = Math.max(1, Math.round(image.naturalWidth * scale));
    canvas.height = Math.max(1, Math.round(image.naturalHeight * scale));
    const context = canvas.getContext('2d', { willReadFrequently: true });
    context.drawImage(image, 0, 0, canvas.width, canvas.height);
    const data = context.getImageData(0, 0, canvas.width, canvas.height).data;
    let mask = new Uint8Array(canvas.width * canvas.height);
    for (let index = 0; index < mask.length; index += 1) {
        const offset = index * 4;
        mask[index] = isScreenPixel(data[offset], data[offset + 1], data[offset + 2], sample) ? 1 : 0;
    }
    mask = dilate(mask, canvas.width, canvas.height, 4);
    const blob = largestBlob(mask, canvas.width, canvas.height);
    if (!blob || blob.length < 80) {
        return null;
    }
    return outsetQuad(extremeCorners(blob).map((point) => ({
        x: point.x / scale,
        y: point.y / scale,
    })), 0.018);
}

function dilate(mask, width, height, radius) {
    const next = new Uint8Array(mask.length);
    for (let y = 0; y < height; y += 1) {
        for (let x = 0; x < width; x += 1) {
            if (!mask[y * width + x]) {
                continue;
            }
            for (let dy = -radius; dy <= radius; dy += 1) {
                const nextY = y + dy;
                if (nextY < 0 || nextY >= height) {
                    continue;
                }
                for (let dx = -radius; dx <= radius; dx += 1) {
                    const nextX = x + dx;
                    if (nextX < 0 || nextX >= width) {
                        continue;
                    }
                    next[nextY * width + nextX] = 1;
                }
            }
        }
    }
    return next;
}

function outsetQuad(quad, fraction) {
    const center = quad.reduce((acc, point) => ({
        x: acc.x + point.x / quad.length,
        y: acc.y + point.y / quad.length,
    }), { x: 0, y: 0 });

    return quad.map((point) => ({
        x: point.x + (point.x - center.x) * fraction,
        y: point.y + (point.y - center.y) * fraction,
    }));
}

function scrubScreenKey(canvas, destination) {
    const context = canvas.getContext('2d', { willReadFrequently: true });
    const image = context.getImageData(0, 0, canvas.width, canvas.height);
    const data = image.data;
    const width = canvas.width;
    const height = canvas.height;
    const count = width * height;
    const key = new Uint8Array(count);
    let found = 0;
    for (let index = 0; index < count; index += 1) {
        const offset = index * 4;
        if (isScreenPixel(data[offset], data[offset + 1], data[offset + 2])) {
            key[index] = 1;
            found += 1;
        }
    }
    if (found === 0) {
        return;
    }
    const origin = new Int32Array(count);
    origin.fill(-1);
    const queue = new Int32Array(count);
    let tail = 0;
    for (let index = 0; index < count; index += 1) {
        if (key[index]) {
            continue;
        }
        const x = index % width;
        const y = Math.floor(index / width);
        if (destination && !pointInQuad({ x, y }, destination)) {
            continue;
        }
        const neighbors = [
            x > 0 ? index - 1 : -1,
            x < width - 1 ? index + 1 : -1,
            y > 0 ? index - width : -1,
            y < height - 1 ? index + width : -1,
        ];
        if (!neighbors.some((next) => next >= 0 && key[next])) {
            continue;
        }
        origin[index] = index;
        queue[tail] = index;
        tail += 1;
    }
    let head = 0;
    while (head < tail) {
        const index = queue[head];
        head += 1;
        const x = index % width;
        const y = Math.floor(index / width);
        const from = origin[index];
        [[1, 0], [-1, 0], [0, 1], [0, -1]].forEach(([dx, dy]) => {
            const nextX = x + dx;
            const nextY = y + dy;
            if (nextX < 0 || nextY < 0 || nextX >= width || nextY >= height) {
                return;
            }
            const next = nextY * width + nextX;
            if (!key[next] || origin[next] !== -1) {
                return;
            }
            origin[next] = from;
            queue[tail] = next;
            tail += 1;
        });
    }
    for (let index = 0; index < count; index += 1) {
        if (!key[index] || origin[index] < 0) {
            continue;
        }
        const source = origin[index] * 4;
        const offset = index * 4;
        data[offset] = data[source];
        data[offset + 1] = data[source + 1];
        data[offset + 2] = data[source + 2];
        data[offset + 3] = 255;
    }
    context.putImageData(image, 0, 0);
}

function pointInQuad(point, quad) {
    let inside = false;
    for (let index = 0, previous = quad.length - 1; index < quad.length; previous = index, index += 1) {
        const current = quad[index];
        const prior = quad[previous];
        const crosses = (current.y > point.y) !== (prior.y > point.y)
            && point.x < ((prior.x - current.x) * (point.y - current.y)) / ((prior.y - current.y) || 1e-9) + current.x;
        if (crosses) {
            inside = !inside;
        }
    }
    return inside;
}

function largestBlob(mask, width, height) {
    const seen = new Uint8Array(mask.length);
    let best = null;
    for (let start = 0; start < mask.length; start += 1) {
        if (!mask[start] || seen[start]) {
            continue;
        }
        const stack = [start];
        const points = [];
        seen[start] = 1;
        while (stack.length) {
            const index = stack.pop();
            const x = index % width;
            const y = Math.floor(index / width);
            points.push({ x, y });
            [[1, 0], [-1, 0], [0, 1], [0, -1]].forEach(([dx, dy]) => {
                const nextX = x + dx;
                const nextY = y + dy;
                const next = nextY * width + nextX;
                if (nextX < 0 || nextY < 0 || nextX >= width || nextY >= height || !mask[next] || seen[next]) {
                    return;
                }
                seen[next] = 1;
                stack.push(next);
            });
        }
        if (!best || points.length > best.length) {
            best = points;
        }
    }
    return best;
}

function extremeCorners(points) {
    const score = (point, sx, sy) => point.x * sx + point.y * sy;
    return [
        points.reduce((best, point) => (score(point, -1, -1) < score(best, -1, -1) ? point : best)),
        points.reduce((best, point) => (score(point, 1, -1) > score(best, 1, -1) ? point : best)),
        points.reduce((best, point) => (score(point, 1, 1) > score(best, 1, 1) ? point : best)),
        points.reduce((best, point) => (score(point, -1, 1) < score(best, -1, 1) ? point : best)),
    ];
}

function defaultQuad(image) {
    const width = image.naturalWidth;
    const height = image.naturalHeight;
    return [
        { x: width * 0.22, y: height * 0.16 },
        { x: width * 0.78, y: height * 0.16 },
        { x: width * 0.78, y: height * 0.72 },
        { x: width * 0.22, y: height * 0.72 },
    ];
}

function drawPrint(context, image, destination) {
    const region = printRegion(image);
    const source = [
        { x: region.x, y: region.y },
        { x: region.x + region.width, y: region.y },
        { x: region.x + region.width, y: region.y + region.height },
        { x: region.x, y: region.y + region.height },
    ];
    const matrix = homography(source, destination);
    const columns = 24;
    const rows = 24;
    for (let row = 0; row < rows; row += 1) {
        for (let column = 0; column < columns; column += 1) {
            const tile = [
                { x: region.x + (region.width * column) / columns, y: region.y + (region.height * row) / rows },
                { x: region.x + (region.width * (column + 1)) / columns, y: region.y + (region.height * row) / rows },
                { x: region.x + (region.width * (column + 1)) / columns, y: region.y + (region.height * (row + 1)) / rows },
                { x: region.x + (region.width * column) / columns, y: region.y + (region.height * (row + 1)) / rows },
            ];
            blit(context, image, tile, tile.map((point) => apply(matrix, point)));
        }
    }
}

function printRegion(image) {
    const width = image.naturalWidth;
    const height = image.naturalHeight;
    const region = crop ?? { x: 0, y: 0, width, height };
    const y = clamp(region.y, 0, Math.max(0, height - 1));
    return {
        x: 0,
        y,
        width,
        height: clamp(region.height, 1, height - y),
    };
}

function blit(context, image, source, destination) {
    [[0, 1, 2], [0, 2, 3]].forEach((triangle) => {
        const from = triangle.map((index) => source[index]);
        const to = triangle.map((index) => destination[index]);
        const transform = affine(from, to);
        context.save();
        context.beginPath();
        context.moveTo(to[0].x, to[0].y);
        context.lineTo(to[1].x, to[1].y);
        context.lineTo(to[2].x, to[2].y);
        context.closePath();
        context.clip();
        context.setTransform(transform.a, transform.b, transform.c, transform.d, transform.e, transform.f);
        context.drawImage(image, 0, 0);
        context.restore();
    });
}

function affine(from, to) {
    const denominator = from[0].x * (from[1].y - from[2].y) + from[1].x * (from[2].y - from[0].y) + from[2].x * (from[0].y - from[1].y);
    const solve = (values) => ({
        x: (values[0] * (from[1].y - from[2].y) + values[1] * (from[2].y - from[0].y) + values[2] * (from[0].y - from[1].y)) / denominator,
        y: (values[0] * (from[2].x - from[1].x) + values[1] * (from[0].x - from[2].x) + values[2] * (from[1].x - from[0].x)) / denominator,
        z: (values[0] * (from[1].x * from[2].y - from[2].x * from[1].y) + values[1] * (from[2].x * from[0].y - from[0].x * from[2].y) + values[2] * (from[0].x * from[1].y - from[1].x * from[0].y)) / denominator,
    });
    const x = solve(to.map((point) => point.x));
    const y = solve(to.map((point) => point.y));
    return { a: x.x, b: y.x, c: x.y, d: y.y, e: x.z, f: y.z };
}

function homography(from, to) {
    const rows = [];
    from.forEach((point, index) => {
        const target = to[index];
        rows.push([point.x, point.y, 1, 0, 0, 0, -target.x * point.x, -target.x * point.y, target.x]);
        rows.push([0, 0, 0, point.x, point.y, 1, -target.y * point.x, -target.y * point.y, target.y]);
    });
    return solve(rows);
}

function solve(rows) {
    const matrix = rows.map((row) => row.slice());
    const size = 8;
    for (let column = 0; column < size; column += 1) {
        let pivot = column;
        for (let row = column + 1; row < size; row += 1) {
            if (Math.abs(matrix[row][column]) > Math.abs(matrix[pivot][column])) {
                pivot = row;
            }
        }
        [matrix[column], matrix[pivot]] = [matrix[pivot], matrix[column]];
        const scale = matrix[column][column] || 1e-9;
        for (let index = column; index <= size; index += 1) {
            matrix[column][index] /= scale;
        }
        for (let row = 0; row < size; row += 1) {
            if (row === column) {
                continue;
            }
            const factor = matrix[row][column];
            for (let index = column; index <= size; index += 1) {
                matrix[row][index] -= factor * matrix[column][index];
            }
        }
    }
    return matrix.map((row) => row[size]).concat(1);
}

function apply(matrix, point) {
    const weight = matrix[6] * point.x + matrix[7] * point.y + matrix[8];
    return {
        x: (matrix[0] * point.x + matrix[1] * point.y + matrix[2]) / weight,
        y: (matrix[3] * point.x + matrix[4] * point.y + matrix[5]) / weight,
    };
}

async function run(label, action) {
    if (busy) {
        return;
    }
    busy = true;
    status.textContent = label;
    document.querySelectorAll('button').forEach((button) => {
        button.disabled = true;
    });
    try {
        await action();
    } catch (error) {
        status.textContent = error.message;
    } finally {
        busy = false;
        document.getElementById('generate').disabled = !config.configured;
        const animateButton = document.getElementById('animate');
        if (animateButton) {
            animateButton.disabled = !config.configured;
        }
        document.getElementById('capture').disabled = false;
        document.getElementById('redetect').disabled = false;
        document.getElementById('export').disabled = false;
        const recordButton = document.getElementById('record');
        if (recordButton) {
            recordButton.disabled = frames.length < 2;
        }
    }
}

function syncDevice() {
    const device = value('device');
    document.querySelectorAll('input[name="reference"]').forEach((input) => {
        input.checked = input.dataset.device === device;
    });
    document.getElementById('size').value = device === 'macbook' ? '2048x1152' : '1152x2048';
    syncViewportHint();
}

function syncViewportHint() {
    const viewport = config.viewports?.[value('device')];
    document.getElementById('viewport-hint').textContent = viewport ? `A captura usa ${viewport.label}.` : '';
}

function value(id) {
    return document.getElementById(id).value;
}

function clamp(value, min, max) {
    return Math.min(max, Math.max(min, value));
}

function localPoint(event) {
    const rect = handles.getBoundingClientRect();
    return {
        x: (event.clientX - rect.left) * (handles.width / rect.width),
        y: (event.clientY - rect.top) * (handles.height / rect.height),
    };
}

function loadBlob(blob) {
    return new Promise((resolve, reject) => {
        const image = new Image();
        image.onload = () => resolve(image);
        image.onerror = () => reject(new Error('Quadro ilegível.'));
        image.src = URL.createObjectURL(blob);
    });
}

function download(blob, name) {
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = name;
    link.click();
    URL.revokeObjectURL(link.href);
}

async function postJson(url, body) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(body),
    });
    return readPayload(response);
}

async function postForm(url, body) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body,
    });
    return readPayload(response);
}

async function readPayload(response) {
    const payload = await response.json().catch(() => ({}));
    if (!response.ok) {
        const message = payload.message
            || Object.values(payload.errors || {}).flat()[0]
            || `Erro ${response.status}.`;
        throw new Error(message);
    }
    return payload;
}
