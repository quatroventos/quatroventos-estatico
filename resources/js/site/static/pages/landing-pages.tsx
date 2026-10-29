// Gerado por scripts/export-static-pages.mjs a partir de /landing-pages (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { LandingCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { placeholderCase } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20um%20or%C3%A7amento%20de%20landing%20page.';

const USES = [
  { title: 'Campanhas', text: 'Um destino próprio para cada anúncio, com a mensagem alinhada ao que trouxe o clique.' },
  { title: 'Lançamentos', text: 'Produto, serviço ou unidade nova apresentados numa página só, com uma ação clara.' },
  { title: 'Captação', text: 'Formulário ou WhatsApp como único caminho, com o contato chegando às ferramentas do time.' },
  { title: 'Eventos', text: 'Programação, local e inscrição reunidos na mesma página.' },
];

const EXAMPLES = [
  { title: 'Campanha de Google Ads', text: 'A mesma promessa do anúncio no topo da página e um formulário curto logo na primeira dobra.' },
  { title: 'Lançamento de produto', text: 'Benefícios, prova e condição de lançamento com prazo definido.' },
  { title: 'Captação para clínica', text: 'Especialidade, profissional e agendamento direto pelo WhatsApp.' },
  { title: 'Pré-venda de imóvel', text: 'Planta, localização e cadastro de interesse antes do lançamento oficial.' },
  { title: 'Inscrição em evento', text: 'Programação, palestrantes e inscrição com pagamento na própria página.' },
  { title: 'Material gratuito', text: 'E-book, planilha ou aula em troca do contato, entregue automaticamente por e-mail.' },
  { title: 'Oferta sazonal', text: 'Black Friday ou data comemorativa com produto, condição e prazo claros.' },
  { title: 'Recrutamento', text: 'Vagas, benefícios e candidatura reunidos numa página só.' },
];

const STEPS = [
  { title: 'Briefing', text: 'Negócio, público, oferta e concorrência.' },
  { title: 'Página', text: 'Layout, texto e integrações montados em até 24 horas.' },
  { title: 'Publicação', text: 'Página no domínio definitivo, com um ano de hospedagem incluso.' },
  { title: 'Acompanhamento', text: 'Resultados medidos e sugestões de melhoria após a publicação.' },
];

const DELIVERABLES = [
  'Página pensada para o público e a concorrência',
  'Responsiva em celular, computador e tablet',
  'Texto escrito para levar ao contato ou à compra',
  'Leve e preparada para buscadores',
  'Integração com WhatsApp, Instagram, e-mail, pagamentos e outras ferramentas',
  'Acompanhamento de resultados e sugestões de melhoria após a publicação',
];

const FAQ = [
  { q: 'Quanto custa?', a: 'A partir de R$ 350,00, com um ano de hospedagem incluso. O valor final depende do conteúdo e das integrações.' },
  { q: 'Qual o prazo?', a: 'A landing page fica pronta em até 24 horas após o briefing.' },
  { q: 'É preciso ter domínio ou hospedagem?', a: 'Não. O registro do domínio e a configuração da hospedagem fazem parte do serviço, e cada contratação inclui um ano de hospedagem.' },
  { q: 'A página é editável?', a: 'Sim. Textos, imagens e cores são editados de forma visual.' },
  { q: 'A página vem otimizada para o Google?', a: 'Com otimizações básicas de SEO: título, descrição, metas e carregamento rápido. Estratégia avançada de SEO não faz parte do escopo.' },
  { q: 'O que está incluso?', a: 'Design exclusivo, textos estratégicos, versão para celular, publicação no domínio, integrações básicas (WhatsApp, tags e formulários) e 30 dias de suporte técnico após a entrega.' },
  { q: 'A Quatro Ventos gerencia anúncios?', a: 'Não. O foco é a página, entregue pronta para divulgação.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageLandingPages(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <LandingCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Cliques convertidos <em className="mark">em clientes.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Landing pages sob medida, desenhadas para conversão e otimizadas para busca, integradas ao processo de atendimento e vendas. Prontas em até 24 horas, a partir de R$ 350,00 com um ano de hospedagem.</p>
              </div>
            </div>
          </div>
          <LeadForm className="hero-form" id="contato" whatsappHref={WHATSAPP} />
        </div>
      </section>
      {/* seção: prose */}
      <section className="section on-light is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-figures is-reveal" ref={revealOnView}>
            <h2 className="figs has-mini">
              <span className="fig"><b>24h</b><small>do briefing à página no ar</small></span>
              <span className="fig"><b><span className="fig-cur">R$</span>350</b><small>a partir de, com um ano de hospedagem incluso</small></span>
              <span className="figs-mini" aria-hidden="true">
                <span className="figs-mini-bar"><i /><i /><i /></span>
                <span className="figs-mini-page"><i /><i /><i /><i /><b>Quero</b></span>
                <span className="figs-mini-live">no ar</span>
              </span>
            </h2>
            <div className="figs-foot">
              <p>Domínio configurado e a página no ar em até 24 horas. A landing page tira o que distrai e leva o visitante a um único passo: o contato ou a compra.</p>
              <div className="actions">
                <WhatsAppCta label="Conversar sobre a página" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: list */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Quando usar uma <em className="mark">landing page.</em></h2>
            <div className="rich intro">
              <p>Sempre que o visitante chega com um objetivo e a página precisa levar a uma única ação.</p>
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
            <h2 className="h2">Uma página para <em className="mark">cada campanha.</em></h2>
            <div className="rich intro">
              <p>Cada objetivo pede uma estrutura diferente. Alguns exemplos de landing pages e do que cada uma precisa ter.</p>
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
              <h3 className="h3">Qualquer oferta com um público definido</h3>
              <p>Se existe uma oferta e um público que chega por anúncio, busca ou indicação, a página é construída para levar à ação.</p>
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
            <h2 className="h2">Processo enxuto.</h2>
            <div className="rich intro">
              <p>Do briefing à página no ar em até 24 horas, com os resultados acompanhados depois da publicação.</p>
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
            <h2 className="h2">Diferenciais de <em className="mark">cada página.</em></h2>
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
      <CaseShowcase
        title="Landing pages no ar."
        intro="Páginas criadas para campanhas, lançamentos e captação, integradas às ferramentas de atendimento de cada cliente."
        items={[placeholderCase('lp-1', ['Campanha']), placeholderCase('lp-2', ['Captação'])]}
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
            <h2 className="h2">Uma landing page pronta <em className="mark">para converter.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para entender o público, a oferta e as ferramentas que a página precisa conectar.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Solicitar landing page" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
