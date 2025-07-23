@extends('admin::admin.layouts.master')

@section('title', 'Permissions Management')

@section('page-title', 'Permission Details')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.permissions.index') }}">Permissions Manager</a></li>
<li class="breadcrumb-item active" aria-current="page">Permission Details</li>
@endsection

@section('content')
<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start Permission Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <div class="card-body">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th scope="row">Name</th>
                                    <td scope="col">{{ $permission->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Slug</th>
                                    <td scope="col">{{ $permission->slug ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Created At</th>
                                    <td scope="col">{{ $permission->created_at ? $permission->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End permission Content -->
</div>
<!-- End Container fluid  -->
@endsection