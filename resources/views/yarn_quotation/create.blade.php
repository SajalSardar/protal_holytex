@extends('layouts.master')
@section('title', 'Yarn Quotation')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Yarn Quotation </h2>

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
                    <span class="fw-medium">Yarn Quotation</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <form action="{{ route('yarnquotation.store') }}" method="POST" enctype="multipart/form-data"
                        id="yarn_form">
                        @csrf
                        <div class="row">
                            <input type="hidden" id="order_id" name="order_id">
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number <span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <select name="po_number" id="po_number" value="{{ old('po_number') }}"
                                        class="form-control select2  @error('po_number') is-invalid @enderror">
                                        <option value="" selected disabled>Select PO Number</option>
                                        @foreach ($orders as $item)
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
                                    <label class="label text-secondary">Order number</label>
                                    <input type="text" value="{{ old('order_number') }}" class="form-control"
                                        id="order_number" name="order_number" readonly>
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
                            <h3 class="mb-lg-4 mb-3">Price & Quantity</h3>
                            <div class="col-lg-12 mb-5">
                                <div class="mb-4">
                                    <table class="table align-middle" id="item_price_table">
                                        <thead>
                                            <tr>
                                                <th>Style</th>
                                                <th>Description</th>
                                                <th>Quantity(KG)</th>
                                                <th>Unit Price(TK)</th>
                                                <th>Total Price(TK)</th>
                                                <th>Yarn Factory</th>
                                                <th>Delivery Place</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2"><strong class="fs-18">Total</strong></td>
                                                <td><input class="fs-18" name="total_quantity" id="total_quantity"
                                                        value="0.0" readonly style="width: 150px;">KG
                                                </td>
                                                <td></td>
                                                <td><input class=" fs-18" name="grand_total" id="grand_total"
                                                        value="0.0" readonly style="width: 180px">TK
                                                </td>
                                                <td></td>
                                                <td></td>
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

                            <div class="col-md-3">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Yarn Description</label>
                                    <textarea rows="1" class="form-control" placeholder="Write your note here...."
                                        id="description"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Quantity(KG)</label>
                                    <input type="number" class="form-control " placeholder="Quantity" id="unit_quantity"
                                        min="1">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Price(TK)</label>
                                    <input type="number" class="form-control " min="1" placeholder="Unit Price"
                                        id="unit_price">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Total Price</label>
                                    <input type="text" readonly class="form-control " placeholder="Unit Price"
                                        id="total_unit_price">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Yarn Factory</label>
                                    <select name="yarn_factory" id="yarn_factory" class="form-control select2">
                                        <option value="" selected disabled>Select Factory</option>
                                        @foreach ($yarnFactory as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Delivery Point</label>
                                    <select name="delivery_point" id="delivery_point" class="form-control select2">
                                        <option value="" selected disabled>Select Netting Factory</option>
                                        @foreach ($nettingFactory as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1 col-sm-6 align-self-center">
                                <button type="button" id="add_item_btn" class="btn btn-danger fs-15 text-white"
                                    style="height:55px" onclick="addToTable()">Add +
                                </button>
                            </div>

                            <hr>
                            <div class="col-lg-12 mt-5">
                                <div class="d-flex flex-wrap gap-3">
                                    <button class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button>
                                    <button type="button" id="submit_button"
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


        $('#po_number').on('change',function(){
            var po_number = $('#po_number option:selected').text();
            var order_number = $(this).val();

             if (po_number) {
                // Optional: show a loading message
                // console.log('Fetching data for PO:', po_number);

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
    
    const unit_price = document.getElementById("unit_price");
    const unit_quantity = document.getElementById("unit_quantity");
    const total_unit_price = document.getElementById("total_unit_price");
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
        const yarn_factory = document.getElementById("yarn_factory");
        const delivery_point = document.getElementById("delivery_point");
        const tableBody = document.getElementById("item_price_table").getElementsByTagName("tbody")[0];

        if (!style.value || style.value == '') {
            alert("Please enter a valid style.");
            return;
        }
        if (!description.value || description.value == '') {
            alert("Please enter yarn description.");
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
        if (!yarn_factory.value) {
            alert("Please Select yarn factory.");
            return;
        }
        if (!delivery_point.value) {
            alert("Please Select netting factory.");
            return;
        }

        //Check for duplicate style (only if not editing)
     
        //  if (!editingRow) {
        // const existingRows = tableBody.querySelectorAll("tr");
        //     for (let row of existingRows) {
        //         const existingStyle = row.cells[0].textContent.trim().toLowerCase();
        //         if (existingStyle === style.value.trim().toLowerCase()) {
        //             alert("This style already exists in the table.");
        //             return;
        //         }
        //     }
        // } else {
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
            editingRow.cells[5].innerHTML = `${yarn_factory.options[yarn_factory.selectedIndex].text} <input type="hidden" value="${yarn_factory.value}" name="yarn_factory[]">`;
            editingRow.cells[6].innerHTML = `${delivery_point.options[delivery_point.selectedIndex].text} <input type="hidden" value="${delivery_point.value}" name="delivery_point[]">`;
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
                <td>${yarn_factory.options[yarn_factory.selectedIndex].text} <input type="hidden" value="${yarn_factory.value}" name="yarn_factory[]"></td>
                <td>${delivery_point.options[delivery_point.selectedIndex].text} <input type="hidden" value="${delivery_point.value}" name="delivery_point[]"></td>
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
        description.value = "";
        unit_quantity.value = "";
        unit_price.value = "";
        total_unit_price.value = "";
        resetSelect('yarn_factory');
        resetSelect('delivery_point');
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
            const yarn_factory = document.getElementById("yarn_factory");
            const delivery_point = document.getElementById("delivery_point");

            // ✅ Get text from text node only (exclude input elements)
            let selectedValue = row.cells[0].childNodes[0].textContent.trim();
            $('#style_select').val(selectedValue).trigger('change');
            description.value = row.cells[1].childNodes[0].textContent.trim();
            unit_quantity.value = row.cells[2].childNodes[0].textContent.trim();
            unit_price.value = row.cells[3].childNodes[0].textContent.trim();
            total_unit_price.value = row.cells[4].childNodes[0].textContent.trim();
            let yarn_factory_value = row.cells[5].childNodes[1].value.trim();
            let netting_factory_value = row.cells[6].childNodes[1].value.trim();
            $('#yarn_factory').val(yarn_factory_value).trigger('change');
            $('#delivery_point').val(netting_factory_value).trigger('change');


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