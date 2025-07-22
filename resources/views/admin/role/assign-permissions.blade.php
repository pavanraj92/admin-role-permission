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
    <!-- End assign permission Content -->
</div>
<!-- End Container fluid  -->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        function updateGroupButton(group) {
            const checkboxes = Array.from(document.querySelectorAll(`.permission-checkbox[data-group='${CSS.escape(group)}']`)).filter(cb => !cb.disabled);

            const allChecked = checkboxes.length > 0 && checkboxes.every(cb => cb.checked);
            const groupButton = document.querySelector(`.toggle-group-permissions[data-group="${group}"]`);

            if (groupButton) {
                groupButton.textContent = allChecked ? 'Uncheck All' : 'Check All';
            }
        }

        function updateGlobalButtonLabel() {
            const allCheckboxes = Array.from(document.querySelectorAll('.permission-checkbox')).filter(cb => !cb.disabled);
            const allChecked = allCheckboxes.length > 0 && allCheckboxes.every(cb => cb.checked);
            const globalBtn = document.getElementById('toggleAllPermissionsBtn');


            if (globalBtn) {
                globalBtn.textContent = allChecked ? 'Uncheck All Permissions' : 'Select All Permissions';
            }
        }

        // Group-wise toggle button click
        document.querySelectorAll('.toggle-group-permissions').forEach(button => {
            button.addEventListener('click', function() {
                const group = this.dataset.group;
                const checkboxes = Array.from(document.querySelectorAll(`.permission-checkbox[data-group='${CSS.escape(group)}']`)).filter(cb => !cb.disabled);

                const allChecked = checkboxes.every(cb => cb.checked);

                // Toggle all
                checkboxes.forEach(cb => cb.checked = !allChecked);

                // Always keep 'list' checked if it's in the group and we are checking all
                if (!allChecked) {
                    const listCB = document.querySelector(`.permission-checkbox[data-group="${group}"][data-slug$="list"]`);
                    if (listCB) listCB.checked = true;
                }

                updateGroupButton(group);
                updateGlobalButtonLabel();
            });
        });

        // Checkbox change – update buttons
        document.querySelectorAll('.permission-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const group = this.dataset.group;
                const isListPermission = this.dataset.slug.endsWith('list');

                // If a permission is checked and it's not 'list', auto-check the 'list' permission in same group
                if (this.checked && !isListPermission) {
                    const listCB = document.querySelector(`.permission-checkbox[data-group="${group}"][data-slug$="list"]`);
                    if (listCB && !listCB.disabled) {
                        listCB.checked = true;
                    }
                }

                updateGroupButton(group);
                updateGlobalButtonLabel();
            });
        });

        // Global select all / unselect all
        const globalBtn = document.getElementById('toggleAllPermissionsBtn');

        if (globalBtn) {
            globalBtn.addEventListener('click', function() {
                const allCheckboxes = Array.from(document.querySelectorAll('.permission-checkbox')).filter(cb => !cb.disabled);
                const allChecked = allCheckboxes.every(cb => cb.checked);

                allCheckboxes.forEach(cb => cb.checked = !allChecked);

                // Always keep all 'list' checkboxes selected if checking all
                if (!allChecked) {
                    document.querySelectorAll('.permission-checkbox[data-slug$="list"]').forEach(cb => cb.checked = true);
                }

                // Update all group buttons
                const uniqueGroups = new Set([...allCheckboxes.map(cb => cb.dataset.group)]);
                uniqueGroups.forEach(group => updateGroupButton(group));

                updateGlobalButtonLabel();
            });
        }

        // Initialize button states
        const allCheckboxes = document.querySelectorAll('.permission-checkbox');
        const uniqueGroups = new Set([...allCheckboxes].map(cb => cb.dataset.group));
        uniqueGroups.forEach(group => updateGroupButton(group));
        updateGlobalButtonLabel();

    });
</script>

@endpush