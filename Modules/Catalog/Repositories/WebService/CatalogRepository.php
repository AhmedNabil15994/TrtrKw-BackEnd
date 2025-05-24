<?php

namespace Modules\Catalog\Repositories\WebService;

use Illuminate\Support\Facades\DB;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\VendorProduct;
use Modules\Catalog\Entities\Product;
use Modules\Variation\Entities\Option;
use Modules\Variation\Entities\ProductVariant;
use Modules\Vendor\Entities\Vendor;

class CatalogRepository
{
    protected $category;
    protected $product;
    protected $vendor;
    protected $prd;
    protected $prdVariant;
    protected $option;
    protected $defaultVendor;

    function __construct(
        VendorProduct  $product,
        Product        $prd,
        Category       $category,
        Vendor         $vendor,
        ProductVariant $prdVariant,
        Option         $option
    ) {
        $this->category = $category;
        $this->product = $product;
        $this->vendor = $vendor;
        $this->prd = $prd;
        $this->prdVariant = $prdVariant;
        $this->option = $option;

        $this->defaultVendor = app('vendorObject') ?? null;
    }

    public function getLatestNCategories($request)
    {
        $categories = $this->buildCategoriesTree($request);
        $count = $request->categories_count ?? 8;
        return $categories->where('show_in_home', 1)->orderBy('sort', 'asc')->take($count)->get();
    }

    public function getAllCategories($request)
    {
        $categories = $this->buildCategoriesTree($request);
        $categories = $categories->orderBy('sort', 'asc');
        if (!empty($request->categories_count))
            $categories = $categories->take($request->categories_count);
        return $categories->get();
    }

    public function getAllMainCategories($request)
    {
        return $this->category->active()->mainCategories()->orderBy('sort', 'asc')->get();
    }

