@extends('layouts.master')
@section('title', 'Buy Yarn')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Buy Yarn</h2>

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
                    <span class="fw-medium">Buy Yarn</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <form action="{{ route('netting.store') }}" method="POST" enctype="multipart/form-data"
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



                            <div class="col-lg-2 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Yarn Factory</label>
                                    <select name="yarn_factory" id="yarn_factory" class="form-control select2">
                                        <option value="" selected disabled>Select Factory</option>
                                        {{-- @foreach ($yarnFactory as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Delivery Point</label>
                                    <select name="delivery_point" id="delivery_point" class="form-control select2">
                                        <option value="" selected disabled>Select Netting Factory</option>
                                        {{-- @foreach ($nettingFactory as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <div class="col-lg-12 mt-5">
                                <div class="d-flex flex-wrap gap-3">
                                    <button class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button>
                                    <button type="button" id="submit_button"
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
                    console.log('API response:', data);
                    let display_div = $('#show_all_yarn_item');
                    let order_id = null;
                    let order_number = null;
                    let singleItem = '';
                    // Append new options
                    Object.entries(data).forEach(([key, value]) => {
                        // console.log(key, value);
                        let total_quantity = 0;
                        singleItem +=`<div class="card border-0 rounded-3 mb-3">
                                        <div class="card-header">
                                             <h3 style="text-transform:uppercase">Style: ${key}</h3>
                                        </div>
                                        <div class="card-body">
                                        <table class="table">
                                            <tr>
                                                <th>Description</th>
                                                <th>Quantity(KG)</th>
                                                <th>Yarn Factory</th>
                                                <th>Netting Factory</th>
                                            </tr>`;
                        value.forEach(item => {
                            total_quantity += parseFloat(item.quantity);
                            order_number = item.order_number
                            order_id = item.order_id
                        singleItem += `
                            
                                <tr>
                                    <td>${item.description}</td>
                                    <td>${item.quantity}</td>
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
                                </div>
                            `;
                    });
                    
                     display_div.html(singleItem);
                    
                    $('#order_id').val(order_id);
                    $('#order_number').val(order_number);
                    // Refresh Select2
                    $('#style_select').trigger('change.select2');

                }).catch(error => {
                     console.error('Fetch error:', error);
                });
            }
            
            
        });

    });
    
    

    function resetSelect(id) {
        $('#'+id).val(null).trigger('change');
    }
</script>
@endsection