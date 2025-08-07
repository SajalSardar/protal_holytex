@extends('layouts.master')
@section('title', 'Style')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Style</h2>

        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                        <span class="text-secondary fw-medium hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">Style</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <h3 class="mb-3">Create Style</h3>

                    <form action="{{ route('settings.style.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Style Name<span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <input type="text" class="form-control @error('style_name') is-invalid @enderror"
                                        placeholder="Style name" name="style_name" value="{{ old('style_name') }}">
                                    @error('style_name')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 align-self-center">
                                <div class="form-group mb-4 mt-4">
                                    <button class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                            class="ri-add-line text-white fw-medium"></i> Create Style</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class=" col-lg-12">
            <h3>Style List</h3>
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="default-table-area">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Style</th>
                                        <th>Created Date</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($styles as $item)
                                    <tr>
                                        <td>#{{ $item->id }}</td>
                                        <td>{{ $item->style_name }}</td>
                                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                        <td>{{ Str::ucfirst($item->status) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1 justify-content-end">
                                                <button
                                                    class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                    <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                                </button>
                                                <button
                                                    class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                                                    <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">No data Found!</td>
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
@endsection