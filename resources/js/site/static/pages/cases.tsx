// Gerado por scripts/export-static-pages.mjs a partir de /cases (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /sobre (hero sem formulário, logos e CTA iguais).
// Lista única de thumbs, com busca por texto (sem acento e sem caixa) e filtro por categoria.
import { useId, useMemo, useState, type ReactNode } from 'react';
import { flushSync } from 'react-dom';
import type { StaticPageProps } from '../index';
import { AgentLog, Rotating, trackLens } from '../../hero';
import { Arrow, resetTilt, revealOnView, trackTilt } from '../../ui';
import { SHOWCASE } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Vi%20os%20cases%20e%20gostaria%20de%20conversar%20sobre%20um%20projeto.';

type Category = 'ia' | 'software' | 'sites';

type Thumb = { name: string; cover: string; href: string; meta: string[]; category: Category };

const CATEGORIES: { id: Category; label: string }[] = [
  { id: 'ia', label: 'Inteligência artificial' },
  { id: 'software', label: 'Software e webapps' },
  { id: 'sites', label: 'Sites' },
];

const fromShowcase = (item: (typeof SHOWCASE)[keyof typeof SHOWCASE], category: Category): Thumb => ({
  name: item.name,
  cover: item.cover,
  href: `/cases/${item.slug}`,
  meta: item.tags,
  category,
});

const THUMBS: Thumb[] = [
  fromShowcase(SHOWCASE['fiscal-hub'], 'software'),
  fromShowcase(SHOWCASE['firefly-webmaker'], 'ia'),
  fromShowcase(SHOWCASE.bia, 'ia'),
  fromShowcase(SHOWCASE.seucommerce, 'software'),
  fromShowcase(SHOWCASE.moltec, 'software'),
  fromShowcase(SHOWCASE.cyclolux, 'software'),
  fromShowcase(SHOWCASE['ramos-benedetti'], 'sites'),
  fromShowcase(SHOWCASE.produtive, 'sites'),
  { name: 'Assessment Produtive', cover: '/assets/cases/assessment.jpg', href: '/cases/assessment-produtive', meta: ['Webapp', '2025'], category: 'software' },
  { name: 'Aliança Francesa Brasília', cover: '/assets/cases/afbrasilia.jpg', href: '/cases/alianca-francesa-brasilia', meta: ['Criação de site', '2025'], category: 'sites' },
  { name: 'Conexme', cover: '/assets/cases/conexme.jpg', href: '/cases/conexme', meta: ['Webapp', '2024'], category: 'software' },
  { name: 'Tripfy', cover: '/assets/cases/tripfy.jpg', href: '/cases/tripfy', meta: ['Site e webapp', '2023'], category: 'software' },
  { name: 'Aliança Francesa Curitiba', cover: '/assets/cases/afcuritiba.jpg', href: '/cases/alianca-francesa-curitiba', meta: ['Criação de site', '2022'], category: 'sites' },
  { name: 'Multiversidade PUCPR', cover: '/assets/cases/puc.jpg', href: '/cases/multiversidade-pucpr', meta: ['Criação de site', '2021'], category: 'sites' },
];

