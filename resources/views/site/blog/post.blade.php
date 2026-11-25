@extends('site.blog.layout')

@php
  $ogType = 'article';
@endphp

@section('content')
  <section class="hero blog-hero blog-hero-post">
    <div class="agent-log" aria-hidden="true">
      <div>
        <span><span class="k">blog</span> · post{!! "\n" !!}</span>
        <span><span class="k">cat</span> {{ $post['category'] }}{!! "\n" !!}</span>
        <span><span class="k">date</span> <span class="s">{{ $post['date'] }}</span>{!! "\n" !!}</span>
      </div>
    </div>
    <div class="wrap">
      <nav class="blog-path" aria-label="Navegação do blog">
        <a href="/blog">Blog</a>
        <span aria-hidden="true">/</span>
        <a href="/blog/categoria/{{ $post['category'] }}">{{ $categoryLabel }}</a>
      </nav>
      <h1 class="is-inner">{{ $post['title'] }}</h1>
      <div class="hero-foot">
        <p class="lead">{{ $post['excerpt'] }}</p>
        <div class="blog-post-meta">
          <time datetime="{{ $post['date'] }}">{{ \Illuminate\Support\Carbon::parse($post['date'])->format('d/m/Y') }}</time>
        </div>
        <div class="actions">
          <a class="pill lime" href="{{ $whatsapp }}" target="_blank" rel="noreferrer">Falar no WhatsApp<span class="arrow" aria-hidden="true"><svg class="icon-arrow" viewBox="0 0 16 16" aria-hidden="true"><path d="M4.5 11.5l7-7M5.5 4.5h6v6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg></span></a>
          <a class="pill line" href="{{ $toolPath }}">Testar se o site aparece na IA</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section on-light">
    <div class="wrap">
      <article class="blog-article">
        @foreach($post['body'] as $block)
          @if($block['type'] === 'h2')
            <h2>{{ $block['text'] }}</h2>
          @elseif($block['type'] === 'ul')
            <ul>
              @foreach($block['items'] as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          @else
            <p>{{ $block['text'] }}</p>
          @endif
        @endforeach
      </article>
    </div>
  </section>

  @include('site.blog.partials.conversion-light', [
    'conversionBody' => 'Se este tema descreve um gargalo da sua operação, chame no WhatsApp. A Quatro Ventos responde com o recorte técnico do piloto.',
  ])

  @if(count($related) > 0)
    <section class="section on-light is-flush">
      <div class="wrap">
        <div class="sec-head is-solo">
          <h2 class="h2">Na mesma categoria</h2>
        </div>
        @include('site.blog.partials.post-rows', ['posts' => $related])
      </div>
    </section>
  @endif

  @include('site.blog.partials.cta-final')
@endsection
