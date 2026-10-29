---
name: Quatro Ventos
description: Site público da Quatro Ventos. Processos melhores com IA, automações e sistemas.
colors:
  bg: "#07070a"
  ink: "#0b0b0f"
  panel: "#121217"
  paper: "#f4f4f1"
  white: "#ffffff"
  lime: "#c8f25a"
  olive: "#5f7a12"
  whatsapp: "#25d366"
  on-dark-soft: "#c9c9c4"
  on-dark-mute: "#8d8d93"
  on-light-soft: "#3e3e44"
  on-light-mute: "#6b6b72"
  line-dark: "rgb(255 255 255 / 0.12)"
  line-light: "rgb(11 11 15 / 0.12)"
  danger: "#b42318"
typography:
  display:
    fontFamily: "Host Grotesk, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "clamp(44px, 7.2vw, 116px)"
    fontWeight: 650
    lineHeight: 0.98
    letterSpacing: "-0.05em"
  display-inner:
    fontFamily: "Host Grotesk, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "clamp(40px, 5.4vw, 88px)"
    fontWeight: 650
    lineHeight: 1
    letterSpacing: "-0.05em"
  headline-statement:
    fontFamily: "Host Grotesk, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "clamp(40px, 5.6vw, 92px)"
    fontWeight: 650
    lineHeight: 0.98
    letterSpacing: "-0.05em"
  headline:
    fontFamily: "Host Grotesk, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "clamp(34px, 4.4vw, 68px)"
    fontWeight: 650
    lineHeight: 0.98
    letterSpacing: "-0.045em"
  row-title:
    fontFamily: "Host Grotesk, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "clamp(22px, 2.2vw, 34px)"
    fontWeight: 600
    lineHeight: 1.1
    letterSpacing: "-0.035em"
  title:
    fontFamily: "Host Grotesk, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "clamp(22px, 1.8vw, 28px)"
    fontWeight: 650
    lineHeight: 1.08
    letterSpacing: "-0.03em"
  lead:
    fontFamily: "Host Grotesk, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "clamp(18px, 1.4vw, 21px)"
    fontWeight: 400
    lineHeight: 1.45
  body:
    fontFamily: "Host Grotesk, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "18px"
    fontWeight: 400
    lineHeight: 1.55
  label:
    fontFamily: "Host Grotesk, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "16px"
    fontWeight: 600
    lineHeight: 1.2
  mono:
    fontFamily: "Martian Mono, ui-monospace, SF Mono, Menlo, monospace"
    fontSize: "13px"
    fontWeight: 400
    letterSpacing: "0em"
rounded:
  mark: "0.12em"
  field: "14px"
  dropdown-item: "12px"
  dropdown: "18px"
  card: "22px"
  pill: "999px"
spacing:
  gutter: "clamp(20px, 3vw, 44px)"
  section: "clamp(80px, 9vw, 136px)"
  max: "1440px"
  header: "88px"
  grid-gap: "16px"
  card-padding: "28px"
  column-gap: "64px"
components:
  button-primary:
    backgroundColor: "{colors.white}"
    textColor: "{colors.ink}"
    typography: "{typography.label}"
    rounded: "{rounded.pill}"
    padding: "0 26px"
    height: "54px"
  button-primary-hover:
    backgroundColor: "#e4e4df"
    textColor: "{colors.ink}"
  button-primary-light:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.white}"
  button-primary-light-hover:
    backgroundColor: "#2a2a31"
    textColor: "{colors.white}"
  button-whatsapp:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.lime}"
    typography: "{typography.label}"
    rounded: "{rounded.pill}"
    padding: "0 14px 0 26px"
    height: "54px"
  button-whatsapp-hover:
    backgroundColor: "{colors.lime}"
    textColor: "{colors.ink}"
  button-line:
    backgroundColor: "transparent"
    textColor: "{colors.white}"
    typography: "{typography.label}"
    rounded: "{rounded.pill}"
    padding: "0 26px"
    height: "54px"
  button-small:
    backgroundColor: "{colors.white}"
    textColor: "{colors.ink}"
    rounded: "{rounded.pill}"
    padding: "0 20px"
    height: "46px"
  card-nav:
    backgroundColor: "{colors.white}"
    textColor: "{colors.ink}"
    rounded: "{rounded.card}"
    padding: "{spacing.card-padding}"
  card-nav-hover:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.white}"
  card-plan:
    backgroundColor: "{colors.white}"
    textColor: "{colors.ink}"
    rounded: "{rounded.card}"
    padding: "30px 28px 28px"
  chip:
    backgroundColor: "{colors.white}"
    textColor: "{colors.ink}"
    rounded: "{rounded.pill}"
    padding: "12px 18px"
  input-field:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink}"
    rounded: "{rounded.field}"
    padding: "12px 16px"
    height: "52px"
  nav-link:
    textColor: "{colors.on-dark-soft}"
    rounded: "{rounded.pill}"
    padding: "0 14px"
    height: "44px"
  dropdown:
    backgroundColor: "{colors.panel}"
    textColor: "{colors.on-dark-soft}"
    rounded: "{rounded.dropdown}"
    padding: "10px"
  whatsapp-float:
    backgroundColor: "{colors.whatsapp}"
    textColor: "{colors.white}"
    rounded: "{rounded.pill}"
    size: "60px"
  panel-statement:
    backgroundColor: "{colors.bg}"
    textColor: "{colors.white}"
    rounded: "{rounded.card}"
    padding: "clamp(32px, 5vw, 72px)"
  panel-statement-light:
    backgroundColor: "{colors.white}"
    textColor: "{colors.ink}"
    rounded: "{rounded.card}"
    padding: "clamp(32px, 5vw, 72px)"
  card-feature-lead:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.white}"
    rounded: "{rounded.card}"
    padding: "clamp(28px, 3vw, 44px)"
  card-feature-hover:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.lime}"
  quote-card:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink}"
    rounded: "{rounded.card}"
    padding: "40px 32px 28px"
  case-cover:
    backgroundColor: "{colors.ink}"
    rounded: "{rounded.field}"
  case-go:
    backgroundColor: "transparent"
    textColor: "{colors.white}"
    rounded: "{rounded.pill}"
    padding: "0 16px 0 18px"
    height: "40px"
  case-go-hover:
    backgroundColor: "{colors.white}"
    textColor: "{colors.ink}"
  logo-cell:
    backgroundColor: "{colors.paper}"
    rounded: "{rounded.card}"
    padding: "clamp(16px, 2vw, 28px)"
  logo-cell-hover:
    backgroundColor: "{colors.ink}"
---

# Design System: Quatro Ventos

## Overview

**Creative North Star: "A operação rodando ao fundo"**

