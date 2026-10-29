// Gerado por scripts/export-static-pages.mjs a partir de /vps-e-infraestrutura (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { ReactNode } from 'react';
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { VpsCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { placeholderCase } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20um%20VPS%20gerenciado.';

const USES = [
  { title: 'Aplicações e APIs', text: 'Sistemas web e APIs em containers Docker, com deploy padronizado.' },
  { title: 'n8n e automações', text: 'n8n e serviços de automação em servidor próprio, com controle de acesso e logs.' },
  { title: 'Bancos de dados', text: 'Banco dedicado à aplicação, com backup e acesso restrito.' },
  { title: 'Modelos de IA', text: 'Modelos e serviços de IA em ambiente controlado, quando os dados não podem sair dele.' },
];

const STEPS = [
  { title: 'Levantamento', text: 'Recursos, serviços, acessos e dados que a aplicação precisa.' },
  { title: 'Provisionamento', text: 'Servidor criado e configurado, com SSL, firewall e atualizações de segurança.' },
  { title: 'Deploy', text: 'Aplicação publicada com Docker e testada no novo ambiente.' },
  { title: 'Operação', text: 'Backups, monitoramento e suporte de quem conhece a aplicação.' },
];

const DELIVERABLES = [
  'Provisionamento e configuração do servidor',
  'Deploy de aplicações com Docker',
  'SSL, firewall e atualizações de segurança',
  'Backups e monitoramento',
  'Hospedagem de n8n e serviços de automação',
  'Suporte de quem conhece a aplicação',
];

const FAQ: { q: string; a: ReactNode }[] = [
  { q: 'Qual a diferença entre hospedagem e VPS?', a: <>Os planos de hospedagem atendem sites e e-mails. O VPS é um servidor configurado sob medida para a aplicação, quando ela exige mais controle, privacidade ou desempenho. <a href="/hospedagem-de-sites">Hospedagem de sites</a></> },
  { q: 'Vocês hospedam n8n?', a: 'Sim. O n8n e outros serviços de automação rodam no servidor, com controle de acesso e logs.' },
  { q: 'Por que Docker?', a: 'A aplicação roda em containers, o que padroniza o deploy e simplifica atualizar a aplicação ou movê-la para outro servidor.' },
  { q: 'Quem cuida do servidor depois da entrega?', a: 'A Quatro Ventos. Atualizações de segurança, backups e monitoramento seguem com o mesmo time, que conhece a aplicação.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageVpsEInfraestrutura(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <VpsCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">VPS e infraestrutura <em className="mark">gerenciada.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Servidores dedicados, configurados e mantidos por quem desenvolve. Para aplicações, APIs, n8n, bancos de dados e modelos de IA que precisam rodar em ambiente controlado.</p>
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
            <h2 className="h2">O que roda <em className="mark">no servidor.</em></h2>
            <div className="rich intro">
              <p>Aplicações que precisam de ambiente controlado, com acesso, dados e desempenho sob gestão de quem desenvolve.</p>
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
          <div className="prose-panel is-reveal" ref={revealOnView}>
            <h2 className="h2">Infraestrutura <em className="mark">própria.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>A Quatro Ventos mantém infraestrutura própria de hospedagem e servidores. Quando a aplicação exige mais controle, privacidade ou desempenho, o VPS é configurado sob medida.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Avaliar a infraestrutura" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Da aplicação ao servidor em operação.</h2>
            <div className="rich intro">
              <p>O servidor é montado a partir do que a aplicação precisa e segue mantido depois da publicação.</p>
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
            <h2 className="h2">O que o serviço <em className="mark">inclui.</em></h2>
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
      <CaseShowcase
        title="Aplicações em servidor próprio."
        intro="Sistemas, APIs e automações rodando em VPS configurado e mantido pelo time."
        items={[placeholderCase('vps-1', ['VPS', 'Docker']), placeholderCase('vps-2', ['n8n'])]}
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
            <h2 className="h2">Uma aplicação pronta para o <em className="mark">servidor certo.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Conte o que a aplicação precisa rodar e onde ela está hoje. Avaliamos o servidor e o caminho para a migração.</p>
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
