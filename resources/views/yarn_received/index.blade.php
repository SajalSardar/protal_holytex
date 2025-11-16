@extends('layouts.master')
@section('title', 'Yarn Received')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex">
            <h2 class="mb-0">Yarn Stock</h2>
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
                    <span class="fw-medium">Yarn Received</span>
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
                                        <th>PO</th>
                                        <th>Style</th>
                                        <th>Description</th>
                                        <th>Total(kg)</th>
                                        <th>Dyed</th>
                                        <th>Knitte</th>
                                        <th>Available</th>
                                        <th>Unit</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($yarnReceived as $item)

                                    <tr>
                                        <td>{{ $item->po_number }}</td>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->total_quantity }}</td>
                                        <td>{{ $item->dyed_total }}</td>
                                        <td>{{ $item->knit_total }}</td>
                                        <td>{{ number_format($item->total_quantity - ($item->dyed_total +
                                            $item->knit_total), 2) }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>
                                            <div class="dropdown text-end">
                                                <a class="btn btn-primary dropdown-toggle" href="#" role="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </a>

                                                <ul class="dropdown-menu dropdown-menu-end table_action_btn">
                                                    <li>
                                                        <a class="dropdown-item py-2"
                                                            href="{{ route('yarnreceived.distribute',['po_number'=>$item->po_number,'style'=>$item->style,'description'=>$item->description]) }}">
                                                            <i
                                                                class="material-symbols-outlined fs-16 text-body">edit</i>
                                                            Yarn Distribute</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item py-2"
                                                            href="{{ route('yarnreceived.detail.view',['po_number'=>$item->po_number,'style'=>$item->style,'description'=>$item->description] ) }}">
                                                            <i
                                                                class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                                            Details</a>
                                                    </li>

                                                </ul>
                                            </div>
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