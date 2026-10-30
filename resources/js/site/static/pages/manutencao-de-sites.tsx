// Gerado por scripts/export-static-pages.mjs a partir de /manutencao-de-sites (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import { useState, type ReactNode } from 'react';
import type { StaticPageProps } from '../index';
import { MaintainCanvas } from '../../hero';
import { LeadForm } from '../../sections';
import { Arrow, revealOnView } from '../../ui';
import { BillingSwitch, PlanBase, PlanCard, type Plan } from '../plans';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20um%20plano%20de%20manuten%C3%A7%C3%A3o%20para%20o%20meu%20site.';

const PLAN_BASE = [
  'Helpdesk por WhatsApp, portal do cliente e e-mail',
  'Backups do site e do banco de dados na nuvem',
  'Checagens de disponibilidade',
  'Inspeções visuais do site',
  'Horas técnicas para alterações e melhorias',
  'Atendimento de quem desenvolve',
];

const PLANS: Plan[] = [
  { name: 'Basic', monthly: 350, annual: 3500, from: 'Para começar', adds: ['Backups semanais', 'Checagem de disponibilidade diária', 'Inspeção visual mensal', '1h técnica por mês'] },
  { name: 'Standard', monthly: 520, annual: 5200, featured: true, from: 'Tudo do Basic, com', adds: ['Checagem de disponibilidade horária', 'Inspeção visual semanal', '3h técnicas por mês', 'Monitoramento de segurança'] },
  { name: 'Pro', monthly: 990, annual: 9900, from: 'Tudo do Standard, com', adds: ['Atendimento prioritário', 'Backups diários', 'Inspeção visual duas vezes por semana', '6h técnicas por mês', 'Testes de checkout semanais'] },
];

const PREVENTIVE = [
  { title: 'Atualizações', text: 'Mantemos o site sempre atualizado com a versão mais recente do CMS, plugins e tema.' },
  { title: 'Backup', text: 'Backups periódicos do site e do banco de dados, em um local seguro, na nuvem.' },
  { title: 'Monitoramento', text: 'Verificamos periodicamente as páginas, funcionalidades e formulários do site à procura de erros, lentidão ou qualquer outra falha, e agimos imediatamente para corrigi-los.' },
  { title: 'Análise', text: 'Relatórios mensais com os principais dados de acesso ao site.' },
  { title: 'Conteúdo', text: 'Atualizamos o conteúdo quando necessário, assim você não precisa se preocupar com nada.' },
  { title: 'Suporte', text: 'Disponíveis por chat ou e-mail para responder todas as questões relacionadas ao site.' },
];

const ADHOC_ICONS: Record<string, ReactNode> = {
  update: <path d="M20 11a8 8 0 0 0-14.5-4.5M4 4v3h3M4 13a8 8 0 0 0 14.5 4.5M20 20v-3h-3" />,
  speed: <path d="M4 17a8 8 0 1 1 16 0M12 17l4-5M10.5 17h3" />,
  shield: <path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3zM9 12l2 2 4-4" />,
  move: <path d="M7 8h11l-3-3M17 16H6l3 3" />,
  power: <path d="M12 3v8M7.5 6.5a7 7 0 1 0 9 0" />,
  search: <path d="M10.5 17a6.5 6.5 0 1 0 0-13 6.5 6.5 0 0 0 0 13zM15.5 15.5L20 20" />,
};

const ADHOC = [
  [
    { icon: 'update', title: 'Atualizações', text: 'Textos, fotos, posts, páginas: a gente arruma o que precisar para deixar o site novo de novo.' },
    { icon: 'speed', title: 'Otimização de velocidade', text: 'Resolvemos aquele problema chato que faz os clientes abandonarem a página.' },
    { icon: 'shield', title: 'Remoção de malware', text: 'Site lento, popups estranhos, e-mails duvidosos? Pode ser malware. A gente limpa tudo.' },
  ],
  [
    { icon: 'move', title: 'Migração entre hospedagens', text: 'Vai mudar de hospedagem? A gente copia, ajusta, instala e deixa tudo certinho.' },
    { icon: 'power', title: 'Site fora do ar', text: 'Alguma coisa deu errado, alguém mexeu no site e ele quebrou? A gente faz ele voltar ao ar rapidinho.' },
    { icon: 'search', title: 'Diagnóstico de problemas', text: 'Sabe aquele probleminha que está difícil de resolver? A gente encontra e resolve.' },
  ],
];

const ADHOC_LOG = ['Plugins atualizados', 'Imagens otimizadas', 'Malware removido', 'Backup restaurado', 'Site no ar'];


