<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.open-dynamic-modal', function() {
            const config = $(this).data('config') || {};

            $('#globalModalTitle').text(config.title || 'Modal Title');
            $('#globalDynamicForm').attr('action', config.action_url || '#');
            $('#globalModalBody').html(config.body_html || '<p>No content</p>');

            // If Select2 is needed
            if (config.init_select2) {
                $('#globalModalBody').find('select.select2').select2({
                    dropdownParent: $('#globalDynamicModal'),
                    placeholder: config.placeholder || 'Select',
                    width: '100%',
                    ajax: config.ajax_url ? {
                        url: config.ajax_url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    } : undefined
                });
            }

            $('#globalDynamicModal').modal('show');
        });
    });
</script>