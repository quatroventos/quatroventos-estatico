import { useRef, useState } from 'react';
import { Upload } from 'lucide-react';
import { api, firstError } from '../api';

export function BulkImageUpload({ onUploaded }: { onUploaded: (path: string) => void }) {
  const input = useRef<HTMLInputElement>(null);
  const [progress, setProgress] = useState<{ done: number; total: number } | null>(null);
  const [errors, setErrors] = useState<string[]>([]);

  async function upload(files: File[]) {
    setErrors([]);
    setProgress({ done: 0, total: files.length });
    const failed: string[] = [];

    for (const [index, file] of files.entries()) {
      try {
        const body = new FormData();
        body.append('file', file);
        body.append('kind', 'image');
        const payload = await api<{ path: string }>('/media', { method: 'POST', body });
        onUploaded(payload.path);
      } catch (err) {
        failed.push(`${file.name}: ${firstError(err)}`);
      }
      setProgress({ done: index + 1, total: files.length });
    }

    setErrors(failed);
    setProgress(null);
    if (input.current) {
      input.current.value = '';
    }
  }

  return (
    <>
      <input
        ref={input}
        className="media-input"
        type="file"
        accept="image/*"
        multiple
        onChange={(event) => {
          const files = [...(event.target.files ?? [])].filter((file) => file.type.startsWith('image/'));
          if (files.length > 0) {
            void upload(files);
          }
        }}
      />
      <button className="btn btn-secondary" type="button" disabled={progress !== null} onClick={() => input.current?.click()}>
        <Upload size={14} /> {progress ? `Enviando ${Math.min(progress.done + 1, progress.total)} de ${progress.total}...` : 'Enviar vários prints'}
      </button>
      {errors.length > 0 ? (
        <div className="repeater-add-errors">
          {errors.map((message) => <p key={message} className="field-ai-error">{message}</p>)}
        </div>
      ) : null}
    </>
  );
}
