<?php

namespace Modules\Catalog\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Advertising\Transformers\WebService\AdvertisingResource;
use Modules\Tags\Transformers\WebService\TagsResource;
use Modules\Vendor\Transformers\WebService\OpeningStatusResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'sku' => $this->sku,
            'price' => $this->price,
            'origin_price' => $this->origin_price,
            'qty' => $this->qty,
            'image' => url($this->image),
            'title' => optional($this->translate(locale()))->title,
            'description' => htmlView(optional($this->translate(locale()))->description),
            'short_description' => optional($this->translate(locale()))->short_description,
            'dimensions' => $this->shipment,
            'vendor' => [
                'id' => $this->vendor_id,
                'title' => optional($this->vendor->translate(locale()))->title,
                'image' => url($this->vendor->image),
                'opening_status' => new OpeningStatusResource($this->vendor->openingStatus),
            ],
            'offer' => new ProductOfferResource($this->offer),
            'images' => ProductImagesResource::collection($this->images),
            'tags' => TagsResource::collection($this->tags),
            'products_options' => ProductOptionResource::collection($this->options),
            'variations_values' => ProductVariantResource::collection($this->variants),

            'adverts' => AdvertisingResource::collection($this->adverts),
            'sharable_link' => route('frontend.products.index', optional($this->translate(locale()))->slug),

            //'categories' => $this->parentCategories->pluck('id'),
            //'sub_categories' => CategoryDetailsResource::collection($this->subCategories),
        ];

        if (auth('api')->check()) {
            $result['is_favorite'] = CheckProductInUserFavourites($this->id, auth('api')->id());
        } else {
            $result['is_favorite'] = null;
        }

        return $result;
    }
}
