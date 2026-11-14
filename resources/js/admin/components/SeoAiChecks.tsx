import { useId, useState } from 'react';
import { ChevronDown, ChevronRight } from 'lucide-react';
import { ScoreRing } from './SiteHealth';

export type AiCheck = {
  id: string;
  label: string;
  status: 'good' | 'ok' | 'bad';
  message: string;
  weight: number;
};

export type AiReadiness = { score: number; checks: AiCheck[] };

const STATUS_ORDER = { bad: 0, ok: 1, good: 2 } as const;

/** Nota de prontidão para buscadores de IA, com os pendentes abertos e os certos recolhidos. */
export function SeoAiChecks({ data, title, hint }: { data: AiReadiness; title: string; hint: string }) {
  const [showGood, setShowGood] = useState(false);
  const goodId = useId();
  const checks = [...data.checks].sort((a, b) => STATUS_ORDER[a.status] - STATUS_ORDER[b.status] || b.weight - a.weight);
  const open = checks.filter((check) => check.status !== 'good');
  const good = checks.filter((check) => check.status === 'good');

  return (
    <div className="seo-ai">
      <div className="seo-score">
        <ScoreRing score={data.score} label="Pronta para IA" />
        <div>
          <strong>{title}</strong>
          <span>{hint}</span>
        </div>
      </div>
      {open.length > 0 ? (
        <ul className="seo-checks">
          {open.map((check) => (
            <li key={check.id} className={`is-${check.status}`}>
              <i aria-hidden="true" />
              <div>
                <strong>{check.label}</strong>
                <p>{check.message}</p>
              </div>
            </li>
          ))}
        </ul>
      ) : null}
      {good.length > 0 ? (
        <>
          <button className="seo-good-toggle" type="button" aria-expanded={showGood} aria-controls={goodId} onClick={() => setShowGood(!showGood)}>
            {showGood ? <ChevronDown size={12} /> : <ChevronRight size={12} />} {good.length} {good.length === 1 ? 'item certo' : 'itens certos'}
          </button>
          {showGood ? (
            <ul className="seo-checks" id={goodId}>
              {good.map((check) => (
                <li key={check.id} className="is-good">
                  <i aria-hidden="true" />
                  <div>
                    <strong>{check.label}</strong>
                    <p>{check.message}</p>
                  </div>
                </li>
              ))}
            </ul>
          ) : null}
        </>
      ) : null}
    </div>
  );
}
