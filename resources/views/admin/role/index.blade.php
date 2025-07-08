@extends('admin::admin.layouts.master')

@section('title', 'Roles Management')

@section('page-title', 'Manage Roles')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-results__option,
    .select2-selection__choice,
    .select2-selection__rendered {
        text-transform: capitalize;
    }
</style>
@endpush

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Manage Roles</li>
@endsection



@section('content')
<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start Role Content -->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title">Filter</h4>
                <form action="{{ route('admin.roles.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title">Keyword</label>
                                <input type="text" name="keyword" id="keyword" class="form-control"
                                    value="{{ app('request')->query('keyword') }}" placeholder="Enter name">
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" form="filterForm" class="btn btn-primary mb-3">Filter</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary mb-3">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <h4 class="card-title">Manage roles</h4> --}}
                    <div class="text-right">
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary mb-3">Create New Role</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($roles) && $roles->count() > 0)
                                @php
                                $i = ($roles->currentpage() - 1) * $roles->perpage() + 1;
                                @endphp
                                @foreach ($roles as $role)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        {{ $role->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
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
                                        <a href="{{ route('admin.roles.assign.permissions.edit', $role) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Assign Permissions"
                                            class="btn btn-primary btn-sm"><i class="mdi mdi-key"></i></a>
                                        <button type="button"
                                            class="btn btn-secondary btn-sm open-dynamic-modal"
                                            title="Assign Admins"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            data-target="#assignAdminsModal"
                                            data-config='@json($config)'>
                                            <i class="mdi mdi-account-multiple"></i>
                                        </button>
                                        <a href="{{ route('admin.roles.edit', $role) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit this record"
                                            class="btn btn-success btn-sm"><i class="mdi mdi-pencil"></i></a>
                                        <a href="{{ route('admin.roles.show', $role) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="View this record"
                                            class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                        <a href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Delete this record"
                                            data-url="{{ route('admin.roles.destroy', $role) }}"
                                            data-text="Are you sure you want to delete this record?"
                                            data-method="DELETE"
                                            class="btn btn-danger btn-sm delete-record"><i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>
                                @php
                                $i++;
                                @endphp
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" class="text-center">No roles found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <!--pagination move the right side-->
                        @if ($roles->count() > 0)
                        {{ $roles->links('admin::pagination.custom-admin-pagination') }}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End role Content -->
</div>
<!-- End Container fluid  -->


<!-- dynamic modal -->
@extends('admin_role_permissions::admin.components.global.dynamic-modal')
 
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush