@extends('admin::admin.layouts.master')

@section('title', 'Permissions Management')
@section('page-title', (isset($permission) ? 'Edit' : 'Create') . ' Permission')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.permissions.index') }}">Permissions Manager</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ isset($permission) ? 'Edit' : 'Create' }} Permission</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Start Permission Content -->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <form action="{{ isset($permission) ? route('admin.permissions.update', $permission->id) : route('admin.permissions.store') }}"
                    method="POST" id="permissionForm" enctype="multipart/form-data">
                    @if (isset($permission))
                    @method('PUT')
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                    value="{{ $permission?->name ?? old('name') }}" required>
                                @error('name')
                                <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"> {{isset($permission) ? 'Update' : 'Save'}}</button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End permission Content -->
</div>
@endsection

@push('scripts')
    @include('admin_role_permissions::admin.permission.partials.script')
@endpush