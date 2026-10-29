// Gera resources/js/site/static/pages/*.tsx a partir do conteúdo publicado no CMS.
// Cada página vira JSX literal (marcação + texto) para permitir edição direta e o live mode do Impeccable.
// Uso: node scripts/export-static-pages.mjs [--base=http://127.0.0.1:8000] [slug ...]

import { mkdir, readdir, rm, writeFile } from 'node:fs/promises';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { execFileSync } from 'node:child_process';
import { Fragment, createElement } from 'react';
import { createServer } from 'vite';
import react from '@vitejs/plugin-react';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const outDir = join(root, 'resources/js/site/static/pages');
const args = process.argv.slice(2);
const base = (args.find((arg) => arg.startsWith('--base='))?.slice(7) ?? 'http://127.0.0.1:8000').replace(/\/$/, '');
const only = args.filter((arg) => !arg.startsWith('--'));

// Componentes com estado ou efeito continuam como componente; o resto vira marcação literal.
const KEEP = {
  Rotating: '../../hero',
  AgentLog: '../../hero',
  CasesPair: '../../cases',
  CasesList: '../../cases',
  ContactForm: '../../sections',
};

const VOID = new Set(['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'source', 'track', 'wbr']);

const ATTR = {
  class: 'className',
  for: 'htmlFor',
  tabindex: 'tabIndex',
  colspan: 'colSpan',
  rowspan: 'rowSpan',
  srcset: 'srcSet',
  autocomplete: 'autoComplete',
  readonly: 'readOnly',
  maxlength: 'maxLength',
  'stroke-width': 'strokeWidth',
  'stroke-linecap': 'strokeLinecap',
  'stroke-linejoin': 'strokeLinejoin',
  'fill-rule': 'fillRule',
  'clip-rule': 'clipRule',
};

function slugs() {
  if (only.length) {
    return only;
  }

  const php = "echo json_encode(App\\Models\\Page::query()->where('status','published')->orderBy('slug')->pluck('slug'));";
  const output = execFileSync('php', ['artisan', 'tinker', '--execute', php], {
    cwd: root,
    env: { ...process.env, DB_HOST: process.env.DB_HOST ?? '127.0.0.1', DB_PORT: process.env.DB_PORT ?? '3307', CACHE_STORE: 'array' },
    encoding: 'utf8',
  });

  return JSON.parse(output.trim().split('\n').pop());
}

function decode(value) {
  return value
    .replace(/&#x([0-9a-f]+);/gi, (_, hex) => String.fromCodePoint(parseInt(hex, 16)))
    .replace(/&#(\d+);/g, (_, dec) => String.fromCodePoint(Number(dec)))
    .replace(/&nbsp;/g, '\u00a0')
    .replace(/&quot;/g, '"')
    .replace(/&apos;/g, "'")
    .replace(/&lt;/g, '<')
    .replace(/&gt;/g, '>')
    .replace(/&amp;/g, '&');
}

function styleObject(value) {
  const out = {};
  for (const rule of value.split(';')) {
    const index = rule.indexOf(':');
    if (index < 1) {
      continue;
    }
    const key = rule.slice(0, index).trim();
    const prop = key.startsWith('--') ? key : key.replace(/-([a-z])/g, (_, char) => char.toUpperCase());
    out[prop] = rule.slice(index + 1).trim();
  }
  return out;
}

// Parser mínimo para o HTML do editor rico (p, strong, em, a, ul, li, br...).
function parseHtml(html) {
  const rootNode = { tag: null, props: {}, children: [] };
  const stack = [rootNode];
  const pattern = /<!--[\s\S]*?-->|<\/([a-zA-Z0-9-]+)\s*>|<([a-zA-Z0-9-]+)((?:\s+[^\s=/>]+(?:\s*=\s*(?:"[^"]*"|'[^']*'|[^\s>]+))?)*)\s*(\/?)>|([^<]+)/g;
  let match;

  while ((match = pattern.exec(html))) {
    const [, close, open, attrs, selfClose, textValue] = match;
    const top = stack[stack.length - 1];

    if (textValue !== undefined) {
      top.children.push(decode(textValue));
    } else if (open) {
      const tag = open.toLowerCase();
      const props = {};
      const attrPattern = /([^\s=/>]+)(?:\s*=\s*(?:"([^"]*)"|'([^']*)'|([^\s>]+)))?/g;
      let attr;
      while ((attr = attrPattern.exec(attrs ?? ''))) {
        const name = attr[1].toLowerCase();
        const raw = attr[2] ?? attr[3] ?? attr[4];
        const key = ATTR[name] ?? name;
        props[key] = raw === undefined ? true : name === 'style' ? styleObject(decode(raw)) : decode(raw);
      }
      const node = { tag, props, children: [] };
      top.children.push(node);
      if (!selfClose && !VOID.has(tag)) {
        stack.push(node);
      }
    } else if (close) {
      const tag = close.toLowerCase();
      const index = stack.map((node) => node.tag).lastIndexOf(tag);
      if (index > 0) {
        stack.length = index;
      }
    }
  }

  return rootNode.children;
}

function expand(node, ctx) {
  if (node === null || node === undefined || typeof node === 'boolean') {
    return [];
  }
  if (typeof node === 'string' || typeof node === 'number') {
    return [String(node)];
  }
  if (Array.isArray(node)) {
    return node.flatMap((child) => expand(child, ctx));
  }

  const { type, props } = node;

  if (typeof type === 'symbol' || type === ctx.Fragment) {
    return expand(props.children, ctx);
  }

  if (typeof type === 'function') {
    const name = type.displayName || type.name;
    if (KEEP[name]) {
      ctx.imports.add(name);
      const { children, ...rest } = props;
      return [{ component: name, props: rest }];
    }
    const result = expand(type(props), ctx);
    if (name === 'SectionView') {
      return [{ comment: `seção: ${props.kind}` }, ...result];
    }
    return result;
  }

  const { children, dangerouslySetInnerHTML, ...rest } = props;
  const attrs = {};
  for (const [key, value] of Object.entries(rest)) {
    if (value === undefined || value === null || value === false || typeof value === 'function') {
      continue;
    }
    attrs[key] = value;
  }

  const kids = dangerouslySetInnerHTML ? parseHtml(dangerouslySetInnerHTML.__html) : expand(children, ctx);
  return [{ tag: type, props: attrs, children: kids }];
}

function attrString(props) {
  return Object.entries(props)
    .map(([key, value]) => {
      if (value === true) {
        return key;
      }
      if (typeof value === 'string') {
        return /["&{}\n\\]/.test(value) ? `${key}={${JSON.stringify(value)}}` : `${key}="${value}"`;
      }
      return `${key}={${JSON.stringify(value)}}`;
    })
    .map((attr) => ` ${attr}`)
    .join('');
}

function textJsx(value, inline) {
  if (!inline && !value.trim()) {
    return '';
  }
  if (/[{}<>&\n]/.test(value) || (!inline && value !== value.trim())) {
    return `{${JSON.stringify(value)}}`;
  }
  return value;
}

function renderInline(node) {
  if (typeof node === 'string') {
    return textJsx(node, true);
  }
  if (node.comment) {
    return '';
  }
  if (node.component) {
    return componentJsx(node);
  }
  const open = `<${node.tag}${attrString(node.props)}`;
  if (!node.children.length) {
    return `${open} />`;
  }
  return `${open}>${node.children.map(renderInline).join('')}</${node.tag}>`;
}

function componentJsx(node) {
  if (node.component === 'ContactForm') {
    return '<ContactForm form={form} />';
  }
  return `<${node.component}${attrString(node.props)} />`;
}

function render(node, depth) {
  const pad = '  '.repeat(depth);

  if (typeof node === 'string') {
    const value = textJsx(node, false);
    return value ? `${pad}${value}` : '';
  }
  if (node.comment) {
    return `${pad}{/* ${node.comment} */}`;
  }
  if (node.component) {
    return `${pad}${componentJsx(node)}`;
  }

  const open = `<${node.tag}${attrString(node.props)}`;
  if (!node.children.length) {
    return `${pad}${open} />`;
  }

  const hasText = node.children.some((child) => typeof child === 'string' && child.trim());
  if (hasText) {
    return `${pad}${renderInline(node)}`;
  }

  const inner = node.children.map((child) => render(child, depth + 1)).filter(Boolean).join('\n');
  return `${pad}${open}>\n${inner}\n${pad}</${node.tag}>`;
}

function pageName(slug) {
  return `Page${slug.split(/[^a-z0-9]+/i).filter(Boolean).map((part) => part[0].toUpperCase() + part.slice(1)).join('')}`;
}

function fileName(slug) {
  return slug.replace(/[^a-z0-9-]+/gi, '__');
}

async function main() {
  globalThis.window = { location: { origin: base } };

  const server = await createServer({
    root,
    configFile: false,
    logLevel: 'error',
    plugins: [react()],
    server: { middlewareMode: true, hmr: false },
    appType: 'custom',
  });

  try {
    const { Sections } = await server.ssrLoadModule('/resources/js/site/sections.tsx');

    await mkdir(outDir, { recursive: true });
    const list = slugs();
    const written = [];

    for (const slug of list) {
      const response = await fetch(`${base}/api/pages/${encodeURIComponent(slug)}`, { headers: { Accept: 'application/json' } });
      if (!response.ok) {
        console.warn(`pulando ${slug}: HTTP ${response.status}`);
        continue;
      }
      const page = await response.json();
      const ctx = { Fragment, imports: new Set() };
      const tree = expand(createElement(Sections, { sections: page.sections ?? [], form: null }), ctx);
      const body = tree.map((node) => render(node, 3)).filter(Boolean).join('\n');

      const groups = {};
      for (const name of ctx.imports) {
        (groups[KEEP[name]] ??= []).push(name);
      }
      const imports = Object.entries(groups)
        .map(([from, names]) => `import { ${names.sort().join(', ')} } from '${from}';`)
        .join('\n');
      const usesForm = ctx.imports.has('ContactForm');
      const name = pageName(slug);

      const source = [
        `// Gerado por scripts/export-static-pages.mjs a partir de /${slug === 'home' ? '' : slug} (CMS).`,
        '// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.',
        "import type { StaticPageProps } from '../index';",
        imports,
        '',
        `export default function ${name}(${usesForm ? '{ form }: StaticPageProps' : '_props: StaticPageProps'}) {`,
        '  return (',
        '    <>',
        body,
        '    </>',
        '  );',
        '}',
        '',
      ].filter((line, index) => !(index === 3 && line === '')).join('\n');

      await writeFile(join(outDir, `${fileName(slug)}.tsx`), source);
      written.push(slug);
    }

    if (!only.length) {
      const keep = new Set(written.map((slug) => `${fileName(slug)}.tsx`));
      for (const file of await readdir(outDir)) {
        if (file.endsWith('.tsx') && !keep.has(file)) {
          await rm(join(outDir, file));
        }
      }
    }

    const all = only.length ? (await readdir(outDir)).filter((file) => file.endsWith('.tsx')).map((file) => file.replace(/\.tsx$/, '').replace(/__/g, '/')) : written;
    const entries = all.sort().map((slug) => `  ${JSON.stringify(slug)}: () => import('./pages/${fileName(slug)}'),`).join('\n');
    const index = [
      '// Gerado por scripts/export-static-pages.mjs. STATIC_MODE é editado à mão.',
      "import type { ComponentType } from 'react';",
      "import type { SiteForm } from '../types';",
      '',
      '// true: o front renderiza as páginas de ./pages; false: renderiza as seções vindas do CMS.',
      'export const STATIC_MODE = true;',
      '',
      'export type StaticPageProps = { form: SiteForm | null };',
      '',
      'export const STATIC_PAGES: Record<string, () => Promise<{ default: ComponentType<StaticPageProps> }>> = {',
      entries,
      '};',
      '',
    ].join('\n');

    const indexPath = join(outDir, '..', 'index.ts');
    let previous = '';
    try {
      previous = (await import('node:fs')).readFileSync(indexPath, 'utf8');
    } catch {}
    const mode = previous.match(/export const STATIC_MODE = (true|false);/)?.[1];
    await writeFile(indexPath, mode ? index.replace('STATIC_MODE = true', `STATIC_MODE = ${mode}`) : index);

    console.log(`${written.length} páginas exportadas para resources/js/site/static/pages`);
  } finally {
    await server.close();
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
