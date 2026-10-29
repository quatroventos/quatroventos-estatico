// Gerado por scripts/export-static-pages.mjs a partir de /suporte-e-infraestrutura (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { ReactNode } from 'react';
import type { StaticPageProps } from '../index';
import { AgentLog } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20suporte%20e%20infraestrutura.';

const SERVICES = [
  { title: 'Sustentação de software', text: 'Suporte contínuo a sistemas em produção.', href: '/sustentacao-de-software' },
  { title: 'Manutenção de sites', text: 'Planos a partir de R$ 350/mês.', href: '/manutencao-de-sites' },
  { title: 'Recuperação de WordPress', text: 'Site invadido ou fora do ar.', href: '/recuperacao-de-site-wordpress' },
  { title: 'Hospedagem', text: 'A partir de R$ 37,50/mês no anual.', href: '/hospedagem-de-sites' },
  { title: 'VPS e infraestrutura', text: 'Servidores gerenciados para sistemas, automações e aplicações.', href: '/vps-e-infraestrutura' },
];

const USES = [
  { title: 'Atualização', text: 'Sistema, dependências, plugins e servidor nas versões corrigidas.' },
  { title: 'Backup', text: 'Arquivos e banco de dados copiados periodicamente para fora do servidor.' },
  { title: 'Monitoramento', text: 'Disponibilidade e funcionamento verificados, com aviso quando algo para.' },
  { title: 'Resposta rápida', text: 'Chamado atendido por quem conhece o código e a infraestrutura.' },
];

const FAQ: { q: string; a: ReactNode }[] = [
  { q: 'Já sou cliente. Como abro um chamado?', a: <>Pelo portal de suporte. <a href="https://quatroventos.freshdesk.com/support/home" target="_blank" rel="noreferrer">Clientes: abrir chamado</a></> },
  { q: 'Qual a diferença entre sustentação de software e manutenção de sites?', a: <>A sustentação é o suporte contínuo a sistemas em produção. A manutenção cuida de sites e WordPress, com planos a partir de R$ 350/mês. <a href="/manutencao-de-sites">Manutenção de sites</a></> },
  { q: 'Hospedagem ou VPS?', a: <>A hospedagem atende sites e e-mails, a partir de R$ 37,50/mês no plano anual. O VPS é um servidor gerenciado para sistemas, automações e aplicações que precisam de mais controle. <a href="/vps-e-infraestrutura">VPS e infraestrutura</a></> },
  { q: 'Meu site WordPress foi invadido. O que fazer?', a: <>Quanto antes a recuperação começa, menor o dano. A recuperação remove o malware, corrige o que estava vulnerável e devolve o site ao ar. <a href="/recuperacao-de-site-wordpress">Recuperação de site WordPress</a></> },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageSuporteEInfraestrutura(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero">
        <AgentLog />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Operação estável, <em className="mark">todos os dias.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Sistemas, automações e sites precisam de atualização, backup, monitoramento e resposta rápida. Sustentação, manutenção e infraestrutura com o mesmo time que desenvolve.</p>
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
            <h2 className="h2">Serviços para manter <em className="mark">tudo no ar.</em></h2>
            <div className="rich intro">
              <p>Do sistema em produção ao servidor onde ele roda. Cada serviço cobre uma parte da operação, e todos ficam com o mesmo time.</p>
            </div>
          </header>
          <ul className="cards is-5">
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
      {/* seção: usos */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">O que mantém a operação <em className="mark">estável.</em></h2>
            <div className="rich intro">
              <p>Sistemas, automações e sites em produção dependem das mesmas quatro rotinas. Os serviços acima cobrem cada uma delas.</p>
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
            <h2 className="h2">O mesmo time que <em className="mark">desenvolve, sustenta.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Quem construiu o sistema conhece o código, as integrações e o servidor. Correções e atualizações partem desse conhecimento, sem repassar o contexto para outra equipe.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Falar com um especialista" />
              </div>
            </div>
          </div>
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
            <h2 className="h2">Sustentação para sistemas e sites <em className="mark">em produção.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Conte o que está em produção hoje e como é mantido. Indicamos o serviço que cobre cada parte.</p>
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
