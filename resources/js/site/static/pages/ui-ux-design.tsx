// Gerado por scripts/export-static-pages.mjs a partir de /ui-ux-design (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { UxCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { SHOWCASE } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conversar%20sobre%20UI%2FUX%20design.';

const USES = [
  { title: 'Produto novo', text: 'A ideia vira fluxo e protótipo antes do investimento em desenvolvimento.' },
  { title: 'Sistema existente', text: 'Telas e fluxos que geram dúvida, erro ou retrabalho são redesenhados.' },
  { title: 'Site e e-commerce', text: 'Arquitetura de informação e interface pensadas para quem vai navegar e comprar.' },
  { title: 'Design recebido', text: 'O layout pronto, do Figma ou de outro time, implementado fiel ao que foi aprovado.' },
];

const EXAMPLES = [
  { title: 'Landing page de produto', text: 'Mensagem, prova e chamada para ação organizadas e testadas no protótipo antes do lançamento.' },
  { title: 'Site institucional', text: 'Páginas e conteúdos estruturados para quem procura a empresa e precisa encontrar rápido.' },
  { title: 'Checkout de e-commerce', text: 'Etapas, campos e mensagens de erro desenhados para reduzir o abandono do carrinho.' },
  { title: 'Onboarding de SaaS', text: 'Os primeiros passos do usuário até o primeiro resultado dentro do produto.' },
  { title: 'Painel administrativo', text: 'Tabelas, filtros e ações em massa para quem opera o sistema todos os dias.' },
  { title: 'Agendamento online', text: 'Escolha de serviço, horário e confirmação em poucos toques, no celular.' },
  { title: 'Portal do cliente', text: 'Área logada com pedidos, documentos e solicitações reunidos em um só lugar.' },
  { title: 'Design system', text: 'Componentes, cores e tipografia documentados para o time reutilizar em novas telas.' },
];

const STEPS = [
  { title: 'Discovery', text: 'Objetivos, usuários e restrições.' },
  { title: 'Arquitetura de informação', text: 'Estrutura, navegação e fluxos.' },
  { title: 'Wireframes', text: 'O esqueleto das telas.' },
  { title: 'Protótipo navegável', text: 'Validação antes do código.' },
  { title: 'Design de interface', text: 'Visual final e sistema de componentes.' },
];

const DELIVERABLES = [
  'Objetivos, usuários e restrições documentados',
  'Arquitetura de informação e fluxos',
  'Wireframes das telas',
  'Protótipo navegável para validação',
  'Interface final e sistema de componentes',
  'Implementação fiel ao design aprovado',
];

const FAQ = [
  { q: 'O que é discovery?', a: 'A etapa em que objetivos, usuários e restrições do produto são levantados antes de desenhar qualquer tela.' },
  { q: 'Qual a diferença entre wireframe e protótipo?', a: 'O wireframe é o esqueleto das telas: estrutura e conteúdo, sem o visual final. O protótipo é navegável e permite validar o fluxo com quem vai usar antes do código.' },
  { q: 'Por que validar antes de programar?', a: 'Ajustar um protótipo é mais rápido do que refazer telas já programadas. A validação com quem vai usar reduz retrabalho no desenvolvimento.' },
  { q: 'O mesmo time desenha e desenvolve?', a: 'Sim. Por isso o protótipo já nasce viável e a interface publicada é fiel ao que foi aprovado.' },
  { q: 'Vocês desenvolvem a partir de um design que já temos?', a: 'Sim. Recebemos o layout pronto, em Figma ou outra ferramenta, e implementamos o site ou sistema fiel a ele, em Laravel, WordPress ou na stack que o projeto pedir.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageUiUxDesign(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <UxCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">UI/UX design e <em className="mark">discovery de produto.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Antes de programar, entender. O problema é pesquisado, o fluxo desenhado e as telas validadas com quem vai usar.</p>
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
            <h2 className="h2">Onde o design <em className="mark">entra.</em></h2>
            <div className="rich intro">
              <p>Em qualquer projeto em que alguém precise usar uma tela para chegar a um resultado: do produto que ainda é ideia ao sistema que o time usa todo dia.</p>
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
            <h2 className="h2">Exemplos de <em className="mark">telas.</em></h2>
            <div className="rich intro">
              <p>Interfaces em que um fluxo bem desenhado muda o resultado: mais vendas, menos chamados e menos erro de quem opera.</p>
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
              <h3 className="h3">Qualquer tela que gera dúvida</h3>
              <p>Se uma tela gera erro, chamado ou retrabalho, ela pode ser redesenhada e validada antes de voltar para o código.</p>
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
            <h2 className="h2">Design que <em className="mark">vira código.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Como o mesmo time desenha e desenvolve, o protótipo já nasce viável e a interface publicada é fiel ao que foi aprovado. Quando o design chega pronto, ele é implementado com a mesma fidelidade.</p>
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
            <h2 className="h2">Do problema à interface.</h2>
            <div className="rich intro">
              <p>As telas só seguem para o código depois de validadas no protótipo com quem vai usar.</p>
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
      {/* seção: cases */}
      <CaseShowcase
        title="Do design recebido ao site no ar."
        intro="Layouts entregues pelos clientes, implementados fiéis ao design, com o CMS e a stack que cada projeto pedia."
        items={[SHOWCASE['ramos-benedetti'], SHOWCASE.produtive]}
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
            <h2 className="h2">Um produto pronto <em className="mark">para ser desenhado?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para entender o problema, quem vai usar e por onde começar o discovery.</p>
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
