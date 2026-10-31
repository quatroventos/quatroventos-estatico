// Converte as páginas de resources/js/site/static/pages em seções do CMS (database/data/pages/*.json).
// Renderiza cada página com SSR, lê o HTML pelos padrões de seção e grava só a chave "sections" de cada JSON;
// slug, SEO e status já existentes no arquivo são preservados.
// Uso: node scripts/static-to-cms.mjs [slug ...] [--check]
// --check renderiza as seções geradas com o renderer do CMS e compara a estrutura com a página estática.
import { createElement } from 'react';
import { renderToStaticMarkup } from 'react-dom/server';
import { createServer } from 'vite';
import react from '@vitejs/plugin-react';
import { existsSync, readFileSync } from 'node:fs';
import { mkdir, readdir, writeFile } from 'node:fs/promises';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const pagesDir = join(root, 'resources/js/site/static/pages');
const outDir = join(root, 'database/data/pages');
const args = process.argv.slice(2);
const check = args.includes('--check');
const only = args.filter((arg) => !arg.startsWith('--'));

const TONES = { hero: 'dark', steps: 'dark', cta: 'dark', quotes: 'white', logos: 'white', showcase: 'dark', 'case-grid': 'dark', adhoc: 'dark', method: 'dark' };

const CANVAS = {
  FlowCanvas: 'flow-agent', RagCanvas: 'rag', N8nCanvas: 'n8n', LlmCanvas: 'llm', SystemCanvas: 'system', SoftwareCanvas: 'software',
  SaasCanvas: 'saas', ModernizeCanvas: 'modernize', PipelineCanvas: 'pipeline', UxCanvas: 'ux', QaCanvas: 'qa', SitesCanvas: 'sites',
  LandingCanvas: 'landing', EcomCanvas: 'ecom', WpCanvas: 'wp', SeoCanvas: 'seo', SustainCanvas: 'sustain', MaintainCanvas: 'maintain',
  RecoverCanvas: 'recover', HostCanvas: 'host', VpsCanvas: 'vps',
};

// Categoria de cada case em /cases: o primeiro grupo cujos serviços o case atende.
const BROWSER_SERVICES = {
  ia: ['inteligencia-artificial', 'agentes-de-ia', 'automacao-com-ia', 'ia-com-dados-da-empresa', 'integracao-de-llm', 'sistemas-com-ia'],
  software: ['desenvolvimento-de-software', 'software-sob-medida', 'desenvolvimento-de-saas', 'modernizacao-de-sistemas', 'erp-e-crm', 'ui-ux-design', 'qa-e-testes', 'desenvolvimento-de-ecommerce', 'sustentacao-de-software'],
  sites: ['desenvolvimento-web', 'desenvolvimento-de-sites', 'landing-pages', 'sites-wordpress', 'seo-e-llms', 'manutencao-de-sites', 'recuperacao-de-site-wordpress'],
};

