@extends('layouts.master')
@section('title', 'Deying Quotation')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Deying Quotation</h2>

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
                    <span class="fw-medium">Deying Quotation</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <form action="{{ route('dyeingquotation.store') }}" method="POST" enctype="multipart/form-data"
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
                                        @foreach ($nettings as $item)
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

                            <div id="show_all_netting_item"></div>

                            <hr>
                            <div class="col-lg-12 mt-5">
                                <div class="d-flex flex-wrap gap-3">
                                    <button type="submit" id="submit_button"
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

                fetch(`/get-netting-order/${encodeURIComponent(po_number)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                }).then(data => {
                    // console.log('API response:', data);
                    let display_div = $('#show_all_netting_item');
                    let order_id = null;
                    let order_number = null;
                    let singleItem = '';
                    // Append new options
                    data.forEach(item => {
                        order_number = item.order_number;
                        order_id = item.order_id;
                        singleItem +=`<div class="card border-0 rounded-3 mb-3">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-3">
                                                    <h3 style="text-transform:uppercase">Style: ${item.style}</h3>    
                                                </div>
                                                <div class="col-3">
                                                    <p>Netting Factory: ${item.netting_factory.name}</p>
                                                </div>
                                                <div class="col-3">
                                                    <p>Dyeing Factory: ${item.dyeing_factory.name}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body"><div class="row">
                                            <input type="hidden" value=" ${item.dyeing_factory.id}" name="items[${item.style}][dyeing_factory_id]"> 
                                            <div class="col-sm-3">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Total Quantity (KG)</label>
                                                    <input type="text" class="form-control" id="quantity_${item.style}" name="items[${item.style}][quantity]" value="${item.quantity}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Rate(TK)</label>
                                                    <input type="number" class="form-control" oninput="attachRateCalculation('${item.style}')" name="items[${item.style}][rate]"  id="rate_${item.style}">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Total</label>
                                                    <input type="number" class="form-control" id="total_amount_${item.style}" name="items[${item.style}][total]" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6" id="deying_select_section_${item.style}">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Delivery Point</label>
                                                    <select name="items[${item.style}][delivery_point]" id="deying_point_${item.style}" class="form-control select2_innter">
                                                        <option value="" selected disabled>Select Germents Factory</option>
                                                        @foreach ($delivery_point as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                </div>
                            `;
                    });
                    
                     display_div.html(singleItem);
                    $('.select2_innter').select2();
                    $('#order_id').val(order_id);
                    $('#order_number').val(order_number);

                }).catch(error => {
                     console.error('Fetch error:', error);
                });
            }
            
            
        });

    });

    function attachRateCalculation(style) {
        let qtyInput = document.getElementById(`quantity_${style}`);
        let rateInput = document.getElementById(`rate_${style}`);
        let totalInput = document.getElementById(`total_amount_${style}`);

        let qty = parseFloat(qtyInput.value) || 0;
        let rate = parseFloat(rateInput.value) || 0;

        totalInput.value = (qty * rate).toFixed(2);
    }


    function resetSelect(id) {
        $('#'+id).val(null).trigger('change');
    }
</script>
@endsection