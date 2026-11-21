import { Arrow, Marked, Rich, rows, strings, text, type Data } from './ui';

const SCAN_LINES: Array<Array<[string, string | null]>> = [
  [['scan', 'k'], [' home → ', null], ['ok', 's']],
  [['robots', 'k'], [' gptbot · claude · perplexity', null]],
  [['html', 'k'], [' body text ', null], ['readable', 's']],
  [['schema', 'k'], [' ', null], ['json-ld', 'p'], [' · faq · service', null]],
  [['score', 'k'], [' ', null], ['0–100', 's'], [' · band', null]],
];

function AiVisAtmosphere() {
  const lines = [...SCAN_LINES, ...SCAN_LINES];

  return (
    <div className="ai-vis-atmosphere" aria-hidden="true">
      <div className="agent-log" aria-hidden="true">
        <div>
          {lines.map((line, row) => (
            <span key={row}>
              {line.map(([value, tone], index) => (tone ? <span key={index} className={tone}>{value}</span> : value))}
              {'\n'}
            </span>
          ))}
        </div>
      </div>
    </div>
  );
}

export function AiVisHero({ data }: { data: Data }) {
  const badges = strings(data, 'badges');
  const placeholder = text(data, 'placeholder') || 'https://seusite.com.br';
  const buttonLabel = text(data, 'button_label') || 'Ver minha nota';
  const title = text(data, 'title') || 'Como aparecer no *ChatGPT*?';
  const lead = text(data, 'lead') || text(data, 'subtitle');

  return (
    <section className="section ai-vis-hero is-mark-draw" data-section="ai-vis-hero">
      <AiVisAtmosphere />
      <div className="wrap">
        <div className="ai-vis-hero-grid">
          <div className="ai-vis-hero-copy">
            <h1><Marked value={title} /></h1>
            {lead ? <Rich className="lead" value={lead.startsWith('<') ? lead : `<p>${lead}</p>`} /> : null}
            {badges.length > 0 ? (
              <ul className="ai-vis-trust">
                {badges.map((label) => (
                  <li key={label}>{label}</li>
                ))}
              </ul>
            ) : null}
          </div>
          <div className="ai-vis-hero-tool">
            <form className="ai-vis-form" action="/api/como-aparecer-no-chatgpt" method="post" noValidate>
              <input type="text" name="empresa_site" tabIndex={-1} autoComplete="off" aria-hidden="true" className="hp" />
              <label className="sr-only" htmlFor="ai-vis-url">Endereço do site</label>
              <input id="ai-vis-url" name="url" type="text" inputMode="url" autoComplete="url" placeholder={placeholder} required maxLength={500} />
              <button className="pill solid" type="submit">{buttonLabel}</button>
            </form>
            <p className="hint" id="ai-vis-status" role="status" aria-live="polite" hidden />
          </div>
        </div>
      </div>
    </section>
  );
}

export function AiVisPlatforms({ data }: { data: Data }) {
  const platforms = rows(data, 'platforms');
  const label = text(data, 'label') || 'Este teste verifica as ferramentas:';
  if (platforms.length === 0) {
    return null;
  }

  return (
    <section className="section on-dark ai-vis-platforms" data-section="ai-vis-platforms" aria-label="Assistentes cobertos">
      <div className="wrap">
        <p className="ai-vis-platforms-label">{label}</p>
        <ul>
          {platforms.map((item, index) => (
            <li key={`${text(item, 'name')}-${index}`}>
              {text(item, 'logo') ? <img src={text(item, 'logo')} alt="" width={28} height={28} loading="lazy" decoding="async" /> : null}
              <span>{text(item, 'name')}</span>
            </li>
          ))}
        </ul>
      </div>
    </section>
  );
}

