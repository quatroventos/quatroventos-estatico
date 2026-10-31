import { type ReactNode, useEffect, useState } from 'react';
import { Plus, Trash2 } from 'lucide-react';
import { api } from '../api';
import { getPath, setPath } from '../lib';
import { MediaField } from './MediaField';
import { RichText } from './RichText';
import { acceptsAi, FieldAi, type FieldAiContext } from './SectionCopy';

export type Field = {
  type: string;
  name: string;
  label: string;
  simple?: boolean;
  itemType?: string;
  kind?: 'image' | 'video' | 'pdf';
  fields?: Field[];
  options?: { value: string; label: string }[];
  max?: number;
  all?: boolean;
  hint?: string;
};

export type SchemaContext = {
  pageSlug?: string;
};

type CaseOption = {
  id: number;
  slug: string;
  client: string;
  title: string;
  status: string;
  services: string[];
};

let caseOptions: Promise<CaseOption[]> | null = null;

function loadCaseOptions(): Promise<CaseOption[]> {
  caseOptions ??= api<{ data: CaseOption[] }>('/cases')
    .then((payload) => payload.data)
    .catch(() => {
      caseOptions = null;
      return [];
    });
  return caseOptions;
}

function CasesPicker({
  field,
  value,
  context,
  onChange,
}: {
  field: Field;
  value: unknown;
  context?: SchemaContext;
  onChange: (value: unknown) => void;
}) {
  const [cases, setCases] = useState<CaseOption[] | null>(null);
  const selected = Array.isArray(value) ? value.filter((item): item is string => typeof item === 'string') : [];
  const max = field.max ?? 2;

  useEffect(() => {
    void loadCaseOptions().then(setCases);
  }, []);

  const linked = (cases ?? []).filter((item) => field.all || !context?.pageSlug || item.services.includes(context.pageSlug));

  function toggle(slug: string) {
    if (selected.includes(slug)) {
      onChange(selected.filter((item) => item !== slug));
      return;
    }
    if (selected.length >= max) {
      return;
    }
    onChange([...selected, slug]);
  }

  return (
    <div className="field">
      <label>{field.label}</label>
      {cases === null ? <p className="hint">Carregando cases...</p> : null}
      {cases !== null && linked.length === 0 ? (
        <p className="hint">Nenhum case vinculado a esta página. Vincule em Cases, no quadro Serviços.</p>
      ) : null}
      <div className="case-checks">
        {linked.map((item) => (
          <label key={item.slug}>
            <input
              type="checkbox"
              checked={selected.includes(item.slug)}
              disabled={!selected.includes(item.slug) && selected.length >= max}
              onChange={() => toggle(item.slug)}
            />
            <span>
              <strong>{item.client}</strong> {item.title}
              {item.status !== 'published' ? ' (rascunho)' : ''}
            </span>
          </label>
        ))}
      </div>
      <p className="hint">{field.hint ?? 'Sem seleção, entram automaticamente os cases publicados vinculados a esta página.'}</p>
    </div>
  );
}

export type Section = {
  id?: string;
  type?: string;
  label: string;
  preview?: string;
  fields: Field[];
};

function AiSlot({
  type,
  name,
  path,
  value,
  ai,
  onChange,
  children,
}: {
  type: string;
  name: string;
  path: string;
  value: unknown;
  ai?: FieldAiContext;
  onChange: (value: string) => void;
  children: ReactNode;
}) {
  if (!ai || !acceptsAi(type, name)) {
    return <>{children}</>;
  }

  return (
    <div className={`field-ai-wrap is-${type}`}>
      {children}
      <FieldAi ai={ai} path={path} value={value} onApply={onChange} />
    </div>
  );
}