O site é uma vitrine escura e editorial em que a IA trabalhando é o próprio pano de fundo: um log de agente em fonte mono corre desfocado atrás do título, e a segunda linha do H1 troca, em limão, entre o que a Quatro Ventos entrega. Fora das aberturas, a página vira leitura: papel claro, tipografia grande com tracking negativo, linhas finas separando conteúdo e cards brancos de canto generoso. O contraste entre aberturas escuras e seções de leitura claras é o ritmo da página.

A densidade é baixa e a escala tipográfica é alta. A hierarquia vem do tamanho e do peso da Host Grotesk, não de rótulos, ícones ou cor. A cor aparece pouco e com função fixa: limão marca a frase que importa e o WhatsApp; ação e interação são monocromáticas (branco no escuro, tinta no claro). Não há fotografia de banco, ilustração, gradiente em texto nem rótulo de seção acima do título. As únicas imagens são material real: logos de clientes e prints de projetos dos cases.

Todas as páginas são montadas por seções do CMS, e cada tipo de seção carrega um tom fixo. O sistema decide o fundo, o espaçamento entre seções e a forma das listas a partir do conteúdo, não do editor.

**Key Characteristics:**
- Aberturas escuras (hero, etapas, chamada) alternadas com seções de leitura em papel e seções de prova em branco.
- H1 e H2 enormes em Host Grotesk peso 650, tracking entre -0.045em e -0.05em, entrelinha abaixo de 1.
- Limão restrito a três lugares e única cor da marca; ação e interação em branco e tinta.
- Martian Mono apenas para números, o log de agente e nomes de tecnologia.
- Pílulas para ações e etiquetas; cards brancos com raio de 22px; linhas de 1px para estruturar listas.
- Movimento ligado à leitura: linha rotativa e deriva do log no hero, efeitos guiados pela rolagem nas seções (marca-texto, timeline, entrada de cards) e resposta ao ponteiro (lente, tilt, preenchimento circular). Tudo desligado em movimento reduzido.

## Colors

Paleta de tinta quase preta e papel quente, com dois acentos saturados de papel fixo.

### Primary
- **Ação monocromática** (white / ink): CTA principal que não é WhatsApp (pílula sólida do header, do hero, da chamada, dos planos e do envio do formulário). Em fundo escuro é branca com texto tinta; em papel ou branco é tinta com texto branco. Card de navegação em hover vira tinta. Borda de foco dos campos e checkbox em tinta.
- **Oliva** (olive): sublinhado de links de leitura em seções claras, onde o limão não tem contraste. Nunca como fundo nem como texto.

### Secondary
- **Limão Sinal** (lime): só na linha rotativa do H1, nos destaques de frase (trecho entre asteriscos no título vira destaque) e no CTA de WhatsApp. Em seção escura o destaque é texto limão; em seção clara é fundo limão com texto em tinta.

### Tertiary
- **Verde WhatsApp** (whatsapp): exclusivo do botão flutuante de WhatsApp, por reconhecimento da marca do canal.

### Neutral
- **Noite** (bg): fundo das aberturas, do header sólido, do menu mobile, do rodapé e das seções escuras.
- **Tinta** (ink): texto em seções claras, fundo da pílula de WhatsApp, estado ativo do filtro de cases e do FAQ aberto.
- **Painel** (panel): fundo do dropdown do menu principal.
- **Papel** (paper): fundo das seções de leitura; fundo dos campos de formulário e dos cards dentro de seções brancas.
- **Branco** (white): fundo de seções de prova (depoimentos, logos), de cards, planos, chips e do card de formulário.
- **Texto Suave / Mudo em escuro** (on-dark-soft, on-dark-mute): lead, intro e links secundários sobre fundo escuro; metadados e rodapé.
- **Texto Suave / Mudo em claro** (on-light-soft, on-light-mute): corpo de texto e metadados sobre papel ou branco; numeração dos cards.
- **Linhas** (line-dark, line-light): divisores de 1px de listas, FAQ, etapas, logos e rodapé.
- **Erro** (danger): mensagens de campo inválido e status de erro do formulário.

### Named Rules
**The Three Places Rule.** O limão aparece como cor de composição apenas na linha rotativa, nos destaques de frase do título e no CTA de WhatsApp. Estados de sistema (anel de foco sobre fundo escuro, seleção de texto, link de pular para o conteúdo) também usam limão por legibilidade; nenhum outro elemento de conteúdo pode usá-lo.

**The Lime Is The Only Color Rule.** Fora do limão, o site é preto, branco e cinza. O componente de CTA decide sozinho: link de WhatsApp vira pílula limão, qualquer outro destino vira pílula sólida (branca no escuro, tinta no claro).

**The Tone By Type Rule.** O fundo é do tipo de seção, nunca do editor: hero, etapas e chamada são escuros; depoimentos e logos são brancos; todos os demais são papel.

## Typography

**Display Font:** Host Grotesk (com system-ui, -apple-system, Segoe UI, sans-serif), fonte variável local de 300 a 800.
**Body Font:** Host Grotesk.
**Label/Mono Font:** Martian Mono (com ui-monospace, SF Mono, Menlo, monospace), fonte variável local.

**Character:** Uma grotesca contemporânea em tamanhos de cartaz, comprimida por tracking negativo, contra uma mono técnica pequena que aparece só onde há dado de máquina.

### Hierarchy
- **Display** (650, até 116px, entrelinha 0.98): H1 dos heros. Na home com linha rotativa o teto cai para 108px e a linha quebra livre; nas páginas internas usa a variante de 88px com largura máxima de 18ch.
- **Headline Statement** (650, até 92px, 0.98): H2 da seção de chamada e do segundo de dois blocos de texto seguidos.
- **Headline** (650, até 68px, 0.98): H2 de seção, largura máxima de 15 a 16ch.
- **Row Title** (600, até 34px, 1.1): linhas de link em lista.
- **Title** (650, até 28px, 1.08): H3 de cards, etapas e linhas título-texto.
- **Lead** (400, até 21px, 1.45): abertura abaixo do H1, até 560px.
- **Body** (400, 18px, 1.55; 17px abaixo de 640px): texto corrido, até 62ch em blocos de leitura.
- **Label** (600, 16px; 15px na pílula pequena): pílulas e navegação (15.5px, peso regular).
- **Mono** (400, 13px, tracking 0): numeração 01, 02, 03 de cards e etapas, período do preço, contador do lightbox, nomes de tecnologia da stack do case e o log de agente.

### Named Rules
**The Mono Is Machine Rule.** Martian Mono só aparece em números, no log de agente e em nomes de tecnologia. Nunca em título, rótulo de seção ou botão.

