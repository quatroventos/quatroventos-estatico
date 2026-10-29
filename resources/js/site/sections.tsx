import { useEffect, useRef, useState, type FormEvent, type ReactNode } from 'react';
import { CasesList, CasesPair } from './cases';
import { Hero } from './hero';
import type { FormField, Section, SiteForm } from './types';
import { Arrow, Cta, LinkTo, Marked, Rich, pad, plain, rows, text, type Data } from './ui';
import { t } from './i18n';
import { PhoneInput, isPhoneComplete, phoneToString, type PhoneValue } from './phone';

type Tone = 'dark' | 'light' | 'white';

const TONES: Record<string, Tone> = {
  hero: 'dark',
  steps: 'dark',
  cta: 'dark',
  quotes: 'white',
  logos: 'white',
};

function kindOf(section: Section): string {
  return section.type.split('.').pop() ?? section.type;
}

function isEmpty(section: Section): boolean {
  const kind = kindOf(section);
  const data = section.data ?? {};

  if (kind === 'cases') {
    return !Array.isArray(data.cases) || data.cases.length === 0;
  }
  if (['list', 'cards', 'steps', 'portfolio', 'plans', 'faq', 'quotes', 'logos'].includes(kind)) {
    return rows(data).length === 0;
  }

  return false;
}

export function Sections({ sections, form }: { sections: Section[]; form: SiteForm | null }) {
  const visible = sections.filter((section) => !isEmpty(section));
  let previous: Tone | null = null;
  let previousKind = '';

  return (
    <>
      {visible.map((section) => {
        const kind = kindOf(section);
        const tone = TONES[kind] ?? 'light';
        const flush = previous === tone && kind !== 'hero';
        const stacked = kind === 'prose' && previousKind === 'prose';
        previous = tone;
        previousKind = kind;

        return <SectionView key={section.id} kind={kind} data={section.data ?? {}} tone={tone} flush={flush} stacked={stacked} form={form} />;
      })}
    </>
  );
}

function Shell({ tone, flush, id, className, children }: { tone: Tone; flush: boolean; id?: string; className?: string; children: ReactNode }) {
  const classes = ['section', `on-${tone}`, flush ? 'is-flush' : '', className ?? ''].filter(Boolean).join(' ');

  return (
    <section className={classes} id={id}>
      <div className="wrap">{children}</div>
    </section>
  );
}

function Head({ title, intro, href }: { title: string; intro?: string; href?: string }) {
  if (!title && !intro) {
    return null;
  }

  return (
    <header className={intro ? 'sec-head' : 'sec-head is-solo'}>
      {title ? (
        <h2 className="h2">
          {href ? <a href={href}><Marked value={title} /><span className="head-go"><Arrow /></span></a> : <Marked value={title} />}
        </h2>
      ) : <span />}
      {intro ? <Rich className="intro" value={intro} /> : null}
    </header>
  );
}

