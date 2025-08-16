@extends('layouts.master')
@section('title', 'Yarn Quotation List')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex">
            <h2 class="mb-0">Yarn Quotation List </h2>
            <a href="{{ route('yarnquotation.create') }}" class="ms-5 btn btn-primary py-2 px-4 fw-medium fs-16">+
                Create Yarn
                Quotation</a>
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
                    <span class="fw-medium">Yarn Quotation List</span>
                </li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="default-table-area style-two default-table-width">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Order Number</th>
                                        <th>PO</th>
                                        <th>Style</th>
                                        <th>Quantity(kg)</th>
                                        <th>Total(TK)</th>
                                        <th>Approx. delivery date</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($yearnList as $key=>$poItems)
                                    @foreach ($poItems as $items)
                                    @php
                                    $item = $items->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $item->order_number }}</td>
                                        <td>{{ $item->po_number }}</td>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ number_format($items->sum('quantity'), 2) }}</td>
                                        <td>{{ number_format($items->sum('total_price'), 2) }}</td>
                                        <td>{{ $item->approximate_delivery_date }}</td>
                                        <td>{{ Str::ucfirst($item->status) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1 justify-content-end">
                                                <button
                                                    class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                    <i
                                                        class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                                </button>
                                                <button
                                                    class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                    <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                                </button>
                                                <button
                                                    class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                    <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
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
</div>

<div class="flex-grow-1"></div>
@endsection