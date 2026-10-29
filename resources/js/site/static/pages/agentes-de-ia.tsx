// Gerado por scripts/export-static-pages.mjs a partir de /agentes-de-ia (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { FlowCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20um%20agente%20de%20IA.';

const USES = [
  { title: 'Atendimento', text: 'Responde dúvidas, consulta pedidos e abre chamados, 24 horas por dia.' },
  { title: 'Vendas', text: 'Qualifica leads, esclarece dúvidas sobre produtos e agenda reuniões com o comercial.' },
  { title: 'Operação interna', text: 'Busca informações, preenche cadastros e executa rotinas entre sistemas.' },
  { title: 'Suporte a equipes', text: 'Um assistente que conhece procedimentos e documentos internos.' },
];

const EXAMPLES = [
  { title: 'Análise e seleção de produtos', text: 'Compara catálogos, preços e especificações de fornecedores, aplica os critérios da empresa e entrega uma lista curta com a justificativa de cada escolha.' },
  { title: 'Geração de conteúdo', text: 'Produz descrições de produto, posts, e-mails e fichas técnicas no tom da marca a partir dos dados do catálogo, e envia para revisão antes de publicar.' },
  { title: 'Assistente financeiro', text: 'Envia cobranças e lembretes de vencimento, emite boletos e notas fiscais, concilia pagamentos e avisa o financeiro sobre inadimplência.' },
  { title: 'Gestão de estoque', text: 'Acompanha os níveis de estoque, alerta sobre ruptura, sugere reposição pelo histórico de vendas e responde consultas de disponibilidade.' },
  { title: 'Automação de pedidos', text: 'Recebe pedidos por WhatsApp, e-mail ou planilha, confere dados e estoque, lança no ERP e informa o cliente sobre o status até a entrega.' },
  { title: 'Fluxo interno', text: 'Do atendimento à proposta e ao contrato ou ordem de serviço: coleta as informações, monta a proposta no modelo da empresa, gera o documento e acompanha as aprovações.' },
  { title: 'Atendimento automatizado', text: 'Responde dúvidas, consulta pedidos, abre chamados e transfere para uma pessoa quando o caso exige.' },
  { title: 'Leitura de documentos', text: 'Lê notas, contratos, currículos e comprovantes, extrai os dados, preenche os sistemas e sinaliza o que está fora do padrão.' },
  { title: 'Relatórios sob demanda', text: 'Consulta os sistemas e responde em linguagem natural perguntas como "quanto vendemos este mês por região", com o relatório pronto quando pedido.' },
  { title: 'Pós-venda e retenção', text: 'Faz pesquisas de satisfação, acompanha entregas, identifica clientes insatisfeitos e aciona o time antes do cancelamento.' },
  { title: 'Recrutamento e RH', text: 'Faz a triagem inicial de candidatos, agenda entrevistas e responde dúvidas de colaboradores sobre benefícios e políticas internas.' },
];

const STEPS = [
  { title: 'Diagnóstico', text: 'Atendimentos, tarefas e sistemas que o agente vai tocar.' },
  { title: 'Protótipo', text: 'O agente respondendo casos reais em ambiente de teste.' },
  { title: 'Desenvolvimento', text: 'Integrações, base de conhecimento e regras de transferência.' },
  { title: 'Homologação', text: 'Validação com o time antes de falar com clientes.' },
  { title: 'Produção', text: 'Publicação no canal, com conversas e custo monitorados.' },
  { title: 'Evolução', text: 'Ajustes contínuos a partir das conversas reais.' },
];

const DELIVERABLES = [
  'Desenho do fluxo e das regras do agente',
  'Integração com WhatsApp, site, e-mail ou ferramentas internas',
  'Conexão com ERP, CRM, banco de dados e APIs',
  'Base de conhecimento com os conteúdos da empresa',
  'Transferência para atendimento humano',
  'Painel de conversas, métricas e custo',
  'Ajustes contínuos após a publicação',
];

const FAQ = [
  { q: 'Como funciona um agente? É como o ChatGPT?', a: 'Usa o mesmo tipo de modelo de linguagem, mas com um trabalho definido. O ChatGPT responde sobre qualquer assunto com o que aprendeu no treinamento. O agente responde com base nos conteúdos da sua empresa, consulta seus sistemas e executa ações dentro de regras que nós configuramos, como abrir um chamado, consultar um pedido ou agendar uma reunião.' },
  { q: 'Como falo com o agente?', a: 'Por praticamente qualquer canal de texto: WhatsApp, chat do site, e-mail, Slack, Teams ou dentro de um sistema interno. Se o canal recebe uma mensagem, conseguimos acionar o agente a partir dela.' },
  { q: 'Funciona no WhatsApp?', a: 'Sim, via API oficial do WhatsApp Business ou provedores compatíveis.' },
  { q: 'O agente pode alucinar e inventar respostas?', a: 'O risco existe em qualquer modelo de linguagem, e o projeto é desenhado para reduzi-lo. O agente responde a partir da base de conhecimento e dos dados consultados nos sistemas, é instruído a dizer que não sabe quando a informação não está disponível e é testado com casos reais antes de ir para produção.' },
  { q: 'O agente pode errar?', a: 'Como qualquer sistema. Por isso o escopo de ação é limitado, as conversas são registradas e há regras claras de transferência para uma pessoa.' },
  { q: 'Qual modelo de IA é usado?', a: 'O que oferecer o melhor equilíbrio entre custo e qualidade para o caso: OpenAI, Claude, Gemini ou modelos abertos.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageAgentesDeIa(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <FlowCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Agentes de IA que <em className="mark">atendem, qualificam e executam.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Agentes que conversam com clientes, consultam sistemas e resolvem tarefas de ponta a ponta. No WhatsApp, no site ou dentro da operação, liberando o time para o trabalho que exige pessoas.</p>
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
            <h2 className="h2">Onde um agente <em className="mark">melhora o processo.</em></h2>
            <div className="rich intro">
              <p>Um agente assume a parte repetitiva de um fluxo e devolve ao time o que exige julgamento. Os quatro pontos onde ele costuma render mais:</p>
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
            <h2 className="h2">Exemplos de <em className="mark">aplicação.</em></h2>
            <div className="rich intro">
              <p>Fluxos em que um agente assume o trabalho repetitivo. Se o processo passa por mensagens, sistemas e planilhas, provavelmente cabe um agente.</p>
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
              <h3 className="h3">Qualquer operação repetitiva</h3>
              <p>Se uma tarefa segue regras, se repete todo dia e passa por mensagens, planilhas ou sistemas, ela pode ser assumida por um agente.</p>
              <a className="ex-more-link" href="#contato">
                Conte qual é a sua
                <span className="arrow"><Arrow /></span>
              </a>
            </li>
          </ul>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Do diagnóstico à conversa real.</h2>
            <div className="rich intro">
              <p>O agente entra em produção depois de responder casos reais em teste e passar pela validação do time. Depois disso, evolui com as conversas.</p>
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
            <h2 className="h2">Qual tarefa deveria <em className="mark">sair da fila do time?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para avaliar se o fluxo comporta um agente, quais sistemas ele precisa acessar e por onde começar.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Conversar sobre um agente" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
