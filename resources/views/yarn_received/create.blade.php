@extends('layouts.master')
@section('title', 'Yarn Received')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Yarn Received</h2>

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

    <div class="row mb-4 mt-2">
        <div class="col-12">
            <div class="card border bg-white rounded-3 overflow-hidden">
                <div class="card-header">
                    <h3 class="card-title">Basic Order Info</h3>
                </div>
                <div class="card-body">
                    <div class="default-table-area style-two default-table-width">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th><strong>PO</strong></th>
                                        <th><strong>Order Number</strong></th>
                                        <th><strong>Order Date</strong></th>
                                        <th><strong>Status</strong></th>
                                        <th><strong>Approx. delivery</strong></th>
                                        <th><strong>Delivery Date</strong></th>
                                        <th><strong>Approved By</strong></th>
                                        <th><strong>PO PDF</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $order->po_number }}</td>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->order_date }}</td>
                                        <td>
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success p-2 fs-12 fw-normal">{{
                                                Str::ucfirst($order->status)
                                                }}</span>
                                        </td>
                                        <td>{{ $order->approximate_delivery_date }}</td>
                                        <td>{{ $order->delivery_date ?? '--' }}</td>
                                        <td>{{ $order->approvedBy->name ?? '--' }}</td>
                                        <td>
                                            @if ($order->po_file)
                                            <a href="{{ asset('storage/'.$order->po_file) }}" target="_blank"><span
                                                    class="material-symbols-outlined">
                                                    picture_as_pdf
                                                </span></a>
                                            @else
                                            --
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table align-middle mt-4">
                                <thead>
                                    <tr>
                                        <th><strong>Created By</strong></th>
                                        <th><strong>Created at</strong></th>
                                        <th><strong>Last updated by</strong></th>
                                        <th><strong>Last update at</strong></th>
                                        <th><strong>Shipping Address</strong></th>
                                        <th><strong>Remarks</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $order->creator->name ?? '--' }}</td>
                                        <td>{{ $order->created_at->format('d M Y')}}</td>
                                        <td>{{ $order->lastUpdateBy->name ?? '--'}}</td>
                                        <td>{{ $order->updated_at->format('d M Y')}}</td>
                                        <td>{{ $order->ship_address }}</td>
                                        <td>{{ $order->remarks }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    @forelse ($order->yarnQuotations->groupBy('style') as $key => $items)
                    <div class="accordion  mb-5">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button style="background: #605dff;" class="accordion-button text-uppercase text-white"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}">
                                    <strong>Style: {{ $key }}</strong>
                                </button>
                            </h2>

                            <div id="collapse{{ $key }}" class="accordion-collapse collapse show">
                                <div class="accordion-body p-0">
                                    <div class="default-table-area style-two default-table-width">
                                        <div class="table-responsive">
                                            <table class="table align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>Description</th>
                                                        <th>Qty(KG)</th>
                                                        <th>Status</th>
                                                        <th>Delevired Qty.</th>
                                                        <th>Reveive(kg)</th>
                                                        <th>Loss Qty</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($items as $item)
                                                    <tr>
                                                        <td>{{ $item->description }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ $item->status }}</td>
                                                        <td>--</td>
                                                        <td>
                                                            <input type="text">
                                                        </td>
                                                        <td>--</td>
                                                        <td>--</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td class="text-center"><strong>Total:</strong></td>
                                                        <td colspan="2">
                                                            <strong>{{
                                                                number_format($items->sum('quantity'),
                                                                2)
                                                                }}KG</strong>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="table-responsive  mt-4">
                                        <table class="table align-middle">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Description</th>
                                                    <th>Approx. Delivery</th>
                                                    <th>Delivery Date</th>
                                                    <th>remarks</th>
                                                    <th>Approved By</th>
                                                    <th>Created By</th>
                                                    <th>Created At</th>
                                                    <th>Last Up. By</th>
                                                    <th>Last Up. At</th>
                                                    <th>Yarn Fac.</th>
                                                    <th>Netting Fac.</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($items as $item)
                                                <tr>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->approximate_delivery_date }}</td>
                                                    <td>{{ $item->delivery_date ?? '--' }}</td>
                                                    <td>{{ $item->remarks }}</td>
                                                    <td>{{ $item->approvedBy->name ?? '--' }}</td>
                                                    <td>{{ $item->creator->name ?? '--' }}</td>
                                                    <td>{{ $item->created_at->format('d M Y')}}</td>
                                                    <td>{{ $item->lastUpdateBy->name ?? '--'}}</td>
                                                    <td>{{ $item->updated_at->format('d M Y')}}</td>
                                                    <td>Name: {{ $item->yarnFactory->name}} <br> Address: {{
                                                        $item->yarnFactory->address}}</td>
                                                    <td>Name: {{ $item->nettingFactory->name}} <br> Address:
                                                        {{
                                                        $item->nettingFactory->address}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p>No Data Found!</p>
                    @endforelse
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-flex">
                                <h3>Total Quantity(KG): {{
                                    number_format($order->yarnQuotations->sum('quantity'),
                                    2)
                                    }}KG</h3>

                                <h3 class="ms-5">Total TK: {{
                                    number_format($order->yarnQuotations->sum('total_price'),
                                    2)
                                    }}TK</h3>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
</div>

<div class="flex-grow-1"></div>
@endsection