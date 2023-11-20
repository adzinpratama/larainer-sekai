<?php

namespace Modules\Merchant\Services;

use App\Services\BaseServices;
use Modules\Merchant\Entities\Merchant;

class MerchantServices extends BaseServices
{
    protected static $model = Merchant::class;

    protected $imageKey = ['avatar', 'banner'];
    protected $imagePath = 'merchants/';
    protected $imageSubPath = 'name';

    protected $imageOptions = [
        'avatar' => [
            'resize' => [
                'width' => 300,
                'height' => 300,
            ]
        ],
        'banner' => [
            'resize' => [
                'width' => 1600,
                'height' => 500
            ]
        ]
    ];
}
