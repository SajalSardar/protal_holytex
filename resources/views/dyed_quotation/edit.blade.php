@extends('layouts.master')
@section('title', 'Edit Dyed Quotation')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Edit Dyed Quotation </h2>

        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                        <span class="text-secondary fw-medium hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">Dyed Quotation</span>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">Edit Dyed Quotation</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <form action="{{ route('dyedquotation.update',$dyedquotation->id) }}" method="POST"
                        enctype="multipart/form-data" id="yarn_form">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number <span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <input type="text" value="{{ old('po_number',$dyedquotation->po_number) }}"
                                        class="form-control" name="po_number" readonly>
                                    @error('po_number')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Date</label>
                                    <input type="date" value="{{ old('order_date',$dyedquotation->purchase_date) }}"
                                        class="form-control" name="order_date">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Approximate Delivery Date</label>
                                    <input type="date" class="form-control"
                                        value="{{ old('approximate_delivery_date',$dyedquotation->approximate_delivery_date) }}"
                                        name="approximate_delivery_date">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label class="label text-secondary">Status</label>
                                    <select name="status" class="form-select form-control status_select select2"
                                        id="status_select">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="pending" {{ $dyedquotation->status === "pending" ? 'selected' :
                                            '' }}>Pending</option>
                                        <option value="approved" {{ $dyedquotation->status === "approved" ? 'selected' :
                                            '' }}>Approved</option>
                                        <option value="recevied" {{ $dyedquotation->status === "recevied" ? 'selected' :
                                            '' }}>Recevied</option>
                                        <option value="ready_to_deliver" {{ $dyedquotation->status ===
                                            "ready_to_deliver" ? 'selected' :
                                            '' }}>Ready to deliver</option>
                                        <option value="delivered" {{ $dyedquotation->status === "delivered" ? 'selected'
                                            : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $dyedquotation->status === "cancelled" ? 'selected'
                                            : '' }}>Cancelled</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Remarks</label>
                                    <textarea class="form-control" name="remarks"
                                        rows="1">{{ old('remarks',$dyedquotation->remarks) }}</textarea>
                                </div>
                            </div>

                            @if ($dyedquotation->dyedYarnReceived)
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <h3>RECEIVED CHALLAN INFO</h3>
                                </div>
                                <div class="col-lg-3 col-sm-4">
                                    <div class="form-group mb-4">
                                        <label class="label text-secondary">Challan No.</label>
                                        <input type="text" name="challan[challan_number]" class="form-control"
                                            value="{{ old('challan_number',$dyedquotation->dyedYarnReceived->challan_number)}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-4">
                                    <div class="form-group mb-4">
                                        <label class="label text-secondary">Vehicle Number</label>
                                        <input type="text" name="challan[vehicle_number]" class="form-control"
                                            value="{{ old('vehicle_number',$dyedquotation->dyedYarnReceived->vehicle_number)}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-4">
                                    <div class="form-group mb-4">
                                        <label class="label text-secondary">Challan Date</label>
                                        <input type="date" name="challan[challan_date]" class="form-control"
                                            value="{{ old('challan_date',$dyedquotation->dyedYarnReceived->challan_date)}}">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-4">
                                    <div class="form-group mb-4">
                                        <label class="label text-secondary">Received Date</label>
                                        <input type="date" name="challan[received_date]" class="form-control"
                                            value="{{ old('received_date',$dyedquotation->dyedYarnReceived->received_date)}}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-4">
                                    <div class="form-group mb-4">
                                        <label class="label text-secondary">Upload Challan</label>
                                        <input type="file" name="challan[challan_file]" class="form-control">
                                        <p class="fs-12">Uploaded file size 512kb &amp; File type jpg,png </p>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div id="recevied_info"></div>
                            @endif

                            <hr>

                            <div class="col-lg-2 col-sm-6 px-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Style</label>
                                    <input type="text" class="form-control " placeholder="style" id="from_stock"
                                        value="{{ @$dyedquotation->style }}" name="style" readonly>

                                </div>
                            </div>

                            <div class="col-lg-2 col-sm-6 pe-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Description</label>
                                    <textarea rows="1" class="form-control" placeholder="Write your note here...."
                                        id="description" name="description"
                                        readonly>{{ @$dyedquotation->description }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 pe-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Quantity(KG)<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control " placeholder="Quantity" id="unit_quantity"
                                        min="1" name="quantity" value="{{ @$dyedquotation->quantity }}">
                                    @error('quantity')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 pe-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Price(TK)<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control " min="1" placeholder="Unit Price"
                                        id="unit_price" name="price" value="{{ @$dyedquotation->price }}">
                                    @error('price')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 pe-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Total Price</label>
                                    <input type="text" readonly class="form-control " placeholder="Unit Price"
                                        id="total_unit_price" name="total_unit_price"
                                        value="{{ @$dyedquotation->total_price }}">
                                </div>
                            </div>

                            <div class="col-lg-2 col-sm-6 pe-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Knit Factory<span
                                            class="text-danger">*</span></label>
                                    <select name="netting_factory_id" id="delivery_point"
                                        onchange="selectOneDeliveryPoin('delivery_point')" class="form-control select2">
                                        <option value="" selected disabled>Select Knit Factory</option>
                                        @foreach ($knitgFactory as $item)
                                        <option value="{{ $item->id }}" {{$dyedquotation->delivery_point_id ===
                                            $item->id
                                            ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('netting_factory_id')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
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

        $('#status_select').on('change', function(){
            let selectedValue = $(this).val();
            if(selectedValue == 'recevied'){
                $('#recevied_info').html(`<hr>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h3>CHALLAN INFO</h3>
                        </div>
                        <div class="col-lg-3 col-sm-4">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Challan No.</label>
                                <input type="text" name="challan[challan_number]" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Vehicle Number</label>
                                <input type="text" name="challan[vehicle_number]" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Challan Date</label>
                                <input type="date" name="challan[challan_date]" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Received Date</label>
                                <input type="date" name="challan[received_date]" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-4">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Upload Challan</label>
                                <input type="file" name="challan[challan_file]" class="form-control">
                                <p class="fs-12">Uploaded file size 512kb &amp; File type jpg,png </p>
                                                                                    </div>
                        </div>
                    </div>
                `);
            }else{
                $('#recevied_info').html('')
            }
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