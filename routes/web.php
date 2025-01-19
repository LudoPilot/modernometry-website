<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Autres routes (modifier pour avoir une structure Inertia)
Route::get('/', [HomepageController::class, 'index'])->name('homepage.index');

Route::get('/tutoriels', [TutorialController::class, 'index'])->name('tutorials.index');
Route::get('/tutorials/{id}', [TutorialController::class, 'show'])->name('tutorials.show');
Route::get('/tutorials/{id}/edit', [TutorialController::class, 'edit'])->name('tutorials.edit'); // rendre cette route privée plus tard
Route::delete('/tutorials/{id}/delete', [TutorialController::class, 'destroy'])->name('tutorials.destroy'); // rendre cette route privée plus tard



// Pages Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/articles', [BlogController::class, 'articles'])->name('blog.articles');
Route::get('blog/articles/{article}', [BlogController::class, 'show'])->name('articles.show');
Route::get('blog/articles/{article}/edit', [BlogController::class, 'edit'])->name('articles.edit');
Route::patch('blog/articles/{article}/edit', [BlogController::class, 'update'])->name('articles.update');
Route::delete('blog/articles/{article}/delete', [BlogController::class, 'destroy'])->name('articles.destroy');


// Page À propos
// Route::get('/about', function() {
// 	return view('about');
// });

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
