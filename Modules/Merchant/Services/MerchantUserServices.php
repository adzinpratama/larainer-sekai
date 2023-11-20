<?php

namespace Modules\Merchant\Services;

use App\Services\BaseServices;
use Modules\Merchant\Entities\MerchantUser;

class MerchantUserServices extends BaseServices
{
    protected static $model = MerchantUser::class;
}
