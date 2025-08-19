@extends('admin::admin.layouts.master')

@section('title', 'View Permission - ' . ($permission?->name ?? 'N/A'))
@section('page-title', 'Permission Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permissions Manager</a></li>
<li class="breadcrumb-item active" aria-current="page">Permission Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- Header -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="card-title mb-0">{{ $permission?->name ?? 'N/A' }}</h4>
                        <div>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                                Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Section -->
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Permission Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Permission Name -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Name:</label>
                                                <p><strong>{{ $permission?->name ?? 'N/A' }}</strong></p>
                                            </div>
                                        </div>

                                        <!-- Slug -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Slug:</label>
                                                <p>{{ $permission?->slug ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <!-- Created At -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Created At:</label>
                                                <p>
                                                    {{ $permission?->created_at
                                                        ? $permission->created_at->format(config('GET.admin_date_time_format') ?? 'M d, Y g:i A')
                                                        : 'N/A' }}
                                                </p>
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
                                        @admincan('permission_manager_edit')
                                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-warning mb-2">
                                            <i class="mdi mdi-pencil"></i> Edit Permission
                                        </a>
                                        @endadmincan

                                        @admincan('permission_manager_delete')
                                        <button type="button" class="btn btn-danger delete-btn delete-record"
                                            title="Delete this record"
                                            data-url="{{ route('admin.permissions.destroy', $permission) }}"
                                            data-redirect="{{ route('admin.permissions.index') }}"
                                            data-text="Are you sure you want to delete this record?"
                                            data-method="DELETE">
                                            <i class="mdi mdi-delete"></i> Delete Permission
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
