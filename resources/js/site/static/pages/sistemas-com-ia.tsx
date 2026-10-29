// Gerado por scripts/export-static-pages.mjs a partir de /sistemas-com-ia (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { SystemCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { SHOWCASE } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20um%20sistema%20com%20IA.';

const USES = [
  { title: 'Plataformas internas', text: 'Sistemas de operação em que a IA lê, classifica e decide junto com o time.' },
  { title: 'Produtos digitais', text: 'Produtos em que a IA é a funcionalidade central, não um complemento.' },
  { title: 'Portais de atendimento', text: 'O cliente resolve a demanda conversando com um assistente ligado aos dados.' },
  { title: 'Ferramentas de análise', text: 'Dados e documentos cruzados, com a resposta entregue em linguagem natural.' },
];

const STEPS = [
  { title: 'Discovery', text: 'Problema, usuários, dados disponíveis e riscos.' },
  { title: 'Protótipo', text: 'Telas e fluxo de IA validados com usuários.' },
  { title: 'MVP', text: 'Primeira versão em produção.' },
  { title: 'Escala', text: 'Performance, custo, monitoramento e novas funcionalidades.' },
];

const DELIVERABLES = [
  'Discovery com problema, usuários e riscos mapeados',
  'Protótipo com telas e fluxo de IA',
  'Arquitetura de dados, modelos e integrações',
  'MVP publicado em produção',
  'Monitoramento de qualidade, custo e uso',
  'Evolução com novas funcionalidades',
];

const FAQ = [
  { q: 'Qual a diferença para integrar IA num sistema existente?', a: 'Na integração, a IA entra numa etapa de um sistema que já funciona. Num sistema AI-native, telas, dados e regras são desenhados a partir do que a IA faz bem e do que precisa de uma pessoa.' },
  { q: 'É preciso ter a ideia completa para começar?', a: 'Não. O discovery parte do problema e dos usuários e define o que entra no MVP.' },
  { q: 'Qual modelo de IA é usado?', a: 'O que oferecer o melhor equilíbrio entre custo e qualidade para o caso: OpenAI, Claude, Gemini ou modelos abertos.' },
  { q: 'Quem mantém o sistema depois?', a: 'A Quatro Ventos. Sustentação e evolução fazem parte da oferta.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageSistemasComIa(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <SystemCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Sistemas que já nascem <em className="mark">com IA.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Quando a IA é o centro do processo, a arquitetura muda. Desenvolvemos sistemas e produtos digitais em que a inteligência artificial faz parte do fluxo principal, do discovery à produção.</p>
              </div>
            </div>
          </div>
          <LeadForm className="hero-form" id="contato" whatsappHref={WHATSAPP} />
        </div>
      </section>
      {/* seção: list */}
      <section className="section on-light is-mark-draw">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">O que <em className="mark">desenvolvemos.</em></h2>
            <div className="rich intro">
              <p>Sistemas em que a IA executa parte do trabalho principal e uma pessoa entra onde o julgamento é necessário.</p>
            </div>
          </header>
          <ul className="uses-band">
            {USES.map((item) => (
              <li key={item.title}>
                <h3 className="h3">{item.title}</h3>
                <p>{item.text}</p>
              </li>
            ))}
          </ul>
        </div>
      </section>
      {/* seção: prose */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-light is-reveal" ref={revealOnView}>
            <h2 className="h2">Produto <em className="mark">AI-native.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Em vez de acoplar um chat a um sistema antigo, telas, dados e regras são desenhados a partir do que a IA faz bem e do que precisa de uma pessoa. O resultado é um processo mais simples de executar e um produto mais fácil de evoluir.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Conversar sobre o produto" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Do discovery à escala.</h2>
            <div className="rich intro">
              <p>O fluxo de IA é validado com usuários antes de virar produto. A primeira versão vai para produção e evolui a partir do uso real.</p>
            </div>
          </header>
          <ol className="steps is-timeline is-4 is-scrub">
            {STEPS.map((step, index) => (
              <li key={step.title}>
                <span className="mono">{String(index + 1).padStart(2, '0')}</span>
                <h3 className="h3">{step.title}</h3>
                <div className="rich"><p>{step.text}</p></div>
              </li>
            ))}
          </ol>
        </div>
      </section>
      {/* seção: list */}
      <section className="section on-light is-mark-draw">
        <div className="wrap">
          <header className="sec-head is-solo">
            <h2 className="h2">O que o projeto <em className="mark">entrega.</em></h2>
          </header>
          <ul className="checklist">
            {DELIVERABLES.map((item) => (
              <li key={item}>
                <span className="tick"><svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3.5 8.5l3 3 6-7" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" /></svg></span>
                <span>{item}</span>
              </li>
            ))}
          </ul>
        </div>
      </section>
      {/* seção: cases */}
      <CaseShowcase
        title="Sistema com IA em produção."
        intro="Um produto em que a inteligência artificial é o fluxo principal, do pedido do cliente à página publicada."
        items={[SHOWCASE['firefly-webmaker']]}
      />
      {/* seção: faq */}
      <section className="section on-light">
        <div className="wrap">
          <div className="faq-grid">
            <h2 className="h2">Perguntas frequentes</h2>
            <div className="faq">
              {FAQ.map((item) => (
                <details key={item.q}>
                  <summary>{item.q}<span className="plus" aria-hidden="true" /></summary>
                  <div className="rich"><p>{item.a}</p></div>
                </details>
              ))}
            </div>
          </div>
        </div>
      </section>
      {/* seção: cta */}
      <section className="section on-light is-flush">
        <div className="wrap">
          <div className="prose-panel is-reveal" ref={revealOnView}>
            <h2 className="h2">Uma ideia de produto com <em className="mark">IA no centro?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para entender o problema, os usuários e os dados disponíveis, e definir o que entra no primeiro protótipo.</p>
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
