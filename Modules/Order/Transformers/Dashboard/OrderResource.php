<?php

namespace Modules\Order\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\Resource;

class OrderResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'unread'               => $this->unread,
            'total'                => $this->total,
            'shipping'             => $this->shipping,
            'subtotal'             => $this->subtotal,
            'transaction'          => $this->transactions->method,
            'state'                => optional(optional(optional($this->orderAddress)->state)->translate(locale()))->title,
            'order_status_id'      => $this->orderStatus->translate(locale())->title,
            'deleted_at'           => $this->deleted_at,
            'created_at'           => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}
