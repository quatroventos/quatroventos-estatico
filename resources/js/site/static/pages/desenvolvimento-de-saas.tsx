// Gerado por scripts/export-static-pages.mjs a partir de /desenvolvimento-de-saas (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { SaasCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { SHOWCASE } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20o%20desenvolvimento%20de%20um%20SaaS.';

const USES = [
  { title: 'SaaS vertical', text: 'Produto para um setor específico, com as regras do negócio que as ferramentas genéricas não cobrem.' },
  { title: 'Ferramenta interna', text: 'O sistema que já resolve o problema da sua empresa, preparado para ser vendido a outras.' },
  { title: 'White label', text: 'Plataforma revendida com a marca, o domínio e a configuração de cada cliente.' },
  { title: 'Produto com IA', text: 'Inteligência artificial no centro do produto, com o custo de uso controlado no plano de cada cliente.' },
];

const EXAMPLES = [
  { title: 'Gestão para clínicas', text: 'Agenda, prontuário, cobrança e lembretes ao paciente, com uma conta isolada por clínica.' },
  { title: 'Plataforma de cursos', text: 'Alunos, turmas, conteúdo e certificados, com cobrança por assinatura ou por matrícula.' },
  { title: 'SDR com IA', text: 'Atendimento e qualificação de leads 24 horas, com painel de conversas para cada empresa cliente.' },
  { title: 'Gerador de sites com IA', text: 'Páginas criadas e publicadas a partir de uma conversa, com domínio e hospedagem incluídos no plano.' },
  { title: 'Gestão de frotas', text: 'Veículos, motoristas, manutenção e custos por empresa, com acesso pelo celular para quem está em campo.' },
  { title: 'Portal de condomínios', text: 'Comunicados, reservas, boletos e ocorrências, com um espaço próprio para cada condomínio.' },
  { title: 'Propostas comerciais', text: 'Modelos, envio, assinatura e acompanhamento de propostas, com limite de usuários por plano.' },
  { title: 'Relatórios white label', text: 'Painéis de indicadores entregues aos clientes finais com a marca e o domínio de cada revenda.' },
];

const STEPS = [
  { title: 'Discovery', text: 'Problema, público e modelo de negócio.' },
  { title: 'MVP', text: 'O mínimo que já resolve e já gera receita.' },
  { title: 'Lançamento', text: 'Infraestrutura, monitoramento e suporte.' },
  { title: 'Evolução', text: 'Roadmap guiado pelo uso real.' },
];

const SCOPE = [
  'Arquitetura multi-tenant e isolamento de dados',
  'Cadastro, login, perfis e permissões',
  'Planos, assinatura e cobrança recorrente',
  'Painel administrativo e métricas de uso',
  'APIs e integrações para clientes',
  'Funcionalidades de IA no produto',
];

const FAQ = [
  { q: 'O que é arquitetura multi-tenant?', a: 'Uma única aplicação atende várias empresas clientes, cada uma enxergando apenas os próprios dados. O isolamento é definido na arquitetura, antes da primeira tela.' },
  { q: 'Por que começar por um MVP?', a: 'Para colocar no ar o mínimo que já resolve o problema e já gera receita, e decidir as próximas funcionalidades a partir do uso real, não de suposições.' },
  { q: 'A cobrança recorrente fica dentro do produto?', a: 'Sim. Planos, assinatura e cobrança recorrente fazem parte do escopo, integrados a um meio de pagamento escolhido no projeto.' },
  { q: 'Quem cuida do produto depois do lançamento?', a: 'O mesmo time. O lançamento inclui infraestrutura, monitoramento e suporte, e a evolução segue um roadmap guiado pelo uso.' },
  { q: 'Dá para colocar IA no produto?', a: 'Sim, quando o produto pede. Funcionalidades de IA entram como parte do escopo, com o modelo escolhido pelo equilíbrio entre custo e qualidade.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageDesenvolvimentoDeSaas(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <SaasCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Desenvolvimento <em className="mark">de SaaS.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Do MVP ao produto com clientes pagantes. Arquitetura multi-tenant, autenticação, planos e cobrança, painel administrativo e IA embarcada quando o produto pede.</p>
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
            <h2 className="h2">Tipos de <em className="mark">SaaS.</em></h2>
            <div className="rich intro">
              <p>Os formatos de produto por assinatura que mais aparecem quando uma solução pode atender muitas empresas ao mesmo tempo.</p>
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
            <h2 className="h2">Exemplos de <em className="mark">produtos.</em></h2>
            <div className="rich intro">
              <p>Problemas que se repetem em muitas empresas e que podem ser resolvidos por um produto cobrado todo mês.</p>
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
              <h3 className="h3">Qualquer produto por assinatura</h3>
              <p>Se o problema aparece em muitas empresas do mesmo jeito e a solução pode ser cobrada todo mês, ele pode virar um SaaS.</p>
              <a className="ex-more-link" href="#contato">
                Conte qual é a sua ideia
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
            <h2 className="h2">A base decidida <em className="mark">antes da primeira tela.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Isolamento de dados, permissões e cobrança são definidos na arquitetura do MVP. Assim o produto ganha clientes e funcionalidades sem precisar refazer a base.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Conversar sobre o produto" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Da ideia ao produto em uso.</h2>
            <div className="rich intro">
              <p>O produto vai ao ar no menor formato que já resolve o problema e evolui a partir do que os clientes realmente usam.</p>
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
          <header className="sec-head">
            <h2 className="h2">O escopo de <em className="mark">um SaaS.</em></h2>
            <div className="rich intro">
              <p>Além da funcionalidade principal, um produto vendido por assinatura precisa de uma base que atenda vários clientes, controle acessos e cobre todo mês.</p>
            </div>
          </header>
          <ul className="checklist">
            {SCOPE.map((item) => (
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
        title="SaaS em produção."
        intro="Produtos por assinatura que construímos e que estão no ar."
        items={[SHOWCASE.bia, SHOWCASE['firefly-webmaker'], SHOWCASE.seucommerce]}
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
            <h2 className="h2">Um SaaS pronto para <em className="mark">sair do papel?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para entender o problema, o público e o modelo de negócio, e definir o que entra no MVP.</p>
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
