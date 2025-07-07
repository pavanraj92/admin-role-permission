@extends('admin::admin.layouts.master')

@section('title', 'Permissions Management')

@section('page-title', 'Manage Permissions')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Manage Permissions</li>
@endsection

@section('content')
<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start Permission Content -->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title">Filter</h4>
                <form action="{{ route('admin.permissions.index') }}" method="GET" id="filterForm">
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
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary mb-3">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <h4 class="card-title">Manage permissions</h4> --}}
                    <div class="text-right">
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary mb-3">Create New Permission</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($permissions) && $permissions->count() > 0)
                                @php
                                $i = ($permissions->currentpage() - 1) * $permissions->perpage() + 1;
                                @endphp
                                @foreach ($permissions as $permission)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->slug }}</td>
                                    <td>
                                        <!-- create update status functionality-->
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
                                    <td>
                                        {{ $permission->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.permissions.edit', $permission) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit this record"
                                            class="btn btn-success btn-sm"><i class="mdi mdi-pencil"></i></a>
                                        <a href="{{ route('admin.permissions.show', $permission) }}"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="View this record"
                                            class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                        {{--<a href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Delete this record"
                                            data-url="{{ route('admin.permissions.destroy', $permission) }}"
                                        data-text="Are you sure you want to delete this record?"
                                        data-method="DELETE"
                                        class="btn btn-danger btn-sm delete-record"><i class="mdi mdi-delete"></i></a> --}}
                                    </td>
                                </tr>
                                @php
                                $i++;
                                @endphp
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" class="text-center">No permissions found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                        <!--pagination move the right side-->
                        @if ($permissions->count() > 0)
                        {{ $permissions->links('admin::pagination.custom-admin-pagination') }}
                        @endif

                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- End permission Content -->
</div>
<!-- End Container fluid  -->
@endsection