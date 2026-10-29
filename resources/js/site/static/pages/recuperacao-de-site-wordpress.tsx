// Gerado por scripts/export-static-pages.mjs a partir de /recuperacao-de-site-wordpress (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { ReactNode } from 'react';
import type { StaticPageProps } from '../index';
import { RecoverCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Meu%20site%20WordPress%20foi%20invadido%20ou%20est%C3%A1%20fora%20do%20ar.';

const USES = [
  { title: 'Popups e anúncios', text: 'Janelas e anúncios que ninguém da empresa colocou no site.' },
  { title: 'Redirecionamentos', text: 'Visitantes levados para outros endereços ao abrir uma página.' },
  { title: 'Envios suspeitos', text: 'E-mails e spam saindo do servidor sem que ninguém tenha enviado.' },
  { title: 'Site quebrado', text: 'Página em branco, erro no painel ou o site fora do ar.' },
];

const STEPS = [
  { title: 'Análise', text: 'Identificação da invasão e do que foi afetado.' },
  { title: 'Limpeza', text: 'Remoção do malware e de arquivos comprometidos.' },
  { title: 'Correção', text: 'Atualização do que estava vulnerável.' },
  { title: 'Restauração', text: 'Site de volta ao ar, testado.' },
  { title: 'Proteção', text: 'Plano de manutenção para evitar reincidência.' },
];

const DELIVERABLES = [
  'Malware e arquivos comprometidos removidos',
  'WordPress, plugins e tema atualizados',
  'Senhas de acesso trocadas',
  'Site testado antes de voltar ao ar',
  'Relatório do que foi afetado e corrigido',
  'Indicação do plano de manutenção para evitar reincidência',
];

const OTHER = [
  { title: 'Site lento', text: 'Correção do que faz o visitante abandonar a página.' },
  { title: 'Malware', text: 'Limpeza de popups, redirecionamentos e envios suspeitos.' },
  { title: 'Migração', text: 'Troca de hospedagem com tudo ajustado e funcionando.' },
  { title: 'Site quebrado', text: 'Restauração do site no ar.' },
  { title: 'Problemas complexos', text: 'Diagnóstico e solução.' },
];

const FAQ: { q: string; a: ReactNode }[] = [
  { q: 'Em quanto tempo o site volta ao ar?', a: 'Depende da extensão da invasão e do estado dos backups. A análise mostra o que foi afetado e qual o caminho mais curto para restaurar com segurança.' },
  { q: 'Preciso ter backup?', a: 'Ajuda, mas não é obrigatório. Sem um backup limpo, a recuperação parte da limpeza dos arquivos e do banco de dados atuais.' },
  { q: 'Basta restaurar um backup antigo?', a: 'Não. Se a falha que permitiu a invasão continuar aberta, o site pode ser invadido de novo. Por isso a recuperação inclui corrigir o que estava vulnerável.' },
  { q: 'Como evitar uma nova invasão?', a: <>Com WordPress, plugins e tema atualizados, backups e monitoramento. É o que cobre o plano de manutenção. <a href="/manutencao-de-sites">Manutenção de sites</a></> },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageRecuperacaoDeSiteWordpress(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <RecoverCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Recuperação de site <em className="mark">WordPress.</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Recuperação de sites WordPress invadidos, limpeza de malware e restauração segura do site no ar.</p>
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
            <h2 className="h2">Sinais de que o site <em className="mark">foi invadido.</em></h2>
            <div className="rich intro">
              <p>Nem toda invasão derruba o site. Alguns sinais aparecem antes, e cada um pede a mesma resposta: começar a recuperação.</p>
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
      {/* seção: prose */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-light is-reveal" ref={revealOnView}>
            <h2 className="h2">Tempo é <em className="mark">decisivo.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Um site invadido afeta vendas, reputação e posicionamento no Google. Quanto antes a recuperação começa, menor o dano.</p>
              </div>
              <div className="prose-price">
                <p className="prose-price-from">A partir de</p>
                <p className="prose-price-value">R$ 450,00</p>
                <p className="prose-price-term">Site recuperado em até 24 horas.*</p>
                <p className="prose-price-note">*Desde que informados os acessos corretos.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Começar a recuperação" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Da invasão ao site protegido.</h2>
            <div className="rich intro">
              <p>A recuperação remove a causa, não só o sintoma. O site volta ao ar testado e com o que estava vulnerável corrigido.</p>
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
            <h2 className="h2">O que a recuperação <em className="mark">entrega.</em></h2>
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
      {/* seção: exemplos */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Outros <em className="mark">atendimentos.</em></h2>
            <div className="rich intro">
              <p>Nem todo problema é invasão. O mesmo time resolve o que deixa o site lento, quebrado ou sem hospedagem.</p>
            </div>
          </header>
          <ul className="ex-grid">
            {OTHER.map((item) => (
              <li key={item.title}>
                <h3 className="h3">{item.title}</h3>
                <p>{item.text}</p>
              </li>
            ))}
            <li className="is-more">
              <h3 className="h3">Outro problema no site</h3>
              <p>Conte o que está acontecendo. A análise mostra a causa e o caminho para resolver.</p>
              <a className="ex-more-link" href="#contato">
                Descrever o problema
                <span className="arrow"><Arrow /></span>
              </a>
            </li>
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
            <h2 className="h2">Site fora do ar <em className="mark">agora?</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Mande o endereço do site pelo WhatsApp. A análise começa pelo que foi afetado.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Falar no WhatsApp" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
