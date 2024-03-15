<?php
/*
@package: File Manager Advanced
@Class: fma_admin_menus
*/
if(class_exists('class_fma_admin_menus')) {
	return;
}
class class_fma_admin_menus {
	var $langs;
	/**
	 * AFM - Languages
	 */
	 public function __construct() {
             include('class_fma_lang.php');
			$this->langs = new class_fma_adv_lang();
	  }
	/**
	 * Loading Menus
	 */
	public function load_menus() {
		
		$fmaPer = $this->fmaPer();
		    
			/** Authorizing only super admin to manage settings */
		    $subPer = 'manage_options';
		    if ( is_multisite() && !is_network_admin() ) {
			   $subPer = 'manage_network';	
			   $fmaPer = $this->networkPer();
		    }

		 add_menu_page(
			__( 'File Manager', 'file-manager-advanced' ),
			__( 'File Manager', 'file-manager-advanced' ),
			$fmaPer,
			'file_manager_advanced_ui',
			array($this, 'file_manager_advanced_ui'),
			plugins_url( 'assets/icon/fma.png', __FILE__ ),
			4
			);
	add_submenu_page( 'file_manager_advanced_ui', 'Settings', 'Settings', $subPer, 'file_manager_advanced_controls', array(&$this, 'file_manager_advanced_controls'));
	add_submenu_page( 'file_manager_advanced_ui', 'Shortcodes', 'Shortcodes', $subPer, 'file_manager_advanced_shortcodes', array(&$this, 'file_manager_advanced_shortcodes'));
	}
	/** 
	 * Fma permissions
	 */
	public function fmaPer() {
		$settings = $this->get();
		$user = wp_get_current_user();
		$allowed_fma_user_roles = isset($settings['fma_user_roles']) ? $settings['fma_user_roles'] : array('administrator');

		if(!in_array('administrator', $allowed_fma_user_roles)) {
		$fma_user_roles = array_merge(array('administrator'), $allowed_fma_user_roles);
		} else {
			$fma_user_roles = $allowed_fma_user_roles;
		}

		$checkUserRoleExistance = array_intersect($fma_user_roles, $user->roles);

		if(count($checkUserRoleExistance) > 0 && !in_array('administrator', $checkUserRoleExistance)) {
            $fmaPer = 'read';
		} else {
			$fmaPer = 'manage_options';
		}
		return $fmaPer;
	}
	/**
	 * Fma - Network Permissions
	 */
	public function networkPer() {
		$settings = $this->get();
		$user = wp_get_current_user();
		$allowed_fma_user_roles = isset($settings['fma_user_roles']) ? $settings['fma_user_roles'] : array();

		$fma_user_roles = $allowed_fma_user_roles;

		$checkUserRoleExistance = array_intersect($fma_user_roles, $user->roles);

		if(count($checkUserRoleExistance) > 0 ) {
			if(!in_array('administrator', $checkUserRoleExistance)) {
				$fmaPer = 'read';
			} else {
				$fmaPer = 'manage_options';
			}
		} else {
			$fmaPer = 'manage_network';
		}
		return $fmaPer;	
	}
	/**
	* Diaplying AFM
    */
     public function file_manager_advanced_ui() {
		 $fmaPer = $this->fmaPer();
		 if(current_user_can($fmaPer)) {
		    include('pages/main.php');
		 }
	 }
	/**
	* Settings
    */
    public function file_manager_advanced_controls(){
		if(current_user_can('manage_options')) {
		    include('pages/controls.php');
		 }
	}
	/**
	* Shortcode
    */
    public function file_manager_advanced_shortcodes(){
		if(current_user_can('manage_options')) {
		    include('pages/buy_shortcode.php');
		 }
	}
   /**
	* Saving Options
    */
    public function save() {
	   if(isset($_POST['submit']) && wp_verify_nonce( $_POST['_fmaform'], 'fmaform' )) {
		    _e('Saving options, Please wait...','file-manager-advanced');
		   $save = array();
		   $defaultRole = array('administrator');
		   if(is_multisite()) {
			$defaultRole = array();
		   }
		   $public_dir = isset($_POST['public_path']) ? sanitize_text_field($_POST['public_path']) : '';
		   $save['fma_user_roles'] = isset($_POST['fma_user_role']) ? array_map('sanitize_text_field',$_POST['fma_user_role']) : $defaultRole;
		   $save['fma_theme'] = isset($_POST['fma_theme']) ? sanitize_text_field($_POST['fma_theme']) : 'light';
		   $save['fma_locale'] = isset($_POST['fma_locale']) ? sanitize_text_field($_POST['fma_locale']) : 'en';
		   /* Directory Traversal fix @220723 */
		   $save['public_path'] = $this->afm_sanitize_directory($public_dir);
           $save['public_url'] = isset($_POST['public_url']) ? sanitize_text_field($_POST['public_url']) : '';
		   //25122022
		   $save['upload_max_size'] = isset($_POST['upload_max_size']) ? sanitize_text_field($_POST['upload_max_size']) : '0';
		   $save['display_ui_options'] = isset($_POST['display_ui_options']) ? array_map('sanitize_text_field',$_POST['display_ui_options']) : array();
           $save['hide_path'] = isset($_POST['hide_path']) ? sanitize_text_field($_POST['hide_path']) : 0;
		   $save['enable_trash'] = isset($_POST['enable_trash']) ? sanitize_text_field($_POST['enable_trash']) : 0;
		   $save['enable_htaccess'] = isset($_POST['enable_htaccess']) ? sanitize_text_field($_POST['enable_htaccess']) : 0;
		   $save['fma_upload_allow'] = isset($_POST['fma_upload_allow']) ? sanitize_text_field($_POST['fma_upload_allow']) : 'all';		   
		  $u = update_option('fmaoptions',$save);
		  if($u) {
			  $this->f('?page=file_manager_advanced_controls&status=1');
		  } else {
			  $this->f('?page=file_manager_advanced_controls&status=2');
		  }
	   }
   }
   /**
	* Sanitize directory path
    */
	public function afm_sanitize_directory($path = '') {
        if(!empty($path)) {
			$path = str_replace('..', '', htmlentities(trim($path)));
		}
		return $path;	
	}
   /**
	* Getting Options
    */
   public function get() {
	   return get_option('fmaoptions');
   }
   /**
	* Diplay Notices
    */
   public function notice($type, $message) {
	    if(isset($type) && !empty($type)) {
	     $class = ($type == '1') ? 'updated' : 'error';
         return '<div class="'.$class.' notice">
		  <p>'.$message.'</p>
		  </div>';
		}
   }
   /**
	* Redirection
    */
    public function f($u) {
		$url = esc_url_raw($u);
		wp_register_script( 'fma-redirect-script', '');
		wp_enqueue_script( 'fma-redirect-script' );
		wp_add_inline_script(
		'fma-redirect-script',
		' window.location.href="'.$url.'" ;'
	  );
	}
	public static function shortcodeUpdateNotice() {
		if(class_exists('file_manager_advanced_shortcode')):
			if(defined('fmas_ver')){ 
				if(fmas_ver < '2.4.1') { 
					return '<div class="error notice" style="background: #f7dfdf">
					<p><strong>Advanced File manager shortcode addon update:</strong> You are using version <strong>'.fmas_ver.'</strong> we recommend you to update to latest version. If you did not receive update please download from <a href="https://advancedfilemanager.com/my-account/" target="_blank">my account</a> page.</p>
					</div>';
				}
			} else {
				return '<div class="error notice" style="background: #f7dfdf">
					<p><strong>Advanced File manager shortcode addon update:</strong> You are using old version, we recommend you to update to latest version. If you did not receive update please download from <a href="https://advancedfilemanager.com/my-account/" target="_blank">my account</a> page.</p>
					</div>';
			}
		endif;
	}
	/**
	 * Get User Roles
	 */
	public function wpUserRoles() {
		global $wp_roles;
        return $wp_roles->roles; 
	}
}