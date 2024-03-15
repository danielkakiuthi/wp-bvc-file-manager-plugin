<?php if ( ! defined( 'ABSPATH' ) ) {
        exit;
    } 
	$operations = fma_shortcode_generator::operations();
	$languages = fma_shortcode_generator::languages();
    $roles = fma_shortcode_generator::wpUserRoles();
	$themes = fma_shortcode_generator::themes();
	$ui = fma_shortcode_generator:: ui();
	fma_shortcode_generator::shortcode_generator_enqueue_script();
	$id = intval($_GET['id']);
	$shortcodeId = sanitize_text_field($_GET['shortcodeId']);
	$nonce = sanitize_text_field($_GET['nonce']);
	$shortcodeData =  fma_shortcode_generator::getShortcode($id, $shortcodeId, $nonce);
	$shortcodeDataUnserialized = maybe_unserialize($shortcodeData->shortcode_data);
 ?>
 <div class="wrap" style="background:#fff; padding: 20px; border:1px solid #ccc;">
 <h3>Edit Shortcode <a href="<?php echo admin_url('admin.php?page=file_manager_advanced_shortcode_generator');?>" class="button button-primary">Shortcodes</a></h3>
 <?php if(isset($_POST['submit'])):
    fma_shortcode_generator::editGeneratedShortcode($id, $shortcodeId); 
  endif; ?>	
 <form action="<?php echo admin_url('admin.php?page=fma_shortcode_edit&id='.$id.'&shortcodeId='.$shortcodeId.'&nonce='.$nonce);?>" method="post" name="afm-shortcode-generator" id="afm-shortcode-generator" class="afmshortcodegenerator">
 <?php wp_nonce_field( 'fma_edit_nonce', 'fma_edit_nonce_field' ); ?>
<table class="form-table">
<tbody>
<tr>
<th>Shortcode Title*</th>
<td>
<input type="text" name="shortcode_title" id="shortcode_title" class="regular-text" value="<?php echo $shortcodeData->title;?>" placeholder="Enter shortcode title*" required />
<div>
<p class="description">Enter suitable unique shortcode title.</p>
</div>
</td>
</tr>

<tr>
<th>Shortcode Type*</th>
<td>
<select name="login" id="login">
	<option value="1" <?php echo ($shortcodeData->type == '1') ? 'selected="selected"': ''; ?>>Logged In Users</option>
	<option value="0" <?php echo ($shortcodeData->type == '0') ? 'selected="selected"': ''; ?>>Non Logged In Users (Site visitors)</option>
</select>
<p class="description">Select shortcode type to generate shortcode for logged in and non logged in users.</p>
</td>
</tr>

<tr>
<th>
Select User Roles*</th>
<td>
<?php
foreach($roles as $key => $role) {
	$checked = '';
	if(in_array($key, $shortcodeDataUnserialized['user_roles'])){
	  $checked = 'checked="checked"';
	} 
	?>
<input type="checkbox" value="<?php echo esc_attr($key);?>" name="fma_user_role[]" <?php echo esc_attr($checked); ?> /> <?php echo esc_attr($role['name']);?> <br/>
<?php } ?>
<p class="description">Only selected user roles can access file manager. Default: All </p>
</td>
</tr>

<tr>
<th>Path*</th>
<td>
<input name="path" type="text" id="path" value="<?php echo $shortcodeDataUnserialized['path'];?>" placeholder="Enter folder path or allowed symbols like % or $" class="regular-text" required />
<p class="description">Any valid folder path or suggested symbols like % and $.<br/>
(1) Any valid folder path. Eg. <code>wp-content/uploads</code><br/>
(2) <strong>%</strong> - Root Directory<br/>
(3) <strong>$</strong> - Will generate logged in users personal folder of their username (unique) under location "wp-content/uploads/file-manager-advanced/users", use path="$" in shortcode.<br/>
(4) <code>wp-content/uploads/file-manager-advanced/users</code> - you can view and access all user's personal folders under this path.</p>
</td>
</tr>

<tr>
<th>Path Type*</th>
<td>
<select name="path_type" id="path_type">
	<option value="inside" <?php echo ($shortcodeDataUnserialized['path_type'] == 'inside') ? 'selected="selected"': ''; ?>>Inside</option>
	<option value="outside" <?php echo ($shortcodeDataUnserialized['path_type'] == 'outside') ? 'selected="selected"': ''; ?>>Outside</option>
