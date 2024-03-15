<?php
/**
  Plugin Name: File Manager Advanced Shortcode
  Plugin URI: https://advancedfilemanager.com/product/file-manager-advanced-shortcode-wordpress/
  Description: Shortcodes for advanced file manager
  Author: modalweb
  Version: 2.5.1
  Author URI: https://advancedfilemanager.com/
**/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
define('fmas_file',__FILE__);
define('fmas_ver','2.5.1');
/**
 * Shortcode Generator
 */
include('shortcode_generator/afm_shortcode_generator.php');
if(!class_exists('file_manager_advanced_shortcode')) {

class file_manager_advanced_shortcode {
	var $ver = '2.5.1';
    /* constructor */
	public function __construct() {
       add_action( 'init', array($this,'file_manager_advanced_directory'));
	   add_shortcode('file_manager_advanced',array($this,'file_manager_advanced_return'));
	   add_action( 'init', array(&$this,'fma_shortcode_updates'));
       add_shortcode( 'fma_user_role', array($this,'fma_check_user_role'));
       add_shortcode( 'fma_user', array($this,'fma_check_user'));
       add_shortcode('advanced_file_manager_front', array($this, 'advanced_file_manager_front_shortcode_callback'));
       register_activation_hook ( __FILE__, array($this, 'on_activate' ));
       add_action( 'admin_menu', array( $this, 'afm_shortcode_generator_menus' ), 99 );
	}
    /**
     * Add afm menus
     */
    public function afm_shortcode_generator_menus() {
        fma_shortcode_generator::fma_shortcode_menus();
    }
    /**
     * shortcocde front latest
     */
    public function advanced_file_manager_front_shortcode_callback($attr) {
        ob_start();
        if(!$attr) {
            return '<p class="afm_returned_error">Invalid Shortcode!</p>';
        } else {
            if(!isset($attr['id'])) {
                return '<p class="afm_returned_error">Invalid Shortcode!</p>';
            } else {
            $id = sanitize_text_field($attr['id']);
             global $wpdb;
            $table_name = $wpdb->prefix.'fma_addon_shortcodes';
            $shortcodeData = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE shortcode_id = %s", $id) );
                if(!$shortcodeData) {
                    return '<p class="afm_returned_error">Invalid Shortcode!</p>';
                }
                if($shortcodeData->status == 0) {
                    return '<p class="afm_returned_error">File Manager has been deactivated by site admin.</p>';
                }
           } 
            $fma_id = 'fma_ui_'.$shortcodeData->shortcode_id;
            $shortcode_data = maybe_unserialize($shortcodeData->shortcode_data);
            $shortcode_data_array = isset($shortcodeData->shortcode_data) ? maybe_unserialize($shortcodeData->shortcode_data) : array();
		    $shortcode_data = apply_filters( 'afm_shortcode_filter_'.$shortcodeData->shortcode_id, $shortcode_data_array );
            self::afm_scripts($fma_id, $shortcode_data['theme'], $shortcode_data['lang']);
            if($shortcodeData->type == 1) {
              include('shortcode_generator/pages/secure_shortcode_logged_in.php');
            } else if($shortcodeData->type == 0) {
              include('shortcode_generator/pages/secure_shortcode_non_logged_in.php');
            }
            $shortcode_output = ob_get_contents();
            ob_end_clean();
            return $shortcode_output;
        }
      }
      /**
       * on activation
       */
      public function on_activate() {
          fma_shortcode_generator::createTables();
      }
    /* shortcode */
	public function file_manager_advanced_return($atts) {
        if(class_exists('class_fma_shortcode') && !is_admin()) {
            wp_enqueue_script('jquery');
        $fma_adv = '';
        $shortcodeAtts = shortcode_atts( array(
                'id' => 'file_manager_advanced',
                'login' => 'yes',
                'roles' => 'all',
                'path' => '%',
                'url' => '',
                'path_type' => 'inside',
                'write' => 'false',
                'read' => 'true',
                'hide' => '',
                'operations' => 'all',
                'block_users' => '',
                'view' => 'grid',
                'theme' => 'light',
                'lang' => 'en',
                'dateformat' => 'M d, Y h:i A',
                'hide_path' =>  'no',
                'enable_trash' => 'no',
                'height' => '',
                'width' => '',
                'ui' => '',
                'upload_allow' => 'all',
                'upload_max_size' => '0',
            ), $atts );

        $fma_id = !empty($shortcodeAtts['id']) ? $shortcodeAtts['id'] : 'file_manager_advanced';

                    $elfCss = [
                        'commands.css',
                        'common.css',
                        'contextmenu.css',
                        'cwd.css',
                        'dialog.css',
                        'fonts.css',
                        'navbar.css',
                        'quicklook.css',
                        'statusbar.css',
                        'toast.css',
                        'toolbar.css'
                    ];

                wp_enqueue_style( 'query-ui-1.12.0', plugins_url('library/jquery/jquery-ui-1.12.0.css', fma_file));

                foreach($elfCss as $elCss) {
                    wp_enqueue_style( $elCss, plugins_url('library/css/'.$elCss.'', fma_file));	
                }
                wp_enqueue_style( 'fma_theme', plugins_url('library/css/theme.css', fma_file));
                if(isset($shortcodeAtts['theme']) && $shortcodeAtts ['theme'] == 'dark') {
                wp_enqueue_style( 'fma_themee_'.$fma_id, plugins_url('library/themes/dark/css/theme.css', fma_file));
                }
                else if(isset($shortcodeAtts['theme']) && $shortcodeAtts ['theme'] == 'grey') {
                wp_enqueue_style( 'fma_themee', plugins_url('library/themes/grey/css/theme.css', fma_file));
                }
                else if(isset($shortcodeAtts['theme']) && $shortcodeAtts ['theme'] == 'windows10') {
                wp_enqueue_style( 'fma_themee', plugins_url('library/themes/windows10/css/theme.css', fma_file));
                }
                else if(isset($shortcodeAtts['theme']) && $shortcodeAtts ['theme'] == 'bootstrap') {
                wp_enqueue_style( 'fma_themee', plugins_url('library/themes/bootstrap/css/theme.css', fma_file));
                }
                wp_enqueue_style( 'fma_custom', plugins_url('library/css/custom_style_filemanager_advanced.css', fma_file));

                wp_enqueue_script('afm-init-jquery', plugins_url('library/js/init.js', fma_file));

                wp_enqueue_script( 'afm-elFinder', plugins_url('library/js/elFinder.js', fma_file), array('jquery', 'jquery-ui-core', 'jquery-ui-selectable', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-resizable', 'jquery-ui-dialog', 'jquery-ui-slider', 'jquery-ui-tabs'));

                wp_enqueue_script( 'afm-elFinder.version', plugins_url('library/js/elFinder.version.js', fma_file));
                wp_enqueue_script( 'afm-jquery.elfinder', plugins_url('library/js/jquery.elfinder.js', fma_file));
                wp_enqueue_script( 'afm-elFinder.mimetypes', plugins_url('library/js/elFinder.mimetypes.js', fma_file));
                wp_enqueue_script( 'afm-elFinder.options', plugins_url('library/js/elFinder.options.js', fma_file));
                wp_enqueue_script( 'afm-elFinder.options.netmount', plugins_url('library/js/elFinder.options.netmount.js', fma_file));
                wp_enqueue_script( 'afm-elFinder.history', plugins_url('library/js/elFinder.history.js', fma_file));
                wp_enqueue_script( 'afm-elFinder.command', plugins_url('library/js/elFinder.command.js', fma_file));
                wp_enqueue_script( 'afm-elFinder.resources', plugins_url('library/js/elFinder.resources.js', fma_file));
            
                wp_enqueue_script( 'afm-jquery.dialogelfinder', plugins_url('library/js/jquery.dialogelfinder.js', fma_file));
            

                wp_enqueue_script( 'afm-button', plugins_url('library/js/ui/button.js', fma_file));
                wp_enqueue_script( 'afm-contextmenu', plugins_url('library/js/ui/contextmenu.js', fma_file));
                wp_enqueue_script( 'afm-cwd', plugins_url('library/js/ui/cwd.js', fma_file));
                wp_enqueue_script( 'afm-dialog', plugins_url('library/js/ui/dialog.js', fma_file));
                wp_enqueue_script( 'afm-fullscreenbutton', plugins_url('library/js/ui/fullscreenbutton.js', fma_file));
                wp_enqueue_script( 'afm-navbar', plugins_url('library/js/ui/navbar.js', fma_file));
                wp_enqueue_script( 'afm-navdock', plugins_url('library/js/ui/navdock.js', fma_file));
                wp_enqueue_script( 'afm-overlay', plugins_url('library/js/ui/overlay.js', fma_file));
                wp_enqueue_script( 'afm-panel', plugins_url('library/js/ui/panel.js', fma_file));
                wp_enqueue_script( 'afm-path', plugins_url('library/js/ui/path.js', fma_file));
                wp_enqueue_script( 'afm-searchbutton', plugins_url('library/js/ui/searchbutton.js', fma_file));
                wp_enqueue_script( 'afm-sortbutton', plugins_url('library/js/ui/sortbutton.js', fma_file));
                wp_enqueue_script( 'afm-stat', plugins_url('library/js/ui/stat.js', fma_file));
                wp_enqueue_script( 'afm-toast', plugins_url('library/js/ui/toast.js', fma_file));
                wp_enqueue_script( 'afm-toolbar', plugins_url('library/js/ui/toolbar.js', fma_file));
                wp_enqueue_script( 'afm-tree', plugins_url('library/js/ui/tree.js', fma_file));
                wp_enqueue_script( 'afm-uploadButton', plugins_url('library/js/ui/uploadButton.js', fma_file));
                wp_enqueue_script( 'afm-viewbutton', plugins_url('library/js/ui/viewbutton.js', fma_file));
                wp_enqueue_script( 'afm-workzone', plugins_url('library/js/ui/workzone.js', fma_file));
            

                wp_enqueue_script( 'afm-archive', plugins_url('library/js/commands/archive.js', fma_file));
                wp_enqueue_script( 'afm-back', plugins_url('library/js/commands/back.js', fma_file));
                wp_enqueue_script( 'afm-chmod', plugins_url('library/js/commands/chmod.js', fma_file));
                wp_enqueue_script( 'afm-colwidth', plugins_url('library/js/commands/colwidth.js', fma_file));
                wp_enqueue_script( 'afm-copy', plugins_url('library/js/commands/copy.js', fma_file));
                wp_enqueue_script( 'afm-cut', plugins_url('library/js/commands/cut.js', fma_file));
                wp_enqueue_script( 'afm-download', plugins_url('library/js/commands/download.js', fma_file));
                wp_enqueue_script( 'afm-duplicate', plugins_url('library/js/commands/duplicate.js', fma_file));
                wp_enqueue_script( 'afm-edit', plugins_url('library/js/commands/edit.js', fma_file));
                wp_enqueue_script( 'afm-empty', plugins_url('library/js/commands/empty.js', fma_file));
                wp_enqueue_script( 'afm-extract', plugins_url('library/js/commands/extract.js', fma_file));
                wp_enqueue_script( 'afm-forward', plugins_url('library/js/commands/forward.js', fma_file));
                wp_enqueue_script( 'afm-fullscreen', plugins_url('library/js/commands/fullscreen.js', fma_file));
                wp_enqueue_script( 'afm-getfile', plugins_url('library/js/commands/getfile.js', fma_file));
                wp_enqueue_script( 'afm-help', plugins_url('library/js/commands/help.js', fma_file));
                wp_enqueue_script( 'afm-hidden', plugins_url('library/js/commands/hidden.js', fma_file));
                //wp_enqueue_script( 'afm-hide', plugins_url('library/js/commands/hide.js', fma_file));
                wp_enqueue_script( 'afm-home', plugins_url('library/js/commands/home.js', fma_file));
                wp_enqueue_script( 'afm-info', plugins_url('library/js/commands/info.js', fma_file));
                wp_enqueue_script( 'afm-mkdir', plugins_url('library/js/commands/mkdir.js', fma_file));
                wp_enqueue_script( 'afm-mkfile', plugins_url('library/js/commands/mkfile.js', fma_file));
                wp_enqueue_script( 'afm-netmount', plugins_url('library/js/commands/netmount.js', fma_file));
                wp_enqueue_script( 'afm-open', plugins_url('library/js/commands/open.js', fma_file));
                wp_enqueue_script( 'afm-opendir', plugins_url('library/js/commands/opendir.js', fma_file));
                wp_enqueue_script( 'afm-opennew', plugins_url('library/js/commands/opennew.js', fma_file));
                wp_enqueue_script( 'afm-paste', plugins_url('library/js/commands/paste.js', fma_file));
                wp_enqueue_script( 'afm-quicklook', plugins_url('library/js/commands/quicklook.js', fma_file));
                wp_enqueue_script( 'afm-quicklook.plugins', plugins_url('library/js/commands/quicklook.plugins.js', fma_file));
                wp_enqueue_script( 'afm-reload', plugins_url('library/js/commands/reload.js', fma_file));
                wp_enqueue_script( 'afm-rename', plugins_url('library/js/commands/rename.js', fma_file));
                wp_enqueue_script( 'afm-resize', plugins_url('library/js/commands/resize.js', fma_file));
                wp_enqueue_script( 'afm-restore', plugins_url('library/js/commands/restore.js', fma_file));
                wp_enqueue_script( 'afm-rm', plugins_url('library/js/commands/rm.js', fma_file));
                wp_enqueue_script( 'afm-search', plugins_url('library/js/commands/search.js', fma_file));
                wp_enqueue_script( 'afm-selectall', plugins_url('library/js/commands/selectall.js', fma_file));
                wp_enqueue_script( 'afm-selectinvert', plugins_url('library/js/commands/selectinvert.js', fma_file));
                wp_enqueue_script( 'afm-selectnone', plugins_url('library/js/commands/selectnone.js', fma_file));
                wp_enqueue_script( 'afm-sort', plugins_url('library/js/commands/sort.js', fma_file));
                wp_enqueue_script( 'afm-undo', plugins_url('library/js/commands/undo.js', fma_file));
                wp_enqueue_script( 'afm-up', plugins_url('library/js/commands/up.js', fma_file));
                wp_enqueue_script( 'afm-upload', plugins_url('library/js/commands/upload.js', fma_file));
                wp_enqueue_script( 'afm-view', plugins_url('library/js/commands/view.js', fma_file));
                wp_enqueue_script( 'afm-quicklook.googledocs', plugins_url('library/js/extras/quicklook.googledocs.js', fma_file));

                if(isset($shortcodeAtts['lang'])) {
                    $locale = $shortcodeAtts['lang'];
                    wp_enqueue_script( 'fma_lang_'.$shortcodeAtts['lang'], plugins_url('library/js/i18n/elfinder.'.$locale.'.js', fma_file));
                }

                wp_enqueue_script( 'codemirror', plugins_url('library/codemirror/lib/codemirror.js',  fma_file ));
                wp_enqueue_style( 'codemirror', plugins_url('library/codemirror/lib/codemirror.css', fma_file));
                wp_enqueue_script( 'htmlmixed', plugins_url('library/codemirror/mode/htmlmixed/htmlmixed.js',  fma_file ));
                wp_enqueue_script( 'xml', plugins_url('library/codemirror/mode/xml/xml.js',  fma_file ));
                wp_enqueue_script( 'css', plugins_url('library/codemirror/mode/css/css.js',  fma_file ));
                wp_enqueue_script( 'javascript', plugins_url('library/codemirror/mode/javascript/javascript.js',  fma_file ));
                wp_enqueue_script( 'clike', plugins_url('library/codemirror/mode/clike/clike.js',  fma_file ));
                wp_enqueue_script( 'php', plugins_url('library/codemirror/mode/php/php.js',  fma_file ));	
        
                $generated_script_id = $fma_id."-fma-shortcode-js";

                wp_register_script( $generated_script_id, plugins_url('js/shortcode.js', fmas_file ), array('jquery') );

                wp_enqueue_script( $generated_script_id );
                include('pages/shortcode.php');
                return $fma_adv;
        } else {
            $plugin_url_text = '<strong>Please install <a href="https://wordpress.org/plugins/file-manager-advanced/"> File Manager Advanced </a> Plugin to make shortcode work.</strong>';
            return $plugin_url_text;
        }
    }
     /**
     * afm scripts
     */
    public static function afm_scripts($shortcodeId = '', $theme='', $lang='') {
        wp_enqueue_script('jquery');
        $fma_id = !empty($shortcodeId) ? $shortcodeId  : 'file_manager_advanced_secure';
        $elfCss = [
            'commands.css',
            'common.css',
            'contextmenu.css',
            'cwd.css',
            'dialog.css',
            'fonts.css',
            'navbar.css',
            'quicklook.css',
            'statusbar.css',
            'toast.css',
            'toolbar.css'
        ];
    wp_enqueue_style( 'query-ui-1.12.0', plugins_url('library/jquery/jquery-ui-1.12.0.css', fma_file));
    foreach($elfCss as $elCss) {
        wp_enqueue_style( $elCss, plugins_url('library/css/'.$elCss.'', fma_file));	
    }

    wp_enqueue_style( 'fma_theme', plugins_url('library/css/theme.css', fma_file));
   
    $themes = fma_shortcode_generator::themes();
    /**
     * Removing light theme as it is a default theme
     */
    unset($themes['light']);
  
    if(isset($theme) && array_key_exists($theme,$themes)) {
      wp_enqueue_style( 'fma_theme_'.$fma_id, plugins_url('library/themes/'.$theme.'/css/theme.css', fma_file));
    }

    wp_enqueue_style( 'fma_custom', plugins_url('library/css/custom_style_filemanager_advanced.css', fma_file));

    wp_enqueue_script('afm-init-jquery', plugins_url('library/js/init.js', fma_file));
    wp_enqueue_script( 'afm-elFinder', plugins_url('library/js/elFinder.js', fma_file), array('jquery', 'jquery-ui-core', 'jquery-ui-selectable', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-resizable', 'jquery-ui-dialog', 'jquery-ui-slider', 'jquery-ui-tabs'));
    
    $elfinderLibraryScripts = ['elFinder.version.js', 'jquery.elfinder.js', 'elFinder.mimetypes.js', 'elFinder.options.js', 'elFinder.options.netmount.js', 'elFinder.history.js', 'elFinder.command.js','elFinder.resources.js', 'jquery.dialogelfinder.js'];

    foreach($elfinderLibraryScripts as $elfinderLibraryScript) {
        wp_enqueue_script( $elfinderLibraryScript, plugins_url('library/js/'.$elfinderLibraryScript, fma_file));
    }


    $elfinderUiScripts = ['button.js', 'contextmenu.js', 'cwd.js', 'dialog.js', 'fullscreenbutton.js', 'navbar.js', 'navdock.js', 'overlay.js', 'panel.js', 'path.js', 'searchbutton.js', 'sortbutton.js', 'stat.js', 'toast.js', 'toolbar.js', 'tree.js', 'uploadButton.js', 'viewbutton.js', 'workzone.js'];

    foreach($elfinderUiScripts as $elfinderUiScript) {
      wp_enqueue_script( 'afm-'.$elfinderUiScript, plugins_url('library/js/ui/'.$elfinderUiScript, fma_file));
    }

    $elfinderCommandsScripts = ['archive.js', 'back.js', 'chmod.js', 'colwidth.js', 'copy.js', 'cut.js', 'download.js', 'duplicate.js', 'edit.js', 'empty.js', 'extract.js', 'forward.js', 'fullscreen.js', 'getfile.js', 'help.js', 'hidden.js', 'home.js', 'info.js', 'mkdir.js', 'mkfile.js', 'netmount.js', 'open.js', 'opendir.js', 'opennew.js', 'paste.js', 'quicklook.js', 'quicklook.plugins.js', 'reload.js', 'rename.js', 'resize.js', 'restore.js', 'rm.js', 'search.js', 'selectall.js', 'selectinvert.js', 'selectnone.js', 'sort.js', 'undo.js', 'up.js', 'upload.js', 'view.js'];

    foreach($elfinderCommandsScripts as $elfinderCommandsScript) {
       wp_enqueue_script( 'afm-'.$elfinderCommandsScript, plugins_url('library/js/commands/'.$elfinderCommandsScript, fma_file));
    }
    wp_enqueue_script( 'afm-quicklook.googledocs', plugins_url('library/js/extras/quicklook.googledocs.js', fma_file));

    if(isset($lang)) {
        $locale = $lang;
        wp_enqueue_script( 'fma_lang_'.$locale, plugins_url('library/js/i18n/elfinder.'.$locale.'.js', fma_file));
    }

    wp_enqueue_script( 'codemirror', plugins_url('library/codemirror/lib/codemirror.js',  fma_file ));
    wp_enqueue_style( 'codemirror', plugins_url('library/codemirror/lib/codemirror.css', fma_file));

    $CmModes = ['htmlmixed', 'xml', 'css', 'javascript', 'clike', 'php'];
 
    foreach($CmModes as $CmMode){
      wp_enqueue_script( $CmMode, plugins_url('library/codemirror/mode/'.$CmMode.'/'.$CmMode.'.js',  fma_file ));
    }

    }
    /* Directory */
    public function file_manager_advanced_directory() {
         if(is_user_logged_in()) {
                    $current_user = wp_get_current_user();
                    $upload_dir   = wp_upload_dir();
                    if ( isset( $current_user->user_login ) && ! empty( $upload_dir['basedir'] ) ) {
                        $user_dirname = $upload_dir['basedir'].'/file-manager-advanced/users/'.$current_user->user_login;
                            if ( ! file_exists( $user_dirname ) ) {
                              wp_mkdir_p( $user_dirname );
                        }
                    }
        }
        /**
         * check if shortcode generator table not exists
         */
        fma_shortcode_generator::createTables();
    }
	/* User Role Shortcode */
	// use: [fma_user_role role="subscriber, author"]shortcode[/fma_user_role]
	public function fma_check_user_role( $atts, $content = null ) {
        extract( shortcode_atts( array(
                'role' => 'role' ), $atts ) );
        $user = wp_get_current_user();
        $roles = explode(',', $role);
        $allowed_roles = $roles;
        if( array_intersect($allowed_roles, $user->roles ) ) {
                return apply_filters('the_content',$content);
        }
   }
   /* User Shortcode */
	// use: [fma_user user="1,2"]shortcode[/fma_user]
	public function fma_check_user( $atts, $content = null ) {
        extract( shortcode_atts( array(
                'user' => 'user' ), $atts ) );
        $cuser = wp_get_current_user();
        $users = explode(',', $user);
        $allowed_users = $users;
        if( in_array($cuser->ID, $allowed_users ) ) {
                return apply_filters('the_content',$content);
        }
   } 
   /**
    * Shortcode Updates
    */
	   public function fma_shortcode_updates()
	   {
		    $path = $_SERVER['REQUEST_URI'];
			$file = basename($path, ".php");
			$file_name = explode('?', $file);
			if(($file_name[0] == 'plugins.php') || ($file_name[0] == 'plugins')) {
		    require_once ( 'upgrade/upgrade.php');
			$fma_plugin_current_version = $this->ver;
			$fma_plugin_remote_path = 'https://advancedfilemanager.com/upgrade/';
			$fma_plugin_slug = plugin_basename( __FILE__ );
			$fma_license_order = '1';
			$fma_license_key = 'success';
		      new file_manager_advanced_shortcode_updates( $fma_plugin_current_version, $fma_plugin_remote_path, $fma_plugin_slug, $fma_license_order, $fma_license_key );
			}
	   }
       /**
        * Encryption
        */
        public function encryption($plaintext) {
            $key = bin2hex(get_option('admin_email'));
            $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
            $iv = openssl_random_pseudo_bytes($ivlen);
            $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
            $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
            return base64_encode( $iv.$hmac.$ciphertext_raw );
        }
	}
new file_manager_advanced_shortcode;
/**
 * Shortcode class
 */
require_once('classes/class_fma_shortcode_secure.php');
new class_fma_shortcode_secure;
}