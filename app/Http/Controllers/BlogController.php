<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use App\Models\Category;
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

	public function store(Request $request) {
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string',
    		'category_id' => 'nullable|exists:categories,id',
    		'tags' => 'array',
    		'tags.*' => 'exists:tags,id',
		]);

		$article = Article::create([
			'title' => $validated['title'],
			'content' => $validated['content'],
			'category_id' => $validated['category_id'] ?? null,
			'user_id' => auth()->id(),
		]);

		$article->tags()->sync($validated['tags'] ?? []);

		return redirect()->route('blog.index')->with('success', 'Article créé avec succès.');
	}

	public function show(Article $article) {
		return inertia('Blog/Show', [
			'article' => $article,
		]);
	} 

	public function edit(Article $article) {
		return inertia('Blog/Edit', [
			'article' => $article,
			'categories' => Category::all(),
			'tags' => Tag::all(),
		]);
	}

	public function update(Request $request, Article $article) {
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string',
			'category_id' => 'nullable|exists:categories,id',
			'tags' => 'array',
			'tags.*' => 'exists:tags,id',
		]);

		unset($validated['slug']);

		$article->update([
			'title' => $validated['title'],
			'content' => $validated['content'],
			'category_id' => $validated['category_id'] ?? null,
		]);

		$article->tags()->sync($validated['tags'] ?? []);

		// retourner à la liste des articles
        return redirect()->route('blog.index')
            ->with('success', 'Article mis à jour.');
	}

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('blog.index')
            ->with('success', 'Article supprimé.');
    }
}
