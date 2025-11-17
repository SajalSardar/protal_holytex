@php
$po_number = request()->po_number ?? '';
@endphp
@extends('layouts.master')
@section('title', 'Yarn Received')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Yarn Receiving</h2>

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
                    <span class="fw-medium">Yarn Received</span>
                </li>
            </ol>
        </nav>
    </div>
    <form action="{{ route('yarnreceived.store') }}" method="POST" enctype="multipart/form-data" id="yarn_form">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-white border-0 rounded-3 mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <input type="hidden" id="order_id" name="order_id">
                            <input type="hidden" id="order_number" name="order_number">
                            <div class="col-lg-4">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number <span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <input type="text" name="po_number" id="po_number"
                                        class="form-control  @error('po_number') is-invalid @enderror"
                                        value="{{@$yarnQuotation->po_number}}" readonly>
                                    @error('po_number')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
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
            </div>

            <div class="accordion mb-5">
                <div class="accordion-item">
                    <h2 class="accordion-header mb-3">
                        <button style="background: #605dff;" class="accordion-button text-uppercase text-white"
                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse${key}">
                            <strong>Style: {{ @$yarnQuotation->style }}</strong>
                        </button>
                    </h2>
                    <div id="collapse${key}" class="accordion-collapse collapse show">
                        <div class="accordion-body p-0 px-2">


                            @php
                            $quotation = $yarnQuotation->quantity ?? 0;
                            $allTotalRecevied =
                            $yarnQuotation->yarn_received_sum_quantity +
                            $yarnQuotation->yarn_loss_sum_quantity;
                            $noreceived = $quotation - $allTotalRecevied;
                            @endphp
                            <div class="row my-4">
                                <input type="hidden" name="style" value="{{@ $yarnQuotation->style}}">

                                <input type="hidden" name="yarn_factory_id"
                                    value="{{ @$yarnQuotation->yarn_factory_id}}">

                                <input type="hidden" name="yarn_id" value="{{ @$yarnQuotation->id}}">

                                <div class="col-lg col-sm-4 pe-0 mb-3">
                                    <label class="label text-secondary">Description</label>
                                    <input class="form-control" name="description"
                                        value="{{ @$yarnQuotation->description}}" readonly>
                                </div>
                                <div class="col-lg col-sm-4 pe-0 mb-3">
                                    <label class="label text-secondary">Quotation(KG)</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ @$yarnQuotation->quantity}}">
                                </div>
                                <div class="col-lg col-sm-4 pe-0 mb-3">
                                    <label class="label text-secondary">Yarn
                                        Factory</label>
                                    <input class="form-control" readonly
                                        value="{{ @$yarnQuotation->yarnFactory->name}}">
                                </div>
                                <div class="col-lg col-sm-4 pe-0 mb-3">
                                    <label class="label text-secondary">Received</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ @$yarnQuotation->yarn_received_sum_quantity}}">
                                </div>
                                <div class="col-lg col-sm-4 pe-0 mb-3">
                                    <label class="label text-secondary">Quotation
                                        Loss</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ @$yarnQuotation->yarn_loss_sum_quantity ?? 0}}">
                                </div>
                                <div class="col-lg col-sm-4 pe-0 mb-3">
                                    <label class="label text-secondary">No
                                        Received</label>
                                    <input type="text" class="form-control" readonly value="{{ @$noreceived }}">
                                </div>
                                <div class="col-lg col-sm-4 mb-3">
                                    <label class="label text-secondary">Store
                                        Address</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ @$yarnQuotation->yarnStore->name}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <h4 class="fs-18 text-primary">Receive Quantity</h4>
                                </div>
                            </div>
                            {{-- <div class="col-12">
                                <div class="alert alert-success mb-3">Total Quotation Received Done!</div>
                            </div> --}}
                            <div class="row">
                                <div class="col-lg-2 pe-0 mb-3">
                                    <label class="label text-secondary">Lot
                                        No.</label>
                                    <input type="text" class="form-control"
                                        oninput="this.value = this.value.replace(/^(\\d*\\.?\\d{0,2}).*$/,'$1')"
                                        name="loat_no">
                                </div>
                                <div class="col-lg-1 pe-0 mb-3">
                                    <label class="label text-secondary">Bags</label>
                                    <input type="text" class="form-control"
                                        oninput="this.value = this.value.replace(/^(\\d*\\.?\\d{0,2}).*$/,'$1')"
                                        name="bag_count">
                                </div>
                                <div class="col-lg-2 pe-0 mb-3">
                                    <label class="label text-secondary">Yarn(KG)</label>
                                    <input type="text" max="{{ @$noreceived }}" id="netting_item" class="form-control"
                                        oninput="limitWeightValue(this)" name="yarn">
                                </div>
                                <div class="col-lg-2 pe-0 mb-3">
                                    <label class="label text-secondary">Loss(KG)</label>
                                    <input type="text" class="form-control" max="{{ @$noreceived }}" id="loss_item"
                                        oninput="limitWeightValue(this)" name="loss">
                                </div>
                                <div class="col-lg-2 pe-0 mb-3">
                                    <label class="label text-secondary">Store
                                        Address</label>
                                    <select class="form-control address_select_box select2" name="store_id">
                                        <option value="" selected>Selete Store</option>
                                        @foreach ($storeAddress as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <label class="label text-secondary">Remarks</label>
                                    <textarea rows="1" class="form-control" name="remarks"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 my-3">
                <div class="d-flex flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"
                        onclick="this.disabled=true; this.innerHTML='Saving…'; this.form.submit();"> <i
                            class="ri-add-line text-white fw-medium"></i> Create</button>
                </div>
            </div>

        </div>
    </form>
</div>

<div class="flex-grow-1"></div>
@endsection


@section('script')
<script>
    $(function() {
        $('.select2').select2();  

    });
    
    function limitWeightValue(input){
         input.value = input.value.replace(/^(\d*\.?\d{0,2}).*$/, '$1');
        
        let netting_= $('#netting_item').val();
        let loss_= $('#loss_item').val();

        let maxVal = parseFloat(input.max);

        let totalVal = (Number(netting_) || 0) + (Number(loss_) || 0);
        console.log(maxVal);
        if (totalVal > maxVal) {
            alert(`Max allowed is ${maxVal}Kg`);
            input.value = 0;
        }
    }

</script>
@endsection