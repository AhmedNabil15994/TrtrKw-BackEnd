<?php

namespace Modules\Vendor\Repositories\Dashboard;

use Illuminate\Support\Facades\File;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\Product;
use Modules\Core\Traits\CoreTrait;
use Modules\Vendor\Entities\Vendor;
use Hash;
use DB;

class VendorRepository
{
    use CoreTrait;

    protected $vendor;
    protected $product;
    protected $prodCategory;
    protected $imgPath;

    function __construct(Vendor $vendor, Product $product, Category $prodCategory)
    {
        $this->vendor = $vendor;
        $this->product = $product;
        $this->prodCategory = $prodCategory;
        $this->imgPath = public_path('uploads/vendors');
    }

    public function countVendors()
    {
        $vendors = $this->vendor->count();
        return $vendors;
    }

    public function countSubscriptionsVendors()
    {
        $query = $this->vendor->query();

        $query->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        return $query->count();
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $vendors = $this->vendor->orderBy($order, $sort)->get();
        return $vendors;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        return $this->vendor->active()->orderBy($order, $sort)->get();
    }

    public function getAllActiveProdCategories($order = 'id', $sort = 'desc')
    {
        return $this->prodCategory->active()->orderBy($order, $sort)->get();
    }

    public function findById($id)
    {
        $vendor = $this->vendor->withDeleted()->find($id);
        return $vendor;
    }

