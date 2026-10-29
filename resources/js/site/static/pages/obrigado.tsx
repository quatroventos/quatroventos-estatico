// Gerado por scripts/export-static-pages.mjs a partir de /obrigado (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
import type { StaticPageProps } from '../index';
import { AgentLog } from '../../hero';

export default function PageObrigado(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero">
        <AgentLog />
        <div className="wrap">
          <h1 className="display is-inner">Mensagem recebida.</h1>
          <div className="hero-foot">
            <div className="rich lead">
              <p>Retornamos em breve. Para urgências, use o WhatsApp.</p>
            </div>
            <div className="actions">
              <a className="pill lime" href="https://wa.me/554130399593?text=Gostaria%20de%20um%20or%C3%A7amento." target="_blank" rel="noreferrer">Abrir WhatsApp<span className="arrow"><svg className="icon-arrow" viewBox="0 0 16 16" aria-hidden="true"><path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" /></svg></span></a>
              <a className="pill line" href="/">Voltar para o início</a>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
