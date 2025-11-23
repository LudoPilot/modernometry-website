<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class BlogController extends Controller
{
	public function index()
	{
		Article::all();

		return inertia('Blog/');
	}

    public function create()
	{
		return inertia('Blog/Create');
	}

	public function store(Request $request) {
		$validated = $request->valdiate([
			'title' => 'required|string|max:255',
			'content' => 'required|string'
		]);

		Article::create([
			'title' => $validated['title'],
			'content' => $validated['content'],
			'user_id' => auth()->id(),
		]);

		return redirect()->route('blog.index')->with('success', 'Article créé avec succès.');
	}

	public function show(Article $article) {
		return inertia('Blog/Show', [
			'article' => $article
		]);
	} 

	public function edit(Article $article) {
		return inertia('Blog/Edit', [
			'article' => $article
		]);
	}

	public function update(Request $request, Article $article) {
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string'
		]);

		$article->update($validated);

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
