@extends('layouts.master')
@section('title', 'Edit Yarn Factroy')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Edit Yarn Factory</h2>

        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                        <span class="text-secondary fw-medium hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="fw-medium">Edit Yarn Factory</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class=" col-lg-12">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <h3 class="mb-3">Edit Factory</h3>

                    <form action="{{ route('settings.yarnfactroy.update',$yarnfactroy->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Factory Name<span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Factory name" name="name"
                                        value="{{ old('name', $yarnfactroy->name) }}">
                                    @error('name')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Status</label>
                                    <select name="status"
                                        class="form-control @error('description') is-invalid @enderror">
                                        <option value="active" {{ $yarnfactroy->status === 'active' ? 'selected' :
                                            ''}}>Active
                                        </option>
                                        <option value="inactive" {{ $yarnfactroy->status === 'inactive' ? 'selected' :
                                            ''}}>Inactive</option>
                                    </select>
                                    @error('status')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Factory Address<span
                                            style="color: rgb(205, 2, 2)">*</span></label>
                                    <textarea rows="2" class="form-control @error('address') is-invalid @enderror"
                                        placeholder="Factory address"
                                        name="address">{{ old('address', $yarnfactroy->address) }}</textarea>
                                    @error('address')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group mb-4">
                                    <label class="label text-secondary">Remarks</label>
                                    <textarea rows="2" class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Factory description"
                                        name="description">{{ old('description',$yarnfactroy->description) }}</textarea>
                                    @error('description')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 align-self-center">
                                <div class="form-group mb-4 mt-4">
                                    <button class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                            class="ri-add-line text-white fw-medium"></i> Update</button>
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