<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    public function index()
    {
        $tutorials = Article::tutorial()
            ->published()
            ->latest('published_at')
            ->get();

        return inertia('Tutorials/Index', [
            'tutorials' => $tutorials,
        ]);
    }

    public function create()
    {
        return inertia('Tutorials/Create', [
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

        $tutorial = Article::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'] ?? null,
            'user_id' => auth()->id(),
            'type' => 'tutorial',
        ]);

        $tagIds = [];

        if (!empty($validated['tags'])) {
            foreach ($validated['tags'] as $tagName) {
                $tagName = trim($tagName);
                if ($tagName === '') continue;

                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );

                $tagIds[] = $tag->id;
            }
        }

        $tutorial->tags()->sync($tagIds);

        return redirect()
            ->route('tutorials.index')
            ->with('success', 'Tutoriel créé avec succès.');
    }

    public function show(Article $article)
    {
        if ($article->type !== 'tutorial' || !$article->isPublished()) {
            abort(404);
        }

        $article->load('tags');

        return inertia('Tutorials/Show', [
            'tutorial' => $article,
        ]);
    }

    // public function edit(Article $article)
    // {
    //     if ($article->type !== 'tutorial') {
    //         abort(404);
    //     }

    //     $article->load('tags');

    //     return inertia('Tutorials/Edit', [
    //         'tutorial' => $article,
    //         'categories' => Category::all(),
    //         'tags' => Tag::all(),
    //     ]);
    // }
	
	public function edit(string $slug)
	{
		$tutorial = Article::tutorial()
			->where('slug', $slug)
			->firstOrFail();

		$tutorial->load('tags');

		return inertia('Tutorials/Edit', [
			'tutorial' => $tutorial,
			'categories' => Category::all(),
			'tags' => Tag::all(),
		]);
	}


    public function update(Request $request, Article $article)
    {
        if ($article->type !== 'tutorial') {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'string|max:50',
        ]);

        $article->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'] ?? null,
        ]);

        $tagIds = [];

        if (!empty($validated['tags'])) {
            foreach ($validated['tags'] as $tagName) {
                $tagName = trim($tagName);
                if ($tagName === '') continue;

                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );

                $tagIds[] = $tag->id;
            }
        }

        $article->tags()->sync($tagIds);

        return redirect()
            ->route('tutorials.show', $article->slug)
            ->with('success', 'Tutoriel mis à jour.');
    }

    public function destroy(Article $article)
    {
        if ($article->type !== 'tutorial') {
            abort(404);
        }

        $article->delete();

        return redirect()
            ->route('tutorials.index')
            ->with('success', 'Tutoriel supprimé.');
    }

	public function publish(Article $article)
	{
		if ($article->type !== 'tutorial') {
			abort(404);
		}

		$article->publish();

		return back(303)->with('success', 'Article publié.');
	}

	public function unpublish(Article $article)
	{
		if ($article->type !== 'tutorial') {
			abort(404);
		}

		$article->unpublish();

		return back(303)->with('success', 'Article dépublié.');
	}
}