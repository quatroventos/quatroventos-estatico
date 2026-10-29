// Gerado por scripts/export-static-pages.mjs a partir de /diagnostico-de-site (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
import type { StaticPageProps } from '../index';
import { AgentLog } from '../../hero';
import { ContactForm } from '../../sections';

export default function PageDiagnosticoDeSite({ form }: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero">
        <AgentLog />
        <div className="wrap">
          <h1 className="display is-inner">Por que o site não vende?</h1>
          <div className="hero-foot">
            <div className="rich lead">
              <p>Relatório completo em PDF com os problemas de segurança, performance, SEO on-page e velocidade que travam o processo de conversão.</p>
            </div>
          </div>
          <ul className="badges">
            <li>Gratuito</li>
            <li>Relatório em PDF</li>
            <li>Resultado em até 24h</li>
          </ul>
        </div>
      </section>
      {/* seção: list */}
      <section className="section on-light">
        <div className="wrap">
          <header className="sec-head is-solo">
            <h2 className="h2">Escopo do relatório</h2>
          </header>
          <dl className="def-rows">
            <div>
              <dt className="h3">Segurança</dt>
              <dd>
                <div className="rich">
                  <p>Malware, vulnerabilidades, SSL e proteção contra ataques.</p>
                </div>
              </dd>
            </div>
            <div>
              <dt className="h3">Performance</dt>
              <dd>
                <div className="rich">
                  <p>Tempo de carregamento, Core Web Vitals e otimizações necessárias.</p>
                </div>
              </dd>
            </div>
            <div>
              <dt className="h3">SEO on-page</dt>
              <dd>
                <div className="rich">
                  <p>Metas, estrutura de conteúdo, palavras-chave e fatores de rankeamento.</p>
                </div>
              </dd>
            </div>
            <div>
              <dt className="h3">Cache</dt>
              <dd>
                <div className="rich">
                  <p>Cache, compressão e configurações.</p>
                </div>
              </dd>
            </div>
            <div>
              <dt className="h3">Estrutura</dt>
              <dd>
                <div className="rich">
                  <p>Links quebrados, erros 404, redirecionamentos e URLs.</p>
                </div>
              </dd>
            </div>
            <div>
              <dt className="h3">Prioridades</dt>
              <dd>
                <div className="rich">
                  <p>Problemas mais urgentes para a conversão.</p>
                </div>
              </dd>
            </div>
          </dl>
        </div>
      </section>
      {/* seção: contact */}
      <section className="section on-light is-flush" id="contato">
        <div className="wrap">
          <div className="contact-grid">
            <div />
            <div className="form-card">
              <ContactForm form={form} />
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
