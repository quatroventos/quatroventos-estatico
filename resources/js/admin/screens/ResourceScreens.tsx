import { useNavigate } from 'react-router-dom';
import { UserRoundSearch } from 'lucide-react';
import { useAuth } from '../auth';
import { api, firstError } from '../api';
import { CrudPage } from './CrudPage';

export { MenuScreen } from './MenuScreen';

export function TeamScreen() {
  return (
    <CrudPage
      eyebrow="Conteúdo"
      title="Equipe"
      description="Liderança e profissionais exibidos no site."
      endpoint="/team-members"
      createLabel="Adicionar membro"
      columns={[
        { key: 'name', label: 'Nome' },
        { key: 'role', label: 'Cargo' },
        { key: 'sort', label: 'Ordem' },
      ]}
      fields={[
        { name: 'name', label: 'Nome' },
        { name: 'role', label: 'Cargo' },
        { name: 'bio', label: 'Bio', type: 'rich' },
        { name: 'photo_path', label: 'Foto', type: 'media' },
        { name: 'sort', label: 'Ordem', type: 'number' },
      ]}
    />
  );
}

export function BranchesScreen() {
  return (
    <CrudPage
      eyebrow="Conteúdo"
      title="Filiais"
      description="Unidades e seletor de cidade."
      endpoint="/branches"
      createLabel="Adicionar filial"
      columns={[
        { key: 'city', label: 'Cidade' },
        { key: 'state', label: 'UF' },
        { key: 'select_label', label: 'Label' },
      ]}
      fields={[
        { name: 'slug', label: 'Slug' },
        { name: 'city', label: 'Cidade' },
        { name: 'state', label: 'UF' },
        { name: 'select_label', label: 'Label do seletor' },
        { name: 'eyebrow', label: 'Eyebrow' },
        { name: 'address', label: 'Endereço', type: 'textarea' },
        { name: 'is_headquarters', label: 'Sede', type: 'toggle' },
        { name: 'sort', label: 'Ordem', type: 'number' },
      ]}
    />
  );
}

export function CertificationsScreen() {
  const typeLabels: Record<string, string> = {
    badge: 'Selo',
    placeholder: 'Espaço reservado',
  };

  return (
    <CrudPage<{ id: number; label: string; type: string }>
      eyebrow="Configuração"
      title="Certificações"
      description="Selos do rodapé."
      endpoint="/certifications"
      createLabel="Adicionar selo"
      columns={[
        { key: 'label', label: 'Label' },
        {
          key: 'type',
          label: 'Tipo',
          render: (row) => typeLabels[row.type] ?? row.type,
        },
      ]}
      fields={[
        { name: 'label', label: 'Label' },
        { name: 'type', label: 'Tipo', type: 'select', options: [
          { value: 'badge', label: 'Selo' },
          { value: 'placeholder', label: 'Espaço reservado' },
        ] },
        { name: 'image_path', label: 'Imagem', type: 'media' },
        { name: 'url', label: 'URL' },
        { name: 'alt', label: 'Alt' },
        { name: 'sort', label: 'Ordem', type: 'number' },
      ]}
    />
  );
}

export function UsersScreen() {
  const { user, setUser } = useAuth();
  const navigate = useNavigate();

  async function impersonate(row: { id: number; name: string }) {
    if (!confirm(`Acessar o painel como ${row.name}?`)) {
      return;
    }
    try {
      const payload = await api<{ user: NonNullable<typeof user> }>(`/users/${row.id}/impersonate`, { method: 'POST' });
      setUser(payload.user);
      navigate('/');
    } catch (err) {
      alert(firstError(err));
    }
  }

  return (
    <CrudPage<{ id: number; name: string; email: string; role: string }>
      eyebrow="Sistema"
      title="Usuários"
      description="Acesso ao CMS."
      endpoint="/users"
      createLabel="Adicionar usuário"
      canDeleteRow={(row) => row.id !== user?.id}
      rowActions={(row) => (
        user?.abilities.impersonate && row.id !== user?.id ? (
          <button
            className="icon-btn"
            type="button"
            title="Acessar como"
            aria-label={`Acessar como ${row.name}`}
            data-tour="users-impersonate"
            onClick={() => void impersonate(row)}
          >
            <UserRoundSearch size={14} />
          </button>
        ) : null
      )}
      columns={[
        { key: 'name', label: 'Nome' },
        { key: 'email', label: 'E-mail' },
        { key: 'role', label: 'Papel', render: (row) => (row.role === 'root' ? 'Root' : 'Usuário') },
      ]}
      fields={[
        { name: 'name', label: 'Nome' },
        { name: 'email', label: 'E-mail' },
        { name: 'password', label: 'Senha', type: 'password' },
        { name: 'role', label: 'Papel', type: 'select', options: [
          { value: 'root', label: 'Root' },
          { value: 'user', label: 'Usuário' },
        ] },
      ]}
    />
  );
}