function SectionView({ kind, data, tone, flush, stacked, form }: { kind: string; data: Data; tone: Tone; flush: boolean; stacked: boolean; form: SiteForm | null }) {
  const shell = { tone, flush };

  switch (kind) {
    case 'hero':
      return <Hero data={data} />;

    case 'prose':
      return (
        <Shell {...shell}>
          <div className={stacked ? 'prose-grid is-stack' : 'prose-grid'}>
            {text(data, 'title') ? <h2 className="h2"><Marked value={text(data, 'title')} /></h2> : <span />}
            <Rich value={text(data, 'body')} />
          </div>
        </Shell>
      );

    case 'list':
    case 'cards':
    case 'portfolio':
      return (
        <Shell {...shell}>
          <Head title={text(data, 'title')} href={text(data, 'href')} />
          <ListBody items={rows(data)} />
          {text(data, 'intro') ? <Rich className="list-note" value={text(data, 'intro')} /> : null}
        </Shell>
      );

    case 'steps':
      return (
        <Shell {...shell}>
          <Head title={text(data, 'title')} />
          <ol className="steps">
            {rows(data).map((item, index) => (
              <li key={`${text(item, 'title')}-${index}`}>
                <span className="mono">{pad(index)}</span>
                <h3 className="h3">{text(item, 'title')}</h3>
                <Rich value={text(item, 'body')} />
              </li>
            ))}
          </ol>
          {text(data, 'intro') ? <Rich className="steps-note" value={text(data, 'intro')} /> : null}
        </Shell>
      );

    case 'plans':
      return (
        <Shell {...shell} id="planos">
          <Head title={text(data, 'title')} intro={text(data, 'intro')} />
          <div className="plans">
            {rows(data).map((item, index) => (
              <article className="plan" key={`${text(item, 'name')}-${index}`}>
                <h3 className="name">{text(item, 'name')}</h3>
                <p className="price">
                  {text(item, 'price')}
                  {text(item, 'period') ? <small>{text(item, 'period')}</small> : null}
                </p>
                <Rich value={text(item, 'body')} />
                <Cta href={text(item, 'href')} label={text(item, 'cta') || t('plans.cta')} variant="primary" />
              </article>
            ))}
          </div>
        </Shell>
      );

    case 'quotes':
      return (
        <Shell {...shell}>
          <Quotes title={text(data, 'title')} items={rows(data)} />
        </Shell>
      );

    case 'logos':
      return (
        <Shell {...shell}>
          <Head title={text(data, 'title')} />
          <ul className="logos">
            {rows(data).map((item, index) => (
              <li key={`${text(item, 'name')}-${index}`}>
                {text(item, 'image')
                  ? <img src={text(item, 'image')} alt={text(item, 'image_alt') || text(item, 'name')} loading="lazy" />
                  : <span className="logo-name">{text(item, 'name')}</span>}
              </li>
            ))}
          </ul>
        </Shell>
      );

    case 'faq':
      return (
        <Shell {...shell}>
          <div className="faq-grid">
            <h2 className="h2"><Marked value={text(data, 'title') || t('faq.title')} /></h2>
            <div className="faq">
              {rows(data).map((item, index) => (
                <details key={`${text(item, 'question')}-${index}`}>
                  <summary>
                    {text(item, 'question')}
                    <span className="plus" aria-hidden="true" />
                  </summary>
                  <Rich value={text(item, 'answer')} />
                </details>
              ))}
            </div>
          </div>
        </Shell>
      );

    case 'cta':
      return (
        <Shell {...shell} className="cta-block">
          <h2><Marked value={text(data, 'title')} /></h2>
          <div className="cta-side">
            <Rich value={text(data, 'body')} />
            <div className="actions">
              <Cta href={text(data, 'href')} label={text(data, 'label')} variant="primary" />
              <Cta href={text(data, 'secondary_href')} label={text(data, 'secondary_label')} variant="line" />
            </div>
          </div>
        </Shell>
      );

    case 'contact':
      return (
        <Shell {...shell} id="contato">
          <div className="contact-grid">
            <div>
              {text(data, 'title') ? <h2 className="h2"><Marked value={text(data, 'title')} /></h2> : null}
              <Rich value={text(data, 'intro')} />
            </div>
            <div className="form-card">
              <ContactForm form={form} />
            </div>
          </div>
        </Shell>
      );

    case 'cases':
      return (
        <Shell {...shell}>
          <CasesPair data={data} />
        </Shell>
      );

    case 'cases-list':
      return (
        <Shell {...shell}>
          <CasesList data={data} />
        </Shell>
      );

    default:
      return null;
  }
}

