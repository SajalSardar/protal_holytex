@extends('layouts.master')
@section('title', 'Dyed Quotation')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Dyed Quotation</h2>

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
                    <span class="fw-medium">Dyed Quotation</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <form action="{{ route('dyedquotation.store') }}" method="POST" enctype="multipart/form-data"
                        id="netting_form">
                        @csrf
                        <div class="row">
                            <input type="hidden" id="order_id" name="order_id">
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number <span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <select name="po_number" id="po_number" value="{{ old('po_number') }}"
                                        class="form-control select2  @error('po_number') is-invalid @enderror">
                                        <option value="" selected disabled>Select PO Number</option>
                                        @foreach ($yearns as $item)
                                        <option value="{{ $item->po_number }}">{{ $item->po_number }}</option>
                                        @endforeach
                                    </select>
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
                                    <label class="label text-secondary">Order number</label>
                                    <input type="text" value="{{ old('order_number') }}" class="form-control"
                                        id="order_number" name="order_number" readonly>
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

                            <div id="show_all_yarn_item"></div>

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


        $('#po_number').on('change',function(){
            var po_number = $(this).val();

            if (po_number) {
                // Optional: show a loading message
                // console.log('Fetching data for PO:', po_number);

                fetch(`/get-yarn-style-by-po-dyed/${encodeURIComponent(po_number)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                }).then(data => {
                    // console.log('API response:', data);
                    let display_div = $('#show_all_yarn_item');
                    let order_id = null;
                    let order_number = null;
                    let singleItem = '';

                    if(data.message){
                        singleItem += `
                            <div class="alert alert-info">
                                <p>${data.message}</p>
                            </div>
                        `;
                        display_div.html(singleItem);
                        return;
                    }

                    // Append new options
                    Object.entries(data).forEach(([key, style]) => {
                    Object.entries(style).forEach(([keyFa, factoryId]) => {
                        let total_quantity = 0;
                        let netting_factory_id = null;
                        singleItem +=`<div class="card border-0 rounded-3 mb-5">
                                        <div class="card-header bg-primary">
                                             <h3 class="text-white" style="text-transform:uppercase">Style: ${key}</h3>
                                        </div>
                                        <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <th>Description</th>
                                                <th>Quantity(KG)</th>
                                                <th>Yarn Factory</th>
                                                <th>Dyed Factory</th>
                                            </tr>`;
                        factoryId.forEach(item => {
                            totalQty = parseFloat(item.quantity || 0) + parseFloat(item.from_stock_quantity || 0)
                            total_quantity += totalQty;
                            order_number = item.order_number;
                            order_id = item.order_id;
                            dyed_factory_id =item.dyed_factory.id;
                            singleItem += `
                            
                                <tr>
                                    <td>${item.description}
                                        <input type="hidden" value="${item.description}" name="items[${key}][${keyFa}][description]">
                                    </td>
                                    <td>${totalQty}</td>
                                    <td>Name:${item.yarn_factory.name} <br> Address:${item.yarn_factory.address}</td>
                                    <td>Name:${item.dyed_factory.name}<br> Address:${item.dyed_factory.address}</td>
                                </tr>
                                `;
                         
                        });
                                           
                            singleItem +=`<tr>
                                    <td><strong>Total Quantity (KG)</strong></td>
                                    <td><strong>${total_quantity.toFixed(2)}</strong></td>
                                </tr></table>
                                
                                </div>
                                    <div class="card-footer">
                                        <div class="row"> 
                                            <input type="hidden" value="${dyed_factory_id}" name="items[${key}][${keyFa}][dyed_factory_id]"> 
                                            <div class="col-sm-2 col-lg">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Total Quantity (KG)</label>
                                                    <input type="text" class="form-control" id="total_quantity_${key}_${keyFa}"  value="${total_quantity.toFixed(2)}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-lg">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">From Stock(KG)</label>
                                                    <input type="text" class="form-control" oninput="knitFromQtyCal(this,'${key}_${keyFa}')" id="from_stock_quantity_${key}_${keyFa}" name="items[${key}][${keyFa}][from_stock_quantity]">
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-lg">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Yarn Quantity(KG)</label>
                                                    <input type="text" class="form-control" id="knit_quantity_${key}_${keyFa}" name="items[${key}][${keyFa}][knit_quantity]" value="${total_quantity.toFixed(2)}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-lg">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Rate(TK)</label>
                                                    <input type="number" class="form-control" oninput="attachRateCalculation(this,'${key}_${keyFa}')" name="items[${key}][${keyFa}][rate]"  id="rate_${key}_${keyFa}" min="1">
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-lg">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Total</label>
                                                    <input type="number" class="form-control" id="total_amount_${key}_${keyFa}" name="items[${key}][${keyFa}][total]" readonly>
                                                    
                                                </div>
                                            </div>
                                            <hr>

                                            <div class=col-12" id="garments_select_section_${key}_${keyFa}">
                                                <div id="garments_add_row_container_${key}_${keyFa}">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="form-group mb-4">
                                                                <label class="label text-secondary">From Stock(KG)</label>
                                                                <input type="text" class="form-control stock_quantity_${key}_${keyFa}"" name="items[${key}][${keyFa}][inner_items][1][form_stock_quantity]" oninput="calculateStockQuantity(this,'${key}_${keyFa}')"  data-id_prefix="${key}_${keyFa}" id="stock_quantity_${key}_${keyFa}" min="1">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group mb-4">
                                                                <label class="label text-secondary">Quantity(KG)</label>
                                                                <input type="number" class="form-control quantity_${key}_${keyFa}" data-id_prefix="${key}_${keyFa}" oninput="calculateQuantity(this,'${key}_${keyFa}')" name="items[${key}][${keyFa}][inner_items][1][quantity]"  id="quantity_${key}_${keyFa}" min="1">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-3">
                                                            <div class="form-group mb-4">
                                                                <label class="label text-secondary">Delivery Point</label>
                                                                <select name="items[${key}][${keyFa}][inner_items][1][delivery_point]" id="garments_${key}_${keyFa}" class="form-control select2">
                                                                    <option value="" selected disabled>Select Knit Factory</option>
                                                                    @foreach ($knitgFactory as $item)
                                                                        <option value="{{  $item->id }}">{{  $item->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 align-self-center mb-3">
                                                    <button type="button" id="add_new_row_${key}_${keyFa}" onclick="addNewRows('garments','${key}','${keyFa}')" class="btn btn-warning py-2 px-2 fw-medium fs-14">Add row</button>
                                                </div>
                                            </div>


                                        </div>  
                                    </div>
                                </div>
                            `;
                        });
                     });
                    
                     display_div.html(singleItem);
                    
                    $('#order_id').val(order_id);
                    $('#order_number').val(order_number);

                }).catch(error => {
                     console.error('Fetch error:', error);
                });
            }
            
            
        });

    });


    function attachRateCalculation(element,classPrefx) {
        let quantityIdIn = document.getElementById('knit_quantity_'+classPrefx);
        let totalInput = document.getElementById('total_amount_'+classPrefx);
        let rate_ = document.getElementById('rate_'+classPrefx);
        // console.log(classPrefx);
        
        let qty = parseFloat(quantityIdIn.value) || 0;
        let rate = parseFloat(rate_.value) || 0;
        
        let totalValue = (qty * rate).toFixed(2);
        totalInput.value = totalValue; 

    }
    
    function calTotalFrom(element,classPrefx){
        let totalQuantity = $('#total_quantity_'+classPrefx).val();
        let stockFromQuantity = $('#from_stock_quantity_'+classPrefx).val();

        let totalQuantityVal = parseFloat(totalQuantity) || 0;
        let stockFromQuantityVal = parseFloat(stockFromQuantity) || 0;

        let netQty = totalQuantityVal - stockFromQuantityVal;
        return netQty;
    }

    function knitFromQtyCal(element,classPrefx){
        let netQty = calTotalFrom(element,classPrefx);
        let knit_quantity = $('#knit_quantity_'+classPrefx);
        knit_quantity.val(netQty || 0);
        calculateQuantity(element,classPrefx);
        attachRateCalculation(element,classPrefx);
        // console.log(classPrefx);
        
        $('.stock_quantity_' + classPrefx).each(function () {
           $(this).val(0);
        });
    }

    function calculateQuantity (element,classPrefx){
        let netQty = calTotalFrom(element,classPrefx);
        
        let total = 0;
        $('.quantity_' + classPrefx).each(function () {
            let val = parseFloat($(this).val()) || 0;
            total += val;
        });

        if(netQty < total){
            $(element).val('');
            $('#add_new_row_'+classPrefx).prop('disabled', true);
            alert(`Max allowed is ${netQty}Kg (Total Quantity - From Stock)`);
        }else{
            $('#add_new_row_'+classPrefx).prop('disabled', false);
        }
    }
    
    function calculateStockQuantity (element,classPrefx){
        let from_stock_quantity = $('#from_stock_quantity_'+classPrefx).val();
        
        let total = 0;
        $('.stock_quantity_' + classPrefx).each(function () {
            let val = parseFloat($(this).val()) || 0;
            total += val;
        });

        if(from_stock_quantity < total){
            $(element).val('');
            alert(`Max allowed is ${from_stock_quantity || 0}Kg (From Stock Quantity)`);
        }
    }
    

    function resetSelect(id) {
        $('#'+id).val(null).trigger('change');
    }

    let indexRow =1;
    function addNewRows(sectionType,key,keyFa){
        let newRow = `
        <div class="row" id="row_${key}_${keyFa}_${indexRow}">
            <div class="col-sm-3">
                <div class="form-group mb-4">
                    <label class="label text-secondary">From Stock(KG)</label>
                    <input type="text" class="form-control stock_quantity_${key}_${keyFa}"" name="items[${key}][${keyFa}][inner_items][${indexRow+1}][form_stock_quantity]" oninput="calculateStockQuantity(this,'${key}_${keyFa}')"  data-id_prefix="${key}_${keyFa}_${indexRow}" id="stock_quantity_${key}_${keyFa}_${indexRow}" min="1">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group mb-4">
                    <label class="label text-secondary">Quantity(KG)</label>
                    <input type="number" class="form-control quantity_${key}_${keyFa}" data-id_prefix="${key}_${keyFa}_${indexRow}" oninput="calculateQuantity(this,'${key}_${keyFa}')" name="items[${key}][${keyFa}][inner_items][${indexRow+1}][quantity]" id="quantity_${key}_${keyFa}_${indexRow}" min="1">
                </div>
            </div>
            
            <div class="col-sm-3">
                <div class="form-group mb-4">
                    <label class="label text-secondary">Delivery Point</label>
                    <select name="items[${key}][${keyFa}][inner_items][${indexRow+1}][delivery_point]" class="form-control select2" id="garments_${key}_${keyFa}_${indexRow}">
                        <option value="" selected disabled>Select Knit Factory</option>
                            @foreach ($knitgFactory as $item)
                            <option value="{{  $item->id }}">{{  $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-1 align-self-center">
                <button type="button" class="btn btn-danger remove-row text-white" 
                    data-row="#row_${key}_${keyFa}_${indexRow}">
                    Remove
                </button>
            </div>
        </div>
        `;

        $(`#garments_add_row_container_${key}_${keyFa}`).append(newRow);
        indexRow++;
    }

    $(document).on('click', '.remove-row', function () {
        let rowId = $(this).data('row');
        $(rowId).remove();
    });

</script>
@endsection