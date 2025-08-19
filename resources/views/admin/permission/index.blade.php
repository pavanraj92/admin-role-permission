@extends('admin::admin.layouts.master')

@section('title', 'Permission Management')
@section('page-title', 'Permission Manager')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Permission Manager</li>
@endsection

@section('content')
<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start Permission Content -->
    <div class="row">
        <div class="col-12">
            @include('admin_role_permissions::admin.permission.partials.filter')
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @admincan('permission_manager_create')
                    <div class="text-right">
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary mb-3">Create New Permission</a>
                    </div>
                    @endadmincan

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>S. No.</th>
                                    <th>@sortablelink('name', 'Name', [], ['class' => 'text-dark']) </th>
                                    <th>@sortablelink('slug', 'Slug', [], ['class' => 'text-dark']) </th>
                                    <th>@sortablelink('status', 'Status', [], ['class' => 'text-dark']) </th>
                                    <th>@sortablelink('created_at', 'Created At', [], ['class' => 'text-dark']) </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($permissions as $permission)
                                <tr>
                                    <td scope="row">{{ ($permissions->currentPage() - 1) * $permissions->perPage() + $loop->iteration }}</td>
                                    <td>{{ $permission?->name ?? 'N/A' }}</td>
                                    <td>{{ $permission?->slug ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                        $isActive = $permission->status == '1';
                                        $nextStatus = $isActive ? '0' : '1';
                                        $label = $isActive ? 'Active' : 'InActive';
                                        $btnClass = $isActive ? 'btn-success' : 'btn-warning';
                                        $tooltip = $isActive ? 'Click to change status to inactive' : 'Click to change status to active';
                                        @endphp
                                        <a href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="{{ $tooltip }}"
                                            data-url="{{ route('admin.updateStatus') }}"
                                            data-method="POST"
                                            data-status="{{ $nextStatus }}"
                                            data-id="{{ $permission->id }}"
                                            class="btn {{ $btnClass }} btn-sm update-status">
                                            {{ $label }}
                                        </a>
                                    </td>
                                    <td>{{ $permission?->created_at ? $permission->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : '—' }}</td>
                                    <td style="width: 10%;">
                                        @admincan('permission_manager_view')
                                        <a href="{{ route('admin.permissions.show', $permission) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="View this record"
                                            class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                        @endadmincan
                                        @admincan('permission_manager_edit')
                                        <a href="{{ route('admin.permissions.edit', $permission) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit this record"
                                            class="btn btn-success btn-sm"><i class="mdi mdi-pencil"></i></a>
                                        @endadmincan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- pagination move the right side -->
                        @if ($permissions->count() > 0)
                        {{ $permissions->links('admin::pagination.custom-admin-pagination') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection