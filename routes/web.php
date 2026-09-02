<?php

use Illuminate\Support\Facades\Route;
use Shazzoo\ContentStudio\Http\Controllers\ArticleController;

// -------------------------------
// Frontend Routes (met caching)
// -------------------------------

Route::middleware('cache.headers:public;max_age=2628000;etag')->group(function () {
    Route::get('/blog', function () {
        $locale = request('locale', app()->getLocale());

        return redirect()->route('locale.blog.index', ['locale' => $locale]);
    })->name('blog.index');

    Route::get('/blog/{slug}', function (string $slug) {
        $locale = request('locale', app()->getLocale());

        return redirect()->route('locale.blog.show', ['locale' => $locale, 'slug' => $slug]);
    })->name('blog.show');

    Route::get('/{locale}/blog', [ArticleController::class, 'index'])->name('locale.blog.index');
    Route::get('/{locale}/blog/{slug}', [ArticleController::class, 'show'])->name('locale.blog.show');
    // Route::get('/cs/{locale}/articles', [ArticleController::class, 'index'])->name('locale.blog.index');
    // Route::get('/cs/{locale}/articles/{slug}', [ArticleController::class, 'show'])->name('locale.blog.show');
});
