<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleAdminController extends Controller
{
	    public function index(Request $request)
    {
        $type = $request->string('type', 'all')->toString();       // all|blog|tutorial
        $status = $request->string('status', 'all')->toString();   // all|published|draft
        $q = $request->string('q', '')->toString();

        $articles = Article::query()
            ->when($type !== 'all', fn ($query) => $query->where('type', $type))
            ->when($status === 'published', fn ($query) => $query->whereNotNull('published_at'))
            ->when($status === 'draft', fn ($query) => $query->whereNull('published_at'))
            ->when($q !== '', fn ($query) => $query->where('title', 'like', "%{$q}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $articles->through(function ($a) {
            return [
                'id' => $a->id,
                'title' => $a->title,
                'slug' => $a->slug,
                'type' => $a->type,
                'published_at' => optional($a->published_at)->toDateTimeString(),
                'created_at' => optional($a->created_at)->format('d/m/Y'),
            ];
        });

        return inertia('Admin/Articles/Index', [
            'articles' => $articles,
            'filters' => [
                'type' => $type,
                'status' => $status,
                'q' => $q,
            ],
        ]);
    }

    public function create()
    {
        return inertia('Admin/Articles/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:blog,tutorial'],
            'content' => ['required', 'string'],
        ]);

        $data['user_id'] = $request->user()->id;

        $article = Article::create($data);

        return redirect()->route('admin.articles.edit', $article->slug);
    }

    public function edit(Article $article)
    {
        return inertia('Admin/Articles/Edit', [
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'type' => $article->type,
                'content' => $article->content,
                'published_at' => optional($article->published_at)->toDateTimeString(),
            ],
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:blog,tutorial'],
            'content' => ['required', 'string'],
        ]);

        $article->update($data);

        return back();
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('admin.articles.index');
    }

	// PAS DE DISTINCTION ENTRE ARTICLES DE BLOG ET TUTORIELS POUR LE MOMENT ICI

    public function publish(Article $article)
    {
        $article->publish();
        return back(303)->with('success', 'Article publié.');
    }

    public function unpublish(Article $article)
    {
        $article->unpublish();
        return back(303)->with('success', 'Article dépublié.');
    }
}
