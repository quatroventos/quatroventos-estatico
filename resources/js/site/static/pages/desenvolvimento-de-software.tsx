// Gerado por scripts/export-static-pages.mjs a partir de /desenvolvimento-de-software (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { AgentLog } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20o%20desenvolvimento%20de%20um%20sistema.';

const SERVICES = [
  { title: 'Software sob medida', text: 'Web apps e plataformas desenhados a partir do processo.', href: '/software-sob-medida' },
  { title: 'SaaS', text: 'Produtos digitais com assinatura, multi-tenant e evolução contínua.', href: '/desenvolvimento-de-saas' },
  { title: 'Modernização de sistemas', text: 'Auditoria técnica, refatoração e migração de legado.', href: '/modernizacao-de-sistemas' },
  { title: 'ERP e CRM', text: 'Implantação, customização e integração.', href: '/erp-e-crm' },
  { title: 'UI/UX design', text: 'Discovery, protótipo e design de interface.', href: '/ui-ux-design' },
  { title: 'QA e testes', text: 'Qualidade validada antes de chegar ao usuário.', href: '/qa-e-testes' },
];

const STEPS = [
  { title: 'Arquitetura', text: 'Fluxos, dados e integrações.' },
  { title: 'Prototipagem', text: 'Telas validadas antes do código.' },
  { title: 'Frontend', text: 'Interface rápida e acessível.' },
  { title: 'Backend', text: 'Regras, APIs, integrações e segurança.' },
  { title: 'Homologação', text: 'Testes em ambiente privado.' },
  { title: 'Entrega e evolução', text: 'Publicação, monitoramento e melhorias.' },
];

const STACK = ['PHP', 'Laravel', 'Node.js', 'Python', 'React', 'Next.js', 'Vue', 'MySQL', 'PostgreSQL', 'Redis', 'Docker', 'n8n', 'OpenAI', 'Claude', 'Gemini'];

const FAQ = [
  { q: 'Quando faz sentido um sistema sob medida?', a: 'Quando o processo não cabe em um sistema de prateleira, ou quando adaptar a operação à ferramenta custa mais do que construir a ferramenta para a operação.' },
  { q: 'Vocês trabalham em sistemas que já existem?', a: 'Sim. Modernização, integração e sustentação de sistemas em produção, inclusive desenvolvidos por terceiros. O trabalho começa com uma auditoria técnica do código e da infraestrutura.' },
  { q: 'Todo projeto usa inteligência artificial?', a: 'Não. A IA entra onde melhora a operação, como ler documentos, classificar pedidos ou responder dúvidas. O restante do sistema segue regras definidas no projeto.' },
  { q: 'Quais tecnologias vocês usam?', a: 'PHP com Laravel, Node.js e Python no backend; React, Next.js e Vue no frontend; MySQL, PostgreSQL e Redis para dados; Docker para infraestrutura; n8n para automação; OpenAI, Claude e Gemini para IA. A escolha depende do projeto e do que o cliente já usa.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageDesenvolvimentoDeSoftware(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero">
        <AgentLog />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Software sob medida para processos que precisam <em className="mark">render mais.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Quando o processo não cabe em um sistema de prateleira, construímos o sistema. Web apps, SaaS, integrações e modernização de legado, com inteligência artificial onde ela melhora a operação.</p>
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
            <h2 className="h2">Serviços de <em className="mark">engenharia de software.</em></h2>
            <div className="rich intro">
              <p>Do sistema novo ao legado que precisa andar de novo. Cada frente pode ser contratada sozinha ou dentro de um mesmo projeto.</p>
            </div>
          </header>
          <ul className="cards">
            {SERVICES.map((item, index) => (
              <li key={item.href}>
                <a className="card" href={item.href}>
                  <span className="num">
                    <span className="mono">{String(index + 1).padStart(2, '0')}</span>
                    <span className="go"><Arrow /></span>
                  </span>
                  <div>
                    <h3 className="h3">{item.title}</h3>
                    <div className="rich"><p>{item.text}</p></div>
                  </div>
                </a>
              </li>
            ))}
          </ul>
        </div>
      </section>
      {/* seção: prose */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-light is-reveal" ref={revealOnView}>
            <h2 className="h2">O sistema se adapta <em className="mark">ao processo.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>O ponto de partida é como a operação funciona hoje, não a lista de recursos de uma ferramenta. A partir daí, o mesmo time desenha a arquitetura, programa e publica, com IA nas etapas em que ela reduz trabalho manual.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Conversar sobre um sistema" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Da arquitetura ao sistema em produção.</h2>
            <div className="rich intro">
              <p>As telas são validadas antes do código e o sistema passa por homologação antes de chegar ao usuário. Depois da publicação, segue monitorado e evoluindo.</p>
            </div>
          </header>
          <ol className="steps is-timeline is-scrub">
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
          <header className="sec-head">
            <h2 className="h2">Stack que <em className="mark">usamos.</em></h2>
            <div className="rich intro">
              <p>Tecnologias consolidadas, escolhidas conforme o projeto e o que a empresa já usa.</p>
            </div>
          </header>
          <ul className="chips">
            {STACK.map((item) => <li key={item}>{item}</li>)}
          </ul>
        </div>
      </section>
      {/* seção: faq */}
      <section className="section on-light is-flush">
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
            <h2 className="h2">Que sistema faria a operação <em className="mark">andar mais rápido?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para entender o processo, ver o que já existe e indicar se o caminho é construir, integrar ou modernizar.</p>
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
