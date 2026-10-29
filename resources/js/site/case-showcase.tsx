import type { ReactNode } from 'react';
import { Arrow, resetTilt, trackTilt } from './ui';

export type ShowcaseCase = {
  slug: string;
  name: string;
  cover: string;
  ratio?: 'og' | '16x9' | '16x10';
  tags: string[];
  text: string;
  href: string;
  external?: boolean;
  cta: string;
  placeholder?: boolean;
};

function linkProps(item: ShowcaseCase) {
  return item.external ? { href: item.href, target: '_blank', rel: 'noreferrer' } : { href: item.href };
}

function CasePlaceholder({ item }: { item: ShowcaseCase }) {
  return (
    <article className="case-card is-wide is-placeholder">
      <div className="case-card-cover" aria-hidden="true">
        <span>{item.name}</span>
      </div>
      <div className="case-card-body">
        <h3>{item.name}</h3>
        {item.tags.length ? <p className="case-meta">{item.tags.map((tag) => <span key={tag}>{tag}</span>)}</p> : null}
        <p className="case-desc">{item.text}</p>
      </div>
    </article>
  );
}

export function CaseFeature({ item }: { item: ShowcaseCase }) {
  if (item.placeholder) {
    return <CasePlaceholder item={item} />;
  }

  const ratio = item.ratio && item.ratio !== 'og' ? ` is-cover-${item.ratio}` : '';

  return (
    <article className={`case-card is-wide is-tilt${ratio}`} onPointerMove={trackTilt} onPointerLeave={resetTilt}>
      <a className="case-card-cover" {...linkProps(item)} tabIndex={-1} aria-hidden="true">
        <img src={item.cover} alt="" loading="lazy" />
      </a>
      <div className="case-card-body">
        <h3>{item.name}</h3>
        {item.tags.length ? <p className="case-meta">{item.tags.map((tag) => <span key={tag}>{tag}</span>)}</p> : null}
        <p className="case-desc">{item.text}</p>
        <a className="case-go" {...linkProps(item)} aria-label={`${item.cta}: ${item.name}`}>{item.cta}<Arrow /></a>
      </div>
    </article>
  );
}

export function CaseFeatures({ items }: { items: ShowcaseCase[] }) {
  return (
    <div className="cases-grid is-single is-open">
      {items.map((item) => <CaseFeature key={item.slug} item={item} />)}
    </div>
  );
}

export function CaseShowcase({ title, intro, items, tone = 'dark' }: { title: ReactNode; intro?: ReactNode; items: ShowcaseCase[]; tone?: 'dark' | 'light' | 'white' }) {
  if (items.length === 0) {
    return null;
  }

  return (
    <section className={`section on-${tone}`}>
      <div className="wrap">
        <header className={intro ? 'sec-head' : 'sec-head is-solo'}>
          <h2 className="h2">{title}</h2>
          {intro ? <div className="rich intro"><p>{intro}</p></div> : null}
        </header>
        <CaseFeatures items={items} />
      </div>
    </section>
  );
}