</select>
<p class="description">Select "Outside", if you want to use any directory (Folder) outside wordpress root directory, default: Inside</p>
</td>
</tr>

<tr>
<th>URL</th>
<td>
<input name="url" type="url" id="url" value="<?php echo isset($shortcodeDataUnserialized['url']) ? $shortcodeDataUnserialized['url'] : '' ;?>" class="regular-text">
<p class="description">This option will work only with path type "Outside".</p>
</td>
</tr>

<tr>
<th>Hide</th>
<td>
<input name="hide" type="text" id="hide" value="<?php echo $shortcodeDataUnserialized['hide'];?>" class="regular-text">
<p class="description">Enter folder or file path you want to hide. Muliple comma(,) separated.</p>
</td>
</tr>

<tr>
<th>Operations*</th>
<td>
<table class="afm_operation_table">	
<tr>
<?php 
$count = 1;
foreach($operations as $key => $operation) { 
	$checked = '';
	if(is_array($shortcodeDataUnserialized['operations'])) {
	 if(in_array($key, $shortcodeDataUnserialized['operations'])){
	   $checked = 'checked="checked"';
	 }
   } else if($shortcodeDataUnserialized['operations'] == 'all') {
	   $checked = 'checked="checked"';
   }
	?>
<td><input type="checkbox" value="<?php echo $key; ?>" name="operations[]" <?php echo $checked; ?>><?php echo $operation; ?></td>
<?php 
echo ($count++%6==0) ? '</tr><tr>' : '';
} ?>
</tr>
</table>
<p class="description">Select file operations you want to assign to file manager. Default: All</p>
</td>
</tr>

<tr>
<th>Block Users</th>
<td>
<input name="block_users" type="text" id="block_users" value="<?php echo $shortcodeDataUnserialized['block_users'];?>" class="regular-text">
<p class="description">Enter user ids seprated by comma(,) you want to block the access of File Manager. Example: 2,10</p>
</td>
</tr>

<tr>
<th>View*</th>
<td>
<select name="view" id="view">
	<option value="grid" <?php echo (isset($shortcodeDataUnserialized['view']) && $shortcodeDataUnserialized['view'] == 'grid') ? 'selected="selected"': ''; ?>>Grid</option>
	<option value="list" <?php echo (isset($shortcodeDataUnserialized['view']) && $shortcodeDataUnserialized['view'] == 'list') ? 'selected="selected"': ''; ?>>List</option>
</select>
<p class="description">The option 'Grid' will return the file manager files layout in grid format and the option 'List' will return the file manager files layout in list format.</p>
</td>
</tr>

<tr>
<th>Theme*</th>
<td>
<select name="fma_theme" id="fma_theme">
<?php foreach($themes as $key => $theme) { 
	$selected = '';
	if($key == $shortcodeDataUnserialized['theme']) {
		$selected = "selected=selected";
	}
	?>
	<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $theme; ?></option>
<?php } ?>
</select>
<p class="description">Select file manager advanced theme. Default: Light</p>
</td>
</tr>

<tr>
<th>Language</th>
<td>
<select name="lang" id="lang">
<?php foreach($languages as $key => $language) {
	$selected = '';
	if($key == $shortcodeDataUnserialized['lang']) {
		$selected = "selected=selected";
	}
	?>
<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $language; ?></option>
<?php } ?>
</select>
<p class="description">Select file manager advanced language. Default: English (en)</p>
</td>
</tr>

<tr>
<th>Date Format*</th>
<td>
<input name="dateformat" type="text" id="dateformat" value="<?php echo $shortcodeDataUnserialized['dateformat'];?>" class="regular-text" required />
<p class="description">File creation or modification date format. You can change this formar as per your requirement. Example: dateformat : 'M d, Y h:i A' will return <?php echo date('M d, Y h:i A')?></p>
</td>
</tr>

<tr>
<th>Hide Path*</th>
<td>
<select name="hide_path" id="hide_path">
	<option value="no" <?php echo (isset($shortcodeDataUnserialized['hide_path']) && $shortcodeDataUnserialized['hide_path'] == 'no') ? 'selected="selected"': ''; ?>>No</option>
	<option value="yes" <?php echo (isset($shortcodeDataUnserialized['hide_path']) && $shortcodeDataUnserialized['hide_path'] == 'yes') ? 'selected="selected"': ''; ?>>Yes</option>
</select>
<p class="description">The option 'Yes' will hide the real file path on preview. Default: No</p>
</td>
</tr>

