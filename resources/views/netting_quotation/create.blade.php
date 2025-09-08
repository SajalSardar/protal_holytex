@extends('layouts.master')
@section('title', 'Netting Quotation')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Netting Quotation</h2>

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
                    <span class="fw-medium">Netting Quotation</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <form action="{{ route('nettingquotation.store') }}" method="POST" enctype="multipart/form-data"
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

                fetch(`/get-yarn-style-by-po/${encodeURIComponent(po_number)}`)
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
                                                <th>Netting Factory</th>
                                            </tr>`;
                        factoryId.forEach(item => {
                            totalQty = parseFloat(item.quantity || 0) + parseFloat(item.from_stock_quantity || 0)
                            total_quantity += totalQty;
                            order_number = item.order_number;
                            order_id = item.order_id;
                            netting_factory_id =item.netting_factory.id;
                            singleItem += `
                            
                                <tr>
                                    <td>${item.description}</td>
                                    <td>${totalQty}</td>
                                    <td>Name:${item.yarn_factory.name} <br> Address:${item.yarn_factory.address}</td>
                                    <td>Name:${item.netting_factory.name}<br> Address:${item.netting_factory.address}</td>
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
                                            <input type="hidden" value="${netting_factory_id}" name="items[${key}][${keyFa}][netting_factory_id]"> 
                                            <div class="col-sm-3">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Total Quantity (KG)</label>
                                                    <input type="text" class="form-control" id="total_quantity_${key}_${keyFa}"  value="${total_quantity.toFixed(2)}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">From Stock(KG)</label>
                                                    <input type="text" class="form-control" oninput="knitFromQtyCal(this,'${key}_${keyFa}')" id="from_stock_quantity_${key}_${keyFa}" name="items[${key}][${keyFa}][from_stock_quantity]">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Knit Quantity(KG)</label>
                                                    <input type="text" class="form-control" id="knit_quantity_${key}_${keyFa}" name="items[${key}][${keyFa}][knit_quantity]" readonly>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-3">
                                                <p class="label text-secondary">Delivery to garments factory?</p>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label>
                                                        <input type="radio" class="form-check-input" style="border:1px solid #000" name="items[${key}][${keyFa}][delevary_poin_check]" value="dyeing" onclick="showHideDeliveryPoint(this,'${key}','${keyFa}')"> No</label>
                                                        <label class="ms-3">
                                                        <input type="radio" class="form-check-input" style="border:1px solid #000" name="items[${key}][${keyFa}][delevary_poin_check]" value="garments" onclick="showHideDeliveryPoint(this,'${key}','${keyFa}')"> Yes</label>
                                                    </div>    
                                                </div>
                                            </div>
                                            <div class=col-12">
                                                <div id="add_row_container_${key}_${keyFa}">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="form-group mb-4">
                                                                <label class="label text-secondary">Quantity(KG)</label>
                                                                <input type="number" class="form-control quantity_${key}_${keyFa}" data-id_prefix="${key}_${keyFa}" oninput="calculateQuantity(this,'${key}_${keyFa}')" name="items[${key}][${keyFa}][inner_items][1][quantity]"  id="quantity_${key}_${keyFa}" min="1">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group mb-4">
                                                                <label class="label text-secondary">Rate(TK)</label>
                                                                <input type="number" class="form-control" oninput="attachRateCalculation(this,'${key}_${keyFa}')" name="items[${key}][${keyFa}][inner_items][1][rate]"  id="rate_${key}_${keyFa}" min="1">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group mb-4">
                                                                <label class="label text-secondary">Total</label>
                                                                <input type="number" class="form-control" id="total_amount_${key}_${keyFa}" name="items[${key}][${keyFa}][inner_items][1][total]" readonly>
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3" id="deying_select_section_${key}_${keyFa}" style="display:none;">
                                                            <div class="form-group mb-4">
                                                                <label class="label text-secondary">Delivery Point</label>
                                                                <select name="items[${key}][${keyFa}][inner_items][1][delivery_point]" class="form-control select2" id="deying_point_${key}_${keyFa}">
                                                                    <option value="" selected disabled>Select Deying Factory</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-3" id="garments_select_section_${key}_${keyFa}" style="display:none;">
                                                            <div class="form-group mb-4">
                                                                <label class="label text-secondary">Delivery Point</label>
                                                                <select name="items[${key}][${keyFa}][inner_items][1][delivery_point]" id="garments_${key}_${keyFa}" class="form-control select2">
                                                                    <option value="" selected disabled>Select Garments Factory</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 align-self-center mb-3">
                                                    <button type="button" id="add_new_row_${key}_${keyFa}" onclick="addNewRows('${key}','${keyFa}')" class="btn btn-warning py-2 px-2 fw-medium fs-14" style="display:none;">Add row</button>
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
        let quantityIdIn = document.getElementById('quantity_'+classPrefx);
        let totalInput = document.getElementById('total_amount_'+classPrefx);
        let rate_ = document.getElementById('rate_'+classPrefx);
        

        
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

        attachRateCalculation(element,$(element).data('id_prefix'));
    }

    function showHideDeliveryPoint(element,style,factoryId) {

        if (!element.checked) return; 

        let value = element.value;

        let deyingSection = document.getElementById(`deying_select_section_${style}_${factoryId}`);
        let garmentsSection = document.getElementById(`garments_select_section_${style}_${factoryId}`);
        let addNewRowBtn = document.getElementById(`add_new_row_${style}_${factoryId}`);

        let add_row_container = document.getElementById(`add_row_container_${style}_${factoryId}`);
        let container_rows = add_row_container.querySelectorAll('.row');
        container_rows.forEach((row, index) => {
            if (index > 0) row.remove();
        });

        if (value === "garments") {
            garmentsSection.style.display = "block";
            deyingSection.style.display = "none";
            addNewRowBtn.style.display = "none";

            // Call garments API
            fetch(`/get-all-garments-factory`)
                .then(res => res.json())
                .then(data => {
                    let select = document.getElementById(`garments_${style}_${factoryId}`);
                    select.innerHTML = '<option value="" selected disabled>Select Garments Factory</option>';
                    data.forEach(item => {
                        let option = document.createElement("option");
                        option.value = item.id;
                        option.textContent = item.name;
                        select.appendChild(option);
                    });
                    //$(`#garments_${key}`).select2();
                });
        } 
        else if (value === "dyeing") {
            deyingSection.style.display = "block";
            addNewRowBtn.style.display = "block";
            garmentsSection.style.display = "none";
            getDyeingFactory(`#deying_point_${style}_${factoryId}`);
            
        }
    }
    
    function getDyeingFactory(selectId){
        // console.log(selectId);
        fetch(`/get-all-dyeing-factory`)
        .then(res => res.json())
        .then(data => {
            let $select = $(selectId);
            $select.empty();

            $select.append('<option value="" selected disabled>Select Dyeing Factory</option>');

            data.forEach(item => {
                $select.append(`<option value="${item.id}">${item.name}</option>`);
            });
        });
    }
    

    function resetSelect(id) {
        $('#'+id).val(null).trigger('change');
    }

    let indexRow = 1;
    function addNewRows(key,keyFa){
        let newRow = `
            <div class="row" id="row_${key}_${keyFa}_${indexRow}">
                <div class="col-sm-3">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Quantity(KG)</label>
                        <input type="number" class="form-control quantity_${key}_${keyFa}" data-id_prefix="${key}_${keyFa}_${indexRow}" oninput="calculateQuantity(this,'${key}_${keyFa}')" name="items[${key}][${keyFa}][inner_items][${indexRow+1}][quantity]" id="quantity_${key}_${keyFa}_${indexRow}" min="1">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Rate(TK)</label>
                        <input type="number" class="form-control" oninput="attachRateCalculation(this,'${key}_${keyFa}_${indexRow}')" name="items[${key}][${keyFa}][inner_items][${indexRow+1}][rate]" id="rate_${key}_${keyFa}_${indexRow}" min="1">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Total</label>
                        <input type="number" class="form-control" id="total_amount_${key}_${keyFa}_${indexRow}" name="items[${key}][${keyFa}][inner_items][${indexRow+1}][total]" readonly>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Delivery Point</label>
                        <select name="items[${key}][${keyFa}][inner_items][${indexRow+1}][delivery_point]" class="form-control select2" id="deying_point_${key}_${keyFa}_${indexRow}">
                            <option value="" selected disabled>Select Deying Factory</option>
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

         $(`#add_row_container_${key}_${keyFa}`).append(newRow);
         getDyeingFactory(`#deying_point_${key}_${keyFa}_${indexRow}`);
         indexRow++;
    }

    $(document).on('click', '.remove-row', function () {
        let rowId = $(this).data('row');
        $(rowId).remove();
    });

</script>
@endsection