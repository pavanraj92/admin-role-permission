@extends('admin::admin.layouts.master')

@section('title', 'Roles Management')

@section('page-title', 'Role Details')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.roles.index') }}">Manage Roles</a></li>
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
                        <table class="table table-bordered table-responsive-lg">
                            <tbody>
                                <tr>
                                    <th scope="row">Name</th>
                                    <td scope="col">{{ $role->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Created At</th>
                                    <td scope="col">{{ $role->created_at ?? 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <th scope="row">Assigned Permissions</th>
                                    <td scope="col">
                                        @if($role->permissions && $role->permissions->count())
                                        <ul class="mb-0 pl-3">
                                            @foreach($role->permissions as $permission)
                                            <li>{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</li>
                                            @endforeach
                                        </ul>
                                        @else
                                        <span class="text-muted">No permissions assigned</span>
                                        @endif
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