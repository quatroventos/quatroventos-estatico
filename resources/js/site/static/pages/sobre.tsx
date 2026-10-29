// Gerado por scripts/export-static-pages.mjs a partir de /sobre (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { useEffect, useRef, type CSSProperties, type ReactNode } from 'react';
import { AgentLog, Rotating, trackLens } from '../../hero';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Gostaria%20de%20conhecer%20melhor%20a%20Quatro%20Ventos.';

const PRINCIPLES = [
  { title: 'Processo antes de ferramenta', text: 'A tecnologia é escolhida depois de entender o problema.' },
  { title: 'Soluções criativas', text: 'Nenhum projeto parte de template.' },
  { title: 'Um único time', text: 'Design, frontend, backend, infraestrutura e IA no mesmo projeto.' },
  { title: 'Homologação antes da publicação', text: 'Tudo validado em ambiente privado.' },
  { title: 'Continuidade', text: 'Suporte, manutenção e evolução após a entrega.' },
  { title: 'Garantia de 30 dias', text: 'Para ajustes e correções em sites.' },
];

const PRINCIPLE_GLYPHS: ReactNode[] = [
  <><circle cx="17" cy="17" r="10" pathLength={1} /><path d="m24.5 24.5 9 9" pathLength={1} style={{ '--j': 1 } as CSSProperties} /></>,
  <><path d="M20 4v8M20 28v8M4 20h8M28 20h8" pathLength={1} /><path d="M20 13a7 7 0 1 1 0 14 7 7 0 0 1 0-14Z" pathLength={1} style={{ '--j': 1 } as CSSProperties} /></>,
  <><circle cx="14" cy="16" r="8" pathLength={1} /><circle cx="26" cy="16" r="8" pathLength={1} style={{ '--j': 1 } as CSSProperties} /><circle cx="20" cy="26" r="8" pathLength={1} style={{ '--j': 2 } as CSSProperties} /></>,
  <><path d="M20 4 33 9v10c0 8-5.5 14-13 17C12.5 33 7 27 7 19V9l13-5Z" pathLength={1} /><path d="m14 20 4.5 4.5L27 16" pathLength={1} style={{ '--j': 1 } as CSSProperties} /></>,
  <><path d="M33 20a13 13 0 1 1-4-9.4" pathLength={1} /><path d="M30 4v7h-7" pathLength={1} style={{ '--j': 1 } as CSSProperties} /></>,
  <><rect x="6" y="8" width="28" height="26" rx="4" pathLength={1} /><path d="M6 16h28M13 4v7M27 4v7" pathLength={1} style={{ '--j': 1 } as CSSProperties} /><path d="m15 25 3.5 3.5L26 21" pathLength={1} style={{ '--j': 2 } as CSSProperties} /></>,
];

// Textos de 2010 em diante ainda a definir.
const HISTORY = [
  { year: '2005', title: 'Primeiro escritório', text: 'No Portão, em Curitiba.' },
  { year: '2008', title: 'Quatro Ventos Express', text: 'Serviços rápidos.' },
  { year: '2010', title: 'Monalisa CMS', text: 'Texto a definir.' },
  { year: '2014', title: 'Home office', text: 'Texto a definir.' },
  { year: '2026', title: 'Hoje', text: 'Texto a definir.' },
];

const STEPS = [
  { title: 'Desenhar', text: 'Diagnóstico de processos e sistemas e protótipo validado em versão funcional.' },
  { title: 'Codar', text: 'Frontend, backend, integrações e IA no mesmo time.' },
  { title: 'Testar', text: 'Homologação em ambiente privado antes da publicação.' },
  { title: 'Evoluir', text: 'Publicação, monitoramento e melhoria contínua a partir do uso real.' },
];

const ORBIT_TILT = 62;
const ORBIT_BASE_SPEED = 30;
const ORBIT_NODE_ANGLES = [315, 45, 135, 225];

