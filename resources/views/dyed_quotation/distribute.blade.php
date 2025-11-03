@extends('layouts.master')
@section('title', 'Distribute yarn Dyed')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Distribute yarn dyed</h2>

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
                    <span class="fw-medium">Distribute yarn dyed</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <form action="{{ route('yarn.dyed.distribute.store') }}" method="POST" enctype="multipart/form-data"
                        id="netting_form">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="dyed_quotation_id" value="{{ $dyedquotation->id }}">
                            <input type="hidden" name="order_id" value="{{ $orderDetail->order_id }}">
                            <input type="hidden" name="style" value="{{ $dyedquotation->style }}">
                            <input type="hidden" name="description" value="{{ $dyedquotation->description }}">
                            <input type="hidden" name="dyed_factory_id" value="{{ $dyedquotation->dyedFactory->id }}">
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number</label>
                                    <input name="po_number" id="po_number"
                                        value="{{ old('po_number', $dyedquotation->po_number) }}" class="form-control"
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
                                            $dyedquotation->style }}</h3>
                                    </div>
                                    <div class="card-body">
                                        @php
                                        $dyedYarnknitQuot = $dyedquotation->dyedYarnknitQuot->quantity ?? 0;
                                        $dyedYarnLoss = $dyedquotation->dyedYarnLoss->sum('quantity') ?? 0;
                                        $dyedYarnStock = $dyedquotation->dyedYarnStock->sum('quantity') ?? 0;
                                        $totalUse = $dyedYarnknitQuot + $dyedYarnLoss + $dyedYarnStock;
                                        @endphp

                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Description</th>
                                                    <th>Quantity(KG)</th>
                                                    <th>Quotation Quantity(KG)</th>
                                                    <th>Loss</th>
                                                    <th>Stock</th>
                                                    <th>Allowed Quantity</th>
                                                    <th>Dyed Factory</th>
                                                </tr>

                                                <tr>
                                                    <td>{{ $dyedquotation->description }}</td>
                                                    <td>{{ $dyedquotation->quantity }} {{ $dyedquotation->unit }}
                                                        <input type="hidden"
                                                            value="{{ $dyedquotation->quantity - $totalUse }}"
                                                            id="total_stock_quantity" name="total_stock_quantity">
                                                    </td>
                                                    <td>
                                                        {{ $dyedYarnknitQuot ?? 0 }}kg
                                                    </td>
                                                    <td>
                                                        {{ $dyedYarnLoss ?? 0 }}kg
                                                    </td>
                                                    <td>
                                                        {{ $dyedYarnStock ?? 0 }}kg
                                                    </td>
                                                    <td>{{ $dyedquotation->quantity -
                                                        $totalUse }}kg
                                                    </td>
                                                    <td>Name: {{ $dyedquotation->dyedFactory->name }}<br> Address:{{
                                                        $dyedquotation->dyedFactory->address }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Quantity(KG)</label>
                                                    <input type="text" class="form-control quantity"
                                                        id="yarn_quantity_1" name="quantity"
                                                        oninput="limitWeightValue(this)">
                                                    @error('quantity')
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Rate(TK)</label>
                                                    <input type="number" class="form-control"
                                                        oninput="attachRateCalculation(this)" id="price_1" min="1"
                                                        name="price">
                                                    @error('price')
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary">Total</label>
                                                    <input type="number" class="form-control" id="total_amount_1"
                                                        readonly name="total_amount">

                                                    @error('total_amount')
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror

                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-3" id="knit_factory_1">
                                                <div class="form-group mb-4">
                                                    <label class="label text-secondary d-block">Delivery Point</label>
                                                    <select class="form-control select2" name="knit_factory_id">
                                                        <option value="" selected disabled>Knit Factory
                                                        </option>
                                                        @foreach ($knitFactory as $items)
                                                        <option value="{{ $items->id }}">{{ $items->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    @error('knit_factory_id')
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 pe-0 mb-3">
                                                <label class="label text-secondary">Loss(KG)</label>
                                                <input type="text" class="form-control" id="loss"
                                                    oninput="limitWeightValue(this)" name="loss">
                                            </div>
                                            <div class="col-lg-2 pe-0 mb-3">
                                                <label class="label text-secondary">Store
                                                    Stock</label>
                                                <input type="text" class="form-control" id="store"
                                                    oninput="limitWeightValue(this)" name="stock">
                                            </div>
                                            <div class="col-lg-3 col-sm-3 mb-3" id="knit_factory_1">
                                                <label class="label text-secondary d-block">Store Address</label>
                                                <select class="form-control select2" name="store_id">
                                                    <option value="" selected disabled>Select Store
                                                    </option>
                                                    @foreach ($storeAddress as $items)
                                                    <option value="{{ $items->id }}">{{ $items->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
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


    function attachRateCalculation(element) {
        let quantityIdIn = document.getElementById('yarn_quantity_1');
        let totalInput = document.getElementById('total_amount_1');
        let rate_ = document.getElementById('price_1');
        // console.log(classPrefx);
        
        let qty = parseFloat(quantityIdIn.value) || 0;
        let rate = parseFloat(rate_.value) || 0;
        
        let totalValue = (qty * rate).toFixed(2);
        totalInput.value = totalValue; 

    }


    function calculateQuantity (element){
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

        attachRateCalculation(element);
    }

     function limitWeightValue(input){
         input.value = input.value.replace(/^(\d*\.?\d{0,2}).*$/, '$1');
        
        let yarn_quantity= document.getElementById('yarn_quantity_1').value;
        let loss_= document.getElementById('loss').value;
        let store_= document.getElementById('store').value;

        let total_stock_quantity = $('#total_stock_quantity').val();
        let stock_quantity = parseFloat(total_stock_quantity) || 0;

        let val = parseFloat(input.value);
        let totalVal = (Number(yarn_quantity) || 0) + (Number(loss_) || 0)+ (Number(store_) || 0);
        // console.log(totalVal);
        if (totalVal > stock_quantity) {
            alert(`Max allowed is ${stock_quantity}Kg (Yarn + Loss + Store)`);
            input.value = 0;
        }

        calculateQuantity(input);
    }


</script>
@endsection