<?php

namespace Modules\Catalog\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantValueResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'option_value' => optional(optional($this->optionValue)->translate(locale()))->title,
            'color' => optional($this->optionValue)->color,
            'option_value_id' => $this->option_value_id,
        ];
    }
}
