// Gerado por scripts/export-static-pages.mjs a partir de /vps-e-infraestrutura (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
// Planos e preços de Cloud VPS copiados de fireflyhost.com.br em 29/10/2026; a Firefly não tem checkout por plano de VPS, a contratação é pelo WhatsApp.
import { useState, type ReactNode } from 'react';
import type { StaticPageProps } from '../index';
import { VpsCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { BillingSwitch, PlanBase, PlanCard, PlanTabs, type Plan } from '../plans';
const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20um%20VPS%20gerenciado.';

type Tier = { id: string; tab: string; title: string; text: string; common: string[]; plans: Plan[] };

const ALL_VPS = ['20 TB de tráfego', 'Backups diários', '1 IP dedicado', 'Instalação grátis'];

const TIERS: Tier[] = [
  {
    id: 'padrao',
    tab: 'VPS padrão',
    title: 'Cloud VPS padrão',
    text: 'Para cargas variáveis e aplicações de consumo baixo a médio de processamento.',
    common: [],
    plans: [
      { name: 'VPS 2x4', monthly: 250, annual: 2500, adds: ['2 vCPUs', '4 GB de RAM', '40 GB de armazenamento NVMe'] },
      { name: 'VPS 4x8', monthly: 410, annual: 4100, adds: ['4 vCPUs', '8 GB de RAM', '80 GB de armazenamento NVMe'] },
      { name: 'VPS 8x16', monthly: 750, annual: 7500, adds: ['8 vCPUs', '16 GB de RAM', '160 GB de armazenamento NVMe'] },
    ],
  },
  {
    id: 'dedicado',
    tab: 'Alto desempenho',
    title: 'Cloud VPS de alto desempenho',
    text: 'vCPUs dedicadas na geração de hardware mais recente, para produção crítica com uso contínuo de CPU.',
    common: ['vCPUs e recursos dedicados'],
    plans: [
      { name: 'VPS 8x16 dedicado', monthly: 850, annual: 8500, adds: ['8 vCPUs AMD', '16 GB de RAM', '320 GB de armazenamento NVMe'] },
      { name: 'VPS 12x24 dedicado', monthly: 1250, annual: 12500, adds: ['12 vCPUs', '24 GB de RAM', '480 GB de armazenamento NVMe'] },
      { name: 'VPS 16x32 dedicado', monthly: 1550, annual: 15500, adds: ['16 vCPUs', '32 GB de RAM', '640 GB de armazenamento NVMe'] },
    ],
  },
];

function planWhatsApp(plan: Plan) {
  return `https://wa.me/554130399593?text=${encodeURIComponent(`Gostaria de contratar o ${plan.name}.`)}`;
}

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
  { q: 'Qual a diferença entre hospedagem e VPS?', a: <>Os planos de hospedagem atendem sites e e-mails. O VPS é um servidor configurado sob medida para a aplicação, quando ela exige mais controle, privacidade ou desempenho. Veja os planos de <a href="/hospedagem-de-sites#planos">hospedagem de sites</a>.</> },
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
  const [annual, setAnnual] = useState(false);
  const [tierId, setTierId] = useState(TIERS[0].id);
  const [switched, setSwitched] = useState(false);
  const tier = TIERS.find((item) => item.id === tierId) ?? TIERS[0];

  const selectTier = (id: string) => {
    setTierId(id);
    setSwitched(true);
  };

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
                <p>Servidores configurados e mantidos por quem desenvolve. Para aplicações, APIs, n8n, bancos de dados e modelos de IA que precisam rodar em ambiente controlado.</p>
              </div>
            </div>
          </div>
          <LeadForm className="hero-form" id="contato" whatsappHref={WHATSAPP} />
        </div>
      </section>
      {/* seção: plans */}
      <section className="section on-light" id="planos">
        <div className="wrap">
          <header className="sec-head plan-head">
            <h2 className="h2">Planos de Cloud VPS <em className="mark">para cada carga.</em></h2>
            <div className="plan-switches">
              <PlanTabs items={TIERS} activeId={tier.id} label="Tipo de VPS" controls="planos-vps" onChange={selectTier} />
              <BillingSwitch annual={annual} onChange={setAnnual} />
            </div>
          </header>
          <div className={`plan-category${switched ? ' is-swap' : ''}`} id="planos-vps" key={tier.id}>
            <div className="plan-category-head">
              <h3>{tier.title}</h3>
              <p>{tier.text}</p>
            </div>
            <div className="plan-tiers">
              {tier.plans.map((plan) => (
                <PlanCard key={plan.name} plan={plan} annual={annual} href={planWhatsApp(plan)} heading="h4" instant={switched} />
              ))}
            </div>
            <PlanBase
              title="Em todos os planos"
              text="Os planos mudam em vCPUs, memória e armazenamento. O resto vem em todos."
              items={[...tier.common, ...ALL_VPS]}
              heading="h4"
              instant={switched}
            />
          </div>
          <p className="plan-note">Infraestrutura da Firefly Host, a empresa de hospedagem da Quatro Ventos. O plano cobre o servidor e a instalação; a gestão da aplicação (deploy com Docker, monitoramento e suporte) é orçada à parte. Para sites, WordPress e WooCommerce, veja os planos de <a href="/hospedagem-de-sites#planos">hospedagem de sites</a>.</p>
        </div>
      </section>
      {/* seção: list */}
      <section className="section on-light is-flush is-mark-draw">
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
              <img className="prose-logo" src="/assets/parceiros/firefly-host.svg" alt="Firefly Host" width={507} height={125} loading="lazy" />
              <div className="rich">
                <p>A Quatro Ventos mantém infraestrutura própria de hospedagem e servidores, operada pela Firefly Host, a empresa de hospedagem do grupo. Quando a aplicação exige mais controle, privacidade ou desempenho, o VPS é configurado sob medida.</p>
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
