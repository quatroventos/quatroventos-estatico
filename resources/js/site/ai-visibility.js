import './behaviors.js';

const form = document.getElementById('ai-vis-form');
const status = document.getElementById('ai-vis-status');
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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
  setStatus('Lendo a página inicial…');

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
    const idle = button.getAttribute('data-copy-idle') || 'Copiar link do resultado';
    try {
      await navigator.clipboard.writeText(value);
      button.textContent = 'Link copiado';
      setTimeout(() => {
        button.textContent = idle;
      }, 1800);
    } catch {
      window.prompt('Copie o link:', value);
    }
  });
});

function padScore(value, places) {
  return String(Math.max(0, Math.min(100, Math.round(value)))).padStart(places, '0');
}

function createFlipDigit(value) {
  const digit = document.createElement('div');
  digit.className = 'ai-vis-flip-digit';
  digit.dataset.value = value;
  digit.innerHTML = `
    <span class="ai-vis-flip-static ai-vis-flip-static-top"><span>${value}</span></span>
    <span class="ai-vis-flip-static ai-vis-flip-static-bottom"><span>${value}</span></span>
    <span class="ai-vis-flip-hinge"></span>
    <span class="ai-vis-flip-card ai-vis-flip-card-top"><span>${value}</span></span>
    <span class="ai-vis-flip-card ai-vis-flip-card-bottom"><span>${value}</span></span>
  `;
  return digit;
}

function setDigitLabels(digit, value) {
  digit.querySelectorAll('.ai-vis-flip-static > span, .ai-vis-flip-card > span').forEach((label) => {
    label.textContent = value;
  });
  digit.dataset.value = value;
}

function setDigitValue(digit, next, animate) {
  const current = digit.dataset.value || '0';
  if (current === next) {
    return Promise.resolve();
  }

  const topCard = digit.querySelector('.ai-vis-flip-card-top');
  const bottomCard = digit.querySelector('.ai-vis-flip-card-bottom');
  const topStatic = digit.querySelector('.ai-vis-flip-static-top > span');
  const bottomStatic = digit.querySelector('.ai-vis-flip-static-bottom > span');
  const topLabel = topCard?.querySelector('span');
  const bottomLabel = bottomCard?.querySelector('span');

  if (!topCard || !bottomCard || !topStatic || !bottomStatic || !topLabel || !bottomLabel) {
    setDigitLabels(digit, next);
    return Promise.resolve();
  }

  if (!animate) {
    setDigitLabels(digit, next);
    digit.classList.remove('is-flipping');
    return Promise.resolve();
  }

  topLabel.textContent = current;
  bottomLabel.textContent = next;
  topStatic.textContent = current;
  bottomStatic.textContent = next;
  digit.classList.remove('is-flipping');
  void digit.offsetWidth;
  digit.classList.add('is-flipping');

  return new Promise((resolve) => {
    let done = false;
    const finish = () => {
      if (done) {
        return;
      }
      done = true;
      setDigitLabels(digit, next);
      digit.classList.remove('is-flipping');
      resolve();
    };
    topCard.addEventListener('animationend', finish, { once: true });
    window.setTimeout(finish, 420);
  });
}

function mountFlipBoard(board, places, value) {
  board.replaceChildren();
  const digits = padScore(value, places).split('').map((char) => createFlipDigit(char));
  digits.forEach((digit) => board.appendChild(digit));
  return digits;
}

function writeFlipValue(digits, value, animate) {
  const places = digits.length;
  const chars = padScore(value, places).split('');
  return Promise.all(digits.map((digit, index) => setDigitValue(digit, chars[index], animate)));
}

function easeOutCubic(t) {
  return 1 - (1 - t) ** 3;
}

function runScoreReveal() {
  const hero = document.querySelector('[data-ai-vis-score-reveal]');
  const flip = hero?.querySelector('[data-ai-vis-flip]');
  const board = flip?.querySelector('.ai-vis-flip-board');
  if (!hero || !flip || !board) {
    return;
  }

  const target = Number(flip.getAttribute('data-score') || '0');
  const places = target >= 100 ? 3 : 2;

  if (reducedMotion) {
    mountFlipBoard(board, places, target);
    hero.dataset.scoreStage = 'settled';
    return;
  }

  hero.dataset.scoreStage = 'counting';
  const digits = mountFlipBoard(board, places, 0);
  const duration = Math.min(1700, 800 + target * 10);
  const started = performance.now();
  let shown = 0;
  let pending = null;
  let running = false;
  let finished = false;

  const flush = async () => {
    if (running) {
      return;
    }
    running = true;
    while (pending !== null && pending !== shown) {
      const next = pending;
      pending = null;
      const animate = Math.abs(next - shown) === 1;
      shown = next;
      await writeFlipValue(digits, next, animate);
    }
    running = false;
    if (pending !== null && pending !== shown) {
      await flush();
      return;
    }
    if (finished && shown === target) {
      window.setTimeout(() => {
        hero.dataset.scoreStage = 'settled';
      }, 380);
    }
  };

  const tick = (now) => {
    const progress = Math.min(1, (now - started) / duration);
    pending = Math.round(easeOutCubic(progress) * target);
    flush();

    if (progress < 1) {
      window.requestAnimationFrame(tick);
      return;
    }

    finished = true;
    pending = target;
    flush();
  };

  window.requestAnimationFrame(tick);
}

runScoreReveal();