export function AiVisPillars({ data }: { data: Data }) {
  const items = rows(data);
  const title = text(data, 'title') || 'Três passos simples';
  const intro = text(data, 'intro');

  return (
    <section className="section on-light ai-vis-pillars is-reveal" data-section="ai-vis-pillars" aria-labelledby="pilares">
      <div className="wrap">
        <h2 id="pilares" className="h2"><Marked value={title} /></h2>
        {intro ? <Rich className="intro" value={intro} /> : null}
        <ol className="ai-vis-pillar-list">
          {items.map((item, index) => (
            <li key={`${text(item, 'title')}-${index}`}>
              <strong>{text(item, 'title')}</strong>
              <Rich value={text(item, 'body')} />
            </li>
          ))}
        </ol>
      </div>
    </section>
  );
}

export function AiVisMeasure({ data }: { data: Data }) {
  const items = rows(data);
  const title = text(data, 'title') || 'O que olhamos no teste';
  const intro = text(data, 'intro');
  const anchor = text(data, 'anchor') || 'medir';

  return (
    <section className="section on-dark ai-vis-measure-section is-reveal" data-section="ai-vis-measure" aria-labelledby="medir" id={anchor}>
      <div className="wrap">
        <h2 id="medir" className="h2"><Marked value={title} /></h2>
        {intro ? <Rich className="intro" value={intro} /> : null}
        <div className="ai-vis-measure-track" aria-hidden="true"><span /></div>
        <ul className="ai-vis-measure">
          {items.map((item, index) => (
            <li key={`${text(item, 'label')}-${index}`}>
              <span className="ai-vis-measure-pts">
                {text(item, 'pts') || text(item, 'points')}
                {' '}
                <span className="ai-vis-measure-unit">PTS</span>
              </span>
              <div>
                <strong>{text(item, 'label') || text(item, 'title')}</strong>
                <Rich value={text(item, 'body')} />
              </div>
            </li>
          ))}
        </ul>
      </div>
    </section>
  );
}

export function AiVisArticle({ data }: { data: Data }) {
  const blocks = rows(data, 'blocks');
  const title = text(data, 'title');
  const intro = text(data, 'intro');
  const anchor = text(data, 'anchor') || 'guia';

  return (
    <article className="section on-white ai-vis-article is-mark-draw is-reveal" data-section="ai-vis-article" aria-labelledby="guia" id={anchor}>
      <div className="wrap">
        <header className="ai-vis-article-head">
          {title ? <h2 id="guia" className="h2"><Marked value={title} /></h2> : null}
          {intro ? <Rich className="intro" value={intro} /> : null}
        </header>
        <div className="ai-vis-article-body">
          {blocks.map((block, index) => {
            const steps = rows(block, 'steps');
            return (
              <div key={`${text(block, 'heading')}-${index}`}>
                {text(block, 'heading') ? <h3>{text(block, 'heading')}</h3> : null}
                {text(block, 'body') ? <Rich value={text(block, 'body')} /> : null}
                {steps.length > 0 ? (
                  <ol className="ai-vis-steps">
                    {steps.map((step, stepIndex) => (
                      <li key={`${text(step, 'title')}-${stepIndex}`}>
                        <strong>{text(step, 'title')}</strong>
                        <Rich value={text(step, 'body')} />
                      </li>
                    ))}
                  </ol>
                ) : null}
              </div>
            );
          })}
        </div>
      </div>
    </article>
  );
}

export function AiVisApply({ data }: { data: Data }) {
  const title = text(data, 'title');
  const body = text(data, 'body');
  const label = text(data, 'cta_label') || 'Chamar no WhatsApp';
  const href = text(data, 'cta_href');
  if (!title && !body) {
    return null;
  }

  return (
    <section className="section on-white ai-vis-apply" data-section="ai-vis-apply" aria-labelledby="aplicar">
      <div className="wrap">
        <div className="prose-panel is-reveal">
          {title ? <h2 id="aplicar" className="h2"><Marked value={title} /></h2> : null}
          <div className="prose-panel-side">
            {body ? <Rich value={body} /> : null}
            {href ? (
              <div className="actions">
                <a className="pill lime" href={href} target="_blank" rel="noreferrer">
                  {label}
                  <span className="arrow" aria-hidden="true"><Arrow /></span>
                </a>
              </div>
            ) : null}
          </div>
        </div>
      </div>
    </section>
  );
}
