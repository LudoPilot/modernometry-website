<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleAdminController extends Controller
{
	public function index(Request $request)
	{
		$type = $request->string('type', 'all')->toString();       // all|blog|tutorial
		$status = $request->string('status', 'all')->toString();   // all|published|draft|trashed
		$q = $request->string('q', '')->toString();

		$query = Article::query()
			->withTrashed()
			->when($type !== 'all', fn ($query) => $query->where('type', $type))
			->when($q !== '', fn ($query) => $query->where('title', 'like', "%{$q}%"));

		// Statut 
		if ($status === 'trashed') {
			$query->onlyTrashed();
		} elseif ($status === 'published') {
			$query->whereNull('deleted_at')->whereNotNull('published_at');
		} elseif ($status === 'draft') {
			$query->whereNull('deleted_at')->whereNull('published_at');
		}

		$articles = $query
			->latest()
			->paginate(10)
			->withQueryString();

		$articles->through(function ($a) {
			return [
				'id' => $a->id,
				'title' => $a->title,
				'slug' => $a->slug,
				'type' => $a->type,
				'published_at' => $a->published_at ? (string) $a->published_at : null,
				'deleted_at' => $a->deleted_at ? (string) $a->deleted_at : null,
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
			'cover' => ['nullable', 'image', 'max:4096']
        ]);

        $data['user_id'] = $request->user()->id;

		// cas avec une image de couverture
		if ($request->hasFile('cover')) {
			$data['cover_path'] = $request->file('cover')->store('covers', 'public');
		}

		unset($data['cover']);	// TOOO : voir pour le stockage plus tard


        $article = Article::create($data);

        return redirect()->route('admin.articles.edit', $article->slug);
    }

	public function show(string $slug)
	{
		$article = Article::withTrashed()
			->where('slug', $slug)
			->firstOrFail();

		return inertia('Admin/Articles/Show', [
			'article' => [
				'id' => $article->id,
				'title' => $article->title,
				'slug' => $article->slug,
				'type' => $article->type,
				'content' => $article->content,
				'published_at' => $article->published_at ? (string) $article->published_at : null,
				'deleted_at' => $article->deleted_at ? (string) $article->deleted_at : null,
				'cover_url' => $article->cover_path ? Storage::url($article->cover_path) : null,
				'created_at' => optional($article->created_at)->format('d/m/Y'),
			],
		]);
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
				'cover_path' => $article->cover_path,
				'cover_url' => $article->cover_path ? Storage::url($article->cover_path) : null,
            ],
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:blog,tutorial'],
            'content' => ['required', 'string'],
			'cover' => ['nullable', 'image', 'max:4096']
        ]);

		// si nouvelle image de couverture -> on supprime l'ancienne
		if ($request->hasFile('cover')) {
			if ($article->cover_path) {
				Storage::disk('public')->delete($article->cover_path);
			}
			$data['cover_path'] = $request->file('cover')->store('covers', 'public');
		}

    	unset($data['cover']);

        $article->update($data);

        return back();
    }

    public function destroy(Article $article)
    {
		$article->unpublish();
        $article->delete();

		return back()->with('success', 'Article mis à la corbeille.');
    }

	public function restore(string $slug)
	{
		$article = Article::withTrashed()->where('slug', $slug)->firstOrFail();

		// $article->unpublish();

		$article->restore();

		return back()->with('success', 'Article restauré.');
	}

	public function forceDelete(string $slug)
	{
		$article = Article::withTrashed()->where('slug', $slug)->firstOrFail();

		$article->tags()->detach();
		$article->forceDelete();

		return back()->with('success', 'Article supprimé définitivement.');
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
