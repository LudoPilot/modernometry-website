<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\TagController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomepageController;
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

Route::get('/', [HomepageController::class, 'index'])
    ->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware([])->group(function () {	// Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Routes pour Tutoriels
Route::prefix('tutorials')->name('tutorials.')->group(function () {

    // liste
    Route::get('/', [TutorialController::class, 'index'])
        ->name('index');

    // CRUD tutoriels --> mettre des protections plus tard
    Route::middleware('auth')->group(function () {
        Route::get('/create', [TutorialController::class, 'create'])
            ->name('create');

        Route::post('/', [TutorialController::class, 'store'])
            ->name('store');

        Route::get('/{article:slug}/edit', [TutorialController::class, 'edit'])
            ->name('edit');

        Route::patch('/{article:slug}', [TutorialController::class, 'update'])
            ->name('update');

        Route::delete('/{article:slug}', [TutorialController::class, 'destroy'])
            ->name('destroy');

		// Publier/dépublier un article | TODO : protéger ces routes plus tard + faire version CRUD Admin
		Route::patch('/{article:slug}/publish', [BlogController::class, 'publish'])
			->name('articles.publish');
		Route::patch('/{article:slug}/unpublish', [BlogController::class, 'unpublish'])
			->name('articles.unpublish');
    });

    // affichage
    Route::get('/{article:slug}', [TutorialController::class, 'show'])
        ->name('show');
});


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

		// Publier/dépublier un article | TODO : protéger ces routes plus tard + faire version CRUD Admin
		Route::patch('/{article:slug}/publish', [BlogController::class, 'publish'])->name('articles.publish');
		Route::patch('/{article:slug}/unpublish', [BlogController::class, 'unpublish'])->name('articles.unpublish');
    });

    // Articles
    Route::get('/{article:slug}', [BlogController::class, 'show'])->name('articles.show');
});


// Routes Admin
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
	// Dashboard
	Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
		->name('dashboard');

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

	// Articles
	Route::prefix('articles')->name('articles.')->group(function () {
		Route::get('/', [\App\Http\Controllers\Admin\ArticleAdminController::class, 'index'])
			->name('index');

		Route::get('/create', [\App\Http\Controllers\Admin\ArticleAdminController::class, 'create'])
			->name('create');

		Route::post('/', [\App\Http\Controllers\Admin\ArticleAdminController::class, 'store'])
			->name('store');

		Route::get('/{article:slug}', [\App\Http\Controllers\Admin\ArticleAdminController::class, 'show'])
		->name('show');

		Route::get('/{article:slug}/edit', [\App\Http\Controllers\Admin\ArticleAdminController::class, 'edit'])
			->name('edit');

		Route::patch('/{article:slug}', [\App\Http\Controllers\Admin\ArticleAdminController::class, 'update'])
			->name('update');

		Route::delete('/{article:slug}', [\App\Http\Controllers\Admin\ArticleAdminController::class, 'destroy'])
			->name('destroy');

		Route::patch('/{article:slug}/publish', [\App\Http\Controllers\Admin\ArticleAdminController::class, 'publish'])
			->name('publish');

		Route::patch('/{article:slug}/unpublish', [\App\Http\Controllers\Admin\ArticleAdminController::class, 'unpublish'])
			->name('unpublish');

		Route::patch('/{article:slug}/restore', [\App\Http\Controllers\Admin\ArticleAdminController::class, 'restore'])
			->name('restore');

		Route::delete('/{article:slug}/force-delete', [\App\Http\Controllers\Admin\ArticleAdminController::class, 'forceDelete'])
			->name('force-delete');			
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
