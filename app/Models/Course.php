<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Smariqislam\Coupon\Models\Coupon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Course extends Model
{
    use HasSlug;

    protected $fillable = [
      'title',
      'slug',
      'description',
      'course_category_id',
      'price'
    ];

    protected $appends = ['short_description'];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'coupon_course', 'product_id', 'coupon_id');
    }


    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => substr(data_get($attributes, 'description'), 0, 20).'.....',
//            get: fn ($value, $attributes) => 'wow',
        );
    }
}