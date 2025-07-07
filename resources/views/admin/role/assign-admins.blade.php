@extends('admin::admin.layouts.master')

@section('title', 'Assign Permissions Management')

@section('page-title', 'Manage Assign Permissions')

@push('styles')
<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Custom CSS for the page -->
<!-- <link rel="stylesheet" href="{{ asset('backend/custom.css') }}"> -->
@endpush

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Manage Assign Permissions</li>
@endsection


@section('content')
<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start Assign Permission Content -->
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
    <!-- End assign permission Content -->
</div>
<!-- End Container fluid  -->
@endsection
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#admins').select2({
            placeholder: "Select admins",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush