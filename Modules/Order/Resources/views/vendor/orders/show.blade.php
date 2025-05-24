@extends('apps::vendor.layouts.app')
@section('title', __('order::vendor.orders.show.title'))
@section('content')
    <style type="text/css" media="print">
        @page {
            size: auto;
            margin: 0;
        }

        @media print {
            a[href]:after {
                content: none !important;
            }

            .contentPrint {
                width: 100%;
                /* font-family: tahoma; */
                font-size: 16px;
            }

            .invoice-body td.notbold {
                padding: 2px;
            }

            h2.invoice-title.uppercase {
                margin-top: 0px;
            }

            .invoice-content-2 {
                background-color: #fff;
                padding: 5px 20px;
            }

            .invoice-content-2 .invoice-cust-add, .invoice-content-2 .invoice-head {
                margin-bottom: 0px;
            }

            .no-print, .no-print * {
                display: none !important;
            }
        }
    </style>
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('vendor.home')) }}">{{ __('apps::vendor.home.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ url(route('vendor.orders.index')) }}">
                            {{__('order::vendor.orders.index.title')}}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{__('order::vendor.orders.show.title')}}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <div class="col-md-12">
                    <div class="no-print">
                        <div class="col-md-3">
                            <ul class="ver-inline-menu tabbable margin-bottom-10">
                                <li class="active">
                                    <a data-toggle="tab" href="#order">
                                        <i class="fa fa-cog"></i> {{__('order::vendor.orders.show.invoice')}}
                                    </a>
                                    <span class="after"></span>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#edit">
                                        <i class="fa fa-cog"></i> {{__('order::vendor.orders.show.edit')}}
                                    </a>
                                    <span class="after"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-9 contentPrint">
                        <div class="tab-content">
                            <div class="tab-pane active" id="order">

                                <div class="invoice-content-2 bordered">
                                    <div class="row invoice-head">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="invoice-logo">
                                                <center>
                                                    <img src="{{ url(config('setting.logo')) }}" class="img-responsive"
                                                         alt="" style="width: 170px; height: 170px;"/>
                                                    <span>
                                                      {{ $order->orderStatus->translate(locale())->title }}
                                                    </span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                      <span class="bold uppercase">
                                        {{ $order->orderAddress->state->city->translate(locale())->title }} /
                                        {{ $order->orderAddress->state->translate(locale())->title }}
                                      </span>
                                            <br/>
                                            <span
                                                class="bold">{{__('order::dashboard.orders.show.address.block')}} : </span>
                                            {{ $order->orderAddress->block }}
                                            <br/>
                                            <span
                                                class="bold">{{__('order::dashboard.orders.show.address.street')}} : </span>
                                            {{ $order->orderAddress->street }}
                                            <br/>
                                            <span
                                                class="bold">{{__('order::dashboard.orders.show.address.building')}} : </span>
                                            {{ $order->orderAddress->building }}
                                            <br/>
                                            <span
                                                class="bold">{{__('order::dashboard.orders.show.address.details')}} : </span>
                                            {{ $order->orderAddress->address }}
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="company-address">
                                                <h6 class="uppercase">#{{ $order['id'] }}</h6>
                                                <h6 class="uppercase">{{date('Y-m-d / H:i:s' , strtotime($order->created_at))}}</h6>
                                                <span class="bold">
                                                    {{__('order::dashboard.orders.show.user.username')}} :
                                                </span>
                                                {{ $order->orderAddress->username }}
                                                <br/>
                                                <span class="bold">
                                                    {{__('order::dashboard.orders.show.user.mobile')}} :
                                                  </span>
                                                {{ $order->orderAddress->mobile }}
                                                <br/>
                                            </div>
                                        </div>
                                        <div class="row invoice-body">
                                            <div class="col-xs-12 table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{__('order::dashboard.orders.show.items.title')}}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{__('order::dashboard.orders.show.items.price')}}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{__('order::dashboard.orders.show.items.qty')}}
                                                        </th>
                                                        <th class="invoice-title uppercase text-center">
                                                            {{__('order::dashboard.orders.show.items.total')}}
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($order->allProducts as $product)
                                                        @if(isset($product->product_variant_id) && !empty($product->product_variant_id))
                                                            <tr>
                                                                <td class="text-center sbold">
                                                                    {{--{{ $product->variant->product->translate(locale())->title }}--}}
                                                                    <a title="{{ $product->notes }}"
                                                                       href="{{ route('vendor.products.edit', $product->variant->product->id) }}">
                                                                        {{ generateVariantProductData($product->variant->product, $product->product_variant_id, $product->variant->productValues->pluck('option_value_id')->toArray())['name'] }}
                                                                    </a>
                                                                </td>
                                                                <td class="text-center sbold"> {{ $product->sale_price }} </td>
                                                                <td class="text-center sbold"> {{ $product->qty }} </td>
                                                                <td class="text-center sbold"> {{ $product->total }}</td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <td class="notbold text-center">
                                                                    <a title="{{ $product->notes }}"
                                                                       href="{{ route('vendor.products.edit', $product->product->id) }}">
                                                                        {{ $product->product->translate(locale())->title }}
                                                                        <br>
                                                                        {{ $product->product->sku }}
                                                                    </a>
                                                                </td>
                                                                <td class="text-center notbold"> {{ $product->sale_price }} </td>
                                                                <td class="text-center notbold"> {{ $product->qty }} </td>
                                                                <td class="text-center notbold"> {{ $product->total }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center bold">
                                                            {{__('order::dashboard.orders.show.order.total')}}
                                                        </th>
                                                        <th class="text-center bold"></th>
                                                        <th class="text-center bold"> {{ $order->allProducts->sum('qty') }}</th>
                                                        <th class="text-center bold"> {{ number_format($order->allProducts->sum('total'), 3) }} </th>
                                                        {{--<th class="text-center bold"> {{ $order->subtotal }} </th>--}}
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row invoice-subtotal">

                                            {{--<div class="col-xs-2">
                                                <h4 class="text-center notbold">{{__('order::dashboard.orders.show.order.subtotal')}}</h4>
                                                <p class="text-center notbold">{{ $order->subtotal }}</p>
                                            </div>
                                            <div class="col-xs-2">
                                                <h4 class="text-center notbold">{{__('order::dashboard.orders.show.order.shipping')}}</h4>
                                                <p class="text-center notbold">{{ $order->shipping }} </p>
                                            </div>
                                            <div class="col-xs-2">
                                                <h4 class="text-center notbold">{{__('order::dashboard.orders.show.order.off')}}</h4>
                                                <p class="text-center notbold">{{ $order->off }}</p>
                                            </div>
                                            <div class="col-xs-2">
                                                <h4 class="text-center notbold">{{__('order::dashboard.orders.show.order.total')}}</h4>
                                                <p class="text-center notbold">{{ $order->total }}</p>
                                            </div>--}}

                                            <div class="col-xs-2">
                                                <h4 class="text-center notbold">
                                                    {{__('transaction::dashboard.orders.show.transaction.method')}}
                                                </h4>
                                                <p class="text-center notbold">
                                                    {{ ucfirst($order->transactions->method) }}
                                                </p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="edit">
                                <form id="updateForm" method="POST"
                                      action="{{url(route('vendor.orders.update',$order['id']))}}"
                                      enctype="multipart/form-data" class="horizontal-form">
                                    <div class="no-print">
                                        @csrf
                                        <input name="_method" type="hidden" value="PUT">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('order::dashboard.orders.show.status')}}
                                                </label>
                                                <div class="col-md-9">
                                                    <select name="order_status" id="single" class="form-control"
                                                            required>
                                                        <option value="">Select</option>
                                                        @foreach ($statuses as $status)
                                                            <option
                                                                value="{{ $status->id }}" {{ ($order->order_status_id == $status->id) ? 'selected' : '' }}>
                                                                {{ $status->translate(locale())->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <br>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('order::dashboard.orders.show.order_notes')}}
                                                </label>
                                                <div class="col-md-9">
                                                    <textarea class="form-control" name="order_notes" rows="8"
                                                              cols="80">{{ $order->order_notes }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="result" style="display: none"></div>
                                        <div class="progress-info" style="display: none">
                                            <div class="progress">
                                                <span class="progress-bar progress-bar-warning"></span>
                                            </div>
                                            <div class="status" id="progress-status"></div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" id="submit" class="btn green btn-lg">
                                                {{__('apps::dashboard.general.edit_btn')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-4">
                            <a class="btn btn-lg blue hidden-print margin-bottom-5"
                               onclick="javascript:window.print();">
                                {{__('apps::vendor.general.print_btn')}}
                                <i class="fa fa-print"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
