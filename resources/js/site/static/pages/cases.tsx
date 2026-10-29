// Gerado por scripts/export-static-pages.mjs a partir de /cases (CMS).
// Editável à mão durante o ajuste de layout; ao voltar para o CMS, a estrutura é portada para sections.tsx.
// Segue a receita de página da home e o padrão de /agentes-de-ia (DESIGN.md, Layout).
import type { StaticPageProps } from '../index';
import { AgentLog } from '../../hero';
import { CasesList } from '../../cases';
import { LeadForm } from '../../sections';
import type { CaseCard } from '../../types';
import { Arrow, revealOnView } from '../../ui';

const WHATSAPP = 'https://wa.me/554130399593?text=Vi%20os%20cases%20e%20gostaria%20de%20conversar%20sobre%20um%20projeto.';

const CASES: CaseCard[] = [
  {
    slug: 'fiscal-hub',
    path: '/cases/fiscal-hub',
    client: 'Fiscal Cobranças do Brasil',
    title: 'Cobrança no WhatsApp oficial, com acordo e boleto na mesma conversa',
    summary: 'Plataforma que integra o sistema de cobrança da operação ao WhatsApp oficial da Meta, gera acordos com boletos e reúne os resultados em painéis de BI.',
    sector: 'Finanças',
    services: [
      { slug: 'software-sob-medida', label: 'Desenvolvimento de software sob medida e web apps', path: '/software-sob-medida' },
      { slug: 'erp-e-crm', label: 'Implantação e integração de ERP e CRM', path: '/erp-e-crm' },
    ],
    cover: { src: '/assets/cases/fiscal-hub.jpg', alt: 'Painel do Fiscal Hub com envios e acordos por hora, ranking de templates e conversa de cobrança no WhatsApp' },
    gallery: [],
    featured: true,
  },
];

const QUOTES = [
  { text: 'Trabalhar com a Quatro Ventos tem sido uma experiência excelente. A equipe é extremamente profissional, ágil e sempre disponível para oferecer suporte quando necessário. Desde o início, demonstraram um grande compromisso em entender nossas necessidades e transformar nossas ideias em soluções. O site da Aliança Francesa de Curitiba é um reflexo desse trabalho cuidadoso e bem-executado. Além de um design visualmente bonito, a plataforma se destaca pela usabilidade, com navegação intuitiva e estrutura bem organizada. Graças ao conhecimento especializado da Quatro Ventos, conseguimos oferecer aos nossos usuários uma experiência acessível, eficiente e alinhada à identidade da nossa instituição.', name: 'Mayara Barros', company: 'Aliança Francesa de Curitiba' },
  { text: 'Sou cliente satisfeito e recorrente da Quatro Ventos. Recomendo a agência não só pela qualidade e profissionalismo, mas também pelo atendimento realmente comprometido. Dá pra ver que na Quatro Ventos o cliente importa.', name: 'Diogo Fontana', company: 'Editora Danúbio' },
  { text: 'Tenho orgulho de contar que, há vários anos, a Quatro Ventos se tornou meu parceiro de confiança para hospedagem. Sempre atenciosos e solícitos, a equipe, sob a liderança do Gabriel, se destaca pela rapidez e eficiência na resolução de qualquer eventual problema. Essa parceria de longa data me ajudou a desenvolver o novo site junto com eles, e o resultado ficou simplesmente maravilhoso. Parabéns por 20 anos de excelência e por transformar desafios em oportunidades de inovação.', name: 'Diego Araújo', company: 'Diego Araújo Osteopatia' },
  { text: 'Faz anos que contamos com os serviços da Quatro Ventos e sempre fui atendida com profissionalismo, agilidade e qualidade. O suporte é excelente e as soluções sempre atendem exatamente o que preciso. Recomendo muito para quem busca um serviço confiável e de alto nível!', name: 'Vivien Uhlmann', company: 'Cycloar' },
  { text: 'Atendimento e serviço excepcionais! Equipe qualificada e muito ágil na resolução de problemas! Super recomendo!', name: 'Alecsandra Santos', company: 'Loto Mais Fácil' },
  { text: 'Uma empresa séria que me ajudou com muita atitude de comprometimento, parabéns pela confiança que passam.', name: 'Rodrigo Gasparini', company: 'Gasparini' },
];

const LOGOS = [
  { src: '/assets/clientes/santaplural.png', alt: 'Santa Plural' },
  { src: '/assets/clientes/rodolfofontana.png', alt: 'Rodolfo Fontana' },
  { src: '/assets/clientes/liv.png', alt: 'Liv Decora' },
  { src: '/assets/clientes/inoveit.png', alt: 'Inove IT' },
  { src: '/assets/clientes/hemera.png', alt: 'Hemera DTVM' },
  { src: '/assets/clientes/garimpo.png', alt: 'Garimpo Home' },
  { src: '/assets/clientes/fernandasaad.png', alt: 'Fernanda Saad' },
  { src: '/assets/clientes/giselesiqueira.png', alt: 'Gisele Siqueira Dermatologista' },
  { src: '/assets/clientes/digio.png', alt: 'Digio' },
  { src: '/assets/clientes/ddm.png', alt: 'Data Driven Mkt' },
];

function WhatsAppCta({ label }: { label: string }) {
  return (
    <a className="pill lime" href={WHATSAPP} target="_blank" rel="noreferrer">
      {label}
      <span className="arrow"><Arrow /></span>
    </a>
  );
}

export default function PageCases(_props: StaticPageProps) {
  return (
    <>
      {/* seção: hero */}
      <section className="hero">
        <AgentLog />
        <div className="wrap hero-split">
          <div>
            <h1 className="display is-inner"><em className="mark">Projetos</em></h1>
            <div className="hero-foot">
              <div className="rich lead">
                <p>Processos melhorados com IA, software e web em finanças, educação, varejo e serviços.</p>
              </div>
            </div>
          </div>
          <LeadForm className="hero-form" id="contato" whatsappHref={WHATSAPP} />
        </div>
      </section>
      {/* seção: cases-list */}
      <section className="section on-light">
        <div className="wrap">
          <CasesList data={{ intro: '', title: '', filter: true, cases: CASES }} />
        </div>
      </section>
      {/* seção: logos */}
      <section className="section on-white">
        <div className="wrap">
          <header className="sec-head is-solo">
            <h2 className="h2">Clientes</h2>
          </header>
          <ul className="logos">
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
