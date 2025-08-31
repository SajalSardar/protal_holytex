@extends('layouts.master')
@section('title', 'Dyeing Quotation List')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div class="d-flex">
            <h2 class="mb-0">Dyeing Quotation List </h2>
            <a href="{{ route('dyeingquotation.create') }}" class="ms-5 btn btn-primary py-2 px-4 fw-medium fs-16">+
                Create
                Dyeing Quotation</a>
        </div>

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
                    <span class="fw-medium">Dyeing Quotation List</span>
                </li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="default-table-area style-two default-table-width">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Order Number</th>
                                        <th>PO</th>
                                        <th>Style</th>
                                        <th>Quantity(kg)</th>
                                        <th>Rate(TK)</th>
                                        <th>Total(TK)</th>
                                        <th>Approx. delivery_date</th>
                                        <th>Status</th>
                                        <th>Netting Factory</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dyeings as $item)
                                    <tr>
                                        <td>{{ $item->order_number }}</td>
                                        <td>{{ $item->po_number }}</td>
                                        <td>{{ $item->style }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->total_price }}</td>
                                        <td>{{ $item->approximate_delivery_date }}</td>
                                        <td>{{ Str::ucfirst($item->status) }}</td>
                                        <td>
                                            Name:{{ $item->dyeingFactory->name }} <br>
                                            Address:{{ $item->dyeingFactory->address }}

                                        </td>
                                        <td>
                                            <div class="dropdown text-end">
                                                <a class="btn btn-primary dropdown-toggle" href="#" role="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </a>

                                                <ul class="dropdown-menu dropdown-menu-end table_action_btn">
                                                    <li><a class="dropdown-item py-2" href="#"
                                                            onclick="showStatusModal('{{ $item->style }}', '{{ $item->po_number }}','{{ $item->status }}')">
                                                            <i
                                                                class="material-symbols-outlined fs-16 text-primary">contact_page</i>
                                                            Update Status</a></li>
                                                    <li><a class="dropdown-item py-2" href="#"> <i
                                                                class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                                            View</a></li>
                                                    <li><a class="dropdown-item py-2" href="#"><i
                                                                class="material-symbols-outlined fs-16 text-body">edit</i>
                                                            Edit</a></li>
                                                    <li><a class="dropdown-item py-2" href="#"><i
                                                                class="material-symbols-outlined fs-16 text-danger">delete</i>
                                                            Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">No Data Found!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex-grow-1"></div>

<!-- Modal -->
<div class="modal fade" id="status_change_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('dyeing.qty.update.status') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Change Netting Quotation Status</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="style" id="style_nu">
                    <div class="form-group mb-2">
                        <label for="">PO Number</label>
                        <input type="text" class="form-control" name="po_number" id="po_number" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Select Status</label>
                        <select name="status" class="form-select form-control status_select">
                            <option value="" disabled selected>Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="finished">Finished</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary text-white"
                        onclick="this.disabled=true; this.innerHTML='Saving…'; this.form.submit();">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    function showStatusModal(style, po_number,status){
        const modalEl = document.getElementById('status_change_modal');
        const myModal = new bootstrap.Modal(modalEl, {
            keyboard: false
        });
        myModal.show();

        let styleN = $('#style_nu');
        let getpo_number = $('#po_number');
        styleN.val(style);
        getpo_number.val(po_number);
        $('.status_select').val(status);
    }
</script>
@endsection