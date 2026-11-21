import { useEffect, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import { RotateCcw, Trash2 } from 'lucide-react';
import { AppShell } from '../components/AppShell';
import { ConfirmDialog } from '../components/ConfirmDialog';
import { api } from '../api';
import { formatDate } from '../lib';
import { useAuth } from '../auth';
import { useTour } from '../tour/TourProvider';
import { AiVisibilityReportsPanel } from './AiVisibilityReportsScreen';

type ExtraMap = Record<string, { label?: string; value?: string | null } | string | null>;

type Message = {
  id: number;
  name: string;
  email: string;
  phone: string;
  destination: string;
  message: string;
  extra?: ExtraMap | null;
  created_at: string;
};

type Career = {
  id: number;
  name: string;
  email: string;
  phone: string;
  location: string;
  linkedin?: string;
  area: string;
  seniority: string;
  resume_name: string;
  extra?: ExtraMap | null;
  created_at: string;
};

type Tab = 'messages' | 'careers' | 'chatgpt';

function ExtraFields({ extra }: { extra?: ExtraMap | null }) {
  if (!extra || Object.keys(extra).length === 0) {
    return null;
  }

  return (
    <>
      {Object.entries(extra).map(([key, item]) => {
        const label = item && typeof item === 'object' ? (item.label ?? key) : key;
        const value = item && typeof item === 'object' ? (item.value ?? '—') : (item ?? '—');

        return (
          <p key={key}><strong>{label}</strong><br />{String(value || '—')}</p>
        );
      })}
    </>
  );
}

function tabFromParams(tipo: string | null, canMessages: boolean, canCareers: boolean, canChatgpt: boolean): Tab {
  if (tipo === 'chatgpt' && canChatgpt) {
    return 'chatgpt';
  }
  if (tipo === 'curriculos' && canCareers) {
    return 'careers';
  }
  if (canMessages) {
    return 'messages';
  }
  if (canCareers) {
    return 'careers';
  }
  if (canChatgpt) {
    return 'chatgpt';
  }

  return 'messages';
}

export function InboxScreen() {
  const { user } = useAuth();
  const { registerActions } = useTour();
  const [params, setParams] = useSearchParams();
  const canMessages = Boolean(user?.abilities.messages);
  const canCareers = Boolean(user?.abilities.careers);
  const canChatgpt = Boolean(user?.abilities.ai_visibility);
  const tab = tabFromParams(params.get('tipo'), canMessages, canCareers, canChatgpt);
  const [trashed, setTrashed] = useState(false);
  const [items, setItems] = useState<Array<Message | Career>>([]);
  const [current, setCurrent] = useState<Message | Career | null>(null);
  const [pendingDelete, setPendingDelete] = useState<Message | Career | null>(null);
  const [busy, setBusy] = useState(false);
  const canDelete = tab === 'messages' ? user?.abilities.messages_delete : user?.abilities.careers_delete;
  const subtitle = tab === 'chatgpt'
    ? 'URL e resultados gravados para estudo'
    : 'Contato, currículos e testes ChatGPT';

  async function load(next: 'messages' | 'careers', nextTrashed = trashed) {
    const qs = nextTrashed ? '?trashed=1' : '';
    const payload = await api<{ data: Array<Message | Career> }>(`/${next}${qs}`);
    setItems(payload.data);
    setCurrent(payload.data[0] ?? null);
  }

  useEffect(() => {
    if (tab === 'chatgpt') {
      return;
    }
    void load(tab, trashed);
  }, [tab, trashed]);

  useEffect(() => registerActions({
    'inbox.openCareers': () => setParams({ tipo: 'curriculos' }, { replace: true }),
    'inbox.openMessages': () => setParams({}, { replace: true }),
  }), [registerActions, setParams]);

  function selectTab(next: Tab) {
    setTrashed(false);
    if (next === 'careers') {
      setParams({ tipo: 'curriculos' }, { replace: true });
      return;
    }
    if (next === 'chatgpt') {
      setParams({ tipo: 'chatgpt' }, { replace: true });
      return;
    }
    setParams({}, { replace: true });
  }

  async function confirmDelete() {
    if (!pendingDelete || tab === 'chatgpt') {
      return;
    }
    setBusy(true);
    try {
      await api(`/${tab}/${pendingDelete.id}`, { method: 'DELETE' });
      setPendingDelete(null);
      await load(tab);
    } finally {
      setBusy(false);
    }
  }

  async function restore(item: Message | Career) {
    if (tab === 'chatgpt') {
      return;
    }
    setBusy(true);
    try {
      await api(`/${tab}/${item.id}/restore`, { method: 'POST' });
      await load(tab);
    } finally {
      setBusy(false);
    }
  }

  return (
    <AppShell title="Mensagens recebidas" subtitle={subtitle} inbox>
      <div className="inbox-head">
        <div className="inbox-tabs" data-tour="inbox-tabs">
          {canMessages ? (
            <button type="button" className={tab === 'messages' ? 'is-on' : ''} onClick={() => selectTab('messages')}>
              Contato
            </button>
          ) : null}
          {canCareers ? (
            <button type="button" className={tab === 'careers' ? 'is-on' : ''} onClick={() => selectTab('careers')}>
              Currículos
            </button>
          ) : null}
          {canChatgpt ? (
            <button type="button" className={tab === 'chatgpt' ? 'is-on' : ''} onClick={() => selectTab('chatgpt')}>
              Testes ChatGPT
            </button>
          ) : null}
        </div>
        {tab !== 'chatgpt' && canDelete ? (
          <div className="resource-tabs" data-tour="inbox-trash-tabs">
            <button type="button" className={!trashed ? 'is-on' : ''} onClick={() => setTrashed(false)}>
              Ativos
            </button>
            <button type="button" className={trashed ? 'is-on' : ''} onClick={() => setTrashed(true)}>
              Lixeira
            </button>
          </div>
        ) : null}
      </div>

      {tab === 'chatgpt' ? (
        <AiVisibilityReportsPanel />
      ) : (
        <div className="inbox-layout">
          <div className="inbox-list" data-tour="inbox-list">
            {items.length === 0 ? <div className="empty">{trashed ? 'Lixeira vazia.' : 'Nada por aqui.'}</div> : null}
            {items.map((item) => (
              <button
                key={item.id}
                type="button"
                className={`inbox-item${current?.id === item.id ? ' active' : ''}`}
                onClick={() => setCurrent(item)}
              >
                <strong>{item.name}</strong>
                <span>{item.email} · {formatDate(item.created_at)}</span>
              </button>
            ))}
          </div>
          <div className="inbox-detail" data-tour="inbox-detail">
            {!current ? (
              <div className="empty">Selecione um item.</div>
            ) : (
              <>
                <div className="section-header">
                  <div>
                    <h2>{current.name}</h2>
                    <p className="desc">{current.email}</p>
                  </div>
                  <div className="row-actions">
                    {trashed ? (
                      <button
                        className="btn btn-secondary"
                        type="button"
                        disabled={busy}
                        data-tour="inbox-restore"
                        onClick={() => void restore(current)}
                      >
                        <RotateCcw size={14} /> Recuperar
                      </button>
                    ) : (
                      <>
                        {tab === 'careers' ? (
                          <a
                            className="btn btn-secondary"
                            href={`${window.__ADMIN__.path}/api/careers/${current.id}/download`}
                            data-tour="inbox-download"
                          >
                            Baixar currículo
                          </a>
                        ) : null}
                        {canDelete ? (
                          <button
                            className="btn btn-danger"
                            type="button"
                            data-tour="inbox-delete"
                            onClick={() => setPendingDelete(current)}
                          >
                            <Trash2 size={14} /> Excluir
                          </button>
                        ) : null}
                      </>
                    )}
                  </div>
                </div>
                <div className="panel">
                  <div className="panel-body">
                    {'destination' in current ? (
                      <>
                        <p><strong>Telefone</strong><br />{current.phone}</p>
                        <p><strong>Destino</strong><br />{current.destination}</p>
                        <p><strong>Mensagem</strong><br />{current.message}</p>
                        <ExtraFields extra={current.extra} />
                      </>
                    ) : (
                      <>
                        <p><strong>Telefone</strong><br />{current.phone}</p>
                        <p><strong>Localização</strong><br />{current.location}</p>
                        <p><strong>Área</strong><br />{current.area} · {current.seniority}</p>
                        <p><strong>Arquivo</strong><br />{current.resume_name}</p>
                        <ExtraFields extra={current.extra} />
                      </>
                    )}
                  </div>
                </div>
              </>
            )}
          </div>
        </div>
      )}
      {pendingDelete ? (
        <ConfirmDialog
          title="Mover para a lixeira?"
          message="O registro sai da caixa de entrada, mas pode ser recuperado depois na lixeira."
          confirmLabel="Mover para a lixeira"
          busy={busy}
          onClose={() => setPendingDelete(null)}
          onConfirm={() => void confirmDelete()}
        />
      ) : null}
    </AppShell>
  );
}
