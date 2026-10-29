import { ArrowRight } from 'lucide-react';
import { type FormEvent, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { api, firstError } from '../api';
import { useAuth } from '../auth';
import type { CmsUser } from '../lib';
import { DevCredit } from '../components/DevCredit';
import { BrandMark } from '../components/BrandMark';

type LoginResponse = {
  status: 'authenticated';
  user?: CmsUser;
};

export function LoginScreen() {
  const { setUser } = useAuth();
  const navigate = useNavigate();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  async function submitLogin(event: FormEvent) {
    event.preventDefault();
    setError('');
    try {
      const payload = await api<LoginResponse>('/login', {
        method: 'POST',
        body: JSON.stringify({ email, password }),
      });
      if (payload.user) {
        setUser(payload.user);
      }
      navigate('/');
    } catch (err) {
      setError(firstError(err));
    }
  }

  return (
    <div className="login-screen">
      <div className="login-card">
        <BrandMark variant="login" />
        <form onSubmit={(event) => void submitLogin(event)}>
          <h2>Entrar</h2>
          <p className="desc">Acesso ao painel do escritório.</p>
          {error ? <div className="form-error">{error}</div> : null}
          <div className="field">
            <label>E-mail</label>
            <input type="email" value={email} onChange={(event) => setEmail(event.target.value)} required />
          </div>
          <div className="field">
            <label>Senha</label>
            <input type="password" value={password} onChange={(event) => setPassword(event.target.value)} required />
          </div>
          <button className="btn btn-primary" type="submit">
            Entrar <ArrowRight size={16} />
          </button>
        </form>
      </div>
      <DevCredit className="dev-credit--login" />
    </div>
  );
}
