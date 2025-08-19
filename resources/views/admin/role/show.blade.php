@extends('admin::admin.layouts.master')

@section('title', 'Roles Management')
@section('page-title', 'Role Details')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.roles.index') }}">Roles Manager </a></li>
<li class="breadcrumb-item active" aria-current="page">Role Details</li>
@endsection

@section('content')
<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start Role Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <div class="card-body">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th scope="row">Name</th>
                                    <td scope="col">{{ $role?->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Created At</th>
                                    <td scope="col">
                                        {{ $role?->created_at
                                            ? $role->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                            : '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Assigned Permissions</th>
                                    <td>
                                        <div class="row">
                                            @forelse($role?->permissions as $permission)
                                            <div class="col-md-3 mb-2">
                                                <span class="badge bg-secondary text-white p-2">
                                                    {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                                </span>
                                            </div>
                                            @empty
                                            <span class="text-muted">No permissions assigned</span>
                                            @endforelse
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End role Content -->
</div>
<!-- End Container fluid  -->
@endsection