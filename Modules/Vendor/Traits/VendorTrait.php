<?php

namespace Modules\Vendor\Traits;

use Illuminate\Support\MessageBag;
use Modules\Vendor\Entities\Rate;

trait VendorTrait
{
    public function getVendorTotalRate($modelRelation)
    {
        $rateCount = $modelRelation->count();
        $rateSum = $modelRelation->sum('rating');
        $totalRate = floatval($rateCount) != 0 ? floatval($rateSum) / floatval($rateCount) : 0;
        return $totalRate;
    }

    public function getVendorRatesCount($modelRelation)
    {
        $rateCount = $modelRelation->count();
        return $rateCount;
    }

    public function checkUserRateOrder($id)
    {
        $rate = Rate::where('user_id', auth()->id())
            ->where('order_id', $id)
            ->first();
        return $rate ? true : false;
    }

    public function getOrderRate($id)
    {
        $rate = Rate::where('order_id', $id)->value('rating');
        return $rate ? $rate : 0;
    }
}
