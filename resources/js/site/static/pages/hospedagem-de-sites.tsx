// Gerado por scripts/export-static-pages.mjs a partir de /hospedagem-de-sites (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
// Planos, preços e recursos copiados de fireflyhost.com.br em 29/10/2026; o checkout é o da Firefly Host (financeiro.fireflyhost.com.br).
// Nos cards ficam só os diferenciais, na ordem acessos, simultâneos, armazenamento, e-mails, backups e extras; o comum vai para a base da categoria.
import type { ReactNode } from 'react';
import type { StaticPageProps } from '../index';
import { HostCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { BillingSwitch, PlanBase, PlanCard, PlanTabs, type Plan } from '../../plans';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20contratar%20uma%20hospedagem%20de%20site.';
const FIREFLY = 'https://fireflyhost.com.br/';
const STORE = 'https://financeiro.fireflyhost.com.br/index.php?rp=/store/';

type HostPlan = Plan & { id: string; checkout: string };
type Category = { id: string; tab: string; title: string; text: string; common: string[]; plans: HostPlan[] };

const ALL_PLANS = ['SSL grátis, com renovação automática', 'Migração gratuita, feita pela equipe', 'Suporte 100% humano', 'Uptime de 99,9%'];

const CATEGORIES: Category[] = [
  {
    id: 'sites',
    tab: 'Sites',
    title: 'Hospedagem de sites',
    text: 'Estabilidade, performance e segurança para sites que não podem parar.',
    common: ['Subdomínios ilimitados', 'Firewall'],
    plans: [
      {
        id: 'sites-basic',
        name: 'Basic',
        monthly: 45,
        annual: 550,
        checkout: `${STORE}hospedagem-profissional/hospedagem-profissional-basic`,
        adds: ['Até 5.000 acessos mensais', 'Até 30 acessos simultâneos', '10 GB de armazenamento NVMe', '10 contas de e-mail'],
      },
      {
        id: 'sites-pro',
        name: 'Pro',
        monthly: 75,
        annual: 750,
        featured: true,
        checkout: `${STORE}hospedagem-profissional/hospedagem-pro`,
        adds: ['Até 15.000 acessos mensais', 'Até 150 acessos simultâneos', '20 GB de armazenamento NVMe', '20 contas de e-mail', 'Backups diários'],
      },
      {
        id: 'sites-ultra',
        name: 'Ultra',
        monthly: 95,
        annual: 950,
        checkout: `${STORE}hospedagem-profissional/hospedagem-ultra`,
        adds: ['Até 50.000 acessos mensais', 'Até 500 acessos simultâneos', '50 GB de armazenamento NVMe', '30 contas de e-mail', 'Backups diários', 'Cache avançado', 'Antivírus'],
      },
    ],
  },
  {
    id: 'wordpress',
    tab: 'WordPress',
    title: 'Hospedagem otimizada para WordPress',
    text: 'Cache em nível de servidor para um WordPress até 10x mais rápido.',
    common: ['Instalador de WordPress em 1 clique', 'Instalação feita pela equipe, sem custo', 'Acelerador de WordPress', 'Anti-malware'],
    plans: [
      {
        id: 'wp-basic',
        name: 'WP Basic',
        monthly: 75,
        annual: 740,
        checkout: `${STORE}hospedagem-wordpress/wordpress-basic`,
        adds: ['Até 10.000 acessos mensais', 'Até 100 acessos simultâneos', '10 GB de armazenamento NVMe', 'Backups semanais'],
      },
      {
        id: 'wp-pro',
        name: 'WP Pro',
        monthly: 95,
        annual: 950,
        featured: true,
        checkout: `${STORE}hospedagem-wordpress/wordpress-pro`,
        adds: ['Até 20.000 acessos mensais', 'Até 200 acessos simultâneos', '20 GB de armazenamento NVMe', 'Backups diários'],
      },
      {
        id: 'wp-ultra',
        name: 'WP Ultra',
        monthly: 120,
        annual: 1200,
        checkout: `${STORE}hospedagem-wordpress/wp-ultra`,
        adds: ['Mais de 50.000 acessos mensais', 'Mais de 500 acessos simultâneos', '50 GB de armazenamento NVMe', 'Backups diários', 'Licença Elementor Pro'],
      },
    ],
  },
  {
    id: 'woocommerce',
    tab: 'WooCommerce',
    title: 'Hospedagem otimizada para WooCommerce',
    text: 'Plataforma segura e rápida para a loja online vender mais.',
    common: ['Instalador de WooCommerce em 1 clique', 'Instalação feita pela equipe, sem custo', 'Acelerador de WordPress', 'Anti-malware', 'Licença Elementor Pro', 'Backups diários'],
    plans: [
      {
        id: 'woo-basic',
        name: 'Woo Basic',
        monthly: 95,
        annual: 950,
        checkout: `${STORE}hospedagem-woocommerce/woo-basic`,
        adds: ['Até 10.000 clientes por mês', 'Até 100 clientes simultâneos', '10 GB de armazenamento NVMe'],
      },
      {
        id: 'woo-pro',
        name: 'Woo Pro',
        monthly: 125,
        annual: 1250,
        featured: true,
        checkout: `${STORE}hospedagem-woocommerce/woo-pro`,
        adds: ['Até 20.000 clientes por mês', 'Até 200 clientes simultâneos', '20 GB de armazenamento NVMe'],
      },
      {
        id: 'woo-ultra',
        name: 'Woo Ultra',
        monthly: 175,
        annual: 1750,
        checkout: `${STORE}hospedagem-woocommerce/wooultra`,
        adds: ['Mais de 50.000 clientes por mês', 'Mais de 500 clientes simultâneos', '50 GB de armazenamento NVMe'],
      },
    ],
  },
];

const FEATURES = [
  { title: 'Armazenamento NVMe', text: 'Discos NVMe, mais rápidos que SSD comum, para o site e o banco de dados responderem sem espera.' },
  { title: 'Cache avançado', text: 'Cache em nível de servidor que entrega as páginas mais rápido e alivia o processamento.' },
  { title: 'Firewall de aplicação (WAF)', text: 'Filtro de requisições maliciosas e bloqueio de tentativas de acesso por força bruta.' },
  { title: 'Proteção anti-DDoS', text: 'Mitigação de ataques de negação de serviço antes de chegarem ao site.' },
  { title: 'Scanner de malware', text: 'Varredura dos arquivos da conta para detectar código malicioso cedo.' },
  { title: 'Backups com JetBackup', text: 'Arquivos e bancos de dados restaurados pelo próprio cPanel, com retenção de 7 dias.' },
  { title: 'Isolamento com CloudLinux', text: 'Cada conta isolada com CageFS e recursos próprios. O vizinho não derruba o seu site.' },
  { title: 'cPanel, PHP e MariaDB', text: 'Painel cPanel, escolha da versão do PHP por site e bancos MariaDB.' },
  { title: 'E-mail profissional', text: 'Contas no seu domínio, com anti-spam e SMTP dedicado para as mensagens chegarem.' },
];

const FAQ: { q: string; a: ReactNode }[] = [
  { q: 'Quem é a Firefly Host?', a: 'É a empresa de hospedagem da Quatro Ventos. A infraestrutura e o atendimento são os mesmos; a contratação e a cobrança acontecem pela Firefly Host.' },
  { q: 'Qual a diferença entre o pagamento mensal e o anual?', a: 'No plano anual, o pagamento é antecipado e tem desconto de dois meses. O seletor Mensal e Anual mostra o valor equivalente por mês e o total cobrado por ano.' },
  { q: 'A migração é gratuita?', a: 'Sim. A equipe migra o site e os e-mails para a nova hospedagem sem custo.' },
  { q: 'Como funcionam os backups?', a: 'Os planos com backups diários guardam arquivos e bancos de dados com retenção de 7 dias e restauração pelo JetBackup no cPanel.' },
  { q: 'A hospedagem serve para sistemas e aplicações?', a: <>Os planos de hospedagem atendem sites, WordPress e lojas WooCommerce. Aplicações, APIs, n8n e bancos de dados rodam melhor em um Cloud VPS. Veja os planos de <a href="/vps-e-infraestrutura#planos">VPS e infraestrutura</a>.</> },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

function planWhatsApp(plan: Plan) {
  return `https://wa.me/554130399593?text=${encodeURIComponent(`Gostaria de contratar o plano ${plan.name} da Firefly Host.`)}`;
}

export default function PageHospedagemDeSites({ planLinks }: StaticPageProps) {
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
                <p>Planos a partir de R$ 45,00 por mês na Firefly Host, a empresa de hospedagem da Quatro Ventos. Armazenamento NVMe, cPanel e suporte 100% humano, com a mesma infraestrutura e o mesmo atendimento.</p>
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
            <h2 className="h2">Planos de hospedagem <em className="mark">para cada tipo de projeto.</em></h2>
            <div className="plan-switches">
              <PlanTabs items={CATEGORIES} label="Tipo de hospedagem" controls="planos-categoria" />
              <BillingSwitch />
            </div>
          </header>
          <div id="planos-categoria">
            {CATEGORIES.map((category, index) => (
              <div className="plan-category" data-plan-panel={category.id} hidden={index > 0} key={category.id}>
                <div className="plan-category-head">
                  <h3>{category.title}</h3>
                  <p>{category.text}</p>
                </div>
                <div className="plan-tiers">
                  {category.plans.map((plan) => (
                    <PlanCard key={plan.name} plan={plan} href={planLinks?.[plan.id] || plan.checkout} altHref={planWhatsApp(plan)} heading="h4" />
                  ))}
                </div>
                <PlanBase
                  title="Em todos os planos"
                  text="Os planos mudam no volume de acessos, no armazenamento e nos recursos extras. O resto vem em todos."
                  items={[...category.common, ...ALL_PLANS]}
                  heading="h4"
                />
              </div>
            ))}
          </div>
          <p className="plan-note">Contratação e cobrança pela Firefly Host. Para aplicações e alto tráfego, veja os planos de <a href="/vps-e-infraestrutura#planos">VPS e infraestrutura</a>.</p>
        </div>
      </section>
      {/* seção: firefly */}
      <section className="section on-light is-flush">
        <div className="wrap">
          <div className="prose-panel firefly-panel is-reveal" ref={revealOnView}>
            <div className="firefly-brand">
              <img src="/assets/parceiros/firefly-host.svg" alt="Firefly Host" width={507} height={125} loading="lazy" />
              <p>A empresa de hospedagem da Quatro Ventos</p>
            </div>
            <div className="prose-panel-side">
              <div className="rich">
                <p>A Firefly Host é a empresa de hospedagem da Quatro Ventos, criada depois de 20 anos hospedando sites com o nosso nome. A infraestrutura e o atendimento são os mesmos: quem cuida do seu servidor é o mesmo time que desenvolve e mantém sites.</p>
              </div>
              <div className="actions">
                <a className="pill line" href={FIREFLY} target="_blank" rel="noreferrer">Conhecer a Firefly Host</a>
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: exemplos */}
      <section className="section on-dark">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Segurança e desempenho <em className="mark">na infraestrutura.</em></h2>
            <div className="rich intro">
              <p>Os recursos da plataforma da Firefly Host. Limites, frequência de backup e extras de cada plano estão nos cards acima.</p>
            </div>
          </header>
          <ul className="ex-grid is-plain">
            {FEATURES.map((item) => (
              <li key={item.title}>
                <h3 className="h3">{item.title}</h3>
                <p>{item.text}</p>
              </li>
            ))}
          </ul>
        </div>
      </section>
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
                <p>Conte o tipo de site, o volume de acessos e quantas contas de e-mail a empresa usa hoje. Indicamos o plano que cabe.</p>
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
