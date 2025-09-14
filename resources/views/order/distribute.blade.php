@php
$po_number = request()->po_number ?? '';
@endphp
@extends('layouts.master')
@section('title', 'Distribute Order')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Distribute Order</h2>

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
                    <span class="fw-medium">Distribute Order</span>
                </li>
            </ol>
        </nav>
    </div>
    <form action="{{ route('order.delivered') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">

            <div class="col-lg-12">
                <div class="card bg-white border-0 rounded-3">
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

            @forelse ($order->orderDetails as $item)
            @php
            $noRecQty = $item->unit_quantity - $item->order_delivery_qty_sum_quantity;
            $knitReceived = getKintByGermentsReceived($item->po_number,$item->style);
            // dd(count($knitReceived));
            @endphp
            @if (count($knitReceived) > 0)
            <div class="accordion">
                <div class="accordion-item mt-5">
                    <h2 class="accordion-header">
                        <button style="background: #605dff;"
                            class="accordion-button text-uppercase text-white rounded-bottom-0" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse_{{ $item->style }}"
                            aria-expanded="true">
                            <strong>Style: {{ $item->style }}</strong>
                        </button>
                    </h2>
                    <div id="collapse_{{ $item->style }}" class="accordion-collapse collapse show">
                        <div class="accordion-body p-0">
                            @if ($noRecQty > 0)
                            <div class="card bg-white border-0 rounded-3 rounded-top-0">
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-lg-3 ps-0 mb-3">
                                            <label class="label text-secondary">Style</label>
                                            <input type="text" class="form-control" value="{{ $item->style }}" readonly>
                                        </div>
                                        <div class="col-lg-3 pe-0 mb-3">
                                            <label class="label text-secondary">Description</label>
                                            <input type="text" class="form-control" value="{{ $item->description }}"
                                                readonly>
                                        </div>
                                        <div class="col-lg-3 pe-0 mb-3">
                                            <label class="label text-secondary">Order Quantity(PC)</label>
                                            <input type="text" class="form-control" id="order_qty_{{ $item->style }}"
                                                value="{{ $item->unit_quantity }}" readonly>
                                        </div>
                                        <div class="col-lg-3 pe-0 mb-3">
                                            <label class="label text-secondary">Not Distributed(PC)</label>
                                            <input type="text" id="nodist_{{ $item->style }}"
                                                value="{{ $noRecQty ?? 0 }}" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        @foreach ($item->orderDeliveryQty as $deliver)
                                        <div class="col-md-6">
                                            <p class="p-0 m-0"> <strong>Delivery:</strong> {{ $deliver->quantity }}PC -
                                                <strong>Date:</strong> {{
                                                $deliver->created_at->format('d-m-Y') }} - <strong>Garments:</strong> {{
                                                $deliver->garmentsFactory->name }}
                                            </p>
                                        </div>
                                        @endforeach
                                    </div>
                                    @foreach ($knitReceived as $knitrc)
                                    <div class="row">
                                        <div class="col-md ps-0 mb-3">
                                            <input type="hidden" value="{{ $knitrc->garments_factory_id }}"
                                                name="items[{{ $item->style }}][{{ $knitrc->garments_factory_id }}][garments_factory_id]">
                                            <input type="hidden" value="{{ $order->id }}"
                                                name="items[{{ $item->style }}][{{ $knitrc->garments_factory_id }}][order_id]">
                                            <input type="hidden" value="{{ $item->id }}"
                                                name="items[{{ $item->style }}][{{ $knitrc->garments_factory_id }}][order_details_id]">

                                            <label class="label text-secondary">Lot No.</label>
                                            <input type="text" class="form-control"
                                                oninput="this.value = this.value.replace(/^(\d*\.?\d{0,2}).*$/,'$1')"
                                                name="items[{{ $item->style }}][{{ $knitrc->garments_factory_id }}][loat_no]">
                                        </div>
                                        <div class="col-md pe-0 mb-3">
                                            <label class="label text-secondary">Bags</label>
                                            <input type="text" class="form-control"
                                                oninput="this.value = this.value.replace(/^(\d*\.?\d{0,2}).*$/,'$1')"
                                                name="items[{{ $item->style }}][{{ $knitrc->garments_factory_id }}][bag_count]">
                                        </div>
                                        <div class="col-md pe-0 mb-3">
                                            <label class="label text-secondary">Quantity(PC)</label>
                                            <input type="text" class="form-control quantity_{{ $item->style }}"
                                                oninput="limitQtyValue(this,'{{ $item->style }}')"
                                                name="items[{{ $item->style }}][{{ $knitrc->garments_factory_id }}][quantity]">
                                        </div>
                                        <div class="col-md mb-3">
                                            <label class="label text-secondary">Remarks</label>
                                            <textarea rows="1" class="form-control"
                                                name="items[{{ $item->style }}][{{ $knitrc->garments_factory_id }}][remarks]"></textarea>
                                        </div>
                                        <div class="col-md pe-0 mb-3">
                                            <label class="label text-secondary">Total Kint Received(KG)</label>
                                            <input type="text" class="form-control" value="{{
                                                    $knitrc->total_quantity }}" readonly>
                                        </div>
                                        <div class="col-md pe-0 mb-3">
                                            <label class="label text-secondary">Garments Factory(KG)</label>
                                            <input type="text" class="form-control" value="{{
                                                    $knitrc->garmentsFactory->name }}" readonly>
                                        </div>
                                    </div>
                                    @if (count($knitReceived)>1)
                                    <hr>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="alert alert-success m-3"> Delivery Done</div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            @endif
            @empty
            <div class="alert alert-info">No Data Found!</div>
            @endforelse

            <div class="col-lg-12 my-3">
                <div class="d-flex flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"
                        onclick="this.disabled=true; this.innerHTML='Saving…'; this.form.submit();"> <i
                            class="ri-add-line text-white fw-medium"></i> Submit</button>
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
    
    function limitQtyValue(input, idPrefix){
         input.value = input.value.replace(/^(\d*\.?\d{0,2}).*$/, '$1');
    
        let nodist_= document.getElementById('nodist_'+idPrefix).value;

        let totalVal = 0;
        document.querySelectorAll('.quantity_' + idPrefix).forEach(el => {
            totalVal += parseFloat(el.value) || 0;
        });
        if (totalVal > nodist_) {
            alert(`Max allowed is ${nodist_}PC`);
            input.value = 0;
        }
    }


</script>
@endsection