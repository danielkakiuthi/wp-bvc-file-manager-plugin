<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
fma_shortcode_generator::shortcode_generator_enqueue_script();
$options = get_option('fma_shortcode_options'); ?>
<div class="wrap afm_main_bg">
<h3>Shortcode Settings</h3>
<?php if(isset($_POST['submit'])):
    fma_shortcode_generator::settingsShortcode();
  endif; ?>	
<form action="<?php echo admin_url('admin.php?page=file_manager_advanced_shortcode_settings');?>" method="post" name="afm-shortcode-settings" id="afm-shortcode-settings" class="afmshortcodegenerator">
<?php wp_nonce_field( 'fma_shortcode_nonce', 'fma_shortcode_nonce_field' ); ?>
<table class="form-table">
<tbody>
<tr>
<th>Login Message for Logged in Shortcode*</th>
<td>
<textarea name="shortcode_login_message" id="shortcode_login_message" class="large-text" rows="3" cols="30" required /><?php echo (isset($options['shortcode_login_message']) && !empty($options['shortcode_login_message'])) ? esc_attr($options['shortcode_login_message']) : '';?></textarea>	
<div>
<p class="description">This message will display on frontend loggedin shortcode. Default: Please login to access file manager.</p>
</div>
</td>
</tr>

<tr>
<th>Unauthorized Access Message*</th>
<td>
<textarea name="shortcode_unauthorized_message" id="shortcode_unauthorized_message" class="large-text" rows="3" cols="30" required /><?php echo (isset($options['shortcode_unauthorized_message']) && !empty($options['shortcode_unauthorized_message'])) ? esc_attr($options['shortcode_unauthorized_message']) : '';?></textarea>	
<div>
<p class="description">This message will display on frontend loggedin shortcode. Default: Unauthoried Access.</p>
</div>
</td>
</tr>

<tr>
<th>Loading Message*</th>
<td>
<textarea name="shortcode_loading_message" id="shortcode_loading_message" class="large-text" rows="3" cols="30" required /><?php echo (isset($options['shortcode_loading_message']) && !empty($options['shortcode_loading_message'])) ? esc_attr($options['shortcode_loading_message']) : '';?></textarea>	
<div>
<p class="description">This message will display on frontend shortcodes. Default: Loading please wait...</p>
</div>
</td>
</tr>

</tbody>
</table>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
</form>
</div>