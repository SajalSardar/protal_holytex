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

    {{-- basic order info --}}
    <div class="row mb-4 mt-2">
        <div class="col-12">
            <div class="card border bg-white rounded-3 overflow-hidden">
                <div class="card-header bg-primary">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title text-white">Basic Order Info</h3>
                    </div>
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
                                            @php
                                            $statusClasses = [
                                            'processing' => 'bg-primary-div text-primary-div bg-opacity-10',
                                            'pending' => 'bg-danger text-danger bg-opacity-10',
                                            'cancelled' => 'bg-danger text-danger bg-opacity-25',
                                            'block' => 'bg-info text-info bg-opacity-25',
                                            ];
                                            $statusDesign = $statusClasses[$order->status] ?? 'bg-success text-success
                                            bg-opacity-10';
                                            @endphp
                                            <span class="{{ $statusDesign }} badge p-2 fs-12 fw-normal">{{
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

    {{-- Customer Info --}}
    <div class="row mb-4 mt-2">
        <div class="col-12">
            <div class="card border bg-white rounded-3 overflow-hidden">
                <div class="card-header bg-primary">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title text-white">Client Info</h3>
                    </div>
                </div>
                <div class="card-body">
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
            </div>
        </div>
    </div>

    {{-- Order Details --}}
    <div class="row mb-4 mt-2">
        <div class="col-12">
            <div class="card border bg-white rounded-3 overflow-hidden">
                <div class="card-header bg-primary">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title text-white">Order Details</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="default-table-area style-two">
                        <div class="table-responsive">
                            @php
                            $totalDelivered = 0;
                            @endphp
                            <table class="table align-middle mt-4" style="width: 1800px">
                                <thead>
                                    <tr>
                                        <th><strong>Style</strong></th>
                                        <th><strong>Description</strong></th>
                                        <th><strong>Delivered(PC)</strong></th>
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
                                    @php
                                    $totalDelivered += $item->order_delivery_qty_sum_quantity ?? 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->order_delivery_qty_sum_quantity ?? 0 }}</td>
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
                                        <td>
                                            <strong>{{
                                                number_format($totalDelivered,
                                                2)
                                                }}PC</strong>
                                        </td>
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
            </div>
        </div>
    </div>

    {{-- yarn quotations --}}
    <div class="row mb-4 mt-2">
        <div class="col-12">
            <div class="card border bg-white rounded-3 overflow-hidden">
                <div class="card-header bg-primary">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title text-white">Yarn Quotation</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="default-table-area style-two">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Style</th>
                                        <th>Description</th>
                                        <th>Quotation(kg)</th>
                                        <th>Received(kg)</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($yarnQuotation as $item)
                                    <tr>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ number_format($item->quantity, 2) }}</td>
                                        <td>{{ $item->yarn_received_sum_quantity }}</td>
                                        <td>{{ Str::ucfirst($item->status) }}</td>

                                        <td>

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3">No data found!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- yarn Stock --}}
    <div class="row mb-4 mt-2">
        <div class="col-12">
            <div class="card border bg-white rounded-3 overflow-hidden">
                <div class="card-header bg-primary">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title text-white">Yarn Stock</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="default-table-area style-two">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Style</th>
                                        <th>Description</th>
                                        <th>Stock(kg)</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($yarnStock as $item)
                                    <tr>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ number_format($item->quantity, 2) }}</td>
                                        <td>{{ Str::ucfirst($item->status) }}</td>

                                        <td>

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3">No data found!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dyed quotations --}}
    <div class="row mb-4 mt-2">
        <div class="col-12">
            <div class="card border bg-white rounded-3 overflow-hidden">
                <div class="card-header bg-primary">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title text-white">Yarn Dyed Quotation</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="default-table-area style-two">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Style</th>
                                        <th>Description</th>
                                        <th>Quantity(kg)</th>
                                        <th>Distribute</th>
                                        <th>Loss</th>
                                        <th>Stock</th>
                                        <th>Available</th>
                                        <th>Approx. delivery_date</th>
                                        <th>Status</th>
                                        <th>Dyed Factory</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dyedQuotation as $item)
                                    @php
                                    $totalUse = $item->dyed_yarnknit_quot_sum_quantity +
                                    $item->dyed_yarn_loss_sum_quantity +
                                    $item->dyed_yarn_stock_sum_quantity;
                                    @endphp
                                    <tr>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->dyed_yarnknit_quot_sum_quantity }}</td>
                                        <td>{{ $item->dyed_yarn_loss_sum_quantity }}</td>
                                        <td>{{ $item->dyed_yarn_stock_sum_quantity }}</td>
                                        <td>{{ $item->quantity - $totalUse }}</td>
                                        <td>{{ $item->approximate_delivery_date }}</td>
                                        <td>{{ Str::ucfirst($item->status) }}</td>
                                        <td>{{ $item->dyedFactory->name }}</td>
                                        <td></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">No Data Found!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Knitting quotations --}}
    <div class="row mb-4 mt-2">
        <div class="col-12">
            <div class="card border bg-white rounded-3 overflow-hidden">
                <div class="card-header bg-primary">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title text-white">Knitting Quotation</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="default-table-area style-two">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Style</th>
                                        <th>Description</th>
                                        <th>Quantity(kg)</th>
                                        <th>Distribute</th>
                                        <th>Loss</th>
                                        <th>Stock</th>
                                        <th>Available</th>
                                        <th>Approx. delivery_date</th>
                                        <th>Status</th>
                                        <th>Yarn Type</th>
                                        <th>Netting Factory</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($nettings as $item)
                                    @php
                                    $totalUse = $item->netting_dyeing_quatiton_sum_quantity +
                                    $item->netting_garments_quotation_sum_quantity
                                    + $item->netting_loss_sum_quantity + $item->knit_store_stock_sum_quantity;
                                    @endphp
                                    <tr>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ ($item->netting_dyeing_quatiton_sum_quantity +
                                            $item->netting_garments_quotation_sum_quantity) ?? 0 }}</td>
                                        <td>{{ $item->netting_loss_sum_quantity ?? 0 }}</td>
                                        <td>{{ $item->knit_store_stock_sum_quantity ?? 0 }}</td>
                                        <td>{{ $item->quantity - $totalUse }}</td>
                                        <td>{{ $item->approximate_delivery_date }}</td>
                                        <td>{{ Str::ucfirst($item->status) }}</td>
                                        <td>{{$item->dyed_quotation_id ? 'Dyed Yarn' : 'Yarn' }}</td>
                                        <td>{{ $item->nettingFactory->name }}</td>
                                        <td>

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">No Data Found!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dyeing quotations --}}
    <div class="row mb-4 mt-2">
        <div class="col-12">
            <div class="card border bg-white rounded-3 overflow-hidden">
                <div class="card-header bg-primary">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title text-white">Dyeing Quotation</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="default-table-area style-two">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Style</th>
                                        <th>Description</th>
                                        <th>Quantity(kg)</th>
                                        <th>Distribute</th>
                                        <th>Loss</th>
                                        <th>Stock</th>
                                        <th>Available</th>
                                        <th>Approx. delivery_date</th>
                                        <th>Status</th>
                                        <th>Dyeing Factory</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dyeings as $item)
                                    @php
                                    $totalUse = $item->dyeing_garments_quot_sum_quantity +
                                    $item->dyeing_loss_sum_quantity +
                                    $item->dyeing_stock_sum_quantity;
                                    @endphp
                                    <tr>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->dyeing_garments_quot_sum_quantity }}</td>
                                        <td>{{ $item->dyeing_loss_sum_quantity }}</td>
                                        <td>{{ $item->dyeing_stock_sum_quantity }}</td>
                                        <td>{{ $item->quantity-$totalUse }}</td>
                                        <td>{{ $item->approximate_delivery_date }}</td>
                                        <td>{{ Str::ucfirst($item->status) }}</td>
                                        <td>{{ $item->dyeingFactory->name }}</td>
                                        <td>

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">No Data Found!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- garments quotations --}}
    <div class="row mb-4 mt-2">
        <div class="col-12">
            <div class="card border bg-white rounded-3 overflow-hidden">
                <div class="card-header bg-primary">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title text-white">Fabric</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="default-table-area style-two">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Style</th>
                                        <th>Description</th>
                                        <th>Quantity(kg)</th>
                                        <th>Approx. delivery_date</th>
                                        <th>Fabric Type</th>
                                        <th>Status</th>
                                        <th>Received Date</th>
                                        <th>Garments Factory</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($nettingGarments as $item)

                                    <tr>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->approximate_delivery_date ?? '--' }}</td>
                                        <td>{{ Str::ucfirst($item->fabric_type) }}</td>
                                        <td>{{ Str::ucfirst($item->status) }}</td>
                                        <td>{{ $item->received_date ?? '--' }}</td>
                                        <td>
                                            {{ $item->garmentsFactory->name }}
                                        </td>
                                        <td>

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">No Data Found!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex-grow-1"></div>

@endsection

@section('script')
<script>
    function showMoreItems(id){
        let item_section = $('#display_items_'+id);
        item_section.toggle();
    }
</script>
@endsection