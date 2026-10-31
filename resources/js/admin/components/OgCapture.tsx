import { useState } from 'react';
import { Camera } from 'lucide-react';
import { api, firstError } from '../api';

/**
 * Gera a OG image (1200x630) a partir do topo da página com o conteúdo atual do editor, sem salvar.
 */
export function OgCaptureButton({
  endpoint,
  body,
  onCaptured,
}: {
  endpoint: string;
  body: unknown;
  onCaptured: (path: string) => void;
}) {
  const [running, setRunning] = useState(false);
  const [error, setError] = useState<string | null>(null);

  async function capture() {
    setRunning(true);
    setError(null);
    try {
      const result = await api<{ path: string }>(endpoint, { method: 'POST', body: JSON.stringify(body) });
      onCaptured(result.path);
    } catch (err) {
      setError(firstError(err));
    } finally {
      setRunning(false);
    }
  }

  return (
    <div className="og-capture">
      <button className="btn btn-secondary" type="button" disabled={running} onClick={() => void capture()}>
        <Camera size={14} /> {running ? 'Gerando print...' : 'Gerar print do topo da página'}
      </button>
      <p className="hint">{running ? 'Abrindo a página e capturando header e abertura em 1200×630.' : 'Captura header e abertura com o conteúdo atual, em 1200×630.'}</p>
      {error ? <p className="field-ai-error">{error}</p> : null}
    </div>
  );
}