    public function getActiveVendorsWithLimitProducts($minQty)
    {
        $query = $this->vendor->query();

        $query = $query->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        if (config('setting.other.is_multi_vendors') == 0) {
            $query = $query->where('id', config('setting.default_vendor'));
        }

        $query = $query->active()->with(['products' => function ($q) use ($minQty) {
            $q->with('variants');
            $q->active();
            $q->where(function ($q) use ($minQty) {
                $q->where('qty', '<=', $minQty);
                $q->orWhereHas('variants', function ($q) use ($minQty) {
                    $q->where('qty', '<=', $minQty);
                });
            });
        }]);
        return $query->get();
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {

            $data = [
                'vendor_status_id' => $request->vendor_status_id,
                'supplier_code_myfatorah' => $request->supplier_code_myfatorah,
                'fixed_commission' => $request->fixed_commission,
                'vendor_email' => $request->vendor_email,
                'commission' => $request->commission,
                'order_limit' => $request->order_limit,
                'fixed_delivery' => $request->fixed_delivery,
//                'image' => path_without_domain($request->image),
                'status' => $request->status ? 1 : 0,
                'is_trusted' => $request->is_trusted ? 1 : 0,
            ];

            if (!is_null($request->image)) {
                $imgName = $this->uploadImage($this->imgPath, $request->image);
                $data['image'] = 'uploads/vendors/' . $imgName;
            } else {
                $data['image'] = url(config('setting.logo'));
            }

            $vendor = $this->vendor->create($data);

            if ($request->seller_id) {
                $vendor->sellers()->sync($request->seller_id);
            }
            $vendor->sections()->sync($request->section_id);

            if (isset($request->payment_id)) {
                $vendor->payments()->sync($request->payment_id);
            }

            if ($request->companies) {
                $vendor->companies()->sync($request->companies);
            }

            if ($request->states) {
                $vendor->states()->sync($request->states);
            }

            $this->translateTable($vendor, $request);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        $vendor = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelte($vendor) : null;

        try {

            $data = [
                'vendor_status_id' => $request->vendor_status_id,
                'supplier_code_myfatorah' => $request->supplier_code_myfatorah,
                'vendor_email' => $request->vendor_email,
                'fixed_commission' => $request->fixed_commission,
                'commission' => $request->commission,
                'order_limit' => $request->order_limit,
                'fixed_delivery' => $request->fixed_delivery,
//                'image' => $request->image ? path_without_domain($request->image) : $vendor->image,
                'status' => $request->status ? 1 : 0,
                'is_trusted' => $request->is_trusted ? 1 : 0,
            ];

            if ($request->image) {
                File::delete($vendor->image); ### Delete old image
                $imgName = $this->uploadImage($this->imgPath, $request->image);
                $data['image'] = 'uploads/vendors/' . $imgName;
            } else {
                $data['image'] = $vendor->image;
            }

            $vendor->update($data);

            if ($request->seller_id) {
                $vendor->sellers()->sync($request->seller_id);
            }
            $vendor->sections()->sync($request->section_id);

            if (isset($request->payment_id)) {
                $vendor->payments()->sync($request->payment_id);
            }

            if ($request->companies) {
                $vendor->companies()->sync($request->companies);
            }

            if ($request->states) {
                $vendor->states()->sync($request->states);
            }

            $this->translateTable($vendor, $request);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateInfo($request, $id)
    {
        DB::beginTransaction();

        $vendor = $this->findById($id);

        try {

            $vendor->update([
                'vendor_status_id' => $request->vendor_status_id,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function sorting($request)
    {
        DB::beginTransaction();

        try {

            foreach ($request['vendors'] as $key => $value) {

                $key++;

                $this->vendor->find($value)->update([
                    'sorting' => $key,
                ]);

            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelte($model)
    {
        $model->restore();
    }

    public function translateTable($model, $request)
    {
        foreach ($request['title'] as $locale => $value) {
            $model->translateOrNew($locale)->title = $value;
            $model->translateOrNew($locale)->description = $request['description'][$locale];
            $model->translateOrNew($locale)->seo_description = $request['seo_description'][$locale];
            $model->translateOrNew($locale)->seo_keywords = $request['seo_keywords'][$locale];
        }

        $model->save();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            if ($model)
                File::delete($model->image); ### Delete old image

            if ($model->trashed()):
                $model->forceDelete();
            else:
                $model->delete();
            endif;

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteSelected($request)
    {
        DB::beginTransaction();

        try {

            foreach ($request['ids'] as $id) {
                $model = $this->delete($id);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }


    public function getAllPaginatedProducts($request, $order = 'id', $sort = 'desc', $count = 50)
    {
        $query = $this->product->orderBy($order, $sort)->active();

        if (isset($request->category) && !empty($request->category)) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('product_categories.category_id', $request->category);
            });
        }

        return $query->paginate($count);
    }

    public function assignVendorProducts($vendor, $request)
    {
        DB::beginTransaction();

        try {
            if (isset($request->ids) && !empty($request->ids)) {
                foreach ($request->ids as $k => $id) {
                    $pivotArray = ['price' => $request->price[$id], 'qty' => $request->qty[$id]];
                    if (isset($request->status[$id])) {
                        $pivotArray['status'] = isset($request->status[$id]) || $request->status[$id] == 'on' ? 1 : 0;
                    } else {
                        $pivotArray['status'] = 0;
                    }
                    $products_array[$id] = $pivotArray;
                }
                // sync without delete old items
                $vendor->products()->sync($products_array, false);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function QueryTable($request)
    {
        $query = $this->vendor->with(['translations']);

        $query->where(function ($query) use ($request) {

            $query
                ->where('id', 'like', '%' . $request->input('search.value') . '%')
                ->orWhere(function ($query) use ($request) {

                    $query->whereHas('translations', function ($query) use ($request) {

                        $query->where('description', 'like', '%' . $request->input('search.value') . '%');
                        $query->orWhere('title', 'like', '%' . $request->input('search.value') . '%');
                        $query->orWhere('slug', 'like', '%' . $request->input('search.value') . '%');

                    });

                });

        });

        return $this->filterDataTable($query, $request);
    }

    public function filterDataTable($query, $request)
    {
        // Search Pages by Created Dates
        if (isset($request['req']['from']) && $request['req']['from'] != '')
            $query->whereDate('created_at', '>=', $request['req']['from']);

        if (isset($request['req']['to']) && $request['req']['to'] != '')
            $query->whereDate('created_at', '<=', $request['req']['to']);

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'only')
            $query->onlyDeleted();

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'with')
            $query->withDeleted();

        if (isset($request['req']['status']) && $request['req']['status'] == '1')
            $query->active();

        if (isset($request['req']['status']) && $request['req']['status'] == '0')
            $query->unactive();

        if (isset($request['req']['sections']) && $request['req']['sections'] != '') {

            $query->whereHas('sections', function ($query) use ($request) {
                $query->where('section_id', $request['req']['sections']);
            });

        }

        return $query;
    }
}
