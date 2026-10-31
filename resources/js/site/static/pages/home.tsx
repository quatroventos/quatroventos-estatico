// Gerado por scripts/export-static-pages.mjs a partir de / (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
import type { StaticPageProps } from '../index';
import { toShowcase } from '../../blocks';
import { CaseFeatures } from '../../case-showcase';
import type { CaseCard } from '../../types';
import { AgentLog, Rotating, trackLens } from '../../hero';
import { Arrow, resetTilt, revealOnView, trackTilt } from '../../ui';
import { SHOWCASE } from '../showcase';

function caseList(value: unknown): CaseCard[] {
  return Array.isArray(value) ? (value as CaseCard[]).filter((item) => item && typeof item.slug === 'string') : [];
}

// Destaque e grade vêm da seção case-grid da home no CMS; rascunhos já chegam filtrados.
function HomeCases({ sections }: { sections: StaticPageProps['sections'] }) {
  const grid = sections?.find((section) => section.type.endsWith('case-grid'));

  if (!grid) {
    return <CaseFeatures items={[SHOWCASE['fiscal-hub']]} />;
  }

  const featured = caseList(grid.data.featured_cases);
  const cases = caseList(grid.data.cases);

  return (
    <>
      {featured.length ? <CaseFeatures items={featured.map(toShowcase)} /> : null}
      {cases.length ? (
        <div className="cases-grid is-3 is-open">
          {cases.map((item) => (
            <article className="case-card is-tilt" key={item.slug} onPointerMove={trackTilt} onPointerLeave={resetTilt}>
              <a className="case-card-cover" href={item.path} tabIndex={-1} aria-hidden="true">
                {item.cover?.src ? <img src={item.cover.src} alt="" loading="lazy" /> : null}
              </a>
              <div className="case-card-body">
                <h3>{item.client}</h3>
                {item.tags?.length ? <p className="case-meta">{item.tags.map((tag) => <span key={tag}>{tag}</span>)}</p> : null}
                <a className="case-go" href={item.path} aria-label={`Ver case ${item.client}`}>Ver case<Arrow /></a>
              </div>
            </article>
          ))}
        </div>
      ) : null}
    </>
  );
}

