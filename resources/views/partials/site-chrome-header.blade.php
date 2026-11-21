@php
  $headerItems = $sitePayload['menus']['header'] ?? [];
  $headerHubs = array_values(array_filter($headerItems, fn (array $item): bool => count($item['children'] ?? []) > 0));
  $headerSeen = array_flip(array_column($headerItems, 'href'));
  $headerLoose = array_values(array_filter($headerItems, fn (array $item): bool => count($item['children'] ?? []) === 0));
  $footerLoose = [];
  foreach ($sitePayload['menus']['footer'] ?? [] as $column) {
      $nodes = count($column['children'] ?? []) > 0 ? $column['children'] : [$column];
      foreach ($nodes as $node) {
          $href = $node['href'] ?? '';
          if ($href === '' || isset($headerSeen[$href])) {
              continue;
          }
          $inHeaderChildren = false;
          foreach ($headerHubs as $hub) {
              foreach ($hub['children'] ?? [] as $child) {
                  if (($child['href'] ?? '') === $href) {
                      $inHeaderChildren = true;
                      break 2;
                  }
              }
          }
          if (! $inHeaderChildren) {
              $footerLoose[] = $node;
              $headerSeen[$href] = true;
          }
      }
  }
  $headerExtras = [];
  foreach ([...$headerLoose, ...$footerLoose] as $extra) {
      $href = $extra['href'] ?? '';
      if ($href === '' || isset($headerExtras[$href])) {
          continue;
      }
      $headerExtras[$href] = $extra;
  }
  $headerExtras = array_values($headerExtras);
  $headerMobile = [...$headerItems];
  foreach ($headerExtras as $extra) {
      if (! collect($headerItems)->contains(fn (array $item): bool => ($item['href'] ?? '') === ($extra['href'] ?? ''))) {
          $headerMobile[] = $extra;
      }
  }
  $headerWhatsapp = $sitePayload['whatsapp_url'] ?? null;
  $headerBrand = $sitePayload['name'] ?? 'Quatro Ventos';
  $headerLogo = $sitePayload['logo'] ?? asset('assets/logo-quatroventos.svg');
  $headerHome = $sitePayload['home_path'] ?? '/';
  $headerPath = '/'.trim(request()->path(), '/');
  $headerPath = $headerPath === '/' ? '/' : $headerPath;
@endphp
<header class="site-header">
  <div class="wrap header-bar">
    <a class="brand" href="{{ $headerHome }}" aria-label="{{ $headerBrand }}, início">
      <img src="{{ $headerLogo }}" alt="" width="436" height="69" />
    </a>
    <nav class="main-nav" aria-label="Menu principal">
      <ul>
        @foreach($headerItems as $item)
          @php
            $hasMega = count($item['children'] ?? []) > 0;
            $itemHref = $item['href'] ?? '#';
            $isCurrent = $itemHref === '/'
              ? $headerPath === '/'
              : ($headerPath === $itemHref || str_starts_with($headerPath, rtrim($itemHref, '/').'/'));
          @endphp
          <li>
            <a href="{{ $itemHref }}" @if($isCurrent) aria-current="page" @endif>
              <span>{{ $item['label'] }}</span>
              @if($hasMega)
                <svg class="caret" viewBox="0 0 10 10" aria-hidden="true"><path d="M1 3l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" /></svg>
              @endif
            </a>
          </li>
        @endforeach
      </ul>
    </nav>
    @if($headerWhatsapp)
      <a class="pill solid small header-cta" href="{{ $headerWhatsapp }}" target="_blank" rel="noreferrer">Iniciar um projeto</a>
    @endif
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="menu-mobile" aria-label="Abrir menu">
      <span></span>
    </button>
  </div>
  @if(count($headerHubs))
    <div class="mega" id="mega-menu" aria-hidden="true">
      <div class="wrap mega-grid">
        @foreach($headerHubs as $hub)
          <div class="mega-col">
            <a class="mega-hub" href="{{ $hub['href'] }}">
              {{ $hub['label'] }}
              <svg class="icon-arrow" viewBox="0 0 16 16" aria-hidden="true"><path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </a>
            <ul>
              @foreach($hub['children'] ?? [] as $child)
                <li><a href="{{ $child['href'] }}">{{ $child['label'] }}</a></li>
              @endforeach
            </ul>
          </div>
        @endforeach
        <div class="mega-side">
          <ul>
            @foreach($headerExtras as $extra)
              <li><a href="{{ $extra['href'] }}">{{ $extra['label'] }}</a></li>
            @endforeach
          </ul>
          @if($headerWhatsapp)
            <div class="actions">
              <a class="pill lime" href="{{ $headerWhatsapp }}" target="_blank" rel="noreferrer">
                Iniciar um projeto
                <span class="arrow" aria-hidden="true">
                  <svg class="icon-arrow" viewBox="0 0 16 16" aria-hidden="true"><path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </span>
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>
  @endif
</header>
<div class="mobile-nav" id="menu-mobile" aria-hidden="true">
  <ul>
    @foreach($headerMobile as $item)
      @php
        $subId = 'sub-'.preg_replace('/[^a-z0-9]+/i', '-', $item['href'] ?? 'item');
        $hasChildren = count($item['children'] ?? []) > 0;
      @endphp
      <li>
        <div class="row-link">
          <a href="{{ $item['href'] }}" tabindex="-1">{{ $item['label'] }}</a>
          @if($hasChildren)
            <button type="button" aria-expanded="false" aria-controls="{{ $subId }}" aria-label="Serviços de {{ $item['label'] }}" tabindex="-1">
              <svg viewBox="0 0 10 10" aria-hidden="true"><path d="M1 3l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" /></svg>
            </button>
          @endif
        </div>
        @if($hasChildren)
          <ul class="sub" id="{{ $subId }}" hidden>
            @foreach($item['children'] as $child)
              <li><a href="{{ $child['href'] }}" tabindex="-1">{{ $child['label'] }}</a></li>
            @endforeach
          </ul>
        @endif
      </li>
    @endforeach
  </ul>
  @if($headerWhatsapp)
    <a class="pill solid" href="{{ $headerWhatsapp }}" target="_blank" rel="noreferrer" tabindex="-1">Iniciar um projeto</a>
  @endif
</div>
