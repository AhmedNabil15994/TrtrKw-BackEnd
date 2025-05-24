<?php

namespace Modules\Vendor\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Vendor\Http\Requests\FrontEnd\AskQuestionRequest;
use Modules\Vendor\Http\Requests\FrontEnd\PrescriptionRequest;
use Modules\Vendor\Http\Requests\WebService\RateRequest;
use Modules\Vendor\Traits\UploaderTrait;
use Modules\Vendor\Transformers\WebService\DeliveryCompaniesResource;
use Modules\Vendor\Transformers\WebService\SectionResource;
use Modules\Vendor\Transformers\WebService\VendorResource;
use Modules\Vendor\Transformers\WebService\DeliveryChargeResource;
use Modules\Vendor\Repositories\WebService\VendorRepository as Vendor;
use Modules\Catalog\Repositories\WebService\CatalogRepository as Catalog;
use Modules\Vendor\Repositories\Vendor\RateRepository as Rate;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Vendor\Notifications\FrontEnd\AskVendordNotification;
use Modules\Vendor\Notifications\FrontEnd\PrescriptionVendordNotification;
use Notification;

class VendorController extends WebServiceController
{
    use UploaderTrait;

    protected $vendor;
    protected $rate;
    protected $catalog;

    function __construct(Vendor $vendor, Rate $rate, Catalog $catalog)
    {
        $this->vendor = $vendor;
        $this->rate = $rate;
        $this->catalog = $catalog;
    }

    public function sections()
    {
        $sections = $this->vendor->getAllSections();

        return SectionResource::collection($sections);
    }

    public function vendors(Request $request)
    {
        $vendors = $this->vendor->getAllVendors($request);
        return $this->response(VendorResource::collection($vendors));
    }

    public function getVendorById(Request $request)
    {
        $vendor = $this->vendor->getOneVendor($request);
        if ($vendor) {
            $products = $this->catalog->getProductsByVendor($vendor->id);
            $request->request->add(['products' => $products]);
            return $this->response(new VendorResource($vendor));
        } else
            return $this->response(null);
    }

    public function deliveryCharge(Request $request)
    {
        $charge = $this->vendor->getDeliveryChargesByVendorByState($request);

        if (!$charge)
            return $this->response([]);

        return $this->response(new DeliveryChargeResource($charge));
    }

    public function sendPrescription(PrescriptionRequest $request, $id)
    {
        $vendor = $this->vendor->findById($id);

        if (isset($request->image) && !empty($request->image)) {
            $uploadPath = $this->base64($request->image, null, 'prescriptions');
            $request->merge([
                'imagePath' => env('APP_URL') . $uploadPath,
            ]);
        } else {
            $imagePath = null;
        }

        Notification::route('mail', $vendor['vendor_email'])->notify(
            (
            new PrescriptionVendordNotification($request->all())
            )->locale(locale()));

        return $this->response([]);
    }

    public function sendAsk(AskQuestionRequest $request, $id)
    {
        $vendor = $this->vendor->findById($id);

        Notification::route('mail', $vendor['vendor_email'])->notify(
            (
            new AskVendordNotification($request)
            )->locale(locale()));

        return $this->response([]);
    }

    public function vendorRate(RateRequest $request)
    {
        $order = $this->rate->findOrderByIdWithUserId($request->order_id);
        if ($order) {
            $rate = $this->rate->checkUserRate($request->order_id);
            if (!$rate) {
                $request->merge([
                    'vendor_id' => $order->vendor_id,
                ]);
                $createdRate = $this->rate->create($request);
                return $this->response([]);
            } else
                return $this->error(__('vendor::webservice.rates.user_rate_before'));
        } else
            return $this->error(__('vendor::webservice.rates.user_not_have_order'));
    }

    public function getVendorDeliveryCompanies(Request $request, $id)
    {
        $vendor = $this->vendor->findVendorByIdAndStateId($id, $request->state_id);
        if ($vendor) {
            $result['companies'] = DeliveryCompaniesResource::collection($vendor->companies);
            $result['vendor_fixed_delivery'] = $vendor->fixed_delivery;
            return $this->response($result);
        } else {
            return $this->error(__('vendor::webservice.companies.vendor_not_found_with_this_state'), null);
        }
    }


}
