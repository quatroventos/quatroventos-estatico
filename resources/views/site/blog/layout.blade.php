@php
  $brand = $sitePayload['name'] ?? 'Quatro Ventos';
  $home = $sitePayload['home_path'] ?? '/';
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
    <meta property="og:type" content="{{ $ogType ?? 'website' }}" />
    <meta property="og:locale" content="{{ $seo['og_locale'] }}" />
    <meta property="og:site_name" content="{{ $seo['og_site_name'] }}" />
    <meta property="og:title" content="{{ $seo['title'] }}" />
    <meta property="og:description" content="{{ $seo['description'] }}" />
    <meta property="og:url" content="{{ $seo['canonical'] }}" />
    <meta name="twitter:card" content="{{ $seo['twitter_card'] }}" />
    {!! app('Illuminate\Foundation\Vite')(['resources/css/site.css', 'resources/js/site/blog.js']) !!}
  </head>
  <body class="blog-page">
    <a class="skip" href="#conteudo">Pular para o conteúdo</a>
    {!! $__env->make('partials.site-chrome-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render() !!}

    <main id="conteudo" tabindex="-1">
      @yield('content')
    </main>

    <footer class="site-footer blog-foot">
      <div class="wrap">
        <div class="footer-bottom">
          <span>© {{ date('Y') }} {{ $brand }}. Curitiba, desde 2005.</span>
          <nav aria-label="Institucional">
            <a href="{{ $home }}">Início</a>
            <a href="/blog">Blog</a>
            <a href="/sobre-a-quatro-ventos">Sobre</a>
            <a href="/contato">Contato</a>
          </nav>
        </div>
      </div>
    </footer>
  </body>
</html>
