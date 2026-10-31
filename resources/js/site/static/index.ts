// Gerado por scripts/export-static-pages.mjs. STATIC_MODE é editado à mão.
import type { ComponentType } from 'react';
import type { Section, SiteForm } from '../types';

// true: o front renderiza as páginas de ./pages; false: renderiza as seções vindas do CMS.
export const STATIC_MODE = true;

export type StaticPageProps = { form: SiteForm | null; sections?: Section[] };

export const STATIC_PAGES: Record<string, () => Promise<{ default: ComponentType<StaticPageProps> }>> = {
  "agentes-de-ia": () => import('./pages/agentes-de-ia'),
  "automacao-com-ia": () => import('./pages/automacao-com-ia'),
  "cases": () => import('./pages/cases'),
  "contato": () => import('./pages/contato'),
  "desenvolvimento-de-ecommerce": () => import('./pages/desenvolvimento-de-ecommerce'),
  "desenvolvimento-de-saas": () => import('./pages/desenvolvimento-de-saas'),
  "desenvolvimento-de-sites": () => import('./pages/desenvolvimento-de-sites'),
  "desenvolvimento-de-software": () => import('./pages/desenvolvimento-de-software'),
  "desenvolvimento-web": () => import('./pages/desenvolvimento-web'),
  "diagnostico-de-site": () => import('./pages/diagnostico-de-site'),
  "erp-e-crm": () => import('./pages/erp-e-crm'),
  "home": () => import('./pages/home'),
  "hospedagem-de-sites": () => import('./pages/hospedagem-de-sites'),
  "ia-com-dados-da-empresa": () => import('./pages/ia-com-dados-da-empresa'),
  "integracao-de-llm": () => import('./pages/integracao-de-llm'),
  "inteligencia-artificial": () => import('./pages/inteligencia-artificial'),
  "landing-pages": () => import('./pages/landing-pages'),
  "manutencao-de-sites": () => import('./pages/manutencao-de-sites'),
  "modernizacao-de-sistemas": () => import('./pages/modernizacao-de-sistemas'),
  "obrigado": () => import('./pages/obrigado'),
  "qa-e-testes": () => import('./pages/qa-e-testes'),
  "recuperacao-de-site-wordpress": () => import('./pages/recuperacao-de-site-wordpress'),
  "seo-e-llms": () => import('./pages/seo-e-llms'),
  "servicos": () => import('./pages/servicos'),
  "sistemas-com-ia": () => import('./pages/sistemas-com-ia'),
  "sites-wordpress": () => import('./pages/sites-wordpress'),
  "sobre": () => import('./pages/sobre'),
  "software-sob-medida": () => import('./pages/software-sob-medida'),
  "suporte-e-infraestrutura": () => import('./pages/suporte-e-infraestrutura'),
  "sustentacao-de-software": () => import('./pages/sustentacao-de-software'),
  "ui-ux-design": () => import('./pages/ui-ux-design'),
  "vps-e-infraestrutura": () => import('./pages/vps-e-infraestrutura'),
};
