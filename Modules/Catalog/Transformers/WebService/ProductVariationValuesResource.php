<?php

namespace Modules\Catalog\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariationValuesResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'title' => $this->optionValue->translate(locale())->title,
            'color' => optional($this->optionValue)->color,
        ];
        return $result;
    }
}