const VOID = new Set(['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'source', 'track', 'wbr']);
const SVG_VOID = new Set(['path', 'circle', 'rect', 'line', 'polyline', 'polygon', 'ellipse', 'use', 'stop']);

function decode(value) {
  return value
    .replace(/&lt;/g, '<')
    .replace(/&gt;/g, '>')
    .replace(/&quot;/g, '"')
    .replace(/&#x27;/g, "'")
    .replace(/&#39;/g, "'")
    .replace(/&nbsp;/g, '\u00a0')
    .replace(/&amp;/g, '&');
}

function escapeText(value) {
  return value.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function parse(html) {
  const top = { tag: '#root', attrs: {}, children: [] };
  const stack = [top];
  const pattern = /<!--[\s\S]*?-->|<\/([a-zA-Z0-9-]+)\s*>|<([a-zA-Z0-9-]+)((?:\s+[^\s=/>]+(?:\s*=\s*(?:"[^"]*"|'[^']*'|[^\s>]+))?)*)\s*(\/?)>|([^<]+)/g;
  let match;

  while ((match = pattern.exec(html))) {
    const [, close, open, rawAttrs, selfClose, textValue] = match;
    const parent = stack[stack.length - 1];

    if (textValue !== undefined) {
      parent.children.push(decode(textValue));
    } else if (open) {
      const tag = open.toLowerCase();
      const attrs = {};
      const attrPattern = /([^\s=/>]+)(?:\s*=\s*(?:"([^"]*)"|'([^']*)'|([^\s>]+)))?/g;
      let attr;
      while ((attr = attrPattern.exec(rawAttrs ?? ''))) {
        attrs[attr[1]] = decode(attr[2] ?? attr[3] ?? attr[4] ?? '');
      }
      const node = { tag, attrs, children: [], parent };
      parent.children.push(node);
      if (!selfClose && !VOID.has(tag) && !(SVG_VOID.has(tag) && selfClose)) {
        stack.push(node);
      }
    } else if (close) {
      const index = stack.map((node) => node.tag).lastIndexOf(close.toLowerCase());
      if (index > 0) {
        stack.length = index;
      }
    }
  }

  return top;
}

const isEl = (node) => typeof node === 'object' && node !== null;
const classes = (node) => (isEl(node) ? (node.attrs.class ?? '').split(/\s+/).filter(Boolean) : []);
const has = (node, name) => classes(node).includes(name);
const kids = (node) => (node?.children ?? []).filter(isEl);

// Seletor mínimo: "tag", ".a.b", "tag.a" e descendência com espaço.
function matches(node, simple) {
  const [tag, ...names] = simple.split('.');
  return (!tag || node.tag === tag) && names.every((name) => has(node, name));
}

function all(node, selector) {
  const parts = selector.trim().split(/\s+/);
  let current = [node];
  for (const part of parts) {
    const next = [];
    for (const item of current) {
      const walk = (el) => {
        for (const child of kids(el)) {
          if (matches(child, part)) {
            next.push(child);
          }
          walk(child);
        }
      };
      walk(item);
    }
    current = [...new Set(next)];
  }
  return current;
}

const one = (node, selector) => (node ? all(node, selector)[0] ?? null : null);
const child = (node, selector) => kids(node).find((item) => matches(item, selector)) ?? null;
const children = (node, selector) => kids(node).filter((item) => matches(item, selector));

function textOf(node) {
  if (node === null || node === undefined) {
    return '';
  }
  if (typeof node === 'string') {
    return node;
  }
  if (node.tag === 'svg' || node.tag === 'style') {
    return '';
  }
  if (node.tag === 'br') {
    return '\n';
  }
  return node.children.map(textOf).join('');
}

function serialize(node) {
  if (typeof node === 'string') {
    return escapeText(node);
  }
  const attrs = Object.entries(node.attrs).map(([key, value]) => ` ${key}="${value.replace(/&/g, '&amp;').replace(/"/g, '&quot;')}"`).join('');
  if (VOID.has(node.tag)) {
    return `<${node.tag}${attrs}>`;
  }
  return `<${node.tag}${attrs}>${node.children.map(serialize).join('')}</${node.tag}>`;
}

const inner = (node) => (node ? node.children.map(serialize).join('') : '');

// Título com *destaque*: o texto de em.mark vira *trecho*.
function marked(node) {
  if (!node) {
    return '';
  }
  return node.children.map((item) => {
    if (typeof item === 'string') {
      return item;
    }
    if (item.tag === 'em' && has(item, 'mark')) {
      return `*${textOf(item)}*`;
    }
    if (item.tag === 'svg' || has(item, 'head-go')) {
      return '';
    }
    return marked(item);
  }).join('');
}

function head(wrap) {
  const header = child(wrap, 'header');
  const h2 = one(header, 'h2');
  const link = h2 ? child(h2, 'a') : null;
  const data = { title: marked(link ?? h2), intro: inner(one(header, '.rich')) };
  if (link) {
    data.href = link.attrs.href;
  }
  return data;
}

function cta(container) {
  const link = one(container, 'a');
  if (!link) {
    return {};
  }
  const data = { cta_label: textOf(link).trim(), cta_href: link.attrs.href };
  if (has(link, 'line')) {
    data.cta_line = true;
  }
  return data;
}

function money(value) {
  return Number(value.replace(/[^\d,]/g, '').replace(',', '.'));
}

function svgKey(svg) {
  return svg ? inner(svg).replace(/\s+/g, ' ').trim() : '';
}

function heroData(section, source, lead) {
  const data = {};
  const h1 = one(section, 'h1');

  if (has(section, 'is-home')) {
    const visible = h1.children.find((item) => isEl(item) && item.attrs['aria-hidden'] === 'true');
    data.title = visible.children.filter((item) => typeof item === 'string').join('').trim();
    data.rotating = children(one(visible, '.rot'), 'span').map((item) => textOf(item));
  } else {
    data.title = marked(h1);
  }

  data.subtitle = inner(one(section, '.hero-foot .rich'));

  const actions = all(section, '.hero-foot .actions a');
  if (actions[0]) {
    data.primary_label = textOf(actions[0]).trim();
    data.primary_href = actions[0].attrs.href;
  }
  if (actions[1]) {
    data.secondary_label = textOf(actions[1]).trim();
    data.secondary_href = actions[1].attrs.href;
  }

  const badges = all(section, '.badges li').map((item) => textOf(item));
  if (badges.length) {
    data.badges = badges;
  }

  if (has(section, 'has-flow')) {
    const canvas = source.match(/<(\w+Canvas)\b([^>]*)\/>/);
    const preset = canvas?.[2].match(/preset="(\w+)"/)?.[1];
    data.visual = canvas?.[1] === 'FlowCanvas' ? `flow-${preset ?? 'agent'}` : CANVAS[canvas?.[1]] ?? '';
  }

  const card = one(section, '.lead-card');
  if (card) {
    data.lead_form = true;
    data.lead_whatsapp = card.attrs['data-whatsapp'];
    const title = textOf(one(card, '.lead-front .lead-form-title'));
    if (title && title !== lead.defaultTitle) {
      data.lead_title = title;
    }
  }

  return data;
}

function sectionData(section, ctx) {
  const wrap = child(section, 'div.wrap');
  const first = kids(wrap)[0];
  const second = kids(wrap)[1];
  const base = {};

  if (has(wrap, 'clients')) {
    const header = child(wrap, 'header');
    return ['logos', {
      title: marked(one(header, 'h2')),
      intro: inner(one(header, '.rich')),
      clients: true,
      mono: has(child(wrap, 'ul'), 'is-mono'),
      items: all(wrap, 'ul.logos li img').map((img) => ({ name: img.attrs.alt, image: img.attrs.src, image_alt: img.attrs.alt })),
    }];
  }

  if (has(wrap, 'contact-split')) {
    const text = kids(wrap)[0];
    return ['contact', { title: marked(one(text, 'h2')), intro: inner(one(text, '.rich')), variant: 'split' }];
  }

  if (has(first, 'prose-panel') && has(first, 'is-figures')) {
    const figs = child(first, 'h2');
    const foot = child(first, 'div.figs-foot');
    return ['figures', {
      items: children(figs, 'span.fig').map((fig) => {
        const value = child(fig, 'b');
        const prefix = textOf(one(value, '.fig-cur'));
        return { prefix, value: textOf(value).slice(prefix.length), caption: textOf(child(fig, 'small')) };
      }),
      mini: Boolean(one(figs, '.figs-mini')),
      body: textOf(child(foot, 'p')),
      ...cta(child(foot, 'div.actions')),
    }];
  }

  if (has(first, 'prose-panel')) {
    const side = child(first, 'div.prose-panel-side');
    const brand = child(first, 'div.firefly-brand');
    const logo = brand ? child(brand, 'img') : child(side, 'img.prose-logo');
    const data = { title: brand ? '' : marked(child(first, 'h2')), body: inner(child(side, 'div.rich')) };
    if (has(first, 'is-light')) {
      data.variant = 'light';
    }
    if (logo) {
      data.logo = logo.attrs.src;
      data.logo_alt = logo.attrs.alt;
    }
    if (brand) {
      data.logo_place = 'brand';
      data.logo_caption = textOf(child(brand, 'p'));
    }
    const price = child(side, 'div.prose-price');
    if (price) {
      for (const part of ['from', 'value', 'term', 'note']) {
        data[`price_${part}`] = textOf(child(price, `p.prose-price-${part}`));
      }
    }
    return ['panel', { ...data, ...cta(child(side, 'div.actions')) }];
  }

  if (has(first, 'faq-grid')) {
    return ['faq', {
      title: marked(child(first, 'h2')),
      items: all(first, 'details').map((item) => ({ question: textOf(child(item, 'summary')), answer: inner(child(item, 'div.rich')) })),
    }];
  }

  if (has(first, 'contact-grid')) {
    const text = kids(first)[0];
    return ['contact', { title: marked(one(text, 'h2')), intro: inner(one(text, '.rich')) }];
  }

  if (has(first, 'adhoc-head')) {
    const column = (side) => all(one(wrap, `.adhoc-list.${side}`), 'li').map((item) => ({
      icon: ctx.adhocIcons[svgKey(one(item, 'svg'))] ?? '',
      title: textOf(one(item, 'h3')),
      text: textOf(one(item, 'p')),
    }));
    return ['adhoc', {
      title: marked(child(first, 'h2')),
      intro: inner(child(first, 'div.rich')),
      left: column('is-left'),
      right: column('is-right'),
      screen_url: textOf(one(wrap, '.adhoc-bar .mono')),
      log: all(wrap, '.adhoc-log li span.mono').map((item) => textOf(item)),
      status: textOf(one(wrap, '.adhoc-status')),
      ...cta(one(wrap, '.adhoc-cta')),
    }];
  }

  if (has(first, 'plan-head')) {
    return ['pricing', pricing(wrap)];
  }

  if (has(first, 'cases-band')) {
    const band = first;
    return ['cases-browser', {
      ...head(band),
      categories: all(band, '.cases-filter button').filter((item) => item.attrs['data-category']).map((item) => ({
        id: item.attrs['data-category'],
        label: item.children.filter((node) => typeof node === 'string').join('').trim(),
        services: BROWSER_SERVICES[item.attrs['data-category']] ?? [],
      })),
    }];
  }

  const top = head(wrap);
  const body = second;

  if (has(body, 'uses-band')) {
    return ['uses', { ...top, items: children(body, 'li').map((item) => ({ title: textOf(child(item, 'h3')), text: textOf(child(item, 'p')) })) }];
  }

  if (has(body, 'ex-grid') && has(body, 'is-glyphs')) {
    return ['glyphs', {
      ...top,
      items: children(body, 'li').map((item) => {
        const link = one(child(item, 'h3'), 'a');
        const row = { glyph: ctx.glyphs[svgKey(child(item, 'svg'))] ?? '', title: textOf(child(item, 'h3')), text: textOf(child(item, 'p')) };
        if (link) {
          row.href = link.attrs.href;
        }
        return row;
      }),
    }];
  }

  if (has(body, 'ex-grid')) {
    const data = { ...top, items: children(body, 'li').filter((item) => !has(item, 'is-more')).map((item) => ({ title: textOf(child(item, 'h3')), text: textOf(child(item, 'p')) })) };
    if (has(body, 'is-plain')) {
      data.plain = true;
    }
    const more = children(body, 'li.is-more')[0];
    if (more) {
      const link = child(more, 'a');
      Object.assign(data, { more_title: textOf(child(more, 'h3')), more_text: textOf(child(more, 'p')), more_label: textOf(link).trim(), more_href: link?.attrs.href ?? '' });
    }
    return ['examples', data];
  }

  if (body?.tag === 'ol' && has(body, 'steps')) {
    return ['steps', {
      ...top,
      timeline: has(body, 'is-timeline'),
      items: children(body, 'li').map((item) => ({ title: textOf(child(item, 'h3')), body: inner(child(item, 'div.rich')) })),
    }];
  }

  if (has(body, 'checklist')) {
    return ['checklist', { ...top, items: children(body, 'li').map((item) => textOf(kids(item)[1])) }];
  }

  if (has(body, 'cards')) {
    const data = {
      ...top,
      items: children(body, 'li').map((item) => {
        const card = child(item, 'a');
        return { title: textOf(one(card, 'h3')), body: inner(one(card, '.rich')), href: card.attrs.href };
      }),
    };
    if (has(body, 'is-feature')) {
      data.feature = true;
    }
    if ((data.items.length === 4 || data.items.length === 5) && !has(body, `is-${data.items.length}`)) {
      data.columns = '3';
    }
    return ['cards', data];
  }

  if (has(body, 'def-rows')) {
    return ['list', {
      ...top,
      items: children(body, 'div').map((row) => {
        const dt = child(row, 'dt');
        const link = child(dt, 'a');
        return { title: textOf(dt), body: inner(one(row, 'dd .rich')), ...(link ? { href: link.attrs.href } : {}) };
      }),
    }];
  }

  if (has(body, 'link-rows')) {
    return ['list', { ...top, items: children(body, 'li').map((item) => ({ title: textOf(child(item, 'a')).trim(), href: child(item, 'a').attrs.href })) }];
  }

  if (has(body, 'chips')) {
    return ['list', { ...top, items: children(body, 'li').map((item) => ({ title: textOf(item) })) }];
  }

  if (has(body, 'quotes')) {
    return ['quotes', {
      title: top.title,
      even: has(body, 'is-even'),
      items: children(body, 'blockquote').map((item) => {
        const by = child(item, 'footer');
        return { quote: inner(child(item, 'div.rich')), author: textOf(child(by, 'b')), company: textOf(child(by, 'span')) };
      }),
    }];
  }

  if (has(body, 'logos')) {
    return ['logos', { ...top, mono: has(body, 'is-mono'), items: all(body, 'li img').map((img) => ({ name: img.attrs.alt, image: img.attrs.src, image_alt: img.attrs.alt })) }];
  }

  if (has(body, 'yc')) {
    return ['history', {
      title: top.title,
      items: all(body, '.yc-list li').map((item) => ({ year: textOf(child(item, 'span')), title: textOf(child(item, 'h3')), text: textOf(child(item, 'p')) })),
    }];
  }

  if (has(body, 'loop')) {
    return ['method', {
      ...top,
      center: textOf(one(body, '.loop-center')),
      items: all(body, '.loop-steps li').map((item) => {
        const h3 = child(item, 'h3');
        return { title: h3.children.filter((node) => typeof node === 'string').join(''), text: textOf(one(item, '.rich')) };
      }),
    }];
  }

  if (has(body, 'cases-grid')) {
    const grids = children(wrap, 'div.cases-grid');
    const slugOf = (article) => ctx.caseSlug(one(article, 'a')?.attrs.href ?? '');
    const featured = grids.filter((grid) => has(grid, 'is-single'));
    const grid = grids.find((item) => has(item, 'is-3'));

    if (grid) {
      return ['case-grid', {
        ...top,
        featured: featured.flatMap((item) => children(item, 'article')).map(slugOf).filter(Boolean),
        items: children(grid, 'article').map(slugOf).filter(Boolean),
      }];
    }

    const articles = featured.flatMap((item) => children(item, 'article'));
    return ['showcase', {
      ...top,
      items: articles.filter((item) => !has(item, 'is-placeholder')).map(slugOf).filter(Boolean),
      placeholders: articles.filter((item) => has(item, 'is-placeholder')).map((item) => ({
        name: textOf(one(item, 'h3')),
        tags: all(item, '.case-meta span').map((tag) => textOf(tag)),
        text: textOf(one(item, '.case-desc')),
      })),
    }];
  }

  throw new Error(`seção não reconhecida: ${classes(section).join('.')} > ${classes(first).join('.')} / ${classes(body).join('.')}`);
}

function pricing(wrap) {
  const header = child(wrap, 'header');
  const tabs = all(header, '.plan-billing.is-tabs button');
  const panel = kids(wrap).find((item) => item.attrs.id);
  const groups = tabs.length
    ? children(panel, 'div.plan-category').map((item) => ({ node: item, id: item.attrs['data-plan-panel'] }))
    : [{ node: wrap, id: 'planos' }];
  const firstBase = one(wrap, '.plan-base');

  const data = {
    title: marked(child(header, 'h2')),
    billing: all(header, '.plan-billing').some((item) => !has(item, 'is-tabs')),
    base_title: textOf(one(firstBase, 'h3') ?? one(firstBase, 'h4')),
    base_text: textOf(one(child(firstBase, 'div'), 'p')),
    categories: groups.map(({ node, id }) => {
      const tab = tabs.find((item) => item.attrs['data-panel'] === id);
      const categoryHead = child(node, 'div.plan-category-head');
      return {
        id,
        tab: tab ? textOf(tab) : '',
        title: textOf(child(categoryHead, 'h3')),
        text: textOf(child(categoryHead, 'p')),
        plans: all(child(node, 'div.plan-tiers'), 'article').map((article) => {
          const [name, monthly] = article.attrs['data-offer'].split('|');
          const yearly = all(article, 'p.plan-bill').find((item) => item.attrs['data-billing'] === 'year');
          const annual = money(yearly.children.filter((item) => typeof item === 'string').join(''));
          const plan = { name, monthly: String(Number(monthly)), annual: String(annual) };
          if (has(article, 'is-featured')) {
            plan.featured = true;
          }
          const from = one(article, 'li.is-from');
          if (from) {
            plan.from = textOf(from);
          }
          plan.adds = all(article, '.plan-adds li').filter((item) => !has(item, 'is-from')).map((item) => textOf(children(item, 'span').pop()));
          plan.href = one(article, 'a.plan-cta').attrs.href;
          const alt = one(article, 'a.plan-alt');
          if (alt) {
            plan.alt_href = alt.attrs.href;
          }
          return plan;
        }),
        base_items: all(child(node, 'div.plan-base'), 'ul li').map((item) => textOf(kids(item)[1])),
      };
    }),
  };

  if (tabs.length) {
    data.tabs_label = one(header, '.plan-billing.is-tabs').attrs['aria-label'];
    data.panel_id = panel.attrs.id;
  }

  const note = child(wrap, 'p.plan-note');
  if (note) {
    data.note = `<p>${inner(note)}</p>`;
  }

  return data;
}

function convert(html, source, ctx) {
  const tree = parse(html);
  const sections = [];
  const counters = {};

  for (const section of children(tree, 'section')) {
    let kind;
    let data;
    if (has(section, 'hero')) {
      kind = 'hero';
      data = heroData(section, source, ctx.lead);
    } else {
      [kind, data] = sectionData(section, ctx);
      const tone = classes(section).find((name) => name.startsWith('on-'))?.slice(3);
      if (tone && tone !== (TONES[kind] ?? 'light')) {
        data.tone = tone;
      }
      if (has(section, 'is-mark-draw')) {
        data.mark_draw = true;
      }
      const defaultAnchor = kind === 'pricing' ? 'planos' : kind === 'contact' ? 'contato' : '';
      if (section.attrs.id && section.attrs.id !== defaultAnchor) {
        data.anchor = section.attrs.id;
      }
    }

    for (const [key, value] of Object.entries(data)) {
      if (value === '' || value === undefined || (Array.isArray(value) && value.length === 0 && key !== 'items')) {
        delete data[key];
      }
    }

    counters[kind] = (counters[kind] ?? 0) + 1;
    sections.push({ id: `${kind}-${counters[kind]}`, type: `site.${kind}`, data });
  }

  return sections;
}

// Estrutura comparável: tags, classes ordenadas, ids e textos; ignora atributos de comportamento e estilo.
function outline(html) {
  const lines = [];
  const walk = (node, depth) => {
    for (const item of node.children) {
      if (typeof item === 'string') {
        const value = item.replace(/\s+/g, ' ').trim();
        if (value) {
          lines.push(`${'  '.repeat(depth)}"${value}"`);
        }
        continue;
      }
      if (item.tag === 'svg' || item.tag === 'style') {
        lines.push(`${'  '.repeat(depth)}<${item.tag}>`);
        continue;
      }
      const attrs = ['id', 'href', 'src', 'alt', 'data-offer', 'data-panel', 'data-plan-panel', 'hidden', 'aria-label']
        .filter((key) => key in item.attrs)
        .map((key) => ` ${key}=${item.attrs[key]}`)
        .join('');
      lines.push(`${'  '.repeat(depth)}<${item.tag}${classes(item).sort().map((name) => `.${name}`).join('')}${attrs}>`);
      walk(item, depth + 1);
    }
  };
  walk(parse(html), 0);
  return lines;
}

function diff(expected, actual) {
  const out = [];
  let i = 0;
  let j = 0;
  while ((i < expected.length || j < actual.length) && out.length < 12) {
    if (expected[i] === actual[j]) {
      i += 1;
      j += 1;
      continue;
    }
    const ahead = actual.indexOf(expected[i], j);
    const back = expected.indexOf(actual[j], i);
    if (ahead !== -1 && (back === -1 || ahead - j <= back - i)) {
      for (; j < ahead; j += 1) out.push(`+ ${actual[j].trim()}`);
    } else if (back !== -1) {
      for (; i < back; i += 1) out.push(`- ${expected[i].trim()}`);
    } else {
      out.push(`- ${expected[i]?.trim() ?? ''}`, `+ ${actual[j]?.trim() ?? ''}`);
      i += 1;
      j += 1;
    }
  }
  return out;
}

async function main() {
  globalThis.window = {
    location: { origin: 'http://127.0.0.1:8000', pathname: '/' },
    matchMedia: () => ({ matches: false, addEventListener() {}, removeEventListener() {} }),
  };

  const server = await createServer({ root, configFile: false, logLevel: 'error', plugins: [react()], server: { middlewareMode: true, hmr: false }, appType: 'custom' });

  try {
    const { GLYPHS } = await server.ssrLoadModule('/resources/js/site/glyphs.tsx');
    const { ADHOC_ICONS } = await server.ssrLoadModule('/resources/js/site/adhoc.tsx');
    const { SHOWCASE } = await server.ssrLoadModule('/resources/js/site/static/showcase.ts');
    const { t } = await server.ssrLoadModule('/resources/js/site/i18n/index.ts');
    const { Sections } = check ? await server.ssrLoadModule('/resources/js/site/sections.tsx') : {};

    const svgMap = (icons, attrs) => Object.fromEntries(Object.entries(icons).map(([id, node]) => [svgKey(child(parse(renderToStaticMarkup(createElement('svg', attrs, node))), 'svg')), id]));
    const hrefs = Object.fromEntries(Object.values(SHOWCASE).map((item) => [item.href, item.slug]));
    const ctx = {
      glyphs: svgMap(GLYPHS, { viewBox: '0 0 40 40' }),
      adhocIcons: svgMap(ADHOC_ICONS, { viewBox: '0 0 24 24' }),
      caseSlug: (href) => hrefs[href] ?? href.match(/^\/cases\/([a-z0-9-]+)/)?.[1] ?? '',
      lead: { defaultTitle: t('lead.title') },
    };

    await mkdir(outDir, { recursive: true });
    const files = (await readdir(pagesDir)).filter((file) => file.endsWith('.tsx') && (!only.length || only.includes(file.replace(/\.tsx$/, ''))));
    let failures = 0;

    for (const file of files) {
      const slug = file.replace(/\.tsx$/, '');
      const source = readFileSync(join(pagesDir, file), 'utf8');
      const mod = await server.ssrLoadModule(`/resources/js/site/static/pages/${file}`);
      const html = renderToStaticMarkup(createElement(mod.default, { form: null }));
      const sections = convert(html, source, ctx);
      const target = join(outDir, `${slug}.json`);
      const page = existsSync(target) ? JSON.parse(readFileSync(target, 'utf8')) : { slug };
      page.sections = sections;
      await writeFile(target, `${JSON.stringify(page, null, 2)}\n`);

      if (check) {
        const cards = (data, key) => (data[key] ?? []).map((slug) => {
          const item = Object.values(SHOWCASE).find((entry) => entry.slug === slug);
          return item ? { slug, path: item.href, client: item.name, title: item.name, summary: item.text, sector: null, services: [], tags: item.tags, cover: { src: item.cover, alt: '' }, cover_ratio: item.ratio ?? null, gallery: [], featured: false } : null;
        }).filter(Boolean);
        const resolved = sections.map((section) => {
          const data = { ...section.data };
          if (section.type === 'site.showcase' || section.type === 'site.case-grid') {
            data.cases = cards(data, 'items');
            data.featured_cases = cards(data, 'featured');
          }
          return { ...section, data };
        });
        const cms = renderToStaticMarkup(createElement(Sections, { sections: resolved, form: null }));
        const changes = diff(outline(html), outline(cms));
        if (changes.length) {
          failures += 1;
          console.log(`\n${slug}:\n  ${changes.join('\n  ')}`);
        }
      }
    }

    console.log(`\n${files.length} páginas convertidas para database/data/pages${check ? `, ${failures} com diferença estrutural` : ''}`);
  } finally {
    await server.close();
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
