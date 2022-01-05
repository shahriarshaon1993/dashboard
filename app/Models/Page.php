<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Page extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = "pages";

    protected $guarded = ['id'];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * Route model binding using slug for query.
     *
     * @param  mixed $value
     * @param  mixed $field
     * @return void
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)->firstOrFail();
    }

    /**
     * Upload pages photo with thum and page thumb size
     * thumb: width: 160, height: 120.
     * thumb: width: 600
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('featured_image')->singleFile()
            ->registerMediaConversions(function (Media $media = null) {
                $this->addMediaConversion('thumb')
              ->width(160)
              ->height(120);

            $this->addMediaConversion('page-thumb')
                ->width(600);
        });

        $this
            ->addMediaCollection('page-image')
            ->registerMediaConversions(function (Media $media = null) {
                $this->addMediaConversion('thumb')
              ->width(160)
              ->height(120);

            $this->addMediaConversion('page-thumb')
                ->width(600);
        });
    }

    /**
     * Find page by slug name
     *
     * @param  mixed $slug
     * @return void
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->where('status', true)->firstOrFail();
    }
}
