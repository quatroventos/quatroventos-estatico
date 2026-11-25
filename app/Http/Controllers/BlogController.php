<?php

namespace App\Http\Controllers;

use App\Support\BlogContent;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogContent::posts();

        return view('site.blog.index', $this->shared([
            'seo' => [
                'title' => 'Blog: IA aplicada a processos, automação e desenvolvimento | Quatro Ventos',
                'description' => 'Artigos sobre inteligência artificial aplicada a processos, automação, desenvolvimento de software e sites.',
                'canonical' => url('/blog'),
            ],
            'posts' => $posts,
            'leadPosts' => array_slice($posts, 0, 3),
            'morePosts' => array_slice($posts, 3),
            'activeCategory' => null,
        ]));
    }

    public function category(string $slug): View|Response
    {
        $category = BlogContent::category($slug);
        if ($category === null) {
            abort(404);
        }

        $posts = BlogContent::postsByCategory($slug);

        return view('site.blog.category', $this->shared([
            'seo' => [
                'title' => $category['label'].' | Blog Quatro Ventos',
                'description' => $category['lead'],
                'canonical' => url('/blog/categoria/'.$slug),
            ],
            'category' => $category,
            'posts' => $posts,
            'leadPosts' => array_slice($posts, 0, 3),
            'morePosts' => array_slice($posts, 3),
            'activeCategory' => $slug,
        ]));
    }

    public function show(string $slug): View|Response
    {
        $post = BlogContent::post($slug);
        if ($post === null) {
            abort(404);
        }

        $related = array_values(array_filter(
            BlogContent::postsByCategory($post['category']),
            fn (array $item): bool => $item['slug'] !== $slug
        ));

        return view('site.blog.post', $this->shared([
            'seo' => [
                'title' => $post['title'].' | Quatro Ventos',
                'description' => $post['excerpt'],
                'canonical' => url('/blog/'.$slug),
            ],
            'post' => $post,
            'categoryLabel' => BlogContent::categoryLabel($post['category']),
            'related' => array_slice($related, 0, 3),
            'activeCategory' => $post['category'],
        ]));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function shared(array $data): array
    {
        return array_merge([
            'sitePayload' => BlogContent::chromePayload(),
            'whatsapp' => BlogContent::WHATSAPP,
            'toolPath' => BlogContent::TOOL_PATH,
            'categories' => BlogContent::categories(),
            'updatedAt' => now()->toIso8601String(),
        ], $data, [
            'seo' => array_merge([
                'robots' => 'index, follow',
                'og_site_name' => 'Quatro Ventos',
                'og_locale' => 'pt_BR',
                'twitter_card' => 'summary_large_image',
                'og_image' => null,
            ], $data['seo'] ?? []),
        ]);
    }
}
