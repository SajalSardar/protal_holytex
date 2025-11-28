@extends('layouts.master')
@section('title', 'Dyed Yarn Store')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex">
            <h2 class="mb-0">Dyed Yarn Store</h2>
            {{-- <a href="{{ route('yarnstorestock.create',['delived_factory_type' => 'yarn']) }}"
                class="ms-5 btn btn-primary py-2 px-4 fw-medium fs-16">+
                Create Stock</a>
            <a href="{{ route('yarnreceived.create') }}" class="ms-5 btn btn-primary py-2 px-4 fw-medium fs-16">+
                Receive Stock</a> --}}
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
                    <span class="fw-medium">Dyed Yarn Store</span>
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
                                        <th>Chalan</th>
                                        <th>PO</th>
                                        <th>Description</th>
                                        <th>Style</th>
                                        <th>Quantity</th>
                                        <th>Distribute</th>
                                        <th>Loss</th>
                                        <th>Available</th>
                                        <th>Unit</th>
                                        <th>Received Date</th>
                                        <th>Status</th>
                                        <th>Store Address</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($yarnStocks as $item)
                                    <tr>
                                        <td>
                                            @if ($item->challan_file)
                                            <img src="{{ asset('storage/'.$item->challan_file) }}" alt="" width="50">
                                            @else
                                            --
                                            @endif
                                        </td>
                                        <td>{{ $item->po_number }}</td>
                                        <td>{{ $item->description ?? '--' }}</td>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->use_stock_sum_quantity ?? 0 }}</td>
                                        <td>{{ $item->use_stock_loss_sum_quantity ?? 0 }}</td>
                                        <td>{{ number_format($item->quantity - ( $item->use_stock_sum_quantity+
                                            $item->use_stock_loss_sum_quantity), 2) }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>{{ $item->received_date ? $item->received_date->format('d-m-Y') : '-' }}
                                        </td>
                                        <td>{{ Str::ucfirst($item->status) }}</td>
                                        <td>
                                            Name:{{ $item->storeDetails->name }}
                                        </td>
                                        <td>
                                            @php
                                            $itemjson = [
                                            'id' => $item->id,
                                            'po_number' => $item->po_number,
                                            'description' => $item->description ?? '',
                                            'style' => $item->style,
                                            'quantity' => $item->quantity,
                                            'store_address' => $item->storeDetails->name,
                                            ];
                                            @endphp
                                            <div class="dropdown text-end">
                                                <a class="btn btn-primary dropdown-toggle" href="#" role="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </a>

                                                <ul class="dropdown-menu dropdown-menu-end table_action_btn">
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('use.yarn.stock.create',$item->id ) }}">
                                                            <i
                                                                class="material-symbols-outlined fs-16 text-primary">contact_page</i>
                                                            Use Stock</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('yarnstorestock.show', $item->id) }}"> <i
                                                                class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                                            View</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('yarnstorestock.edit', $item->id) }}"><i
                                                                class="material-symbols-outlined fs-16 text-body">edit</i>
                                                            Edit</a></li>
                                                    <li>
                                                        <form action="{{ route('yarnstorestock.destroy', $item->id) }}"
                                                            method="POST" onclick="deleteAlert(this)">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item py-2">
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
    $(function(){
        $('.select2').select2();
    });

    function deleteAlert(form){
        Swal.fire({
            title: "Are you sure?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
    
    function resetSelect(id) {
        $('#'+id).val(null).trigger('change');
    }
</script>
@endsection