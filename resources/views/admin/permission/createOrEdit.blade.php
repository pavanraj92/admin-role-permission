@extends('admin::admin.layouts.master')

@section('title', 'Permissions Management')

@section('page-title', 'Create Permission')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.permissions.index') }}">Manage Permissions</a></li>
<li class="breadcrumb-item active" aria-current="page">Create Permission</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Start Permission Content -->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <form action="{{ isset($permission) ? route('admin.permissions.update', $permission->id) : route('admin.permissions.store') }}"
                    method="POST" id="permissionForm" enctype="multipart/form-data">
                    @if (isset($permission))
                    @method('PUT')
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ $permission?->name ?? old('name') }}" required>
                                @error('name')
                                <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"> {{isset($permission) ? 'Update' : 'Save'}}</button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End permission Content -->
</div>
@endsection

@push('styles')
<!-- Custom CSS for the page -->
<link rel="stylesheet" href="{{ asset('backend/custom.css') }}">
@endpush

@push('scripts')
<!-- Then the jQuery Validation plugin -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>


<script>
    $(document).ready(function() {

        //jquery validation for the form
        $('#permissionForm').validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                name: {
                    required: "Please enter a name",
                    minlength: "Name must be at least 3 characters long"
                }
            },
            submitHandler: function(form) {
                form.submit();
            },
            errorElement: 'div',
            errorClass: 'text-danger custom-error',
            errorPlacement: function(error, element) {
                $('.validation-error').hide();
                error.insertAfter(element);
            }
        });
    });
</script>
@endpush