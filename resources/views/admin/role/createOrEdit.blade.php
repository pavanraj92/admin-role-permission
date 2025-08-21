@extends('admin::admin.layouts.master')

@section('title', 'Role Management')
@section('page-title', (isset($role) ? 'Edit' : 'Create') . ' Role')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.roles.index') }}">Role Manager</a></li>
<li class="breadcrumb-item active" aria-current="page">{{isset($role) ? 'Edit' : 'Create'}} Role</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Start Role Content -->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <form action="{{ isset($role) ? route('admin.roles.update', $role->id) : route('admin.roles.store') }}"
                    method="POST" id="roleForm" enctype="multipart/form-data">
                    @if (isset($role))
                    @method('PUT')
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                    value="{{ $role?->name ?? old('name') }}" required>
                                @error('name')
                                <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="saveBtn"> {{isset($role) ? 'Update' : 'Save'}}</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End role Content -->
</div>
@endsection

@push('scripts')
    @include('admin_role_permissions::admin.role.partials.script')
@endpush