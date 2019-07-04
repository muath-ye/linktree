<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Profile extends Model implements HasMedia
{
    use HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'username',
        'location',
        'bio',
    ];

    protected $appends = [
        'avatar_url',
    ];

    protected $with = [
      'links'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'username';
    }

    /**
     * @return mixed
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->hasMedia('avatar')) {
            return $this->getFirstMedia('avatar')->getUrl('cropped');
        }

        return asset('/images/avatar.png');
    }

    /**
     * Get the profile's links.
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /**
     *
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    /**
     * @param Media|null $media
     *
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('cropped')
             ->crop(Manipulations::CROP_CENTER, 200, 200)
             ->nonQueued()
             ->performOnCollections('avatar');
    }
}
