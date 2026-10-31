// Gerado por scripts/export-static-pages.mjs a partir de /sobre (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import type { CSSProperties, ReactNode } from 'react';
import { HistoryTimeline, MethodOrbit } from '../../about';
import { GLYPHS } from '../../glyphs';
import { AgentLog, Rotating, trackLens } from '../../hero';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conhecer%20melhor%20a%20Quatro%20Ventos.';

const PRINCIPLES = [
  { title: 'Processo antes de ferramenta', text: 'A tecnologia é escolhida depois de entender o problema.' },
  { title: 'Soluções criativas', text: 'Nenhum projeto parte de template.' },
  { title: 'Um único time', text: 'Design, frontend, backend, infraestrutura e IA no mesmo projeto.' },
  { title: 'Homologação antes da publicação', text: 'Tudo validado em ambiente privado.' },
  { title: 'Continuidade', text: 'Suporte, manutenção e evolução após a entrega.' },
  { title: 'Garantia de 30 dias', text: 'Para ajustes e correções em sites.' },
];

const PRINCIPLE_GLYPHS: ReactNode[] = ['search', 'target', 'team', 'shield', 'loop', 'calendar'].map((id) => GLYPHS[id]);

// Textos de 2010 em diante ainda a definir.
const HISTORY = [
  { year: '2005', title: 'Primeiro escritório', text: 'No Portão, em Curitiba.' },
  { year: '2008', title: 'Quatro Ventos Express', text: 'Serviços rápidos.' },
  { year: '2010', title: 'Monalisa CMS', text: 'Texto a definir.' },
  { year: '2014', title: 'Home office', text: 'Texto a definir.' },
  { year: '2026', title: 'Hoje', text: 'Texto a definir.' },
];

const STEPS = [
  { title: 'Desenhar', text: 'Diagnóstico de processos e sistemas e protótipo validado em versão funcional.' },
  { title: 'Codar', text: 'Frontend, backend, integrações e IA no mesmo time.' },
  { title: 'Testar', text: 'Homologação em ambiente privado antes da publicação.' },
  { title: 'Evoluir', text: 'Publicação, monitoramento e melhoria contínua a partir do uso real.' },
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

export default function PageSobre(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero is-home is-lens" onPointerMove={trackLens}>
        <AgentLog />
        <div className="wrap">
          <div>
            <h1 className="display">
              <span className="sr-only">Design, Code, Test, Repeat.</span>
              <span aria-hidden="true">
                Design, Code, Test,
                <Rotating items={['Repeat.']} />
              </span>
            </h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>O lema existe desde 2005. Vem do algoritmo do shampoo, a piada de TI sobre a instrução "lave, enxágue, repita", que nunca termina. Para a Quatro Ventos, o loop é a disciplina de melhorar processos continuamente: entender, construir, medir e repetir.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: prose */}
      <section className="section on-light is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-reveal" ref={revealOnView}>
            <h2 className="h2">Da web 1.0 à <em className="mark">era da I.A.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>A Quatro Ventos nasceu em Curitiba desenvolvendo sites. Vieram sistemas, integrações, infraestrutura e sustentação de longo prazo, sempre com o mesmo objetivo: fazer a operação dos clientes funcionar melhor. Hoje esse objetivo passa pela inteligência artificial, aplicada com criatividade e critério de engenharia.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: história */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <header className="sec-head is-solo">
            <h2 className="h2">Nossa <em className="mark">história.</em></h2>
          </header>
          <HistoryTimeline items={HISTORY} />
        </div>
      </section>
      {/* seção: princípios */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2"><em className="mark">Princípios</em></h2>
            <div className="rich intro">
              <p>O que orienta cada projeto, do primeiro diagnóstico à evolução depois da entrega.</p>
            </div>
          </header>
          <ul className="ex-grid is-glyphs" ref={revealOnView}>
            {PRINCIPLES.map((item, i) => (
              <li key={item.title} style={{ '--i': i } as CSSProperties}>
                <svg className="pr-glyph" viewBox="0 0 40 40" aria-hidden="true">{PRINCIPLE_GLYPHS[i]}</svg>
                <h3 className="h3">{item.title}</h3>
                <p>{item.text}</p>
              </li>
            ))}
          </ul>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Método</h2>
            <div className="rich intro">
              <p>Design, Code, Test, Repeat. O lema aplicado a cada projeto, em ciclos: o que se aprende em produção volta para o desenho.</p>
            </div>
          </header>
          <MethodOrbit steps={STEPS} center={'Design, Code,\nTest, Repeat.'} />
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
