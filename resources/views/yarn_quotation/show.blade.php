@extends('layouts.master')
@section('title', 'Yarn Quotaion Item Details')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex">
            <h2 class="mb-0">Yarn Quotaion Item Details</h2>
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
                    <span class="fw-medium">Yarn Quotation</span>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">Yarn Quotaion Item Details</span>
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
                            <td>{{ $yarnquotation->po_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Style</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->style }}</td>
                        </tr>
                        <tr>
                            <td><strong>Description</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->description}}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Quantity</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->quantity }} {{ $yarnquotation->unit }}</td>
                        </tr>
                        <tr>
                            <td><strong>From Stock Quantity</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->from_stock_quantity ?? '00' }} {{ $yarnquotation->unit }}</td>
                        </tr>
                        <tr>
                            <td><strong>Price</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->price }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Price</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->total_price }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->status }}</td>
                        </tr>
                        <tr>
                            <td><strong>Remarks</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->remarks ?? '--' }}</td>
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
                            <td><strong>Recever Factory Type</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->receving_factory ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Order Date</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->order_date ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Approximate Delivery Date</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->approximate_delivery_date ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Delivery Date</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->delivery_date ?? '--' }}</td>
                        </tr>

                        <tr>
                            <td><strong>Created By</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->creator->name ?? '-'}}</td>
                        </tr>
                        <tr>
                            <td><strong>Last Updated By</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->lastUpdateBy->name ?? '-'}}</td>
                        </tr>
                        <tr>
                            <td><strong>Approved By</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->approvedBy->name}}</td>
                        </tr>
                        <tr>
                            <td><strong>Created At</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->created_at ??'-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Updated At</strong></td>
                            <td>:</td>
                            <td>{{ $yarnquotation->updated_at ??'-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex-grow-1"></div>

@endsection