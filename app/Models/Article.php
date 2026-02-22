<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

	use SoftDeletes;

	protected $fillable = [
		'title',
		'content',
		'user_id',
		'category_id',
		'type',
		'published_at',
		'cover_path',
	];

	protected static function booted()
	{
		static::creating(function (Article $article) {
			$baseSlug = Str::slug($article->title);
			$slug = $baseSlug;
			$count = 1;

			while (static::where('slug', $slug)->exists()) {
				$slug = $baseSlug . '-' . $count++;
			}

			$article->slug = $slug;
		});
	}

	protected $casts = [
		'published_at' => 'datetime',
	];

	// statut de l'article
	public function isPublished(): bool
	{
		return !is_null($this->published_at);
	}

	public function publish(): void
	{
		$this->update([
			'published_at' => now(),
		]);
	}

	public function unpublish(): void
	{
		$this->update([
			'published_at' => null,
		]);
	}

	// articles publiés
    public function scopePublished(Builder $query)
    {
        return $query->whereNotNull('published_at');
    }

	// brouillons
	public function scopeDraft(Builder $query)
	{
		return $query->whereNull('published_at');
	}

	// blog
	public function scopeBlog($query)
	{
		return $query->where('type', 'blog');
	}

	// tutoriels
	public function scopeTutorial($query)
	{
		return $query->where('type', 'tutorial');
	}
	

	// récupérer le slug
	public function getRouteKeyName()
	{
		return 'slug';
	}

	// récupérer l'image de couverture
	public function coverUrl(): ?string
	{
		return $this->cover_path ? \Storage::url($this->cover_path) : null;
	}

	// RELATIONS
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function tags()
	{
		return $this->belongsToMany(Tag::class);
	}

}
