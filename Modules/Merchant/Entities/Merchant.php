<?php

namespace Modules\Merchant\Entities;

use App\Traits\Model\Slug;
use App\Traits\Model\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Merchant extends Model
{
    use Uuid;
    use Slug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'slug',
        'address',
        'avatar',
        'banner',
        'description',
    ];

    protected $appends = ['avatar_url', 'banner_url'];

    /**
     * relation to merchan_users table
     *
     * @return HasMany
     */
    public function merchantUser(): HasMany
    {
        return $this->hasMany(
            MerchantUser::class,
            'merchant_id'
        );
    }

    /**
     * Method getAvatarUrlAttribute
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        return Storage::url($this->avatar);
    }

    /**
     * Method getBannerUrlAttribute
     *
     * @return string
     */
    public function getBannerUrlAttribute(): string
    {
        return Storage::url($this->banner);
    }
}
