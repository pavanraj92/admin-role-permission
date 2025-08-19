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