<?php

namespace Modules\Catalog\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Modules\Advertising\Transformers\WebService\AdvertisingGroupResource;
use Modules\Catalog\Transformers\WebService\AutoCompleteProductResource;
use Modules\Catalog\Transformers\WebService\CategoryListResource;
use Modules\Catalog\Transformers\WebService\FilteredOptionsResource;
use Modules\Catalog\Transformers\WebService\PaginatedResource;
use Modules\Catalog\Transformers\WebService\ProductResource;
use Modules\Catalog\Transformers\WebService\CategoryResource;
use Modules\Catalog\Repositories\WebService\CatalogRepository as Catalog;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Slider\Repositories\WebService\SliderRepository as Slider;
use Modules\Advertising\Repositories\WebService\AdvertisingRepository as Advertising;
use Modules\Slider\Transformers\WebService\SliderResource;
use Illuminate\Http\JsonResponse;
use Modules\Vendor\Repositories\WebService\VendorRepository as Vendor;
use Modules\Vendor\Transformers\WebService\VendorListResource;

class CatalogController extends WebServiceController
{
    protected $catalog;
    protected $slider;
    protected $advert;
    protected $vendor;

    function __construct(Catalog $catalog, Slider $slider, Advertising $advert, Vendor $vendor)
    {
        $this->catalog = $catalog;
        $this->slider = $slider;
        $this->advert = $advert;
        $this->vendor = $vendor;
    }

    public function getHomeData(Request $request): JsonResponse
    {
        // Get Slider Data
        $sliders = $this->slider->getRandomPerRequest();
        $result['slider'] = SliderResource::collection($sliders);

        /*
        // Get Featured Products
        $newData = $this->catalog->getFeaturedProducts($request);
        $result['featured_products'] = ProductResource::collection($newData);

        // Get Offers Products
        $bundleOffers = $this->catalog->getOffersData($request);
        $result['offers_products'] = ProductResource::collection($bundleOffers);
        */

        // Get Latest N Categories
        $categories = $this->catalog->getLatestNCategories($request);
        $result['categories'] = CategoryResource::collection($categories);

        $adverts = $this->advert->getAdvertGroups();
        $result['advertsGroups'] = AdvertisingGroupResource::collection($adverts);

        if ($request->get_vendors == 'yes') {
            $vendors = $this->vendor->getVendorsList($request);
            $result['vendors'] = VendorListResource::collection($vendors);
        }

        $sections = [];
        if ($request->get_sections == 'yes') {
            $mostPopular = $this->catalog->getAllPopular($request);
            $mostPopularObject = [
                'id' => 'most_popular',
                'title' => __('apps::dashboard.general.most_popular'),
                'image' => null,
                'products' => ProductResource::collection($mostPopular),
                'products_count' => count($mostPopular),
                'adverts' => [],
            ];
            $sections[] = $mostPopularObject;

            $newArrival = $this->catalog->getAllNewArrival($request);
            $newArrivalObject = [
                'id' => 'new_arrival',
                'title' => __('apps::dashboard.general.new_arrival'),
                'image' => null,
                'products' => ProductResource::collection($newArrival),
                'products_count' => count($newArrival),
                'adverts' => [],
            ];
            $sections[] = $newArrivalObject;
        }

        $result['sections'] = $sections;

        return $this->response($result);
    }

    public function getAllCategories(Request $request): JsonResponse
    {
        $categories = $this->catalog->getAllCategories($request);
        return $this->response(CategoryResource::collection($categories));
    }

    public function getAutoCompleteProducts(Request $request)
    {
        $products = $this->catalog->getAutoCompleteProducts($request);
        $result = AutoCompleteProductResource::collection($products);
        return $this->response($result);
    }

    public function getProductsByCategory(Request $request)
    {
        /*$categories = $this->catalog->getAllMainCategories($request);
        $result['main_categories'] = CategoryResource::collection($categories);*/

        /*$options = $this->catalog->getFilterOptions($request);
        $result['options'] = FilteredOptionsResource::collection($options);*/

        /*$products = $this->catalog->getProductsByCategory($request);
        $result['products'] = PaginatedResource::make($products)->mapInto(ProductResource::class);
        return $this->response($result);*/

        $products = $this->catalog->getProductsByCategory($request);
        $result = ProductResource::collection($products);
        return $this->responsePagination($result);
    }

    public function getProductDetails(Request $request, $id): JsonResponse
    {
        $product = $this->catalog->getProductDetails($request, $id);
        if ($product) {
            $result = [
                'product' => new ProductResource($product),
                'related_products' => ProductResource::collection($this->catalog->relatedProducts($product)),
            ];
            return $this->response($result);
        } else
            return $this->response(null);
    }

    public function getFilterValues(Request $request)
    {
        $options = $this->catalog->getFilterOptions($request);
        $result['options'] = FilteredOptionsResource::collection($options);
        $result['price']['min'] = 0;
        $result['price']['max'] = 10000;
        return $this->response($result);
    }

    public function getCategoriesOffersData(Request $request)
    {
        $categories = $this->catalog->getCategoriesOffersData($request);
        $categories = $categories->filter(function ($item) {
            return count($item->products) > 0;
        });
        $result = CategoryListResource::collection($categories);
        return $this->response($result);
    }

    public function getSubCategoriesByCategoryId(Request $request, $id)
    {
        $items = $this->catalog->getSubCategoriesByCategoryId($request, $id);
        return $this->response(CategoryListResource::collection($items));
    }
}
