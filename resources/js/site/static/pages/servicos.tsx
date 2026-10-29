// Gerado por scripts/export-static-pages.mjs a partir de /servicos (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { AgentLog } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20saber%20qual%20servi%C3%A7o%20atende%20meu%20processo.';

const GROUPS = [
  {
    title: 'Inteligência artificial',
    href: '/inteligencia-artificial',
    items: [
      { title: 'Agentes de IA', href: '/agentes-de-ia' },
      { title: 'Automação com IA e n8n', href: '/automacao-com-ia' },
      { title: 'IA com os dados da empresa', href: '/ia-com-dados-da-empresa' },
      { title: 'Integração de LLMs', href: '/integracao-de-llm' },
      { title: 'Sistemas com IA', href: '/sistemas-com-ia' },
    ],
  },
  {
    title: 'Engenharia de software',
    href: '/desenvolvimento-de-software',
    items: [
      { title: 'Software sob medida', href: '/software-sob-medida' },
      { title: 'SaaS', href: '/desenvolvimento-de-saas' },
      { title: 'Modernização de sistemas', href: '/modernizacao-de-sistemas' },
      { title: 'ERP e CRM', href: '/erp-e-crm' },
      { title: 'UI/UX design', href: '/ui-ux-design' },
      { title: 'QA e testes', href: '/qa-e-testes' },
    ],
  },
  {
    title: 'Desenvolvimento web',
    href: '/desenvolvimento-web',
    items: [
      { title: 'Sites', href: '/desenvolvimento-de-sites' },
      { title: 'Landing pages', href: '/landing-pages' },
      { title: 'E-commerce', href: '/desenvolvimento-de-ecommerce' },
      { title: 'WordPress', href: '/sites-wordpress' },
      { title: 'SEO e LLMs', href: '/seo-e-llms' },
    ],
  },
  {
    title: 'Suporte e infraestrutura',
    href: '/suporte-e-infraestrutura',
    items: [
      { title: 'Sustentação de software', href: '/sustentacao-de-software' },
      { title: 'Manutenção de sites', href: '/manutencao-de-sites' },
      { title: 'Recuperação de WordPress', href: '/recuperacao-de-site-wordpress' },
      { title: 'Hospedagem', href: '/hospedagem-de-sites' },
      { title: 'VPS e infraestrutura', href: '/vps-e-infraestrutura' },
    ],
  },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageServicos(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero">
        <AgentLog />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner"><em className="mark">Serviços</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Da estratégia de IA à infraestrutura. Um único time para melhorar processos com tecnologia: projetar, construir, integrar e operar.</p>
              </div>
            </div>
          </div>
          <LeadForm className="hero-form" id="contato" whatsappHref={WHATSAPP} />
        </div>
      </section>
      {/* seção: list (uma por grupo) */}
      {GROUPS.map((group, index) => (
        <section key={group.href} className={index === 0 ? 'section on-light' : 'section on-light is-flush'}>
          <div className="wrap">
            <header className="sec-head is-solo">
              <h2 className="h2">
                <a href={group.href}>{group.title}<span className="head-go"><Arrow /></span></a>
              </h2>
            </header>
            <ul className="link-rows">
              {group.items.map((item) => (
                <li key={item.href}>
                  <a href={item.href}>{item.title}<span className="go"><Arrow /></span></a>
                </li>
              ))}
            </ul>
          </div>
        </section>
      ))}
      {/* seção: cta */}
      <section className="section on-light is-flush">
        <div className="wrap">
          <div className="prose-panel is-reveal" ref={revealOnView}>
            <h2 className="h2">O desafio não cabe <em className="mark">em uma categoria?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Descreva o processo. Indicamos a abordagem.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Conversar no WhatsApp" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
