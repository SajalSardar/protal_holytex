@extends('layouts.master')
@section('title', 'Accessories Quotation List')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex">
            <h2 class="mb-0">Accessories Quotation List </h2>
            <a href="{{ route('accessoriesquotation.create') }}"
                class="ms-5 btn btn-primary py-2 px-4 fw-medium fs-16">+ Create
                Accessories Quotation</a>
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
                    <span class="fw-medium">Accessories Quotation List</span>
                </li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="default-table-area all-products">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Order Number</th>
                                        <th>PO</th>
                                        <th>Quantity(kg)</th>
                                        <th>Total(TK)</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @forelse ($yearnList as $item) --}}
                                    <tr>
                                        {{-- <td>{{ $item->order_number }}</td>
                                        <td>{{ $item->po_number }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->total_price }}</td>
                                        <td>{{ Str::ucfirst($item->status) }}</td> --}}
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
                                    {{-- @empty

                                    @endforelse --}}
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