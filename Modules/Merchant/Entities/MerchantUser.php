<?php

namespace Modules\Merchant\Entities;

use App\Models\User;
use App\Traits\Model\Slug;
use App\Traits\Model\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Merchant\Enums\RoleAccess;

class MerchantUser extends Model
{
    use Uuid;
    use Slug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'merchant_id',
        'user_id',
        'role_access',
        'status',
    ];

    /**
     * Then attributes that are hidden
     *
     * @var array<int,string>
     */
    protected $hidden = [
        'role_access'
    ];

    /**
     * Then attributes that are appends
     *
     * @var array<int,string>
     */
    protected $appends = [
        'role'
    ];

    /**
     * relation to merchant table
     *
     * @return BelongsTo
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    /**
     * relation to user table
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * set role attribute
     *
     * @return Attribute
     */
    public function getRoleAttribute()
    {
        return RoleAccess::ini($this->role_access)->getAllConstant();
    }
}
