@extends('admin::admin.layouts.master')

@section('title', 'Permissions Management')

@section('page-title', 'Permissions Manager')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Permissions Manager</li>
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
                        <div class="col-auto mt-1 text-right">
                            <div class="form-group">
                                <label for="created_at">&nbsp;</label>
                                <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary mt-4">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
                                    <th scope="col">S. No.</th>
                                    <th scope="col">@sortablelink('name', 'Name', [], ['style' => 'color: #4F5467; text-decoration: none;']) </th>
                                    <th scope="col">@sortablelink('slug', 'Slug', [], ['style' => 'color: #4F5467; text-decoration: none;']) </th>
                                    <th scope="col">@sortablelink('status', 'Status', [], ['style' => 'color: #4F5467; text-decoration: none;']) </th>
                                    <th scope="col">@sortablelink('created_at', 'Created At', [], ['style' => 'color: #4F5467; text-decoration: none;']) </th>
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
                                        {{ $permission->created_at
                                            ? $permission->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                            : '—' }}
                                    </td>
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
                                        {{--
                                            @admincan('permission_manager_delete')
                                            <a href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Delete this record"
                                            data-url="{{ route('admin.permissions.destroy', $permission) }}"
                                        data-text="Are you sure you want to delete this record?"
                                        data-method="DELETE"
                                        @endif
                                        class="btn btn-danger btn-sm delete-record"><i class="mdi mdi-delete"></i></a> @endadmincan
                                        --}}
                                    </td>
                                </tr>
                                @php
                                $i++;
                                @endphp
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="4" class="text-center">No records found.</td>
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