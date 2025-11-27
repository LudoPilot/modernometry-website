<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(Tag $tag)
    {
        $articles = $tag->articles()
            ->with(['category', 'tags'])
            ->latest()
            ->get();

        return inertia('Blog/TagShow', [
            'tag' => $tag,
            'articles' => $articles,
        ]);
    }
}
