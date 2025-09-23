@php
$delived_factory_type = $nettingstorestock->delived_factory_type;
$title = $delived_factory_type == 'netting' ? 'Knit Store Edit' : 'Dyeing Store Edit';
@endphp

@extends('layouts.master')
@section('title')
{{ $title }}
@endsection
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">{{ $title }}</h2>

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
                    <span class="fw-medium">{{ $title }}</span>
                </li>
            </ol>
        </nav>
    </div>
    <form action="{{ route('nettingstorestock.update', $nettingstorestock->id) }}" method="POST"
        enctype="multipart/form-data" id="yarn_form">
        @csrf
        @method('PUT')
        <input type="hidden" name="delived_factory_type" value="{{ $delived_factory_type ?? '' }}">
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-white border-0 rounded-3 mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number <span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <input type="text" name="po_number" class="form-control"
                                        value="{{ old('po_number',$nettingstorestock->po_number) }}">
                                    @error('po_number')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Style<span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <input type="text" name="style" class="form-control"
                                        value="{{ old('style',$nettingstorestock->style) }}">
                                    @error('style')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Received Date</label>
                                    <input type="date" name="received_date" class="form-control"
                                        value="{{ old('received_date',$nettingstorestock->received_date ? $nettingstorestock->received_date->format('Y-m-d') : '') }}">
                                    @error('received_date')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 mb-4">
                                <label class="label text-secondary">Remarks</label>
                                <textarea rows="1" class="form-control"
                                    name="remarks">{{old('remarks',$nettingstorestock->remarks)  }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 pe-0 mb-3">
                                <label class="label text-secondary">Lot
                                    No.</label>
                                <input type="text" class="form-control"
                                    oninput="this.value = this.value.replace(/^(\\d*\\.?\\d{0,2}).*$/,'$1')"
                                    name="loat_no" value="{{ old('loat_no',$nettingstorestock->lot_number) }}">
                            </div>
                            <div class="col-lg-3 pe-0 mb-3">
                                <label class="label text-secondary">Bags</label>
                                <input type="text" class="form-control"
                                    oninput="this.value = this.value.replace(/^(\\d*\\.?\\d{0,2}).*$/,'$1')"
                                    name="bag_count" value="{{ old('bag_count',$nettingstorestock->bag_count) }}">
                            </div>
                            <div class="col-lg-3 pe-0 mb-3">
                                <label class="label text-secondary">Quantity(KG)<span
                                        style="color: rgb(205, 2, 2)">*</span></label>
                                <input type="text" class="form-control" name="quantity"
                                    value="{{ old('quantity',$nettingstorestock->quantity) }}">
                                @error('quantity')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label class="label text-secondary">Store
                                    Address<span style="color: rgb(205, 2, 2)">*</span></label>
                                <textarea rows="1" class="form-control"
                                    name="store_address">{{ old('store_address',$nettingstorestock->store_address) }}</textarea>
                                @error('store_address')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-12 my-3">
                                <div class="d-flex flex-wrap gap-3">
                                    <button type="submit"
                                        onclick="this.disabled=true; this.innerHTML='Saving…'; this.form.submit();"
                                        class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                            class="ri-add-line text-white fw-medium"></i> Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
    

</script>
@endsection