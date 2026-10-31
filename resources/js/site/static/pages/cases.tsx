// Gerado por scripts/export-static-pages.mjs a partir de /cases (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /sobre (hero sem formulário, logos e CTA iguais).
// Lista única de thumbs, com busca por texto (sem acento e sem caixa) e filtro por categoria.
import type { StaticPageProps } from '../index';
import type { CaseCard } from '../../types';
import { AgentLog, Rotating, trackLens } from '../../hero';
import { CasesBrowser, type BrowserCase, type BrowserCategory } from '../../cases-browser';
import { Arrow, revealOnView } from '../../ui';
import { SHOWCASE } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Vi%20os%20cases%20e%20gostaria%20de%20conversar%20sobre%20um%20projeto.';

type Category = 'ia' | 'software' | 'sites';

type Thumb = BrowserCase;

const CATEGORIES: BrowserCategory[] = [
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

function cmsThumbs(sections: StaticPageProps['sections']): Thumb[] | null {
  const browser = sections?.find((section) => section.type.endsWith('cases-browser'));
  const cases = browser?.data.cases;
  if (!Array.isArray(cases)) {
    return null;
  }

  return (cases as CaseCard[]).map((item) => ({
    name: item.client,
    cover: item.cover?.src ?? '',
    href: item.path,
    meta: item.tags ?? [],
    category: item.category ?? '',
  }));
}

export default function PageCases({ sections }: StaticPageProps) {
  const thumbs = cmsThumbs(sections) ?? THUMBS;

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
            items={thumbs}
            categories={CATEGORIES}
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
