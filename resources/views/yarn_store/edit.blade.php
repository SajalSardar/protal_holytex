@php
$delived_factory_type = $yarnstorestock->delived_factory_type;
$title = $delived_factory_type == 'yarn' ? 'Edit Yarn Stock' : 'Edit Dyed Yarn Stock';
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
    <form action="{{ route('yarnstorestock.update', $yarnstorestock->id) }}" method="POST" enctype="multipart/form-data"
        id="yarn_form">
        @csrf
        @method('PUT')
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
                                        value="{{ old('po_number',$yarnstorestock->po_number) }}" readonly>
                                    @error('po_number')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Style<span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <input type="text" name="style" class="form-control"
                                        value="{{ old('style',$yarnstorestock->style) }}" readonly>
                                    @error('style')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="label text-secondary">Status</label>
                                    <select name="status" class="form-select form-control status_select select2"
                                        id="status_select">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="pending" {{ $yarnstorestock->status === "pending" ? 'selected'
                                            :
                                            '' }}>Pending</option>
                                        <option value="received" {{ $yarnstorestock->status === "received" ?
                                            'selected' :
                                            '' }}>Received</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 mb-4">
                                <label class="label text-secondary">Remarks</label>
                                <textarea rows="1" class="form-control"
                                    name="remarks">{{old('remarks',$yarnstorestock->remarks)  }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <h3>RECEIVED CHALLAN INFO</h3>
                            </div>
                            <div class="col-lg-3 col-sm-4">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Challan No.</label>
                                    <input type="text" name="challan_number" class="form-control"
                                        value="{{old('challan_number',$yarnstorestock->challan_number)  }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-4">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Vehicle Number</label>
                                    <input type="text" name="vehicle_number" class="form-control"
                                        value="{{old('vehicle_number',$yarnstorestock->vehicle_number)  }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-4">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Challan Date</label>
                                    <input type="date" name="challan_date" class="form-control"
                                        value="{{old('challan_date', $yarnstorestock->challan_date ? $yarnstorestock->challan_date->format('Y-m-d') : '' )  }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-4">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Received Date</label>
                                    <input type="date" name="received_date" class="form-control"
                                        value="{{old('received_date',$yarnstorestock->received_date ? $yarnstorestock->received_date->format('Y-m-d') : '')  }}">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-4">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Upload Challan</label>
                                    <input type="file" name="challan_file" class="form-control">
                                    <p class="fs-12">Uploaded file size 512kb &amp; File type jpg,png </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 pe-0 mb-3">
                                <label class="label text-secondary">Lot
                                    No.</label>
                                <input type="text" class="form-control"
                                    oninput="this.value = this.value.replace(/^(\\d*\\.?\\d{0,2}).*$/,'$1')"
                                    name="loat_no" value="{{ old('loat_no',$yarnstorestock->lot_number) }}">
                            </div>
                            <div class="col-lg-2 pe-0 mb-3">
                                <label class="label text-secondary">Bags</label>
                                <input type="text" class="form-control"
                                    oninput="this.value = this.value.replace(/^(\\d*\\.?\\d{0,2}).*$/,'$1')"
                                    name="bag_count" value="{{ old('bag_count',$yarnstorestock->bag_count) }}">
                            </div>
                            <div class="col-lg-3 pe-0 mb-3">
                                <label class="label text-secondary">Description<span
                                        style="color: rgb(205, 2, 2)">*</span></label>
                                <input type="text" class="form-control" name="description"
                                    value="{{ old('description', @$yarnstorestock->yarnQty->description ?? ($yarnstorestock->description ?? '')) }}"
                                    {{ @$yarnstorestock->yarnQty->description ? 'readonly' : ''}}>
                                @error('description')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-2 pe-0 mb-3">
                                <label class="label text-secondary">Quantity(KG)<span
                                        style="color: rgb(205, 2, 2)">*</span></label>
                                <input type="text" class="form-control" name="quantity"
                                    value="{{ old('quantity',$yarnstorestock->quantity) }}">
                                @error('quantity')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label class="label text-secondary">Store
                                    Address<span style="color: rgb(205, 2, 2)">*</span></label>

                                <select name="store_address" id="" class="select2 form-control">
                                    @foreach ($storeAddress as $item)
                                    <option value="{{ $item->id }}" {{ $item->id === $yarnstorestock->store_id ?
                                        'selected' : ''}}>{{ $item->name }}</option>
                                    @endforeach
                                </select>

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