function FieldControl({
  field,
  value,
  context,
  path,
  ai,
  onChange,
}: {
  field: Field;
  value: unknown;
  context?: SchemaContext;
  path: string;
  ai?: FieldAiContext;
  onChange: (value: unknown) => void;
}) {
  if (field.type === 'cases') {
    return <CasesPicker field={field} value={value} context={context} onChange={onChange} />;
  }

  if (field.type === 'select') {
    return (
      <div className="field">
        <label>{field.label}</label>
        <select value={(value as string) ?? ''} onChange={(event) => onChange(event.target.value)}>
          <option value="">Padrão</option>
          {(field.options ?? []).map((option) => (
            <option key={option.value} value={option.value}>{option.label}</option>
          ))}
        </select>
        {field.hint ? <p className="hint">{field.hint}</p> : null}
      </div>
    );
  }

  if (field.type === 'textarea') {
    return (
      <div className="field">
        <label>{field.label}</label>
        <AiSlot type={field.type} name={field.name} path={path} value={value} ai={ai} onChange={onChange}>
          <textarea value={(value as string) ?? ''} onChange={(event) => onChange(event.target.value)} />
        </AiSlot>
      </div>
    );
  }

  if (field.type === 'toggle') {
    return (
      <div className="field">
        <label>
          <input
            type="checkbox"
            checked={Boolean(value)}
            onChange={(event) => onChange(event.target.checked)}
          />{' '}
          {field.label}
        </label>
        {field.hint ? <p className="hint">{field.hint}</p> : null}
      </div>
    );
  }

  if (field.type === 'rich') {
    return (
      <div className="field">
        <label>{field.label}</label>
        <AiSlot type={field.type} name={field.name} path={path} value={value} ai={ai} onChange={onChange}>
          <RichText value={(value as string) ?? ''} onChange={onChange} />
        </AiSlot>
      </div>
    );
  }

  if (field.type === 'media') {
    return (
      <MediaField
        label={field.label}
        kind={field.kind ?? 'image'}
        value={(value as string) ?? ''}
        onChange={onChange}
      />
    );
  }

  if (field.type === 'repeater') {
    const items = Array.isArray(value) ? value : [];
    const simple = Boolean(field.simple);

    return (
      <div className="field">
        <label>{field.label}</label>
        {items.map((item, index) => (
          <div className="repeater-item" key={index}>
            {simple ? (
              <AiSlot
                type={field.itemType ?? 'text'}
                name={field.name}
                path={`${path}.${index}`}
                value={item}
                ai={ai}
                onChange={(next) => {
                  const copy = [...items];
                  copy[index] = next;
                  onChange(copy);
                }}
              >
                {field.itemType === 'rich' ? (
                  <RichText
                    value={typeof item === 'string' ? item : ''}
                    onChange={(next) => {
                      const copy = [...items];
                      copy[index] = next;
                      onChange(copy);
                    }}
                  />
                ) : (
                  <input
                    value={typeof item === 'string' ? item : ''}
                    onChange={(event) => {
                      const copy = [...items];
                      copy[index] = event.target.value;
                      onChange(copy);
                    }}
                  />
                )}
              </AiSlot>
            ) : (
              (field.fields ?? []).map((child) => (
                <FieldControl
                  key={child.name}
                  field={child}
                  context={context}
                  path={`${path}.${index}.${child.name}`}
                  ai={ai}
                  value={(item as Record<string, unknown>)?.[child.name]}
                  onChange={(next) => {
                    const copy = [...items] as Record<string, unknown>[];
                    copy[index] = { ...(copy[index] ?? {}), [child.name]: next };
                    onChange(copy);
                  }}
                />
              ))
            )}
            <button
              className="icon-btn danger"
              type="button"
              onClick={() => onChange(items.filter((_, i) => i !== index))}
            >
              <Trash2 size={14} />
            </button>
          </div>
        ))}
        <button
          className="btn btn-secondary"
          type="button"
          onClick={() => onChange([...items, simple ? '' : {}])}
        >
          <Plus size={14} /> Adicionar
        </button>
      </div>
    );
  }

  return (
    <div className="field">
      <label>{field.label}</label>
      <AiSlot type={field.type} name={field.name} path={path} value={value} ai={ai} onChange={onChange}>
        <input value={(value as string) ?? ''} onChange={(event) => onChange(event.target.value)} />
      </AiSlot>
      {field.hint ? <p className="hint">{field.hint}</p> : null}
    </div>
  );
}

export function SchemaForm({
  fields,
  content,
  context,
  ai,
  onChange,
}: {
  fields: Field[];
  content: Record<string, unknown>;
  context?: SchemaContext;
  ai?: FieldAiContext;
  onChange: (content: Record<string, unknown>) => void;
}) {
  return (
    <>
      {fields.map((field) => (
        <FieldControl
          key={field.name}
          field={field}
          context={context}
          path={field.name}
          ai={ai}
          value={getPath(content, field.name)}
          onChange={(next) => onChange(setPath(content, field.name, next))}
        />
      ))}
    </>
  );
}
