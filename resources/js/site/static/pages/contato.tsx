// Gerado por scripts/export-static-pages.mjs a partir de /contato (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
import type { CSSProperties, ReactNode } from 'react';
import type { StaticPageProps } from '../index';
import { AgentLog, Rotating, trackLens } from '../../hero';
import { ContactForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20um%20or%C3%A7amento.';

const CHANNELS: { title: string; value: string; href: string; external?: boolean; glyph: ReactNode }[] = [
  {
    title: 'WhatsApp e telefone',
    value: '(41) 3039-9593',
    href: WHATSAPP,
    external: true,
    glyph: (
      <>
        <path d="M20 6a14 14 0 0 0-12 21.2L6 34l7-1.9A14 14 0 1 0 20 6Z" pathLength={1} />
        <path d="M15 15c0 6 4 10 10 10" pathLength={1} style={{ '--j': 1 } as CSSProperties} />
      </>
    ),
  },
  {
    title: 'E-mail',
    value: 'contato@quatroventos.com.br',
    href: 'mailto:contato@quatroventos.com.br',
    glyph: (
      <>
        <rect x="5" y="9" width="30" height="22" rx="4" pathLength={1} />
        <path d="m6 11 14 11 14-11" pathLength={1} style={{ '--j': 1 } as CSSProperties} />
      </>
    ),
  },
  {
    title: 'Suporte para clientes',
    value: 'Abertura de chamado no suporte',
    href: 'https://quatroventos.freshdesk.com/support/home',
    external: true,
    glyph: (
      <>
        <path d="M8 23v-3a12 12 0 0 1 24 0v3" pathLength={1} />
        <path d="M8 22h4v9H8a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2ZM32 22h-4v9h4a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2Z" pathLength={1} style={{ '--j': 1 } as CSSProperties} />
      </>
    ),
  },
];

export default function PageContato({ form }: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero is-home is-lens" onPointerMove={trackLens}>
        <AgentLog />
        <div className="wrap">
          <h1 className="display">
            <span className="sr-only">Vamos conversar sobre o seu projeto.</span>
            <span aria-hidden="true">Vamos conversar sobre<Rotating items={['o seu projeto.', 'inteligência artificial.', 'automação de processos.', 'sistemas sob medida.']} /></span>
          </h1>
          <div className="hero-foot">
            <div className="rich lead">
              <p>Descreva o processo ou o projeto. Retornamos com o próximo passo.</p>
            </div>
            <div className="actions">
              <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
                Chamar no WhatsApp
                <span className="arrow"><Arrow /></span>
              </a>
              <a className="pill line" href="#contato">Enviar uma mensagem</a>
            </div>
          </div>
        </div>
      </section>
      {/* seção: canais */}
      <section className="section on-light is-mark-draw">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Canais de <em className="mark">atendimento.</em></h2>
            <div className="rich intro">
              <p>Escolha o canal mais prático. Clientes com contrato ativo abrem chamado direto no suporte.</p>
            </div>
          </header>
          <ul className="ex-grid is-glyphs is-links" ref={revealOnView}>
            {CHANNELS.map((item, i) => (
              <li key={item.title} style={{ '--i': i } as CSSProperties}>
                <svg className="pr-glyph" viewBox="0 0 40 40" aria-hidden="true">{item.glyph}</svg>
                <h3 className="h3">
                  <a href={item.href} {...(item.external ? { target: '_blank', rel: 'noreferrer' } : {})}>{item.title}</a>
                </h3>
                <p>{item.value}</p>
              </li>
            ))}
          </ul>
        </div>
      </section>
      {/* seção: contact */}
      <section className="section on-dark" id="contato">
        <div className="wrap contact-split">
          <div>
            <h2 className="h2">Envie uma <em className="mark">mensagem.</em></h2>
            <div className="rich lead">
              <p>Conte qual processo ou projeto quer melhorar. Avaliamos o caso e indicamos por onde começar.</p>
              <p>Curitiba, PR</p>
            </div>
          </div>
          <div className="form-card is-dark">
            <ContactForm form={form} />
          </div>
        </div>
      </section>
    </>
  );
}
