@extends('layouts.master')
@section('title', 'Order List')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Order List</h2>

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
                    <span class="fw-medium">Order List</span>
                </li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="default-table-area">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>PO PDF</th>
                                        <th>ID</th>
                                        <th>PO</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Order Date</th>
                                        <th>Quantity</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $item)
                                    <tr>
                                        <td>
                                            @if ($item->po_file)
                                            <a href="{{ asset('storage/'.$item->po_file) }}" target="_blank"><span
                                                    class="material-symbols-outlined">
                                                    picture_as_pdf
                                                </span></a>
                                            @endif
                                        </td>
                                        <td>{{ $item->order_number }}</td>
                                        <td>{{ $item->po_number }}</td>
                                        <td>{{ $item->client_email }}</td>
                                        <td>{{ $item->client_phone }}</td>
                                        <td>{{ $item->order_date }}</td>
                                        <td>{{ $item->total_quantity }}</td>
                                        <td>{{ $item->grand_total }}</td>
                                        <td>
                                            @php
                                            $statusClasses = [
                                            'processing' => 'bg-primary-div text-primary-div bg-opacity-10',
                                            'pending' => 'bg-danger text-danger bg-opacity-10',
                                            'cancelled' => 'bg-danger text-danger bg-opacity-25',
                                            'block' => 'bg-info text-info bg-opacity-25',
                                            ];
                                            $statusDesign = $statusClasses[$item->status] ?? 'bg-success text-success
                                            bg-opacity-10';
                                            @endphp
                                            <span class="badge p-2 fs-12 fw-normal {{ $statusDesign }}">{{
                                                Str::ucfirst($item->status) }}</span>
                                        </td>
                                        <td>
                                            <div class="dropdown text-end">
                                                <a class="btn btn-primary dropdown-toggle" href="#" role="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </a>

                                                <ul class="dropdown-menu dropdown-menu-end table_action_btn">
                                                    <li><a class="dropdown-item py-2" href="#"
                                                            onclick="showStatusModal('{{ $item->id }}', '{{ $item->po_number }}','{{ $item->status }}')">
                                                            <i
                                                                class="material-symbols-outlined fs-16 text-primary">contact_page</i>
                                                            Update Status</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('order.show',$item->id) }}"> <i
                                                                class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                                            View</a></li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="{{ route('order.edit',$item->id) }}"><i
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
        <form action="{{ route('order.update.status') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Change Order Status</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="order_id">
                    <div class="form-group mb-2">
                        <label for="">PO Number</label>
                        <input type="text" class="form-control" name="po_number" id="po_number" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Select Status</label>
                        <select name="status" class="form-select form-control status_select">
                            <option value="" disabled selected>Select Status</option>
                            <option value="processing">Processing</option>
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="finished">Finished</option>
                            <option value="block">Block</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary text-white">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    function showStatusModal(order_id, po_number,status){
        const modalEl = document.getElementById('status_change_modal');
        const myModal = new bootstrap.Modal(modalEl, {
            keyboard: false
        });
        myModal.show();

        let getorder_id = $('#order_id');
        let getpo_number = $('#po_number');
        getorder_id.val(order_id);
        getpo_number.val(po_number);
        $('.status_select').val(status);
    }
</script>
@endsection