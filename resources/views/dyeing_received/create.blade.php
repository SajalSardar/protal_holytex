@php
$po_number = request()->po_number ?? '';
@endphp
@extends('layouts.master')
@section('title', 'Dyeing Receiving')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Dyeing Receiving at Graments Factory</h2>

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
                    <span class="fw-medium">Dyeing Receiving</span>
                </li>
            </ol>
        </nav>
    </div>
    <form action="{{ route('dyeingreceived.store') }}" method="POST" enctype="multipart/form-data" id="yarn_form">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-white border-0 rounded-3 mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <input type="hidden" id="order_id" name="order_id">
                            <input type="hidden" id="order_number" name="order_number">
                            <div class="col-lg-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number <span
                                            style="color: rgb(205, 2, 2)">*</span>(Show only received yarn)</label>
                                    <select name="po_number" id="po_number"
                                        class="form-control select2  @error('po_number') is-invalid @enderror">
                                        <option value="" selected disabled>Select PO Number</option>
                                        @foreach ($dyeingQut as $item)
                                        <option value="{{ $item }}" {{ $po_number==$item ? "selected" : '' }}>{{ $item
                                            }}</option>
                                        @endforeach
                                    </select>
                                    @error('po_number')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="show_all_yarn_item" class="row"></div>
    </form>
</div>

<div class="flex-grow-1"></div>
@endsection


