<?php

namespace Modules\Vendor\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => url($this->image),
            'title' => $this->translate(locale())->title,
            'description' => $this->translate(locale())->description,
            'opening_status' => new OpeningStatusResource($this->openingStatus),
            'sections' => SectionListResource::collection($this->sections),
        ];
    }
}
