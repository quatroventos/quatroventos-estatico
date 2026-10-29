// Gerado por scripts/export-static-pages.mjs a partir de /ia-com-dados-da-empresa (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { RagCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20IA%20com%20os%20dados%20da%20empresa.';

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
  { title: 'Fontes', text: 'Documentos, sistemas e permissões que entram na base.' },
  { title: 'Ingestão', text: 'Conteúdo extraído, dividido e indexado para busca semântica.' },
  { title: 'Protótipo', text: 'Respostas testadas com perguntas reais do time.' },
  { title: 'Avaliação', text: 'Qualidade das respostas medida, com a fonte citada.' },
  { title: 'Produção', text: 'Chat ou integração publicados, com a base atualizada a partir das fontes.' },
];

const DELIVERABLES = [
  'Ingestão e atualização dos documentos e fontes de dados',
  'Busca semântica com controle de permissão',
  'Interface de chat ou integração no sistema existente',
  'Respostas com citação da fonte',
  'Avaliação de qualidade das respostas',
];

const FAQ = [
  { q: 'Os documentos saem da empresa?', a: 'A arquitetura segue a exigência de privacidade: APIs com retenção zero, nuvem própria ou modelos rodando na infraestrutura do cliente.' },
  { q: 'E quando um documento muda?', a: 'A base é atualizada automaticamente a partir das fontes conectadas.' },
  { q: 'A IA pode inventar respostas?', a: 'O risco existe em qualquer modelo de linguagem. O assistente responde a partir dos trechos recuperados, cita a fonte e é instruído a dizer quando a informação não está nos documentos.' },
  { q: 'Cada pessoa vê tudo?', a: 'Não. A busca respeita as permissões das fontes conectadas, e cada pessoa recebe respostas apenas com o conteúdo que já pode acessar.' },
  { q: 'Quais formatos de documento entram?', a: 'PDFs, documentos de texto, planilhas, páginas internas, chamados e dados de sistemas. Formatos menos comuns são avaliados caso a caso.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageIaComDadosDaEmpresa(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <RagCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">O conhecimento da empresa, <em className="mark">respondendo em segundos.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Manuais, contratos, políticas, chamados antigos, planilhas. Construímos assistentes que respondem com base nos documentos e sistemas internos, citam a fonte e respeitam quem pode ver o quê.</p>
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
            <h2 className="h2">Aplicações <em className="mark">no dia a dia.</em></h2>
            <div className="rich intro">
              <p>Onde o conhecimento está espalhado em documentos e sistemas, um assistente encontra a resposta e mostra de onde ela veio.</p>
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
              <p>Fluxos em que a IA consulta os documentos e sistemas da empresa antes de agir. Se a tarefa depende de procurar uma informação interna, provavelmente cabe um assistente.</p>
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
              <h3 className="h3">Qualquer conhecimento espalhado</h3>
              <p>Se a resposta existe em algum manual, contrato, planilha ou sistema, mas alguém precisa parar para procurar, a IA encontra, responde e mostra de onde tirou.</p>
              <a className="ex-more-link" href="#contato">
                Conte onde está o seu
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
            <h2 className="h2">Como funciona <em className="mark">o RAG.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>RAG (geração aumentada por recuperação) é a técnica que busca os trechos certos nos documentos antes de o modelo responder. A IA responde com o conteúdo da empresa, não com o que leu na internet, e mostra de onde tirou a resposta.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Avaliar os documentos da empresa" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Dos documentos à resposta com fonte.</h2>
            <div className="rich intro">
              <p>O assistente vai para produção depois de responder perguntas reais do time e ter a qualidade avaliada. Depois disso, a base acompanha as mudanças nas fontes.</p>
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
            <h2 className="h2">Onde está o conhecimento que <em className="mark">ninguém encontra?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para ver quais documentos e sistemas entram na base, quem precisa consultar e por onde começar.</p>
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
