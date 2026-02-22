<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
	public function index()
	{

		//$articles = Article::blog()->published()->latest('published_at')->get();

		$articles = Article::query()
			->blog()
			->published()
			->with(['category', 'tags'])
			->latest()
			->get()
			->map(fn ($a) => [
				'id' => $a->id,
				'title' => $a->title,
				'slug' => $a->slug,
				'content' => $a->content,           // on l’améliorera ensuite (excerpt)
				'created_at' => $a->created_at,
				'category' => $a->category,
				'tags' => $a->tags,
				'cover_url' => $a->cover_path ? Storage::url($a->cover_path) : null,
			]);

		return inertia('Blog/Index', [
			'articles' => $articles,
		]);
	}

	public function create()
	{
		return inertia('Blog/Create', [
			'categories' => Category::all(),
			'tags' => Tag::all(),
		]);
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string',
			'category_id' => 'nullable|exists:categories,id',
			'cover' => 'nullable|image|max:4096',

			// tags
			'tags' => 'array',
			'tags.*' => 'string|max:50',
		]);

		$coverPath = null;
		if ($request->hasFile('cover')) {
			$coverPath = $request->file('cover')->store('covers', 'public');
		}

		$article = Article::create([
			'title' => $validated['title'],
			'content' => $validated['content'],
			'category_id' => $validated['category_id'] ?? null,
			'user_id' => auth()->id(),
			'type' => 'blog',
			'cover_path' => $coverPath,
		]);

		// conversion des tags en IDs
		$tagIds = []; // ← important

		if (!empty($validated['tags'])) {
			foreach ($validated['tags'] as $tagName) {

				$tagName = trim($tagName);

				if ($tagName === '') continue;

				$tag = \App\Models\Tag::firstOrCreate(
					['slug' => Str::slug($tagName)],
					['name' => $tagName]
				);

				$tagIds[] = $tag->id;
			}
		}


		// 3) Associe les tags à l'article
		$article->tags()->sync($tagIds);

		return redirect()->route('blog.index')->with('success', 'Article créé avec succès.');
	}

	// Affichage d'un article (visibilité publique)
	public function show(Article $article)
	{
		// TODO: modifier plus tard pour que l'auteur puisse quand même voir les articles
		if ($article->type !== 'blog' || !$article->isPublished()) {
			abort(404);
		}

		// $article->load('tags'); 
		$article->load(['tags', 'category']);

		return inertia('Blog/Show', [
			'article' => [
				'id' => $article->id,
				'title' => $article->title,
				'slug' => $article->slug,
				'content' => $article->content,
				'created_at' => $article->created_at, // garde en ISO, ok pour new Date()
				'published_at' => $article->published_at,
				'category' => $article->category,
				'tags' => $article->tags,
				'cover_url' => $article->cover_path ? Storage::url($article->cover_path) : null,
			],
		]);
	}

	public function edit(string $slug)
	{
		$article = Article::blog()
			->where('slug', $slug)
			->firstOrFail();

		$article->load('tags');

		return inertia('Blog/Edit', [
			'article' => [
				...$article->toArray(),
				'cover_url' => $article->cover_path ? Storage::url($article->cover_path) : null,
			],			
			'categories' => Category::all(),
			'tags' => Tag::all(),
		]);
	}


	public function update(Request $request, Article $article)
	{
		if ($article->type !== 'blog') {
			abort(404);
		}

		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string',
			'category_id' => 'nullable|exists:categories,id',
			'cover' => 'nullable|image|max:4096',

			// liste de noms de tags
			'tags' => 'array',
			'tags.*' => 'string|max:50',
		]);

		if ($request->hasFile('cover')) {
			if ($article->cover_path) {
				Storage::disk('public')->delete($article->cover_path);
			}
			$article->cover_path = $request->file('cover')->store('covers', 'public');
		}

		$article->update([
			'title'       => $validated['title'],
			'content'     => $validated['content'],
			'category_id' => $validated['category_id'] ?? null,
		]);

		$tagIds = [];
		if (!empty($validated['tags'])) {
			foreach ($validated['tags'] as $tagName) {

				$tagName = trim($tagName);

				if ($tagName === '') continue;

				$tag = \App\Models\Tag::firstOrCreate(
					['slug' => Str::slug($tagName)],
					['name' => $tagName]
				);

				$tagIds[] = $tag->id;
			}
		}

		$article->tags()->sync($tagIds);

		return redirect()
			->route('blog.articles.show', $article->slug)
			->with('success', 'Article mis à jour.');
	}

	public function destroy(Article $article)
	{
		if ($article->type !== 'blog') {
			abort(404);
		}
		
		$article->delete();

		return redirect()->route('blog.index')
			->with('success', 'Article supprimé.');
	}

	public function publish(Article $article)
	{
		if ($article->type !== 'blog') {
			abort(404);
		}

		$article->publish();

		return back(303)->with('success', 'Article publié.');
	}

	public function unpublish(Article $article)
	{
		if ($article->type !== 'blog') {
			abort(404);
		}

		$article->unpublish();

		return back(303)->with('success', 'Article dépublié.');
	}
}
