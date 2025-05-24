<?php

namespace Modules\Catalog\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'title' => $this->translate(locale())->title,
            'image' => $this->image ? url($this->image) : null,
            'cover' => $this->cover ? url($this->cover) : null,
            'products_count' => $this->products_count,
        ];
        return $result;
    }
}