    public function getFilterOptions($request)
    {
        return $this->option->active()
            ->with(['values' => function ($query) {
                $query->active();
            }])
            ->activeInFilter()
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getAutoCompleteProducts($request)
    {
        $products = $this->prd->active();
        if ($request['search']) {
            $products = $this->productSearch($products, $request);
        }
        return $products->orderBy('id', 'DESC')->get();
    }

    public function getProductsByCategory($request)
    {
        $optionsValues = isset($request->options_values) && !empty($request->options_values) ? array_values($request->options_values) : [];
        $products = $this->prd->active()
            ->with([
                'offer' => function ($query) {
                    $query->active()->unexpired()->started();
                },
                'newArrival' => function ($query) {
                    $query->active()->unexpired()->started();
                },
            ])
            ->with(['variants' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->active()->unexpired()->started();
                }]);
            }]);

        $vendorId = $request->vendor_id ?? ($this->defaultVendor ? $this->defaultVendor->id : null);
        if (!is_null($vendorId)) {
            $products = $products->where('vendor_id', $vendorId);
        }

        if (count($optionsValues) > 0) {
            $products = $products->whereHas('variantValues', function ($query) use ($optionsValues) {
                $query->whereIn('option_value_id', $optionsValues);
            });
        }

        $products = $products->when($request->category_id == 'most_popular', function ($q) use ($request) {
            $q->where('most_popular', true);
        });

        $products = $products->when($request->category_id == 'new_arrival', function ($q) use ($request) {
            $q->whereHas('newArrival', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        $products = $products->when($request->category_id == 'exclusive', function ($q) use ($request) {
            $q->where('exclusive', true);
        });

        $products = $products->when(!is_null($request->category_id) && !in_array($request->category_id, ['most_popular', 'new_arrival']), function ($q) use ($request) {
            $q->whereHas('categories', function ($query) use ($request) {
                $query->where('product_categories.category_id', $request->category_id);
            });
        });

        if ($request['low_price'] && $request['high_price']) {
            $products->whereBetween('price', [$request['low_price'], $request['high_price']]);
        }

        if ($request['search']) {
            $products = $this->productSearch($products, $request);
        }

        if ($request['sort']) {
            $products->when($request['sort'] == 'a_to_z', function ($query) {
                $query->orderByTranslation('title', 'asc');
            });
            $products->when($request['sort'] == 'z_to_a', function ($query) {
                $query->orderByTranslation('title', 'desc');
            });
            $products->when($request['sort'] == 'low_to_high', function ($query) {
                $query->orderBy('price', 'asc');
            });
            $products->when($request['sort'] == 'high_to_low', function ($query) {
                $query->orderBy('price', 'desc');
            });
        } else {
            $products->orderBy('id', 'DESC');
        }

        return $products->paginate(24);
    }

    public function getProductDetails($request, $id)
    {
        $product = $this->prd->active();

        if (!is_null($this->defaultVendor)) {
            $product = $product->where('vendor_id', $this->defaultVendor->id);
        }

        $product = $this->returnProductRelations($product, $request);
        return $product->find($id);
    }

    public function getLatestData($request)
    {
        $product = $this->prd->doesnthave('offer')->active();

        if (!is_null($this->defaultVendor)) {
            $product = $product->where('vendor_id', $this->defaultVendor->id);
        }

        $product = $this->returnProductRelations($product, $request);

        if ($request['search']) {
            $product = $this->productSearch($product, $request);
        }

        return $product->orderBy('id', 'desc')->take(10)->get();
    }

    public function getOffersData($request)
    {
        $product = $this->prd->active();

        if (!is_null($this->defaultVendor)) {
            $product = $product->where('vendor_id', $this->defaultVendor->id);
        }

        $product = $this->returnProductRelations($product, $request);

        if ($request['search']) {
            $product = $this->productSearch($product, $request);
        }

        $product = $product->whereHas('offer', function ($query) {
            $query->active()->unexpired()->started();
        });

        return $product->take(10)->get();
    }

    public function findOneProduct($id)
    {
        $product = $this->prd->active();

        if (!is_null($this->defaultVendor)) {
            $product = $product->where('vendor_id', $this->defaultVendor->id);
        }

        $product = $this->returnProductRelations($product, null);

        return $product->find($id);
    }

    public function findOneProductVariant($id)
    {
        $product = $this->prdVariant->active()->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'productValues', 'product',
        ]);

        if (!is_null($this->defaultVendor)) {
            $product = $this->prdVariant->whereHas('product', function ($query) {
                $query->where('vendor_id', $this->defaultVendor->id);
            });
        }

        return $product->find($id);
    }

    public function getAllSubCategoriesByParent($id)
    {
        return $this->category->where('category_id', $id)->get();
    }

    public function buildCategoriesTree($request)
    {
        $categories = $this->category->active()
            ->withCount(['products' => function ($q) {
                $q->active();
                if (!is_null($this->defaultVendor)) {
                    $q->where('vendor_id', $this->defaultVendor->id);
                }
            }]);

        $categories = $categories->has('products');

        $categories = $categories->with(['adverts' => function ($query) use ($request) {
            $query->active()->unexpired()->started()->orderBy('sort', 'asc');
        }]);

        if ($request->with_sub_categories == 'yes')
            $categories = $categories->with('childrenRecursive');

        if ($request->get_main_categories == 'yes')
            $categories = $categories->mainCategories();

        if ($request->with_products == 'yes') {
            // Get Main Category Products
            $categories = $categories->with([
                'products' => function ($query) use ($request) {
                    $query->active();
                    $query = $this->returnProductRelations($query, $request);

                    if (!is_null($this->defaultVendor)) {
                        $query->where('vendor_id', $this->defaultVendor->id);
                    }

                    if ($request['search']) {
                        $query = $this->productSearch($query, $request);
                    }

                    $query->orderBy('products.sort', 'asc');
                },
            ]);
        }

        return $categories;
    }

    public function getMultiLevelCategoriesWithProducts($request)
    {
        $vendorId = $request->vendor_id ?? ($this->defaultVendor ? $this->defaultVendor->id : null);
        $categories = $this->category->active()
            ->withCount(['products' => function ($q) use ($vendorId) {
                $q->active();
                if (!is_null($vendorId)) {
                    $q->where('vendor_id', $vendorId);
                }
            }]);

        $categories = $categories->with(['adverts' => function ($query) use ($request) {
            $query->active()->unexpired()->started()->orderBy('sort', 'asc');
        }]);
        $categories = $categories->with('childrenRecursive');
        $categories = $categories->has('products');

        // Get Main Category Products
        $categories = $categories->with([
            'products' => function ($query) use ($request, $vendorId) {
                $query->active();
                $query = $this->returnProductRelations($query, $request);

                if (!is_null($vendorId)) {
                    $query->where('vendor_id', $vendorId);
                }

                if ($request['search']) {
                    $query = $this->productSearch($query, $request);
                }

                $query->orderBy('products.sort', 'asc');
            },
        ]);

        return $categories->get();
    }

    public function productSearch($model, $request)
    {
        return $model->whereHas('translations', function ($query) use ($request) {

            $query->where('title', 'like', '%' . $request['search'] . '%');
            $query->orWhere('description', 'like', '%' . $request['search'] . '%');
            $query->orWhere('slug', 'like', '%' . $request['search'] . '%');
        })->orWhereHas('searchKeywords', function ($query) use ($request) {
            $query->where('title', 'like', '%' . $request['search'] . '%');
        });
    }

    public function returnProductRelations($model, $request)
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
            'newArrival' => function ($query) {
                $query->active()->unexpired()->started();
            },
        ]);
    }

    public function relatedProducts($selectedProduct)
    {
        $relatedCategoriesIds = $selectedProduct->categories()->pluck('product_categories.category_id')->toArray();
        $products = $this->prd->where('id', '<>', $selectedProduct->id)->active();
        $products = $products->whereHas('categories', function ($query) use ($relatedCategoriesIds) {
            $query->whereIn('product_categories.category_id', $relatedCategoriesIds);
        });
        return $products->orderBy('id', 'desc')->take(10)->get();
    }

    public function getProductsByVendor($id)
    {
        $products = $this->prd->active()->where('vendor_id', $id);
        $products = $this->returnProductRelations($products, null);
        return $products->orderBy('id', 'DESC')->paginate(24);
    }

    public function getAllNewArrival($request)
    {
        $products = $this->prd->active()->whereHas('newArrival', function ($query) {
            $query->active()->unexpired()->started();
        });
        $products = $this->returnProductRelations($products, null);
        $products = $products->when(!is_null($request->sections_count), function ($q) use ($request) {
            $q->take($request->sections_count);
        });
        return $products->orderBy('id', 'DESC')->get();
    }

    public function getAllPopular($request)
    {
        $products = $this->prd->active()->where('most_popular', true);
        $products = $this->returnProductRelations($products, null);
        $products = $products->when(!is_null($request->sections_count), function ($q) use ($request) {
            $q->take($request->sections_count);
        });
        return $products->orderBy('id', 'DESC')->get();
    }

    public function getCategoriesOffersData($request)
    {
        $categories = $this->category->active();
        $categories = $categories->with([
            'products' => function ($query) use ($request) {
                $query->whereHas('offer', function ($query) {
                    $query->active()->unexpired()->started();
                });
                $query->active();
                $query = $this->returnProductRelations($query, $request);

                $vendorId = $request->vendor_id ?? ($this->defaultVendor ? $this->defaultVendor->id : null);
                if (!is_null($vendorId)) {
                    $query = $query->where('vendor_id', $vendorId);
                }

                if ($request['search']) {
                    $query = $this->productSearch($query, $request);
                }

                $query->orderBy('products.sort', 'asc');
            },
        ]);
        $categories = $categories->whereHas('products.offer', function ($query) {
            $query->active()->unexpired()->started();
        });
        return $categories->orderBy('id', 'desc')->get();
    }

    public function getSubCategoriesByCategoryId($request, $id)
    {
        return $this->category->where('category_id', $id)->orderBy('sort', 'asc')->get();
    }
}
