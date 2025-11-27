<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

	protected $fillable = [
		'title',
		'content',
		'user_id',
		'category_id',
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