const LOGOS = [
  { src: '/assets/clientes/lampa/incavel.svg', alt: 'Incavel' },
  { src: '/assets/clientes/lampa/4kst.svg', alt: '4KST' },
  { src: '/assets/clientes/lampa/af.svg', alt: 'Aliança Francesa' },
  { src: '/assets/clientes/lampa/agmov.svg', alt: 'AGMov' },
  { src: '/assets/clientes/lampa/bisuti.png', alt: 'Bisuti' },
  { src: '/assets/clientes/lampa/conela.svg', alt: 'Conela' },
  { src: '/assets/clientes/lampa/costi.png', alt: 'Costi' },
  { src: '/assets/clientes/lampa/momentum.svg', alt: 'Momentum' },
  { src: '/assets/clientes/lampa/produtive.svg', alt: 'Produtive' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

const normalize = (value: string) => value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();

const categoryLabel = (id: Category) => CATEGORIES.find((item) => item.id === id)?.label ?? '';

const transitionName = (href: string) => `cv-${href.replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '')}`;

function CasesBrowser({ head }: { head: ReactNode }) {
  const searchId = useId();
  const [query, setQueryState] = useState('');
  const [category, setCategoryState] = useState<Category | ''>('');
  const term = normalize(query);

  const morph = (update: () => void) => {
    const doc = document as Document & { startViewTransition?: (callback: () => void) => unknown };
    if (!doc.startViewTransition || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      update();
      return;
    }
    doc.startViewTransition(() => flushSync(update));
  };
  const setQuery = (value: string) => morph(() => setQueryState(value));
  const setCategory = (value: Category | '') => morph(() => setCategoryState(value));

  const matching = useMemo(
    () => THUMBS.filter((item) => !term || normalize([item.name, ...item.meta, categoryLabel(item.category)].join(' ')).includes(term)),
    [term],
  );
  const visible = category ? matching.filter((item) => item.category === category) : matching;
  const countOf = (id: Category | '') => (id ? matching.filter((item) => item.category === id).length : matching.length);

  const clear = () => {
    setQuery('');
    setCategory('');
  };

  return (
    <>
      <div className="cases-band">
        {head}
        <div className="cases-band-tools">
          <div className="cases-filter" role="group" aria-label="Categoria">
            {[{ id: '' as const, label: 'Todos' }, ...CATEGORIES].map((item) => (
              <button key={item.id || 'all'} type="button" aria-pressed={category === item.id} onClick={() => setCategory(item.id)}>
                {item.label}
                <span className="cases-filter-n">{countOf(item.id)}</span>
              </button>
            ))}
          </div>
          <div className="cases-search">
            <label className="sr-only" htmlFor={searchId}>Buscar projeto</label>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10.5 17a6.5 6.5 0 1 0 0-13 6.5 6.5 0 0 0 0 13zM15.5 15.5L20 20" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" /></svg>
            <input id={searchId} type="search" value={query} onChange={(event) => setQuery(event.target.value)} placeholder="Buscar por nome, setor ou tipo" autoComplete="off" />
          </div>
        </div>
      </div>
      <p className="sr-only" aria-live="polite">{visible.length === 1 ? '1 projeto' : `${visible.length} projetos`}</p>
      {visible.length ? (
        <div className="cases-grid is-3 is-open is-cover-16x10">
          {visible.map((item) => <CaseThumb key={item.href} item={item} />)}
        </div>
      ) : (
        <div className="cases-empty">
          <p>Nenhum projeto encontrado{query ? ` para "${query.trim()}"` : ''}.</p>
          <button type="button" className="pill line" onClick={clear}>Limpar a busca</button>
        </div>
      )}
    </>
  );
}

function CaseThumb({ item }: { item: Thumb }) {
  return (
    <article className="case-card is-tilt" style={{ viewTransitionName: transitionName(item.href) }} onPointerMove={trackTilt} onPointerLeave={resetTilt}>
      <a className="case-card-cover" href={item.href} tabIndex={-1} aria-hidden="true">
        <img src={item.cover} alt="" loading="lazy" />
      </a>
      <div className="case-card-body">
        <h3>{item.name}</h3>
        <p className="case-meta">{item.meta.map((tag) => <span key={tag}>{tag}</span>)}</p>
        <a className="case-go" href={item.href} aria-label={`Ver case: ${item.name}`}>Ver case<Arrow /></a>
      </div>
    </article>
  );
}

export default function PageCases(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero is-home is-lens" onPointerMove={trackLens}>
        <AgentLog />
        <div className="wrap">
          <h1 className="display">
            <span className="sr-only">Projetos de inteligência artificial.</span>
            <span aria-hidden="true">Projetos de<Rotating items={['inteligência artificial.', 'software sob medida.', 'sites e webapps.']} /></span>
          </h1>
          <div className="hero-foot">
            <div className="rich lead">
              <p>Processos melhorados com IA, software e web em finanças, educação, indústria, varejo e serviços.</p>
            </div>
            <div className="actions">
              <WhatsAppCta label="Iniciar um projeto" />
              <a className="pill line" href="#projetos">Ver os projetos</a>
            </div>
          </div>
        </div>
      </section>
      {/* seção: cases */}
      <section className="section on-light is-mark-draw" id="projetos">
        <div className="wrap">
          <CasesBrowser
            head={
              <header className="sec-head">
                <h2 className="h2">Projetos que <em className="mark">entregamos.</em></h2>
                <div className="rich intro">
                  <p>Agentes de IA, plataformas SaaS, calculadoras industriais, sites e webapps, do planejamento à publicação.</p>
                </div>
              </header>
            }
          />
        </div>
      </section>
      {/* seção: logos */}
      <section className="section on-white is-mark-draw">
        <div className="wrap clients">
          <header className="clients-head">
            <h2 className="h2">Empresas que <em className="mark">crescem com a gente.</em></h2>
            <div className="rich intro">
              <p>Do mercado financeiro à saúde e ao varejo, atendemos empresas de portes e setores diferentes com o mesmo cuidado: projeto bem planejado, entrega no prazo e suporte depois da publicação.</p>
            </div>
          </header>
          <ul className="logos is-mono">
            {LOGOS.map((item) => (
              <li key={item.src}>
                <img src={item.src} alt={item.alt} loading="lazy" />
              </li>
            ))}
          </ul>
        </div>
      </section>
      {/* seção: cta */}
      <section className="section on-white is-flush">
        <div className="wrap">
          <div className="prose-panel is-reveal" ref={revealOnView}>
            <h2 className="h2">O próximo projeto começa <em className="mark">com uma conversa.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Conte qual processo precisa melhorar. Avaliamos o caso e indicamos por onde começar.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Falar com um especialista" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
