// Gerado por scripts/export-static-pages.mjs a partir de /desenvolvimento-de-sites (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { ReactNode } from 'react';
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { SitesCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { placeholderCase } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20um%20or%C3%A7amento%20de%20site%20sob%20medida.';

const USES = [
  { title: 'Institucional', text: 'Apresenta a empresa, os serviços e os canais de contato, com conteúdo editável pelo painel.' },
  { title: 'Corporativo', text: 'Várias áreas, unidades ou públicos na mesma estrutura, com navegação pensada para cada um.' },
  { title: 'Captação', text: 'Formulários e WhatsApp ligados ao CRM, para que cada contato chegue ao comercial.' },
  { title: 'Conteúdo', text: 'Blog, notícias e materiais organizados para leitura e para os buscadores.' },
];

const EXAMPLES = [
  { title: 'Escritório de advocacia', text: 'Áreas de atuação, equipe e artigos, com contato direto pelo WhatsApp.' },
  { title: 'Clínica e consultório', text: 'Especialidades, profissionais e agendamento online ligado à agenda.' },
  { title: 'Indústria', text: 'Linhas de produto, catálogo técnico e pedidos de orçamento direto para o comercial.' },
  { title: 'Escola e curso', text: 'Cursos, turmas e matrículas, com área exclusiva para alunos.' },
  { title: 'Construtora e imobiliária', text: 'Empreendimentos, plantas e localização, com os contatos separados por empreendimento.' },
  { title: 'Associação e entidade', text: 'Notícias, associados e eventos, com conteúdo atualizado pela própria equipe.' },
  { title: 'Restaurante e turismo', text: 'Cardápio, reservas e roteiros, com fotos, mapa e idiomas.' },
  { title: 'Portal de conteúdo', text: 'Blog e materiais ricos organizados para leitura e para os buscadores.' },
];

const STEPS = [
  { title: 'Arquitetura', text: 'Comportamento do público estudado e jornada de navegação mapeada.' },
  { title: 'Prototipagem', text: 'Protótipos interativos para validar o site antes da programação.' },
  { title: 'Frontend', text: 'Interface convertida em código limpo e otimizado.' },
  { title: 'Backend', text: 'Parte funcional construída em paralelo, com performance e segurança.' },
  { title: 'Homologação', text: 'Testes em ambiente privado e rodada de ajustes.' },
  { title: 'Entrega', text: 'Publicação no endereço definitivo, com acompanhamento.' },
];

const STANDARDS = [
  'Responsivo em celular, tablet e desktop',
  'Textos, imagens e páginas editados pelo painel',
  'Arquivos e código otimizados para carregar rápido',
  'Layout sob medida, sem template pronto',
  'Estrutura semântica, metas e performance para o Google',
  '30 dias de garantia para ajustes e correções após a entrega',
];

const FAQ: { q: string; a: ReactNode }[] = [
  { q: 'O conteúdo é editável?', a: 'Sim. Todo site é entregue com painel para editar textos, imagens e páginas.' },
  { q: 'O site vem preparado para o Google?', a: 'Sim. Estrutura semântica, metas e performance fazem parte do padrão de entrega.' },
  { q: 'O site se integra ao CRM ou a um agente de IA?', a: 'Sim. Formulários que chegam ao CRM, agente de IA no chat e conteúdo conectado aos sistemas internos são considerados desde a arquitetura.' },
  { q: 'A hospedagem está inclusa?', a: <>Pode estar. Planos em <a href="/hospedagem-de-sites">Hospedagem de sites</a>.</> },
  { q: 'E após a publicação?', a: <>30 dias de garantia e planos de manutenção em <a href="/manutencao-de-sites">Manutenção de sites e WordPress</a>.</> },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageDesenvolvimentoDeSites(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <SitesCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Desenvolvimento de sites <em className="mark">sob medida.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Sites institucionais e corporativos pensados para o público, programados do zero e entregues com painel de gestão de conteúdo.</p>
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
            <h2 className="h2">Um site para <em className="mark">cada objetivo.</em></h2>
            <div className="rich intro">
              <p>A estrutura do site parte do que ele precisa resolver: apresentar a empresa, organizar várias áreas, gerar contatos ou publicar conteúdo.</p>
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
            <h2 className="h2">Sites para <em className="mark">cada segmento.</em></h2>
            <div className="rich intro">
              <p>O conteúdo e as funcionalidades mudam conforme o negócio. Alguns exemplos do que costuma entrar em cada tipo de site.</p>
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
              <h3 className="h3">Qualquer empresa que precisa ser encontrada</h3>
              <p>Se o site precisa explicar o que a empresa faz e transformar visita em contato, a estrutura é desenhada para isso.</p>
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
            <h2 className="h2">Um site integrado <em className="mark">ao processo.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>O site pode ir além da vitrine: um agente de IA que atende no chat, formulários que chegam direto ao CRM, conteúdo conectado aos sistemas internos. O projeto já considera essas integrações desde a arquitetura.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Conversar sobre o site" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Da arquitetura à publicação.</h2>
            <div className="rich intro">
              <p>Cada página passa por checklist de responsividade, velocidade, compatibilidade entre navegadores e funcionalidades.</p>
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
            <h2 className="h2">Padrão de <em className="mark">entrega.</em></h2>
          </header>
          <ul className="checklist">
            {STANDARDS.map((item) => (
              <li key={item}>
                <span className="tick"><svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3.5 8.5l3 3 6-7" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" /></svg></span>
                <span>{item}</span>
              </li>
            ))}
          </ul>
        </div>
      </section>
      <CaseShowcase
        title="Sites no ar."
        intro="Projetos institucionais e corporativos desenvolvidos sob medida, com painel de conteúdo e integrações."
        items={[placeholderCase('site-1', ['Site institucional']), placeholderCase('site-2', ['Site corporativo'])]}
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
            <h2 className="h2">Orçamento de site <em className="mark">sob medida.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para entender o público, o conteúdo e as integrações que o site precisa ter.</p>
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
