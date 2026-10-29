// Gerado por scripts/export-static-pages.mjs a partir de /qa-e-testes (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { QaCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20uma%20rodada%20de%20testes%20no%20meu%20sistema.';

const USES = [
  { title: 'Funcionalidades', text: 'Fluxos e regras de negócio conferidos do jeito que o usuário vai usar.' },
  { title: 'Integrações', text: 'APIs e sistemas externos trocando dados como esperado.' },
  { title: 'Navegadores', text: 'O mesmo comportamento nos navegadores que o público usa.' },
  { title: 'Dispositivos', text: 'Layout, toque e leitura conferidos no celular, no tablet e no desktop.' },
];

const EXAMPLES = [
  { title: 'Cálculo errado', text: 'Frete, desconto ou imposto calculado fora da regra em um caso específico que ninguém conferiu.' },
  { title: 'Integração silenciosa', text: 'A API externa muda o formato e o pedido deixa de chegar ao ERP sem nenhum aviso.' },
  { title: 'Quebra no celular', text: 'Botão escondido atrás do teclado ou tabela que não cabe na tela do celular.' },
  { title: 'Regressão', text: 'Uma correção nova quebra uma funcionalidade que já funcionava na versão anterior.' },
  { title: 'Permissão indevida', text: 'Um perfil enxerga ou altera dados que não deveria acessar.' },
  { title: 'Lentidão com volume', text: 'A tela abre rápido com dez registros e trava com dez mil.' },
  { title: 'Formulário que não envia', text: 'Validação que bloqueia um dado válido, como CEP ou telefone em outro formato.' },
  { title: 'E-mail que não chega', text: 'Confirmação, recuperação de senha ou nota que nunca sai da fila de envio.' },
];

const STEPS = [
  { title: 'Planejamento', text: 'Fluxos críticos, ambientes e o que precisa ser coberto.' },
  { title: 'Execução', text: 'Testes manuais e automatizados sobre a versão a publicar.' },
  { title: 'Registro', text: 'Cada falha descrita com os passos para reproduzir.' },
  { title: 'Homologação', text: 'Correções retestadas e validadas com o cliente.' },
];

const DELIVERABLES = [
  'Testes funcionais e de regressão',
  'Testes automatizados',
  'Testes de integração e de API',
  'Testes em navegadores e dispositivos',
  'Testes de performance e carregamento',
  'Homologação com o cliente',
];

const FAQ = [
  { q: 'Testes manuais ou automatizados?', a: 'Os dois. Os automatizados cobrem a regressão e os fluxos que se repetem a cada versão. Os manuais cobrem usabilidade, navegadores e dispositivos.' },
  { q: 'Em que momento os testes entram?', a: 'Antes de cada publicação e sempre que uma mudança toca um fluxo crítico. A homologação com o cliente fecha a rodada.' },
  { q: 'O que o teste de performance mede?', a: 'Como o sistema se comporta com volume de uso e quanto as páginas levam para carregar.' },
  { q: 'Os testes valem para integrações?', a: 'Sim. Testes de integração e de API conferem a troca de dados com sistemas externos, onde um erro costuma passar despercebido até a produção.' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageQaETestes(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <QaCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">QA e testes <em className="mark">de software.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Bug em produção interrompe o processo e custa mais caro. Funcionalidades, integrações, navegadores e dispositivos testados antes de chegar ao usuário.</p>
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
            <h2 className="h2">O que é <em className="mark">testado.</em></h2>
            <div className="rich intro">
              <p>Quatro frentes por onde um erro pode chegar ao usuário. Cada uma é conferida antes da publicação.</p>
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
            <h2 className="h2">Falhas que o teste <em className="mark">encontra antes.</em></h2>
            <div className="rich intro">
              <p>Erros comuns que passam pelo desenvolvimento e só aparecem quando o usuário já está usando o sistema.</p>
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
              <h3 className="h3">Qualquer erro que chegaria ao usuário</h3>
              <p>Uma falha encontrada em produção custa mais caro e interrompe o processo. A rodada de testes encontra antes da publicação.</p>
              <a className="ex-more-link" href="#contato">
                Pedir uma rodada
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
            <h2 className="h2">Nada vai ao ar <em className="mark">sem checklist.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Todo projeto da Quatro Ventos passa por checklist antes da publicação: links, funcionalidades, responsividade, velocidade e compatibilidade entre navegadores.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Pedir uma rodada de testes" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Da versão nova à publicação segura.</h2>
            <div className="rich intro">
              <p>Cada rodada termina com as falhas corrigidas, retestadas e aprovadas por quem vai usar o sistema.</p>
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
            <h2 className="h2">O que a cobertura <em className="mark">inclui.</em></h2>
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
            <h2 className="h2">O sistema precisa de uma <em className="mark">rodada de testes?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Conte qual sistema vai ao ar e o que muda na próxima versão. Indicamos o que testar primeiro.</p>
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
