// Gerado por scripts/export-static-pages.mjs a partir de /modernizacao-de-sistemas (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { CaseShowcase } from '../../case-showcase';
import { ModernizeCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { SHOWCASE } from '../showcase';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20pedir%20uma%20auditoria%20de%20sistema%20legado.';

const SIGNS = [
  { title: 'Lento', text: 'Telas que demoram, relatórios que travam e rotinas que só rodam fora do horário de trabalho.' },
  { title: 'Difícil de alterar', text: 'Cada mudança simples vira semanas de trabalho e o risco de quebrar outra parte do sistema.' },
  { title: 'Sem suporte', text: 'Linguagem, framework ou servidor em versões que não recebem mais correções de segurança.' },
  { title: 'Dependente', text: 'Só uma pessoa entende o código, e a operação para quando ela não está.' },
];

const PATHS = [
  { title: 'Refatoração gradual', text: 'O código é reorganizado por partes, com o sistema em uso, começando pelos trechos que mais travam alterações.' },
  { title: 'Migração de framework ou linguagem', text: 'Saída de versões sem suporte para uma base atual, módulo a módulo.' },
  { title: 'APIs e novo frontend', text: 'As regras de negócio passam a ser expostas por APIs, e a interface é refeita sobre elas.' },
  { title: 'Migração de dados e infraestrutura', text: 'Banco de dados, servidores e deploy levados para um ambiente atual, com validação dos dados migrados.' },
  { title: 'IA em fluxos existentes', text: 'Etapas manuais do sistema, como ler documentos ou classificar solicitações, assumidas por IA.' },
];

const STEPS = [
  { title: 'Auditoria', text: 'Código, segurança, performance, dados e infraestrutura avaliados.' },
  { title: 'Priorização', text: 'Correções rápidas e plano ordenado pelo impacto.' },
  { title: 'Modernização', text: 'Execução por etapas, sem parar a operação.' },
  { title: 'Validação', text: 'Cada parte substitui a anterior só depois de validada.' },
];

const AUDIT = [
  'Arquitetura e qualidade do código',
  'Segurança e versões desatualizadas',
  'Performance e gargalos',
  'Banco de dados e integrações',
  'Infraestrutura e processo de deploy',
  'Relatório com correções rápidas e plano priorizado',
];

const FAQ = [
  { q: 'É preciso parar o sistema para modernizar?', a: 'Não. A modernização é feita por etapas, com o sistema atual em uso, e cada parte só substitui a anterior depois de validada.' },
  { q: 'Vale mais refatorar ou reescrever?', a: 'Depende do estado do código e do que o negócio precisa. A auditoria aponta os riscos de cada caminho e o plano de modernização prioriza o que traz mais resultado primeiro.' },
  { q: 'O sistema foi feito por outra empresa. Vocês assumem?', a: 'Sim. O trabalho começa pela auditoria técnica, justamente para entender o código, a infraestrutura e os riscos antes de qualquer alteração.' },
  { q: 'O que recebo ao final da auditoria?', a: 'Um relatório com correções rápidas e um plano de modernização priorizado.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageModernizacaoDeSistemas(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <ModernizeCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Modernização de <em className="mark">sistemas legados.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Sistema lento, difícil de alterar, preso a uma versão antiga ou dependente de uma única pessoa trava o processo inteiro. Auditamos o código, priorizamos o que importa e modernizamos sem parar a operação.</p>
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
            <h2 className="h2">Quando <em className="mark">modernizar.</em></h2>
            <div className="rich intro">
              <p>Os sinais que mais aparecem antes de um pedido de auditoria. Em geral, o sistema ainda atende, mas ninguém quer mexer nele.</p>
            </div>
          </header>
          <ul className="uses-band">
            {SIGNS.map((item) => (
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
            <h2 className="h2">Caminhos de <em className="mark">modernização.</em></h2>
            <div className="rich intro">
              <p>O caminho sai da auditoria. Em muitos sistemas, mais de um entra no plano, em ordem de prioridade.</p>
            </div>
          </header>
          <ul className="ex-grid">
            {PATHS.map((item) => (
              <li key={item.title}>
                <h3 className="h3">{item.title}</h3>
                <p>{item.text}</p>
              </li>
            ))}
            <li className="is-more">
              <h3 className="h3">Qual caminho vem primeiro</h3>
              <p>A auditoria mostra o que trava mais a operação e define a ordem: o que corrigir já e o que modernizar depois.</p>
              <a className="ex-more-link" href="#contato">
                Pedir auditoria
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
            <h2 className="h2">Modernizar <em className="mark">sem parar a operação.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>O sistema continua atendendo enquanto é modernizado. Cada etapa entra em produção separadamente, depois de validada, começando pelo que mais trava o processo.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Pedir auditoria" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Do diagnóstico ao sistema atualizado.</h2>
            <div className="rich intro">
              <p>Nenhuma alteração começa antes da auditoria. O plano define o que corrigir primeiro e o que modernizar depois.</p>
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
            <h2 className="h2">Escopo da <em className="mark">auditoria.</em></h2>
            <div className="rich intro">
              <p>O primeiro passo de qualquer modernização. Ao final, um relatório com correções rápidas e um plano priorizado.</p>
            </div>
          </header>
          <ul className="checklist">
            {AUDIT.map((item) => (
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
        title="Sistemas antigos, renovados."
        intro="Calculadoras técnicas reconstruídas na web a partir dos sistemas antigos dos clientes."
        items={[SHOWCASE.moltec, SHOWCASE.cyclolux]}
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
            <h2 className="h2">O sistema atual está <em className="mark">travando o processo?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Uma conversa basta para entender o sistema, os sintomas e o que a auditoria precisa cobrir.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Pedir auditoria" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
