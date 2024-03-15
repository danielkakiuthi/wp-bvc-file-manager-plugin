jQuery(document).ready(function() {
    jQuery("#afm-shortcode-generator").validate({
        //errorElement: 'p',
        rules: {
            'operations[]': {
                required: true,
            },
            'fma_user_role[]': {
                required: true,
            },
        },
        messages: {
            'operations[]': {
                required: "You must check at least 1 operation.",
            },
            'fma_user_role[]': {
                required: "You must check at least 1 role.",
            },
            shortcode_title: {
                required: 'Shortcode title is required.'
            },
            path: {
                required: 'Path is required.'
            },
            dateformat: 'Date format is a required.',
            upload_max_size : 'Enter maximum upload size value.',
            upload_allow: 'Enter mime types you want to allow.'
        }
    });

    jQuery("#afm-shortcode-settings").validate({
        messages: {
            'shortcode_login_message': {
                required: "This field is required.",
            },
            'shortcode_unauthorized_message': {
                required: "This field is required.",
            },
            shortcode_loading_message: {
                required: 'This field is required.'
            },
        }
    });
});