<tr>
<th>Enable Trash*</th>
<td>
<select name="enable_trash" id="enable_trash">
	<option value="no" <?php echo (isset($shortcodeDataUnserialized['enable_trash']) && $shortcodeDataUnserialized['enable_trash'] == 'no') ? 'selected="selected"': ''; ?>>No</option>
	<option value="yes" <?php echo (isset($shortcodeDataUnserialized['enable_trash']) && $shortcodeDataUnserialized['enable_trash'] == 'yes') ? 'selected="selected"': ''; ?>>Yes</option>
</select>
<p class="description">The option 'Yes' will enable the trash. Default: No	</p>
</td>
</tr>

<tr>
<th>Height</th>
<td>
<input type="text" name="height" id="height" class="regular-text" value="<?php echo $shortcodeDataUnserialized['height'];?>">
<div>
<p>Height of file manager, empty will set auto height.</p>
</div>
</td>
</tr>

<tr>
<th>Width</th>
<td>
<input type="text" name="width" id="width" class="regular-text" value="<?php echo $shortcodeDataUnserialized['width'];?>">
<div>
<p>Width of file manager, empty will set auto width.</p>
</div>
</td>
</tr>

<tr>
<th>Read</th>
<td>
<select name="read" id="read">
	<option value="true" <?php echo (isset($shortcodeDataUnserialized['read']) && $shortcodeDataUnserialized['read'] == 'true') ? 'selected="selected"': ''; ?>>True</option>
	<option value="false" <?php echo (isset($shortcodeDataUnserialized['read']) && $shortcodeDataUnserialized['read'] == 'false') ? 'selected="selected"': ''; ?>>False</option>
</select>
<p class="description">Select read mode in file manager.</p>
</td>
</tr>

<tr>
<th>Write</th>
<td>
<select name="write" id="write">
	<option value="true" <?php echo (isset($shortcodeDataUnserialized['write']) && $shortcodeDataUnserialized['write'] == 'true') ? 'selected="selected"': ''; ?>>True</option>
	<option value="false" <?php echo (isset($shortcodeDataUnserialized['write']) && $shortcodeDataUnserialized['write'] == 'false') ? 'selected="selected"': ''; ?>>False</option>
</select>
<p class="description">Select write mode in file manager.</p>
</td>
</tr>

<tr>
<th>Default View Type (ui)</th>
<td>
    <?php foreach($ui as $uii):
		$checked = '';
		if(in_array($uii, $shortcodeDataUnserialized['display_ui_options'])) {
			$checked = 'checked=checked';
		}
		?>
      <input type="checkbox" value="<?php echo $uii;?>" name="display_ui_options[]" <?php echo $checked;?>> <?php echo $uii;?> <br>
    <?php endforeach;?>
 <p>You can control the layout of file manager. By default, all options are checked.</p>
</td>
</tr>

<tr>
<th>Maximum Upload Size*</th>
<td>
<input type="text" name="upload_max_size" id="upload_max_size" class="regular-text" value="<?php echo $shortcodeDataUnserialized['upload_max_size'];?>" required />
<div>
<p>
Maximum upload file size. This size is per files. Can be set as number with unit like 10M, 500K, 1G. 0 means unlimited upload.</p>
</div>
</td>
</tr> 

<tr>
<th>Mimetypes allowed to upload*</th>
<td>
<textarea name="upload_allow" id="upload_allow" class="large-text" rows="3" cols="30" required /><?php echo $shortcodeDataUnserialized['upload_allow'];?></textarea>	
<p class="description">Enter Mimetypes allowed to upload, multiple comma(,) separated. Example: <code>image/vnd.adobe.photoshop,image/png</code></p>
<p>Default: <code>all</code> <a href="https://advancedfilemanager.com/advanced-file-manager-mime-types/" target="_blank">MIME Types Help</a></p>
</td>
</tr>

<tr>
<th>Active?*</th>
<td>
<select name="status" id="status">
	<option value="1" <?php echo (isset($shortcodeData->status) && $shortcodeData->status == '1') ? 'selected="selected"': ''; ?>>Yes</option>
	<option value="0" <?php echo (isset($shortcodeData->status) && $shortcodeData->status == '0') ? 'selected="selected"': ''; ?>>No</option>
</select>
<p class="description">Select shortcode activation status.</p>
</td>
</tr>

</tbody>
</table>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update Changes"></p>
</form>
 </div>