const GALAXY_NODES = ORBIT_NODE_ANGLES.map((deg) => {
  const rad = (deg * Math.PI) / 180;
  return { x: 200 + Math.sin(rad) * 150, y: 200 - Math.cos(rad) * 150 };
});

const LOGOS = [
  { src: '/assets/clientes/lampa/incavel.svg', alt: 'Incavel' },
  { src: '/assets/clientes/lampa/4kst.svg', alt: '4KST' },
  { src: '/assets/clientes/lampa/af.svg', alt: 'Aliança Francesa' },
  { src: '/assets/clientes/lampa/agmov.svg', alt: 'AGMov' },
  { src: '/assets/clientes/lampa/bisuti.png', alt: 'Bisuti' },
  { src: '/assets/clientes/lampa/conela.svg', alt: 'Conela' },
  { src: '/assets/clientes/lampa/costi.png', alt: 'Costi' },
  { src: '/assets/clientes/lampa/momentum.svg', alt: 'Momentum' },
  { src: '/assets/clientes/lampa/produtive.svg', alt: 'Produtive' },
];

// O ano grande fica preso no centro da tela e mostra o ano do marco cujo centro está alinhado com ele; entre dois marcos, interpola com easing para assentar em cada ano.
function HistoryTimeline() {
  const yearRef = useRef<HTMLDivElement>(null);
  const listRef = useRef<HTMLOListElement>(null);

  useEffect(() => {
    const year = yearRef.current;
    const list = listRef.current;
    if (!year || !list) return;
    const items = Array.from(list.children) as HTMLElement[];
    const years = HISTORY.map((item) => Number(item.year));
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
    let frame = 0;

    const update = () => {
      frame = 0;
      const rect = year.getBoundingClientRect();
      const anchor = rect.top + rect.height / 2;
      const centers = items.map((item) => {
        const box = item.getBoundingClientRect();
        return box.top + box.height / 2;
      });
      let value = years[0];
      if (anchor >= centers[centers.length - 1]) {
        value = years[years.length - 1];
      } else if (anchor > centers[0]) {
        const k = centers.findIndex((center, i) => anchor >= center && anchor < centers[i + 1]);
        const t = (anchor - centers[k]) / (centers[k + 1] - centers[k]);
        const eased = reduced.matches ? Math.round(t) : t * t * (3 - 2 * t);
        value = Math.round(years[k] + (years[k + 1] - years[k]) * eased);
      }
      year.textContent = String(value);
    };

    const schedule = () => {
      if (!frame) frame = requestAnimationFrame(update);
    };

    update();
    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', schedule);
    return () => {
      window.removeEventListener('scroll', schedule);
      window.removeEventListener('resize', schedule);
      cancelAnimationFrame(frame);
    };
  }, []);

  return (
    <div className="yc">
      <div className="yc-year" ref={yearRef} aria-hidden="true">{HISTORY[0].year}</div>
      <ol className="yc-list" ref={listRef}>
        {HISTORY.map((item) => (
          <li key={item.year}>
            <span className="mono">{item.year}</span>
            <h3 className="yc-title">{item.title}</h3>
            <p>{item.text}</p>
          </li>
        ))}
      </ol>
    </div>
  );
}

