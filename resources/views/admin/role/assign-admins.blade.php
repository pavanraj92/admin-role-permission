@extends('admin::admin.layouts.master')

@section('title', 'Assign Permissions Management')
@section('page-title', 'Manage Assign Permissions')

@push('styles')
    @include('admin_role_permissions::admin.role.partials.style')
@endpush

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.roles.index') }}">Roles Manager</a></li>
<li class="breadcrumb-item active" aria-current="page">Manage Assign Permissions</li>
@endsection


@section('content')
<div class="container-fluid">
    <h4>Assign Admins to Role: <strong>{{ $role->name }}</strong></h4>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.roles.assign.admins.update', $role->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="admins">Select Admins</label>
            <select name="admins[]" id="admins" class="form-control" multiple>
                @foreach($admins as $admin)
                <option value="{{ $admin->id }}" {{ in_array($admin->id, $assignedAdminIds) ? 'selected' : '' }}>
                    {{ $admin->name }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assign Admins</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
    @include('admin_role_permissions::admin.role.partials.script')
@endpush