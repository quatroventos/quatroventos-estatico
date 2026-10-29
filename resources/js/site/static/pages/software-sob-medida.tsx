// Gerado por scripts/export-static-pages.mjs a partir de /software-sob-medida (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { SoftwareCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { SHOWCASE } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20um%20software%20sob%20medida.';

const USES = [
  { title: 'Gestão', text: 'Cadastros, aprovações, relatórios e permissões por perfil, no lugar de planilhas compartilhadas.' },
  { title: 'Portais', text: 'Área logada para clientes, fornecedores e parceiros acompanharem pedidos e enviarem documentos.' },
  { title: 'Integrações', text: 'Dados circulando entre ERP, CRM, loja virtual e ferramentas internas sem digitação manual.' },
  { title: 'MVPs', text: 'Uma primeira versão com o essencial, para testar com usuários reais antes do produto completo.' },
];

const EXAMPLES = [
  { title: 'Controle de ordens de serviço', text: 'Abertura, distribuição para a equipe, registro em campo e fechamento com histórico por cliente.' },
  { title: 'Portal do cliente', text: 'O cliente acompanha pedidos, baixa notas e boletos e abre solicitações sem precisar ligar.' },
  { title: 'Gestão de contratos', text: 'Contratos com vencimentos, reajustes e renovações acompanhados, com aviso antes do prazo.' },
  { title: 'Agendamento online', text: 'Agenda por profissional, unidade ou recurso, com confirmação e lembrete por WhatsApp ou e-mail.' },
  { title: 'Cotação e pedidos B2B', text: 'Catálogo com preço por cliente, montagem de pedido e envio direto para o ERP.' },
  { title: 'Painel de indicadores', text: 'Dados de vendas, operação e financeiro reunidos em um painel atualizado a partir dos sistemas.' },
  { title: 'Fluxo de aprovações', text: 'Compras, descontos e reembolsos com regras de alçada, histórico e notificação a cada etapa.' },
  { title: 'Gestão de documentos', text: 'Envio, versão, validade e assinatura de documentos de clientes, fornecedores ou colaboradores.' },
];

const STEPS = [
  { title: 'Arquitetura', text: 'Fluxos, dados e integrações.' },
  { title: 'Prototipagem', text: 'Telas validadas antes do código.' },
  { title: 'Frontend', text: 'Interface rápida e acessível.' },
  { title: 'Backend', text: 'Regras, APIs, integrações e segurança.' },
  { title: 'Homologação', text: 'Testes em ambiente privado.' },
  { title: 'Entrega e evolução', text: 'Publicação, monitoramento e melhorias.' },
];

const DELIVERABLES = [
  'Mapeamento do processo e desenho da arquitetura',
  'Protótipo das telas validado antes do código',
  'Frontend e backend do sistema',
  'Integrações com os sistemas e APIs da operação',
  'Homologação em ambiente privado',
  'Publicação, monitoramento e melhorias contínuas',
];

const FAQ = [
  { q: 'Por que não usar um sistema pronto?', a: 'Quando um sistema de prateleira atende, ele costuma ser o melhor caminho. O sob medida faz sentido quando o processo não cabe na ferramenta e a operação passa a se adaptar a ela, com planilhas e retrabalho ao redor.' },
  { q: 'O sistema conversa com o que já usamos?', a: 'Sim. Integrações com ERP, CRM, loja virtual, planilhas e APIs fazem parte do desenho da arquitetura, logo no início do projeto.' },
  { q: 'Dá para começar com uma versão menor?', a: 'Sim. Um MVP com o essencial permite validar o sistema com usuários reais e definir as próximas etapas a partir do uso.' },
  { q: 'Quem faz o frontend e quem faz o backend?', a: 'O mesmo time. Arquitetura, interface e backend ficam com as mesmas pessoas, do banco de dados à tela.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageSoftwareSobMedida(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <SoftwareCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Software desenhado <em className="mark">a partir do processo.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Sistemas web, portais, painéis e plataformas construídos sobre a forma como a operação realmente funciona. Frontend e backend no mesmo time, da arquitetura à publicação.</p>
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
            <h2 className="h2">Aplicações de um <em className="mark">sistema próprio.</em></h2>
            <div className="rich intro">
              <p>Os tipos de sistema que mais aparecem quando a operação já não cabe em planilhas ou em uma ferramenta genérica.</p>
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
            <h2 className="h2">Exemplos de <em className="mark">sistemas.</em></h2>
            <div className="rich intro">
              <p>Processos que costumam viver em planilhas, e-mails e mensagens até ganharem um sistema próprio.</p>
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
              <h3 className="h3">Qualquer processo da operação</h3>
              <p>Se o processo tem regras próprias e hoje depende de planilhas, retrabalho ou de uma ferramenta que não encaixa, ele pode virar um sistema.</p>
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
            <h2 className="h2">Full stack <em className="mark">de verdade.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>O mesmo time desenha a arquitetura, programa a interface e constrói o backend. Menos repasse, menos ruído e decisões técnicas coerentes do banco de dados à tela.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Conversar sobre um sistema" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Do processo ao sistema em uso.</h2>
            <div className="rich intro">
              <p>As telas são validadas antes do código e o sistema passa por homologação em ambiente privado antes da publicação.</p>
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
      {/* seção: cases */}
      <CaseShowcase
        title="Software sob medida em produção."
        intro="Um sistema construído em volta da operação do cliente, ligado ao que ela já usava."
        items={[SHOWCASE['fiscal-hub']]}
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
            <h2 className="h2">Qual processo precisa de <em className="mark">um sistema próprio?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para entender o processo, ver com quais sistemas ele precisa conversar e por onde começar.</p>
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