@section('script')
<script>
    $(function() {
        $('.select2').select2();

        $('#po_number').on('change',function(){
            let selected_po_number = $(this).val();
            let currentUrl = window.location.origin + window.location.pathname;
            window.location.href = currentUrl + "?po_number=" + selected_po_number;

           loadYarnData(selected_po_number);
        });
        
        $(window).on('load',function(){
            let request_po ="{{ $po_number }}"
            if(request_po != ''){
                loadYarnData(request_po);
            }
        });


    async function loadYarnData(po_number){
            if (!po_number) return;
            try{
                const response = await fetch(`/get-dyeing-quotation-by-po/${encodeURIComponent(po_number)}`);
                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();

                let order_id = null;
                let order_number = null;
                // console.log('API response:', data);
                let display_div = $('#show_all_yarn_item');

                if(data.length === 0){
                    display_div.html(`
                        <div class="col-lg-12"><div class="alert alert-success">Total Received Done!</div></div>
                    `);
                    return;
                }

                let singleItem = `<div class="col-lg-12">
                                <div class="card bg-white border-0 rounded-3 mb-4">
                                    <div class="card-body p-4">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <h3>CHALLAN INFO</h3>
                                            </div>
                                            <div class="col-lg-3 col-sm-4">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Challan No.</label>
                                                    <input type="text" name="challan_number" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-sm-4">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Vehicle Number</label>
                                                    <input type="text" name="vehicle_number" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-sm-4">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Challan Date</label>
                                                    <input type="date" name="challan_date" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-sm-4">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Received Date</label>
                                                    <input type="date" name="received_date" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-4">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Upload Challan</label>
                                                    <input type="file" name="challan_file" class="form-control">
                                                    <p class="fs-12">Uploaded file size 512kb & File type jpg,png </p>
                                                    @error('challan_file')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                // Append new options
                for (const [key, items] of Object.entries(data)){
                    singleItem +=`<div class="col-lg-12">
                        <div class="accordion mb-5" style="border-bottom:5px solid #605dff;">
                    <div class="accordion-item">
                            <h2 class="accordion-header mb-3">
                                <button style="background: #605dff;" class="accordion-button text-uppercase text-white"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapse${key}">
                                    <strong>Style: ${key}</strong>
                                </button>
                            </h2>
                        <div id="collapse${key}" class="accordion-collapse collapse show">
                            <div class="accordion-body p-0 px-2">`;
                    for (const item of items) {
                         const totalReceviedNettCa = await fetchReceivedNetting(po_number, key,item.dyeing_factory_id);
                        const totalReceviedNettData = totalReceviedNettCa.total_received || 0;
                        // console.log(totalReceviedNettCa);
                        order_id = item.order_id;
                        order_number = item.order_number;
                        let quotation = parseFloat(item.quantity);
                        let allTotalRecevied =
                                            (Number(item.dyeing_receive_garments_sum_quantity) || 0) +
                                            (Number(item.dyeing_store_stock_sum_quantity) || 0);
                        // let noreceived = parseFloat(quotation - allTotalRecevied).toFixed(2);
                        let noreceived = parseFloat(totalReceviedNettData - allTotalRecevied).toFixed(2);
                        
                        singleItem +=`<div class="row my-4">
                                    <input type="hidden" name="items[${item.id}][dyeing_qty_id]" value="${item.id}">
                                    <input type="hidden" name="items[${item.id}][delivery_point_id]" value="${item.delivery_point_id}">
                                    <input type="hidden" name="items[${item.id}][dyeing_factory_id]" value="${item.dyeing_factory_id}">
                                    <input type="hidden" name="items[${item.id}][style]" value="${item.style}">                          
                                    <div class="col-lg pe-0 mb-3"><label class="label text-secondary">Quotation(KG)</label><input type="text" class="form-control" readonly value="${item.quantity}"></div>
                                    <div class="col-lg pe-0 mb-3"><label class="label text-secondary">Netting Recv.</label><input type="text" class="form-control" readonly  name="items[${item.id}][netting_received]" value="${totalReceviedNettData}"></div>
                                    <div class="col-lg pe-0 mb-3"><label class="label text-secondary">Received</label><input type="text" class="form-control" readonly value="${item.dyeing_receive_garments_sum_quantity || 0}"></div>
                                    <div class="col-lg pe-0 mb-3"><label class="label text-secondary">Store In Stock</label><input type="text" class="form-control" readonly value="${item.dyeing_store_stock_sum_quantity || 0}"></div>
                                    <div class="col-lg pe-0 mb-3"><label class="label text-secondary">No Received</label><input type="text" class="form-control" readonly value="${noreceived}"></div>
                                    <div class="col-lg pe-0 mb-3"><label class="label text-secondary">Dyeing Factory</label><input class="form-control" readonly value="${item.dyeing_factory.name}"></div>
                                    <div class="col-lg mb-3"><label class="label text-secondary">Garments Factory</label><input class="form-control" readonly value="${item.garments_factory.name}"></div>`;
                        if(allTotalRecevied >= quotation){ 
                            singleItem +=`<div class="col-12">
                                            <div class="alert alert-success mb-3">Total Received Done!</div>
                                        </div> <hr class="m-0">`;
                        }else{
                            singleItem +=`<div class="col-12">
                                    <div class="row">
                                        <div class="col-12 mb-2 mt-3">
                                            <h4 class="fs-16 text-primary">Receive Quantity(Netting, Loss, Store In Stock):</h4>
                                        </div>
                                        <div class="col-lg-2 pe-0 mb-3"><label class="label text-secondary">Lot No.</label><input type="text" class="form-control" oninput="this.value = this.value.replace(/^(\\d*\\.?\\d{0,2}).*$/,'$1')" name="items[${item.id}][loat_no]"></div>
                                        <div class="col-lg-2 pe-0 mb-3"><label class="label text-secondary">Bags</label><input type="text" class="form-control" oninput="this.value = this.value.replace(/^(\\d*\\.?\\d{0,2}).*$/,'$1')" name="items[${item.id}][bag_count]"></div>
                                        <div class="col-lg-2 pe-0 mb-3"><label class="label text-secondary">Netting(KG)</label><input type="text" max="${noreceived}" id="netting_${item.id}" class="form-control" oninput="limitWeightValue(this,${item.id})" name="items[${item.id}][netting]"></div>
                                        <div class="col-lg-2 pe-0 mb-3"><label class="label text-secondary">Store In Stock</label><input type="text" max="${noreceived}" id="store_stokc_${item.id}" class="form-control" oninput="limitWeightValue(this,${item.id})" name="items[${item.id}][store_stock]"></div>
                                        <div class="col-lg-2 pe-0 mb-3"><label class="label text-secondary">Store Address</label><textarea rows="1" class="form-control" name="items[${item.id}][store_address]"></textarea></div>
                                        <div class="col-lg-2 mb-3"><label class="label text-secondary">Remarks</label><textarea rows="1" class="form-control" name="items[${item.id}][remarks]"></textarea></div>
                                        
                                    </div>
                                </div>
                            </div> <hr class="m-0">`;
                        }
                    };
                    singleItem +=`</div></div></div>
                                </div></div></div>`;
                };
                
                singleItem +=`<div class="col-lg-12 my-3">
                                <div class="d-flex flex-wrap gap-3">
                                    <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                            class="ri-add-line text-white fw-medium"></i> Create</button>
                                </div>
                            </div>`;
                display_div.html(singleItem);
                $('#order_id').val(order_id);
                $('#order_number').val(order_number);

            }catch (error) {
                console.error('Fetch error:', error);
            }
            
        };

        

    });
    
    function limitWeightValue(input, id){
         input.value = input.value.replace(/^(\d*\.?\d{0,2}).*$/, '$1');
        
        let netting_= document.getElementById('netting_'+id).value;
        let store_stokc_= document.getElementById('store_stokc_'+id).value;

        let maxVal = parseFloat(input.max);
        let val = parseFloat(input.value);
        let totalVal = (Number(netting_) || 0) + (Number(store_stokc_) || 0);
        // console.log(totalVal);
        if (totalVal > maxVal) {
            alert(`Max allowed is ${maxVal}Kg (Netting)`);
            input.value = 0;
        }
    }

    function resetSelect(id) {
        $('#'+id).val(null).trigger('change');
    }


    async function fetchReceivedNetting(po_number, style,dyeing_id) {
        try {
            const response = await fetch(`/get-recevied-total-netting-by-style?po_number=${encodeURIComponent(po_number)}&style=${encodeURIComponent(style)}&dyeing_id=${encodeURIComponent(dyeing_id)}`);

            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.status}`);
            }

            const totalReceived = await response.json();
            return totalReceived;
        } catch (error) {
            console.error('Error fetching received yarn:', error);
        }
    }
</script>
@endsection