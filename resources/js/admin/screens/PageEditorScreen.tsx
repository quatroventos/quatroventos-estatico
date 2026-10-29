import { type ReactNode, useEffect, useMemo, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { ChevronDown, ChevronRight, History, PanelLeft, PanelLeftClose, Save, X } from 'lucide-react';
import { api, firstError } from '../api';
import { AppShell } from '../components/AppShell';
import { SchemaForm, type Section } from '../components/SchemaForm';
import { MediaField } from '../components/MediaField';
import { PageOutline, type OutlineSection } from '../components/PageOutline';
import type { LibraryItem } from '../components/SectionPicker';
import { GooglePreview } from '../components/GooglePreview';
import { Modal } from '../components/Modal';
import { formatDate } from '../lib';
import { composeSeoTitle, resolveSeoDescription, type SeoDefaults } from '../seo';
import { useTour } from '../tour/TourProvider';
import { TranslationTabs, useTranslationConfig, useTranslationJob, type TranslationState } from '../components/TranslationBar';

type PageSection = {
  id: string;
  type: string;
  data: Record<string, unknown>;
};

type PageDetail = {
  id: number;
  slug: string;
  locale: string;
  path: string;
  translation_of: number | null;
  source_id: number;
  source_slug: string;
  stale: boolean;
  translated_at: string | null;
  translations: Record<string, TranslationState>;
  template: string;
  status: string;
  seo_title: string;
  seo_description: string | null;
  canonical: string | null;
  og_image: string | null;
  og_image_alt: string | null;
  robots: string | null;
  twitter_card: string | null;
  content: { sections: PageSection[] };
};

type LibraryGroup = {
  group: string;
  group_label: string;
  items: LibraryItem[];
};

type PageRevision = {
  id: number;
  created_at: string | null;
  user_name: string | null;
  status: string | null;
  seo_title: string | null;
};

function newId(): string {
  if (typeof crypto !== 'undefined' && 'randomUUID' in crypto) {
    return crypto.randomUUID().replace(/-/g, '').slice(0, 26);
  }
  return `s${Date.now().toString(36)}${Math.random().toString(36).slice(2, 10)}`;
}

function EditorBox({
  title,
  open,
  onToggle,
  children,
}: {
  title: string;
  open: boolean;
  onToggle: () => void;
  children: ReactNode;
}) {
  return (
    <section className={`editor-box${open ? ' is-open' : ''}`}>
      <button type="button" className="editor-box-toggle" onClick={onToggle}>
        {open ? <ChevronDown size={14} /> : <ChevronRight size={14} />}
        {title}
      </button>
      {open ? <div className="editor-box-body">{children}</div> : null}
    </section>
  );
}

export function PageEditorScreen() {
  const { slug } = useParams();
  const navigate = useNavigate();
  const { registerActions } = useTour();
  const [page, setPage] = useState<PageDetail | null>(null);
  const [baseline, setBaseline] = useState<string | null>(null);
  const [schema, setSchema] = useState<OutlineSection[]>([]);
  const [library, setLibrary] = useState<LibraryGroup[]>([]);
  const [defaults, setDefaults] = useState<SeoDefaults | null>(null);
  const [section, setSection] = useState(0);
  const [error, setError] = useState('');
  const [saved, setSaved] = useState(false);
  const [sidebarOpen, setSidebarOpen] = useState(() => localStorage.getItem('cms-page-sidebar') !== '0');
  const [boxes, setBoxes] = useState({ page: true, seo: true });
  const [versionsOpen, setVersionsOpen] = useState(false);
  const [revisions, setRevisions] = useState<PageRevision[]>([]);
  const [revisionsLoading, setRevisionsLoading] = useState(false);
  const [restoreId, setRestoreId] = useState<number | null>(null);
  const [restoring, setRestoring] = useState(false);
  const [removeIndex, setRemoveIndex] = useState<number | null>(null);
  const [leavingId, setLeavingId] = useState<string | null>(null);
  const libraryItems = useMemo(() => library.flatMap((group) => group.items), [library]);
  const [canvasFlash, setCanvasFlash] = useState(0);
  const [retranslateOpen, setRetranslateOpen] = useState(false);
  const translationConfig = useTranslationConfig();
  const translationJob = useTranslationJob('page', page?.source_id ?? null, (locale, id) => {
    if (id !== null) {
      navigate(`/paginas/${id}`);
      return;
    }
    void reload();
  });

  useEffect(() => {
    if (!canvasFlash) {
      return;
    }
    const timer = window.setTimeout(() => setCanvasFlash(0), 1000);
    return () => window.clearTimeout(timer);
  }, [canvasFlash]);

  useEffect(() => {
    if (!leavingId || !page) {
      return;
    }

    const index = schema.findIndex((item) => item.id === leavingId);
    if (index < 0) {
      setLeavingId(null);
      return;
    }

    const timer = window.setTimeout(() => {
      const sections = page.content.sections.filter((item) => item.id !== leavingId);
      const nextSchema = schema.filter((item) => item.id !== leavingId);
      patchPage({ ...page, content: { sections } }, nextSchema);
      setSection((current) => {
        if (sections.length === 0) {
          return 0;
        }
        if (current > index) {
          return current - 1;
        }
        return Math.min(current, sections.length - 1);
      });
      setLeavingId(null);
      setCanvasFlash(Date.now());
    }, 1200);

    return () => window.clearTimeout(timer);
  }, [leavingId]);

  function adoptPage(next: PageDetail, nextSchema?: OutlineSection[]) {
    setPage(next);
    setBaseline(JSON.stringify(next));
    if (nextSchema) {
      setSchema(nextSchema);
    }
  }

  function patchPage(next: PageDetail, nextSchema?: OutlineSection[]) {
    setPage(next);
    if (nextSchema) {
      setSchema(nextSchema);
    }
    setSaved(false);
  }

  async function reload() {
    const payload = await api<{ data: PageDetail; schema: OutlineSection[]; library: LibraryGroup[]; seo: SeoDefaults }>(`/pages/${slug}`);
    adoptPage(payload.data, payload.schema);
    setLibrary(payload.library);
    setDefaults(payload.seo);
    setSaved(false);
    setSection(0);
  }

  useEffect(() => {
    void reload();
  }, [slug]);

  function openLocale(locale: string) {
    if (!page) {
      return;
    }
    if (locale === translationConfig?.default) {
      navigate(`/paginas/${page.source_slug}`);
      return;
    }
    const target = page.translations[locale];
    if (target?.id) {
      navigate(`/paginas/${target.id}`);
    }
  }

  useEffect(() => {
    if (!versionsOpen) {
      return;
    }

    function onKey(event: KeyboardEvent) {
      if (event.key === 'Escape') {
        setVersionsOpen(false);
      }
    }

    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, [versionsOpen]);

  function toggleSidebar() {
    const next = !sidebarOpen;
    setSidebarOpen(next);
    localStorage.setItem('cms-page-sidebar', next ? '1' : '0');
  }

  async function loadRevisions() {
    if (!slug) {
      return;
    }
    setRevisionsLoading(true);
    try {
      const payload = await api<{ data: PageRevision[] }>(`/pages/${slug}/revisions`);
      setRevisions(payload.data);
    } catch (err) {
      setError(firstError(err));
    } finally {
      setRevisionsLoading(false);
    }
  }

  useEffect(() => registerActions({
    'page-editor.openVersions': () => {
      setVersionsOpen(true);
      void loadRevisions();
    },
    'page-editor.closeVersions': () => setVersionsOpen(false),
  }), [registerActions, slug]);

  async function openVersions() {
    setVersionsOpen(true);
    await loadRevisions();
  }

  function reorderSections(from: number, to: number) {
    if (!page || from === to) {
      return;
    }
    const sections = [...page.content.sections];
    const [moved] = sections.splice(from, 1);
    sections.splice(to, 0, moved);
    const nextSchema = [...schema];
    const [movedSchema] = nextSchema.splice(from, 1);
    nextSchema.splice(to, 0, movedSchema);
    patchPage({ ...page, content: { sections } }, nextSchema);
    setSection(to);
  }

  function confirmRemoveSection() {
    if (!page || removeIndex === null) {
      return;
    }
    const target = schema[removeIndex];
    if (!target) {
      setRemoveIndex(null);
      return;
    }
    setLeavingId(target.id);
    setRemoveIndex(null);
  }

  function addSection(item: LibraryItem) {
    if (!page) {
      return;
    }
    const id = newId();
    const sections = [...page.content.sections, { id, type: item.type, data: {} }];
    const nextSchema: OutlineSection[] = [
      ...schema,
      { id, type: item.type, label: item.label, preview: item.preview, fields: item.fields },
    ];
    patchPage({ ...page, content: { sections } }, nextSchema);
    setSection(sections.length - 1);
    setCanvasFlash(Date.now());
  }

  async function save() {
    if (!page) {
      return;
    }
    setError('');
    setSaved(false);
    try {
      const payload = await api<{ data: PageDetail; schema?: OutlineSection[] }>(`/pages/${slug}`, {
        method: 'PUT',
        body: JSON.stringify({ ...page, status: isTranslation ? page.status : 'published' }),
      });
      const nextSchema = payload.schema ?? schema;
      adoptPage(payload.data, nextSchema);
      setSaved(true);
      if (!isTranslation && payload.data.slug !== slug) {
        navigate(`/paginas/${payload.data.slug}`, { replace: true });
      }
    } catch (err) {
      setError(firstError(err));
    }
  }

  async function restoreRevision() {
    if (!slug || restoreId === null) {
      return;
    }
    setRestoring(true);
    setError('');
    try {
      const payload = await api<{ data: PageDetail; schema?: OutlineSection[] }>(`/pages/${slug}/revisions/${restoreId}/restore`, {
        method: 'POST',
      });
      const refreshed = await api<{ data: PageDetail; schema: OutlineSection[]; library: LibraryGroup[]; seo: SeoDefaults }>(`/pages/${payload.data.slug}`);
      adoptPage(refreshed.data, refreshed.schema);
      setLibrary(refreshed.library);
      setSaved(true);
      setRestoreId(null);
      setVersionsOpen(false);
      if (!isTranslation && payload.data.slug !== slug) {
        navigate(`/paginas/${payload.data.slug}`, { replace: true });
      }
    } catch (err) {
      setError(firstError(err));
    } finally {
      setRestoring(false);
    }
  }

  if (!page || !slug) {
    return (
      <AppShell title="Páginas" full>
        <div className="auth-loading"><div className="spinner" /></div>
      </AppShell>
    );
  }

  const current = schema[section];
  const currentData = page.content.sections[section]?.data ?? {};
  const isHome = page.template === 'home';
  const dirty = baseline !== null && JSON.stringify(page) !== baseline;
  const isTranslation = page.translation_of !== null;
  const pathPrefix = page.path.replace(/\/[^/]*$/, '/').replace(/^\/$/, '/');
  const localeName = translationConfig?.locales.find((item) => item.code === page.locale)?.name ?? page.locale;

  return (
    <AppShell title={page.seo_title} subtitle="Editor de página" full>
      {error ? <div className="editor-banner form-error">{error}</div> : null}
      {translationJob.error ? <div className="editor-banner form-error">{translationJob.error}</div> : null}
      {saved && !dirty ? <div className="editor-banner form-success">Página atualizada.</div> : null}
      {isTranslation && page.stale ? <div className="editor-banner form-warning">O original em português mudou depois desta tradução. Revise ou retraduza.</div> : null}
      <div className="editor-langbar">
        <TranslationTabs
          current={page.locale}
          translations={page.translations}
          config={translationConfig}
          running={translationJob.running}
          onOpen={openLocale}
          onTranslate={(locale) => void translationJob.start(locale)}
        />
      </div>
      <div className={`editor-frame editor-split${sidebarOpen ? '' : ' is-collapsed'}`}>
        <aside className={`editor-nav${sidebarOpen ? '' : ' is-collapsed'}`} data-tour="editor-outline">
          <div className="editor-nav-head">
            {sidebarOpen ? <span className="eyebrow">Seções</span> : null}
            <button
              className="icon-btn"
              type="button"
              onClick={toggleSidebar}
              aria-label={sidebarOpen ? 'Recolher painel' : 'Expandir painel'}
            >
              {sidebarOpen ? <PanelLeftClose size={16} /> : <PanelLeft size={16} />}
            </button>
          </div>
          {sidebarOpen ? (
            <PageOutline
              sections={schema}
              active={section}
              leavingId={leavingId}
              onSelect={setSection}
              onReorder={reorderSections}
              onRemove={setRemoveIndex}
              library={libraryItems}
              onAdd={addSection}
            />
          ) : (
            <button className="icon-btn" type="button" disabled={!dirty} onClick={() => void save()} aria-label="Salvar">
              <Save size={16} />
            </button>
          )}
        </aside>
        <div className="editor-canvas" data-tour="editor-canvas">
          <div className={`panel${canvasFlash ? ' is-entering' : ''}`}>
            <div className="panel-head">
              <h3>{current?.label ?? 'Sem seções'}</h3>
            </div>
            <div className="panel-body">
              {current ? (
                <SchemaForm
                  fields={current.fields}
                  content={currentData}
                  context={{ pageSlug: page.slug }}
                  onChange={(data) => {
                    const sections = page.content.sections.map((item, index) => (
                      index === section ? { ...item, data } : item
                    ));
                    patchPage({ ...page, content: { sections } });
                  }}
                />
              ) : (
                <p className="desc">Adicione uma seção pelo painel lateral.</p>
              )}
            </div>
          </div>
        </div>
        <aside className="editor-inspect">
          <div className="inspect-scroll">
            <EditorBox title="Página" open={boxes.page} onToggle={() => setBoxes({ ...boxes, page: !boxes.page })}>
              {isTranslation ? (
                <>
                  <div className="field">
                    <label>Status em {localeName}</label>
                    <div className="status-pills">
                      <button type="button" className={page.status === 'draft' ? 'is-on' : undefined} onClick={() => patchPage({ ...page, status: 'draft' })}>Rascunho</button>
                      <button type="button" className={page.status === 'published' ? 'is-on' : undefined} onClick={() => patchPage({ ...page, status: 'published' })}>Publicada</button>
                    </div>
                    <p className="hint">{page.translated_at ? `Traduzida com IA em ${formatDate(page.translated_at)}.` : 'Tradução manual.'}</p>
                  </div>
                  <button
                    className="btn btn-secondary lang-retranslate"
                    type="button"
                    disabled={!translationConfig?.configured || translationJob.running[page.locale]}
                    onClick={() => setRetranslateOpen(true)}
                  >
                    {translationJob.running[page.locale] ? 'Traduzindo' : 'Retraduzir a partir do PT'}
                  </button>
                </>
              ) : null}
              <div className="form-actions" data-tour="editor-save">
                <button className="btn btn-primary" type="button" disabled={!dirty} onClick={() => void save()}>
                  Atualizar
                </button>
              </div>
            </EditorBox>

            <div data-tour="editor-seo">
            <EditorBox title="SEO" open={boxes.seo} onToggle={() => setBoxes({ ...boxes, seo: !boxes.seo })}>
              <GooglePreview
                title={composeSeoTitle(page.seo_title, defaults)}
                description={resolveSeoDescription(page.seo_description, defaults)}
                canonical={page.canonical}
                slug={page.path.replace(/^\//, '') || page.slug || slug}
              />
              <div className="field">
                <label>Slug</label>
                <div className="slug-field">
                  <span>{pathPrefix}</span>
                  <input
                    value={isHome ? '' : page.slug}
                    placeholder={isHome ? '' : 'pagina'}
                    disabled={isHome}
                    onChange={(event) => patchPage({
                      ...page,
                      slug: event.target.value.trim().toLowerCase().replace(/[^a-z0-9-]/g, ''),
                    })}
                  />
                </div>
                <p className="hint">{isHome ? `A home publica em ${page.path}.` : 'URL pública da página.'}</p>
              </div>
              <div className="field">
                <label>Título SEO</label>
                <input value={page.seo_title} onChange={(event) => patchPage({ ...page, seo_title: event.target.value })} />
                <p className="hint">Sem o nome do site, o template padrão entra no título público.</p>
              </div>
              <div className="field">
                <label>Description</label>
                <textarea
                  value={page.seo_description ?? ''}
                  placeholder={defaults?.seo_description ?? ''}
                  onChange={(event) => patchPage({ ...page, seo_description: event.target.value })}
                />
                <p className="hint">Vazio usa a description padrão do SEO do site.</p>
              </div>
              <div className="field">
                <label>Canonical</label>
                <input value={page.canonical ?? ''} onChange={(event) => patchPage({ ...page, canonical: event.target.value })} />
              </div>
              <MediaField
                label="OG image"
                value={page.og_image}
                onChange={(value) => patchPage({ ...page, og_image: value })}
              />
              <p className="hint">Vazio usa a OG image padrão do site.</p>
              <div className="field">
                <label>Alt da OG image</label>
                <input
                  value={page.og_image_alt ?? ''}
                  placeholder={defaults?.seo_og_image_alt ?? ''}
                  onChange={(event) => patchPage({ ...page, og_image_alt: event.target.value })}
                />
              </div>
              <div className="field">
                <label>Robots</label>
                <input
                  value={page.robots ?? ''}
                  placeholder={defaults?.seo_robots ?? ''}
                  onChange={(event) => patchPage({ ...page, robots: event.target.value })}
                />
              </div>
              <div className="field">
                <label>Twitter card</label>
                <input
                  value={page.twitter_card ?? ''}
                  placeholder={defaults?.twitter_card ?? ''}
                  onChange={(event) => patchPage({ ...page, twitter_card: event.target.value })}
                />
              </div>
            </EditorBox>
            </div>

            <button className="versions-trigger" type="button" data-tour="editor-versions" onClick={() => void openVersions()}>
              <History size={13} />
              Versões
            </button>
          </div>
        </aside>
      </div>

      {versionsOpen ? (
        <div className="versions-backdrop" onClick={() => setVersionsOpen(false)}>
          <aside className="versions-drawer" onClick={(event) => event.stopPropagation()}>
            <div className="versions-head">
              <div>
                <span className="eyebrow">Controle de versão</span>
                <h3>Versões anteriores</h3>
              </div>
              <button className="icon-btn" type="button" onClick={() => setVersionsOpen(false)} aria-label="Fechar">
                <X size={14} />
              </button>
            </div>
            <div className="versions-body">
              {revisionsLoading ? (
                <div className="auth-loading"><div className="spinner" /></div>
              ) : revisions.length === 0 ? (
                <p className="desc">Nenhuma versão salva ainda.</p>
              ) : (
                <ul className="versions-list">
                  {revisions.map((revision) => (
                    <li key={revision.id}>
                      <div>
                        <strong>{formatDate(revision.created_at)}</strong>
                        <span>{revision.user_name ?? 'Sistema'}</span>
                      </div>
                      <button
                        className="btn btn-secondary"
                        type="button"
                        onClick={() => setRestoreId(revision.id)}
                      >
                        Restaurar
                      </button>
                    </li>
                  ))}
                </ul>
              )}
            </div>
          </aside>
        </div>
      ) : null}

      {restoreId !== null ? (
        <Modal
          title="Restaurar versão"
          eyebrow="Versões"
          onClose={() => setRestoreId(null)}
          footer={(
            <>
              <button className="btn btn-secondary" type="button" onClick={() => setRestoreId(null)}>Cancelar</button>
              <button className="btn btn-primary" type="button" disabled={restoring} onClick={() => void restoreRevision()}>
                Restaurar
              </button>
            </>
          )}
        >
          <p className="desc">A versão atual será salva antes da restauração, para você poder voltar se precisar.</p>
        </Modal>
      ) : null}

      {retranslateOpen ? (
        <Modal
          title="Retraduzir página"
          eyebrow={localeName}
          onClose={() => setRetranslateOpen(false)}
          footer={(
            <>
              <button className="btn btn-secondary" type="button" onClick={() => setRetranslateOpen(false)}>Cancelar</button>
              <button
                className="btn btn-primary"
                type="button"
                onClick={() => {
                  setRetranslateOpen(false);
                  void translationJob.start(page.locale);
                }}
              >
                Retraduzir
              </button>
            </>
          )}
        >
          <p className="desc">O conteúdo desta tradução será substituído por uma nova tradução do original em português. As edições manuais ficam salvas em Versões.</p>
        </Modal>
      ) : null}

      {removeIndex !== null ? (
        <Modal
          title="Remover seção"
          eyebrow="Seções"
          onClose={() => setRemoveIndex(null)}
          footer={(
            <>
              <button className="btn btn-secondary" type="button" onClick={() => setRemoveIndex(null)}>Cancelar</button>
              <button className="btn btn-primary" type="button" onClick={confirmRemoveSection}>Remover</button>
            </>
          )}
        >
          <p className="desc">A seção será removida desta página. O conteúdo só deixa de existir depois de atualizar.</p>
        </Modal>
      ) : null}
    </AppShell>
  );
}