**The Size Is Hierarchy Rule.** Não existe eyebrow nem rótulo acima de título. A hierarquia é feita por tamanho, peso e tracking.

**The Asterisk Highlight Rule.** O destaque de frase é escrito no CMS como trecho entre asteriscos no título e renderizado como marca limão. É o único jeito de colorir parte de um título; não há gradiente em texto.

## Layout

Coluna única centralizada com largura máxima de 1440px e respiro lateral fluido (20 a 44px). Seções verticais com padding fluido de 80 a 136px. O header é fixo, com 88px (76px abaixo de 640px), transparente sobre o hero e sólido com blur depois de 24px de rolagem.

Cabeçalhos de seção usam grade assimétrica de 1.15fr por 1fr: título à esquerda, intro alinhada à base à direita; sem intro, o título ocupa a linha sozinho. Blocos de texto repetem a mesma grade (título à esquerda, corpo à direita). Quando dois blocos de texto vêm seguidos, o segundo usa a variante empilhada: título em escala de chamada ocupando a largura e o corpo alinhado à direita com até 640px. FAQ e contato usam 0.8fr por 1.2fr, com o título do FAQ fixo durante a rolagem. A chamada usa 1.3fr por 1fr alinhada à base.

**Colapso de tom:** quando uma seção tem o mesmo tom da anterior (exceto hero), o padding superior é zerado (`.is-flush`), para que duas seções de papel seguidas leiam como uma só superfície.

**Receita de página (padrão da home):** a home é a referência de ritmo para as demais páginas. A sequência alterna escuro, claro e branco, e cada bloco tem um papel fixo:

1. **Hero escuro** (`.hero`): H1 com destaque, lead e duas ações (WhatsApp em limão e secundária em linha). Na home, altura de tela cheia (`.is-home`) e lente (`.is-lens`).
2. **Oferta em papel** (`.section.on-light`): cabeçalho de seção com destaque desenhado e grade de cards em destaque (`.cards.is-feature`), ou cards numerados comuns quando os itens não são quatro.
3. **Afirmação em papel colapsado** (`.section.on-light.is-flush`): painel de destaque claro (`.prose-panel.is-light.is-reveal`) com um parágrafo e um CTA.
4. **Método escuro** (`.section.on-dark.is-timeline-head`): timeline horizontal de seis etapas com scrub (`.steps.is-timeline.is-scrub`).
5. **Prova em branco** (`.section.on-white`): depoimentos em grade igual (`.quotes.is-even`) com cabeçalho solo.
6. **Trabalho escuro** (`.section.on-dark`): grade aberta de cases com tilt (`.cases-grid.is-3.is-open` e `.case-card.is-tilt`).
7. **Clientes em branco** (`.section.on-white`): cabeçalho à esquerda e logos mono à direita (`.clients` e `.logos.is-mono`).
8. **Chamada final em branco colapsado** (`.section.on-white.is-flush`): painel de destaque escuro (`.prose-panel.is-reveal`).

Páginas internas seguem a mesma lógica com menos blocos: hero, oferta ou conteúdo em papel, uma afirmação, método ou etapas quando houver processo, prova (depoimentos, cases ou logos) e chamada final. Toda seção clara cujo título tenha destaque recebe `.is-mark-draw`.

**Listas em quatro formas, decididas pelo conteúdo:**
1. Todos os itens com texto e link: cards de navegação numerados em grade (quatro colunas quando são exatamente quatro itens).
2. Itens com texto, sem link em todos: linhas título-texto em duas colunas (0.9fr por 1.4fr) separadas por linha fina.
3. Itens com link e sem texto: linhas de link grandes com seta circular à direita.
4. Itens só com título: etiquetas em pílula quando são seis ou mais e curtos (até 22 caracteres); senão, bullets em duas colunas com marcador circular de 8px.

**Responsivo:** menu principal vira menu mobile em tela cheia abaixo de 1140px; abaixo de 1100px grades de quatro cards caem para duas e logos para quatro colunas; abaixo de 900px todas as grades de duas colunas viram uma, etapas vão para duas e logos para três; abaixo de 640px cards, etapas e formulário ficam em uma coluna, logos em duas e as pílulas do hero ocupam a largura.

## Elevation & Depth

O sistema é plano. A profundidade vem do contraste entre seções escuras e claras, de cards brancos sobre papel e de contornos internos de 1 a 2px. Sombras projetadas existem só em elementos que flutuam acima da página: o dropdown do menu, o botão flutuante de WhatsApp e o card de case inclinado pelo ponteiro. No hero, a profundidade é atmosférica: o log desfocado e girado 4 graus fica atrás de uma vinheta radial escura que limpa a área do título.

### Shadow Vocabulary
- **Dropdown** (`box-shadow: 0 0 0 1px rgb(255 255 255 / 0.12), 0 24px 48px rgb(0 0 0 / 0.45)`): painel do submenu no header.
- **Flutuante** (`box-shadow: 0 10px 30px rgb(0 0 0 / 0.35)`): botão de WhatsApp fixo no canto.
- **Header sólido** (`box-shadow: 0 1px 0 rgb(255 255 255 / 0.12)`): linha inferior do header depois da rolagem, com blur de 14px.
- **Tilt** (`box-shadow: 0 24px 48px -24px rgb(0 0 0 / 0.35)`): card de case em hover com tilt, fora da grade aberta. Na grade aberta (`.is-open`) não há sombra.

### Named Rules
**The Flat Page Rule.** Cards, planos, depoimentos e formulários não têm sombra. Sombra só para o que flutua sobre o conteúdo ou está sendo inclinado pelo ponteiro.

## Shapes

Duas famílias de forma: pílula total (999px) para tudo que é ação ou etiqueta (botões, links do menu, chips, filtros, selos do hero, metadados do case, botão de WhatsApp) e retângulo de canto generoso (22px) para containers de conteúdo (cards, planos, depoimentos, card de formulário, card de case, capa e resultados). Campos e status do formulário usam 14px; dropdown 18px com itens de 12px. Ícones de ação ficam dentro de círculos (seta do card, seta da linha de link, mais do FAQ, seta da pílula de WhatsApp). Contornos são internos (inset), nunca bordas que alteram o tamanho.

## Components

