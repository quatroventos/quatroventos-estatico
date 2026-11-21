@php
  $brand = $sitePayload['name'] ?? 'Quatro Ventos';
  $logo = $sitePayload['logo'] ?? asset('assets/logo-quatroventos.svg');
  $whatsapp = $sitePayload['whatsapp_url'] ?? null;
  $home = $sitePayload['home_path'] ?? '/';
  $bandLabel = ['alto' => 'Bom sinal: o site está preparado', 'medio' => 'Quase lá: ainda faltam ajustes', 'baixo' => 'Atenção: a IA tem dificuldade de ler o site'];
  $statusChip = ['good' => 'OK', 'ok' => 'Parcial', 'bad' => 'Falha'];
  $reportCta = [
    'alto' => [
      'title' => 'Quer fechar o <em class="mark">último ajuste</em> no site?',
      'body' => 'A nota já está alta. A Quatro Ventos fecha o que ainda falta — texto, dados do negócio ou arquivos básicos — com proposta de valor fechado.',
    ],
    'medio' => [
      'title' => 'Quer que a Quatro Ventos <em class="mark">complete</em> esses ajustes?',
      'body' => 'A Quatro Ventos aplica o que falta na página inicial — regras de entrada da IA, texto, dados do negócio e arquivos básicos — com proposta de valor fechado.',
    ],
    'baixo' => [
      'title' => 'Quer que a Quatro Ventos deixe o site <em class="mark">legível</em> para a IA?',
      'body' => 'A Quatro Ventos corrige o que impede a leitura — regras de entrada da IA, texto, dados do negócio e arquivos básicos — com proposta de valor fechado.',
    ],
  ];
  $measures = [
    ['pts' => 20, 'label' => 'A IA pode entrar no site', 'body' => 'O arquivo de regras do site (robots.txt) não manda o ChatGPT e outras IAs ficarem de fora.'],
    ['pts' => 20, 'label' => 'O texto da página inicial aparece sem truque', 'body' => 'O que você vende precisa estar escrito de verdade na página, não só aparecer depois que o site “liga” no celular ou no computador.'],
    ['pts' => 15, 'label' => 'Fica claro o que você faz, para quem e o preço', 'body' => 'Na primeira página: o que oferece, para quem é e quanto custa — ou como o orçamento funciona.'],
    ['pts' => 10, 'label' => 'Dados do negócio em formato que a máquina lê', 'body' => 'Um bloco técnico (JSON-LD) descrevendo serviço, produto, perguntas frequentes ou empresa local.'],
    ['pts' => 10, 'label' => 'Site com cadeado e resposta rápida', 'body' => 'Endereço com https e página que abre sem demora demais.'],
    ['pts' => 10, 'label' => 'Título e resumo da página', 'body' => 'O título da aba e o texto curto que aparece no Google (e ajuda a IA a entender o assunto).'],
    ['pts' => 5, 'label' => 'Resumo para IA (llms.txt)', 'body' => 'Um arquivo simples na raiz do site contando, em poucas linhas, o que a empresa faz.'],
    ['pts' => 5, 'label' => 'Quem está por trás e quando atualizou', 'body' => 'Página Sobre, nome de quem publica ou data de atualização visível.'],
    ['pts' => 5, 'label' => 'Lista das páginas (sitemap)', 'body' => 'Um arquivo que lista as páginas do site para facilitar a descoberta.'],
  ];
@endphp
<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}" />
    <meta name="robots" content="{{ $seo['robots'] }}" />
    <meta name="author" content="{{ $seo['og_site_name'] }}" />
    <link rel="canonical" href="{{ $seo['canonical'] }}" />
    {!! $__env->make('partials.favicon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render() !!}
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="{{ $seo['og_locale'] }}" />
    <meta property="og:site_name" content="{{ $seo['og_site_name'] }}" />
    <meta property="og:title" content="{{ $seo['title'] }}" />
    <meta property="og:description" content="{{ $seo['description'] }}" />
    <meta property="og:url" content="{{ $seo['canonical'] }}" />
    <meta property="og:updated_time" content="{{ $updatedAt }}" />
    <meta property="article:modified_time" content="{{ $updatedAt }}" />
    @if(!empty($seo['og_image']))
      <meta property="og:image" content="{{ media($seo['og_image']) }}" />
    @endif
    <meta name="twitter:card" content="{{ $seo['twitter_card'] }}" />
    @foreach($jsonld as $block)
      <script type="application/ld+json">{!! json_encode($block, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
    @endforeach
    {!! app('Illuminate\Foundation\Vite')(['resources/css/site.css', 'resources/js/site/ai-visibility.js']) !!}
  </head>
  <body class="ai-vis">
    <a class="skip" href="#conteudo">Pular para o conteúdo</a>
    {!! $__env->make('partials.site-chrome-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render() !!}

    <main id="conteudo" tabindex="-1">
      @if($report)
        @php
          $checks = collect($report->checks ?? []);
          $gapChecks = $checks->filter(fn (array $check): bool => ($check['status'] ?? '') !== 'good')->values();
          $gapCount = $gapChecks->count();
          $ctaCopy = $reportCta[$report->band] ?? $reportCta['medio'];
        @endphp
        <section class="section ai-vis-hero is-report" data-ai-vis-score-reveal data-score-stage="counting">
          <div class="ai-vis-atmosphere" aria-hidden="true">
            <div class="agent-log">
              <div>
                <span><span class="k">score</span> <span class="s">{{ $report->score }}</span> / 100{!! "\n" !!}</span>
                <span><span class="k">band</span> {{ $report->band }}{!! "\n" !!}</span>
                <span><span class="k">gaps</span> {{ $gapCount }}{!! "\n" !!}</span>
                <span><span class="k">host</span> {{ $report->host }}{!! "\n" !!}</span>
                <span><span class="k">score</span> <span class="s">{{ $report->score }}</span> / 100{!! "\n" !!}</span>
                <span><span class="k">band</span> {{ $report->band }}{!! "\n" !!}</span>
                <span><span class="k">gaps</span> {{ $gapCount }}{!! "\n" !!}</span>
                <span><span class="k">host</span> {{ $report->host }}{!! "\n" !!}</span>
              </div>
            </div>
          </div>
          <div class="wrap ai-vis-hero-grid is-report">
            <div class="ai-vis-hero-copy">
              <h1>Nota de <em class="mark">{{ $report->host }}</em></h1>
              <p class="ai-vis-meta">Teste feito em {{ $report->created_at->timezone('America/Sao_Paulo')->format('d/m/Y') }}</p>
              <p class="ai-vis-band is-{{ $report->band }}">{{ $bandLabel[$report->band] ?? $report->band }}</p>
              <div class="ai-vis-actions">
                <button type="button" class="pill line" data-copy="{{ url($report->publicPath()) }}" data-copy-idle="Copiar link do resultado">Copiar link do resultado</button>
                <a class="pill line" href="{{ $toolPath }}">Testar outro site</a>
              </div>
            </div>
            <div class="ai-vis-score-panel" aria-label="Nota do site">
              <div class="ai-vis-flip" data-ai-vis-flip data-score="{{ $report->score }}">
                <p class="sr-only">{{ $report->score }} de 100</p>
                <div class="ai-vis-flip-board" aria-hidden="true">
                  <strong class="ai-vis-flip-fallback">{{ $report->score }}</strong>
                </div>
                <span class="ai-vis-flip-unit">de 100</span>
              </div>
            </div>
          </div>
        </section>

        <section class="section on-white" aria-labelledby="achados">
          <div class="wrap">
            <h2 id="achados" class="h2">O que encontramos</h2>
            @if($gapChecks->isEmpty())
              <p class="intro ai-vis-checks-summary">Nenhum ponto de atenção nesta leitura.</p>
            @else
              <p class="intro ai-vis-checks-summary">
                {{ $gapCount }} {{ $gapCount === 1 ? 'ponto de atenção' : 'pontos de atenção' }}

              </p>
              <ul class="ai-vis-checks">
                @foreach($gapChecks as $check)
                  <li class="is-{{ $check['status'] }}">
                    <div>
                      <strong>
                        <span class="ai-vis-chip is-{{ $check['status'] }}">{{ $statusChip[$check['status']] ?? $check['status'] }}</span>
                        {{ $check['label'] }}

                      </strong>
                      <p>{{ $check['message'] }}</p>
                    </div>
                    <span>{{ $check['points'] }} de {{ $check['max'] }} pontos</span>
                  </li>
                @endforeach
              </ul>
            @endif
          </div>
        </section>

        @if($whatsapp)
          <section class="section on-white ai-vis-apply" aria-labelledby="aplicar-resultado">
            <div class="wrap">
              <div class="prose-panel is-reveal">
                <h2 id="aplicar-resultado" class="h2">{!! $ctaCopy['title'] !!}</h2>
                <div class="prose-panel-side">
                  <div class="rich">
                    <p>{{ $ctaCopy['body'] }}</p>
                  </div>
                  <div class="actions">
                    <a class="pill lime" href="{{ $whatsapp }}" target="_blank" rel="noreferrer">
                      Chamar no WhatsApp
                      <span class="arrow" aria-hidden="true">
                        <svg class="icon-arrow" viewBox="0 0 16 16" aria-hidden="true">
                          <path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                      </span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </section>
        @endif
      @else
        <section class="section ai-vis-hero is-mark-draw">
          <div class="ai-vis-atmosphere" aria-hidden="true">
            <div class="agent-log">
              <div>
                <span><span class="k">scan</span> home → <span class="s">ok</span>{!! "\n" !!}</span>
                <span><span class="k">robots</span> gptbot · claude · perplexity{!! "\n" !!}</span>
                <span><span class="k">html</span> body text <span class="s">readable</span>{!! "\n" !!}</span>
                <span><span class="k">schema</span> <span class="p">json-ld</span> · faq · service{!! "\n" !!}</span>
                <span><span class="k">score</span> <span class="s">0–100</span> · band{!! "\n" !!}</span>
                <span><span class="k">scan</span> home → <span class="s">ok</span>{!! "\n" !!}</span>
                <span><span class="k">robots</span> gptbot · claude · perplexity{!! "\n" !!}</span>
                <span><span class="k">html</span> body text <span class="s">readable</span>{!! "\n" !!}</span>
                <span><span class="k">schema</span> <span class="p">json-ld</span> · faq · service{!! "\n" !!}</span>
                <span><span class="k">score</span> <span class="s">0–100</span> · band{!! "\n" !!}</span>
              </div>
            </div>
          </div>
          <div class="wrap ai-vis-hero-grid">
            <div class="ai-vis-hero-copy">
              <h1>Como aparecer no <em class="mark">ChatGPT</em>?</h1>
              <p class="lead">Hoje muitas pessoas perguntam à IA onde comprar ou contratar. Digite o endereço do seu site abaixo e descubra se o ChatGPT consegue entender e indicar sua empresa.</p>
              <ul class="ai-vis-trust">
                <li>Grátis</li>
                <li>Sem cadastro</li>
                <li>Não mexe no seu site</li>
              </ul>
            </div>
            <div class="ai-vis-hero-tool">
              <form class="ai-vis-form" id="ai-vis-form" action="{{ $analyzeUrl }}" method="post" novalidate>
                {!! csrf_field() !!}
                <label class="sr-only" for="ai-vis-url">Endereço do site</label>
                <input id="ai-vis-url" name="url" type="url" inputmode="url" autocomplete="url" placeholder="https://seusite.com.br" required maxlength="500" />
                <input type="text" name="empresa_site" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true" />
                <button class="pill solid" type="submit">Ver minha nota</button>
              </form>
              <p class="hint" id="ai-vis-status" role="status" aria-live="polite" hidden></p>
            </div>
          </div>
        </section>

        <section class="section on-dark ai-vis-platforms" aria-label="Assistentes cobertos">
          <div class="wrap">
            <p class="ai-vis-platforms-label">Este teste verifica as ferramentas:</p>
            <ul>
              <li>
                <img src="{{ asset('assets/llms/chatgpt.svg') }}" alt="" width="28" height="28" loading="lazy" decoding="async" />
                <span>ChatGPT</span>
              </li>
              <li>
                <img src="{{ asset('assets/llms/claude.svg') }}" alt="" width="28" height="28" loading="lazy" decoding="async" />
                <span>Claude</span>
              </li>
              <li>
                <img src="{{ asset('assets/llms/gemini.svg') }}" alt="" width="28" height="28" loading="lazy" decoding="async" />
                <span>Gemini</span>
              </li>
              <li>
                <img src="{{ asset('assets/llms/perplexity.svg') }}" alt="" width="28" height="28" loading="lazy" decoding="async" />
                <span>Perplexity</span>
              </li>
            </ul>
          </div>
        </section>

        <section class="section on-light ai-vis-pillars is-reveal" aria-labelledby="pilares">
          <div class="wrap">
            <h2 id="pilares" class="h2">Três passos simples</h2>
            <p class="intro">Primeiro você mede. Depois corrige o que trava. Por fim, o site fica mais fácil de ser citado.</p>
            <ol class="ai-vis-pillar-list">
              <li>
                <strong>Ver a nota do site</strong>
                <p>De 0 a 100. Mesma regra para todo mundo: nove itens com peso fixo. Não é opinião de uma IA — é checklist técnico.</p>
              </li>
              <li>
                <strong>Saber o que arrumar primeiro</strong>
                <p>O resultado lista o que falta, do mais importante ao menos: entrada da IA, texto claro, preço, dados do negócio e confiança.</p>
              </li>
              <li>
                <strong>Facilitar a citação do seu nome</strong>
                <p>Quando a IA consegue entrar e a página inicial explica o negócio com clareza, sobe a chance de o ChatGPT indicar você em vez de falar genérico.</p>
              </li>
            </ol>
          </div>
        </section>

        <section class="section on-dark ai-vis-measure-section is-reveal" aria-labelledby="medir" id="medir">
          <div class="wrap">
            <h2 class="h2">O que olhamos no teste</h2>
            <p class="intro">Nove itens, cem pontos no total. Só o que qualquer pessoa consegue ver no site público. Se não confirmar na hora, não conta ponto.</p>
            <div class="ai-vis-measure-track" aria-hidden="true"><span></span></div>
            <ul class="ai-vis-measure">
              @foreach($measures as $item)
                <li>
                  <span class="ai-vis-measure-pts">{{ $item['pts'] }} <span class="ai-vis-measure-unit">PTS</span></span>
                  <div>
                    <strong>{{ $item['label'] }}</strong>
                    <p>{{ $item['body'] }}</p>
                  </div>
                </li>
              @endforeach
            </ul>
          </div>
        </section>

        <article class="section on-white ai-vis-article is-mark-draw is-reveal" aria-labelledby="guia" id="guia">
          <div class="wrap">
            <header class="ai-vis-article-head">
              <h2 class="h2">Como aparecer no <em class="mark">ChatGPT</em>, na prática</h2>
              <p class="intro">Cliente pergunta à IA “quem faz X na minha cidade” ou “qual empresa de Y”. Se o seu site estiver ilegível para a máquina, a resposta sai sem o seu nome.</p>
            </header>

            <div class="ai-vis-article-body">
              <h3>Por que uns negócios aparecem e outros não</h3>
              <p>O ChatGPT não funciona igual ao Google. Ele não monta uma lista de dez links azuis. Ele escreve uma resposta. Para colocar o seu nome nela, precisa conseguir ler o site, entender o que você faz e achar o conteúdo confiável.</p>
              <p>Se a página inicial estiver “trancada” para a IA, se o texto só existir em animações ou se ninguém entender o que você vende e quanto custa, a ferramenta pula o seu site — ou responde sem citar ninguém.</p>
              <p>Então <strong>como aparecer no ChatGPT</strong> não é só anunciar. É deixar o site legível: a IA entra, lê a oferta e consegue citar o negócio.</p>

              <h3>O que a IA precisa achar no site</h3>
              <p>Começa pela página inicial e pelos arquivos básicos da raiz. A IA precisa de permissão para entrar. Precisa de texto escrito de verdade (não só imagens ou botões). Precisa saber o que você oferece, para quem e quanto custa — ou como pede orçamento. Ajuda ter um bloco de dados do negócio, uma página Sobre, uma data de atualização e uma lista das páginas.</p>
              <p>Isso não garante que toda pergunta cite a sua marca. Mas remove obstáculos que, sozinhos, já tiram o site da conversa.</p>

              <h3>Passo a passo</h3>
              <ol class="ai-vis-steps">
                <li>
                  <strong>Deixe a IA entrar</strong>
                  <p>Peça ao responsável pelo site para checar o arquivo robots.txt. Ele não pode bloquear o ChatGPT e ferramentas parecidas.</p>
                </li>
                <li>
                  <strong>Escreva a oferta na página inicial</strong>
                  <p>O texto principal precisa estar na página mesmo, não só aparecer depois que o site carrega com efeitos. Se um visitante com a tela “simples” não lê a oferta, a IA também não lê.</p>
                </li>
                <li>
                  <strong>Diga o que é, para quem é e quanto custa</strong>
                  <p>Na home: o serviço ou produto, o público e o preço — ou como o orçamento funciona (o que muda o valor e o prazo da proposta).</p>
                </li>
                <li>
                  <strong>Coloque os dados do negócio em formato de máquina</strong>
                  <p>Quem cuida do site pode adicionar o bloco JSON-LD (serviço, produto, FAQ ou empresa local). Você não precisa editar código: basta pedir esse item no orçamento.</p>
                </li>
                <li>
                  <strong>Mostre quem está por trás</strong>
                  <p>Página Sobre, data de atualização, um resumo llms.txt e a lista de páginas (sitemap). São detalhes pequenos que, juntos, reforçam confiança.</p>
                </li>
              </ol>

              <h3>Estar bem no Google não resolve sozinho</h3>
              <p>Aparecer no Google e aparecer no ChatGPT são coisas relacionadas, mas diferentes. No Google, a disputa é posição nos resultados. No ChatGPT, a pergunta é: a IA consegue ler e confiar o bastante para citar o seu nome? Um site bem colocado no Google ainda pode falhar no ChatGPT se a IA estiver bloqueada ou se a oferta estiver escondida.</p>

              <h3>O que este teste não mede</h3>
              <p>Ele não diz se a sua marca já aparece nas respostas da IA hoje. Isso depende também de reputação, de quanto se fala de você na internet e do assunto da pergunta. O teste mede se o site está legível — a parte que dá para corrigir no próprio site.</p>
              <p>Use o formulário no topo. A nota é gratuita e sem cadastro. Se quiser que a Quatro Ventos faça as correções, o orçamento sai depois do diagnóstico.</p>
            </div>
          </div>
        </article>

        @if($whatsapp)
          <section class="section on-white ai-vis-apply" aria-labelledby="aplicar">
            <div class="wrap">
              <div class="prose-panel is-reveal">
                <h2 id="aplicar" class="h2">Quer que a Quatro Ventos <em class="mark">arrume isso</em> no site?</h2>
                <div class="prose-panel-side">
                  <div class="rich">
                    <p>Depois da nota, dá para fechar o que falta — regras de entrada da IA, texto, dados do negócio e arquivos básicos — com proposta de valor fechado.</p>
                  </div>
                  <div class="actions">
                    <a class="pill lime" href="{{ $whatsapp }}" target="_blank" rel="noreferrer">
                      Chamar no WhatsApp
                      <span class="arrow" aria-hidden="true">
                        <svg class="icon-arrow" viewBox="0 0 16 16" aria-hidden="true">
                          <path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                      </span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </section>
        @endif

        <section class="section on-dark is-reveal" aria-labelledby="faq" id="faq">
          <div class="wrap">
            <div class="faq-grid">
              <h2 class="h2">Dúvidas frequentes</h2>
              <div class="faq">
                <details>
                  <summary>Como aparecer no ChatGPT?<span class="plus" aria-hidden="true"></span></summary>
                  <div class="rich"><p>Deixe a IA entrar no site, escreva na página inicial o que você faz e quanto custa, use texto de verdade (não só imagem), e complete Sobre, resumo para IA e lista de páginas. Esta página testa exatamente isso.</p></div>
                </details>
                <details>
                  <summary>Isso é a mesma coisa que aparecer no Google?<span class="plus" aria-hidden="true"></span></summary>
                  <div class="rich"><p>Não. No Google você disputa posição numa lista de links. No ChatGPT, a IA escreve uma resposta. O site só entra nessa resposta se a máquina conseguiu ler e entender o que está publicado. Este teste mede essa parte.</p></div>
                </details>
                <details>
                  <summary>O ChatGPT usa o meu site de verdade?<span class="plus" aria-hidden="true"></span></summary>
                  <div class="rich"><p>Quando está liberado, ele pode citar páginas públicas. Se as regras do site bloqueiam a IA ou a página inicial quase não tem texto, a chance de citação cai.</p></div>
                </details>
                <details>
                  <summary>É de graça mesmo?<span class="plus" aria-hidden="true"></span></summary>
                  <div class="rich"><p>Sim. Sem cadastro. Você cola o endereço, recebe a nota e a lista do que corrigir. Nada é alterado no seu site. Fazer as correções é um serviço à parte, com orçamento.</p></div>
                </details>
                <details>
                  <summary>Vocês olham o site inteiro?<span class="plus" aria-hidden="true"></span></summary>
                  <div class="rich"><p>Não. O foco é a página inicial e os arquivos básicos da raiz. É o mesmo ponto de partida da maioria das IAs. Páginas internas entram na lista de páginas e no resumo, mas a nota é da home.</p></div>
                </details>
                <details>
                  <summary>Rodar o teste mexe no meu site?<span class="plus" aria-hidden="true"></span></summary>
                  <div class="rich"><p>Não. Só lemos o que já está público, como um visitante qualquer. Não instalamos nada e não acessamos área privada.</p></div>
                </details>
                <details>
                  <summary>Quem faz essa ferramenta?<span class="plus" aria-hidden="true"></span></summary>
                  <div class="rich"><p>A Quatro Ventos, de Curitiba: software, IA e sites. Os mesmos critérios entram no SEO do nosso CMS. Se quiser ajuda para aplicar as correções, chame no WhatsApp.</p></div>
                </details>
              </div>
            </div>
          </div>
        </section>
      @endif
    </main>

    <footer class="site-footer ai-vis-foot">
      <div class="wrap">
        <div class="footer-bottom">
          <span>© {{ date('Y') }} {{ $brand }}. Curitiba, desde 2005. Página atualizada em <time datetime="{{ $updatedAt }}">{{ \Illuminate\Support\Carbon::parse($updatedAt)->timezone('America/Sao_Paulo')->format('d/m/Y') }}</time>.</span>
          <nav aria-label="Institucional">
            <a href="{{ $home }}">Início</a>
            <a href="/sobre-a-quatro-ventos">Sobre</a>
            <a href="/contato">Contato</a>
            <a href="/geo-seo">SEO e LLMs</a>
          </nav>
        </div>
      </div>
    </footer>
  </body>
</html>
