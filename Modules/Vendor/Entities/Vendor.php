<?php

namespace Modules\Vendor\Entities;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Area\Entities\State;
use Modules\Company\Entities\Company;
use Modules\Core\Traits\ScopesTrait;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderVendor;

class Vendor extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes, ScopesTrait;

    protected $with = ['translations'];

    public $translationModel = VendorTranslation::class;

    public $translatedAttributes = [
        'description', 'title', 'slug', 'seo_description', 'seo_keywords'
    ];

    protected $fillable = [
        'status', 'sorting', 'order_limit', 'image', 'commission', 'fixed_delivery', 'is_trusted', 'fixed_commission', 'vendor_email', 'receive_question', 'receive_prescription', 'supplier_code_myfatorah', 'vendor_status_id'
    ];

    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'vendor_payments');
    }

    public function openingStatus()
    {
        return $this->belongsTo(VendorStatus::class, 'vendor_status_id');
    }

    public function sellers()
    {
        return $this->belongsToMany(\Modules\User\Entities\User::class, 'vendor_sellers', 'vendor_id', 'seller_id');
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'vendor_sections');
    }

    public function subbscription()
    {
        return $this->hasOne(\Modules\Subscription\Entities\Subscription::class)->latest();
    }

    public function subscriptionHistory()
    {
        return $this->hasMany(\Modules\Subscription\Entities\SubscriptionHistory::class);
    }

    public function products()
    {
        return $this->hasMany(\Modules\Catalog\Entities\Product::class);
    }

    public function subscribed()
    {
        return $this->subbscription()->active()->unexpired()->started();
    }

    public function rates()
    {
        return $this->hasMany(\Modules\Vendor\Entities\Rate::class, 'vendor_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'vendor_companies');
    }

    public function states()
    {
        return $this->belongsToMany(State::class, 'vendor_states');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_vendors');
    }


}
