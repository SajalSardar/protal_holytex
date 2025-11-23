@extends('layouts.master')
@section('title', 'Edit Yarn Quotation')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Edit Yarn Quotation </h2>

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
                    <span class="fw-medium">Edit Yarn Quotation</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <form action="{{ route('yarnquotation.update',$yarnquotation->id) }}" method="POST"
                        enctype="multipart/form-data" id="yarn_form">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number <span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <input type="text" value="{{ old('po_number',$yarnquotation->po_number) }}"
                                        class="form-control" name="po_number" readonly>
                                    @error('po_number')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Date</label>
                                    <input type="date" value="{{ old('order_date',$yarnquotation->order_date) }}"
                                        class="form-control" name="order_date">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Approximate Delivery Date</label>
                                    <input type="date" class="form-control"
                                        value="{{ old('approximate_delivery_date',$yarnquotation->approximate_delivery_date) }}"
                                        name="approximate_delivery_date">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Order number</label>
                                    <input type="text" value="{{ old('order_number',$yarnquotation->order_number) }}"
                                        class="form-control" id="order_number" name="order_number" readonly>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label class="label text-secondary">Status</label>

                                    <select name="status" class="form-select form-control status_select select2">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="pending" {{ $yarnquotation->status === "pending" ? 'selected' :
                                            '' }}>Pending</option>
                                        <option value="approved" {{ $yarnquotation->status === "approved" ? 'selected' :
                                            '' }}>Approved</option>
                                        <option value="received" {{ $yarnquotation->status === "received" ? 'selected' :
                                            '' }}>Received</option>
                                        <option value="ready_to_deliver" {{ $yarnquotation->status ===
                                            "ready_to_deliver" ? 'selected' :
                                            '' }}>Ready to deliver</option>
                                        <option value="delivered" {{ $yarnquotation->status === "delivered" ? 'selected'
                                            : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $yarnquotation->status === "cancelled" ? 'selected'
                                            : '' }}>Cancelled</option>

                                    </select>

                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Remarks</label>
                                    <textarea class="form-control" name="remarks"
                                        rows="1">{{ old('remarks',$yarnquotation->remarks) }}</textarea>
                                </div>
                            </div>
                            <hr>

                            <div class="col-lg-2 col-sm-6 px-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Style</label>
                                    <select class="form-select form-control select2" id="style_select" name="style">
                                        <option selected disabled value="">Style</option>
                                        @foreach ($getStyleByPo as $item)
                                        <option value="{{  $item }}" {{ $item===$yarnquotation->style ? 'selected' :''
                                            }}>{{ $item }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="col-lg-2 col-sm-6 pe-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Yarn Description</label>
                                    <textarea rows="1" class="form-control" placeholder="Write your note here...."
                                        id="description"
                                        name="description">{{ @$yarnquotation->description }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 pe-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Quantity(KG)</label>
                                    <input type="number" class="form-control " placeholder="Quantity" id="unit_quantity"
                                        min="1" name="quantity" value="{{ @$yarnquotation->quantity }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 pe-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Price(TK)</label>
                                    <input type="number" class="form-control " min="1" placeholder="Unit Price"
                                        id="unit_price" name="price" value="{{ @$yarnquotation->price }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 pe-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Total Price</label>
                                    <input type="text" readonly class="form-control " placeholder="Unit Price"
                                        id="total_unit_price" name="total_unit_price"
                                        value="{{ @$yarnquotation->total_price }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 px-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Yarn Factory</label>
                                    <select name="yarn_factory" id="yarn_factory" class="form-control select2">
                                        <option value="" selected disabled>Select Factory</option>
                                        @foreach ($yarnFactory as $item)
                                        <option value="{{ $item->id }}" {{$yarnquotation->yarn_factory_id === $item->id
                                            ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 px-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Delivery(Store Address)</label>
                                    <select name="delivery_point" id="delivery_point" class="form-control select2">
                                        <option value="" selected disabled>Select Store</option>
                                        @foreach ($storeAddress as $item)
                                        <option value="{{ $item->id }}" {{ $item->id === $yarnquotation->store_id ?
                                            'selected': '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <div class="col-lg-12 mt-5">
                                <div class="d-flex flex-wrap gap-3">
                                    <button type="button" id="submit_button"
                                        class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                            class="ri-add-line text-white fw-medium"></i> Update</button>
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

        $('#submit_button').on('click', function(){
            $('#yarn_form').submit();
                $(this).prop('disabled', true); 
                $(this).html('Saving…');
        });

    });

    let isResetting = false; 
    function selectOneDeliveryPoin(selectBox){
        
        if (isResetting) return;
        isResetting = true;
        if (selectBox === "delivery_point") {
            $('#dyed_factory').val(null).trigger('change.select2');
        }

        if (selectBox === "dyed_factory") {
            $('#delivery_point').val(null).trigger('change.select2');
        }
         isResetting = false;
    }
    
    const unit_price = document.getElementById("unit_price");
    const unit_quantity = document.getElementById("unit_quantity");
    const total_unit_price = document.getElementById("total_unit_price");
    const add_item_btn = document.getElementById("add_item_btn");

    // Function to calculate and update total
    function calculateTotal() {
        const price = parseFloat(unit_price.value) || 0;
        const quantity = parseFloat(unit_quantity.value) || 0;
        const total = price * quantity;
        total_unit_price.value = total.toFixed(2); // formatted to 2 decimal places
    }

    // Trigger on change and keyup (or more generally: input)
    unit_price.addEventListener("input", calculateTotal);
    unit_quantity.addEventListener("input", calculateTotal);


    function resetSelect(id) {
        $('#'+id).val('').trigger('change');
    }
</script>
@endsection