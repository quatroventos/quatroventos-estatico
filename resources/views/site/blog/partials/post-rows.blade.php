@if(count($posts) === 0)
  <p class="blog-empty">Nenhum artigo nesta categoria ainda.</p>
@else
  <ul class="link-rows blog-rows">
    @foreach($posts as $item)
      <li>
        <a href="/blog/{{ $item['slug'] }}">
          <span class="blog-row-main">
            <span class="blog-row-meta">
              <span class="blog-row-cat">{{ \App\Support\BlogContent::categoryLabel($item['category']) }}</span>
              <time datetime="{{ $item['date'] }}">{{ \Illuminate\Support\Carbon::parse($item['date'])->format('d/m/Y') }}</time>
            </span>
            <span class="blog-row-title">{{ $item['title'] }}</span>
            <span class="blog-row-excerpt">{{ $item['excerpt'] }}</span>
          </span>
          <span class="go" aria-hidden="true">→</span>
        </a>
      </li>
    @endforeach
  </ul>
@endif
