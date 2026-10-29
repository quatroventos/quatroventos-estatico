// Gerado por scripts/export-static-pages.mjs a partir de /inteligencia-artificial (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { FlowCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20IA%20aplicada%20aos%20processos%20da%20empresa.';

const SERVICES = [
  { title: 'Agentes de IA', text: 'Assistentes que atendem clientes, qualificam leads e executam tarefas internas.', href: '/agentes-de-ia' },
  { title: 'Automação com IA', text: 'Workflows em n8n e integrações que eliminam etapas manuais.', href: '/automacao-com-ia' },
  { title: 'IA com os dados da empresa', text: 'Respostas e busca sobre documentos e sistemas internos, com RAG.', href: '/ia-com-dados-da-empresa' },
  { title: 'Integração de LLMs', text: 'OpenAI, Claude e Gemini dentro de sistemas existentes.', href: '/integracao-de-llm' },
  { title: 'Sistemas com IA', text: 'Produtos sob medida com IA no fluxo principal.', href: '/sistemas-com-ia' },];

const STEPS = [
  { title: 'Diagnóstico', text: 'Processos, dados e sistemas mapeados, com os pontos onde a IA gera ganho.' },
  { title: 'Prova de conceito', text: 'O caso de uso mais valioso testado com dados reais.' },
  { title: 'Integração', text: 'Solução conectada a sistemas, canais e permissões.' },
  { title: 'Produção', text: 'Publicação com monitoramento de qualidade, custo e uso.' },
  { title: 'Evolução', text: 'Prompts, modelos e fluxos ajustados conforme a operação muda.' },
];

const PRINCIPLES = [
  { title: 'Processo antes de ferramenta', text: 'O modelo é escolhido depois de entender o problema.' },
  { title: 'Modelo certo para cada tarefa', text: 'OpenAI, Claude, Gemini ou modelos abertos, conforme custo, qualidade e privacidade.' },
  { title: 'Dados protegidos', text: 'Controle de acesso, registro de uso e conformidade com a LGPD.' },
  { title: 'Humano no circuito', text: 'Decisões sensíveis passam por uma pessoa.' },
  { title: 'Custo previsível', text: 'Uso e custo medidos desde o primeiro dia.' },
];

const FAQ = [
  { q: 'IA faz sentido para uma operação pequena?', a: 'Faz quando existe um processo repetitivo, um volume de atendimento ou conhecimento espalhado em documentos. O diagnóstico indica se é o caso.' },
  { q: 'É preciso trocar os sistemas atuais?', a: 'Não. A IA entra integrada ao que já existe: ERP, CRM, WhatsApp, e-mail, planilhas e bancos de dados.' },
  { q: 'Os dados vão treinar o modelo?', a: 'Usamos APIs e configurações em que os dados enviados não são usados para treinar os modelos dos fornecedores, com acesso restrito ao que cada fluxo precisa.' },
  { q: 'Quanto custa?', a: 'Depende do escopo e do volume de uso. Após o diagnóstico, a proposta apresenta o custo do projeto e a estimativa de custo mensal dos modelos.' },
  { q: 'Quem mantém a solução depois?', a: 'A Quatro Ventos. Sustentação e evolução fazem parte da oferta.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageInteligenciaArtificial(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <FlowCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Inteligência artificial aplicada a <em className="mark">processos reais.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Transformação com IA não é comprar uma ferramenta. É redesenhar processos para que a inteligência artificial assuma o trabalho repetitivo, acelere decisões e use o conhecimento que já existe na operação. Projetamos, integramos e mantemos essas soluções em produção.</p>
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
            <h2 className="h2">Cada processo pede uma <em className="mark">solução diferente.</em></h2>
            <div className="rich intro">
              <p>Um agente no WhatsApp, um fluxo que lê notas fiscais, um assistente que conhece os manuais internos, um sistema novo com IA no centro. A solução é desenhada a partir do processo, combinando modelos, automações e integrações da forma que gera mais resultado.</p>
            </div>
          </header>
          <ul className="cards">
            {SERVICES.map((item, index) => (
              <li key={item.href}>
                <a className="card" href={item.href}>
                  <span className="num">
                    <span className="mono">{String(index + 1).padStart(2, '0')}</span>
                    <span className="go"><Arrow /></span>
                  </span>
                  <div>
                    <h3 className="h3">{item.title}</h3>
                    <div className="rich"><p>{item.text}</p></div>
                  </div>
                </a>
              </li>
            ))}
          </ul>
        </div>
      </section>
      {/* seção: prose */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-light is-reveal" ref={revealOnView}>
            <h2 className="h2">Do piloto <em className="mark">à produção.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma demonstração com IA leva uma tarde. Melhorar um processo de verdade exige mais: integração com os sistemas, custo previsível, dados protegidos e responsabilidade quando o cenário muda. É nesse trabalho que a Quatro Ventos atua.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Fazer um diagnóstico de IA" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Implantação, do diagnóstico à evolução.</h2>
            <div className="rich intro">
              <p>O caso de uso é testado com dados reais antes de ser integrado aos sistemas. Em produção, segue monitorado e ajustado conforme a operação muda.</p>
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
            <h2 className="h2">Princípios de <em className="mark">cada projeto.</em></h2>
          </header>
          <dl className="def-rows">
            {PRINCIPLES.map((item) => (
              <div key={item.title}>
                <dt className="h3">{item.title}</dt>
                <dd><div className="rich"><p>{item.text}</p></div></dd>
              </div>
            ))}
          </dl>
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
            <h2 className="h2">Qual processo a IA pode <em className="mark">melhorar primeiro?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa de diagnóstico define o ponto de partida.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Falar com um especialista em IA" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
