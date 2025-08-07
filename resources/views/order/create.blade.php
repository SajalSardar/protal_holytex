@extends('layouts.master')
@section('title', 'Create Order')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Create Order</h2>

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
                    <span class="fw-medium">Create Order</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">PO Number <span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <input type="text" class="form-control @error('po_number') is-invalid @enderror"
                                        placeholder="PO Number" name="po_number">
                                    @error('po_number')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Customer Name</label>
                                    <input type="text" class="form-control " name="client_name"
                                        placeholder="Customer Name">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Email Address</label>
                                    <input type="email" class="form-control " name="client_email"
                                        placeholder="Enter Email Address">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Phone</label>
                                    <input type="text" class="form-control " name="client_phone"
                                        placeholder="Phone Number">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Order Date</label>
                                    <input type="date" class="form-control" name="order_date">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Terms</label>
                                    <input type="text" class="form-control" name="terms" placeholder="Terms">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Client Address</label>
                                    <textarea class="form-control" rows="2" name="client_address"
                                        placeholder="Enter Client Address"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Ship Address</label>
                                    <textarea class="form-control" rows="2" name="ship_address"
                                        placeholder="Enter Ship Address"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Upload PO</label>
                                    <input type="file" class="form-control" name="po_file" accept="application/pdf">
                                    <span style="font-size: 12px">Upload Only pdf file.</span>
                                    @error('po_file')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr>
                            <h3 class="mb-lg-4 mb-3">Price & Quantity</h3>
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <table class="table align-middle" id="item_price_table">
                                        <thead>
                                            <tr>
                                                <th>Style</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total Price</th>
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
                                                <td><input class="form-control" name="grand_total" id="grand_total"
                                                        class="fs-18" value="0.0" readonly style="width: 150px">
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
                                        @foreach ($styles as $style)
                                        <option value="{{ $style->style_name }}">{{ $style->style_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
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
                                    <input type="text" readonly class="form-control " placeholder="Unit Price"
                                        id="total_unit_price">
                                </div>
                            </div>
                            <div class="col-lg-1 col-sm-6 align-self-center">
                                <button type="button" id="add_item_btn" class="btn btn-danger fs-15 text-white"
                                    style="height:55px" onclick="addToTable()">Add +
                                </button>
                            </div>

                            <hr>
                            <div class="col-lg-12">
                                <div class="d-flex flex-wrap gap-3">
                                    <button class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button>
                                    <button class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
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
     
         if (!editingRow) {
        // Only check for duplicates if adding a new row
        const existingRows = tableBody.querySelectorAll("tr");
            for (let row of existingRows) {
                const existingStyle = row.cells[0].textContent.trim().toLowerCase();
                if (existingStyle === style.value.trim().toLowerCase()) {
                    alert("This style already exists in the table.");
                    return;
                }
            }
        } else {
            // If editing, only check other rows (not the one being edited)
            const existingRows = tableBody.querySelectorAll("tr");
            for (let row of existingRows) {
                if (row !== editingRow) {
                    const existingStyle = row.cells[0].textContent.trim().toLowerCase();
                    if (existingStyle === style.value.trim().toLowerCase()) {
                        alert("This style already exists in the table.");
                        return;
                    }
                }
            }
        }
        

        
        const total = parseFloat(unit_quantity.value || 0) * parseFloat(unit_price.value || 0);
        const totalVal = total.toFixed(2);

        if (editingRow) {
        // Update existing row
            editingRow.cells[0].innerHTML = `${style.value} <input type="hidden" value="${style.value}" name="style[]">`;
            editingRow.cells[1].innerHTML = `${description.value} <input type="hidden" value="${description.value}" name="description[]">`;
            editingRow.cells[2].innerHTML = `${unit_quantity.value} <input type="hidden" value="${unit_quantity.value}" name="unit_quantity[]">`;
            editingRow.cells[3].innerHTML = `${unit_price.value} <input type="hidden" value="${unit_price.value}" name="unit_price[]">`;
            editingRow.cells[4].innerHTML = `${totalVal} <input type="hidden" value="${totalVal}" name="total_unit_price[]">`;
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
                <td class="text-end">
                <i class="material-symbols-outlined fs-16 text-body edit-btn" style="cursor:pointer;">edit</i>
                <i class="material-symbols-outlined fs-16 text-danger delete-btn" style="cursor:pointer;">delete</i>
                </td>
            `;
            tableBody.appendChild(row);
        }

        calculateTotals();

        // Reset form
       resetSelect();
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

            // ✅ Get text from text node only (exclude input elements)
            let selectedValue = row.cells[0].childNodes[0].textContent.trim();style.value
            $('#style_select').val(selectedValue).trigger('change');
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

    function resetSelect() {
        $('#style_select').val(null).trigger('change');
    }
</script>
@endsection