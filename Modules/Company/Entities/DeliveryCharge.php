<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;

class DeliveryCharge extends Model
{
    protected $fillable = ['delivery', 'delivery_time', 'company_id', 'state_id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}
