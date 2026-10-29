// Gerado por scripts/export-static-pages.mjs a partir de /sites-wordpress (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { ReactNode } from 'react';
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { WpCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { placeholderCase } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20um%20or%C3%A7amento%20de%20site%20em%20WordPress.';

const USES = [
  { title: 'Painel simples', text: 'Textos, imagens e páginas editados pelo próprio time, sem depender de programação.' },
  { title: 'Tema próprio', text: 'Layout desenvolvido para o projeto, sem template pronto.' },
  { title: 'Extensível', text: 'Blog, formulários e integrações adicionados conforme o site cresce.' },
  { title: 'Manutenção', text: 'Atualizações, backups e segurança cobertos pelos planos de manutenção.' },
];

const EXAMPLES = [
  { title: 'Site institucional', text: 'Empresa, serviços e contato, com páginas novas criadas pelo próprio time.' },
  { title: 'Blog e portal de notícias', text: 'Publicação frequente pela equipe, com categorias, autores e newsletter.' },
  { title: 'Loja com WooCommerce', text: 'Produtos, pagamento e frete no mesmo painel do conteúdo.' },
  { title: 'Site multilíngue', text: 'Versões em português, inglês e espanhol gerenciadas no mesmo painel.' },
  { title: 'Área de membros', text: 'Conteúdo restrito por cadastro ou assinatura.' },
  { title: 'Site de eventos', text: 'Programação, palestrantes e inscrições atualizados pela equipe.' },
  { title: 'Catálogo de produtos', text: 'Linhas e fichas técnicas com filtro e pedido de orçamento.' },
  { title: 'Migração para WordPress', text: 'Conteúdo de outro sistema levado para o WordPress sem perder endereços nem posições no Google.' },
];

const STEPS = [
  { title: 'Arquitetura', text: 'Comportamento do público estudado e jornada de navegação mapeada.' },
  { title: 'Prototipagem', text: 'Protótipos interativos para validar o site antes da programação.' },
  { title: 'Frontend', text: 'Interface convertida em tema próprio, com código limpo e otimizado.' },
  { title: 'Backend', text: 'Blocos, campos e painel configurados para o time editar com segurança.' },
  { title: 'Homologação', text: 'Testes em ambiente privado e rodada de ajustes.' },
  { title: 'Entrega', text: 'Publicação no endereço definitivo, com acompanhamento.' },
];

const STANDARDS = [
  'Responsivo em celular, tablet e desktop',
  'Textos, imagens e páginas editados pelo painel do WordPress',
  'Arquivos e código otimizados para carregar rápido',
  'Tema próprio, sem template pronto',
  'Estrutura semântica, metas e performance para o Google',
  '30 dias de garantia para ajustes e correções após a entrega',
];

const FAQ: { q: string; a: ReactNode }[] = [
  { q: 'O site é feito com template?', a: 'Não. O layout é desenvolvido sob medida, sem template pronto.' },
  { q: 'Eu mesmo consigo editar o site?', a: 'Sim. Textos, imagens e páginas são editados pelo painel do WordPress.' },
  { q: 'O site inclui manutenção?', a: <>30 dias de garantia. Depois, os planos de manutenção cobrem atualizações, backups e segurança. <a href="/manutencao-de-sites">Manutenção de sites e WordPress</a></> },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageSitesWordpress(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <WpCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Sites em <em className="mark">WordPress.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Sites sob medida em WordPress, com tema próprio e simples de gerenciar pelo próprio time.</p>
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
            <h2 className="h2">Por que <em className="mark">WordPress.</em></h2>
            <div className="rich intro">
              <p>Para sites que precisam ser atualizados com frequência pelo próprio time, com um layout que não parece de template.</p>
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
            <h2 className="h2">O que dá para fazer <em className="mark">em WordPress.</em></h2>
            <div className="rich intro">
              <p>Do site institucional à loja, sempre com o conteúdo nas mãos de quem atualiza. Alguns exemplos.</p>
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
              <h3 className="h3">Qualquer site que muda com frequência</h3>
              <p>Se o time precisa publicar e atualizar sem esperar por programação, o WordPress com tema próprio resolve.</p>
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
            <h2 className="h2">Tema próprio, <em className="mark">painel simples.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Layout próprio, painel para editar o conteúdo e 30 dias de garantia para ajustes e correções após a entrega.</p>
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
        title="Sites em WordPress no ar."
        intro="Projetos com tema próprio, painel organizado para o time e manutenção em dia."
        items={[placeholderCase('wp-1', ['WordPress']), placeholderCase('wp-2', ['WooCommerce'])]}
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
            <h2 className="h2">WordPress sob medida, <em className="mark">feito com método.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para entender o conteúdo, as páginas e as integrações que o site precisa ter.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Pedir orçamento" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