// Órbita do Método com física: a seta gira sozinha a 30°/s, pode ser agarrada, arrastada e lançada (inércia com atrito que volta à velocidade base) e o plano inclina com mola seguindo o ponteiro.
function MethodOrbit() {
  const rootRef = useRef<HTMLDivElement>(null);
  const ringRef = useRef<HTMLDivElement>(null);
  const gxRef = useRef<HTMLDivElement>(null);
  const orbitRef = useRef<SVGGElement>(null);
  const tailRef = useRef<SVGCircleElement>(null);
  const faceRefs = useRef<(HTMLSpanElement | null)[]>([]);
  const stepRefs = useRef<(HTMLLIElement | null)[]>([]);

  useEffect(() => {
    const root = rootRef.current;
    const ring = ringRef.current;
    const gx = gxRef.current;
    const orbit = orbitRef.current;
    const tail = tailRef.current;
    if (!root || !ring || !gx || !orbit || !tail) return;
    const cleanupReveal = revealOnView(root);
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return cleanupReveal;

    const state = {
      angle: -45,
      speed: 0,
      dragging: false,
      grab: 0,
      lastPointer: 0,
      lastTime: 0,
      rx: ORBIT_TILT,
      ry: 0,
      vrx: 0,
      vry: 0,
      targetRx: ORBIT_TILT,
      targetRy: 0,
      active: -1,
      startAt: 0,
      visible: false,
    };
    let frame = 0;
    let prev = 0;

    const pointerAngle = (event: PointerEvent) => {
      const box = ring.getBoundingClientRect();
      const scale = 400 / box.width;
      const u = (event.clientX - (box.left + box.width / 2)) * scale;
      const v = ((event.clientY - (box.top + box.height / 2)) * scale) / Math.cos((state.rx * Math.PI) / 180);
      return (Math.atan2(u, -v) * 180) / Math.PI;
    };

    const unwrap = (next: number, ref: number) => next + 360 * Math.round((ref - next) / 360);

    const setActive = (index: number) => {
      if (index === state.active) return;
      state.active = index;
      stepRefs.current.forEach((step, i) => step?.classList.toggle('is-dim', i !== index));
      faceRefs.current[index]?.firstElementChild?.animate(
        [{ scale: 1 }, { scale: 1.8 }, { scale: 1 }],
        { duration: 520, easing: 'cubic-bezier(0.2, 0.7, 0.2, 1)' },
      );
    };

    const tick = (now: number) => {
      frame = requestAnimationFrame(tick);
      const dt = Math.min((now - (prev || now)) / 1000, 0.05);
      prev = now;
      if (!root.classList.contains('is-in')) return;
      if (!state.startAt) state.startAt = now + 1400;
      const running = now >= state.startAt;

      if (!state.dragging) {
        const base = running ? ORBIT_BASE_SPEED : 0;
        state.speed += (base - state.speed) * (1 - Math.exp(-dt * 0.9));
        state.angle += state.speed * dt;
      }

      const k = 140;
      const c = 13;
      state.vrx += (k * (state.targetRx - state.rx) - c * state.vrx) * dt;
      state.vry += (k * (state.targetRy - state.ry) - c * state.vry) * dt;
      state.rx += state.vrx * dt;
      state.ry += state.vry * dt;

      orbit.setAttribute('transform', `rotate(${state.angle.toFixed(2)} 200 200)`);
      gx.style.setProperty('--rx', `${state.rx.toFixed(2)}deg`);
      gx.style.setProperty('--ry', `${state.ry.toFixed(2)}deg`);

      const len = Math.min(0.06 + Math.abs(state.speed) / 2400, 0.5);
      tail.style.strokeDasharray = `${len} ${1 - len}`;
      tail.style.strokeDashoffset = state.speed >= 0 ? `${-(0.75 - len)}` : '-0.75';
      tail.style.opacity = String(Math.min(0.35 + Math.abs(state.speed) / 900, 0.85));

      if (running || state.dragging) {
        const pos = (((state.angle - 315) % 360) + 360 + 2) % 360;
        setActive(Math.floor(pos / 90) % 4);
      }
    };

    const onDown = (event: PointerEvent) => {
      state.dragging = true;
      ring.setPointerCapture(event.pointerId);
      ring.classList.add('is-grabbing');
      const at = pointerAngle(event);
      state.grab = state.angle - at;
      state.lastPointer = at;
      state.lastTime = performance.now();
      state.speed = 0;
    };

    const onMove = (event: PointerEvent) => {
      const box = ring.getBoundingClientRect();
      const nx = ((event.clientX - box.left) / box.width - 0.5) * 2;
      const ny = ((event.clientY - box.top) / box.height - 0.5) * 2;
      state.targetRx = ORBIT_TILT - Math.max(-1, Math.min(1, ny)) * 12;
      state.targetRy = Math.max(-1, Math.min(1, nx)) * 16;
      if (!state.dragging) return;
      const now = performance.now();
      const at = unwrap(pointerAngle(event), state.lastPointer);
      const dt = Math.max((now - state.lastTime) / 1000, 0.001);
      const instant = (at - state.lastPointer) / dt;
      state.speed = state.speed * 0.6 + instant * 0.4;
      state.angle = at + state.grab;
      state.lastPointer = at;
      state.lastTime = now;
    };

    const onUp = (event: PointerEvent) => {
      if (!state.dragging) return;
      state.dragging = false;
      ring.classList.remove('is-grabbing');
      if (ring.hasPointerCapture(event.pointerId)) ring.releasePointerCapture(event.pointerId);
      if (performance.now() - state.lastTime > 80) state.speed *= 0.2;
      state.speed = Math.max(-2200, Math.min(2200, state.speed));
      state.vry += state.speed * 0.02;
    };

    const onLeave = () => {
      state.targetRx = ORBIT_TILT;
      state.targetRy = 0;
    };

    const observer = new IntersectionObserver(([entry]) => {
      state.visible = entry.isIntersecting;
      if (state.visible && !frame) {
        prev = 0;
        frame = requestAnimationFrame(tick);
      } else if (!state.visible && frame) {
        cancelAnimationFrame(frame);
        frame = 0;
      }
    });
    observer.observe(root);

    ring.addEventListener('pointerdown', onDown);
    ring.addEventListener('pointermove', onMove);
    ring.addEventListener('pointerup', onUp);
    ring.addEventListener('pointercancel', onUp);
    ring.addEventListener('pointerleave', onLeave);

    return () => {
      cleanupReveal?.();
      observer.disconnect();
      cancelAnimationFrame(frame);
      ring.removeEventListener('pointerdown', onDown);
      ring.removeEventListener('pointermove', onMove);
      ring.removeEventListener('pointerup', onUp);
      ring.removeEventListener('pointercancel', onUp);
      ring.removeEventListener('pointerleave', onLeave);
    };
  }, []);

  return (
    <div className="loop" ref={rootRef}>
      <div className="loop-ring" ref={ringRef} aria-hidden="true">
        <div className="gx" ref={gxRef}>
          <div className="gx-disc">
            <svg viewBox="0 0 400 400">
              <circle className="gx-orbit-ring is-outer" cx="200" cy="200" r="192" />
              <circle className="gx-orbit-ring is-inner" cx="200" cy="200" r="96" />
              <circle className="loop-track" cx="200" cy="200" r="150" />
              <g className="gx-orbit" ref={orbitRef} transform="rotate(-45 200 200)">
                <circle className="gx-tail" ref={tailRef} cx="200" cy="200" r="150" pathLength={1} />
                <path className="gx-arrow" d="M-6 -8 L5 0 L-6 8" transform="translate(200 50)" />
              </g>
            </svg>
            {GALAXY_NODES.map((node, index) => (
              <span key={index} className="gx-node" style={{ '--i': index, left: `${node.x / 4}%`, top: `${node.y / 4}%` } as CSSProperties}>
                <span className="gx-face" ref={(el) => { faceRefs.current[index] = el; }}>
                  <span className="loop-node" />
                  <span className="gx-num">{String(index + 1).padStart(2, '0')}</span>
                </span>
              </span>
            ))}
          </div>
        </div>
        <p className="loop-center">Design, Code,<br />Test, Repeat.</p>
      </div>
      <ol className="loop-steps">
        {STEPS.map((step, index) => (
          <li key={step.title} ref={(el) => { stepRefs.current[index] = el; }} style={{ '--i': index } as CSSProperties}>
            <h3 className="h3"><span className="mono">{String(index + 1).padStart(2, '0')}</span>{step.title}</h3>
            <div className="rich"><p>{step.text}</p></div>
          </li>
        ))}
      </ol>
    </div>
  );
}

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageSobre(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero is-home is-lens" onPointerMove={trackLens}>
        <AgentLog />
        <div className="wrap">
          <div>
            <h1 className="display">
              <span className="sr-only">Design, Code, Test, Repeat.</span>
              <span aria-hidden="true">
                Design, Code, Test,
                <Rotating items={['Repeat.']} />
              </span>
            </h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>O lema existe desde 2005. Vem do algoritmo do shampoo, a piada de TI sobre a instrução "lave, enxágue, repita", que nunca termina. Para a Quatro Ventos, o loop é a disciplina de melhorar processos continuamente: entender, construir, medir e repetir.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: prose */}
      <section className="section on-light is-mark-draw">
        <div className="wrap">
          <div className="prose-panel is-reveal" ref={revealOnView}>
            <h2 className="h2">Da web 1.0 à <em className="mark">era da I.A.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>A Quatro Ventos nasceu em Curitiba desenvolvendo sites. Vieram sistemas, integrações, infraestrutura e sustentação de longo prazo, sempre com o mesmo objetivo: fazer a operação dos clientes funcionar melhor. Hoje esse objetivo passa pela inteligência artificial, aplicada com criatividade e critério de engenharia.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* seção: história */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <header className="sec-head is-solo">
            <h2 className="h2">Nossa <em className="mark">história.</em></h2>
          </header>
          <HistoryTimeline />
        </div>
      </section>
      {/* seção: princípios */}
      <section className="section on-light is-flush is-mark-draw">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2"><em className="mark">Princípios</em></h2>
            <div className="rich intro">
              <p>O que orienta cada projeto, do primeiro diagnóstico à evolução depois da entrega.</p>
            </div>
          </header>
          <ul className="ex-grid is-glyphs" ref={revealOnView}>
            {PRINCIPLES.map((item, i) => (
              <li key={item.title} style={{ '--i': i } as CSSProperties}>
                <svg className="pr-glyph" viewBox="0 0 40 40" aria-hidden="true">{PRINCIPLE_GLYPHS[i]}</svg>
                <h3 className="h3">{item.title}</h3>
                <p>{item.text}</p>
              </li>
            ))}
          </ul>
        </div>
      </section>
      {/* seção: steps */}
      <section className="section on-dark is-timeline-head">
        <div className="wrap">
          <header className="sec-head">
            <h2 className="h2">Método</h2>
            <div className="rich intro">
              <p>Design, Code, Test, Repeat. O lema aplicado a cada projeto, em ciclos: o que se aprende em produção volta para o desenho.</p>
            </div>
          </header>
          <MethodOrbit />
        </div>
      </section>
      {/* seção: logos */}
      <section className="section on-white is-mark-draw">
        <div className="wrap clients">
          <header className="clients-head">
            <h2 className="h2">Empresas que <em className="mark">crescem com a gente.</em></h2>
            <div className="rich intro">
              <p>Do mercado financeiro à saúde e ao varejo, atendemos empresas de portes e setores diferentes com o mesmo cuidado: projeto bem planejado, entrega no prazo e suporte depois da publicação.</p>
            </div>
          </header>
          <ul className="logos is-mono">
            {LOGOS.map((item) => (
              <li key={item.src}>
                <img src={item.src} alt={item.alt} loading="lazy" />
              </li>
            ))}
          </ul>
        </div>
      </section>
      {/* seção: cta */}
      <section className="section on-white is-flush">
        <div className="wrap">
          <div className="prose-panel is-reveal" ref={revealOnView}>
            <h2 className="h2">O próximo projeto começa <em className="mark">com uma conversa.</em></h2>
            <div className="prose-panel-side">
              <div className="rich">
                <p>Conte qual processo precisa melhorar. Avaliamos o caso e indicamos por onde começar.</p>
              </div>
              <div className="actions">
                <WhatsAppCta label="Falar com um especialista" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
