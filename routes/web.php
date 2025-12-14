<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\TagController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TutorialController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware([])->group(function () {	// Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Autres routes (modifier pour avoir une structure Inertia)
//Route::get('/', [HomepageController::class, 'index'])->name('homepage.index');

Route::get('/tutorials', [TutorialController::class, 'index'])->name('tutorials.index');
Route::get('/tutorials/{id}', [TutorialController::class, 'show'])->name('tutorials.show');
Route::get('/tutorials/{id}/edit', [TutorialController::class, 'edit'])->name('tutorials.edit'); // rendre cette route privée plus tard
Route::delete('/tutorials/{id}/delete', [TutorialController::class, 'destroy'])->name('tutorials.destroy'); // rendre cette route privée plus tard


// Routes blog
Route::prefix('blog')->name('blog.')->group(function () {

    // Page principale du blog
    Route::get('/', [BlogController::class, 'index'])->name('index');

    // Catégories
    Route::get('/category/{category:slug}', [CategoryController::class, 'show'])
        ->name('categories.show');

    // Tags (doit aussi être avant les articles)
    Route::get('/tag/{tag:slug}', [TagController::class, 'show'])
        ->name('tags.show');

    // CRUD articles
    Route::middleware('auth')->group(function () {
        Route::get('/articles/create', [BlogController::class, 'create'])->name('articles.create');
        Route::post('/articles', [BlogController::class, 'store'])->name('articles.store');

        Route::get('/{article:slug}/edit', [BlogController::class, 'edit'])->name('articles.edit');
        Route::patch('/{article:slug}', [BlogController::class, 'update'])->name('articles.update');
        Route::delete('/{article:slug}', [BlogController::class, 'destroy'])->name('articles.destroy');
    });

    // Articles
    Route::get('/{article:slug}', [BlogController::class, 'show'])->name('articles.show');
});


// Routes Admin
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
	// Dashboard
	Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
		->name('admin.dashboard');

	// Catégories
    Route::prefix('categories')->name('categories.')->group(function () {

        Route::get('/', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'index'])
            ->name('index');

        Route::get('/create', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'create'])
            ->name('create');

        Route::post('/', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'store'])
            ->name('store');

        Route::get('/{category}/edit', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'edit'])
            ->name('edit');

        Route::patch('/{category}', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'update'])
            ->name('update');

        Route::delete('/{category}', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'destroy'])
            ->name('destroy');
    });
});


// Page À propos
Route::get('/about', function() {
	return view('about');
});

// Politique de confidentialité et mentions légales
Route::get('/mentions-legales', function () {
    return inertia('Legal'); // Page Mentions légales
})->name('legal');

Route::get('/politique-de-confidentialite', function () {
    return inertia('PrivacyPolicy'); // Page Politique de confidentialité
})->name('privacy-policy');

// Page de contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

require __DIR__.'/auth.php';
