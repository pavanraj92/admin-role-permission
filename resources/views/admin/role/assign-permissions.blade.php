@extends('admin::admin.layouts.master')

@section('title', 'Assign Permissions Management')

@section('page-title', 'Manage Assign Permissions')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Manage Assign Permissions</li>
@endsection

@section('content')
<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start Assign Permission Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <form action="{{ route('admin.roles.assign.permissions.update', $role) }}" method="POST">
                            @csrf
                            <h4  class="p-3" style="background-color: #dee2e633;">Assign Permissions to: <strong>{{ $role->name }}</strong></h4>
                            <div class="form-group p-3" style="background-color: #dee2e633">
                                @foreach($permissions as $permission)
                                @php
                                $isDashboard = $permission->slug === 'dashboard';
                                $isChecked = in_array($permission->id, $assignedPermissionIds ?? []);
                                @endphp
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                        value="{{ $permission->id }}"
                                        id="perm_{{ $permission->id }}"
                                        {{ $isChecked ? 'checked' : '' }}
                                        {{ $isDashboard ? 'checked disabled' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ ucwords($permission->name) }}
                                    </label>
                                    @if($isDashboard)
                                    <input type="hidden" name="permissions[]" value="{{ $permission->id }}">
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary">Update Permissions</button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back</a>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End assign permission Content -->
</div>
<!-- End Container fluid  -->
@endsection