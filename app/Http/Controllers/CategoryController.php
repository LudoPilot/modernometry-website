<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $articles = $category->articles()
            ->with(['category', 'tags'])
            ->latest()
            ->get();

        return inertia('Blog/CategoryShow', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }
}
