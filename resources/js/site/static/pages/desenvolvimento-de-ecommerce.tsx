// Gerado por scripts/export-static-pages.mjs a partir de /desenvolvimento-de-ecommerce (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { EcomCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { placeholderCase } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20uma%20loja%20virtual.';

const USES = [
  { title: 'Shopify', text: 'Loja hospedada, rápida de colocar no ar, com apps para pagamento, frete e marketing.' },
  { title: 'WooCommerce', text: 'Loja dentro do WordPress, com catálogo e conteúdo no mesmo painel.' },
  { title: 'Tray', text: 'Plataforma brasileira com pagamento, frete, emissão fiscal e marketplaces integrados de fábrica.' },
  { title: 'Sob medida', text: 'Quando a operação não cabe numa plataforma pronta e exige regras e integrações próprias.' },
];

const STEPS = [
  { title: 'Arquitetura', text: 'Comportamento do público estudado e jornada de compra mapeada.' },
  { title: 'Prototipagem', text: 'Protótipos interativos para validar a loja antes da programação.' },
  { title: 'Frontend', text: 'Interface convertida em código limpo e otimizado.' },
  { title: 'Backend', text: 'Pagamento, frete, estoque e integrações construídos em paralelo, com performance e segurança.' },
  { title: 'Homologação', text: 'Pedidos de teste em ambiente privado e rodada de ajustes.' },
  { title: 'Entrega', text: 'Loja publicada no endereço definitivo, com acompanhamento.' },
];

const DELIVERABLES = [
  'Escolha da plataforma pelo porte e pela operação da loja',
  'Layout sob medida e checkout otimizado',
  'Integração com meios de pagamento e frete',
  'Integração com ERP, estoque e emissão fiscal',
  'Migração de outra plataforma',
  'Automação e IA: descrições de produto, atendimento e recuperação de carrinho',
];

const FAQ = [
  { q: 'Qual plataforma escolher?', a: 'Depende do porte, do catálogo e da operação da loja. A recomendação é feita no início do projeto, depois de entender produtos, volume e sistemas envolvidos.' },
  { q: 'É possível migrar de outra plataforma?', a: 'Sim. A migração de outra plataforma pode fazer parte do escopo do projeto.' },
  { q: 'A loja se integra ao ERP?', a: 'Sim. A integração com ERP, estoque e emissão fiscal faz parte do escopo, para que os pedidos sigam sem digitação entre sistemas.' },
  { q: 'Onde a IA entra na loja?', a: 'Em descrições de produto, no atendimento e na recuperação de carrinho, conectados à operação da loja.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageDesenvolvimentoDeEcommerce(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <EcomCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Desenvolvimento de <em className="mark">e-commerce.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Lojas virtuais tratadas como processo de ponta a ponta: vitrine, checkout, pagamento, estoque, emissão fiscal e atendimento. Em Shopify, WooCommerce, Tray ou sob medida.</p>
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
            <h2 className="h2">A plataforma certa <em className="mark">para a operação.</em></h2>
            <div className="rich intro">
              <p>A escolha depende do porte da loja, do tamanho do catálogo e dos sistemas com que ela precisa conversar.</p>
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
            <h2 className="h2">A venda não termina <em className="mark">no checkout.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Pagamento, frete, estoque, nota fiscal e atendimento fazem parte da mesma operação. A loja é desenhada para que cada pedido siga até a entrega sem retrabalho entre sistemas.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Conversar sobre a loja" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Da arquitetura à loja no ar.</h2>
            <div className="rich intro">
              <p>Cada página passa por checklist de responsividade, velocidade, compatibilidade entre navegadores e funcionalidades.</p>
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
          <header className="sec-head is-solo">
            <h2 className="h2">Escopo do <em className="mark">projeto.</em></h2>
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
        title="Lojas no ar."
        intro="Lojas virtuais integradas a pagamento, estoque, emissão fiscal e atendimento."
        items={[placeholderCase('ecom-1', ['Loja virtual']), placeholderCase('ecom-2', ['Integração com ERP'])]}
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
            <h2 className="h2">Uma loja integrada <em className="mark">à operação.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para entender o catálogo, os sistemas envolvidos e qual plataforma faz sentido para a loja.</p>
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
