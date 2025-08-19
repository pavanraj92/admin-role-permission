@extends('admin::admin.layouts.master')

@section('title', 'Assign Permissions Management')
@section('page-title', 'Manage Assign Permissions')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Manage Assign Permissions</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.roles.assign.permissions.update', $role) }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Assign Permissions to: {{ $role->name }}</h6>
                            <button type="button" id="toggleAllPermissionsBtn" class="btn btn-sm btn-primary">
                                Select All Permissions
                            </button>
                        </div>

                        @php
                        $permissionGroups = config('permissions.admin.permissions');
                        @endphp


                        @foreach($permissionGroups as $group => $groupPermissions)
                        <div class="form-group p-3 mb-4 border rounded" style="background-color: #f8f9fa;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 text-uppercase fw-bold"><b>{{ $group }}</b></h6>
                                @if (strtolower($group) !== 'dashboard')
                                <button type="button" class="btn btn-sm btn-success toggle-group-permissions" data-group="{{ $group }}">
                                    Check All
                                </button>
                                @endif
                            </div>


                            @php $chunks = array_chunk($groupPermissions, 2); @endphp

                            @foreach($chunks as $permChunk)
                            <div class="row">
                                @foreach($permChunk as $perm)
                                @php
                                $permission = $permissions->firstWhere('slug', $perm['slug']);
                                if (!$permission) continue;
                                $isChecked = in_array($permission->id, $assignedPermissionIds ?? []);
                                $isDashboard = $perm['slug'] === 'dashboard';
                                @endphp

                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input permission-checkbox"
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->id }}"
                                            id="perm_{{ $permission->id }}"
                                            data-group="{{ $group }}"
                                            data-slug="{{ $permission->slug }}"
                                            {{ $isChecked ? 'checked' : '' }}
                                            {{ $isDashboard ? 'checked disabled' : '' }}>

                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>

                                        @if($isDashboard)
                                        <input type="hidden" name="permissions[]" value="{{ $permission->id }}">
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                        @endforeach


                        <button type="submit" class="btn btn-primary">Update Permissions</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('admin_role_permissions::admin.role.partials.script')
@endpush