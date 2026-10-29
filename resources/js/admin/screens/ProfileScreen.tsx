import { type FormEvent, useState } from 'react';
import { AppShell } from '../components/AppShell';
import { api, firstError } from '../api';
import { useAuth } from '../auth';

export function ProfileScreen() {
  const { user, refresh } = useAuth();
  const [name, setName] = useState(user?.name ?? '');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [ok, setOk] = useState(false);

  async function save(event: FormEvent) {
    event.preventDefault();
    setError('');
    setOk(false);
    try {
      await api('/profile', {
        method: 'PUT',
        body: JSON.stringify({ name, password: password || undefined }),
      });
      setPassword('');
      setOk(true);
      await refresh();
    } catch (err) {
      setError(firstError(err));
    }
  }

  return (
    <AppShell eyebrow="Sistema" title="Perfil">
      <form id="profile-form" className="panel" onSubmit={(event) => void save(event)}>
        <div className="panel-body">
          {error ? <div className="form-error">{error}</div> : null}
          {ok ? <div className="form-success">Perfil atualizado.</div> : null}
          <div className="field" data-tour="profile-name">
            <label>Nome</label>
            <input value={name} onChange={(event) => setName(event.target.value)} />
          </div>
          <div className="field" data-tour="profile-password">
            <label>Nova senha</label>
            <input type="password" value={password} onChange={(event) => setPassword(event.target.value)} />
          </div>
          <div className="form-actions" data-tour="profile-save">
            <button className="btn btn-primary" type="submit">Salvar</button>
          </div>
        </div>
      </form>
    </AppShell>
  );
}
