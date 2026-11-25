<div class="blog-filters cases-filter" role="navigation" aria-label="Categorias">
  <a class="blog-filter{{ empty($activeCategory) ? ' is-active' : '' }}" href="/blog" @if(empty($activeCategory)) aria-current="page" @endif>Todos</a>
  @foreach($categories as $category)
    <a
      class="blog-filter{{ ($activeCategory ?? null) === $category['slug'] ? ' is-active' : '' }}"
      href="/blog/categoria/{{ $category['slug'] }}"
      @if(($activeCategory ?? null) === $category['slug']) aria-current="page" @endif
    >{{ $category['label'] }}</a>
  @endforeach
</div>