function ListBody({ items }: { items: Data[] }) {
  const withBody = items.some((item) => text(item, 'body'));
  const allLinked = items.length > 0 && items.every((item) => text(item, 'href'));
  const withLinks = items.some((item) => text(item, 'href'));

  if (withBody && allLinked) {
    return (
      <ul className={items.length === 4 ? 'cards is-4' : items.length === 5 ? 'cards is-5' : 'cards'}>
        {items.map((item, index) => (
          <li key={`${text(item, 'title')}-${index}`}>
            <LinkTo className="card" href={text(item, 'href')}>
              <span className="num">
                <span className="mono">{pad(index)}</span>
                <span className="go"><Arrow /></span>
              </span>
              <div>
                <h3 className="h3">{plain(text(item, 'title'))}</h3>
                <Rich value={text(item, 'body')} />
              </div>
            </LinkTo>
          </li>
        ))}
      </ul>
    );
  }

  if (withBody) {
    return (
      <dl className="def-rows">
        {items.map((item, index) => {
          const href = text(item, 'href');

          return (
            <div key={`${text(item, 'title')}-${index}`}>
              <dt className="h3">{href ? <LinkTo href={href}>{plain(text(item, 'title'))}</LinkTo> : plain(text(item, 'title'))}</dt>
              <dd><Rich value={text(item, 'body')} /></dd>
            </div>
          );
        })}
      </dl>
    );
  }

  if (withLinks) {
    return (
      <ul className="link-rows">
        {items.map((item, index) => (
          <li key={`${text(item, 'title')}-${index}`}>
            {text(item, 'href')
              ? <LinkTo href={text(item, 'href')}>{text(item, 'title')}<span className="go"><Arrow /></span></LinkTo>
              : <span className="row">{text(item, 'title')}</span>}
          </li>
        ))}
      </ul>
    );
  }

  const short = items.length >= 6 && items.every((item) => text(item, 'title').length <= 22);

  if (short) {
    return (
      <ul className="chips">
        {items.map((item, index) => <li key={`${text(item, 'title')}-${index}`}>{text(item, 'title')}</li>)}
      </ul>
    );
  }

  return (
    <ul className="bullets">
      {items.map((item, index) => (
        <li key={`${text(item, 'title')}-${index}`}>{text(item, 'title')}</li>
      ))}
    </ul>
  );
}

function QuoteBy({ item }: { item: Data }) {
  return (
    <footer className="quote-by">
      <b>{text(item, 'author')}</b>
      {text(item, 'company') ? <span>{text(item, 'company')}</span> : null}
    </footer>
  );
}

function Quotes({ title, items }: { title: string; items: Data[] }) {
  if (items.length === 1) {
    const item = items[0];

    return (
      <div className="quote-solo">
        <span className="q-mark" aria-hidden="true">“</span>
        <blockquote>
          <Rich value={text(item, 'quote')} />
          <QuoteBy item={item} />
        </blockquote>
      </div>
    );
  }

  return (
    <>
      <Head title={title} />
      <div className="quotes">
        {items.map((item, index) => (
          <blockquote key={`${text(item, 'author')}-${index}`}>
            <Rich value={text(item, 'quote')} />
            <QuoteBy item={item} />
          </blockquote>
        ))}
      </div>
    </>
  );
}

export function ContactForm({ form }: { form: SiteForm | null }) {
  const fields = (form?.sections ?? []).flatMap((section) => section.fields ?? []);
  const [values, setValues] = useState<Record<string, string>>({});
  const [status, setStatus] = useState<string | null>(null);
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [busy, setBusy] = useState(false);

  async function submit(event: FormEvent) {
    event.preventDefault();
    setBusy(true);
    setStatus(null);
    setErrors({});

    try {
      const response = await fetch('/api/contato', {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ ...values, empresa_site: '' }),
      });
      const payload = await response.json() as { status?: string; errors?: Record<string, string[]> };
      if (!response.ok) {
        setErrors(payload.errors ?? {});
        setStatus(payload.status ?? 'invalido');
        return;
      }
      setStatus(payload.status ?? 'enviado');
      if (payload.status === 'enviado') {
        setValues({});
      }
    } catch {
      setStatus('erro');
    } finally {
      setBusy(false);
    }
  }

  return (
    <form onSubmit={(event) => void submit(event)}>
      <input type="text" name="empresa_site" tabIndex={-1} autoComplete="off" className="hp" aria-hidden="true" />
      {fields.map((field) => (
        <Field
          key={field.name}
          field={field}
          value={values[field.name] ?? ''}
          lgpd={form?.lgpd_label}
          error={errors[field.name]?.[0]}
          onChange={(value) => setValues((current) => ({ ...current, [field.name]: value }))}
        />
      ))}
      <div className="form-status-slot" aria-live="polite">
        {status === 'enviado' ? <p className="form-status is-ok">{t('form.sent')}</p> : null}
        {status === 'erro' ? <p className="form-status is-error">{t('form.error')}</p> : null}
        {status === 'invalido' ? <p className="form-status is-error">{t('form.invalid')}</p> : null}
      </div>
      <button className="pill solid" type="submit" disabled={busy}>{busy ? t('form.sending') : form?.submit_label || t('form.submit')}</button>
    </form>
  );
}

