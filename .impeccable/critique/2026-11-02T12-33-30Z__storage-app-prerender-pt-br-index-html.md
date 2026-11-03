---
target: slop home
total_score: 20
max_score: 32
na_heuristics: 7,10
p0_count: 0
p1_count: 2
target_identity: "file:/Users/gabriel/Projetos/quatroventos/htdocs/storage/app/prerender/pt-BR/index.html"
target_fingerprint: "sha256:3ca8a17108a095db6cdde48a487dc269b8c11e287444e70a550016e1a2e37a3c"
target_path: /Users/gabriel/Projetos/quatroventos/htdocs/storage/app/prerender/pt-BR/index.html
timestamp: 2026-11-02T12-33-30Z
slug: storage-app-prerender-pt-br-index-html
---
Method: dual-agent (A: a1078a8e-47ad-4b47-ba66-2844cada6b34 · B: c2da7120-6f3c-47af-8384-6fa3262a75a7)
Target: home (storage/app/prerender/pt-BR/index.html). Browser inspection skipped by owner rule (no browser testing); review from source, prerendered HTML and detector.

## Design Health Score (Nielsen)
| # | Heuristic | Score | Key issue |
|---|---|---|---|
| 1 | Visibility of status | 3 | H1 rotation has no state |
| 2 | Match real world | 2 | AI-native, LLMs, Homologação, /rag, English footer motto |
| 3 | User control | 2 | Infinite H1 rotation and log drift without pause |
| 4 | Consistency | 2 | Same WhatsApp action with different labels/prefills; hub names differ nav vs cards; lime outside allowed uses |
| 5 | Error prevention | 3 | No forms on home |
| 6 | Recognition vs recall | 3 | H1 meaning depends on which of 8 terms shows |
| 7 | Flexibility | n/a | Persuade, linear funnel |
| 8 | Aesthetic/minimalist | 2 | Simultaneous hero motion, 8 terms, two near-identical panels, 3 fixed widgets |
| 9 | Error recovery | 3 | Nothing to recover; cookie choice reversible |
| 10 | Help | n/a | Commercial surface |
| Total | | 20/32 | Acceptable (62%) |

## Design Specificity Verdict
~40% authored (hero: agent log with Brazilian ops vocabulary, rotating thesis), ~60% agency template below the fold.
Slop patterns: hub cards numbered 01-04 (sections.tsx:366); same-weight bento cards with circle-reveal hover (site.css:3619-3647); generic 6-step lifecycle without AI; three equal testimonial cards with giant quote glyph (site.css:5798); "Empresas que crescem com a gente" carousel; marker highlight on 4 of 6 H2s; cursor-following glass blur lens (site.css:1057, behaviors.js:207); purple/blue AI palette in the log (site.css:1004-1027); two panels with the same "render mais" idea.
DESIGN.md deviations: lime on step numbers, timeline dots, featured card number, card hover titles; log not marked as illustrative (realistic IDs).
Detector: 1 advisory finding (design-system-color on skip link), false positive (fragment without CSS; skip link uses --ink on --lime).

## What's Working
1. Agent log as positioning (NF-e, boleto, régua, ERP divergence to human review).
2. Hero accessibility (sr-only static H1, aria-hidden rotation/log, skip link, reduced motion).
3. System restraint (tokens, type, no eyebrows/gradient text/icon slop).

## Priority Issues
- [P1] Home sells AI but all proof is web-agency proof (testimonials about sites/support, "crescem com a gente", "entrega no prazo"). Fix: honest retitle of proof (clients since 2005) + labeled illustrative walkthrough of one process built from the log. Command: shape.
- [P1] "AI-native por método" not demonstrated: 6 generic steps without AI. Fix: state where AI enters and the deliverable per step, 4-5 steps, step numbers off lime. Command: clarify.
- [P2] Rotating H1 diluted (8 terms incl. websites, landing pages, "suporte" without period), likely clipping 641-1000px (nowrap + overflow hidden, site.css:2826-2836), no pause (WCAG 2.2.2). Fix: 3-4 AI/systems terms, stop after one cycle or pause on hover/focus, allow wrap. Command: harden.
- [P2] Copy breaks voice rules: "crescem com a gente", "faz por você?", "O que dizem nossos clientes.", "Falar com um especialista", "Da arquitetura à infraestrutura", "com foco em produtividade", "Design, Code, Test, Repeat." Fix: declarative rewrite, align WhatsApp prefills to labels. Command: clarify.
- [P3] Template layer below hero: drop hub numbers, single "render mais" panel, concrete next-step close, log palette inside committed world, decide on cursor lens. Commands: quieter, distill.

## Persona Red Flags
- Jordan: unexplained jargon; H1 changes before reading; no description of first conversation.
- Riley: rotating line clipping 641-1000px (worse in EN/ES); mega menu aria-hidden keyboard access to verify; weak logo alt text (Af, 4kst, Puc Pr).
- Casey: full-width cookie notice covers WhatsApp and language selector; 100svh hero plus reserved rotation height; hover-only card cue.
- Ops director evaluating AI vendors: no LGPD/data handling, ERP integration, time to first result, commercial model; reads as web agency that added AI.

## Minor Observations
- "entrega no prazo" is an unprovided deadline claim.
- Carousel logos differ from PRODUCT.md published list; confirm approval.
- WhatsApp float green #25d366 with heavy shadow is the loudest color.
- Header CTA white solid vs brief's purple pill; reconcile brief and DESIGN.md.

## Questions to Consider
- Why is the strongest proof (the log) blurred, hidden and unlabeled instead of a readable illustrative example?
- Should the home sell the whole catalog or one AI-in-operations argument?
- What can be truthfully said about the first conversation and first two weeks of a project?
