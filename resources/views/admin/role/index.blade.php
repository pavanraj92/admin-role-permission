@extends('admin::admin.layouts.master')

@section('title', 'Roles Management')
@section('page-title', 'Role Manager')

@push('styles')
    @include('admin_role_permissions::admin.role.partials.style')
@endpush

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page"> Role Manager</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @include('admin_role_permissions::admin.role.partials.filter')
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @admincan('roles_manager_create')
                    <div class="text-right">
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary mb-3">Create New Role</a>
                    </div>
                    @endadmincan

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>S. No.</th>
                                    <th>@sortablelink('name', 'Name', [], ['class' => 'text-dark']) </th>
                                    <th>@sortablelink('created_at', 'Created At', [], ['class' => 'text-dark']) </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                <tr>
                                    <th scope="row">{{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}</th>
                                    <td>{{ $role?->name ?? 'N/A'  }}</td>
                                    <td>{{ $role?->created_at ? $role->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : '—' }}
                                    </td>
                                    <td style="width: 20%;">
                                        @php
                                        $html = '<label for="admins">Select Admins</label><select name="admins[]" class="form-control select2" multiple></select>';
                                        $config = [
                                        'title' => "Assign Admins to Role: {$role->name}",
                                        'action_url' => route('admin.roles.assign.admins.update', $role),
                                        'ajax_url' => route('admin.roles.assign.admins.edit', $role),
                                        'role_id' => $role->id,
                                        'role_name' => $role->name,
                                        'placeholder' => 'Search admins',
                                        "init_select2" => true,
                                        "body_html" => $html,
                                        "width" => '100%'
                                        ];
                                        @endphp

                                        @admincan('assign_permission')
                                        <a href="{{ route('admin.roles.assign.permissions.edit', $role) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Assign Permissions"
                                            class="btn btn-primary btn-sm"><i class="mdi mdi-key"></i></a>
                                        @endadmincan

                                        @admincan('assign_roles')
                                        <button type="button"
                                            class="btn btn-secondary btn-sm open-dynamic-modal"
                                            title="Assign Admins"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            data-target="#assignAdminsModal"
                                            data-config='@json($config)'>
                                            <i class="mdi mdi-account-multiple"></i>
                                        </button>
                                        @endadmincan

                                        @admincan('roles_manager_view')
                                        <a href="{{ route('admin.roles.show', $role) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="View this record"
                                            class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                        @endadmincan


                                        @admincan('roles_manager_edit')
                                        <a href="{{ route('admin.roles.edit', $role) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit this record"
                                            class="btn btn-success btn-sm"><i class="mdi mdi-pencil"></i></a>
                                        @endadmincan

                                        @if($role->id != 1) <!-- Assuming 1 is the ID for 'super admin' -->
                                        @admincan('roles_manager_delete')
                                        <a href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Delete this record"
                                            data-url="{{ route('admin.roles.destroy', $role) }}"
                                            data-text="Are you sure you want to delete this record?"
                                            data-method="DELETE"
                                            class="btn btn-danger btn-sm delete-record"><i class="mdi mdi-delete"></i></a>
                                        @endadmincan
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($roles->count() > 0)
                        {{ $roles->links('admin::pagination.custom-admin-pagination') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- dynamic modal -->
@include('admin_role_permissions::admin.components.global.dynamic-modal')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush