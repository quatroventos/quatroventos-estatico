@php
  $brand = $sitePayload['name'] ?? 'Quatro Ventos';
  $logo = $sitePayload['logo'] ?? asset('assets/logo-quatroventos.svg');
  $whatsapp = $sitePayload['whatsapp_url'] ?? null;
  $home = $sitePayload['home_path'] ?? '/';
  $bandLabel = ['alto' => 'Pronto para ser citado: alto', 'medio' => 'Quase lá: médio', 'baixo' => 'Ainda frágil: baixo'];
  $statusChip = ['good' => 'OK', 'ok' => 'Parcial', 'bad' => 'Falha'];
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
    @include('partials.favicon')
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
    @vite(['resources/css/site.css', 'resources/js/site/ai-visibility.js'])
  </head>
  <body class="ai-vis">
    <a class="skip" href="#conteudo">Pular para o conteúdo</a>
    <header class="ai-vis-top">
      <div class="wrap ai-vis-top-bar">
        <a class="brand" href="{{ $home }}" aria-label="{{ $brand }}, início">
          <img src="{{ $logo }}" alt="" width="436" height="69" />
        </a>
        @if($report)
          <nav aria-label="Ferramenta">
            <a href="{{ $toolPath }}">Nova análise</a>
            @if($whatsapp)
              <a class="pill solid small" href="{{ $whatsapp }}" target="_blank" rel="noreferrer">Aplicar no meu site</a>
            @endif
          </nav>
        @endif
      </div>
    </header>

    <main id="conteudo" tabindex="-1">
      @if($report)
        <section class="section ai-vis-hero is-report">
          <div class="wrap">
            <h1>Nota de <em class="mark">{{ $report->host }}</em> na IA</h1>
            <p class="ai-vis-meta">Analisado em {{ $report->created_at->timezone('America/Sao_Paulo')->format('d/m/Y') }}</p>
            <div class="ai-vis-score" data-score="{{ $report->score }}">
              <strong>{{ $report->score }}</strong>
              <span>de 100</span>
            </div>
            <p class="ai-vis-band is-{{ $report->band }}">{{ $bandLabel[$report->band] ?? $report->band }}</p>
            @if($report->niche)
              <p class="ai-vis-niche">Nicho detectado: {{ $report->niche }}</p>
            @endif
            <p class="lead">Esta nota mede o quanto o ChatGPT, o Claude e outros assistentes conseguem ler e entender o site hoje.</p>
            <div class="ai-vis-actions">
              <button type="button" class="pill line" data-copy="{{ url($report->publicPath()) }}">Copiar link do relatório</button>
              <a class="pill solid" href="{{ $toolPath }}">Analisar outro site</a>
              @if($whatsapp)
                <a class="pill solid" href="{{ $whatsapp }}" target="_blank" rel="noreferrer">Aplicar no meu site</a>
              @endif
            </div>
          </div>
        </section>

        <section class="section on-dark" aria-labelledby="achados">
          <div class="wrap">
            <h2 id="achados" class="h2">O que encontramos</h2>
            <ul class="ai-vis-checks">
              @foreach($report->checks as $check)
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
          </div>
        </section>

        @if(($report->fixes ?? []) !== [])
          <section class="section" aria-labelledby="corrigir">
            <div class="wrap">
              <h2 id="corrigir" class="h2">Como corrigir</h2>
              <p class="intro">Do que mais pesa na nota para o que menos pesa.</p>
              <ol class="ai-vis-fixes">
                @foreach($report->fixes as $index => $fix)
                  <li>
                    <h3>{{ $index + 1 }}. {{ $fix['title'] }}</h3>
                    <p class="meta">Tempo estimado: {{ $fix['minutes'] }} min · Impacto: {{ $fix['impact'] }}</p>
                    <p>{{ $fix['body'] }}</p>
                    @if(!empty($fix['code']))
                      <pre><code>{{ $fix['code'] }}</code></pre>
                    @endif
                  </li>
                @endforeach
              </ol>
              @if($whatsapp)
                <p class="ai-vis-cta">Quer que a Quatro Ventos aplique isso no seu site? <a href="{{ $whatsapp }}" target="_blank" rel="noreferrer">Fale no WhatsApp</a>.</p>
              @endif
            </div>
          </section>
        @endif
      @else
        <section class="section ai-vis-hero">
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
          <div class="wrap">
            <h1>Seu negócio aparece quando perguntam para a <em class="mark">IA</em>?</h1>
            <p class="lead">Ferramenta gratuita, sem cadastro. Digite o endereço do site e veja, em menos de um minuto, se ChatGPT, Claude e Gemini conseguem ler e citar o seu negócio.</p>
            <form class="ai-vis-form" id="ai-vis-form" action="{{ $analyzeUrl }}" method="post" novalidate>
              @csrf
              <label class="sr-only" for="ai-vis-url">URL do site</label>
              <input id="ai-vis-url" name="url" type="url" inputmode="url" autocomplete="url" placeholder="https://seusite.com.br" required maxlength="500" />
              <input type="text" name="empresa_site" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true" />
              <button class="pill solid" type="submit">Ver minha nota</button>
            </form>
            <p class="hint" id="ai-vis-status" role="status" aria-live="polite" hidden></p>
            <p class="ai-vis-price">A nota é gratuita. Nada é instalado no site. Implementar as correções é orçamento à parte — a Quatro Ventos envia proposta fechada depois do diagnóstico.</p>
          </div>
        </section>

        <section class="section on-dark" aria-labelledby="criterios">
          <div class="wrap">
            <h2 id="criterios" class="h2">Três eixos, uma nota de 0 a 100</h2>
            <p class="intro">Lemos a home do mesmo jeito que um robô de IA. Cada eixo agrupa as verificações; para cada ponto fraco, o relatório traz o passo a passo em português.</p>
            <ul class="ai-vis-groups">
              <li>
                <div class="ai-vis-group-head">
                  <strong>Acesso</strong>
                  <span>40 pts</span>
                </div>
                <p>Os robôs das IAs entram e o texto chega legível sem depender de JavaScript.</p>
                <ul>
                  <li>Robôs das IAs liberados · 20 pts</li>
                  <li>Conteúdo legível sem JavaScript · 20 pts</li>
                </ul>
              </li>
              <li>
                <div class="ai-vis-group-head">
                  <strong>Clareza</strong>
                  <span>45 pts</span>
                </div>
                <p>A home diz o que é, para quem é e quanto custa — com dados estruturados, meta e HTTPS rápidos.</p>
                <ul>
                  <li>O que é, para quem e quanto custa · 15 pts</li>
                  <li>Dados estruturados · 10 pts</li>
                  <li>Tempo de resposta e HTTPS · 10 pts</li>
                  <li>Título e descrição · 10 pts</li>
                </ul>
              </li>
              <li>
                <div class="ai-vis-group-head">
                  <strong>Confiança</strong>
                  <span>15 pts</span>
                </div>
                <p>Sinais de quem está por trás e como o site se organiza para assistentes.</p>
                <ul>
                  <li>Arquivo llms.txt · 5 pts</li>
                  <li>Autor, data e página Sobre · 5 pts</li>
                  <li>Sitemap · 5 pts</li>
                </ul>
              </li>
            </ul>
          </div>
        </section>

        <section class="section" aria-labelledby="para-quem">
          <div class="wrap prose-panel">
            <h2 id="para-quem" class="h2">Para quem é</h2>
            <p>Para donos de site, marketing e agências que querem saber se o negócio é citável por assistentes de IA — antes de investir em conteúdo ou em mídia. Feito para empresas brasileiras que já têm um site no ar.</p>
            <h2 class="h2">Como calculamos</h2>
            <p>Nove verificações em três eixos somam 100 pontos. Cada uma vale tudo, metade ou zero. Nota de 0 a 39: baixo; de 40 a 69: médio; de 70 a 100: alto. A análise foca a home e os arquivos da raiz (robots, llms, sitemap).</p>
          </div>
        </section>

        <section class="section on-dark" aria-labelledby="faq">
          <div class="wrap">
            <div class="faq-grid">
              <h2 id="faq" class="h2">Perguntas frequentes</h2>
              <div class="faq">
                <details>
                  <summary>O que essa ferramenta mede?<span class="plus" aria-hidden="true"></span></summary>
                  <div class="rich"><p>Três eixos (acesso, clareza, confiança) com nove verificações: robôs de IA, texto no HTML, oferta e preço, JSON-LD, HTTPS, meta tags, llms.txt, Sobre/autor/data e sitemap.</p></div>
                </details>
                <details>
                  <summary>É gratuito?<span class="plus" aria-hidden="true"></span></summary>
                  <div class="rich"><p>Sim. A nota é gratuita e sem cadastro. Implementar as correções no site é um projeto à parte, com orçamento sob medida.</p></div>
                </details>
                <details>
                  <summary>A análise olha o site inteiro?<span class="plus" aria-hidden="true"></span></summary>
                  <div class="rich"><p>Não. Como a maioria dos robôs, começamos pela página inicial e pelos arquivos da raiz. Páginas internas entram no sitemap e no llms.txt, mas a nota da ferramenta é da home.</p></div>
                </details>
                <details>
                  <summary>Quem faz a ferramenta?<span class="plus" aria-hidden="true"></span></summary>
                  <div class="rich"><p>A Quatro Ventos, de Curitiba: software, IA e sites. Usamos os mesmos critérios no SEO do nosso CMS. Se quiser que a Quatro Ventos aplique as correções no seu site, fale pelo WhatsApp.</p></div>
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
