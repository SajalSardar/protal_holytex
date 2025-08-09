@extends('layouts.master')
@section('title', 'Netting')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Netting</h2>

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
                    <span class="fw-medium">Netting</span>
                </li>
            </ol>
        </nav>
    </div>
    <form action="{{ route('netting.store') }}" method="POST" enctype="multipart/form-data" id="yarn_form">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-white border-0 rounded-3 mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <input type="hidden" id="order_id" name="order_id">
                            <input type="hidden" id="order_number" name="order_number">
                            <div class="col-lg-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number <span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <select name="po_number" id="po_number"
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
                        </div>
                    </div>
                </div>
            </div>
            <div id="show_all_yarn_item"></div>
        </div>
    </form>
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

                fetch(`/get-yarn-by-po/${encodeURIComponent(po_number)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    //console.log('API response:', response);
                    return response.json();
                })
                .then(data => {
                    let order_id = null;
                    let order_number = null;
                    // console.log('API response:', data);
                    let display_div = $('#show_all_yarn_item');
                    let singleItem = `<div class="col-lg-12">
                                    <div class="card bg-white border-0 rounded-3 mb-4">
                                        <div class="card-body p-4">
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <h3>CHALLAN INFO</h3>
                                                </div>
                                                <div class="col-lg-3 col-sm-4">
                                                    <div class="form-group mb-4">
                                                        <label class="label text-secondary">Challan No.</label>
                                                        <input type="text" name="challan_no" class="form-control">
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
                                                        <label class="label text-secondary">DO Number</label>
                                                        <input type="text" name="do_number" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-sm-4">
                                                    <div class="form-group mb-4">
                                                        <label class="label text-secondary">Truck No.</label>
                                                        <input type="text" name="truck_no" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-4">
                                                    <div class="form-group mb-4">
                                                        <label class="label text-secondary">Upload Challan</label>
                                                        <input type="file" name="challan" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                    // Append new options
                    data.forEach(item => {
                        order_id = item.order_id;
                        order_number = item.order_number;
                        singleItem +=`<div class="card bg-white border-0 rounded-3 mb-5">
                                        <div class="card-body p-4">
                                        <div class="row">
                                            <input type="hidden" name="items[${item.id}][yarn_id]" value="${item.id}">
                                            <input type="hidden" name="items[${item.id}][description]" value="${item.description}">
                                            <input type="hidden" name="items[${item.id}][yarn_factory_id]" value="${item.yarn_factory_id}">
                                            <input type="hidden" name="items[${item.id}][netting_factory_id]" value="${item.netting_factory_id}">
                                            <div class="col-12">
                                                <h3 class="mb-4" style="text-transform:uppercase">Yarn Description:- ${item.description}</h3>
                                            </div>
                                            <div class="col-lg-2 mb-3"><label class="label text-secondary">Style</label><input type="text" class="form-control" name="items[${item.id}][style]" readonly value="${item.style}"></div>
                                            <div class="col-lg-2 mb-3"><label class="label text-secondary">Quantity(KG)</label><input type="text" class="form-control" name="items[${item.id}][quantity]" readonly value="${item.quantity}"></div>
                                            <div class="col-lg-4 mb-3"><label class="label text-secondary">Yarn Factory</label><textarea class="form-control" readonly>Name:${item.yarn_factory.name}\nAddress:${item.yarn_factory.address}</textarea></div>
                                            <div class="col-lg-4 mb-3"><label class="label text-secondary">Netting Factory</label><textarea class="form-control" readonly>Name:${item.netting_factory.name}\nAddress:${item.netting_factory.address}</textarea></div>
                                        </div>
                                        <hr>
                                        <div  class="row mt-3">
                                            <div class="col-12">
                                                <h4 class="mb-4" style="text-transform:uppercase">Receive Info</h4>
                                            </div>
                                            <div class="col-lg-3 mb-3"><label class="label text-secondary">Lot No.</label><input type="text" class="form-control" name="items[${item.id}][loat_no]"></div>
                                            <div class="col-lg-2 mb-3"><label class="label text-secondary">NO. of Bags</label><input type="text" class="form-control" name="items[${item.id}][bag_number]"></div>
                                            <div class="col-lg-3 mb-3"><label class="label text-secondary">Weight(KG)</label><input type="number" max="${item.quantity}" class="form-control" oninput="limitWeightValue(this)" name="items[${item.id}][weight]"></div>
                                            <div class="col-lg-4 mb-3"><label class="label text-secondary">Remarks</label><textarea rows="1" class="form-control" name="items[${item.id}][remarks]"></textarea></div>
                                        </div>
                                     </div>
                                </div>
                            `;
                    });
                    singleItem +=`<div class="col-lg-12 my-3">
                                    <div class="d-flex flex-wrap gap-3">
                                        <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                                class="ri-add-line text-white fw-medium"></i> Create</button>
                                    </div>
                                </div>`;
                    display_div.html(singleItem);
                    $('#order_id').val(order_id);
                     $('#order_number').val(order_number);

                })
                .catch(error => {
                     console.error('Fetch error:', error);
                });
            }
            
            
        });

        $('#submit_button').on('click', function(){
            const tableBodyData = document.getElementById("item_price_table").getElementsByTagName("tbody")[0];
            const rowCount = tableBodyData.rows.length;
            if(rowCount=== 0){
                alert('Add Yarn Description,quantity,price, etc.');
                
            }else{
                $('#yarn_form').submit();
            }
        });

        

    });
    
    function limitWeightValue(input){
        let maxVal = parseFloat(input.max);
        let val = parseFloat(input.value);
        if (val > maxVal) {
            alert(`Max allowed is ${maxVal}Kg`);
            input.value = maxVal;
        }
    }

    function resetSelect(id) {
        $('#'+id).val(null).trigger('change');
    }
</script>
@endsection