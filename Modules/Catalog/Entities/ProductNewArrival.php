<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;

class ProductNewArrival extends Model
{
    use ScopesTrait;

    protected $fillable = ['product_id', 'start_at', 'end_at', 'status'];

    public function scopeStarted($query)
    {
        return $query->whereNull('start_at')->orWhere('start_at', '<=', date('Y-m-d'));
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('end_at')->where('end_at', '<', date('Y-m-d'));
    }

    public function scopeUnexpired($query)
    {
        return $query->whereNull('end_at')->orWhere('end_at', '>', date('Y-m-d'));
    }
}
