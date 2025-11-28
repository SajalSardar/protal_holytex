@extends('layouts.master')
@section('title', 'Netting Quotation List')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex">
            <h2 class="mb-0">Netting Quotation List </h2>
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
                    <span class="fw-medium">Netting Quotation List</span>
                </li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-0">
                    <div class="default-table-area style-two default-table-width">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>PO</th>
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
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->po_number }}</td>
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
                                        <td>{{$item->dyed_quotation_id || $item->stock_id ? 'Dyed Yarn' : 'Yarn' }}</td>
                                        <td>
                                            Name:{{ $item->nettingFactory->name }} <br>
                                            Address:{{ $item->nettingFactory->address }}

                                        </td>
                                        <td>
                                            <div class="dropdown text-end">
                                                <a class="btn btn-primary dropdown-toggle" href="#" role="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </a>

                                                <ul class="dropdown-menu dropdown-menu-end table_action_btn">
                                                    <li>
                                                        <a class="dropdown-item py-2"
                                                            href="{{ route('knit.distribute',$item->id) }}">
                                                            <i
                                                                class="material-symbols-outlined fs-16 text-body">edit</i>
                                                            Knit Distribute</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item py-2"
                                                            href="{{ route('nettingquotation.show', $item->id) }}"> <i
                                                                class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                                            View</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item py-2"
                                                            href="{{ route('nettingquotation.edit', $item->id) }}"><i
                                                                class="material-symbols-outlined fs-16 text-body">edit</i>
                                                            Edit</a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('nettingquotation.destroy',$item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" onclick="deleteAlert(this)"
                                                                class="dropdown-item py-2">
                                                                <i
                                                                    class="material-symbols-outlined fs-16 text-danger">delete</i>
                                                                Delete
                                                            </button>
                                                        </form>
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

@section('script')
<script>
    function deleteAlert(element) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $(element).parent('form').submit();
            }
        });
    }
</script>
@endsection