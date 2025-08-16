@extends('layouts.master')
@section('title', 'Order Details')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Order Details</h2>

        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                        <span class="text-secondary fw-medium hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">Order</span>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">Order Details</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-sm-6 col-xxl-3">
            <div class="card border bg-white rounded-3 overflow-hidden mb-4">
                <div class="d-flex align-items-center p-4 pb-3 mb-1">
                    <div class="flex-shrink-0">
                        <div class="wh-55 bg-primary bg-opacity-25 text-center rounded-2" style="line-height: 55px;">
                            <i class="ri-shopping-cart-line fs-22 bg-primary text-white rounded-2 p-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="fs-24 fw-medium mb-0">{{
                            number_format($order->orderDetails->sum('total_unit_price'),2) ?? 00 }}</h3>
                        <span>Total Order value</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xxl-3">
            <div class="card border bg-white rounded-3 overflow-hidden mb-4">
                <div class="d-flex align-items-center p-4 pb-3 mb-1">
                    <div class="flex-shrink-0">
                        <div class="wh-55 bg-primary-div bg-opacity-25 text-center rounded-2"
                            style="line-height: 55px;">
                            <i class="ri-shopping-bag-3-line fs-22 bg-primary-div text-white rounded-2 p-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="fs-24 fw-medium mb-0">{{
                            number_format($order->yarnQuotations->sum('total_price'),2) ?? 00}}</h3>
                        <span>Yarn Quotation value</span>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-6 col-xxl-3">
            <div class="card border bg-white rounded-3 overflow-hidden mb-4">
                <div class="d-flex align-items-center p-4 pb-3 mb-1">
                    <div class="flex-shrink-0">
                        <div class="wh-55 bg-card-bg-c bg-opacity-25 text-center rounded-2" style="line-height: 55px;">
                            <i class="ri-wallet-2-line fs-22 bg-card-bg-c text-white rounded-2 p-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="fs-24 fw-medium mb-0">{{
                            number_format($order->nettingQuotations->sum('total_price'),2) ?? 00}}</h3>
                        <span>Netting Quotation value</span>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-6 col-xxl-3">
            <div class="card border bg-white rounded-3 overflow-hidden mb-4">
                <div class="d-flex align-items-center p-4 pb-3 mb-1">
                    <div class="flex-shrink-0">
                        <div class="wh-55 bg-danger bg-opacity-25 text-center rounded-2" style="line-height: 55px;">
                            <i class="ri-money-dollar-circle-line fs-22 bg-danger text-white rounded-2 p-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="fs-24 fw-medium mb-0">{{
                            number_format($order->dyeingQuotations->sum('total_price'),2) ?? 0 }}</h3>
                        <span>Dyeing Quotation value</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xxl-3">
            <div class="card border bg-white rounded-3 overflow-hidden mb-4">
                <div class="d-flex align-items-center p-4 pb-3 mb-1">
                    <div class="flex-shrink-0">
                        <div class="wh-55 bg-primary-div bg-opacity-25 text-center rounded-2"
                            style="line-height: 55px;">
                            <i class="ri-shopping-bag-3-line fs-22 bg-primary-div text-white rounded-2 p-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="fs-24 fw-medium mb-0">{{
                            number_format($order->accessoriesQuotations->sum('total_price'),2) ?? 0 }}</h3>
                        <span>Accessories Quotation value</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4 mt-2">
        <div class="col-12">
            <div class="card border bg-white rounded-3 overflow-hidden">
                <div class="card-header">
                    <h3 class="card-title">Basic Order Info</h3>
                </div>
                <div class="card-body">
                    <div class="default-table-area style-two default-table-width">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th><strong>PO</strong></th>
                                        <th><strong>Order Number</strong></th>
                                        <th><strong>Order Date</strong></th>
                                        <th><strong>Status</strong></th>
                                        <th><strong>Approx. delivery</strong></th>
                                        <th><strong>Delivery Date</strong></th>
                                        <th><strong>Approved By</strong></th>
                                        <th><strong>PO PDF</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $order->po_number }}</td>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->order_date }}</td>
                                        <td>
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success p-2 fs-12 fw-normal">{{
                                                Str::ucfirst($order->status)
                                                }}</span>
                                        </td>
                                        <td>{{ $order->approximate_delivery_date }}</td>
                                        <td>{{ $order->delivery_date ?? '--' }}</td>
                                        <td>{{ $order->approvedBy->name ?? '--' }}</td>
                                        <td>
                                            @if ($order->po_file)
                                            <a href="{{ asset('storage/'.$order->po_file) }}" target="_blank"><span
                                                    class="material-symbols-outlined">
                                                    picture_as_pdf
                                                </span></a>
                                            @else
                                            --
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table align-middle mt-4">
                                <thead>
                                    <tr>
                                        <th><strong>Created By</strong></th>
                                        <th><strong>Created at</strong></th>
                                        <th><strong>Last updated by</strong></th>
                                        <th><strong>Last update at</strong></th>
                                        <th><strong>Shipping Address</strong></th>
                                        <th><strong>Remarks</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $order->creator->name ?? '--' }}</td>
                                        <td>{{ $order->created_at->format('d M Y')}}</td>
                                        <td>{{ $order->lastUpdateBy->name ?? '--'}}</td>
                                        <td>{{ $order->updated_at->format('d M Y')}}</td>
                                        <td>{{ $order->ship_address }}</td>
                                        <td>{{ $order->remarks }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <nav>
                        <div class="nav nav-tabs nav-pills mb-4" id="nav-tab">
                            <button class="nav-link active text-uppercase" data-bs-toggle="tab"
                                data-bs-target="#customer_info" type="button"><strong>Customer Info</strong></button>
                            <button class="nav-link text-uppercase" data-bs-toggle="tab" data-bs-target="#order_details"
                                type="button"><strong>Order Details</strong></button>
                            <button class="nav-link text-uppercase" data-bs-toggle="tab"
                                data-bs-target="#yarn_quot_details" type="button"><strong>Yarn Qut.
                                    Details</strong></button>
                            <button class="nav-link text-uppercase" data-bs-toggle="tab"
                                data-bs-target="#netting_quot_details" type="button"><strong>Netting Qut.
                                    Details</strong></button>
                            <button class="nav-link text-uppercase" data-bs-toggle="tab"
                                data-bs-target="#dyeing_quot_details" type="button"><strong>Dyeing Qut.
                                    Details</strong></button>
                            <button class="nav-link text-uppercase" data-bs-toggle="tab"
                                data-bs-target="#acc_quot_details" type="button"><strong>Accessories
                                    Qut. Details</strong></button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="customer_info">
                            <div class="default-table-area style-two default-table-width">
                                <div class="table-responsive">
                                    <table class="table align-middle mt-4">
                                        <thead>
                                            <tr>
                                                <th><strong>Client Name</strong></th>
                                                <th><strong>Client Email</strong></th>
                                                <th><strong>Client Phone</strong></th>
                                                <th><strong>Client Address</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $order->client_name }}</td>
                                                <td>{{ $order->client_email}}</td>
                                                <td>{{ $order->client_phone}}</td>
                                                <td>{{ $order->client_address}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="order_details">
                            <div class="default-table-area style-two default-table-width">
                                <div class="table-responsive">
                                    <table class="table align-middle mt-4">
                                        <thead>
                                            <tr>
                                                <th><strong>Style</strong></th>
                                                <th><strong>Description</strong></th>
                                                <th><strong>Quantity(PC)</strong></th>
                                                <th><strong>Unit Price</strong></th>
                                                <th><strong>Total Price</strong></th>
                                                <th><strong>Created at</strong></th>
                                                <th><strong>Created by</strong></th>
                                                <th><strong>Last Updated by</strong></th>
                                                <th><strong>Updated at</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($order->orderDetails as $item)
                                            <tr>
                                                <td>{{ $item->style }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->unit_quantity }}</td>
                                                <td>{{ $item->unit_price }}</td>
                                                <td>{{ $item->total_unit_price }}</td>
                                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                                <td>{{ $item->creator->name ?? '--' }}</td>
                                                <td>{{ $item->lastUpdateBy->name ?? '--' }}</td>
                                                <td>{{ $item->updated_at->format('d M Y') }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5">No Data Found!</td>
                                            </tr>
                                            @endforelse

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="text-center"><strong>Total:</strong></td>
                                                <td colspan="2">
                                                    <strong>{{
                                                        number_format($order->orderDetails->sum('unit_quantity'), 2)
                                                        }}PC</strong>
                                                </td>
                                                <td colspan="2">
                                                    <strong>{{
                                                        number_format($order->orderDetails->sum('total_unit_price'), 2)
                                                        }}TK</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="yarn_quot_details">
                            @forelse ($order->yarnQuotations->groupBy('style') as $key => $items)

                            <div class="accordion mt-5">
                                <div class="accordion-item">

                                    <h2 class="accordion-header">
                                        <button style="background: #605dff;"
                                            class="accordion-button text-uppercase text-white" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}">
                                            <strong>Style: {{ $key }}</strong>
                                        </button>
                                    </h2>

                                    <div id="collapse{{ $key }}" class="accordion-collapse collapse show">
                                        <div class="accordion-body p-0">
                                            <div class="default-table-area style-two default-table-width">
                                                <div class="table-responsive">
                                                    <table class="table align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Description</th>
                                                                <th>Qty(KG)</th>
                                                                <th>Price</th>
                                                                <th>Total Price</th>
                                                                <th>Status</th>
                                                                <th>Del. Qty</th>
                                                                <th>No Del. Qty</th>
                                                                <th>Loss Qty</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach ($items as $item)
                                                            <tr>
                                                                <td>{{ $item->description }}</td>
                                                                <td>{{ $item->quantity }}</td>
                                                                <td>{{ $item->price }}</td>
                                                                <td>{{ $item->total_price }}</td>
                                                                <td>{{ $item->status }}</td>
                                                                <td>--</td>
                                                                <td>--</td>
                                                                <td>--</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td class="text-center"><strong>Total:</strong></td>
                                                                <td colspan="2">
                                                                    <strong>{{
                                                                        number_format($items->sum('quantity'),
                                                                        2)
                                                                        }}KG</strong>
                                                                </td>
                                                                <td colspan="2">
                                                                    <strong>{{
                                                                        number_format($items->sum('total_price'),
                                                                        2)
                                                                        }}TK</strong>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="table-responsive  mt-4">
                                                <table class="table align-middle">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>Description</th>
                                                            <th>Approx. Delivery</th>
                                                            <th>Delivery Date</th>
                                                            <th>remarks</th>
                                                            <th>Approved By</th>
                                                            <th>Created By</th>
                                                            <th>Created At</th>
                                                            <th>Last Up. By</th>
                                                            <th>Last Up. At</th>
                                                            <th>Yarn Fac.</th>
                                                            <th>Netting Fac.</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($items as $item)
                                                        <tr>
                                                            <td>{{ $item->description }}</td>
                                                            <td>{{ $item->approximate_delivery_date }}</td>
                                                            <td>{{ $item->delivery_date ?? '--' }}</td>
                                                            <td>{{ $item->remarks }}</td>
                                                            <td>{{ $item->approvedBy->name ?? '--' }}</td>
                                                            <td>{{ $item->creator->name ?? '--' }}</td>
                                                            <td>{{ $item->created_at->format('d M Y')}}</td>
                                                            <td>{{ $item->lastUpdateBy->name ?? '--'}}</td>
                                                            <td>{{ $item->updated_at->format('d M Y')}}</td>
                                                            <td>Name: {{ $item->yarnFactory->name}} <br> Address: {{
                                                                $item->yarnFactory->address}}</td>
                                                            <td>Name: {{ $item->nettingFactory->name}} <br> Address:
                                                                {{
                                                                $item->nettingFactory->address}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p>No Data Found!</p>
                            @endforelse
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <h3>Total Quantity(KG): {{
                                            number_format($order->yarnQuotations->sum('quantity'),
                                            2)
                                            }}KG</h3>

                                        <h3 class="ms-5">Total TK: {{
                                            number_format($order->yarnQuotations->sum('total_price'),
                                            2)
                                            }}TK</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="netting_quot_details">
                            <div class="accordion ">
                                @forelse ($order->nettingQuotations as $item)

                                <div class="accordion-item mt-5">

                                    <h2 class="accordion-header">
                                        <button style="background: #605dff;"
                                            class="accordion-button text-uppercase text-white" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                            <strong>Style: {{ $item->style }}</strong>
                                            @if ($item->delivery_factory_type === 'garments')
                                            <p class="ms-5"><strong>Delivery Factory Type: </strong> Garments Factory.
                                            </p>
                                            @else
                                            <p class="ms-5"><strong>Delivery Factory Type: </strong>Dyeing Factory.</p>
                                            @endif
                                        </button>
                                    </h2>

                                    <div id="collapse{{ $item->id }}" class="accordion-collapse collapse show">
                                        <div class="accordion-body p-0">
                                            <div class="default-table-area style-two default-table-width">
                                                <div class="table-responsive">
                                                    <table class="table align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Style</th>
                                                                <th>Qty(KG)</th>
                                                                <th>Price</th>
                                                                <th>Total Price</th>
                                                                <th>Status</th>
                                                                <th>Del. Qty</th>
                                                                <th>No Del. Qty</th>
                                                                <th>Loss Qty</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <tr>
                                                                <td>{{ $item->style }}</td>
                                                                <td>{{ $item->quantity }}</td>
                                                                <td>{{ $item->price }}</td>
                                                                <td>{{ $item->total_price }}</td>
                                                                <td>{{ $item->status }}</td>
                                                                <td>--</td>
                                                                <td>--</td>
                                                                <td>--</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="table-responsive  mt-4">
                                                <table class="table align-middle">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>Approx. Delivery</th>
                                                            <th>Delivery Date</th>
                                                            <th>remarks</th>
                                                            <th>Approved By</th>
                                                            <th>Created By</th>
                                                            <th>Created At</th>
                                                            <th>Last Up. By</th>
                                                            <th>Last Up. At</th>
                                                            <th>Netting Fac.</th>
                                                            @if ($item->delivery_factory_type === 'garments')
                                                            <th>Garments Fac.</th>
                                                            @else
                                                            <th>Dyeing Fac.</th>
                                                            @endif
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr>
                                                            <td>{{ $item->approximate_delivery_date }}</td>
                                                            <td>{{ $item->delivery_date ?? '--' }}</td>
                                                            <td>{{ $item->remarks }}</td>
                                                            <td>{{ $item->approvedBy->name ?? '--' }}</td>
                                                            <td>{{ $item->creator->name ?? '--' }}</td>
                                                            <td>{{ $item->created_at->format('d M Y')}}</td>
                                                            <td>{{ $item->lastUpdateBy->name ?? '--'}}</td>
                                                            <td>{{ $item->updated_at->format('d M Y')}}</td>
                                                            <td>Name: {{ $item->nettingFactory->name}} <br> Address:
                                                                {{
                                                                $item->nettingFactory->address}}</td>
                                                            @if ($item->delivery_factory_type === 'garments')
                                                            <td>Name: {{ $item->garmentsFactory->name}} <br> Address: {{
                                                                $item->garmentsFactory->address}}</td>
                                                            @else
                                                            <td>Name: {{ $item->dyeingFactory->name}} <br> Address: {{
                                                                $item->dyeingFactory->address}}</td>
                                                            @endif

                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <p>No Data Found!</p>
                                @endforelse
                            </div>
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <h3>Total Quantity(KG): {{
                                            number_format($order->nettingQuotations->sum('quantity'),
                                            2)
                                            }}KG</h3>

                                        <h3 class="ms-5">Total TK: {{
                                            number_format($order->nettingQuotations->sum('total_price'),
                                            2)
                                            }}TK</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="dyeing_quot_details">
                            <div class="accordion ">
                                @forelse ($order->dyeingQuotations as $item)
                                <div class="accordion-item mt-5">

                                    <h2 class="accordion-header">
                                        <button style="background: #605dff;"
                                            class="accordion-button text-uppercase text-white" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}">
                                            <strong>Style: {{ $item->style }}</strong>
                                        </button>
                                    </h2>

                                    <div id="collapse{{ $item->id }}" class="accordion-collapse collapse show">
                                        <div class="accordion-body p-0">
                                            <div class="default-table-area style-two default-table-width">
                                                <div class="table-responsive">
                                                    <table class="table align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Style</th>
                                                                <th>Qty(KG)</th>
                                                                <th>Price</th>
                                                                <th>Total Price</th>
                                                                <th>Status</th>
                                                                <th>Del. Qty</th>
                                                                <th>No Del. Qty</th>
                                                                <th>Loss Qty</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <tr>
                                                                <td>{{ $item->style }}</td>
                                                                <td>{{ $item->quantity }}</td>
                                                                <td>{{ $item->price }}</td>
                                                                <td>{{ $item->total_price }}</td>
                                                                <td>{{ $item->status }}</td>
                                                                <td>--</td>
                                                                <td>--</td>
                                                                <td>--</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="table-responsive  mt-4">
                                                <table class="table align-middle">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>Approx. Delivery</th>
                                                            <th>Delivery Date</th>
                                                            <th>remarks</th>
                                                            <th>Approved By</th>
                                                            <th>Created By</th>
                                                            <th>Created At</th>
                                                            <th>Last Up. By</th>
                                                            <th>Last Up. At</th>
                                                            <th>Dyeing Fac.</th>
                                                            <th>Garments Fac.</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr>
                                                            <td>{{ $item->approximate_delivery_date }}</td>
                                                            <td>{{ $item->delivery_date ?? '--' }}</td>
                                                            <td>{{ $item->remarks }}</td>
                                                            <td>{{ $item->approvedBy->name ?? '--' }}</td>
                                                            <td>{{ $item->creator->name ?? '--' }}</td>
                                                            <td>{{ $item->created_at->format('d M Y')}}</td>
                                                            <td>{{ $item->lastUpdateBy->name ?? '--'}}</td>
                                                            <td>{{ $item->updated_at->format('d M Y')}}</td>
                                                            <td>Name: {{ $item->dyeingFactory->name}} <br> Address: {{
                                                                $item->dyeingFactory->address}}</td>
                                                            <td>Name: {{ $item->garmentsFactory->name}} <br> Address: {{
                                                                $item->garmentsFactory->address}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <p>No Data Found!</p>
                                @endforelse
                            </div>
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <h3>Total Quantity(KG): {{
                                            number_format($order->dyeingQuotations->sum('quantity'),
                                            2)
                                            }}KG</h3>

                                        <h3 class="ms-5">Total TK: {{
                                            number_format($order->dyeingQuotations->sum('total_price'),
                                            2)
                                            }}TK</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="acc_quot_details">
                            <div class="accordion ">
                                @forelse ($order->accessoriesQuotations->groupBy('style') as $key=>$items)

                                <div class="accordion-item mt-5">

                                    <h2 class="accordion-header">
                                        <button style="background: #605dff;"
                                            class="accordion-button text-uppercase text-white" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}">
                                            <strong>Style: {{ $key }}</strong>
                                        </button>
                                    </h2>

                                    <div id="collapse{{ $key }}" class="accordion-collapse collapse show">
                                        <div class="accordion-body p-0">
                                            <div class="default-table-area style-two default-table-width">
                                                <div class="table-responsive">
                                                    <table class="table align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Description</th>
                                                                <th>Qty</th>
                                                                <th>Unit</th>
                                                                <th>Price</th>
                                                                <th>Total Price(TK)</th>
                                                                <th>Status</th>
                                                                <th>Del. Qty</th>
                                                                <th>No Del. Qty</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach ($items as $item)
                                                            <tr>
                                                                <td>{{ $item->description }}</td>
                                                                <td>{{ $item->quantity }}</td>
                                                                <td>{{ $item->unit }}</td>
                                                                <td>{{ $item->price }}</td>
                                                                <td>{{ $item->total_price }}</td>
                                                                <td>{{ $item->status }}</td>
                                                                <td>--</td>
                                                                <td>--</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td class="text-center" colspan="2">
                                                                    <strong>Total:</strong>
                                                                </td>

                                                                <td colspan="2">
                                                                    <strong>{{
                                                                        number_format($items->sum('total_price'),
                                                                        2)
                                                                        }}TK</strong>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="table-responsive  mt-4">
                                                <table class="table align-middle">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>Description</th>
                                                            <th>Approx. Delivery</th>
                                                            <th>Delivery Date</th>
                                                            <th>remarks</th>
                                                            <th>Shiphing Address</th>
                                                            <th>Supplier Info</th>
                                                            <th>Approved By</th>
                                                            <th>Created By</th>
                                                            <th>Created At</th>
                                                            <th>Last Up. By</th>
                                                            <th>Last Up. At</th>
                                                            <th>Last Up. At</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($items as $item)
                                                        <tr>
                                                            <td>{{ $item->description }}</td>
                                                            <td>{{ $item->approximate_delivery_date }}</td>
                                                            <td>{{ $item->delivery_date ?? '--' }}</td>
                                                            <td>{{ $item->remarks }}</td>
                                                            <td>{{ $item->shiphing_address }}</td>
                                                            <td>
                                                                <p>Name:{{ $item->supplier_name }}</p>
                                                                <p>Phone: {{ $item->supplier_phone }}</p>
                                                                <p> Address: {{ $item->supplier_address }}</p>
                                                            </td>
                                                            <td>{{ $item->approvedBy->name ?? '--' }}</td>
                                                            <td>{{ $item->creator->name ?? '--' }}</td>
                                                            <td>{{ $item->created_at->format('d M Y')}}</td>
                                                            <td>{{ $item->lastUpdateBy->name ?? '--'}}</td>
                                                            <td>{{ $item->updated_at->format('d M Y')}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <p>No Data Found!</p>
                                @endforelse
                            </div>
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="d-flex">

                                        <h3 class="ms-5">Total TK: {{
                                            number_format($order->accessoriesQuotations->sum('total_price'),
                                            2)
                                            }}TK</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>

<div class="flex-grow-1"></div>
@endsection