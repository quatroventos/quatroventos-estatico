// Gerado por scripts/export-static-pages.mjs a partir de /automacao-com-ia (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { N8nCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20automa%C3%A7%C3%A3o%20de%20processos%20com%20IA.';

const USES = [
  { title: 'Documentos', text: 'Leitura de e-mails, PDFs e notas fiscais com extração de dados.' },
  { title: 'Sistemas', text: 'Cadastro, atualização e sincronização de pedidos, estoque e clientes entre ERP, CRM e planilhas.' },
  { title: 'Comunicação', text: 'Triagem de chamados, notificações e aprovações por WhatsApp, e-mail ou Slack.' },
  { title: 'Relatórios', text: 'Geração de relatórios e resumos a partir dos dados dos sistemas.' },
];

const EXAMPLES = [
  { title: 'Entrada de pedidos', text: 'Lê o pedido que chega por e-mail ou PDF, confere cliente e itens e lança no ERP sem digitação.' },
  { title: 'Notas fiscais e boletos', text: 'Extrai os dados das notas recebidas, confere com o pedido de compra e lança no financeiro.' },
  { title: 'Conciliação financeira', text: 'Cruza o extrato bancário com contas a receber e a pagar e aponta as divergências.' },
  { title: 'Sincronização de estoque', text: 'Mantém estoque, preços e produtos iguais entre ERP, loja virtual e marketplaces.' },
  { title: 'Cadastro de leads', text: 'Leva leads de formulários e anúncios para o CRM, sem duplicar, e avisa o comercial.' },
  { title: 'Propostas e contratos', text: 'Gera proposta e contrato a partir do CRM, no modelo da empresa, e envia para assinatura.' },
  { title: 'Aprovações', text: 'Envia pedidos de compra, descontos e reembolsos para aprovação no WhatsApp, e-mail ou Slack e registra a resposta.' },
  { title: 'Triagem de chamados', text: 'Classifica chamados por assunto e urgência, responde os simples e encaminha o restante para a fila certa.' },
  { title: 'Relatórios recorrentes', text: 'Monta o relatório semanal ou mensal a partir dos sistemas e envia o resumo por e-mail ou WhatsApp.' },
  { title: 'Onboarding', text: 'Cria acessos e cadastros e envia documentos e boas-vindas a partir de um único gatilho, para clientes ou colaboradores.' },
  { title: 'Monitoramento e alertas', text: 'Acompanha prazos, SLAs, rupturas de estoque e falhas de integração e avisa a pessoa certa.' },
];

const STEPS = [
  { title: 'Mapeamento', text: 'O processo desenhado como funciona hoje.' },
  { title: 'Desenho', text: 'O fluxo automático e as etapas com IA.' },
  { title: 'Construção', text: 'Workflows e integrações.' },
  { title: 'Validação', text: 'Execução em paralelo com o processo atual.' },
  { title: 'Operação', text: 'Monitoramento de falhas e ajustes.' },
];

const DELIVERABLES = [
  'Mapeamento do processo atual e do fluxo automatizado',
  'Workflows em n8n, código ou os dois',
  'Integração com ERP, CRM, planilhas, e-mail e WhatsApp',
  'Etapas com IA para ler documentos, classificar e resumir',
  'Logs de execução e alertas de falha',
  'Documentação do fluxo para o time',
  'Monitoramento e ajustes após a entrada em operação',
];

const FAQ = [
  { q: 'Qual a diferença entre automação e agente de IA?', a: 'A automação segue um fluxo definido: quando algo acontece, executa as etapas na ordem desenhada. A IA entra nas etapas que exigem interpretar texto, documento ou decisão. Um agente conversa e escolhe o que fazer a cada mensagem. Muitos projetos combinam os dois.' },
  { q: 'O que é n8n?', a: 'Uma plataforma de automação de código aberto para montar fluxos entre sistemas. Pode ser hospedada pela própria empresa.' },
  { q: 'Onde a automação roda?', a: 'O n8n roda na infraestrutura do cliente ou na da Quatro Ventos, com controle de acesso e logs. Fluxos em código seguem a mesma lógica.' },
  { q: 'E sistemas sem API?', a: 'Muitas vezes é possível automatizar por banco de dados, arquivos, e-mail ou integração sob medida. A avaliação é feita caso a caso.' },
  { q: 'E se a automação falhar?', a: 'Cada execução fica registrada, e as falhas geram alerta para quem acompanha o fluxo. Antes de substituir o processo atual, a automação roda em paralelo para validar os resultados.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageAutomacaoComIa(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <N8nCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Automação de processos <em className="mark">com IA.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Copiar dados entre sistemas, ler e-mails para abrir pedidos, montar o mesmo relatório toda semana. Transformamos esse trabalho em fluxos automáticos, com IA nas etapas que exigem interpretar texto, documento ou decisão.</p>
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
            <h2 className="h2">O que <em className="mark">automatizar.</em></h2>
            <div className="rich intro">
              <p>Tarefas que seguem regras e se repetem toda semana. A automação cuida do fluxo, e a IA entra onde é preciso ler, classificar ou resumir.</p>
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
            <h2 className="h2">Exemplos de <em className="mark">automação.</em></h2>
            <div className="rich intro">
              <p>Fluxos que saem da mão do time e passam a rodar sozinhos, com uma pessoa só nas exceções.</p>
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
              <h3 className="h3">Qualquer rotina entre sistemas</h3>
              <p>Se alguém copia dados de um e-mail, planilha ou sistema para outro seguindo sempre as mesmas regras, essa rotina pode virar um fluxo no n8n, com IA nas etapas que exigem ler e interpretar.</p>
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
            <h2 className="h2">n8n, código <em className="mark">ou os dois.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>n8n quando o fluxo precisa ser visível e fácil de ajustar. Código quando exige performance, regra complexa ou escala. O n8n roda na infraestrutura do cliente ou na da Quatro Ventos, com controle de acesso e logs.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Avaliar um processo" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Do processo manual ao fluxo em operação.</h2>
            <div className="rich intro">
              <p>A automação só substitui o processo atual depois de rodar em paralelo e entregar o mesmo resultado. Depois disso, segue monitorada.</p>
            </div>
          </header>
          <ol className="steps is-timeline is-5 is-scrub">
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
            <h2 className="h2">Qual processo consome <em className="mark">mais horas do time?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para mapear o processo, ver quais sistemas entram no fluxo e por onde começar.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Mapear a automação" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
