// Gerado por scripts/export-static-pages.mjs a partir de /sustentacao-de-software (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { SustainCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { placeholderCase } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20sustenta%C3%A7%C3%A3o%20de%20software.';

const USES = [
  { title: 'Monitoramento', text: 'Disponibilidade e erros acompanhados, com aviso antes de o usuário perceber.' },
  { title: 'Correção', text: 'Bugs e incidentes resolvidos por quem conhece o código e as integrações.' },
  { title: 'Atualização', text: 'Dependências, frameworks e servidor nas versões corrigidas e seguras.' },
  { title: 'Evolução', text: 'Pequenas melhorias a partir do uso real do sistema.' },
];

const EXAMPLES = [
  { title: 'ERP e sistemas internos', text: 'Cadastros, estoque, financeiro e relatórios que a operação usa todo dia.' },
  { title: 'Portal do cliente', text: 'Área logada com pedidos, boletos, documentos e chamados.' },
  { title: 'APIs e integrações', text: 'Conexões com ERP, CRM, pagamentos e emissão fiscal que não podem parar.' },
  { title: 'SaaS em produção', text: 'Produto com clientes ativos, planos, cobrança e atualizações frequentes.' },
  { title: 'Automações e n8n', text: 'Fluxos que movem dados entre sistemas e precisam de monitoramento.' },
  { title: 'Aplicativos', text: 'Apps e seus backends, com atualização de bibliotecas e lojas.' },
  { title: 'Sistemas com IA', text: 'Agentes e integrações de LLM com custo, qualidade e logs acompanhados.' },
  { title: 'Sistemas legados', text: 'Código antigo mantido estável enquanto a modernização é planejada.' },
];

const SCOPE = [
  'Monitoramento de disponibilidade e erros',
  'Correção de bugs e incidentes',
  'Atualização de dependências, frameworks e segurança',
  'Backups e plano de recuperação',
  'Pequenas melhorias e evolução contínua',
  'Documentação e passagem de conhecimento',
];

const STEPS = [
  { title: 'Auditoria', text: 'Código, infraestrutura e riscos avaliados antes de assumir o sistema.' },
  { title: 'Plano de prioridades', text: 'O que corrigir, atualizar e proteger primeiro.' },
  { title: 'Sustentação', text: 'Monitoramento, correções e atualizações em rotina.' },
  { title: 'Evolução', text: 'Pequenas melhorias a partir do uso do sistema.' },
];

const FAQ = [
  { q: 'Vocês assumem sistemas desenvolvidos por terceiros?', a: 'Sim. O trabalho começa com uma auditoria técnica de código, infraestrutura e riscos. Em seguida, o suporte é assumido com um plano claro de prioridades.' },
  { q: 'O que entra na sustentação?', a: 'Monitoramento de disponibilidade e erros, correção de bugs e incidentes, atualização de dependências, frameworks e segurança, backups e plano de recuperação, pequenas melhorias e documentação.' },
  { q: 'A sustentação inclui melhorias no sistema?', a: 'Sim, pequenas melhorias e evolução contínua fazem parte do escopo. Projetos maiores são avaliados à parte.' },
  { q: 'O conhecimento sobre o sistema fica com a empresa?', a: 'Sim. Documentação e passagem de conhecimento fazem parte do escopo, para que o sistema não dependa de uma única pessoa.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageSustentacaoDeSoftware(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <SustainCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Sustentação <em className="mark">de software.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Sistemas em produção monitorados, atualizados e acompanhados por um time que conhece o código. Correção, atualização e evolução, inclusive de sistemas desenvolvidos por terceiros.</p>
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
            <h2 className="h2">Sistema no ar, <em className="mark">todos os dias.</em></h2>
            <div className="rich intro">
              <p>O sistema em produção acompanhado de perto: no ar, atualizado, com cópia de segurança e com o conhecimento registrado.</p>
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
      {/* seção: exemplos */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">O que dá para <em className="mark">sustentar.</em></h2>
            <div className="rich intro">
              <p>Qualquer sistema que a operação usa todos os dias e não pode parar. Alguns exemplos.</p>
            </div>
          </header>
          <ul className="ex-grid">
            {EXAMPLES.map((item) => (
              <li key={item.title}>
                <h3 className="h3">{item.title}</h3>
                <p>{item.text}</p>
              </li>
            ))}
            <li className="is-more">
              <h3 className="h3">Sistema feito por outro time</h3>
              <p>A auditoria mostra o estado do código e da infraestrutura antes de assumir a sustentação.</p>
              <a className="ex-more-link" href="#contato">
                Conte qual é o seu
                <span className="arrow"><Arrow /></span>
              </a>
            </li>
          </ul>
        </div>
      </section>
      {/* seção: prose */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-reveal" ref={revealOnView}>
            <h2 className="h2">Sistemas <em className="mark">de terceiros.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>O trabalho começa com uma auditoria técnica de código, infraestrutura e riscos. Em seguida, o suporte é assumido com um plano claro de prioridades.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Avaliar um sistema" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Da auditoria à sustentação em rotina.</h2>
            <div className="rich intro">
              <p>Antes de assumir o sistema, o time entende o código e os riscos. Depois, a sustentação segue um plano de prioridades.</p>
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
            <h2 className="h2">O que a sustentação <em className="mark">cobre.</em></h2>
          </header>
          <ul className="checklist">
            {SCOPE.map((item) => (
              <li key={item}>
                <span className="tick"><svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3.5 8.5l3 3 6-7" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" /></svg></span>
                <span>{item}</span>
              </li>
            ))}
          </ul>
        </div>
      </section>
      <CaseShowcase
        title="Sistemas em sustentação."
        intro="Sistemas em produção acompanhados pelo time, com correções, atualizações e evolução em rotina."
        items={[placeholderCase('sus-1', ['Sustentação']), placeholderCase('sus-2', ['Sistema de terceiros'])]}
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
            <h2 className="h2">Sistema em produção <em className="mark">sem sustentação?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para entender o sistema, quem cuida dele hoje e o que a auditoria precisa cobrir.</p>
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
