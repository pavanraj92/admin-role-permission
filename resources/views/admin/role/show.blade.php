@extends('admin::admin.layouts.master')

@section('title', 'View Role - ' . ($role?->name ?? 'N/A'))
@section('page-title', 'Role Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles Manager</a></li>
<li class="breadcrumb-item active" aria-current="page">Role Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="card-title mb-0">{{ $role?->name ?? 'N/A' }}</h4>
                        <div>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary ml-2">
                                Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Section -->
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Role Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Role Name -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Role Name:</label>
                                                <p><strong>{{ $role?->name ?? 'N/A' }}</strong></p>
                                            </div>
                                        </div>

                                        <!-- Created At -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Created At:</label>
                                                <p>
                                                    {{ $role?->created_at
                                                        ? $role->created_at->format(config('GET.admin_date_time_format') ?? 'M d, Y g:i A')
                                                        : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Permissions -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Assigned Permissions:</label>
                                                @if($role?->permissions->count())
                                                <p>
                                                    @foreach($role->permissions as $permission)
                                                    <span class="badge badge-info mb-1">
                                                        {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                                    </span>
                                                    @endforeach
                                                </p>
                                                @else
                                                <p class="text-muted">No permissions assigned</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section -->
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        @admincan('roles_manager_edit')
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning mb-2">
                                            <i class="mdi mdi-pencil"></i> Edit Role
                                        </a>
                                        @endadmincan

                                        @admincan('roles_manager_delete')
                                        <button type="button" class="btn btn-danger delete-btn delete-record"
                                            title="Delete this record"
                                            data-url="{{ route('admin.roles.destroy', $role) }}"
                                            data-redirect="{{ route('admin.roles.index') }}"
                                            data-text="Are you sure you want to delete this record?"
                                            data-method="DELETE">
                                            <i class="mdi mdi-delete"></i> Delete Role
                                        </button>
                                        @endadmincan
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Right Section -->
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection