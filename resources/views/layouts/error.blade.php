<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Ramos Benedetti Advogados' }}</title>
    <meta name="robots" content="noindex, nofollow" />
    @include('partials.favicon')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/footer-compliance.css') }}" />
    <style>
      .error-panel { min-height: 50vh; display: grid; place-items: center; padding: 64px 16px; text-align: center; }
      .error-panel h1 { margin: 0 0 12px; font-size: 28px; }
      .error-panel p { margin: 0 0 24px; color: #5f5f5f; }
      .error-panel a { color: #f45a20; font-weight: 600; text-decoration: none; }
    </style>
  </head>
  <body>
    @include('partials.header')
    <main class="error-panel">
      <div>
        <h1>{{ $heading }}</h1>
        <p>{{ $message }}</p>
        <a href="{{ url('/') }}">Voltar para o início</a>
      </div>
    </main>
    @include('partials.footer')
    <script src="{{ asset('js/site.js') }}"></script>
  </body>
</html>
