// UI de planos compartilhada por /manutencao-de-sites, /hospedagem-de-sites e /vps-e-infraestrutura.
// Os dados ficam em cada página; aqui ficam o card, a base comum, as abas e o seletor de período.
import type { CSSProperties } from 'react';
import { Arrow, revealOnView } from '../ui';

export type Plan = { name: string; monthly: number; annual: number; featured?: boolean; from?: string; adds: string[] };

type Heading = 'h3' | 'h4';

export const money = (value: number) => value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

const cascade = (index: number) => ({ '--ld': `${Math.min(index, 8) * 70}ms` }) as CSSProperties;

export function PlanTick() {
  return (
    <span className="plan-tick">
      <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3.5 8.5l3 3 6-7" pathLength={1} fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" /></svg>
    </span>
  );
}

export function BillingSwitch({ annual, onChange }: { annual: boolean; onChange: (annual: boolean) => void }) {
  return (
    <>
      <div className="plan-billing" role="group" aria-label="Período de cobrança">
        <button type="button" aria-pressed={!annual} onClick={() => onChange(false)}>Mensal</button>
        <button type="button" aria-pressed={annual} onClick={() => onChange(true)}>
          Anual
          <span className="plan-save">2 meses grátis</span>
        </button>
      </div>
      <p className="sr-only" aria-live="polite">{annual ? 'Preços em cobrança anual' : 'Preços em cobrança mensal'}</p>
    </>
  );
}

type Tab = { id: string; tab: string };

export function PlanTabs({ items, activeId, label, controls, onChange }: { items: Tab[]; activeId: string; label: string; controls: string; onChange: (id: string) => void }) {
  return (
    <div className="plan-billing is-tabs" role="group" aria-label={label}>
      {items.map((item) => (
        <button key={item.id} type="button" aria-pressed={item.id === activeId} aria-controls={controls} onClick={() => onChange(item.id)}>{item.tab}</button>
      ))}
    </div>
  );
}

type PlanCardProps = {
  plan: Plan;
  annual: boolean;
  href: string;
  altHref?: string;
  heading?: Heading;
  instant?: boolean;
};

export function PlanCard({ plan, annual, href, altHref, heading: Title = 'h3', instant = false }: PlanCardProps) {
  const offset = plan.from ? 1 : 0;

  return (
    <article className={`plan-tier${plan.featured ? ' is-featured' : ''}${instant ? ' is-in' : ''}`} ref={instant ? undefined : revealOnView}>
      {plan.featured && <span className="plan-flag">Recomendado</span>}
      <Title className="name">{plan.name}</Title>
      <div className="plan-price">
        <p className="price" key={annual ? 'a' : 'm'}>{money(annual ? plan.annual / 12 : plan.monthly)}<small>/mês</small></p>
        <p className="plan-bill">
          {annual ? (
            <><span className="sr-only">de </span><s>{money(plan.monthly * 12)}</s> <span className="sr-only">por </span>{money(plan.annual)} cobrados por ano</>
          ) : 'Cobrança mensal'}
        </p>
      </div>
      <ul className="plan-adds">
        {plan.from ? <li className="is-from">{plan.from}</li> : null}
        {plan.adds.map((item, index) => (
          <li key={item} style={cascade(index + offset)}>
            <PlanTick />
            <span>{item}</span>
          </li>
        ))}
      </ul>
      <div className="plan-actions">
        <a className="pill solid plan-cta" href={href} target="_blank" rel="noreferrer">
          Contratar o {plan.name}
          <span className="arrow"><Arrow /></span>
        </a>
        {altHref ? <a className="plan-alt" href={altHref} target="_blank" rel="noreferrer">Falar com a equipe</a> : null}
      </div>
    </article>
  );
}

type PlanBaseProps = {
  title: string;
  text: string;
  items: string[];
  heading?: Heading;
  instant?: boolean;
};

export function PlanBase({ title, text, items, heading: Title = 'h3', instant = false }: PlanBaseProps) {
  return (
    <div className={`plan-base${instant ? ' is-in' : ''}`} ref={instant ? undefined : revealOnView}>
      <div>
        <Title>{title}</Title>
        <p>{text}</p>
      </div>
      <ul>
        {items.map((item, index) => (
          <li key={item} style={cascade(index)}>
            <PlanTick />
            <span>{item}</span>
          </li>
        ))}
      </ul>
    </div>
  );
}
