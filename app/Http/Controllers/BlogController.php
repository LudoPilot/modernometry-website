<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BlogController extends Controller
{
	public function index()
	{
		$articles = Article::all();
		//$articles = Article::latest()->get();

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

			// tags
			'tags' => 'array',
			'tags.*' => 'string|max:50',
		]);

		$article = Article::create([
			'title' => $validated['title'],
			'content' => $validated['content'],
			'category_id' => $validated['category_id'] ?? null,
			'user_id' => auth()->id(),
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

	public function show(Article $article)
	{
		$article->load('tags'); 
		
		return inertia('Blog/Show', [
			'article' => $article,
		]);
	}

	public function edit(Article $article)
	{
		$article->load('tags'); 

		return inertia('Blog/Edit', [
			'article' => $article,
			'categories' => Category::all(),
			'tags' => Tag::all(),
		]);
	}

	public function update(Request $request, Article $article)
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string',
			'category_id' => 'nullable|exists:categories,id',

			// liste de noms de tags
			'tags' => 'array',
			'tags.*' => 'string|max:50',
		]);

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
		$article->delete();

		return redirect()->route('blog.index')
			->with('success', 'Article supprimé.');
	}
}