export default function PageHome({ sections }: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero is-home is-lens" onPointerMove={trackLens}>
        <AgentLog />
        <div className="wrap">
          <h1 className="display">
            <span className="sr-only">Processos melhores com inteligência artificial.</span>
            <span aria-hidden="true">Processos melhores com<Rotating items={["inteligência artificial.","agentes de IA.","automação de processos.","sistemas sob medida."]} /></span>
          </h1>
          <div className="hero-foot">
            <div className="rich lead">
              <p>Ajudamos empresas a melhorar processos com soluções criativas de IA. Agentes, automações e sistemas desenhados para a operação de cada negócio, do diagnóstico à produção.</p>
            </div>
            <div className="actions">
              <a className="pill lime" href="https://wa.me/554130399593?text=Gostaria%20de%20um%20or%C3%A7amento." target="_blank" rel="noreferrer">Iniciar um projeto<span className="arrow"><svg className="icon-arrow" viewBox="0 0 16 16" aria-hidden="true"><path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" /></svg></span></a>
              <a className="pill line" href="/servicos">Conhecer os serviços</a>
            </div>
          </div>
        </div>
      </section>
      {/* seção: list */}
      <section className="section on-light is-mark-draw">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">O que podemos fazer por <em className="mark">sua empresa?</em></h2>
            <div className="rich intro">
              <p>Da arquitetura à infraestrutura, desenvolvemos seu projeto sob demanda: soluções de inteligência artificial, software sob medida, sites e e-commerce, com suporte contínuo para manter tudo funcionando.</p>
            </div>
          </header>
          <ul className="cards is-4 is-feature">
            <li>
              <a className="card" href="/inteligencia-artificial">
                <span className="num">
                  <span className="mono">01</span>
                  <span className="go">
                    <svg className="icon-arrow" viewBox="0 0 16 16" aria-hidden="true">
                      <path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>
                  </span>
                </span>
                <div>
                  <h3 className="h3">Inteligência artificial</h3>
                  <div className="rich">
                    <p>Agentes, automação de processos, IA sobre dados proprietários e integração de LLMs.</p>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <a className="card" href="/desenvolvimento-de-software">
                <span className="num">
                  <span className="mono">02</span>
                  <span className="go">
                    <svg className="icon-arrow" viewBox="0 0 16 16" aria-hidden="true">
                      <path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>
                  </span>
                </span>
                <div>
                  <h3 className="h3">Engenharia de software</h3>
                  <div className="rich">
                    <p>Plataformas sob medida, SaaS, ERP e CRM, modernização de legado.</p>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <a className="card" href="/desenvolvimento-web">
                <span className="num">
                  <span className="mono">03</span>
                  <span className="go">
                    <svg className="icon-arrow" viewBox="0 0 16 16" aria-hidden="true">
                      <path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>
                  </span>
                </span>
                <div>
                  <h3 className="h3">Desenvolvimento web</h3>
                  <div className="rich">
                    <p>Sites, landing pages, e-commerce e WordPress.</p>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <a className="card" href="/suporte-e-infraestrutura">
                <span className="num">
                  <span className="mono">04</span>
                  <span className="go">
                    <svg className="icon-arrow" viewBox="0 0 16 16" aria-hidden="true">
                      <path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>
                  </span>
                </span>
                <div>
                  <h3 className="h3">Suporte e infraestrutura</h3>
                  <div className="rich">
                    <p>Sustentação, manutenção, hospedagem e VPS.</p>
                  </div>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </section>
      {/* seção: prose */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-light is-reveal" ref={revealOnView}>
            <h2 className="h2">Todo processo tem um ponto que <em className="mark">pode render mais.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Começamos pelo seu processo, não pela ferramenta. Identificamos onde a IA economiza tempo ou acelera uma decisão e entregamos a solução ligada aos sistemas que você já usa.</p>
              </div>
              <div className="actions">
                <a className="pill lime" href="https://wa.me/554130399593?text=Gostaria%20de%20avaliar%20um%20processo." target="_blank" rel="noreferrer">Avaliar meu processo<span className="arrow"><svg className="icon-arrow" viewBox="0 0 16 16" aria-hidden="true"><path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" /></svg></span></a>
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">AI-native por método.</h2>
            <div className="rich intro">
              <p>A IA acelera cada etapa do nosso trabalho, da pesquisa aos testes, e entra no produto onde melhora o resultado da operação. Toda decisão passa por custo, segurança, qualidade e manutenção.</p>
            </div>
          </header>
          <ol className="steps is-timeline is-scrub">
            <li>
              <span className="mono">01</span>
              <h3 className="h3">Diagnóstico</h3>
              <div className="rich"><p>Processos, sistemas e oportunidades mapeados.</p></div>
            </li>
            <li>
              <span className="mono">02</span>
              <h3 className="h3">Protótipo</h3>
              <div className="rich"><p>A ideia validada em versão funcional.</p></div>
            </li>
            <li>
              <span className="mono">03</span>
              <h3 className="h3">Desenvolvimento</h3>
              <div className="rich"><p>Frontend, backend, integrações e IA no mesmo time.</p></div>
            </li>
            <li>
              <span className="mono">04</span>
              <h3 className="h3">Homologação</h3>
              <div className="rich"><p>Validação em ambiente privado antes da publicação.</p></div>
            </li>
            <li>
              <span className="mono">05</span>
              <h3 className="h3">Produção</h3>
              <div className="rich"><p>Publicação, monitoramento e métricas.</p></div>
            </li>
            <li>
              <span className="mono">06</span>
              <h3 className="h3">Evolução</h3>
              <div className="rich"><p>Melhoria contínua a partir do uso real.</p></div>
            </li>
          </ol>
        </div>
      </section>
      {/* seção: quotes */}
      <section className="section on-white is-mark-draw">
        <div className="wrap">
          <header className="sec-head is-solo">
            <h2 className="h2">O que dizem <em className="mark">nossos clientes.</em></h2>
          </header>
          <div className="quotes is-even">
            <blockquote>
              <div className="rich">
                <p>Sou cliente satisfeito e recorrente da Quatro Ventos. Recomendo a agência não só pela qualidade e profissionalismo, mas também pelo atendimento realmente comprometido. Dá pra ver que na Quatro Ventos o cliente importa.</p>
              </div>
              <footer className="quote-by">
                <b>Diogo Fontana</b>
                <span>Editora Danúbio</span>
              </footer>
            </blockquote>
            <blockquote>
              <div className="rich">
                <p>Faz anos que contamos com os serviços da Quatro Ventos e sempre fui atendida com profissionalismo, agilidade e qualidade. O suporte é excelente e as soluções sempre atendem exatamente o que preciso. Recomendo muito para quem busca um serviço confiável e de alto nível!</p>
              </div>
              <footer className="quote-by">
                <b>Vivien Uhlmann</b>
                <span>Cycloar</span>
              </footer>
            </blockquote>
            <blockquote>
              <div className="rich">
                <p>Trabalhar com a Quatro Ventos tem sido uma experiência excelente. A equipe é extremamente profissional, ágil e sempre disponível para oferecer suporte quando necessário. O site da Aliança Francesa de Curitiba é um reflexo desse trabalho cuidadoso e bem-executado.</p>
              </div>
              <footer className="quote-by">
                <b>Mayara Barros</b>
                <span>Aliança Francesa de Curitiba</span>
              </footer>
            </blockquote>
          </div>
        </div>
      </section>
      {/* seção: cases */}
      <section className="section on-dark">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Projetos que entregamos.</h2>
            <div className="rich intro">
              <p>Sites institucionais, webapps e plataformas para finanças, educação, turismo e tecnologia, do planejamento à publicação.</p>
            </div>
          </header>
          <HomeCases sections={sections} />
        </div>
      </section>
      {/* seção: clientes */}
      <section className="section on-white is-mark-draw">
        <div className="wrap clients">
          <header className="clients-head">
            <h2 className="h2">Empresas que <em className="mark">crescem com a gente.</em></h2>
            <div className="rich intro">
              <p>Do mercado financeiro à saúde e ao varejo, atendemos empresas de portes e setores diferentes com o mesmo cuidado: projeto bem planejado, entrega no prazo e suporte depois da publicação.</p>
            </div>
          </header>
          <ul className="logos is-mono">
              <li>
                <img src="/assets/clientes/lampa/incavel.svg" alt="Incavel" loading="lazy" />
              </li>
              <li>
                <img src="/assets/clientes/lampa/4kst.svg" alt="4KST" loading="lazy" />
              </li>
              <li>
                <img src="/assets/clientes/lampa/af.svg" alt="Aliança Francesa" loading="lazy" />
              </li>
              <li>
                <img src="/assets/clientes/lampa/agmov.svg" alt="AGMov" loading="lazy" />
              </li>
              <li>
                <img src="/assets/clientes/lampa/bisuti.png" alt="Bisuti" loading="lazy" />
              </li>
              <li>
                <img src="/assets/clientes/lampa/conela.svg" alt="Conela" loading="lazy" />
              </li>
              <li>
                <img src="/assets/clientes/lampa/costi.png" alt="Costi" loading="lazy" />
              </li>
              <li>
                <img src="/assets/clientes/lampa/momentum.svg" alt="Momentum" loading="lazy" />
              </li>
              <li>
                <img src="/assets/clientes/lampa/produtive.svg" alt="Produtive" loading="lazy" />
              </li>
          </ul>
        </div>
      </section>
      {/* seção: cta */}
      <section className="section on-white is-flush">
        <div className="wrap">
          <div className="prose-panel is-reveal" ref={revealOnView}>
            <h2 className="h2">Qual processo poderia <em className="mark">render mais?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para avaliar onde a IA entra, como e por onde começar.</p>
              </div>
              <div className="actions">
                <a className="pill lime" href="https://wa.me/554130399593?text=Gostaria%20de%20um%20or%C3%A7amento." target="_blank" rel="noreferrer">Falar com um especialista<span className="arrow"><svg className="icon-arrow" viewBox="0 0 16 16" aria-hidden="true"><path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" /></svg></span></a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
