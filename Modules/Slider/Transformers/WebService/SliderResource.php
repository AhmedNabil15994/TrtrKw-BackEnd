<?php

namespace Modules\Slider\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'image' => !is_null($this->image) ? url($this->image) : null,
            'title' => optional($this->translate(locale()))->title,
            'short_description' => optional($this->translate(locale()))->short_description,
        ];

        if ($this->morph_model == 'Category') {
            $result['target'] = 'categories';
            $result['link'] = $this->sliderable_id;
        } elseif ($this->morph_model == 'Product') {
            $result['target'] = 'products';
            $result['link'] = $this->sliderable_id;
        } else {
            $result['target'] = 'external';
            $result['link'] = $this->link;
        }
        return $result;
    }
}