// Envio desligado durante o ajuste de layout e animação: o card gira sem chamar /api/contato/lead.
const LEAD_DRY_RUN = true;

// Lead de click-to-WhatsApp: a frente coleta o interesse; ao enviar, o card gira e o verso agradece e abre o WhatsApp.
export function LeadForm({ whatsappHref, title, className, id }: { whatsappHref: string; title?: string; className?: string; id?: string }) {
  const [nome, setNome] = useState('');
  const [phone, setPhone] = useState<PhoneValue>({ iso: 'BR', number: '' });
  const [sent, setSent] = useState<{ nome: string; whatsapp: string } | null>(null);
  const [status, setStatus] = useState<string | null>(null);
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [busy, setBusy] = useState(false);
  const backTitle = useRef<HTMLHeadingElement>(null);

  useEffect(() => {
    if (sent) {
      backTitle.current?.focus({ preventScroll: true });
    }
  }, [sent]);

  async function submit(event: FormEvent) {
    event.preventDefault();
    setBusy(true);
    setStatus(null);
    setErrors({});

    if (!isPhoneComplete(phone)) {
      setErrors({ whatsapp: [t('lead.phone_invalid')] });
      setBusy(false);
      return;
    }

    const lead = { nome: nome.trim(), whatsapp: phoneToString(phone) };
    const reset = () => {
      setSent(lead);
      setNome('');
      setPhone((current) => ({ ...current, number: '' }));
    };

    if (LEAD_DRY_RUN) {
      await new Promise((resolve) => setTimeout(resolve, 600));
      reset();
      setBusy(false);
      return;
    }

    try {
      const response = await fetch('/api/contato/lead', {
        method: 'POST',
        headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ ...lead, pagina: window.location.pathname, empresa_site: '' }),
      });
      const payload = await response.json() as { status?: string; errors?: Record<string, string[]> };
      if (!response.ok) {
        setErrors(payload.errors ?? {});
        setStatus(payload.status ?? 'invalido');
        return;
      }
      reset();
    } catch {
      setStatus('erro');
    } finally {
      setBusy(false);
    }
  }

  const firstName = sent?.nome.split(/\s+/)[0] ?? '';
  const chatHref = withWhatsAppText(whatsappHref, firstName ? t('lead.wa_text', { name: firstName }) : '');

  return (
    <div className={['lead-card', sent ? 'is-sent' : '', className ?? ''].filter(Boolean).join(' ')} id={id}>
      <div className="lead-flip">
        <div className="lead-face lead-front" inert={sent ? true : undefined}>
          <form className="lead-form" onSubmit={(event) => void submit(event)}>
            <h2 className="lead-form-title">{title || t('lead.title')}</h2>
            <p className="lead-form-text">{t('lead.text')}</p>
            <input type="text" name="empresa_site" tabIndex={-1} autoComplete="off" className="hp" aria-hidden="true" />
            <label className="is-wide">
              {t('lead.name')}
              <input
                name="nome"
                type="text"
                autoComplete="name"
                required
                value={nome}
                aria-invalid={Boolean(errors.nome)}
                onChange={(event) => setNome(event.target.value)}
              />
              {errors.nome?.[0] ? <span className="field-error">{errors.nome[0]}</span> : null}
            </label>
            <div className="lead-field is-wide">
              <label htmlFor={`${id ?? 'lead'}-whatsapp`}>{t('lead.whatsapp')}</label>
              <PhoneInput
                id={`${id ?? 'lead'}-whatsapp`}
                name="whatsapp"
                value={phone}
                invalid={Boolean(errors.whatsapp)}
                countryLabel={t('lead.country')}
                onChange={(next) => {
                  setPhone(next);
                  if (errors.whatsapp) {
                    setErrors(({ whatsapp: _drop, ...rest }) => rest);
                  }
                }}
              />
              {errors.whatsapp?.[0] ? <span className="field-error">{errors.whatsapp[0]}</span> : null}
            </div>
            <div className="form-status-slot" aria-live="polite">
              {status === 'erro' ? <p className="form-status is-error">{t('form.error')}</p> : null}
              {status === 'invalido' ? <p className="form-status is-error">{t('form.invalid')}</p> : null}
            </div>
            <button className="pill solid" type="submit" disabled={busy}>{busy ? t('form.sending') : t('lead.submit')}</button>
          </form>
        </div>
        <div className="lead-face lead-back" inert={sent ? undefined : true} aria-live="polite">
          <span className="lead-check" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M6 12.5l4 4 8-9" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" /></svg>
          </span>
          <h2 className="lead-form-title" ref={backTitle} tabIndex={-1}>{firstName ? t('lead.thanks_title', { name: firstName }) : t('lead.thanks_title_plain')}</h2>
          <p className="lead-back-text">{sent?.whatsapp ? t('lead.thanks_text', { phone: sent.whatsapp }) : t('lead.thanks_text_plain')}</p>
          <a className="pill lime" href={chatHref} target="_blank" rel="noreferrer">
            {t('lead.whatsapp_now')}
            <span className="arrow"><Arrow /></span>
          </a>
        </div>
      </div>
    </div>
  );
}

