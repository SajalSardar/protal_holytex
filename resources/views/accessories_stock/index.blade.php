@extends('layouts.master')
@section('title', 'Accessories Stock')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex">
            <h2 class="mb-0">Accessories Stock</h2>
            <a href="{{ route('accessoriesstock.create') }}" class="ms-5 btn btn-primary py-2 px-4 fw-medium fs-16">+
                Create Stock</a>
            <a href="{{ route('accessoriesreceived.create') }}" class="ms-5 btn btn-primary py-2 px-4 fw-medium fs-16">+
                Receive Goods in Stock</a>
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
                    <span class="fw-medium">Accessories Stock</span>
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
                                    @forelse ($accStock as $item)
                                    <tr>
                                        <td>
                                            @if ($item->challan_file)
                                            <img src="{{ asset('storage/'.$item->challan_file) }}" alt="" width="50">
                                            @else
                                            --
                                            @endif
                                        </td>
                                        <td>{{ $item->accessoriesQty->description ?? ($item->description ?? '--') }}
                                        </td>
                                        <td>{{ $item->po_number }}</td>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>{{ $item->received_date }}</td>
                                        <td>{{ Str::ucfirst($item->status) }}</td>
                                        <td>{{ $item->store_address }}</td>
                                        <td>
                                            <div class="dropdown text-end">
                                                <a class="btn btn-primary dropdown-toggle" href="#" role="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </a>

                                                <ul class="dropdown-menu dropdown-menu-end table_action_btn">
                                                    {{-- <li><a class="dropdown-item py-2" href="#"
                                                            onclick="showStockModal('{{ json_encode($itemjson) }}')">
                                                            <i
                                                                class="material-symbols-outlined fs-16 text-primary">contact_page</i>
                                                            Use Stock</a></li> --}}
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('accessoriesstock.show', $item->id) }}">
                                                            <i
                                                                class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                                            View</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('accessoriesstock.edit', $item->id) }}"><i
                                                                class="material-symbols-outlined fs-16 text-body">edit</i>
                                                            Edit</a></li>
                                                    <li>
                                                        <form
                                                            action="{{ route('accessoriesstock.destroy', $item->id) }}"
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

<!-- Modal -->
<div class="modal fade" id="stock_change_modal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('nettingstorestock.store') }}" method="POST">
            @csrf
            <input type="hidden" id="yarn_quotation_id" name="yarn_quotation_id">
            <input type="hidden" id="stock_id" name="stock_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Use This Yarn Stock</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <label for="">PO Number</label>
                                <input type="text" class="form-control" name="po_number" id="show_po_number"
                                    readonly="">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-2">
                                <label for="">Description</label>
                                <input type="text" class="form-control" name="description" id="show_description"
                                    readonly="">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-2">
                                <label for="">Style</label>
                                <input type="text" class="form-control" name="style" id="show_style" readonly="">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-2">
                                <label for="">Quantity</label>
                                <input type="text" class="form-control" name="quantity" id="show_quantity" readonly="">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-2">
                                <label for="">Stok Address</label>
                                <input type="text" class="form-control" name="stock_address" id="show_stock_address"
                                    readonly="">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <label class="label text-secondary d-block">Receiving PO Number & Style & Description
                                    <span style="color: rgb(205, 2, 2)">*</span></label>
                                <select name="use_po_number" style="width: 100%" id="use_po_number"
                                    onchange="setStockQuantity(this)"
                                    class=" @error('use_po_number') is-invalid @enderror">
                                    <option value="" selected disabled>Select PO Number</option>
                                    {{-- @foreach ($nettingQot as $item)
                                    <option value="{{ $item->id }}" data-from_stock="{{ $item->from_stock_quantity }}"
                                        data-received_stock={{ $item->netting_received_from_stock_sum_quantity ?? 0 }}>
                                        {{ $item->po_number }} - {{ $item->style }}</option>
                                    @endforeach --}}
                                </select>
                                @error('use_po_number')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group mb-2">
                                <label class="label text-secondary d-block">Qotation From Stock</label>
                                <input type="text" class="form-control" id="from_stock_value" value="" readonly>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group mb-2">
                                <label class="label text-secondary d-block">Received From
                                    Stock</label>
                                <input type="text" class="form-control" value="" id="from_stock_received_value"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group mb-2">
                                <label class="label text-secondary d-block">No
                                    Received</label>
                                <input type="text" class="form-control" value="" id="from_stock_no_received_value"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 mb-3">
                            <h3>CHALLAN INFO</h3>
                        </div>
                        <div class="col-lg-3 col-sm-4">
                            <div class="form-group">
                                <label class="label text-secondary">Challan No.</label>
                                <input type="text" name="challan_number" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4">
                            <div class="form-group">
                                <label class="label text-secondary">Vehicle Number</label>
                                <input type="text" name="vehicle_number" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4">
                            <div class="form-group">
                                <label class="label text-secondary">Challan Date</label>
                                <input type="date" name="challan_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4">
                            <div class="form-group">
                                <label class="label text-secondary">Received Date</label>
                                <input type="date" name="received_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-4">
                            <div class="form-group">
                                <label class="label text-secondary">Upload Challan</label>
                                <input type="file" name="challan_file" class="form-control">
                                <p class="fs-12">Uploaded file size 512kb & File type jpg,png </p>
                                @error('challan_file')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h3 class="text-primary">Receive Quantity(Yarn, Loss)</h3>
                        </div>
                        <div class="col-lg-2 pe-0 mb-3">
                            <label class="label text-secondary">Lot No.</label>
                            <input type="text" class="form-control"
                                oninput="this.value = this.value.replace(/^(\d*\.?\d{0,2}).*$/,'$1')" name="loat_no">
                        </div>
                        <div class="col-lg-2 pe-0 mb-3">
                            <label class="label text-secondary">Bags</label>
                            <input type="text" class="form-control"
                                oninput="this.value = this.value.replace(/^(\d*\.?\d{0,2}).*$/,'$1')" name="bag_count">
                        </div>
                        <div class="col-lg-2 pe-0 mb-3">
                            <label class="label text-secondary">Yarn(KG)</label>
                            <input type="text" id="input_yarn" oninput="limitWeightValue(this)" class="form-control"
                                name="input_yarn">
                        </div>
                        <div class="col-lg-2 pe-0 mb-3">
                            <label class="label text-secondary">Loss(KG)</label>
                            <input type="text" class="form-control" oninput="limitWeightValue(this)" id="input_loss"
                                name="input_loss">
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="label text-secondary">Remarks</label>
                            <textarea rows="1" class="form-control" name="remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary text-white"
                        onclick="this.disabled=true; this.innerHTML='Saving…'; this.form.submit();">Stock
                        Received</button>
                </div>
            </div>
        </form>
    </div>
</div>

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