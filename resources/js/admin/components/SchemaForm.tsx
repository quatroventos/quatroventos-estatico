import { useEffect, useState } from 'react';
import { Plus, Trash2 } from 'lucide-react';
import { api } from '../api';
import { getPath, setPath } from '../lib';
import { MediaField } from './MediaField';
import { RichText } from './RichText';

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

  const linked = (cases ?? []).filter((item) => !context?.pageSlug || item.services.includes(context.pageSlug));

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
      <p className="hint">Sem seleção, entram automaticamente os cases publicados vinculados a esta página.</p>
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

function FieldControl({
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
      </div>
    );
  }

  if (field.type === 'textarea') {
    return (
      <div className="field">
        <label>{field.label}</label>
        <textarea value={(value as string) ?? ''} onChange={(event) => onChange(event.target.value)} />
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
      </div>
    );
  }

  if (field.type === 'rich') {
    return (
      <div className="field">
        <label>{field.label}</label>
        <RichText value={(value as string) ?? ''} onChange={onChange} />
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
              field.itemType === 'rich' ? (
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
              )
            ) : (
              (field.fields ?? []).map((child) => (
                <FieldControl
                  key={child.name}
                  field={child}
                  context={context}
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
      <input value={(value as string) ?? ''} onChange={(event) => onChange(event.target.value)} />
    </div>
  );
}

export function SchemaForm({
  fields,
  content,
  context,
  onChange,
}: {
  fields: Field[];
  content: Record<string, unknown>;
  context?: SchemaContext;
  onChange: (content: Record<string, unknown>) => void;
}) {
  return (
    <>
      {fields.map((field) => (
        <FieldControl
          key={field.name}
          field={field}
          context={context}
          value={getPath(content, field.name)}
          onChange={(next) => onChange(setPath(content, field.name, next))}
        />
      ))}
    </>
  );
}
