// Gerado por scripts/export-static-pages.mjs a partir de /seo-e-llms (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { ReactNode } from 'react';
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { SeoCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { placeholderCase } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20um%20diagn%C3%B3stico%20de%20SEO%20e%20presen%C3%A7a%20em%20IA.';

const USES = [
  { title: 'Google', text: 'Resultados orgânicos para as buscas que trazem cliente.' },
  { title: 'Resumos de IA', text: 'As respostas geradas pelo Google no topo da busca, com os sites usados como fonte.' },
  { title: 'ChatGPT', text: 'Respostas com fontes quando o cliente pergunta ao assistente quem resolve o problema dele.' },
  { title: 'Gemini e Perplexity', text: 'Outros assistentes que pesquisam na web e citam sites como referência.' },
];

const SERVICES = [
  { title: 'SEO técnico', text: 'Velocidade, indexação, sitemap, arquitetura de URLs e Core Web Vitals.' },
  { title: 'Conteúdo', text: 'Páginas e artigos que respondem às perguntas reais do público.' },
  { title: 'Dados estruturados', text: 'Schema.org para organização, serviços, produtos e perguntas frequentes.' },
  { title: 'Otimização para LLMs', text: 'Conteúdo citável, llms.txt e acesso liberado aos rastreadores de IA.' },
  { title: 'Autoridade', text: 'Presença nas fontes que buscadores e IAs usam como referência.' },
  { title: 'SEO local', text: 'Perfil da empresa no Google e páginas por cidade ou região atendida.' },
  { title: 'Migração sem perda', text: 'Redirecionamentos e acompanhamento quando o site muda de endereço ou de plataforma.' },
  { title: 'Medição', text: 'Search Console, posições e menções da marca em respostas de IA.' },
];

const STEPS = [
  { title: 'Diagnóstico', text: 'Auditoria técnica, de conteúdo e de presença nas respostas de IA.' },
  { title: 'Plano', text: 'Prioridades por impacto, com as buscas e perguntas que importam para o negócio.' },
  { title: 'Execução', text: 'Correções técnicas, conteúdo e dados estruturados aplicados no site.' },
  { title: 'Acompanhamento', text: 'Relatório mensal de posições, tráfego e citações.' },
];

const DELIVERABLES = [
  'Auditoria técnica, de conteúdo e de presença em IA',
  'Plano de prioridades por impacto',
  'Correções técnicas aplicadas no site',
  'Dados estruturados em Schema.org',
  'llms.txt e acesso aos rastreadores de IA',
  'Relatório mensal de posições, tráfego e citações',
];

const FAQ: { q: string; a: ReactNode }[] = [
  { q: 'Qual a diferença entre SEO e otimização para LLMs?', a: 'O SEO trabalha a posição nos resultados do Google. A otimização para LLMs trabalha para que o site seja usado como fonte nas respostas do ChatGPT, do Gemini, do Perplexity e dos resumos de IA do Google. A mesma base técnica e de conteúdo atende aos dois.' },
  { q: 'Em quanto tempo aparecem resultados?', a: 'Correções técnicas costumam ter efeito em semanas; conteúdo e autoridade, em meses. O relatório mensal mostra a evolução.' },
  { q: 'Funciona para sites em WordPress?', a: <>Sim. Atendemos sites em WordPress, sites sob medida e lojas virtuais. <a href="/sites-wordpress">Sites em WordPress</a></> },
  { q: 'Dá para começar por um diagnóstico?', a: <>Sim. O diagnóstico gratuito avalia SEO on-page, velocidade e segurança do site. <a href="/diagnostico-de-site">Por que o site não vende?</a></> },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageSeoELlms(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <SeoCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">SEO para o Google e para as <em className="mark">respostas de IA.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Otimização para mecanismos de busca e para LLMs. O site aparece quando o cliente pesquisa no Google e quando pergunta ao ChatGPT, ao Gemini ou ao Perplexity.</p>
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
            <h2 className="h2">Onde o site precisa <em className="mark">aparecer.</em></h2>
            <div className="rich intro">
              <p>A mesma base técnica e de conteúdo faz o site ser encontrado na busca tradicional e citado nas respostas geradas por IA.</p>
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
            <h2 className="h2">O que <em className="mark">fazemos.</em></h2>
            <div className="rich intro">
              <p>As frentes que fazem o site ser encontrado no Google e usado como fonte pelas IAs.</p>
            </div>
          </header>
          <ul className="ex-grid">
            {SERVICES.map((item) => (
              <li key={item.title}>
                <h3 className="h3">{item.title}</h3>
                <p>{item.text}</p>
              </li>
            ))}
            <li className="is-more">
              <h3 className="h3">Por onde começar</h3>
              <p>O diagnóstico mostra onde o site está hoje no Google e nas respostas de IA, e quais frentes trazem resultado primeiro.</p>
              <a className="ex-more-link" href="#contato">
                Pedir um diagnóstico
                <span className="arrow"><Arrow /></span>
              </a>
            </li>
          </ul>
        </div>
      </section>
      {/* seção: prose */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-light is-reveal" ref={revealOnView}>
            <h2 className="h2">A busca também acontece <em className="mark">nas respostas de IA.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Parte das pesquisas já termina numa resposta gerada por IA, sem clique. Para ser citado nessas respostas, o site precisa ser fácil de rastrear, ter conteúdo claro e verificável e ser referenciado por outras fontes. O trabalho combina o SEO tradicional com a otimização para modelos de linguagem.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Pedir um diagnóstico" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Do diagnóstico ao relatório mensal.</h2>
            <div className="rich intro">
              <p>O trabalho começa por um diagnóstico e segue com acompanhamento mensal, para ajustar as prioridades conforme os resultados aparecem.</p>
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
      <CaseShowcase
        title="Resultados de busca."
        intro="Sites que passaram a ser encontrados no Google e citados nas respostas de IA."
        items={[placeholderCase('seo-1', ['SEO']), placeholderCase('seo-2', ['Otimização para LLMs'])]}
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
            <h2 className="h2">Ser encontrado no Google e <em className="mark">citado pela IA.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Mande o endereço do site. O diagnóstico mostra onde ele está hoje no Google e nas respostas de IA, e por onde começar.</p>
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
