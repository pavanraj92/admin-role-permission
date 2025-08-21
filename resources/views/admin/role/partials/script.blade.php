<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#admins').select2({
            placeholder: "Select admins",
            allowClear: true,
            width: '100%'
        });

        //jquery validation for the form
        $('#roleForm').validate({
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
                const $btn = $('#saveBtn');

                if ($btn.text().trim().toLowerCase() === 'update') {
                    $btn.prop('disabled', true).text('Updating...');
                } else {
                    $btn.prop('disabled', true).text('Saving...');
                }
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