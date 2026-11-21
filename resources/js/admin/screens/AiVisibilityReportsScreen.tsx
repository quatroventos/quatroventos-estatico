import { useEffect, useState } from 'react';
import { ExternalLink, Trash2 } from 'lucide-react';
import { AppShell } from '../components/AppShell';
import { ConfirmDialog } from '../components/ConfirmDialog';
import { api } from '../api';
import { formatDate } from '../lib';
import { useAuth } from '../auth';

type ReportRow = {
  id: number;
  public_id: string;
  url: string;
  host: string;
  score: number;
  band: string;
  niche?: string | null;
  response_ms?: number | null;
  ip?: string | null;
  created_at: string;
};

type Report = ReportRow & {
  checks?: Array<{ label?: string; status?: string; points?: number; max?: number; message?: string }>;
  fixes?: Array<{ title?: string; impact?: string; minutes?: number; body?: string }>;
};

const bandLabel: Record<string, string> = {
  alto: 'Bom sinal',
  medio: 'Quase lá',
  baixo: 'Atenção',
};

export function AiVisibilityReportsScreen() {
  const { user } = useAuth();
  const [items, setItems] = useState<ReportRow[]>([]);
  const [current, setCurrent] = useState<Report | null>(null);
  const [query, setQuery] = useState('');
  const [pendingDelete, setPendingDelete] = useState<ReportRow | null>(null);
  const [busy, setBusy] = useState(false);
  const canDelete = Boolean(user?.abilities.ai_visibility_delete);

  async function load(search = query) {
    const qs = search.trim() ? `?q=${encodeURIComponent(search.trim())}` : '';
    const payload = await api<{ data: ReportRow[] }>(`/ai-visibility-reports${qs}`);
    setItems(payload.data);
    if (!payload.data.length) {
      setCurrent(null);
      return;
    }
    if (!current || !payload.data.some((row) => row.id === current.id)) {
      await open(payload.data[0]);
    }
  }

  async function open(row: ReportRow) {
    const payload = await api<{ data: Report }>(`/ai-visibility-reports/${row.public_id}`);
    setCurrent(payload.data);
  }

  useEffect(() => {
    void load('');
  }, []);

  async function confirmDelete() {
    if (!pendingDelete) {
      return;
    }
    setBusy(true);
    try {
      await api(`/ai-visibility-reports/${pendingDelete.public_id}`, { method: 'DELETE' });
      setPendingDelete(null);
      await load();
    } finally {
      setBusy(false);
    }
  }

  return (
    <AppShell title="Testes ChatGPT" subtitle="URL e resultados gravados para estudo">
      <div className="inbox-head">
        <form
          className="toolbar"
          onSubmit={(event) => {
            event.preventDefault();
            void load();
          }}
        >
          <input
            type="search"
            value={query}
            onChange={(event) => setQuery(event.target.value)}
            placeholder="Buscar por host, URL ou ID"
            aria-label="Buscar testes"
          />
          <button className="btn btn-secondary" type="submit">Buscar</button>
        </form>
      </div>

      <div className="inbox-layout">
        <div className="inbox-list" role="list">
          {items.map((row) => (
            <button
              key={row.id}
              type="button"
              role="listitem"
              className={current?.id === row.id ? 'is-on' : ''}
              onClick={() => void open(row)}
            >
              <strong>{row.host}</strong>
              <span>{row.score}/100 · {bandLabel[row.band] ?? row.band}</span>
              <span>{formatDate(row.created_at)}</span>
            </button>
          ))}
          {!items.length ? <p className="empty">Nenhum teste gravado ainda.</p> : null}
        </div>

        <div className="inbox-detail">
          {current ? (
            <>
              <div className="inbox-detail-head">
                <div>
                  <h2>{current.host}</h2>
                  <p>{formatDate(current.created_at)}</p>
                </div>
                <div className="actions">
                  <a className="btn btn-secondary" href={`/como-aparecer-no-chatgpt/${current.public_id}`} target="_blank" rel="noreferrer">
                    Abrir resultado <ExternalLink size={14} aria-hidden="true" />
                  </a>
                  {canDelete ? (
                    <button className="icon-btn" type="button" aria-label="Excluir" onClick={() => setPendingDelete(current)}>
                      <Trash2 size={16} />
                    </button>
                  ) : null}
                </div>
              </div>

              <p><strong>URL</strong><br />{current.url}</p>
              <p><strong>Nota</strong><br />{current.score} de 100 ({bandLabel[current.band] ?? current.band})</p>
              {current.niche ? <p><strong>Área</strong><br />{current.niche}</p> : null}
              {current.ip ? <p><strong>IP</strong><br />{current.ip}</p> : null}
              {current.response_ms != null ? <p><strong>Tempo de resposta</strong><br />{current.response_ms} ms</p> : null}
              <p><strong>ID público</strong><br />{current.public_id}</p>

              {current.checks?.length ? (
                <div>
                  <h3>Itens</h3>
                  <ul>
                    {current.checks.map((check, index) => (
                      <li key={`${check.label}-${index}`}>
                        <strong>{check.label}</strong> — {check.points}/{check.max} ({check.status})
                        {check.message ? <div>{check.message}</div> : null}
                      </li>
                    ))}
                  </ul>
                </div>
              ) : null}
            </>
          ) : (
            <p className="empty">Selecione um teste.</p>
          )}
        </div>
      </div>

      {pendingDelete ? (
        <ConfirmDialog
          title="Excluir este teste?"
          message="O registro sai do painel e do banco. O link público do resultado deixa de funcionar."
          confirmLabel="Excluir"
          busy={busy}
          onClose={() => setPendingDelete(null)}
          onConfirm={() => void confirmDelete()}
        />
      ) : null}
    </AppShell>
  );
}
