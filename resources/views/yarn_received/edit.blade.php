@php
$totalRecived = @$yarnQuotation->yarn_received_sum_quantity + $yarnQuotation->yarn_loss_sum_quantity;
$noreceivedValue = @$yarnQuotation->quantity - $totalRecived;
@endphp
@extends('layouts.master')
@section('title', 'Edit Yarn Receive')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Edit Yarn Receive </h2>

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
                    <span class="fw-medium">Edit Yarn Receive</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <form action="{{ route('yarnreceived.update',$yarnreceived->id) }}" method="POST"
                        enctype="multipart/form-data" id="yarn_form">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-3 col-sm-4">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Challan No.</label>
                                    <input type="text" name="challan_number" class="form-control"
                                        value="{{ old('challan_number',$yarnreceived->challan_number) }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-4">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Vehicle Number</label>
                                    <input type="text" name="vehicle_number" class="form-control"
                                        value="{{ old('challan_number',$yarnreceived->vehicle_number) }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-4">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Challan Date</label>
                                    <input type="date" name="challan_date" class="form-control"
                                        value="{{ old('challan_number',$yarnreceived->challan_date) }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-4">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Received Date</label>
                                    <input type="date" name="received_date" class="form-control"
                                        value="{{ old('challan_number',$yarnreceived->received_date) }}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-4">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Upload Challan</label>
                                    <input type="file" name="challan_file" class="form-control">
                                    <p class="fs-12">Uploaded file size 512kb &amp; File type jpg,png
                                    </p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-4">
                            <div class="col-lg col-sm-4 pe-0 mb-3">
                                <label class="label text-secondary">Description</label>
                                <input class="form-control" value="{{ @$yarnQuotation->description }}" readonly>
                            </div>
                            <div class="col-lg col-sm-4 pe-0 mb-3">
                                <label class="label text-secondary">Quotation(KG)</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{ @$yarnQuotation->quantity }}" id="qotQuantity">
                            </div>
                            <div class="col-lg col-sm-4 pe-0 mb-3">
                                <label class="label text-secondary">Yarn
                                    Factory</label>
                                <input class="form-control" readonly=""
                                    value="{{ @$yarnQuotation->yarnFactory->name }}">
                            </div>
                            <div class="col-lg col-sm-4 pe-0 mb-3">
                                <label class="label text-secondary">Received</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{ @$yarnQuotation->yarn_received_sum_quantity }}">
                            </div>
                            <div class="col-lg col-sm-4 pe-0 mb-3">
                                <label class="label text-secondary">Quotation
                                    Loss</label><input type="text" class="form-control" readonly=""
                                    value="{{ @$yarnQuotation->yarn_loss_sum_quantity }}">
                            </div>
                            <div class=" col-lg col-sm-4 pe-0 mb-3">
                                <label class="label text-secondary">No
                                    Received</label>
                                <input type="text" class="form-control" readonly="" value="{{ @$noreceivedValue ?? 0 }}"
                                    id="noReceivedValue">
                            </div>
                            <div class=" col-lg col-sm-4 mb-3">
                                <label class="label text-secondary">Store
                                    Address</label>
                                <input type="text" class="form-control" readonly=""
                                    value="{{ @$yarnQuotation->yarnStore->name }}">
                            </div>
                        </div>
                        <hr>
                        <div class="row">

                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number</label>
                                    <input type="text" value="{{ old('po_number',$yarnreceived->po_number) }}"
                                        class="form-control" name="po_number" readonly>
                                    @error('po_number')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 px-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Style</label>
                                    <input type="text" class="form-control " name="style"
                                        value="{{ @$yarnreceived->style }}" readonly>

                                </div>
                            </div>

                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label class="label text-secondary">Status</label>
                                    <input type="text" class="form-control " name="status"
                                        value="{{ @$yarnreceived->status }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-2 pe-0 mb-3">
                                <label class="label text-secondary">Lot No.</label>
                                <input type="text" class="form-control"
                                    oninput="this.value = this.value.replace(/^(\d*\.?\d{0,2}).*$/,'$1')" name="loat_no"
                                    value="{{ @$yarnreceived->lot_number}}">
                            </div>
                            <div class="col-lg-2 pe-0 mb-3">
                                <label class="label text-secondary">Bags</label>
                                <input type="text" class="form-control"
                                    oninput="this.value = this.value.replace(/^(\d*\.?\d{0,2}).*$/,'$1')"
                                    name="bag_count" value="{{ @$yarnreceived->bag_count }}">
                            </div>
                            <div class="col-lg-2 col-sm-6 pe-0">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Quantity(KG)</label>
                                    <input type="hidden" value="{{ @$yarnreceived->quantity }}" id="itemOldRecived">
                                    <input type="number" class="form-control " placeholder="Quantity" id="unit_quantity"
                                        min="1" name="quantity" value="{{ @$yarnreceived->quantity }}" {{
                                        $noreceivedValue==0 ? 'readonly' : '' }}>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Delivery(Store Address)</label>
                                    <select name="delivery_point" id="delivery_point" class="form-control select2">
                                        <option value="" selected disabled>Select Store</option>
                                        @foreach ($storeAddress as $item)
                                        <option value="{{ $item->id }}" {{ $item->id === $yarnreceived->store_id ?
                                            'selected': '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Remarks</label>
                                    <textarea class="form-control" name="remarks"
                                        rows="1">{{ old('remarks',$yarnreceived->remarks) }}</textarea>
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

        $('#unit_quantity').on('input', function(){
            const itemOldRecived = $("#itemOldRecived").val();
            const qotQuantity = $("#qotQuantity").val();
            const inputValue = $(this).val();

           if ( Number(inputValue) > Number(qotQuantity)) {
                alert(`Max allowed is ${qotQuantity}Kg`);
                $(this).val(itemOldRecived);
            }
        })

    });



    function resetSelect(id) {
        $('#'+id).val('').trigger('change');
    }
</script>
@endsection