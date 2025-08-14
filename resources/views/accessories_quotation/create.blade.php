@extends('layouts.master')
@section('title', 'Accessories Quotation')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Accessories Quotation</h2>

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
                    <span class="fw-medium">Accessories Quotation</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <form action="{{ route('accessoriesquotation.store') }}" method="POST" enctype="multipart/form-data"
                        id="order_submit_form">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="order_id" id="order_id">
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number <span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <select name="po_number" id="po_number" value="{{ old('po_number') }}"
                                        class="form-control select2  @error('po_number') is-invalid @enderror">
                                        <option value="" selected disabled>Select PO Number</option>
                                        @foreach ($ordersPo as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @error('po_number')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Order Date</label>
                                    <input type="date" class="form-control" name="order_date"
                                        value="{{ old('order_date') }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Approximate Delivery Date</label>
                                    <input type="date" class="form-control"
                                        value="{{ old('approximate_delivery_date') }}" name="approximate_delivery_date">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Order number</label>
                                    <input type="text" value="{{ old('order_number') }}" class="form-control"
                                        id="order_number" name="order_number" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Supplier Name</label>
                                    <input type="text" class="form-control " name="supplier_name"
                                        placeholder="Supplier Name" value="{{ old('supplier_name') }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Supplier Phone</label>
                                    <input type="number" class="form-control " name="supplier_phone"
                                        placeholder="Supplier Phone" value="{{ old('supplier_phone') }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Supplier Address</label>
                                    <textarea class="form-control" rows="2" name="supplier_address"
                                        placeholder="Enter Supplier Address">{{ old('supplier_address') }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Shiphing Address</label>
                                    <textarea class="form-control" rows="2" name="shiphing_address"
                                        placeholder="Enter Shiphing Address">{{ old('shiphing_address') }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Remarks</label>
                                    <textarea class="form-control" placeholder="Remarks" name="remarks"
                                        rows="2">{{ old('remarks') }}</textarea>
                                </div>
                            </div>

                            <hr>
                            <h3 class="mb-lg-4 mb-3">Price & Quantity</h3>
                            <div class="col-lg-12 mb-5">
                                <div class="mb-4">
                                    <table class="table align-middle" id="item_price_table">
                                        <thead>
                                            <tr>
                                                <th>Style</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total Price</th>
                                                <th>Unit</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2"><strong class="fs-18">Total</strong></td>
                                                <td><input class="form-control" name="total_quantity"
                                                        id="total_quantity" class="fs-18" value="0.0" readonly
                                                        style="width: 150px"> </td>
                                                <td></td>
                                                <td colspan="2"><input class="form-control" name="grand_total"
                                                        id="grand_total" class="fs-18" value="0.0" readonly
                                                        style="width: 150px">
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Style</label>
                                    <select class="form-select form-control select2" id="style_select">
                                        <option selected disabled value="">Style</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Description</label>
                                    <textarea rows="1" class="form-control" placeholder="Write your note here...."
                                        id="description"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Unit Quantity</label>
                                    <input type="number" class="form-control " placeholder="Quantity" id="unit_quantity"
                                        min="1">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Unit Price</label>
                                    <input type="number" class="form-control " min="1" placeholder="Unit Price"
                                        id="unit_price">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Total Price</label>
                                    <input type="text" readonly class="form-control" placeholder="Unit Price"
                                        id="total_unit_price">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Unit</label>
                                    <select class="form-control select2" id="unit">
                                        <option value="kg">KG</option>
                                        <option value="pc">PC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 align-self-center">
                                <button type="button" id="add_item_btn" class="btn btn-danger fs-15 text-white"
                                    style="height:55px" onclick="addToTable()">Add +
                                </button>
                            </div>

                            <hr>
                            <div class="col-lg-12 mt-5">
                                <div class="d-flex flex-wrap gap-3">
                                    <button class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button>
                                    <button type="button" id="order_submit_btn"
                                        class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                            class="ri-add-line text-white fw-medium"></i> Create Order</button>
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
            var po_number = $('#po_number option:selected').text();
            var order_number = $(this).val();

             if (po_number) {
                
                fetch(`/get-style-by-po-order-detail/${encodeURIComponent(po_number)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // console.log('API response:', data);
                    $('#style_select').empty().append('<option selected disabled value="">Style</option>');

                    let order_id = null;
                    let order_number = null;
                    // Append new options
                    data.forEach(style => {
                        order_id = style.order_id;
                        order_number = style.order_number;
                        $('#style_select').append(
                            $('<option>', {
                                value: style.style,
                                text: style.style,
                            })
                        );
                    });
                    
                    $('#order_id').val(order_id);
                    $('#order_number').val(order_number);
                    // Refresh Select2
                    $('#style_select').trigger('change.select2');

                })
                .catch(error => {
                     console.error('Fetch error:', error);
                });
            }
            
            
        });

        $('#order_submit_btn').on('click', function(){
            const tableBodyData = document.getElementById("item_price_table").getElementsByTagName("tbody")[0];
            const rowCount = tableBodyData.rows.length;
            if(rowCount=== 0){
                alert('Add Style, Description,quantity,price, etc.');
                
            }else{
                $('#order_submit_form').submit();
            }
        });
    });
    
    const unit_price = document.getElementById("unit_price");
    const unit_quantity = document.getElementById("unit_quantity");
    const total_unit_price = document.getElementById("total_unit_price");
    const unit = document.getElementById("unit");
    const add_item_btn = document.getElementById("add_item_btn");
    let editingRow = null;

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


    //add list
    function addToTable() {
        const style = document.getElementById("style_select");
        const description = document.getElementById("description");
        const tableBody = document.getElementById("item_price_table").getElementsByTagName("tbody")[0];

        if (!style.value || style.value == '') {
            alert("Please enter a valid style.");
            return;
        }
        if (!unit_quantity.value) {
            alert("Please enter a unit quantity.");
            return;
        }
        if (!unit_price.value) {
            alert("Please enter a unit price.");
            return;
        }

        //Check for duplicate style (only if not editing)
     
        //  if (!editingRow) {
        // // Only check for duplicates if adding a new row
        // const existingRows = tableBody.querySelectorAll("tr");
        //     for (let row of existingRows) {
        //         const existingStyle = row.cells[0].textContent.trim().toLowerCase();
        //         if (existingStyle === style.value.trim().toLowerCase()) {
        //             alert("This style already exists in the table.");
        //             return;
        //         }
        //     }
        // } else {
        //     // If editing, only check other rows (not the one being edited)
        //     const existingRows = tableBody.querySelectorAll("tr");
        //     for (let row of existingRows) {
        //         if (row !== editingRow) {
        //             const existingStyle = row.cells[0].textContent.trim().toLowerCase();
        //             if (existingStyle === style.value.trim().toLowerCase()) {
        //                 alert("This style already exists in the table.");
        //                 return;
        //             }
        //         }
        //     }
        // }
        

        
        const total = parseFloat(unit_quantity.value || 0) * parseFloat(unit_price.value || 0);
        const totalVal = total.toFixed(2);

        if (editingRow) {
        // Update existing row
            editingRow.cells[0].innerHTML = `${style.value} <input type="hidden" value="${style.value}" name="style[]">`;
            editingRow.cells[1].innerHTML = `${description.value} <input type="hidden" value="${description.value}" name="description[]">`;
            editingRow.cells[2].innerHTML = `${unit_quantity.value} <input type="hidden" value="${unit_quantity.value}" name="unit_quantity[]">`;
            editingRow.cells[3].innerHTML = `${unit_price.value} <input type="hidden" value="${unit_price.value}" name="unit_price[]">`;
            editingRow.cells[4].innerHTML = `${totalVal} <input type="hidden" value="${totalVal}" name="total_unit_price[]">`;
            editingRow.cells[5].innerHTML = `${unit.value} <input type="hidden" value="${unit.value}" name="unit[]">`;
            editingRow = null;
            add_item_btn.textContent = 'Add +';
        } else {
            // Create a new row with visible values and hidden inputs
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${style.value} <input type="hidden" value="${style.value}" name="style[]"></td>
                <td>${description.value} <input type="hidden" value="${description.value}" name="description[]"></td>
                <td>${unit_quantity.value} <input type="hidden" value="${unit_quantity.value}" name="unit_quantity[]"></td>
                <td>${unit_price.value} <input type="hidden" value="${unit_price.value}" name="unit_price[]"></td>
                <td>${totalVal} <input type="hidden" value="${totalVal}" name="total_unit_price[]"></td>
                <td>${unit.value} <input type="hidden" value="${unit.value}" name="unit[]"></td>
                <td class="text-end">
                <i class="material-symbols-outlined fs-16 text-body edit-btn" style="cursor:pointer;">edit</i>
                <i class="material-symbols-outlined fs-16 text-danger delete-btn" style="cursor:pointer;">delete</i>
                </td>
            `;
            tableBody.appendChild(row);
        }

        calculateTotals();

        // Reset form
        resetSelect('style_select');
        // resetSelect('unit');
        description.value = "";
        unit_quantity.value = "";
        unit_price.value = "";
        total_unit_price.value = "";
    }

    //edit row
    document.querySelector("#item_price_table tbody").addEventListener("click", function (e) {
        const row = e.target.closest("tr");

        if (e.target.classList.contains("delete-btn")) {
            if (row) row.remove();
            calculateTotals();
        }

        if (e.target.classList.contains("edit-btn")) {
            const style = document.getElementById("style_select");
            const description = document.getElementById("description");
            const unit_quantity = document.getElementById("unit_quantity");
            const unit_price = document.getElementById("unit_price");
            const total_unit_price = document.getElementById("total_unit_price");
            const unit = document.getElementById("unit");

            // ✅ Get text from text node only (exclude input elements)
            let selectedValue = row.cells[0].childNodes[0].textContent.trim();
            let unitValue = row.cells[5].childNodes[0].textContent.trim();
            $('#style_select').val(selectedValue).trigger('change');
            $('#unit').val(unitValue).trigger('change');
            description.value = row.cells[1].childNodes[0].textContent.trim();
            unit_quantity.value = row.cells[2].childNodes[0].textContent.trim();
            unit_price.value = row.cells[3].childNodes[0].textContent.trim();
            total_unit_price.value = row.cells[4].childNodes[0].textContent.trim();

            add_item_btn.textContent = 'Update';
            editingRow = row;
        }
    });

    function calculateTotals() {
        const tableBody = document.querySelector("#item_price_table tbody");
        const rows = tableBody.querySelectorAll("tr");

        let totalPrice = 0;
        let totalQuantity = 0;

        rows.forEach(row => {
            const qty = parseFloat(row.cells[2].textContent) || 0;         // Quantity column (3rd)
            const total = parseFloat(row.cells[4].textContent) || 0;       // Total column (5th)

            totalQuantity += qty;
            totalPrice += total;
        });

        document.getElementById("total_quantity").value = totalQuantity;
        document.getElementById("grand_total").value = totalPrice.toFixed(2);
    }

    function resetSelect(id) {
        $('#'+id).val(null).trigger('change');
    }
</script>
@endsection