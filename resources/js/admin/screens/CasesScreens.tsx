import { type ReactNode, useEffect, useMemo, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { ArrowDown, ArrowUp, ExternalLink, Pencil, Plus, RotateCcw, Star, Trash2 } from 'lucide-react';
import { api, firstError } from '../api';
import { AppShell } from '../components/AppShell';
import { LangDots } from '../components/LangDots';
import { TranslationTabs, useTranslationConfig, useTranslationJob, type TranslationState } from '../components/TranslationBar';
import { ConfirmDialog } from '../components/ConfirmDialog';
import { MediaField } from '../components/MediaField';
import { RichText } from '../components/RichText';

type Option = { value: string; label: string };

type GalleryItem = { path: string; caption?: string; alt?: string };

type ResultItem = { value?: string; label?: string };

type CaseSummary = {
  id: number;
  slug: string;
  status: 'draft' | 'published';
  client: string;
  title: string;
  sector: string | null;
  services: string[];
  cover_path: string | null;
  featured: boolean;
  sort: number;
  translations?: Record<string, TranslationState>;
};

type CaseDetail = {
  id?: number;
  locale?: string;
  path?: string;
  translation_of?: number | null;
  source_id?: number;
  stale?: boolean;
  translations?: Record<string, TranslationState>;
  slug: string;
  status: 'draft' | 'published';
  client: string;
  title: string;
  summary: string;
  sector: string;
  services: string[];
  year: number | null;
  cover_path: string | null;
  cover_alt: string;
  gallery: GalleryItem[];
  challenge: string;
  solution: string;
  deliverables: string[];
  stack: string[];
  results: ResultItem[];
  testimonial: { quote?: string; author?: string; role?: string } | null;
  url: string;
  featured: boolean;
  sort: number;
  seo_title: string;
  seo_description: string;
};

const empty: CaseDetail = {
  slug: '',
  status: 'draft',
  client: '',
  title: '',
  summary: '',
  sector: '',
  services: [],
  year: null,
  cover_path: null,
  cover_alt: '',
  gallery: [],
  challenge: '',
  solution: '',
  deliverables: [],
  stack: [],
  results: [],
  testimonial: null,
  url: '',
  featured: false,
  sort: 0,
  seo_title: '',
  seo_description: '',
};

function slugify(value: string): string {
  return value
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

function normalize(raw: Partial<CaseDetail> & Record<string, unknown>): CaseDetail {
  return {
    ...empty,
    ...raw,
    summary: (raw.summary as string) ?? '',
    sector: (raw.sector as string) ?? '',
    cover_alt: (raw.cover_alt as string) ?? '',
    challenge: (raw.challenge as string) ?? '',
    solution: (raw.solution as string) ?? '',
    url: (raw.url as string) ?? '',
    seo_title: (raw.seo_title as string) ?? '',
    seo_description: (raw.seo_description as string) ?? '',
    services: Array.isArray(raw.services) ? raw.services : [],
    gallery: Array.isArray(raw.gallery) ? raw.gallery : [],
    deliverables: Array.isArray(raw.deliverables) ? raw.deliverables : [],
    stack: Array.isArray(raw.stack) ? raw.stack : [],
    results: Array.isArray(raw.results) ? raw.results : [],
  };
}

function move<T>(items: T[], from: number, to: number): T[] {
  if (to < 0 || to >= items.length) {
    return items;
  }
  const copy = [...items];
  const [item] = copy.splice(from, 1);
  copy.splice(to, 0, item);
  return copy;
}

export function CasesScreen() {
  const navigate = useNavigate();
  const [rows, setRows] = useState<CaseSummary[]>([]);
  const [services, setServices] = useState<Option[]>([]);
  const [sectors, setSectors] = useState<Option[]>([]);
  const [trashed, setTrashed] = useState(false);
  const [query, setQuery] = useState('');
  const [pendingDelete, setPendingDelete] = useState<CaseSummary | null>(null);
  const [busy, setBusy] = useState(false);

  async function load(nextTrashed = trashed) {
    const payload = await api<{ data: CaseSummary[]; services: Option[]; sectors: Option[] }>(`/cases${nextTrashed ? '?trashed=1' : ''}`);
    setRows(payload.data);
    setServices(payload.services);
    setSectors(payload.sectors);
  }

  useEffect(() => {
    void load(trashed);
  }, [trashed]);

  const serviceLabel = useMemo(() => new Map(services.map((item) => [item.value, item.label])), [services]);
  const sectorLabel = useMemo(() => new Map(sectors.map((item) => [item.value, item.label])), [sectors]);
  const filtered = rows.filter((row) => `${row.client} ${row.title} ${row.slug}`.toLowerCase().includes(query.toLowerCase()));

  async function confirmDelete() {
    if (!pendingDelete) {
      return;
    }
    setBusy(true);
    try {
      await api(`/cases/${pendingDelete.id}`, { method: 'DELETE' });
      setPendingDelete(null);
      await load();
    } finally {
      setBusy(false);
    }
  }

  async function restore(row: CaseSummary) {
    setBusy(true);
    try {
      await api(`/cases/${row.id}/restore`, { method: 'POST' });
      await load();
    } finally {
      setBusy(false);
    }
  }

  return (
    <AppShell
      eyebrow="Conteúdo"
      title="Cases"
      subtitle="Projetos com prints e detalhamento. Cada case pode aparecer nas páginas dos serviços vinculados."
      actions={!trashed ? (
        <button className="btn btn-primary" type="button" onClick={() => navigate('/cases/novo')}>
          <Plus size={16} /> Novo case
        </button>
      ) : undefined}
    >
      <div className="toolbar">
        <input className="search" placeholder="Buscar" value={query} onChange={(event) => setQuery(event.target.value)} />
        <div className="resource-tabs">
          <button type="button" className={!trashed ? 'is-on' : ''} onClick={() => setTrashed(false)}>Ativos</button>
          <button type="button" className={trashed ? 'is-on' : ''} onClick={() => setTrashed(true)}>Lixeira</button>
        </div>
      </div>
      <div className="panel table-panel">
        {filtered.length === 0 ? (
          <div className="empty">{trashed ? 'Lixeira vazia.' : 'Nenhum case cadastrado.'}</div>
        ) : (
          <table>
            <thead>
              <tr>
                <th />
                <th>Cliente</th>
                <th>Título</th>
                <th>Setor</th>
                <th>Serviços</th>
                <th>Status</th>
                <th>Idiomas</th>
                <th />
              </tr>
            </thead>
            <tbody>
              {filtered.map((row) => (
                <tr key={row.id}>
                  <td>{row.cover_path ? <img className="case-thumb" src={row.cover_path} alt="" /> : <span className="case-thumb is-empty" />}</td>
                  <td>
                    <strong>{row.client}</strong>
                    {row.featured ? <Star size={12} className="case-star" aria-label="Destaque" /> : null}
                  </td>
                  <td>{row.title}</td>
                  <td>{row.sector ? sectorLabel.get(row.sector) ?? row.sector : '—'}</td>
                  <td>{row.services.length ? row.services.map((slug) => serviceLabel.get(slug) ?? slug).join(', ') : '—'}</td>
                  <td>{row.status === 'published' ? 'Publicado' : 'Rascunho'}</td>
                  <td><LangDots translations={row.translations} linkTo={(translationId) => `/cases/${translationId}`} /></td>
                  <td>
                    <div className="row-actions">
                      {trashed ? (
                        <button className="icon-btn" type="button" disabled={busy} onClick={() => void restore(row)} aria-label="Recuperar">
                          <RotateCcw size={14} />
                        </button>
                      ) : (
                        <>
                          {row.status === 'published' ? (
                            <a className="icon-btn" href={`/cases/${row.slug}`} target="_blank" rel="noreferrer" aria-label="Ver no site">
                              <ExternalLink size={14} />
                            </a>
                          ) : null}
                          <button className="icon-btn" type="button" onClick={() => navigate(`/cases/${row.id}`)} aria-label="Editar">
                            <Pencil size={14} />
                          </button>
                          <button className="icon-btn danger" type="button" onClick={() => setPendingDelete(row)} aria-label="Excluir">
                            <Trash2 size={14} />
                          </button>
                        </>
                      )}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>
      {pendingDelete ? (
        <ConfirmDialog
          title="Mover para a lixeira?"
          message="O case sai do site e das páginas de serviço, mas pode ser recuperado depois na lixeira."
          confirmLabel="Mover para a lixeira"
          busy={busy}
          onClose={() => setPendingDelete(null)}
          onConfirm={() => void confirmDelete()}
        />
      ) : null}
    </AppShell>
  );
}

function Box({ title, hint, children }: { title: string; hint?: string; children: ReactNode }) {
  return (
    <div className="panel case-box">
      <div className="panel-head">
        <h3>{title}</h3>
        {hint ? <p>{hint}</p> : null}
      </div>
      <div className="panel-body">{children}</div>
    </div>
  );
}

function StringList({ label, value, placeholder, onChange }: { label: string; value: string[]; placeholder?: string; onChange: (value: string[]) => void }) {
  return (
    <div className="field">
      <label>{label}</label>
      {value.map((item, index) => (
        <div className="case-list-row" key={index}>
          <input
            value={item}
            placeholder={placeholder}
            onChange={(event) => onChange(value.map((current, i) => (i === index ? event.target.value : current)))}
          />
          <button className="icon-btn danger" type="button" onClick={() => onChange(value.filter((_, i) => i !== index))} aria-label="Remover">
            <Trash2 size={14} />
          </button>
        </div>
      ))}
      <button className="btn btn-secondary" type="button" onClick={() => onChange([...value, ''])}>
        <Plus size={14} /> Adicionar
      </button>
    </div>
  );
}

export function CaseEditorScreen() {
  const { id } = useParams();
  const navigate = useNavigate();
  const creating = id === 'novo';
  const [form, setForm] = useState<CaseDetail | null>(creating ? { ...empty } : null);
  const [baseline, setBaseline] = useState<string>(creating ? JSON.stringify(empty) : '');
  const [services, setServices] = useState<Option[]>([]);
  const [sectors, setSectors] = useState<Option[]>([]);
  const [slugTouched, setSlugTouched] = useState(!creating);
  const [error, setError] = useState('');
  const [saved, setSaved] = useState(false);
  const [saving, setSaving] = useState(false);
  const [reloadKey, setReloadKey] = useState(0);
  const translationConfig = useTranslationConfig();
  const translationJob = useTranslationJob('case', form?.source_id ?? null, (_locale, translationId) => {
    if (translationId !== null) {
      navigate(`/cases/${translationId}`);
      return;
    }
    setReloadKey((value) => value + 1);
  });

  useEffect(() => {
    if (creating) {
      void api<{ services: Option[]; sectors: Option[] }>('/cases').then((payload) => {
        setServices(payload.services);
        setSectors(payload.sectors);
      });
      return;
    }

    void api<{ data: Record<string, unknown>; services: Option[]; sectors: Option[] }>(`/cases/${id}`).then((payload) => {
      const next = normalize(payload.data);
      setForm(next);
      setBaseline(JSON.stringify(next));
      setServices(payload.services);
      setSectors(payload.sectors);
    });
  }, [id, creating, reloadKey]);

  if (!form) {
    return (
      <AppShell title="Cases">
        <div className="auth-loading"><div className="spinner" /></div>
      </AppShell>
    );
  }

  const dirty = JSON.stringify(form) !== baseline;

  function patch(next: Partial<CaseDetail>) {
    setForm((current) => (current ? { ...current, ...next } : current));
    setSaved(false);
  }

  function toggleService(slug: string) {
    if (!form) {
      return;
    }
    patch({
      services: form.services.includes(slug)
        ? form.services.filter((item) => item !== slug)
        : [...form.services, slug],
    });
  }

  async function save() {
    if (!form) {
      return;
    }
    setSaving(true);
    setError('');
    try {
      const payload = await api<{ data: Record<string, unknown> }>(creating ? '/cases' : `/cases/${id}`, {
        method: creating ? 'POST' : 'PUT',
        body: JSON.stringify({ ...form, gallery: form.gallery.filter((item) => item.path) }),
      });
      const next = normalize(payload.data);
      setForm(next);
      setBaseline(JSON.stringify(next));
      setSaved(true);
      if (creating && next.id) {
        navigate(`/cases/${next.id}`, { replace: true });
      }
    } catch (err) {
      setError(firstError(err));
    } finally {
      setSaving(false);
    }
  }

  const testimonial: NonNullable<CaseDetail['testimonial']> = form.testimonial ?? {};

  return (
    <AppShell
      eyebrow="Cases"
      title={creating ? 'Novo case' : form.client || 'Case'}
      subtitle={form.status === 'published' && !creating ? form.path ?? `/cases/${form.slug}` : 'Rascunho não aparece no site.'}
      actions={(
        <div className="form-actions case-actions">
          <button className="btn btn-secondary" type="button" onClick={() => navigate('/cases')}>Voltar</button>
          {!creating && form.status === 'published' ? (
            <a className="btn btn-secondary" href={form.path ?? `/cases/${form.slug}`} target="_blank" rel="noreferrer">
              <ExternalLink size={14} /> Ver no site
            </a>
          ) : null}
          <button className="btn btn-primary" type="button" disabled={!dirty || saving} onClick={() => void save()}>
            {creating ? 'Criar case' : 'Salvar'}
          </button>
        </div>
      )}
    >
      {!creating && form.translations ? (
        <div className="case-langbar">
          <TranslationTabs
            current={form.locale ?? translationConfig?.default ?? 'pt-BR'}
            translations={form.translations}
            config={translationConfig}
            running={translationJob.running}
            onOpen={(locale) => {
              if (locale === translationConfig?.default) {
                navigate(`/cases/${form.source_id}`);
                return;
              }
              const target = form.translations?.[locale];
              if (target?.id) {
                navigate(`/cases/${target.id}`);
              }
            }}
            onTranslate={(locale) => void translationJob.start(locale)}
          />
          {form.translation_of ? (
            <button
              className="btn btn-secondary"
              type="button"
              disabled={!translationConfig?.configured || Boolean(form.locale && translationJob.running[form.locale])}
              onClick={() => {
                if (form.locale && window.confirm('Substituir esta tradução por uma nova tradução do original em português?')) {
                  void translationJob.start(form.locale);
                }
              }}
            >
              Retraduzir a partir do PT
            </button>
          ) : null}
        </div>
      ) : null}
      {translationJob.error ? <div className="form-error">{translationJob.error}</div> : null}
      {form.translation_of && form.stale ? <div className="form-warning">O original em português mudou depois desta tradução. Revise ou retraduza.</div> : null}
      {error ? <div className="form-error">{error}</div> : null}
      {saved && !dirty ? <div className="form-success">Case salvo.</div> : null}

      <div className="case-editor">
        <div className="case-main">
          <Box title="Identificação">
            <div className="case-grid-2">
              <div className="field">
                <label>Cliente</label>
                <input
                  value={form.client}
                  onChange={(event) => patch({
                    client: event.target.value,
                    ...(slugTouched ? {} : { slug: slugify(event.target.value) }),
                  })}
                />
              </div>
              <div className="field">
                <label>Endereço</label>
                <div className="slug-field">
                  <span>/cases/</span>
                  <input
                    value={form.slug}
                    onChange={(event) => {
                      setSlugTouched(true);
                      patch({ slug: event.target.value.toLowerCase().replace(/[^a-z0-9-]/g, '') });
                    }}
                  />
                </div>
              </div>
            </div>
            <div className="field">
              <label>Título</label>
              <input value={form.title} placeholder="O processo melhorado, em uma frase" onChange={(event) => patch({ title: event.target.value })} />
            </div>
            <div className="field">
              <label>Resumo</label>
              <textarea maxLength={300} value={form.summary} onChange={(event) => patch({ summary: event.target.value })} />
              <p className="hint">{form.summary.length}/300. Aparece no card da listagem e no bloco das páginas de serviço.</p>
            </div>
            <div className="case-grid-2">
              <div className="field">
                <label>Setor</label>
                <select value={form.sector} onChange={(event) => patch({ sector: event.target.value })}>
                  <option value="">Selecione</option>
                  {sectors.map((option) => <option key={option.value} value={option.value}>{option.label}</option>)}
                </select>
              </div>
              <div className="field">
                <label>Ano</label>
                <input
                  type="number"
                  value={form.year ?? ''}
                  onChange={(event) => patch({ year: event.target.value === '' ? null : Number(event.target.value) })}
                />
              </div>
            </div>
            <div className="field">
              <label>Link do projeto</label>
              <input value={form.url} placeholder="https://" onChange={(event) => patch({ url: event.target.value })} />
            </div>
          </Box>

          <Box title="Prints" hint="A capa abre o case e ilustra o card. A galeria segue a ordem abaixo; os três primeiros aparecem nas páginas de serviço.">
            <MediaField label="Capa (16:10)" value={form.cover_path} onChange={(path) => patch({ cover_path: path })} />
            <div className="field">
              <label>Alt da capa</label>
              <input value={form.cover_alt} onChange={(event) => patch({ cover_alt: event.target.value })} />
            </div>
            <div className="field">
              <label>Galeria</label>
              {form.gallery.map((item, index) => (
                <div className="repeater-item case-gallery-item" key={index}>
                  <MediaField
                    label={`Print ${index + 1}`}
                    value={item.path}
                    onChange={(path) => patch({ gallery: form.gallery.map((current, i) => (i === index ? { ...current, path: path ?? '' } : current)) })}
                  />
                  <div className="field">
                    <label>Legenda</label>
                    <input
                      value={item.caption ?? ''}
                      onChange={(event) => patch({ gallery: form.gallery.map((current, i) => (i === index ? { ...current, caption: event.target.value } : current)) })}
                    />
                  </div>
                  <div className="field">
                    <label>Alt</label>
                    <input
                      value={item.alt ?? ''}
                      onChange={(event) => patch({ gallery: form.gallery.map((current, i) => (i === index ? { ...current, alt: event.target.value } : current)) })}
                    />
                  </div>
                  <div className="row-actions">
                    <button className="icon-btn" type="button" disabled={index === 0} onClick={() => patch({ gallery: move(form.gallery, index, index - 1) })} aria-label="Subir">
                      <ArrowUp size={14} />
                    </button>
                    <button className="icon-btn" type="button" disabled={index === form.gallery.length - 1} onClick={() => patch({ gallery: move(form.gallery, index, index + 1) })} aria-label="Descer">
                      <ArrowDown size={14} />
                    </button>
                    <button className="icon-btn danger" type="button" onClick={() => patch({ gallery: form.gallery.filter((_, i) => i !== index) })} aria-label="Remover">
                      <Trash2 size={14} />
                    </button>
                  </div>
                </div>
              ))}
              <button className="btn btn-secondary" type="button" onClick={() => patch({ gallery: [...form.gallery, { path: '', caption: '', alt: '' }] })}>
                <Plus size={14} /> Adicionar print
              </button>
            </div>
          </Box>

          <Box title="Detalhamento">
            <div className="field">
              <label>Desafio</label>
              <RichText value={form.challenge} onChange={(value) => patch({ challenge: value })} />
              <p className="hint">O processo ou problema antes do projeto.</p>
            </div>
            <div className="field">
              <label>Solução</label>
              <RichText value={form.solution} onChange={(value) => patch({ solution: value })} />
              <p className="hint">O que foi construído e como a IA, a automação ou o software resolveram.</p>
            </div>
            <StringList label="Entregáveis" value={form.deliverables} placeholder="Agente no WhatsApp integrado ao CRM" onChange={(deliverables) => patch({ deliverables })} />
            <StringList label="Stack" value={form.stack} placeholder="Laravel" onChange={(stack) => patch({ stack })} />
            <div className="field">
              <label>Resultados</label>
              {form.results.map((item, index) => (
                <div className="case-list-row" key={index}>
                  <input
                    className="case-result-value"
                    value={item.value ?? ''}
                    placeholder="Valor"
                    onChange={(event) => patch({ results: form.results.map((current, i) => (i === index ? { ...current, value: event.target.value } : current)) })}
                  />
                  <input
                    value={item.label ?? ''}
                    placeholder="Descrição do resultado"
                    onChange={(event) => patch({ results: form.results.map((current, i) => (i === index ? { ...current, label: event.target.value } : current)) })}
                  />
                  <button className="icon-btn danger" type="button" onClick={() => patch({ results: form.results.filter((_, i) => i !== index) })} aria-label="Remover">
                    <Trash2 size={14} />
                  </button>
                </div>
              ))}
              <button className="btn btn-secondary" type="button" onClick={() => patch({ results: [...form.results, { value: '', label: '' }] })}>
                <Plus size={14} /> Adicionar resultado
              </button>
              <p className="hint">Só números reais, medidos ou informados pelo cliente. Sem resultado, o bloco não aparece no site.</p>
            </div>
          </Box>

          <Box title="Depoimento" hint="Opcional. Use apenas depoimentos publicados ou autorizados.">
            <div className="field">
              <label>Texto</label>
              <textarea value={testimonial.quote ?? ''} onChange={(event) => patch({ testimonial: { ...testimonial, quote: event.target.value } })} />
            </div>
            <div className="case-grid-2">
              <div className="field">
                <label>Autor</label>
                <input value={testimonial.author ?? ''} onChange={(event) => patch({ testimonial: { ...testimonial, author: event.target.value } })} />
              </div>
              <div className="field">
                <label>Cargo e empresa</label>
                <input value={testimonial.role ?? ''} onChange={(event) => patch({ testimonial: { ...testimonial, role: event.target.value } })} />
              </div>
            </div>
          </Box>
        </div>

        <aside className="case-aside">
          <Box title="Publicação">
            <div className="status-pills">
              <button type="button" className={form.status === 'draft' ? 'is-on' : ''} onClick={() => patch({ status: 'draft' })}>Rascunho</button>
              <button type="button" className={form.status === 'published' ? 'is-on' : ''} onClick={() => patch({ status: 'published' })}>Publicado</button>
            </div>
            <div className="field">
              <label>
                <input type="checkbox" checked={form.featured} onChange={(event) => patch({ featured: event.target.checked })} /> Destaque em /cases
              </label>
            </div>
            <div className="field">
              <label>Ordem</label>
              <input type="number" value={form.sort} onChange={(event) => patch({ sort: Number(event.target.value) || 0 })} />
            </div>
          </Box>

          <Box title="Serviços" hint="O case fica disponível no bloco de cases destas páginas e no filtro de /cases.">
            <div className="case-checks">
              {services.map((option) => (
                <label key={option.value}>
                  <input type="checkbox" checked={form.services.includes(option.value)} onChange={() => toggleService(option.value)} />
                  <span>{option.label}</span>
                </label>
              ))}
              {services.length === 0 ? <p className="hint">Nenhuma página de serviço cadastrada.</p> : null}
            </div>
          </Box>

          <Box title="SEO" hint="Vazio usa o título e o resumo do case.">
            <div className="field">
              <label>Título SEO</label>
              <input value={form.seo_title} placeholder={form.title ? `${form.title} | Case ${form.client}` : ''} onChange={(event) => patch({ seo_title: event.target.value })} />
            </div>
            <div className="field">
              <label>Description</label>
              <textarea value={form.seo_description} placeholder={form.summary} onChange={(event) => patch({ seo_description: event.target.value })} />
            </div>
          </Box>
        </aside>
      </div>
    </AppShell>
  );
}
