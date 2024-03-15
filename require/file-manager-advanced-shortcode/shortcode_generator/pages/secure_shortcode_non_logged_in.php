<?php if ( ! defined( 'ABSPATH' ) ) {
    exit;
} 
$options = get_option('fma_shortcode_options');
?>
<script>
jQuery('document').ready(function(e) {
    var afmui = ['toolbar', 'tree', 'path', 'stat'];
    var fma_ui_opt = '<?php echo implode(',',$shortcode_data['display_ui_options']);?>';
    if (fma_ui_opt != '') {
        var fmui_params = fma_ui_opt;
        if (fmui_params == 'files') {
            var afmui = [];
        } else
            var afmui = fmui_params.split(',');
    }
    jQuery('#<?php echo $fma_id; ?>').elfinder({
        cssAutoLoad: false,
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        lang: '<?php echo $shortcode_data['lang'];?>',
        defaultView: '<?php echo $shortcode_data['view'];?>',
        dateFormat: '<?php echo $shortcode_data['dateformat'];?>',
        customData: {
            action: 'fma_render_secure_visitor',
            yhredokihytrg: '<?php echo $this->encryption(wp_create_nonce($shortcodeData->shortcode_id)); ?>',
            feryehyrede: '<?php echo $this->encryption($shortcodeData->shortcode_id); ?>',
        },
        height: '<?php echo $shortcode_data['height']; ?>',
        width: '<?php echo $shortcode_data['width']; ?>',
        ui: afmui,
        requestType: 'post',
    });
});
</script>
<div id="<?php echo $fma_id;?>"><?php echo (isset($options['shortcode_loading_message']) && !empty($options['shortcode_loading_message'])) ? esc_attr($options['shortcode_loading_message']) : 'Loading please wait...';?></div>