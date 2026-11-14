// Gera o HTML público de cada URL a partir dos componentes React.
// Uso: node scripts/prerender.mjs storage/app/prerender-payloads.json

import { mkdir, readFile, writeFile } from 'node:fs/promises';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { createServer } from 'vite';
import react from '@vitejs/plugin-react';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const input = resolve(root, process.argv[2] ?? 'storage/app/prerender-payloads.json');
const outRoot = join(root, 'storage/app/prerender');

function fileFor(page) {
  const locale = page.locale || 'pt-BR';
  const path = (page.path || '/').replace(/^\/+|\/+$/g, '');
  const name = path === '' ? 'index.html' : `${path}.html`;

  return join(outRoot, locale, name);
}

const vite = await createServer({
  configFile: false,
  root,
  appType: 'custom',
  server: { middlewareMode: true, hmr: false },
  plugins: [react()],
  logLevel: 'error',
});

try {
  const { renderSite } = await vite.ssrLoadModule('/resources/js/site/document.tsx');
  const payloads = JSON.parse(await readFile(input, 'utf8'));
  let count = 0;

  for (const item of payloads) {
    const body = renderSite(item.page, item.site);
    const file = fileFor(item.page);
    await mkdir(dirname(file), { recursive: true });
    await writeFile(file, body);
    count += 1;
    process.stdout.write(`${item.page.path}\n`);
  }

  process.stdout.write(`${count} páginas\n`);
} finally {
  await vite.close();
}
