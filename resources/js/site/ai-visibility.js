const form = document.getElementById('ai-vis-form');
const status = document.getElementById('ai-vis-status');

function setStatus(text, isError = false) {
  if (!status) {
    return;
  }
  status.hidden = !text;
  status.textContent = text;
  status.classList.toggle('is-error', isError);
}

form?.addEventListener('submit', async (event) => {
  event.preventDefault();
  const button = form.querySelector('button[type="submit"]');
  const url = new FormData(form).get('url');
  const token = form.querySelector('input[name="_token"]')?.value;
  button.disabled = true;
  setStatus('Analisando a home…');

  try {
    const response = await fetch(form.action, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token || '',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({
        url,
        empresa_site: form.querySelector('input[name="empresa_site"]')?.value || '',
      }),
    });
    const data = await response.json().catch(() => ({}));
    if (!response.ok) {
      throw new Error(data.message || 'Não foi possível analisar agora.');
    }
    window.location.assign(data.path);
  } catch (error) {
    setStatus(error instanceof Error ? error.message : 'Falha na análise.', true);
    button.disabled = false;
  }
});

document.querySelectorAll('[data-copy]').forEach((button) => {
  button.addEventListener('click', async () => {
    const value = button.getAttribute('data-copy') || '';
    try {
      await navigator.clipboard.writeText(value);
      button.textContent = 'Link copiado';
      setTimeout(() => {
        button.textContent = 'Copiar link do relatório';
      }, 1800);
    } catch {
      window.prompt('Copie o link:', value);
    }
  });
});