function withWhatsAppText(href: string, message: string): string {
  if (!message) {
    return href;
  }

  try {
    const url = new URL(href);
    url.searchParams.set('text', message);
    return url.toString();
  } catch {
    return href;
  }
}

function Field({
  field,
  value,
  lgpd,
  error,
  onChange,
}: {
  field: FormField;
  value: string;
  lgpd?: string;
  error?: string;
  onChange: (value: string) => void;
}): ReactNode {
  const label = field.name === 'lgpd' && lgpd ? lgpd : field.label;
  const errorNode = error ? <span className="field-error">{error}</span> : null;

  if (field.type === 'textarea') {
    return (
      <label className="is-wide">
        {label}
        <textarea name={field.name} required={field.required} value={value} aria-invalid={Boolean(error)} onChange={(event) => onChange(event.target.value)} />
        {errorNode}
      </label>
    );
  }

  if (field.type === 'select') {
    return (
      <label className="is-wide">
        {label}
        <select name={field.name} required={field.required} value={value} aria-invalid={Boolean(error)} onChange={(event) => onChange(event.target.value)}>
          <option value="">{t('form.select')}</option>
          {(field.options ?? []).map((option) => (
            <option key={option.value} value={option.value}>{option.label}</option>
          ))}
        </select>
        {errorNode}
      </label>
    );
  }

  if (field.type === 'checkbox') {
    return (
      <label className="check">
        <input type="checkbox" name={field.name} required={field.required} checked={value === '1'} onChange={(event) => onChange(event.target.checked ? '1' : '')} />
        <span>{label}</span>
        {errorNode}
      </label>
    );
  }

  const type = field.type === 'email' || field.type === 'tel' ? field.type : 'text';
  const autoComplete = field.type === 'email' ? 'email' : field.type === 'tel' ? 'tel' : field.name === 'nome' ? 'name' : undefined;

  return (
    <label>
      {label}
      <input name={field.name} type={type} autoComplete={autoComplete} required={field.required} value={value} aria-invalid={Boolean(error)} onChange={(event) => onChange(event.target.value)} />
      {errorNode}
    </label>
  );
}
