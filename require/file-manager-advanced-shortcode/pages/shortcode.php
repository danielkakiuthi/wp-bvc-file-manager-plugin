<?php if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
 $lang = (isset($shortcodeAtts['lang']) && !empty($shortcodeAtts['lang'])) ? $shortcodeAtts['lang'] : 'en';
 $view = (isset($shortcodeAtts['view']) && !empty($shortcodeAtts['view'])) ? $shortcodeAtts['view'] : 'grid';
 $dateFormat = (isset($shortcodeAtts['dateformat']) && !empty($shortcodeAtts['dateformat'])) ? $shortcodeAtts['dateformat'] : 'M d, Y h:i A';
 $upload_allow = !empty($shortcodeAtts['upload_allow']) ? $this->encryption($shortcodeAtts['upload_allow']) : '';
 $url = !empty($shortcodeAtts['url']) ? $this->encryption($shortcodeAtts['url']) : '';
 $hide = !empty($shortcodeAtts['hide']) ? $this->encryption($shortcodeAtts['hide']) : '';
 wp_add_inline_script( $generated_script_id, "jQuery(document).ready(function(){
	var afmui = ['toolbar', 'tree', 'path', 'stat'];
	var fma_ui_opt = '".$shortcodeAtts['ui']."';
	if(fma_ui_opt != '') {
	  var fmui_params = fma_ui_opt;
	if(fmui_params == 'files') {
	  var afmui = [];
	} else 
	  var afmui = fmui_params.split(',');
	 }
   jQuery('".'#'.$fma_id."').elfinder(
	  {
		  cssAutoLoad : false, 
		  url : '".admin_url('admin-ajax.php')."',						
		  lang:  '".$lang."',					
		  defaultView : '".$view."',
		  dateFormat : '".$dateFormat."',
		  customData : {action: 'fma_load_shortcode_fma_secure',
		 _fmakey: '".$this->encryption(wp_create_nonce('fmaskey'))."',
		  path:'".$this->encryption($shortcodeAtts['path'])."',
		  url: '". $url."',
		  w: '".$this->encryption($shortcodeAtts['write'])."',
		  r: '".$this->encryption($shortcodeAtts['read'])."',
		  hide: '". $hide."',
		  operations: '".$this->encryption($shortcodeAtts['operations'])."',
		  path_type: '".$this->encryption($shortcodeAtts['path_type'])."',
		  hide_path: '".$this->encryption($shortcodeAtts['hide_path'])."',
		  enable_trash: '".$this->encryption($shortcodeAtts['enable_trash'])."',
		  upload_allow: '". $upload_allow."',
		  upload_max_size: '".$this->encryption($shortcodeAtts['upload_max_size'])."',
	      },
		  height: '".$shortcodeAtts['height']."',
		  width: '".$shortcodeAtts['width']."',
		  ui: afmui,
		  requestType : 'post',
	  });
});" );

if($shortcodeAtts['login'] == 'yes') {
if(is_user_logged_in()) {
   global $wp_roles;
   $current_user = wp_get_current_user();
$uid = $current_user->ID;
			$user = new WP_User( $uid );
			/*if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
				foreach ( $user->roles as $role ):
					$role;
				endforeach;
			}*/
	$roles = $shortcodeAtts['roles'];
if(empty($roles)){
 $roles = array();
}
else if($roles == 'all')
{
 $roles = array();
   $roless = $wp_roles->get_names();
	foreach($roless as $key => $mkrole)
	{
		$roles[] = $key;
	}
}
else if($roles != 'all')
{
  $roles = explode(',',$roles);
}
$fma_permission = false;
$fma_intersect = array_intersect($user->roles,$roles);
if(count($fma_intersect) > 0) {
	$fma_permission = true;
}
$block_users = $shortcodeAtts['block_users'];
if(empty($block_users))
{
 $block_users_Array = array('-1' => '-1');
}
else
{
$block_users_Array = explode(',', $block_users);
}
  if($fma_intersect && !in_array($uid, $block_users_Array)){
   $fma_adv .= '<div id="'.$fma_id.'"></div>';
} else {
    $fma_adv .= 'Permissions Denied!';
}
 } else {
	$fma_adv .= '<strong>Login to access file manager. <a href="'.site_url().'/wp-login.php?redirect_to='.get_permalink().'&reauth=1" class="button">Click To Login</a></strong>';
 }
 } else {
	  $fma_adv .= '<div id="'.$fma_id.'"></div>';

 }
 ?>