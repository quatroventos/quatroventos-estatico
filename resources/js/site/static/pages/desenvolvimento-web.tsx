// Gerado por scripts/export-static-pages.mjs a partir de /desenvolvimento-web (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { ReactNode } from 'react';
import type { StaticPageProps } from '../index';
import { AgentLog } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20um%20or%C3%A7amento%20de%20desenvolvimento%20web.';

const SERVICES = [
  { href: '/desenvolvimento-de-sites', title: 'Sites sob medida', text: 'Institucionais e corporativos.' },
  { href: '/landing-pages', title: 'Landing pages', text: 'Páginas focadas em conversão.' },
  { href: '/desenvolvimento-de-ecommerce', title: 'E-commerce', text: 'Shopify, WooCommerce e Tray.' },
  { href: '/sites-wordpress', title: 'WordPress', text: 'Sites sob medida, simples de gerenciar.' },
  { href: '/seo-e-llms', title: 'SEO e LLMs', text: 'Otimização para mecanismos de busca e LLMs.' },
];

const STANDARDS = [
  { title: 'Responsivos', text: 'Celular, tablet e desktop.' },
  { title: 'Gerenciáveis', text: 'Textos, imagens e páginas editados pelo painel.' },
  { title: 'Rápidos', text: 'Arquivos e código otimizados para carregamento.' },
  { title: 'Sob medida', text: 'Sem template pronto.' },
  { title: 'Preparados para o Google', text: 'Estrutura semântica, metas e performance.' },
  { title: 'Garantidos', text: '30 dias de garantia para ajustes e correções após a entrega.' },
];

const FAQ: { q: string; a: ReactNode }[] = [
  { q: 'Qual serviço escolher?', a: 'Depende do objetivo. Um site apresenta a empresa e organiza o conteúdo; uma landing page leva a uma única ação; uma loja virtual vende e conversa com estoque, pagamento e emissão fiscal. A recomendação sai da primeira conversa.' },
  { q: 'O conteúdo é editável?', a: 'Sim. Todo site é entregue com painel para editar textos, imagens e páginas.' },
  { q: 'O site se conecta a outras ferramentas?', a: 'Sim. Formulários, WhatsApp, CRM, automações e agentes de IA entram no projeto desde a arquitetura.' },
  { q: 'E após a publicação?', a: <>30 dias de garantia e planos de manutenção em <a href="/manutencao-de-sites">Manutenção de sites e WordPress</a>.</> },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageDesenvolvimentoWeb(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero">
        <AgentLog />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Sites que trabalham pelo <em className="mark">processo comercial.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Sites, landing pages e lojas virtuais sob medida. Rápidos, gerenciáveis, preparados para o Google e conectados a atendimento, CRM, automações e IA.</p>
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
            <h2 className="h2">Serviços de <em className="mark">desenvolvimento web.</em></h2>
            <div className="rich intro">
              <p>Do site institucional à loja virtual, cada projeto é desenhado para o público e conectado às ferramentas que o time já usa.</p>
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
                    <div className="rich">
                      <p>{item.text}</p>
                    </div>
                  </div>
                </a>
              </li>
            ))}
          </ul>
        </div>
      </section>
      {/* seção: list */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <header className="sec-head is-solo">
            <h2 className="h2">Padrão de <em className="mark">entrega.</em></h2>
          </header>
          <dl className="def-rows">
            {STANDARDS.map((item) => (
              <div key={item.title}>
                <dt className="h3">{item.title}</dt>
                <dd>
                  <div className="rich">
                    <p>{item.text}</p>
                  </div>
                </dd>
              </div>
            ))}
          </dl>
        </div>
      </section>
      {/* seção: prose */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-light is-reveal" ref={revealOnView}>
            <h2 className="h2">Mais que vitrine, <em className="mark">parte da operação.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Formulários que chegam ao CRM, atendimento no WhatsApp, automações e agentes de IA. O site é planejado para levar cada contato ao próximo passo do processo comercial.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Conversar sobre o projeto" />
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
      {/* seção: logos */}
      <section className="section on-white">
        <div className="wrap">
          <header className="sec-head is-solo">
            <h2 className="h2">Clientes</h2>
          </header>
          <ul className="logos">
            <li>
              <img src="/assets/clientes/santaplural.png" alt="Santa Plural" loading="lazy" />
            </li>
            <li>
              <img src="/assets/clientes/rodolfofontana.png" alt="Rodolfo Fontana" loading="lazy" />
            </li>
            <li>
              <img src="/assets/clientes/liv.png" alt="Liv Decora" loading="lazy" />
            </li>
            <li>
              <img src="/assets/clientes/inoveit.png" alt="Inove IT" loading="lazy" />
            </li>
            <li>
              <img src="/assets/clientes/hemera.png" alt="Hemera DTVM" loading="lazy" />
            </li>
            <li>
              <img src="/assets/clientes/garimpo.png" alt="Garimpo Home" loading="lazy" />
            </li>
            <li>
              <img src="/assets/clientes/fernandasaad.png" alt="Fernanda Saad" loading="lazy" />
            </li>
            <li>
              <img src="/assets/clientes/giselesiqueira.png" alt="Gisele Siqueira Dermatologista" loading="lazy" />
            </li>
            <li>
              <img src="/assets/clientes/digio.png" alt="Digio" loading="lazy" />
            </li>
            <li>
              <img src="/assets/clientes/ddm.png" alt="Data Driven Mkt" loading="lazy" />
            </li>
          </ul>
        </div>
      </section>
      {/* seção: cta */}
      <section className="section on-white is-flush">
        <div className="wrap">
          <div className="prose-panel is-reveal" ref={revealOnView}>
            <h2 className="h2">Um site que faça parte <em className="mark">do processo.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para entender o objetivo, escolher entre site, landing page ou loja virtual e definir por onde começar.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Pedir orçamento" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
