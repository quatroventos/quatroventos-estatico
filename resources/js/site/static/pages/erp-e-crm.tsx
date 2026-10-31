// Gerado por scripts/export-static-pages.mjs a partir de /erp-e-crm (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { PipelineCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20ERP%20e%20CRM.';

const SCOPE = [
  { title: 'Odoo', text: 'Implantação e customização dos módulos, cadastros e regras da empresa, com ajustes onde o padrão não atende.' },
  { title: 'Salesforce e CRMs', text: 'Clientes, oportunidades e pedidos sincronizados entre o CRM e o restante dos sistemas.' },
  { title: 'Módulos sob medida', text: 'Funcionalidades próprias dentro da plataforma ou como sistema à parte, integrado a ela, quando o padrão não atende.' },
  { title: 'Integrações', text: 'E-commerce, site, WhatsApp e sistemas fiscais conectados ao ERP, com IA nas rotinas repetitivas.' },
];

const EXAMPLES = [
  { title: 'Pedido da loja no ERP', text: 'O pedido feito na loja virtual entra no ERP com cliente, itens e pagamento, pronto para faturar.' },
  { title: 'Lead do site no CRM', text: 'Formulários do site e de anúncios criam o lead no CRM, sem duplicar, e avisam o vendedor.' },
  { title: 'Oportunidade que vira pedido', text: 'A venda fechada no CRM gera o pedido no ERP sem redigitar cliente, produtos e condições.' },
  { title: 'Estoque e preço sincronizados', text: 'Saldo e preço do ERP refletidos na loja virtual e nos canais de venda.' },
  { title: 'Atendimento pelo WhatsApp', text: 'Conversas registradas no histórico do cliente no CRM, com consulta de pedidos pelo atendente.' },
  { title: 'Emissão fiscal', text: 'Pedidos faturados no ERP com emissão da nota pelo sistema fiscal e retorno do status.' },
  { title: 'Financeiro integrado', text: 'Títulos a receber gerados a partir do faturamento e baixados conforme o pagamento.' },
  { title: 'Documentos de entrada', text: 'Notas e pedidos de fornecedores lidos por IA e lançados no ERP para conferência.' },
];

const STEPS = [
  { title: 'Levantamento', text: 'Processos de vendas, financeiro e operação e os sistemas envolvidos.' },
  { title: 'Configuração', text: 'Plataforma configurada e customizada para as regras da empresa.' },
  { title: 'Integração', text: 'ERP e CRM conectados aos canais e sistemas da operação.' },
  { title: 'Acompanhamento', text: 'Ajustes a partir do uso e novas automações.' },
];

const DELIVERABLES = [
  'Mapeamento dos processos e dos sistemas envolvidos',
  'Plataforma configurada para as regras da empresa',
  'Módulos sob medida onde o padrão não atende',
  'Integrações com loja virtual, site, WhatsApp e fiscal',
  'Automação de rotinas repetitivas com IA',
  'Acompanhamento e ajustes a partir do uso',
];

const FAQ = [
  { q: 'Odoo, Salesforce ou sistema sob medida?', a: 'Depende do processo. Plataformas como Odoo e Salesforce resolvem boa parte da operação com configuração. Quando a plataforma não atende uma parte específica, desenvolvemos módulos sob medida integrados a ela.' },
  { q: 'Vocês integram o CRM que já usamos?', a: 'Sim. Além do Salesforce, integramos outros CRMs ao ERP, ao site, ao e-commerce e ao WhatsApp, por API ou integração sob medida.' },
  { q: 'O ERP conversa com os sistemas fiscais?', a: 'Sim. A integração com sistemas fiscais faz parte do escopo, para que a nota saia do mesmo dado do pedido.' },
  { q: 'Onde a IA entra no ERP e no CRM?', a: 'Em rotinas repetitivas, como ler documentos de entrada, classificar solicitações e preencher lançamentos, sempre dentro das regras configuradas.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageErpECrm(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <PipelineCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">ERP e CRM integrados <em className="mark">ao restante da operação.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Implantação, customização e integração de ERP e CRM, em plataformas como Odoo e Salesforce ou sob medida, para que vendas, financeiro e operação trabalhem com o mesmo dado.</p>
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
            <h2 className="h2">O escopo de <em className="mark">ERP e CRM.</em></h2>
            <div className="rich intro">
              <p>Da implantação da plataforma às integrações com os canais de venda, ao fiscal e ao restante dos sistemas.</p>
            </div>
          </header>
          <ul className="uses-band">
            {SCOPE.map((item) => (
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
            <h2 className="h2">Exemplos de <em className="mark">integração.</em></h2>
            <div className="rich intro">
              <p>Pontos em que o dado costuma ser digitado duas vezes e passa a circular sozinho entre os sistemas.</p>
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
              <h3 className="h3">Qualquer dado redigitado</h3>
              <p>Se a mesma informação é lançada em mais de um sistema, ela pode passar a circular sozinha entre eles.</p>
              <a className="ex-more-link" href="#contato">
                Conte qual é o seu caso
                <span className="arrow"><Arrow /></span>
              </a>
            </li>
          </ul>
        </div>
      </section>
      {/* seção: prose */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-light is-reveal" ref={revealOnView}>
            <h2 className="h2">Vendas, financeiro e operação <em className="mark">no mesmo dado.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Quando ERP e CRM não conversam, cada área trabalha com a própria versão do cliente, do pedido e do estoque. Integrados, o dado é lançado uma vez e segue para onde precisa.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Conversar sobre integração" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Do levantamento à operação integrada.</h2>
            <div className="rich intro">
              <p>A configuração parte dos processos de cada área, e as integrações entram depois que a plataforma reflete as regras da empresa.</p>
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
            <h2 className="h2">O que o projeto <em className="mark">entrega.</em></h2>
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
            <h2 className="h2">ERP e CRM falando <em className="mark">a mesma língua.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para mapear os sistemas que a empresa usa hoje e onde o dado está sendo digitado duas vezes.</p>
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
