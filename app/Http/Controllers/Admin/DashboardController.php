<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return inertia('Admin/Dashboard', [
            'stats' => [
                'articles'   => Article::count(),
                'categories' => Category::count(),
                'tags'       => Tag::count(),
                'users'      => User::count(),
            ],
            'latestArticles' => Article::latest()->take(5)->get(['id', 'title', 'slug', 'created_at']),
        ]);
    }
}
