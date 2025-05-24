<?php

namespace Modules\Vendor\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Catalog\Transformers\WebService\PaginatedResource;
use Modules\Catalog\Transformers\WebService\ProductResource;
use Modules\Vendor\Traits\VendorTrait;

class VendorResource extends JsonResource
{
    use VendorTrait;

    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'image' => url($this->image),
            'title' => $this->translate(locale())->title,
            'description' => $this->translate(locale())->description,
            'opening_status' => new OpeningStatusResource($this->openingStatus),

            /*'payments' => PaymenteResource::collection($this->payments),
            'fixed_delivery' => $this->fixed_delivery,
            'order_limit' => $this->order_limit,
            'rate' => $this->getVendorTotalRate($this->rates),*/
        ];

        if (request()->get('with_products') == 'yes') {
            $productsCount = request()->get('with_products_count') ?? 10;
            $result['products'] = ProductResource::collection($this->products->take($productsCount));
        }

        if (request()->route()->getName() == 'get_one_vendor') {
            $products = $request->products;
            $request->request->remove('products');
            $result['products'] = PaginatedResource::make($products)->mapInto(ProductResource::class);
        }


        /*if (request()->route()->getName() == 'get_one_vendor')
            $result['areas'] = (count($this->deliveryCharge) > 0) ? DeliveryChargeResource::collection($this->deliveryCharge) : null;
        else
            $result['delivery_charge'] = (count($this->deliveryCharge) > 0) ? new DeliveryChargeResource($this->deliveryCharge[0]) : null;*/

        return $result;
    }
}
