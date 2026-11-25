@extends('site.blog.layout')

@section('content')
  <section class="hero blog-hero">
    <div class="agent-log" aria-hidden="true">
      <div>
        <span><span class="k">blog</span> · category{!! "\n" !!}</span>
        <span><span class="k">slug</span> {{ $category['slug'] }}{!! "\n" !!}</span>
        <span><span class="k">posts</span> <span class="s">{{ count($posts) }}</span>{!! "\n" !!}</span>
      </div>
    </div>
    <div class="wrap">
      <nav class="blog-path" aria-label="Navegação do blog"><a href="/blog">Blog</a></nav>
      <h1 class="is-inner">{{ $category['label'] }}</h1>
      <div class="hero-foot">
        <p class="lead">{{ $category['lead'] }}</p>
        <div class="actions">
          <a class="pill lime" href="{{ $whatsapp }}" target="_blank" rel="noreferrer">Falar no WhatsApp<span class="arrow" aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 16 16" aria-hidden="true"><path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg></span></a>
          <a class="pill line" href="/blog">Todos os artigos</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section on-light">
    <div class="wrap">
      @include('site.blog.partials.chips')
      @include('site.blog.partials.post-rows', ['posts' => $leadPosts])
    </div>
  </section>

  @include('site.blog.partials.conversion-light', [
    'conversionBody' => 'Tem um processo nessa área travado hoje? A Quatro Ventos monta o piloto com a operação e responde no WhatsApp.',
  ])

  @if(count($morePosts) > 0)
    <section class="section on-light is-flush">
      <div class="wrap">
        @include('site.blog.partials.post-rows', ['posts' => $morePosts])
      </div>
    </section>
  @endif

  @include('site.blog.partials.cta-final')
@endsection
