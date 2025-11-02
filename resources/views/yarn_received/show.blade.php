@extends('layouts.master')
@section('title', 'Yarn Receive Item Details')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex">
            <h2 class="mb-0">Yarn Receive Item Details</h2>
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
                    <span class="fw-medium">Yarn Receive</span>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">Yarn Receive Item Details</span>
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
                            <td>{{ $yarnreceived->po_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Style</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->style }}</td>
                        </tr>
                        <tr>
                            <td><strong>Quantity</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->quantity }} {{ $yarnreceived->unit }}</td>
                        </tr>
                        <tr>
                            <td><strong>Lot number</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->lot_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Bag Count</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->bag_count }}</td>
                        </tr>
                        <tr>
                            <td><strong>Challan Date</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->challan_date }}</td>
                        </tr>
                        <tr>
                            <td><strong>Challan Number</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->challan_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Vehicle Number</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->vehicle_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->status }}</td>
                        </tr>
                        <tr>
                            <td><strong>Remarks</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->remarks ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Challan</strong></td>
                            <td>:</td>
                            <td>
                                <a href="{{ asset('storage/'.$yarnreceived->challan_file) }}" target="_blank"><img
                                        src="{{ asset('storage/'.$yarnreceived->challan_file) }}" alt="" width="50"></a>
                            </td>
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
                            <td><strong>Store</strong></td>
                            <td>:</td>
                            <td>
                                {{ $yarnreceived->yarnStore->name ?? '--' }}
                                <br>
                                {{ $yarnreceived->yarnStore->address ?? '--' }}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Yarn factory</strong></td>
                            <td>:</td>
                            <td>
                                {{ $yarnreceived->yarnFactory->name ?? '--' }}
                                <br>
                                {{ $yarnreceived->yarnFactory->address ?? '--' }}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Received Date</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->received_date ?? '--' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Received by</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->receivedBy->name ?? '--' }}</td>
                        </tr>

                        <tr>
                            <td><strong>Last Updated By</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->lastUpdateBy->name ?? '-'}}</td>
                        </tr>
                        <tr>
                            <td><strong>Created At</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->created_at ??'-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Updated At</strong></td>
                            <td>:</td>
                            <td>{{ $yarnreceived->updated_at ??'-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex-grow-1"></div>

@endsection