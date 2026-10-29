import type { LocaleInfo } from './i18n';

export type MenuNode = {
  label: string;
  href: string;
  children: MenuNode[];
};

export type SeoPayload = {
  title: string;
  description: string | null;
  robots: string;
  canonical: string;
  og_image: string | null;
  og_image_alt: string | null;
  twitter_card: string;
  og_site_name: string;
  og_locale: string;
  og_locale_alternates?: string[];
  hreflang?: Array<{ hreflang: string; href: string }>;
};

export type Section = {
  id: string;
  type: string;
  data: Record<string, unknown>;
};

export type CaseImage = {
  src: string;
  alt: string;
  caption?: string;
};

export type CaseCard = {
  slug: string;
  path: string;
  client: string;
  title: string;
  summary: string;
  sector: string | null;
  services: Array<{ slug: string; label: string; path: string }>;
  cover: CaseImage | null;
  gallery: CaseImage[];
  featured: boolean;
};

export type CaseDetail = CaseCard & {
  year: number | null;
  url: string | null;
  challenge: string;
  solution: string;
  deliverables: string[];
  stack: string[];
  results: Array<{ value?: string; label?: string }>;
  testimonial: { quote?: string; author?: string; role?: string } | null;
  next: CaseCard | null;
};

export type PagePayload = {
  slug: string;
  path: string;
  locale?: string;
  alternates?: Record<string, string>;
  template: string;
  sections: Section[];
  seo: SeoPayload;
  case?: CaseDetail;
};

export type FormField = {
  name: string;
  label: string;
  type: string;
  required?: boolean;
  hint?: string;
  options?: Array<{ label: string; value: string }>;
};

export type SiteForm = {
  submit_label?: string;
  lgpd_label?: string;
  sections?: Array<{ label?: string; fields?: FormField[] }>;
};

export type SitePayload = {
  name: string;
  locale?: string;
  default_locale?: string;
  locales?: LocaleInfo[];
  cookie?: string;
  home_path?: string;
  tagline: string | null;
  phone: string | null;
  phone_href: string | null;
  email: string | null;
  whatsapp_url: string | null;
  logo: string;
  address: string | null;
  instagram_url: string | null;
  linkedin_url: string | null;
  facebook_url: string | null;
  admin_path: string;
  menus: {
    header: MenuNode[];
    footer: MenuNode[];
  };
  seo: Record<string, unknown>;
  form: SiteForm | null;
};

declare global {
  interface Window {
    __SITE__?: {
      page: PagePayload;
      site: SitePayload;
    };
  }
}
