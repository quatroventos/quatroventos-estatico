// Gerado por scripts/export-static-pages.mjs a partir de /hospedagem-de-sites (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { ReactNode } from 'react';
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { HostCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { placeholderCase } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20contratar%20uma%20hospedagem%20de%20site.';

const USES = [
  { title: 'E-mails ilimitados', text: 'Quantas contas de e-mail forem necessárias, dentro do espaço de e-mail do plano.' },
  { title: 'SSL', text: 'Certificado para o site abrir em HTTPS, incluído em todos os planos.' },
  { title: 'Anti-spam', text: 'Filtro nas caixas de e-mail para barrar mensagens indesejadas.' },
  { title: 'Suporte de quem desenvolve', text: 'Atendimento do mesmo time que desenvolve e mantém sites.' },
];

const PLANS = [
  { name: 'Basic', price: 'R$ 37,50', text: 'Mensal R$ 45,00. 1 GB para arquivos, 10 GB para e-mails, contas de e-mail ilimitadas, SSL e anti-spam.' },
  { name: 'Standard', price: 'R$ 58,50', text: 'Mensal R$ 70,00. 3 GB para arquivos, 20 GB para e-mails, contas de e-mail ilimitadas, SSL e anti-spam.' },
  { name: 'Business', price: 'R$ 140,50', text: 'Mensal R$ 170,00. 5 GB para arquivos, 30 GB para e-mails, contas de e-mail ilimitadas, SSL e anti-spam.' },
  { name: 'Pro', price: 'R$ 190,50', text: 'Mensal R$ 230,00. 10 GB para arquivos, 50 GB para e-mails, contas de e-mail ilimitadas, SSL e anti-spam.' },
];

const STEPS = [
  { title: 'Plano', text: 'Escolha pelo espaço para arquivos e e-mails que o site e a equipe precisam.' },
  { title: 'Configuração', text: 'Domínio, SSL, anti-spam e contas de e-mail configurados.' },
  { title: 'Publicação', text: 'Site no ar em HTTPS e caixas de e-mail funcionando.' },
  { title: 'Suporte', text: 'Atendimento do mesmo time que desenvolve e mantém sites.' },
];

const RELATED = [
  { title: 'Desenvolvimento de sites', href: '/desenvolvimento-de-sites' },
  { title: 'Software sob medida', href: '/software-sob-medida' },
  { title: 'Manutenção', href: '/manutencao-de-sites' },
  { title: 'VPS para aplicações', href: '/vps-e-infraestrutura' },
];

const FAQ: { q: string; a: ReactNode }[] = [
  { q: 'Qual a diferença entre o preço anual e o mensal?', a: 'O valor em destaque em cada plano é o preço mensal no pagamento anual antecipado. No pagamento mês a mês vale o valor mensal indicado no plano: Basic R$ 45,00, Standard R$ 70,00, Business R$ 170,00 e Pro R$ 230,00.' },
  { q: 'Quantas contas de e-mail posso criar?', a: 'Todos os planos têm contas de e-mail ilimitadas. O que muda entre eles é o espaço para e-mails, de 10 GB a 50 GB.' },
  { q: 'O SSL está incluído?', a: 'Sim. SSL e anti-spam fazem parte de todos os planos.' },
  { q: 'A hospedagem serve para sistemas e aplicações?', a: <>Os planos atendem sites e e-mails. Aplicações, APIs, n8n e bancos de dados rodam melhor em um servidor gerenciado. <a href="/vps-e-infraestrutura">VPS e infraestrutura</a></> },
  { q: 'Vocês fazem a migração do site?', a: <>A troca de hospedagem, com tudo ajustado e funcionando, é um dos atendimentos da manutenção. <a href="/manutencao-de-sites">Manutenção de sites</a></> },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageHospedagemDeSites(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <HostCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Hospedagem <em className="mark">de sites.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Planos a partir de R$ 37,50 por mês, com desconto no pagamento anual antecipado. Infraestrutura própria, com suporte de quem desenvolve.</p>
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
            <h2 className="h2">O que todo plano <em className="mark">inclui.</em></h2>
            <div className="rich intro">
              <p>Os planos mudam no espaço para arquivos e e-mails. O resto vem em todos.</p>
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
      {/* seção: plans */}
      <section className="section on-light is-flush" id="planos">
        <div className="wrap">
          <header className="sec-head is-solo">
            <h2 className="h2">Planos (preço mensal no plano anual)</h2>
          </header>
          <div className="plans">
            {PLANS.map((plan) => (
              <article className="plan" key={plan.name}>
                <h3 className="name">{plan.name}</h3>
                <p className="price">{plan.price}<small>/mês</small></p>
                <div className="rich">
                  <p>{plan.text}</p>
                </div>
                <WhatsAppCta label="Contratar" />
              </article>
            ))}
          </div>
        </div>
      </section>
      {/* seção: prose */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-reveal" ref={revealOnView}>
            <h2 className="h2">Infraestrutura <em className="mark">própria.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Os sites ficam na infraestrutura da Quatro Ventos, e o suporte é feito pelo mesmo time que desenvolve. Quando um problema aparece, quem atende entende tanto do servidor quanto do site.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Escolher o plano" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Do plano ao site no ar.</h2>
            <div className="rich intro">
              <p>Domínio, certificado e e-mails configurados pelo time, sem a empresa precisar mexer em painel técnico.</p>
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
            <h2 className="h2">Serviços <em className="mark">relacionados.</em></h2>
          </header>
          <ul className="link-rows">
            {RELATED.map((item) => (
              <li key={item.href}>
                <a href={item.href}>{item.title}<span className="go"><Arrow /></span></a>
              </li>
            ))}
          </ul>
        </div>
      </section>
      <CaseShowcase
        title="Sites hospedados."
        intro="Sites e e-mails na infraestrutura da Quatro Ventos, com suporte de quem desenvolve."
        items={[placeholderCase('hs-1', ['Hospedagem']), placeholderCase('hs-2', ['E-mails'])]}
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
            <h2 className="h2">Dúvida sobre o <em className="mark">plano ideal?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Conte o tamanho do site e quantas contas de e-mail a empresa usa hoje. Indicamos o plano que cabe.</p>
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
