@php
$delived_factory_type = $nettingstorestock->delived_factory_type;
$title = $delived_factory_type == 'netting' ? 'Knit Store Details' : 'Dyeing Store Details';
@endphp

@extends('layouts.master')
@section('title')
{{ $title }}
@endsection
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex">
            <h2 class="mb-0">{{ $title }}</h2>
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
                    <span class="fw-medium">Order</span>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">{{ $title }}</span>
                </li>
            </ol>
        </nav>
    </div>
    <div class="row mt-5">
        <div class=" col-lg-4">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><strong>PO Number</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->po_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Style</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->style }}</td>
                        </tr>
                        <tr>
                            <td><strong>Description</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->yarnQuotations ?
                                $nettingstorestock->yarnQuotations->pluck('description')->map(fn($d) =>
                                trim($d))->join(", ") : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Quantity</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->quantity }} {{ $nettingstorestock->unit }}</td>
                        </tr>
                        <tr>
                            <td><strong>Knit Type</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->delived_factory_type }}</td>
                        </tr>
                        <tr>
                            <td><strong>Loat Number</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->lot_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Bag Count</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->bag_count ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Challan Date</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->challan_date ? $nettingstorestock->challan_date->format('d-m-Y')
                                : '-'
                                }}</td>
                        </tr>
                        <tr>
                            <td><strong>Challan Number</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->challan_number ?? '-'}}</td>
                        </tr>
                        <tr>
                            <td><strong>Vehicle Number</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->vehicle_number ?? '-'}}</td>
                        </tr>
                        <tr>
                            <td><strong>Received Date</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->received_date ?
                                $nettingstorestock->received_date->format('d-m-Y') :
                                '-'
                                }}</td>
                        </tr>
                        <tr>
                            <td><strong>Store Address</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->store_address ??'-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Remarks</strong></td>
                            <td>:</td>
                            <td>{{ $nettingstorestock->remarks ??'-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Challan</strong></td>
                            <td>:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                @if ($nettingstorestock->challan_file)
                                <a href="{{ asset('storage/'.$nettingstorestock->challan_file) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$nettingstorestock->challan_file) }}" alt=""
                                        width="100%">
                                </a>
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        {{-- <div class=" col-lg-8">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-header">
                    <h3 class="card-title">Uses This Stock</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>PO</th>
                                    <th>Style</th>
                                    <th>Quantity(kg)</th>
                                    <th>Received Date</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($usesStock as $uses_stock)
                                <tr>
                                    <td>{{ $uses_stock->id }}</td>
                                    <td>{{ $uses_stock->po_number }}</td>
                                    <td>{{ $uses_stock->style }}</td>
                                    <td>{{ $uses_stock->quantity }} {{ $uses_stock->unit }}</td>
                                    <td>{{ $uses_stock->received_date }}</td>
                                    <td>{{ $uses_stock->created_at->format('d-m-Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="alert alert-info">
                                            <p>No data Found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="paginate mt-3">
                        {{ $usesStock->links() }}
                    </div>
                </div>

            </div>
        </div> --}}
    </div>
</div>

<div class="flex-grow-1"></div>

@endsection