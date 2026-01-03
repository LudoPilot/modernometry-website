<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\Article;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HomepageController extends Controller
{
	public function index()
	{
    	return Inertia::render('Welcome', [
			'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
			'latestArticles' => Article::query()->latest()->take(3)->get()
		]);
	}
}
