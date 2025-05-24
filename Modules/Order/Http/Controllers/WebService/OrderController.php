<?php

namespace Modules\Order\Http\Controllers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\MessageBag;
use Modules\Cart\Traits\CartTrait;
use Modules\Catalog\Repositories\WebService\CatalogRepository as Catalog;
use Modules\Order\Http\Requests\WebService\CreateOrderRequest;

//use Modules\Order\Http\Requests\WebService\CreateOrderRequestOld;
use Modules\Order\Transformers\WebService\OrderProductResource;
use Modules\User\Repositories\WebService\AddressRepository;
use Modules\Vendor\Repositories\WebService\VendorRepository as Vendor;
use Notification;
use Illuminate\Http\Request;
use Modules\Order\Events\ActivityLog;
use Modules\Order\Events\VendorOrder;

//use Modules\Transaction\Services\PaymentService;
use Modules\Transaction\Services\UPaymentService;
use Modules\Order\Transformers\WebService\OrderResource;
use Modules\Order\Repositories\WebService\OrderRepository as Order;

//use Modules\Order\Repositories\WebService\OrderRepositoryOld as Order;
use Modules\Wrapping\Repositories\WebService\WrappingRepository as Wrapping;
use Modules\Company\Repositories\WebService\CompanyRepository as Company;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;

class OrderController extends WebServiceController
{
    use CartTrait;

    protected $payment;
    protected $order;
    protected $company;
    protected $catalog;
    protected $address;
    protected $vendor;

    function __construct(
        Order             $order,
        UPaymentService   $payment,
        Company           $company,
        Catalog           $catalog,
        AddressRepository $address,
        Vendor            $vendor
    )
    {
        $this->payment = $payment;
        $this->order = $order;
        $this->company = $company;
        $this->catalog = $catalog;
        $this->address = $address;
        $this->vendor = $vendor;
    }

    public function createOrder(CreateOrderRequest $request)
    {
        if (auth('api')->check())
            $userToken = auth('api')->user()->id;
        else
            $userToken = $request->user_id;

        // Check if address is not found
        if ($request->address_type == 'selected_address') {
            // get address by id
            $companyDeliveryFees = getCartConditionByName($userToken, 'company_delivery_fees');
            $addressId = isset($companyDeliveryFees->getAttributes()['address_id'])
                ? $companyDeliveryFees->getAttributes()['address_id']
                : null;
            $address = $this->address->findByIdWithoutAuth($addressId);
            if (!$address)
                return $this->error(__('user::webservice.address.errors.address_not_found'), [], 422);
        }

        foreach (getCartContent($userToken) as $key => $item) {

            if ($item->attributes->product->product_type == 'product') {
                $cartProduct = $item->attributes->product;
                $product = $this->catalog->findOneProduct($cartProduct->id);
                if (!$product)
                    return $this->error(__('cart::api.cart.product.not_found') . $cartProduct->id, [], 422);

                $product->product_type = 'product';
            } else {
                $cartProduct = $item->attributes->product;
                $product = $this->catalog->findOneProductVariant($cartProduct->id);
                if (!$product)
                    return $this->error(__('cart::api.cart.product.not_found') . $cartProduct->id, [], 422);

                $product->product_type = 'variation';
            }

            $checkPrdFound = $this->productFound($product, $item);
            if ($checkPrdFound)
                return $this->error($checkPrdFound, [], 422);

            $checkPrdStatus = $this->checkProductActiveStatus($product, $request);
            if ($checkPrdStatus)
                return $this->error($checkPrdStatus, [], 422);

            if (!is_null($product->qty)) {
                $checkPrdMaxQty = $this->checkMaxQty($product, $item->quantity);
                if ($checkPrdMaxQty)
                    return $this->error($checkPrdMaxQty, [], 422);
            }

            $checkVendorStatus = $this->vendorStatus($product);
            if ($checkVendorStatus)
                return $this->error($checkVendorStatus, [], 422);

        }

        $order = $this->order->create($request, $userToken);
        if (!$order)
            return $this->error('error', [], 422);

        if ($request['payment'] != 'cash') {
            $payment = $this->payment->send($order, $request['payment'], $userToken, 'api-order');

            return $this->response([
                'paymentUrl' => $payment
            ]);
        }

        $this->fireLog($order);
        $this->clearCart($userToken);

        return $this->response(new OrderResource($order));
    }

    public function webhooks(Request $request)
    {
        $this->order->updateOrder($request);
    }

    public function success(Request $request)
    {
        $order = $this->order->updateOrder($request);
        if ($order) {
            $orderDetails = $this->order->findById($request['OrderID']);
            $userToken = auth('api')->check() ? auth('api')->id() : ($request->userToken ?? null);
            if ($orderDetails) {
                $this->fireLog($orderDetails);
                // $this->clearCart($userToken);
                return $this->response(new OrderResource($orderDetails));
            } else
                return $this->error(__('order::frontend.orders.index.alerts.order_failed'), [], 422);
        }
    }

    public function failed(Request $request)
    {
        $this->order->updateOrder($request);
        return $this->error(__('order::frontend.orders.index.alerts.order_failed'), [], 422);
    }

    public function userOrdersList(Request $request)
    {
        $orders = $this->order->getAllByUser();
        return $this->response(OrderResource::collection($orders));
    }

    public function getOrderDetails(Request $request, $id)
    {
        $order = $this->order->findById($id);

        if (!$order)
            return $this->error(__('order::api.orders.validations.order_not_found'), [], 422);

        $allOrderProducts = $order->orderProducts->mergeRecursive($order->orderVariations);
        return $this->response(OrderProductResource::collection($allOrderProducts));
    }

    public function fireLog($order)
    {
        $data = [
            'id' => $order->id,
            'type' => 'orders',
            'url' => url(route('dashboard.orders.show', $order->id)),
            'description_en' => 'New Order',
            'description_ar' => 'طلب جديد ',
        ];
        $data2 = [];

        if ($order->vendors) {
            foreach ($order->vendors as $k => $value) {
                $vendor = $this->vendor->findById($value->id);
                if ($vendor) {
                    $data2 = [
                        'ids' => $vendor->sellers->pluck('id'),
                        'type' => 'vendor',
                        'url' => url(route('vendor.orders.show', $order->id)),
                        'description_en' => 'New Order',
                        'description_ar' => 'طلب جديد',
                    ];
                }
            }
        }

        event(new ActivityLog($data));
        if (count($data2) > 0) {
            event(new VendorOrder($data2));
        }
    }
}
