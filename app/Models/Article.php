<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

	protected $fillable = [
		'title',
		'content',
		'user_id',
		'category_id',
		'type',
		'published_at',
	];

	public function setTitleAttribute($value)
	{
		$this->attributes['title'] = $value;

		$baseSlug = Str::slug($value);
		$slug = $baseSlug;
		$count = 1;

		// vérifie que le slug est unique
		while (static::where('slug', $slug)->exists()) {
			$slug = $baseSlug . '-' . $count++;
		}

		$this->attributes['slug'] = $slug;
	}

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

	// types d'articles
    public function scopePublished(Builder $query)
    {
        return $query->whereNotNull('published_at');
    }

	public function scopeDraft(Builder $query)
	{
		return $query->whereNull('published_at');
	}

	public function scopeBlog($query)
	{
		return $query->where('type', 'blog');
	}

	public function scopeTutorial($query)
	{
		return $query->where('type', 'tutorial');
	}
	

	// récupérer le slug
	public function getRouteKeyName()
	{
		return 'slug';
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