### Buttons
- **Shape:** pílula (999px), altura 54px, 46px na variante pequena do header.
- **Primary:** pílula sólida, branca com texto tinta em fundo escuro e tinta com texto branco em papel ou branco; padding 0 26px, peso 600.
- **WhatsApp:** fundo tinta, texto limão, contorno interno limão de 1px e seta em círculo limão de 30px à direita. Só existe quando o destino é WhatsApp.
- **Line:** transparente com contorno interno de 1px (branco a 70% em escuro, tinta a 50% em claro). Usada para o CTA secundário.
- **Hover / Focus:** todas sobem 2px. Sólida escurece um tom (#e4e4df no escuro, #2a2a31 no claro); WhatsApp inverte (fundo limão, texto tinta, seta em tinta); line fecha o contorno e ganha véu de 4 a 6%. Foco é anel de 2px com offset 3px, limão em escuro e tinta em claro. Desabilitado: opacidade 0.6 e cursor de progresso.

### Chips
- **Style:** pílula branca, texto tinta, 15.5px peso 500, padding 12px 18px. Selos do hero são a mesma pílula com contorno branco a 45% sobre escuro.
- **State:** o filtro de cases usa pílula com contorno e fica tinta com texto branco quando ativo.

### Cards / Containers
- **Corner Style:** 22px.
- **Background:** branco em seções de papel; papel quando a seção é branca.
- **Shadow Strategy:** nenhuma (The Flat Page Rule).
- **Border:** nenhuma.
- **Internal Padding:** 28px (planos 30px 28px 28px).
- **Card de navegação:** numeração mono no topo com seta em círculo papel, título e texto na base, altura mínima de 260px. No hover sobe 4px, vira tinta com texto branco e a seta gira 45 graus sobre círculo branco.

### Inputs / Fields
- **Style:** fundo papel, borda de 1px na linha clara, raio 14px, altura mínima 52px (textarea 140px), rótulo acima em 14.5px peso 500.
- **Focus:** borda tinta e fundo branco, sem anel adicional.
- **Error / Disabled:** mensagem em vermelho de erro a 13.5px abaixo do campo; status de erro em caixa com véu vermelho a 8%; status de sucesso em papel com contorno fino.

### Navigation
- **Header:** marca "quatro**ventos**" em 27px (peso 300 com "ventos" em 700), links em pílula de 44px com texto suave que clareia e ganha véu branco a 7% no hover, na página atual e com submenu aberto. Itens com filhos têm caret que gira 180 graus. O dropdown abre com fade e 6px de deslocamento, com o hub em destaque no topo separado por linha.
- **Mobile:** botão circular de três traços que vira X; painel em tela cheia sobre noite, itens de 26px peso 600 separados por linhas, submenu por botão circular, CTA em pílula de largura total no fim (o tom segue o componente de CTA). Fecha com Escape e trava a rolagem do corpo.
- **Rodapé:** marca grande (até 64px), contatos, colunas auto-ajustáveis a partir de 160px com título de 15px e links mudos; faixa inferior com copyright, lema e redes.

### Linha rotativa (signature)
A segunda linha do H1 da home é uma pilha de termos em limão sobre o mesmo grid; a cada 2.8s o termo ativo sai para cima e o próximo entra de baixo em 0.9s com curva cubic-bezier(0.75, 0, 0.2, 1). Leitores de tela recebem o título com o primeiro termo; a pilha visual fica oculta para eles. Com movimento reduzido, o primeiro termo fica fixo.

### Log de agente (signature)
Fundo de todos os heros, incluindo a página 404: linhas de log mono em 15 a 22px, cinza-azulado com realces em azul, oliva e violeta, borradas a 2.4px, opacidade 0.5, giradas -4 graus, derivando para cima em 60s em loop. Sem legenda. Decorativo, oculto para leitores de tela.

### Lente do hero (`.hero.is-lens` + `trackLens`)
Variante da home. O log perde o desfoque (opacidade 0.6) e a vinheta ganha `backdrop-filter: blur(3px)` com máscara radial de 200px que segue o ponteiro (`--lens-x`, `--lens-y`, registradas com `@property` e transição de 0.5s). Onde a lente passa, o log fica nítido; o resto fica desfocado. Posição inicial em 72% por 38%. Em telas sem hover a máscara é removida e o hero fica todo desfocado. Usar só no hero da home; heros internos mantêm o log desfocado estático.

### Fluxo de agente no hero (`.hero.has-flow` + `FlowCanvas`)
Fundo alternativo ao log para páginas de agentes e automação. Grade de pontos brancos a 11% em passo de 28px, revelada da esquerda para a direita, e um fluxo em SVG à direita (até 800px) sendo montado nó a nó em ciclo de 12s: gatilho, agente, ferramentas, ação e o desvio de confiança para um humano. Nós são retângulos de raio 14px em `#0e0f15` com contorno branco a 16%, um quadrado de 10px colorido pelo tipo (azul-acinzentado gatilho, violeta agente, oliva ferramenta, branco ação e humano, mudo condição), rótulo em mono 10.5px e nome em 15px peso 600. Os fios se desenham antes de cada nó entrar e um cursor percorre a montagem. O véu do hero vira degradê horizontal (97% à esquerda, 12% à direita) para manter o texto limpo. Opacidade 0.8; 0.24 abaixo de 900px. Rótulos traduzidos pelo dicionário `flow.*`. Com movimento reduzido, o fluxo aparece montado e sem cursor. Um hero por página usa ou o log ou o fluxo, nunca os dois.

- **Presets:** `FlowCanvas preset="automation"` mantém a geometria e troca os rótulos para um fluxo de automação (e-mail recebido, IA extrai dados, nota fiscal, ERP, lançar pedido, dados incompletos, aprovar no Slack). Usar `agent` em páginas de agentes e `automation` em páginas de automação.
- **RAG:** `RagCanvas` usa o mesmo contêiner (`.flow-canvas`) com outra cena no mesmo ciclo de 12s: pergunta, base de 12 documentos varrida por uma linha de busca, 3 trechos acesos em oliva (`#9db35c`), fios até o modelo e resposta montada linha a linha com 2 citações de fonte. Em movimento reduzido, mostra o estado final sem a varredura. Usado em `/ia-com-dados-da-empresa`.
- **n8n:** `N8nCanvas` usa o mesmo contêiner com a linguagem do editor do n8n, em ciclo de 14s: gatilho Gmail em formato de "D" com raio, AI Agent com sub-nós OpenAI e Postgres em linha tracejada, If com saídas true/false, HTTP Request para o ERP, Slack no ramo false e Gmail de confirmação. A montagem vai até 44%; na execução (48-70%), nós e fios do caminho executado ficam verdes (`#9db35c`) com "1 item" nas conexões, e o ramo false não executa. As keyframes `n8n-{pop|draw|fade}-{início%}` são geradas no componente a partir de `N8N_STEPS`. Usado em `/automacao-com-ia`.
- **LLMs no sistema:** `LlmCanvas` mostra uma janela do sistema da empresa com 3 tarefas, um Gateway LLM e os provedores ChatGPT, Claude e Gemini (ponto de cor de cada marca: `#74aa9c`, `#d97757`, `#4e8df5`). Em ciclo de 18s, cada tarefa envia um pulso claro pelo gateway até um provedor diferente, que acende em oliva; a resposta volta como pulso oliva e preenche o campo com a etiqueta do modelo. Os pulsos são traços curtos (`stroke-dasharray: 0.08 1.2`) andando por `stroke-dashoffset`. Em movimento reduzido, mostra os 3 campos preenchidos, sem pulsos. Usado em `/integracao-de-llm`.
- **Sistema AI-native:** `SystemCanvas` mostra a interface de um sistema se montando (janela, busca "Pergunte ao sistema", menu, fila de pedidos e painel lateral). Em seguida, a IA processa a fila linha a linha, com etiqueta, confiança e status "Automático" em oliva; a linha com 61% de confiança vai para "Revisão humana", em âmbar (`#e0a458`, uso exclusivo desta ilustração). Por fim, o painel "Insight da IA" monta um gráfico, um texto e a ação sugerida. O ciclo é de 16s. As keyframes `sys-{tipo}-{início%}` são geradas a partir das chamadas de `sys()` no render. Usado em `/sistemas-com-ia`.
- **Software sob medida (do rascunho ao sistema):** `SoftwareCanvas` não é fluxo. A tela de ordens de serviço começa como wireframe de prancheta (traço tracejado, X nos espaços de imagem e gráfico, blocos cinza no lugar de texto) com anotações em oliva itálico. Cada região troca o rascunho pela versão real 40% do ciclo depois de aparecer; em seguida entram os dados (KPIs, status das linhas, gráfico). O rótulo no topo passa de "Protótipo" a "Sistema em produção". Usa `createTimeline` com os tipos `wire` e `note`. Em movimento reduzido, mostra só o sistema final. Regra geral: nem todo hero precisa ser fluxo; escolher a linguagem pelo assunto da página. Usado em `/software-sob-medida`.
- **SaaS (clientes sobre a mesma base):** `SaasCanvas`. Uma base multi-tenant (login, permissões, planos, cobrança) e o painel "Assinaturas" sobem primeiro; três clientes assinam em sequência, cada um como uma janela própria empilhada com o seu plano, e a cada assinatura o painel ganha uma barra de receita recorrente e uma fatura "Pago". Fecha com a nota em oliva "cada cliente vê só os próprios dados" e o selo "+3 clientes". Usa `createTimeline`. Usado em `/desenvolvimento-de-saas`.
- **Modernização (varredura do legado):** `ModernizeCanvas`. Uma calculadora antiga (janela cinza com barra azul, fonte mono, campos afundados, tabela e gráfico serrilhado) é varrida da esquerda para a direita por uma linha oliva em três paradas (entradas, cálculo, gráfico); atrás da linha aparece a versão nova (campos com slider, KPIs, curva). O selo "Em produção" pulsa o ciclo inteiro e cada módulo concluído ganha um chip com check. A troca usa `clip-path: inset()` animado nos grupos `.mod-legacy` e `.mod-modern` (keyframes `mod-*` em site.css, 16s). Em movimento reduzido, mostra só a versão nova. Usado em `/modernizacao-de-sistemas`.
- **UI/UX (wireframe, código, site):** `UxCanvas`. A mesma tela de landing page passa por três camadas no mesmo lugar: wireframe tracejado com X nos espaços de imagem (2-30%), editor `page.tsx` com os componentes digitados linha a linha e cursor piscando (36-62%) e o site renderizado com título "Design fiel, do Figma ao ar.", botão oliva, imagem e cards (68-92%). Embaixo, as etapas Wireframe, Código e Site no ar acendem em sequência. Troca de camadas nas keyframes `ux-*` de site.css (16s). Em movimento reduzido, mostra só o site. Usado em `/ui-ux-design`.
- **QA (suíte de testes):** `QaCanvas`. Janela "Suíte de testes" com sete testes em linhas: cada um roda (destaque oliva) e passa com check e tempo de execução; "cálculo do frete" falha em âmbar com a mensagem "esperado R$ 18,90, recebido R$ 0,00", é retestado e passa. No painel "Resultado", a barra de cobertura sobe até 92% e Desktop, Tablet e Celular recebem check; fecha com o selo "Pronto para publicar". Usa `createTimeline` (a falha usa o tipo `wire`). Em movimento reduzido, mostra todos os testes aprovados. Usado em `/qa-e-testes`.
- **ERP e CRM (funil de vendas):** `PipelineCanvas`. Kanban "Funil de vendas" com cinco colunas (Lead, Qualificado, Proposta, Negociação, Ganho), cards fantasmas e um espaço tracejado no topo de cada coluna. O card do contato (contorno oliva, valor e cinco pontos de progresso) é arrastado de coluna em coluna com um cursor visível só durante o arraste; cada etapa acende um ponto. Em "Ganho" o card fica oliva e entra o selo "Pedido criado no ERP". Deslocamento e cursor nas keyframes `pipe-*` de site.css (16s). Em movimento reduzido, o card aparece direto em "Ganho". Usado em `/erp-e-crm`.
- **Sites (responsivo):** `SitesCanvas`. Desktop, tablet e celular lado a lado montam o mesmo site ao mesmo tempo, seção por seção, cada um no próprio layout (menu com links ou hambúrguer, hero, imagem, cards em 3, 2 ou 1 coluna, rodapé). Embaixo, os anéis de Performance 98, SEO 100 e Acessibilidade 96 fecham em sequência e entra o selo "No ar". Usado em `/desenvolvimento-de-sites`.
- **Landing pages (anúncio ao lead):** `LandingCanvas`. O cursor clica no "Saiba mais" do anúncio, um traço oliva leva à landing page, os campos Nome, WhatsApp e E-mail são preenchidos um a um, o botão é clicado e o formulário vira "Recebido!". À direita, o card "Novo lead" chega e a barra de conversão sobe até 4,8%. Usado em `/landing-pages`.
- **E-commerce (pedido de ponta a ponta):** `EcomCanvas`. Página de produto com tamanho escolhido e clique em "Comprar" (o carrinho ganha o selo 1); o painel do pedido percorre Pagamento aprovado, Nota fiscal emitida, Estoque atualizado, Etiqueta de envio e Rastreio, cada etapa com o sistema que executa (Pix, ERP, Frete, WhatsApp). Fecha com "Sem digitação entre sistemas". Usado em `/desenvolvimento-de-ecommerce`.
- **WordPress (editor de blocos):** `WpCanvas`. Editor com inseridor "+": título, parágrafo, imagem, colunas e botão entram selecionados, com o nome do bloco ao lado; "Atualizar" é clicado e entra "Página atualizada". O painel "Saúde do site" confirma núcleo, plugins, backup e firewall e fecha com "Tudo em dia". Usado em `/sites-wordpress`.
- **SEO e LLMs (busca e resposta de IA):** `SeoCanvas`. Página de resultados em que o site sobe uma posição por vez até o primeiro lugar, empurrando os concorrentes (keyframes `seo-*` de site.css); em seguida a "Resposta da IA" é montada e cita o site como primeira fonte. Em movimento reduzido, mostra o site em primeiro e a resposta completa. Usado em `/seo-e-llms`.
- **Sustentação de software (incidente e rotina):** `SustainCanvas`. Painel "Produção" com disponibilidade e gráfico de erros por hora; um pico âmbar abre o "Incidente #318", o commit de correção é publicado e o status fica verde. À direita, a "Rotina da semana" fecha item a item até "Tudo em produção". Usado em `/sustentacao-de-software`.
- **Manutenção de sites (atualizações e relatório):** `MaintainCanvas`. Tela de atualizações do WordPress aplica núcleo, plugins e tema um a um, a barra de backup na nuvem enche e o "Relatório mensal" monta uptime, carregamento e serviços executados, fechando com "Relatório enviado". Usado em `/manutencao-de-sites`.
- **Recuperação de WordPress (varredura e limpeza):** `RecoverCanvas`. Varredura arquivo a arquivo marca três como malware (âmbar), a limpeza troca cada marca por "limpo" e o aviso vira "Malware removido"; as etapas análise, limpeza, correção, restauração e proteção fecham em "Site no ar e protegido". Usado em `/recuperacao-de-site-wordpress`.
- **Hospedagem (painel e caixa de entrada):** `HostCanvas`. Domínio em HTTPS com selo SSL, barras de uso de arquivos e e-mails e contas de e-mail; à direita, a caixa de entrada recebe mensagens e o anti-spam marca a suspeita, fechando com "Site e e-mails no ar". Usado em `/hospedagem-de-sites`.
- **VPS e infraestrutura (deploy e servidor):** `VpsCanvas`. Terminal com `docker compose up -d` sobe os containers um a um até "Deploy concluído"; à direita, CPU, memória e disco do servidor e os itens de segurança (firewall, SSL, backups) fecham em "Servidor monitorado". Usado em `/vps-e-infraestrutura`.

Os heros de Suporte e infraestrutura usam o tipo `swap` da timeline (`sys('swap', at)`), que mostra o elemento por 28% do ciclo e some: é o estado âmbar de alerta antes da correção. Em movimento reduzido, `.sys-swap` fica oculto e só o estado final (verde) aparece.
- Os cinco heros da seção Web usam `createTimeline` e reaproveitam `sys-*`, `wf-*`, `ux-*` e `qa-*`; as classes próprias (`site-*`, `lp-*`, `ec-*`, `wp-*`, `seo-*`) ficam no bloco "Heros da seção Web" de site.css. O cursor (`lp-cursor`) e as etiquetas de bloco (`wp-sel`) somem em movimento reduzido.
### Card de lead no hero (`LeadForm` + `.hero-split`)
Captação padrão do hero de todas as páginas, no modelo click-to-WhatsApp: o visitante sinaliza interesse e a equipe chama no WhatsApp. Hero em duas colunas (1.1fr e 0.9fr, centradas): título até 80px e 14ch com lead à esquerda, card de até 460px à direita (embaixo, abaixo de 1100px). O card segue o vocabulário escuro do hero: painel translúcido (#121217 a 58%) com `backdrop-filter: blur(18px) saturate(140%)` sobre o fundo animado, raio 22px, contorno interno de 1px na linha escura, sem sombra. Campos com fundo branco a 4%, borda na linha escura, raio 14px e 48px de altura; no foco a borda vai a branco 60% e o fundo a 7%; erro em salmão claro (#f2998d) para manter contraste no escuro. Rótulos em texto suave. A frente tem título branco em 20 a 23px peso 650 ("Receba nosso contato"), mini copy em texto suave de 14.5px ("Deixe seus dados que entramos em contato."), nome e WhatsApp em largura cheia (o WhatsApp é um campo composto `PhoneInput`: seletor de país com bandeira e DDI à esquerda, separado por fio, e número com máscara do país; envio validado pelo tamanho completo) e uma única pílula sólida branca em largura cheia, "Tenho interesse". Sem e-mail: o retorno é sempre pelo WhatsApp. Ao enviar (`/api/contato/lead`), o card gira 180 graus no eixo Y em 0.9s (cada face gira com a própria perspectiva, sem `preserve-3d` no pai, para o blur acompanhar) com `cubic-bezier(0.16, 1, 0.3, 1)`; o verso mostra um círculo branco com check tinta que se desenha, "Obrigado, {nome}.", a confirmação de que a equipe vai chamar no número informado e a pílula de WhatsApp (tinta com limão) com mensagem pronta. O foco vai para o título do verso e a face oculta fica `inert`. Com movimento reduzido, troca de face sem giro.

### Etapas
Lista numerada em três colunas sobre escuro, cada etapa aberta por uma linha fina com um traço branco de 56px por cima, número mono, título e texto até 34ch.

### Timeline de método (`.steps.is-timeline.is-scrub`)
Seis etapas em uma linha sobre escuro, presas a uma régua de 1px. Cada etapa tem um ponto limão de 11px sobre a régua, número mono (28px acima de 1100px), título de 19 a 22px peso 600 e texto de 15.5px até 24ch. O cabeçalho da seção vira coluna única (`.is-timeline-head`), com H2 sem limite de largura e intro até 62ch.
- **Scrub:** com `animation-timeline` disponível e acima de 1100px, a régua se preenche em limão da esquerda para a direita conforme a rolagem (20% a 55% da passagem), e cada etapa sai de desfoque 6px e opacidade 0.12 para nítida, com o ponto pulsando (escala 0 a 1.7 a 1) e o número passando de cinza para limão, em sequência pelo índice `--i`. Abaixo de 1100px cada etapa só ganha foco ao entrar na tela.
- **Responsivo:** três colunas abaixo de 1100px, cada uma com linha própria; uma coluna abaixo de 640px.
- **Uso:** só para processo sequencial real (método, onboarding, etapas de entrega). Seis etapas é o desenho; com outra contagem, usar Etapas comuns.

- **Cinco etapas:** `.is-5` usa cinco colunas acima de 1100px; abaixo disso segue a regra padrão.
### Cards em destaque (`.cards.is-feature`)
Grade bento de quatro colunas para a oferta principal com quatro itens. O primeiro card ocupa 2 por 2, em tinta, com número limão, seta em círculo limão de 56px, título de até 64px (12ch) e texto até 36ch; o último ocupa duas colunas; os do meio, uma. Altura mínima de linha 220px.
- **Hover/foco:** um círculo tinta cresce a partir da seta (canto superior direito, `clip-path` de 0 a 150% em 0.6s, `cubic-bezier(0.16, 1, 0.3, 1)`); o título vira limão, o texto clareia e a seta gira 45 graus sobre círculo limão. O card não sobe. No card líder o círculo é painel e a seta vira branca. Foco com contorno limão de 2px.
- **Responsivo:** duas colunas abaixo de 1100px (primeiro e último em largura cheia); uma abaixo de 640px.
- **Uso:** uma vez por página, para o índice de serviços ou subserviços. Com quantidade diferente de quatro, usar cards numerados comuns.

### Faixa de usos (`.uses-band`)
Lista curta de usos ou aplicações não clicáveis, sem número, num único painel tinta de raio 22px abaixo do `sec-head` padrão. Quatro colunas separadas por fio `--line-dark`; cada coluna tem H3 branco no topo e texto `--on-dark-soft` de 16px na base, com 56px de respiro entre eles. Sem limão dentro da faixa.
- **Entrada (scroll):** o painel assenta de `scale(0.9)` e raio 56px para tamanho e raio finais entre a entrada e 35% da passagem; os títulos entram da esquerda com opacidade até 47%; acima de 1100px os fios verticais se desenham de cima para baixo (`scaleY`) até 50%. Tudo na mesma `view-timeline` (`--uses-band`), protegido por `@supports (animation-timeline: view())` e `prefers-reduced-motion: no-preference`; sem suporte, a faixa aparece estática.
- **Responsivo:** 2 por 2 abaixo de 1100px; uma coluna abaixo de 640px, com fio horizontal entre itens.
- **Uso:** quando os itens são equivalentes e informativos (onde algo se aplica). Para índice de serviços com link, usar `.cards.is-feature`.

### Checklist de entregas (`.checklist`)
Lista de itens só com título que representam o que é entregue. Duas colunas com leitura de cima para baixo (`columns: 2`, balanceadas automaticamente para qualquer quantidade), fio `--line-light` entre linhas, texto de 19px e marcador em círculo tinta de 26px com check branco de 14px. Uma coluna abaixo de 900px. Substitui os bullets quando a lista é de entregas.

### Grade de exemplos (`.ex-grid`)
Lista longa (oito ou mais) de itens com título e texto, sem link, em seção de papel. Três colunas de cards brancos sem borda, raio 22px, padding 28px, intervalo de 16px e linhas de mesma altura (`grid-auto-rows: 1fr`). Título H3 reduzido (20 a 24px) e texto de 15.5px em `--on-light-soft`. O último item pode ser um quadro de fechamento (`li.is-more`), também branco, com link sublinhado e seta para `#contato` alinhado à base (sublinhado vira oliva no hover).
- **Holofote (só `hover: hover`):** ao passar o mouse num card, os demais caem para opacidade 0.35 com blur de 1.5px, e um círculo tinta cresce do canto do título (`clip-path` de 0 a 150% em 0.6s, `cubic-bezier(0.16, 1, 0.3, 1)`); título vira limão e texto `--on-dark-soft`. O quadro de fechamento não recebe o círculo.
- **Responsivo:** duas colunas abaixo de 1100px; uma abaixo de 640px, com altura livre.
- **Uso:** para listas curtas com texto, usar as linhas título-texto (`.def-rows`).

### Marca-texto desenhado (`.is-mark-draw`)
Modificador de seção clara. O destaque limão do H2 (`.mark`) começa com largura 0 e se desenha até 100% conforme o título atravessa a tela (`view-timeline`, faixa de 80% da entrada até 50% da passagem). Em seções escuras o destaque é texto limão e não desenha. Aplicar em toda seção clara cujo título tenha destaque.

### Depoimentos
Depoimento único vira bloco grande com aspas de até 180px à esquerda; vários sem modificador viram colunas em cascata de cards.
- **Grade igual (`.quotes.is-even`):** três cards de mesma altura em seção branca, fundo papel, raio 22px, padding 40px 32px 28px. Texto de 18.5px peso 450 em tinta; aspas gigantes (200px) em tinta a 6% no canto superior direito como marca d'água; autor no pé, separado por linha fina, nome em 600 e empresa em mudo. Uma coluna abaixo de 900px. Preferir esta forma quando houver exatamente três depoimentos.

### Grade de cases (`.cases-grid.is-3.is-open` + `.case-card.is-tilt`)
Três colunas sem card de fundo: capa 4 por 3 com raio 14px, e abaixo o nome (20 a 24px), metadados em pílulas com contorno fino e texto mono de 12px, e o botão "Ver case" (`.case-go`) alinhado à direita. A capa é um link sem foco (`tabIndex -1`, `aria-hidden`); o acesso por teclado é o `.case-go`, com `aria-label` nomeando o case. Espaço entre linhas de 48px.
- **Tilt:** com mouse, o card inclina até 9 graus em X e 11 em Y seguindo o ponteiro (`--tilt-x`, `--tilt-y` via `trackTilt`/`resetTilt`), a imagem se desloca até 16px em sentido contrário com escala 1.1 e um brilho radial branco a 22% acompanha o ponteiro. Em fundo escuro o nome vira limão no hover. Só com hover real e sem movimento reduzido.
- **Ver case:** pílula de 40px com contorno fino e seta de 14px; no hover preenche (branco em escuro, tinta em claro) e a seta avança 2px na diagonal.
- **Entrada:** cards sobem 48px e aparecem conforme a rolagem, escalonados por coluna (`--c`).
- **Responsivo:** duas colunas abaixo de 1100px e uma abaixo de 640px.

### Cases em destaque (`<CaseShowcase>` / `<CaseFeatures>`)
Componente reutilizável em `resources/js/site/case-showcase.tsx`, com os dados centralizados em `static/showcase.ts` (`SHOWCASE[slug]`); cada página só escolhe os slugs. `CaseShowcase` monta a seção escura com cabeçalho (H2 e intro) e `CaseFeatures`, que pode ser usado sozinho dentro de outra seção (home, acima da grade de 3). Cada item é um `.case-card.is-wide.is-tilt`: capa à esquerda em 1.35fr e texto em 1fr (nome de 28 a 40px, pílulas de metadados, descrição `.case-desc` até 44ch e "Ver case" ou "Ver o produto"). A proporção da capa segue a imagem: 1200/630 por padrão, `is-cover-16x9` ou `is-cover-16x10`. Com mais de um case, empilham com 56 a 104px entre eles e os pares invertem o lado da capa. Uma coluna abaixo de 900px. Links externos abrem em nova aba. Usado em `/software-sob-medida`, `/sistemas-com-ia`, `/desenvolvimento-de-saas`, `/modernizacao-de-sistemas`, `/ui-ux-design` e na home.

Enquanto o case real não existe, `placeholderCase(slug, tags)` (em `static/showcase.ts`) gera um item `placeholder: true`: card sem link e sem tilt, capa tracejada com grade de pontos e "Case em breve" centralizado, mesma proporção 1200/630. Para trocar, basta substituir a chamada por `SHOWCASE[slug]`. Em uso nas páginas da seção Web: `/desenvolvimento-de-sites`, `/landing-pages`, `/desenvolvimento-de-ecommerce`, `/sites-wordpress` e `/seo-e-llms`; e nas páginas de Suporte e infraestrutura: `/sustentacao-de-software`, `/manutencao-de-sites`, `/recuperacao-de-site-wordpress`, `/hospedagem-de-sites` e `/vps-e-infraestrutura`.

### Clientes (`.clients` + `.logos.is-mono`)
Grade de 1fr por 1.35fr: cabeçalho à esquerda (H2 com destaque e intro de 19px até 44ch) e logos à direita em três colunas (duas abaixo de 640px; cabeçalho em cima abaixo de 900px). Cada célula é um retângulo 16 por 10, fundo papel, raio 22px, com o logo em silhueta tinta (`brightness(0)`, 32px de altura, até 78% de largura, opacidade 0.6). No hover, um círculo tinta cresce a partir do canto superior direito e o logo volta à cor original (os arquivos são brancos sobre transparente). As células entram com a mesma subida escalonada dos cases.
- **Variante padrão (`.logos`):** cinco colunas de células papel com raio 4px, logos quadrados em escala de cinza com multiply e opacidade 0.7, e uma linha limão de 2px que cresce na base no hover. Usar para os logos PNG antigos.

**Proveniência de raster:** o build não contém raster gerado. Os únicos rasters do sistema são os logos reais de clientes do site anterior, copiados de `assets/img/clientes/*.png` para `site rba/public/assets/clientes/` (aliancafrancesa, cycloar, ddm, digio, fernandasaad, garimpo, giselesiqueira, hemera, inoveit, liv, rodolfofontana, santaplural), os logos vetoriais em `public/assets/clientes/lampa/` e as capas reais dos cases em `public/assets/cases/`. Capas e prints de cases vêm do CMS como material real dos projetos.

### Painel de destaque (`.prose-panel`)
Bloco de afirmação dentro de uma seção clara: um cartão de raio 22px e padding `clamp(32px, 5vw, 72px)` com duas colunas (1.15fr e 1fr, gap 32px 64px, alinhadas pela base). À esquerda, um H2 grande (`clamp(40px, 5.6vw, 92px)`, line-height 0.96, tracking -0.05em, até 13ch) com o trecho-chave em `<em class="mark">`. À direita, `.prose-panel-side` empilha o parágrafo (`clamp(18px, 1.35vw, 20px)`, line-height 1.6) e um CTA opcional com 28px de intervalo. Abaixo de 900px vira uma coluna.
- **Escuro (padrão):** fundo noite, texto branco, destaque como texto limão sem faixa, parágrafo em `on-dark-soft`.
- **Claro (`.is-light`):** fundo branco sobre seção papel, texto tinta, destaque com a faixa limão de marca-texto, parágrafo em `on-light-soft`. Com `.is-mark-draw` na seção, a faixa se desenha conforme a rolagem.
- **Entrada (`.is-reveal` + `revealOnView`):** o cartão abre de um recorte (`inset(10% 8%)`) até o tamanho cheio em 0.9s com `cubic-bezier(0.16, 1, 0.3, 1)`; título e coluna direita sobem 16px e aparecem em seguida (0.35s e 0.5s de atraso). Dispara uma vez quando 40% do cartão está visível; sem animação com movimento reduzido.
- **Uso:** uma afirmação por página, com no máximo um parágrafo e um CTA. Não empilhar dois painéis seguidos.

### Botão flutuante de WhatsApp
Círculo verde WhatsApp de 60px (56px no mobile) fixo a 24px do canto, ícone SVG branco, sobe 3px e cresce 4% no hover.

## Do's and Don'ts

### Do:
- **Do** usar o componente de CTA para toda ação: ele aplica limão ao WhatsApp e a pílula sólida ao resto.
- **Do** escrever destaques de título como trecho entre asteriscos no CMS, no máximo um por título.
- **Do** deixar o tipo de seção decidir o tom (escuro, papel ou branco) e confiar no colapso de padding entre seções do mesmo tom.
- **Do** usar a seta SVG própria (traço de 1.6, diagonal para cima à direita) para toda indicação de destino, dentro de círculo quando for ícone isolado.
- **Do** manter Martian Mono a 13px para números de ordem, unidades de preço e nomes de tecnologia.
- **Do** usar só material real como imagem: logos de clientes e prints dos projetos.
- **Do** desligar toda transição e animação em prefers-reduced-motion, incluindo a linha rotativa e a deriva do log.
- **Do** seguir a receita da home ao montar páginas internas: alternar escuro, papel e branco, uma afirmação em painel por página, prova antes da chamada final.
- **Do** proteger todo efeito guiado por rolagem com `@supports (animation-timeline: view())` e `prefers-reduced-motion: no-preference`, de modo que sem suporte o conteúdo apareça estático e completo.
- **Do** restringir efeitos de ponteiro (lente, tilt) a `hover: hover` e `pointerType === 'mouse'`.

### Don't:
- **Don't** usar limão em qualquer outro elemento de conteúdo além da linha rotativa, dos destaques de frase e do CTA de WhatsApp.
- **Don't** usar o verde WhatsApp fora do botão flutuante.
- **Don't** colocar eyebrow, rótulo ou kicker acima de títulos.
- **Don't** usar fotografia de banco, ilustração ou imagem gerada.
- **Don't** aplicar gradiente em texto; o único gradiente do sistema é a vinheta radial escura do hero.
- **Don't** usar biblioteca de ícones nem glifos como ícone; setas, caret, mais e WhatsApp são SVG próprios.
- **Don't** adicionar movimento fora do vocabulário documentado (linha rotativa, deriva do log, lente, marca-texto desenhado, timeline com scrub, preenchimento circular, tilt, entrada escalonada, revelação do painel e hovers).
- **Don't** repetir na mesma página um padrão de assinatura da home: um bloco de cards em destaque, uma timeline e um painel de afirmação por página, além da chamada final.
- **Don't** usar a lente fora do hero da home.
- **Don't** dar sombra a cards, planos ou formulários.