const FAQ: { q: string; a: ReactNode }[] = [
  { q: 'Qual a diferença entre os planos?', a: 'Todos têm helpdesk por WhatsApp, portal do cliente e e-mail. O Basic tem backups semanais, checagem de disponibilidade diária, inspeção visual mensal e 1h técnica por mês. O Standard passa a checagem para horária, a inspeção para semanal, as horas para 3h e inclui monitoramento de segurança. O Pro tem atendimento prioritário, backups diários, inspeção visual duas vezes por semana, 6h técnicas por mês e testes de checkout semanais.' },
  { q: 'Com que frequência são feitos os backups?', a: 'Semanais nos planos Basic e Standard e diários no Pro. Site e banco de dados são copiados para a nuvem.' },
  { q: 'O que são as horas técnicas?', a: 'Horas por mês para alterações e melhorias no site: 1h no Basic, 3h no Standard e 6h no Pro.' },
  { q: 'De quanto tempo é o contrato?', a: 'Mensal ou anual. O anual tem desconto equivalente a 6 meses e pode ser parcelado em até 6x no cartão.' },
  { q: 'Posso cancelar a qualquer momento?', a: 'Os contratos mensais podem ser cancelados a qualquer momento, sem burocracia. O contrato anual pode ser cancelado apenas no vencimento.' },
  { q: 'Quais são as formas de pagamento?', a: 'Boleto, Pix ou cartão.' },
  { q: 'Se eu não usar as horas do mês, elas ficam para o próximo?', a: 'Sim. As horas vão para um banco de horas com validade de 3 meses, com acúmulo máximo de 9 horas.' },
  { q: 'Meu site foi invadido. A manutenção resolve?', a: <>A limpeza de malware e a restauração do site são feitas na recuperação. Depois dela, o plano de manutenção evita a reincidência. <a href="/recuperacao-de-site-wordpress">Recuperação de site WordPress</a></> },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageManutencaoDeSites(_props: StaticPageProps) {
  const [annual, setAnnual] = useState(false);

  return (
    <>
      {/* seção: hero */}
      <section className="hero has-flow">
        <MaintainCanvas />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner">Seu site está com problemas? <em className="mark">Podemos resolver!</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Recuperação de sites, restauração de backups, migrações, ajustes e muito mais.</p>
              </div>
            </div>
          </div>
          <LeadForm className="hero-form" id="contato" whatsappHref={WHATSAPP} />
        </div>
      </section>
      {/* seção: plans */}
      <section className="section on-light" id="planos">
        <div className="wrap">
          <header className="sec-head plan-head">
            <h2 className="h2">Planos de manutenção de sites <em className="mark">sob medida para o seu negócio.</em></h2>
            <BillingSwitch annual={annual} onChange={setAnnual} />
          </header>
          <div className="plan-tiers">
            {PLANS.map((plan) => (
              <PlanCard key={plan.name} plan={plan} annual={annual} href={WHATSAPP} />
            ))}
          </div>
          <PlanBase title="Em todos os planos" text="Cada plano sobe a frequência dos cuidados e as horas técnicas do anterior." items={PLAN_BASE} />
        </div>
      </section>
      {/* seção: list */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">A importância da manutenção <em className="mark">de sites preventiva.</em></h2>
            <div className="rich intro">
              <p>A rotina que mantém o site atualizado, com cópia de segurança e funcionando, antes que um problema chegue ao visitante.</p>
            </div>
          </header>
          <ul className="ex-grid">
            {PREVENTIVE.map((item) => (
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
          <div className="prose-panel is-reveal" ref={revealOnView}>
            <h2 className="h2">Prevenir custa <em className="mark">menos que recuperar.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Plugin e tema desatualizados abrem espaço para invasão, lentidão e erro. Com atualização, backup e monitoramento constantes, o site segue seguro e no ar, e quando algo quebra, a correção é feita por quem desenvolve.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Escolher um plano" />
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: avulsos */}
      <section className="section on-dark">
        <div className="wrap">
          <header className="adhoc-head">
            <h2 className="h2">Precisa de algo <em className="mark">mais pontual?</em></h2>
            <div className="rich intro">
              <p>Atendimentos avulsos, sem plano de manutenção, feitos pelo mesmo time que desenvolve.</p>
            </div>
          </header>
          <div className="adhoc" ref={revealOnView}>
            {ADHOC.map((column, side) => (
              <ul className={`adhoc-list ${side === 0 ? 'is-left' : 'is-right'}`} key={side}>
                {column.map((item) => (
                  <li key={item.title}>
                    <span className="adhoc-icon" aria-hidden="true">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">{ADHOC_ICONS[item.icon]}</svg>
                    </span>
                    <div>
                      <h3 className="h3">{item.title}</h3>
                      <p>{item.text}</p>
                    </div>
                  </li>
                ))}
              </ul>
            ))}
            <div className="adhoc-screen" aria-hidden="true">
              <div className="adhoc-bar">
                <i /><i /><i />
                <span className="mono">seusite.com.br</span>
              </div>
              <ol className="adhoc-log">
                {ADHOC_LOG.map((line) => (
                  <li key={line}>
                    <span className="plan-tick"><svg viewBox="0 0 16 16"><path d="M3.5 8.5l3 3 6-7" pathLength={1} fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" /></svg></span>
                    <span className="mono">{line}</span>
                  </li>
                ))}
              </ol>
              <span className="adhoc-status"><i />No ar</span>
            </div>
          </div>
          <div className="adhoc-cta">
            <WhatsAppCta label="Falar com um especialista" />
          </div>
        </div>
      </section>
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
            <h2 className="h2">Manutenção com <em className="mark">quem desenvolve.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Conte qual é o site e como ele é mantido hoje. Indicamos o plano e o que precisa ser corrigido primeiro.</p>
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
