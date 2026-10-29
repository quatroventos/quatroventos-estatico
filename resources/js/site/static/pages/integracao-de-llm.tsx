// Gerado por scripts/export-static-pages.mjs a partir de /integracao-de-llm (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { LlmCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20integrar%20IA%20ao%20nosso%20sistema.';

const EXAMPLES = [
  { title: 'Resumo e classificação', text: 'Resumo e classificação de textos, tickets e documentos, para a equipe ler menos e encaminhar mais rápido.' },
  { title: 'Geração de conteúdo', text: 'Geração de descrições, e-mails e conteúdos a partir dos dados do sistema, com revisão antes de publicar.' },
  { title: 'Extração estruturada', text: 'Extração estruturada de informações de PDFs e imagens, em campos prontos para gravar no banco.' },
  { title: 'Assistente no painel', text: 'Assistentes dentro do painel do sistema, que respondem dúvidas e executam ações do usuário.' },
  { title: 'Tradução e adaptação', text: 'Tradução e adaptação de conteúdo para outros idiomas, públicos ou canais, mantendo o tom definido.' },
  { title: 'Busca em linguagem natural', text: 'O usuário pergunta em texto livre e o sistema consulta os próprios dados para responder.' },
  { title: 'Respostas sugeridas', text: 'Sugestões de resposta para atendentes, com base no histórico do cliente e nas políticas da empresa.' },
  { title: 'Revisão e validação', text: 'Cadastros, textos e documentos conferidos contra regras definidas, com o que está fora do padrão sinalizado.' },
];

const STEPS = [
  { title: 'Mapeamento', text: 'As etapas do sistema em que a IA melhora o processo.' },
  { title: 'Prova de conceito', text: 'Modelo e prompt testados com dados reais.' },
  { title: 'Integração', text: 'Código, cache, limites e registro de custo na stack existente.' },
  { title: 'Produção', text: 'Publicação com qualidade e custo monitorados.' },
];

const DELIVERABLES = [
  'Escolha de modelo por tarefa, custo e latência',
  'Prompts versionados e testados',
  'Avaliação automática de qualidade',
  'Cache, limites e fallback entre fornecedores',
  'Registro de uso e custo por funcionalidade',
  'Tratamento de dados sensíveis',
];

const FAQ = [
  { q: 'É preciso reescrever o sistema?', a: 'Não. A IA entra integrada à stack existente, nas etapas em que melhora o processo.' },
  { q: 'Qual modelo é usado?', a: 'O que oferecer o melhor equilíbrio entre custo, qualidade e latência para cada tarefa: OpenAI, Claude, Gemini ou modelos abertos.' },
  { q: 'Os dados vão treinar o modelo?', a: 'Usamos APIs e configurações em que os dados enviados não são usados para treinar os modelos dos fornecedores, com tratamento de dados sensíveis antes do envio.' },
  { q: 'Como o custo é controlado?', a: 'Com cache, limites de uso e registro de consumo e custo por funcionalidade, acompanhados desde a entrada em produção.' },
  { q: 'E se um fornecedor ficar fora do ar?', a: 'A integração pode ter fallback para outro fornecedor, para que a funcionalidade continue respondendo.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageIntegracaoDeLlm(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <LlmCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">ChatGPT, Claude e Gemini <em className="mark">dentro do sistema.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>O sistema já funciona. A IA entra onde melhora o processo: classificar, resumir, gerar, extrair, responder. Integrada à stack existente, com custo controlado.</p>
              </div>
            </div>
          </div>
          <LeadForm className="hero-form" id="contato" whatsappHref={WHATSAPP} />
        </div>
      </section>
      {/* seção: exemplos */}
      <section className="section on-light is-mark-draw">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Integrações <em className="mark">comuns.</em></h2>
            <div className="rich intro">
              <p>Etapas em que um modelo de linguagem assume a leitura, a escrita ou a interpretação de texto dentro de um sistema que já está em uso.</p>
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
              <h3 className="h3">Qualquer etapa com texto</h3>
              <p>Se uma etapa do sistema depende de ler, escrever ou interpretar texto, ela pode ganhar um modelo de linguagem.</p>
              <a className="ex-more-link" href="#contato">
                Conte qual é a sua
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
            <h2 className="h2">Na stack <em className="mark">que já existe.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Stack: PHP/Laravel, Node.js, Python, React e a tecnologia que o sistema já usa. A IA entra no código atual, sem trocar de plataforma e com custo controlado.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Avaliar uma integração" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Da etapa escolhida à produção.</h2>
            <div className="rich intro">
              <p>A integração só entra no sistema depois de testada com dados reais. Em produção, qualidade e custo seguem monitorados.</p>
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
            <h2 className="h2">Além da <em className="mark">chamada de API.</em></h2>
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
            <h2 className="h2">Que etapa do sistema a IA <em className="mark">pode assumir?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para identificar a etapa, escolher o modelo e estimar o custo de uso.</p>
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
