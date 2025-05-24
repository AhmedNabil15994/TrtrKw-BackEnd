<?php

namespace Modules\Vendor\Repositories\WebService;

use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Entities\Section;
use Modules\Vendor\Entities\DeliveryCharge;

class VendorRepository
{
    function __construct(Vendor $vendor, Section $section/*, DeliveryCharge $charge*/)
    {
        $this->section = $section;
        $this->vendor = $vendor;
//        $this->charge = $charge;
    }

    public function getAllSections()
    {
        $sections = $this->section->with([
            'vendors' => function ($query) {
                $query->active()->with([
                    'deliveryCharge' => function ($query) {
                        $query->where('state_id', '');
                    }
                ]);

                $query->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
                    return $q->whereHas('subbscription', function ($query) {
                        $query->active()->unexpired()->started();
                    });
                });

                $query->inRandomOrder();
            },
        ]);

        $sections = $sections->whereHas('vendors', function ($query) {
            $query->active();
            $query->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
                return $q->whereHas('subbscription', function ($query) {
                    $query->active()->unexpired()->started();
                });
            });
        })->active()->inRandomOrder()->take(10)->get();
        return $sections;
    }

    public function getAllVendors($request)
    {
        $vendors = $this->vendor->active();

        $vendors = $vendors->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        if ($request->with_products == 'yes') {
            // Get Vendor Products
            $vendors = $vendors->with([
                'products' => function ($query) use ($request) {
                    $query->active();
                    $query = $this->returnProductRelations($query, $request);
                    $query->orderBy('products.id', 'DESC');
                },
            ]);
        }

        if ($request['section_id']) {
            $vendors->whereHas('sections', function ($query) use ($request) {
                $query->where('section_id', $request['section_id']);
            });
        }

        if ($request['state_id']) {
            $vendors->with([
                'deliveryCharge' => function ($query) use ($request) {
                    $query->where('state_id', $request->state_id);
                }
            ]);
            $vendors->whereHas('deliveryCharge', function ($query) use ($request) {
                $query->where('state_id', $request->state_id);
            });
        }

        if ($request['search']) {
            $vendors->whereHas('translations', function ($query) use ($request) {

                $query->where('description', 'like', '%' . $request['search'] . '%');
                $query->orWhere('title', 'like', '%' . $request['search'] . '%');
                $query->orWhere('slug', 'like', '%' . $request['search'] . '%');

            });
        }

        return $vendors->orderBy('id', 'ASC')->get();
    }

    public function getVendorsList($request, $order = 'id', $sort = 'desc')
    {
        $vendors = $this->vendor->active();
        $vendors = $vendors->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });
        $vendors = $vendors->when(!is_null($request->vendors_count), function ($q) use ($request) {
            $q->take($request->vendors_count);
        });
        return $vendors->orderBy($order, $sort)->get();
    }

    public function getOneVendor($request)
    {
        $vendor = $this->vendor->active();
        $vendor = $vendor->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });
        return $vendor->find($request->id);
    }

    /*public function getDeliveryChargesByVendorByState($request)
    {
        $charge = $this->charge
            ->where('vendor_id', $request['vendor_id'])
            ->where('state_id', $request['state_id'])
            ->first();

        return $charge;
    }*/

    public function findById($id)
    {
        $vendor = $this->vendor->with(['companies' => function ($q) {
            $q->with('deliveryCharge', 'availabilities');
        }]);

        $vendor = $vendor->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        return $vendor->find($id);
    }

    public function findVendorByIdAndStateId($id, $stateId)
    {
        $vendor = $this->vendor
            ->with(['companies' => function ($q) use ($stateId) {
                $q->active();
                $q->whereHas('deliveryCharge', function ($query) use ($stateId) {
                    $query->where('state_id', $stateId);
                });
                $q->has('availabilities');
            }]);

        $vendor = $vendor->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        $vendor = $vendor->whereHas('states', function ($query) use ($stateId) {
            $query->where('state_id', $stateId);
        });

        return $vendor->find($id);
    }

    public function returnProductRelations($model, $request = null)
    {
        return $model->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'options',
            'images',
            'vendor',
            'subCategories',
            'addOns',
            'variants' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->active()->unexpired()->started();
                }]);
            },
        ]);
    }

}
