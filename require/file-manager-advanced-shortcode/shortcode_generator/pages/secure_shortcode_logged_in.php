<?php if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$options = get_option('fma_shortcode_options');
$unauthorized_msg = (isset($options['shortcode_unauthorized_message']) && !empty($options['shortcode_unauthorized_message'])) ? esc_attr($options['shortcode_unauthorized_message']) : 'Unauthoried Access!';

if(is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $uid = $current_user->ID;
    $user = new WP_User( $uid );
    $blocked_user = isset($shortcode_data['block_users']) ? explode(',', $shortcode_data['block_users']) : [];
    $fma_intersect = !empty($shortcode_data['user_roles']) ? array_intersect($user->roles,$shortcode_data['user_roles']) : [];
    /**
     * If user is in block List
     */
    if(in_array($uid, $blocked_user)) {
       echo '<p class="afm_returned_error">'.$unauthorized_msg.'</p>';
    /**
     * if user role is not allowed  
     */   
    } else if(count($fma_intersect) == 0) {
        echo '<p class="afm_returned_error">'.$unauthorized_msg.'</p>';
    } else { ?>
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
            action: 'fma_render_secure_auth',
            yuhytrg: '<?php echo $this->encryption(wp_create_nonce($shortcodeData->shortcode_id)); ?>',
            uyhtrefde: '<?php echo $this->encryption($shortcodeData->shortcode_id); ?>',
        },
        height: '<?php echo $shortcode_data['height']; ?>',
        width: '<?php echo $shortcode_data['width']; ?>',
        ui: afmui,
        requestType: 'post',
    });
});
</script>
<div id="<?php echo $fma_id;?>"><?php echo (isset($options['shortcode_loading_message']) && !empty($options['shortcode_loading_message'])) ? esc_attr($options['shortcode_loading_message']) : 'Loading please wait...';?></div>
<?php }
 } else { ?>
     <p class="afm_returned_error">
  <?php echo (isset($options['shortcode_login_message']) && !empty($options['shortcode_login_message'])) ? esc_attr($options['shortcode_login_message']) : 'Please login to access file manager.';?>
  </p>
<?php }
?>