@extends('layouts.master')
@section('title', 'Distribute yarn')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Distribute yarn</h2>

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
                    <span class="fw-medium">Distribute yarn</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <form action="{{ route('yarnreceived.distribute.store') }}" method="POST"
                        enctype="multipart/form-data" id="netting_form">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="yarnreceived_id" value="{{ $yarnreceived->id }}">
                            <input type="hidden" name="order_id" value="{{ $orderDetail->order_id }}">
                            <input type="hidden" name="style" value="{{ $yarnreceived->style }}">
                            <input type="hidden" name="description" value="{{ $yarnreceived->description }}">
                            <input type="hidden" name="from_store_id" value="{{ $yarnreceived->yarnStore->id }}">
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number</label>
                                    <input name="po_number" id="po_number"
                                        value="{{ old('po_number', $yarnreceived->po_number) }}" class="form-control"
                                        readonly>
                                    @error('po_number')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Date</label>
                                    <input type="date" value="{{ old('order_date') }}" class="form-control"
                                        name="order_date">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Approximate Delivery Date</label>
                                    <input type="date" class="form-control"
                                        value="{{ old('approximate_delivery_date') }}" name="approximate_delivery_date">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Remarks</label>
                                    <textarea class="form-control" name="remarks"
                                        rows="1">{{ old('remarks') }}</textarea>
                                </div>
                            </div>

                            <hr>

                            <div id="show_all_yarn_item">
                                <div class="card border-0 rounded-3 mb-5">
                                    <div class="card-header bg-primary">
                                        <h3 class="text-white" style="text-transform:uppercase">Style: {{
                                            $yarnreceived->style }}</h3>
                                    </div>
                                    <div class="card-body">
                                        @php
                                        $dyedQuotations =
                                        number_format($yarnreceived->dyedQuotations->sum('quantity'),2);

                                        $knitQuotations =
                                        number_format($yarnreceived->KnitQuotations->sum('quantity'),2);

                                        $totalQut = $dyedQuotations + $knitQuotations;
                                        @endphp
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Description</th>
                                                    <th>Quantity(KG)</th>
                                                    <th>Quotation Quantity(KG)</th>
                                                    <th>Allowed Quantity</th>
                                                    <th>Store Address</th>
                                                </tr>

                                                <tr>
                                                    <td>{{ $yarnreceived->description }}</td>
                                                    <td>{{ $yarnreceived->quantity }} {{ $yarnreceived->unit }}
                                                        <input type="hidden"
                                                            value="{{ $yarnreceived->quantity - $totalQut }}"
                                                            id="total_stock_quantity" name="total_stock_quantity">
                                                    </td>
                                                    <td>
                                                        {{ $totalQut ?? 0 }}kg
                                                    </td>
                                                    <td>{{ number_format($yarnreceived->quantity - $totalQut,2) }}kg
                                                    </td>
                                                    <td>Name:{{ $yarnreceived->yarnStore->name }} <br> Address:{{
                                                        $yarnreceived->yarnStore->address }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Quantity(KG)</label>
                                                    <input type="text" class="form-control quantity"
                                                        id="yarn_quantity_1" name="items[1][quantity]"
                                                        oninput="calculateQuantity(this,1)">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Rate(TK)</label>
                                                    <input type="number" class="form-control"
                                                        oninput="attachRateCalculation(this,1)" id="price_1" min="1"
                                                        name="items[1][price]">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Total</label>
                                                    <input type="number" class="form-control" id="total_amount_1"
                                                        readonly name="items[1][total_amount]">

                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <p class="label text-secondary">Delivery factory</p>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label>
                                                            <input type="radio" class="form-check-input"
                                                                style="border:1px solid #000"
                                                                name="items[1][delivery_poin_check]" value="knit"
                                                                onclick="showHideDeliveryPoint(this,1)">
                                                            Knit</label>
                                                        <label class="ms-3">
                                                            <input type="radio" class="form-check-input"
                                                                style="border:1px solid #000"
                                                                name="items[1][delivery_poin_check]" value="yarn_dyed"
                                                                onclick="showHideDeliveryPoint(this,1)">
                                                            Yarn Dyed</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-3 d-none" id="knit_factory_1">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary d-block">Delivery Point</label>
                                                    <select class="form-control select2"
                                                        name="items[1][knit_factory_id]">
                                                        <option value="" selected disabled>Knit Factory
                                                        </option>
                                                        @foreach ($knitFactory as $items)
                                                        <option value="{{ $items->id }}">{{ $items->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-3 d-none" id="yarn_dyed_factory_1">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary d-block">Delivery Point</label>
                                                    <select class="form-control select2"
                                                        name="items[1][dyed_factory_id]">
                                                        <option value="" selected disabled>Dyed Factory
                                                        </option>
                                                        @foreach ($dyedFactory as $items)
                                                        <option value="{{ $items->id }}">{{ $items->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="add_new_rows_section"></div>
                                        <hr>
                                        <div class="col-sm-2 align-self-center mb-3">
                                            <button type="button" id="add_new_row_btn" onclick="addNewRows()"
                                                class="btn btn-warning py-2 px-2 fw-medium fs-14">Add row</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="col-lg-12 mt-5">
                                <div class="d-flex flex-wrap gap-3">
                                    <button type="submit" id="submit_button"
                                        onclick="this.disabled=true; this.innerHTML='Saving…'; this.form.submit();"
                                        class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                            class="ri-add-line text-white fw-medium"></i> Create</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex-grow-1"></div>
@endsection


@section('script')
<script>
    $(function() {
        $('.select2').select2();

    });


    function attachRateCalculation(element,classPrefx) {
        let quantityIdIn = document.getElementById('yarn_quantity_'+classPrefx);
        let totalInput = document.getElementById('total_amount_'+classPrefx);
        let rate_ = document.getElementById('price_'+classPrefx);
        // console.log(classPrefx);
        
        let qty = parseFloat(quantityIdIn.value) || 0;
        let rate = parseFloat(rate_.value) || 0;
        
        let totalValue = (qty * rate).toFixed(2);
        totalInput.value = totalValue; 

    }


    function calculateQuantity (element,classPrefx){
        let total_stock_quantity = $('#total_stock_quantity').val();
        let stock_quantity = parseFloat(total_stock_quantity) || 0;

        let total = 0;

        $('.quantity').each(function () {
            let val = parseFloat($(this).val()) || 0;
            total += val;
        });

        if(stock_quantity < total){
            $(element).val('');
            $('#add_new_row_btn').prop('disabled', true);
            alert(`Max allowed is ${stock_quantity}Kg`);
        }else{
            $('#add_new_row_btn').prop('disabled', false);
        }

        if(stock_quantity == total){
            $('#add_new_row_btn').prop('disabled', true);
        }else{
            $('#add_new_row_btn').prop('disabled', false);
        }

        attachRateCalculation(element,classPrefx);
    }

    function showHideDeliveryPoint(element,index) {

        // if (!element.checked) return; 

        let value = element.value;
        if (value === "knit") {
            $('#yarn_dyed_factory_'+index).addClass('d-none');
            $('#knit_factory_'+index).removeClass('d-none');
        } 
        else if (value === "yarn_dyed") {
            $('#knit_factory_'+index).addClass('d-none');
            $('#yarn_dyed_factory_'+index).removeClass('d-none');
           
        }
    }
    

    let rowIndex = 2;
    function addNewRows(){
        let newRow = `
            <div class="row" id="row_${rowIndex}">
                <div class='col-12'><hr></div>
                <div class="col-sm-2 col-lg-3">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Quantity(KG)</label>
                        <input type="text" class="form-control quantity" oninput="calculateQuantity(this,${rowIndex})" id="yarn_quantity_${rowIndex}" name="items[${rowIndex}][quantity]">
                    </div>
                </div>
                <div class="col-sm-2 col-lg-3">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Rate(TK)</label>
                        <input type="number" class="form-control"
                            oninput="attachRateCalculation(this,${rowIndex})"
                            id="price_${rowIndex}" min="1" name="items[${rowIndex}][price]">
                    </div>
                </div>
                <div class="col-sm-2 col-lg-3">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Total</label>
                        <input type="number" class="form-control" id="total_amount_${rowIndex}" readonly name="items[${rowIndex}][total_amount]">

                    </div>
                </div>
                <div class="col-sm-2">
                    <p class="label text-secondary">Delivery factory</p>
                    <div class="row">
                        <div class="form-group  mb-4">
                            <label>
                                <input type="radio" class="form-check-input"
                                    style="border:1px solid #000" name="items[${rowIndex}][delivery_poin_check]"
                                    value="knit" onclick="showHideDeliveryPoint(this,${rowIndex})">
                                Knit</label>
                            <label class="ms-3">
                                <input type="radio" class="form-check-input"
                                    style="border:1px solid #000" name="items[${rowIndex}][delivery_poin_check]"
                                    value="yarn_dyed" onclick="showHideDeliveryPoint(this,${rowIndex})">
                                Yarn Dyed</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-3 d-none" id="knit_factory_${rowIndex}">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Delivery Point</label>
                        <select class="form-control" name="items[${rowIndex}][knit_factory_id]" id="knit_factory_select_${rowIndex}">
                            <option value="" selected disabled>Knit Factory
                            </option>
                            @foreach ($knitFactory as $items)
                                <option value="{{ $items->id }}">{{ $items->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-3 d-none" id="yarn_dyed_factory_${rowIndex}">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Delivery Point</label>
                        <select class="form-control" name="items[${rowIndex}][dyed_factory_id]" id="yarn_dyed_factory_select_${rowIndex}">
                            <option value="" selected disabled>Dyed Factory
                            </option>
                            @foreach ($dyedFactory as $items)
                                <option value="{{ $items->id }}">{{ $items->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-1 align-self-center">
                    <button type="button" class="btn btn-danger remove-row text-white" data-row="#row_${rowIndex}">
                        Remove
                    </button>
                </div>
            </div>
        `;

        $("#add_new_rows_section").append(newRow);

        $("#yarn_dyed_factory_select_"+rowIndex).select2();
        $("#knit_factory_select_"+rowIndex).select2();

        rowIndex++;
    }

    $(document).on('click', '.remove-row', function () {
        let rowId = $(this).data('row');
        $(rowId).remove();
    });

</script>
@endsection