<?php if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
$shortcodeAtts = maybe_unserialize($shortcodeData->shortcode_data);
$lang = (isset($shortcodeAtts['lang']) && !empty($shortcodeAtts['lang'])) ? $shortcodeAtts['lang'] : 'en';
$view = (isset($shortcodeAtts['view']) && !empty($shortcodeAtts['view'])) ? $shortcodeAtts['view'] : 'grid';
$dateFormat = (isset($shortcodeAtts['dateformat']) && !empty($shortcodeAtts['dateformat'])) ? $shortcodeAtts['dateformat'] : 'M d, Y h:i A';
echo wp_add_inline_script( $shortcodeData->shortcode_id, "jQuery(document).ready(function(){
	var afmui = ['toolbar', 'tree', 'path', 'stat'];
	var fma_ui_opt = '".implode(',',$shortcodeAtts['display_ui_options'])."';
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
		  customData : {action: 'fma_secure_shortcode',
		  security: '".$this->encryption(wp_create_nonce('fmaskey'.$shortcodeData->shortcode_id))."',
		  validate: '".$this->encryption($shortcodeData->shortcode_id)."',
	      },
		  height: '".$shortcodeAtts['height']."',
		  width: '".$shortcodeAtts['width']."',
		  ui: afmui,
	  });
});" );

if($shortcodeData->type == 1) {
if(is_user_logged_in()) {
   global $wp_roles;
   $current_user = wp_get_current_user();
   $uid = $current_user->ID;
   $user = new WP_User( $uid );
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
  if($fma_intersect && !in_array($uid, $block_users_Array)) { ?>
<div id="<?php echo $fma_id ?>"></div>
<?php } else {
    return 'Permissions Denied!';
   }
   } 
 } else { ?>
<div id="<?php echo $fma_id ?>"></div>
<?php }
 ?>