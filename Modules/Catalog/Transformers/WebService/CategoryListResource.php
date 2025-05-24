<?php

namespace Modules\Catalog\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Advertising\Transformers\WebService\AdvertisingResource;

class CategoryListResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'title' => $this->translate(locale())->title,
            'image' => $this->image ? url($this->image) : null,
            'cover' => $this->cover ? url($this->cover) : null,
            'color' => $this->color ?? null,
        ];

        if ($request->route()->getName() != 'api.get_sub_categories')
            $result['products'] = ProductResource::collection($this->products);

        return $result;
    }
}
