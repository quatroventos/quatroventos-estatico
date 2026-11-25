// UI de planos compartilhada pela seção de planos do CMS e pelas páginas estáticas de manutenção, hospedagem e VPS.
// Os dados ficam em cada página ou seção; aqui ficam o card, a base comum, as abas e o seletor de período.
import type { CSSProperties } from 'react';
import { t } from './i18n';
import { Arrow, revealOnView } from './ui';

export type Plan = { name: string; monthly: number; annual: number; triennial?: number; featured?: boolean; from?: string; adds: string[] };

type Heading = 'h3' | 'h4';

export const money = (value: number) => value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

const cascade = (index: number) => ({ '--ld': `${Math.min(index, 8) * 70}ms` }) as CSSProperties;

function triennialTotal(plan: Plan): number {
  return plan.triennial && plan.triennial > 0 ? plan.triennial : plan.annual * 3;
}

export function PlanTick() {
  return (
    <span className="plan-tick">
      <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3.5 8.5l3 3 6-7" pathLength={1} fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" /></svg>
    </span>
  );
}

export function BillingSwitch() {
  return (
    <>
      <div className="plan-billing" role="group" aria-label={t('plans.billing')}>
        <button type="button" data-billing="month" aria-pressed="false">{t('plans.monthly')}</button>
        <button type="button" data-billing="year" aria-pressed="false">
          {t('plans.annual')}
          <span className="plan-save">{t('plans.save')}</span>
        </button>
        <button type="button" data-billing="tri" aria-pressed="true">{t('plans.triennial')}</button>
      </div>
      <p className="sr-only" aria-live="polite">{t('plans.live_tri')}</p>
    </>
  );
}

type Tab = { id: string; tab: string };

export function PlanTabs({ items, label, controls }: { items: Tab[]; label: string; controls: string }) {
  return (
    <div className="plan-billing is-tabs" role="group" aria-label={label}>
      {items.map((item, index) => (
        <button key={item.id} type="button" data-panel={item.id} aria-pressed={index === 0} aria-controls={controls}>{item.tab}</button>
      ))}
    </div>
  );
}

type PlanCardProps = {
  plan: Plan;
  href: string;
  altHref?: string;
  heading?: Heading;
  instant?: boolean;
};

export function PlanCard({ plan, href, altHref, heading: Title = 'h3', instant = false }: PlanCardProps) {
  const offset = plan.from ? 1 : 0;
  const tri = triennialTotal(plan);
  const fixed = t('plans.fixed_price');

  return (
    <article className={`plan-tier${plan.featured ? ' is-featured' : ''}${instant ? ' is-in' : ''}`} data-offer={`${plan.name}|${plan.monthly}`} ref={instant ? undefined : revealOnView}>
      {plan.featured && <span className="plan-flag">{t('plans.featured')}</span>}
      <Title className="name">{plan.name}</Title>
      <div className="plan-price">
        <p className="price" data-billing="month" hidden>{money(plan.monthly)}<small>{t('plans.per_month')}</small></p>
        <p className="price" data-billing="year" hidden>{money(plan.annual / 12)}<small>{t('plans.per_month')}</small></p>
        <p className="price" data-billing="tri">{money(tri / 36)}<small>{t('plans.per_month')}</small></p>
        <p className="plan-bill" data-billing="month" hidden>{t('plans.bill_monthly')} · {fixed}</p>
        <p className="plan-bill" data-billing="year" hidden>
          <span className="sr-only">{t('plans.bill_from')}</span><s>{money(plan.monthly * 12)}</s> <span className="sr-only">{t('plans.bill_to')}</span>{money(plan.annual)} {t('plans.bill_yearly')} · {fixed}
        </p>
        <p className="plan-bill" data-billing="tri">
          <span className="sr-only">{t('plans.bill_from')}</span><s>{money(plan.monthly * 36)}</s> <span className="sr-only">{t('plans.bill_to')}</span>{money(tri)} {t('plans.bill_triennial')} · {fixed}
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
          {t('plans.hire', { name: plan.name })}
          <span className="arrow"><Arrow /></span>
        </a>
        {altHref ? <a className="plan-alt" href={altHref} target="_blank" rel="noreferrer">{t('plans.alt')}</a> : null}
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
