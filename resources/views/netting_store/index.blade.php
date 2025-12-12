@extends('layouts.master')
@section('title', 'Knit Store')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex">
            <h2 class="mb-0">Knit Store</h2>

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
                    <span class="fw-medium">Knit Store</span>
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
                                        <th>Chalan</th>
                                        <th>Description</th>
                                        <th>PO</th>
                                        <th>Style</th>
                                        <th>Quantity(kg)</th>
                                        <th>Unit</th>
                                        <th>Received Date</th>
                                        <th>Status</th>
                                        <th>Store Address</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($rowNettingstock as $item)
                                    <tr>
                                        <td>
                                            @if ($item->challan_file)
                                            <img src="{{ asset('storage/'.$item->challan_file) }}" alt="" width="50">
                                            @else
                                            --
                                            @endif
                                        </td>
                                        <td width="200">
                                            {{ $item->description }}
                                        </td>
                                        <td>{{ $item->po_number }}</td>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>{{ $item->received_date }}</td>
                                        <td>{{ Str::ucfirst($item->status) }}</td>
                                        <td>Name:{{ $item->storeAddress->name }} <br> Address:{{
                                            $item->storeAddress->address }}</td>
                                        <td>
                                            @php
                                            $itemjson = [
                                            'id' => $item->id,
                                            'po_number' => $item->po_number,
                                            'description' =>$item->description,
                                            'style' => $item->style,
                                            'quantity' => $item->quantity,
                                            'store_address' => $item->store_address,
                                            ];
                                            @endphp
                                            <div class="dropdown text-end">
                                                <a class="btn btn-primary dropdown-toggle" href="#" role="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </a>

                                                <ul class="dropdown-menu dropdown-menu-end table_action_btn">
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('nettingstorestock.knit.distribute.create',$item->id) }}">
                                                            <i
                                                                class="material-symbols-outlined fs-16 text-primary">contact_page</i>
                                                            Use Stock</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('nettingstorestock.show', $item->id) }}"> <i
                                                                class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                                            View</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('nettingstorestock.edit', $item->id) }}"><i
                                                                class="material-symbols-outlined fs-16 text-body">edit</i>
                                                            Edit</a></li>
                                                    <li>
                                                        <form
                                                            action="{{ route('nettingstorestock.destroy', $item->id) }}"
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
    

    function showStockModal(item){
        const modalEl = document.getElementById('stock_change_modal');
        const myModal = new bootstrap.Modal(modalEl, {
            keyboard: false
        });
        myModal.show();

        let stockItem = JSON.parse(item);

        $('#show_po_number').val(stockItem.po_number);
        $('#show_description').val(stockItem.description);
        $('#show_style').val(stockItem.style);
        $('#show_quantity').val(stockItem.quantity);
        $('#show_stock_address').val(stockItem.store_address);
        $('#stock_id').val(stockItem.id);

        $('#use_po_number').select2({
            dropdownParent: $('#stock_change_modal')
        });
    }

    function setStockQuantity(element) {
        $('#input_yarn').val('');
        $('#input_loss').val('');
        $('#yarn_quotation_id').val(element.value);
        let from_stock = $('#use_po_number option:selected').data('from_stock');
        let received_stock = $('#use_po_number option:selected').data('received_stock');

        from_stock = parseFloat(from_stock) || 0;
        received_stock = parseFloat(received_stock) || 0;

        let no_received = from_stock - received_stock;
        $('#from_stock_value').val(from_stock);
        $('#from_stock_received_value').val(received_stock);
        $('#from_stock_no_received_value').val(no_received);
    }

    function limitWeightValue(element){
         element.value = element.value.replace(/^(\d*\.?\d{0,2}).*$/, '$1');

        let inputYarn = $('#input_yarn').val();
        let inputYarnLoss = $('#input_loss').val();
        let totalVal = (Number(inputYarn) || 0) + (Number(inputYarnLoss) || 0);
        
        let show_quantity= $('#show_quantity').val();
        let from_stock_no_received_value= $('#from_stock_no_received_value').val();

        let show_quantity_value = parseFloat(show_quantity) || 0;
        let no_received_value = parseFloat(from_stock_no_received_value) || 0;
        
        if (totalVal > show_quantity_value) {
            alert(`This Stock Quantity ${show_quantity_value}Kg.`);
            element.value = 0;
            return;
        }

        if(totalVal > no_received_value){
             alert(`No received quantity is ${no_received_value}Kg`);
             element.value = 0;
            return;
        }
    }


    function resetSelect(id) {
        $('#'+id).val(null).trigger('change');
    }
</script>
@endsection