import { type FormEvent, useEffect, useMemo, useState } from 'react';
import { Check, Pencil, Trash2, X } from 'lucide-react';
import { api, firstError } from '../api';
import { formatDate } from '../lib';
import { ConfirmDialog } from './ConfirmDialog';

type Redirect = {
  id: number;
  source: string;
  target: string;
  automatic: boolean;
  hits: number;
  last_hit_at: string | null;
  updated_at: string | null;
};

type Draft = { source: string; target: string };

const emptyDraft: Draft = { source: '', target: '' };

export function SeoRedirects({ canEdit }: { canEdit: boolean }) {
  const [rows, setRows] = useState<Redirect[]>([]);
  const [draft, setDraft] = useState<Draft>(emptyDraft);
  const [editing, setEditing] = useState<number | null>(null);
  const [edit, setEdit] = useState<Draft>(emptyDraft);
  const [pendingDelete, setPendingDelete] = useState<Redirect | null>(null);
  const [filter, setFilter] = useState('');
  const [busy, setBusy] = useState(false);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [ok, setOk] = useState('');

  async function load() {
    const response = await api<{ data: Redirect[] }>('/redirects');
    setRows(response.data);
  }

  useEffect(() => {
    void load().catch((err) => setError(firstError(err))).finally(() => setLoading(false));
  }, []);

  const filtered = useMemo(() => {
    const term = filter.trim().toLowerCase();

    return term === ''
      ? rows
      : rows.filter((row) => row.source.toLowerCase().includes(term) || row.target.toLowerCase().includes(term));
  }, [rows, filter]);

  async function run(action: () => Promise<unknown>, message: string) {
    setBusy(true);
    setError('');
    setOk('');
    try {
      await action();
      await load();
      setOk(message);
      return true;
    } catch (err) {
      setError(firstError(err));
      return false;
    } finally {
      setBusy(false);
    }
  }

  async function create(event: FormEvent) {
    event.preventDefault();
    const saved = await run(
      () => api('/redirects', { method: 'POST', body: JSON.stringify(draft) }),
      'Redirect criado.',
    );
    if (saved) {
      setDraft(emptyDraft);
    }
  }

  function startEdit(row: Redirect) {
    setEditing(row.id);
    setEdit({ source: row.source, target: row.target });
  }

  async function saveEdit(id: number) {
    const saved = await run(
      () => api(`/redirects/${id}`, { method: 'PUT', body: JSON.stringify(edit) }),
      'Redirect atualizado.',
    );
    if (saved) {
      setEditing(null);
    }
  }

  async function confirmDelete() {
    if (!pendingDelete) {
      return;
    }
    const id = pendingDelete.id;
    const deleted = await run(() => api(`/redirects/${id}`, { method: 'DELETE' }), 'Redirect excluído.');
    if (deleted) {
      setPendingDelete(null);
    }
  }

  return (
    <div className="panel">
      <div className="panel-head">
        <h3>Redirects 301</h3>
        <p>Quando o slug de uma página ou case publicado muda, o endereço antigo entra aqui sozinho, apontando para o novo. Cada origem vai direto ao destino final, em um salto só.</p>
      </div>
      <div className="panel-body">
        {error ? <div className="form-error">{error}</div> : null}
        {ok ? <div className="form-success">{ok}</div> : null}
        {canEdit ? (
          <form className="redirect-form" onSubmit={(event) => void create(event)}>
            <div className="field">
              <label>Origem</label>
              <input value={draft.source} onChange={(event) => setDraft({ ...draft, source: event.target.value })} placeholder="/endereco-antigo" required />
            </div>
            <div className="field">
              <label>Destino</label>
              <input value={draft.target} onChange={(event) => setDraft({ ...draft, target: event.target.value })} placeholder="/endereco-novo ou https://..." required />
            </div>
            <button className="btn btn-primary" type="submit" disabled={busy}>Adicionar</button>
          </form>
        ) : null}
        <div className="field">
          <label>Buscar</label>
          <input value={filter} onChange={(event) => setFilter(event.target.value)} placeholder="Filtrar por origem ou destino" />
        </div>
        <div className="table-panel">
          {loading ? (
            <div className="auth-loading"><div className="spinner" /></div>
          ) : filtered.length === 0 ? (
            <div className="empty">{rows.length === 0 ? 'Nenhum redirect cadastrado.' : 'Nenhum redirect encontrado.'}</div>
          ) : (
            <table>
              <thead>
                <tr>
                  <th>Origem</th>
                  <th>Destino</th>
                  <th>Tipo</th>
                  <th>Acessos</th>
                  <th>Último acesso</th>
                  {canEdit ? <th /> : null}
                </tr>
              </thead>
              <tbody>
                {filtered.map((row) => (
                  <tr key={row.id}>
                    {editing === row.id ? (
                      <>
                        <td><input value={edit.source} onChange={(event) => setEdit({ ...edit, source: event.target.value })} /></td>
                        <td><input value={edit.target} onChange={(event) => setEdit({ ...edit, target: event.target.value })} /></td>
                      </>
                    ) : (
                      <>
                        <td>{row.source}</td>
                        <td>{row.target}</td>
                      </>
                    )}
                    <td>
                      <span className={`badge ${row.automatic ? 'badge-green' : 'badge-neutral'}`}>{row.automatic ? 'Automático' : 'Manual'}</span>
                    </td>
                    <td>{row.hits}</td>
                    <td>{row.last_hit_at ? formatDate(row.last_hit_at) : '—'}</td>
                    {canEdit ? (
                      <td>
                        <div className="row-actions">
                          {editing === row.id ? (
                            <>
                              <button className="icon-btn" type="button" disabled={busy} onClick={() => void saveEdit(row.id)} aria-label="Salvar">
                                <Check size={14} />
                              </button>
                              <button className="icon-btn" type="button" disabled={busy} onClick={() => setEditing(null)} aria-label="Cancelar">
                                <X size={14} />
                              </button>
                            </>
                          ) : (
                            <>
                              <button className="icon-btn" type="button" onClick={() => startEdit(row)} aria-label="Editar">
                                <Pencil size={14} />
                              </button>
                              <button className="icon-btn danger" type="button" onClick={() => setPendingDelete(row)} aria-label="Excluir">
                                <Trash2 size={14} />
                              </button>
                            </>
                          )}
                        </div>
                      </td>
                    ) : null}
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </div>
      </div>
      {pendingDelete ? (
        <ConfirmDialog
          title="Excluir redirect?"
          message={`${pendingDelete.source} deixa de redirecionar para ${pendingDelete.target} e passa a responder 404, se não houver página nesse endereço.`}
          busy={busy}
          onClose={() => setPendingDelete(null)}
          onConfirm={() => void confirmDelete()}
        />
      ) : null}
    </div>
  );
}
