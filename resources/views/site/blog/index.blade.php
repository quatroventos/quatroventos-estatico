@extends('site.blog.layout')

@section('content')
  <section class="hero blog-hero">
    <div class="agent-log" aria-hidden="true">
      <div>
        <span><span class="k">blog</span> · index{!! "\n" !!}</span>
        <span><span class="k">topics</span> ia · automação · sites{!! "\n" !!}</span>
        <span><span class="k">locale</span> <span class="s">pt-BR</span>{!! "\n" !!}</span>
        <span><span class="k">status</span> <span class="p">published</span>{!! "\n" !!}</span>
      </div>
    </div>
    <div class="wrap">
      <h1>Blog</h1>
      <div class="hero-foot">
        <p class="lead">Inteligência artificial aplicada a processos, automação e desenvolvimento, na prática.</p>
        <div class="actions">
          <a class="pill lime" href="{{ $whatsapp }}" target="_blank" rel="noreferrer">Falar no WhatsApp<span class="arrow" aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 16 16" aria-hidden="true"><path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg></span></a>
          <a class="pill line" href="{{ $toolPath }}">Testar se o site aparece na IA</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section on-light">
    <div class="wrap">
      <div class="sec-head">
        <h2 class="h2">Artigos</h2>
        <p class="intro">IA, automação, desenvolvimento, sites e infraestrutura — sem hype, com processo.</p>
      </div>
      @include('site.blog.partials.chips')
      @include('site.blog.partials.post-rows', ['posts' => $leadPosts])
    </div>
  </section>

  @include('site.blog.partials.conversion-light')

  @if(count($morePosts) > 0)
    <section class="section on-light is-flush">
      <div class="wrap">
        <div class="sec-head is-solo">
          <h2 class="h2">Mais leitura</h2>
        </div>
        @include('site.blog.partials.post-rows', ['posts' => $morePosts])
      </div>
    </section>
  @endif

  @include('site.blog.partials.cta-final')
@endsection
