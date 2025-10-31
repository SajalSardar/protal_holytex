@extends('layouts.master')
@section('title', 'Accessories Quotation Item Details')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex">
            <h2 class="mb-0">Accessories Quotation Item Details</h2>
        </div>

        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                        <span class="text-secondary fw-medium hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">Accessories Quotation</span>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">Accessories Quotation Item Details</span>
                </li>
            </ol>
        </nav>
    </div>
    <div class="row mt-5">
        <div class=" col-lg-6">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><strong>PO Number</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->po_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Style</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->style }}</td>
                        </tr>
                        <tr>
                            <td><strong>Description</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->description}}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Quantity</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->quantity }} {{ $accessoriesquotation->unit }}</td>
                        </tr>
                        <tr>
                            <td><strong>From Stock Quantity</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->from_stock_quantity ?? '00' }} {{ $accessoriesquotation->unit
                                }}</td>
                        </tr>
                        <tr>
                            <td><strong>Price</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->price }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Price</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->total_price }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->status }}</td>
                        </tr>
                        <tr>
                            <td><strong>Remarks</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->remarks ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Supplier Name</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->supplier_name ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Supplier Phone</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->supplier_phone ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Supplier Address</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->supplier_address ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Shiphing Address</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->shiphing_address ?? '--' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class=" col-lg-6">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><strong>Order Date</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->purchase_date ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Approximate Delivery Date</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->approximate_delivery_date ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Delivery Date</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->delivery_date ?? '--' }}</td>
                        </tr>

                        <tr>
                            <td><strong>Created By</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->creator->name ?? '-'}}</td>
                        </tr>
                        <tr>
                            <td><strong>Last Updated By</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->lastUpdateBy->name ?? '-'}}</td>
                        </tr>
                        <tr>
                            <td><strong>Approved By</strong></td>
                            <td>:</td>
                            <td>{{ @$accessoriesquotation->approvedBy->name}}</td>
                        </tr>
                        <tr>
                            <td><strong>Created At</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->created_at ??'-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Updated At</strong></td>
                            <td>:</td>
                            <td>{{ $accessoriesquotation->updated_at ??'-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex-grow-1"></div>

@endsection