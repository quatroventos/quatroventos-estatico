import { api } from '../api';

export function MediaField({
  label,
  value,
  kind = 'image',
  onChange,
}: {
  label: string;
  value?: string | null;
  kind?: 'image' | 'video' | 'pdf';
  onChange: (path: string | null) => void;
}) {
  async function upload(file: File) {
    const body = new FormData();
    body.append('file', file);
    body.append('kind', kind);
    const payload = await api<{ path: string }>('/media', { method: 'POST', body });
    onChange(payload.path);
  }

  return (
    <div className="field">
      <label>{label}</label>
      <input
        type="file"
        accept={kind === 'video' ? 'video/mp4,video/webm' : kind === 'pdf' ? 'application/pdf' : 'image/*'}
        onChange={(event) => {
          const file = event.target.files?.[0];
          if (file) {
            void upload(file);
          }
        }}
      />
      {value ? (
        <div className="media-preview-wrap">
          {kind === 'image' ? (
            <img className="media-preview" src={value} alt="" />
          ) : (
            <p className="hint">{value}</p>
          )}
          <button className="btn btn-text" type="button" onClick={() => onChange(null)}>
            Remover
          </button>
        </div>
      ) : null}
    </div>
  );
}
