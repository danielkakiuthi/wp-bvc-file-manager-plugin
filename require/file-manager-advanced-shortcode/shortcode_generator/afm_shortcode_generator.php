<?php 
/**
 * Shortcode generator - afm
 * Ver:1.0
 */
if(!class_exists('fma_shortcode_generator')) {
    class fma_shortcode_generator {

       public function __construct() {
       } 
        /**
         * Enqueue JS
         */
        public static function shortcode_generator_enqueue_script() {
            wp_enqueue_script( 'afm-shortcode-generator-form-validation', plugin_dir_url( fmas_file ) . 'shortcode_generator/assets/js/jquery.validate.js' );
            wp_enqueue_script( 'afm-shortcode-form-validation', plugin_dir_url( fmas_file ) . 'shortcode_generator/assets/js/afm-shortcode-generator.js' );
            wp_enqueue_style('afm-shortcode-form-validation',  plugin_dir_url( fmas_file ) . 'shortcode_generator/assets/css/afm-shortcode-generator.css', array(), '0.1.0', 'all');
       }
       /**
        * AdditionMenus
        */
       public static function fma_shortcode_menus() {
        add_submenu_page( 'file_manager_advanced_ui', 'Shortcode Generator (PRO)', 'Shortcode Generator (PRO)', 'manage_options', 'file_manager_advanced_shortcode_generator', array('fma_shortcode_generator', 'file_manager_advanced_shortcode_generator'));
        add_submenu_page( '&nbsp' , 'Create Shortcode', 'Create Shortcode', 'manage_options', 'fma_shortcode_create', array('fma_shortcode_generator', 'fma_shortcode_create'));
        add_submenu_page( '&nbsp' , 'Edit Shortcode', 'Edit Shortcode', 'manage_options', 'fma_shortcode_edit', array('fma_shortcode_generator', 'fma_shortcode_edit'));
        add_submenu_page( 'file_manager_advanced_ui', 'Shortcode Settings', 'Shortcode Settings', 'manage_options', 'file_manager_advanced_shortcode_settings', array('fma_shortcode_generator', 'file_manager_advanced_shortcode_settings'));
       }
       /**
       * Shortcode Generator
       */
       public static function file_manager_advanced_shortcode_generator() {
            if(current_user_can('manage_options')) {
                require_once('pages/shortcode_generator.php');
            }
        }
        /**
         * Create shortcode
         */
        public static function fma_shortcode_create() {
            if(current_user_can('manage_options')) {
                require_once('pages/create_shortcode.php');
            }
        }
        /**
         * Edit Shortcode
         */
        public static function fma_shortcode_edit() {
            if(current_user_can('manage_options')) {
                require_once('pages/edit_shortcode.php');
            }
        }
        /**
         * Shortcode Settings
         */
        public static function file_manager_advanced_shortcode_settings() {
            if(current_user_can('manage_options')) {
                require_once('pages/shortcode_settings.php');
            }
        }
        /**
         * Create Tables
         */
        public static function createTables() {
            global $wpdb;
            $table_name = $wpdb->prefix.'fma_addon_shortcodes';
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                id int(11) NOT NULL AUTO_INCREMENT,
                shortcode_id text NOT NULL,
                title text NOT NULL,
                shortcode text NOT NULL,
                type int(11) NOT NULL,
                status int(11) NOT NULL,
                shortcode_data text NOT NULL,
                created_by int(11) NOT NULL,
                updated_by int(11) NOT NULL,
                created_at datetime NULL,
                updated_at datetime NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta( $sql );
        }

        /**
         * List of operations
         */
        public static function operations() {
            $defaultOperation = [
                'archive' => 'Archive', 
                'back' => 'Back', 
                'copy' => 'Copy', 
                'cut' => 'Cut', 
                'download' => 'Download', 
                'duplicate' => 'Duplicate', 
                'edit' => 'Edit', 
                'extract' => 'Extract',
                'forward' => 'Forward', 
                'fullscreen' => 'Full Screen', 
                'getfile' => 'Get File', 
                'home' => 'Home', 
                'info' => 'Info', 
                'mkdir' => 'Create Directory (Folder)', 
                'mkfile' => 'Create File', 
                'netmount' => 'Net Mount', 
                'netunmount' => 'Net Unmount',
                'open' => 'Open', 
                'opendir' => 'Open Directory (Folder)', 
                'paste' => 'Paste', 
                'quicklook' => 'Quick Look', 
                'reload' => 'Reload', 
                'rename' => 'Rename', 
                'resize' => 'Resize', 
                'restore' => 'Restore', 
                'rm' => 'Delete (Remove)',
                'search' => 'Search', 
                'sort' => 'Sort', 
                'up' => 'Up', 
                'upload' => 'Upload', 
                'view' => 'View', 
           ];
           /**
            * Hook to enhance file operations
            */
           $operations = apply_filters( 'afm_shortcode_file_operations', $defaultOperation );
           return $operations;
        }
        /**
         * Languages
         */
        public static function languages() {

            $defaultLangs = [
                 'en' => 'English',
                 'ar' => 'Arabic',
                 'bg' => 'Bulgarian',
                 'ca' => 'Catalan',
                 'cs' => 'Czech',
                 'da' => 'Danish',
                 'de' => 'German',
                 'el' => 'Greek',
                 'es' => 'Español',
                 'fa' => 'Persian-Farsi',
                 'fo' => 'Faroese translation',
                 'fr' => 'French',
                 'he' => 'Hebrew',
                 'hr' => 'hr',
                 'hu' => 'magyar',
                 'id' => 'Indonesian',
                 'it' => 'Italiano',
                 'ja' => 'Japanese',
                 'ko' => 'Korean',
                 'nl' => 'Dutch',
                 'no' => 'Norwegian',
                 'pl' => 'Polski',
                 'pr_BR' => 'Português',
                 'ro' => 'Română',
                 'ru' => 'Russian',
                 'sk' => 'Slovak',
                 'sl' => 'Slovenian',
                 'sr' => 'Serbian',
                 'sv' => 'Swedish',
                 'tr' => 'Türkçe',
                 'ug_CN' => 'Uyghur',
                 'uk' => 'Ukrainian',
                 'vi' => 'Vietnamese',
                 'zh_CN' => 'Simplified Chinese',
                 'zh_TW' => 'Traditional Chinese',
            ];
 
            $languages = apply_filters( 'afm_shortcode_language', $defaultLangs );
            return $languages;
         }
         /**
          * Get Themes
          */
          public static function themes() {
            $defaultThemes = array(
                'light' => 'Light',
                'dark' => 'Dark',
                'grey' => 'Grey',
                'windows10' => 'windows 10',
                'bootstrap' => 'Bootstrap',
            );
            $themes = apply_filters('afm_shortcode_themes', $defaultThemes);
            return $themes;
          }
          /**
          * Get UI
          */
          public static function ui() {
            $ui = array(
                'toolbar',
                'tree',
                'path',
                'stat',
            );
            return $ui;
          }
         /**
          * Generate Shortcode
          */

         public static function generateShortcode() {
            global $wpdb;
            $table_name = $wpdb->prefix.'fma_addon_shortcodes';
            //match nonce
            if(isset($_POST['submit']) && wp_verify_nonce( $_POST['fma_create_nonce_field'], 'fma_create_nonce' )) {
                _e('System is generating shortcode, please wait...','file-manager-advanced-shortcode');
               $title = isset($_POST['shortcode_title']) ? sanitize_text_field($_POST['shortcode_title']) : 'Shortcode title';
               $type = isset($_POST['login']) ? intval($_POST['login']) : '1';
               $user_roles = isset($_POST['fma_user_role']) ? array_map('sanitize_text_field', $_POST['fma_user_role']) : 'all';
               $path = isset($_POST['path']) ? str_replace('..', '', htmlentities(trim($_POST['path']))) : '/';
               $path_type = isset($_POST['path_type']) ? sanitize_text_field($_POST['path_type']) : 'inside';
               $url = isset($_POST['url']) ? sanitize_url($_POST['url']) : '';
               $hide = isset($_POST['hide']) ? sanitize_text_field($_POST['hide']) : '';
               $operations = isset($_POST['operations']) ? array_map('sanitize_text_field', $_POST['operations']) : 'all';

               $block_users = isset($_POST['block_users']) ? sanitize_text_field($_POST['block_users']) : '';
               $view = isset($_POST['view']) ? sanitize_text_field($_POST['view']) : 'list';
               $fma_theme = isset($_POST['fma_theme']) ? sanitize_text_field($_POST['fma_theme']) : 'light';
               $lang = isset($_POST['lang']) ? sanitize_text_field($_POST['lang']) : 'en';
               $dateformat = isset($_POST['dateformat']) ? sanitize_text_field($_POST['dateformat']) : 'M d, Y h:i A';
               $hide_path = isset($_POST['hide_path']) ? sanitize_text_field($_POST['hide_path']) : 'no';
               $enable_trash = isset($_POST['enable_trash']) ? sanitize_text_field($_POST['enable_trash']) : 'no';
               $height = isset($_POST['height']) ? sanitize_text_field($_POST['height']) : '';
               $width = isset($_POST['width']) ? sanitize_text_field($_POST['width']) : '';
               $read = isset($_POST['read']) ? sanitize_text_field($_POST['read']) : '';
               $write = isset($_POST['write']) ? sanitize_text_field($_POST['write']) : '';
               $display_ui_options = isset($_POST['display_ui_options']) ? array_map('sanitize_text_field', $_POST['display_ui_options']) : array();
               $upload_max_size = isset($_POST['upload_max_size']) ? sanitize_text_field($_POST['upload_max_size']) : '0';
               $upload_allow = isset($_POST['upload_allow']) ? sanitize_text_field($_POST['upload_allow']) : 'all';
               $status = isset($_POST['status']) ? intval($_POST['status']) : '1';
               /**
                * Shortcode Data
                */
               $shortcode_data = array();
               $shortcode_data['user_roles'] = $user_roles;
               $shortcode_data['path'] = $path;
               $shortcode_data['path_type'] = $path_type;
               $shortcode_data['url'] = $url;
               $shortcode_data['hide'] = $hide;
               $shortcode_data['operations'] = $operations;
               $shortcode_data['block_users'] = $block_users;
               $shortcode_data['view'] = $view;
               $shortcode_data['theme'] = $fma_theme;
               $shortcode_data['lang'] = $lang;
               $shortcode_data['dateformat'] = $dateformat;
               $shortcode_data['hide_path'] = $hide_path;
               $shortcode_data['enable_trash'] = $enable_trash;
               $shortcode_data['height'] = $height;
               $shortcode_data['width'] = $width;
               $shortcode_data['read'] = $read;
               $shortcode_data['write'] = $write;
               $shortcode_data['display_ui_options'] = $display_ui_options;
               $shortcode_data['upload_max_size'] = $upload_max_size;
               $shortcode_data['upload_allow'] = $upload_allow;
               /**
                * Inserting into DB
                */
               $insert = $wpdb->insert($table_name, 
               array(
                'shortcode_id' => self::getRandomShortcodeKey(rand(9,19)),
                'title' => $title,
                'shortcode' => 'local',
                'type' => $type,
                'status' => $status,
                'shortcode_data' => maybe_serialize($shortcode_data),
                'created_by' => get_current_user_id(),
                'updated_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
                'updated_at' => current_time( 'mysql' )
               )
            );

            if($insert) {
                self::redirect(admin_url('admin.php?page=file_manager_advanced_shortcode_generator&type=1&id='.$wpdb->insert_id));
            } else {
                self::redirect(admin_url('admin.php?page=fma_shortcode_create&type=2')) ;
            }
           }

         }
    /**
     * Get Shortcodes 
     */
    public static function getShortcodes() {
        global $wpdb;
        $table_name = $wpdb->prefix.'fma_addon_shortcodes';
        $shortcodes = $wpdb->get_results("Select * from ".$table_name." order by id DESC");
        if(count($shortcodes) > 0) {
            return $shortcodes;
        } else {
            return array();
        }
    }   
    /**
     * Get Shortcode by id
    */
    public static function getShortcode($id,$shortcodeId,$nonce) {
        if(wp_verify_nonce($nonce, $shortcodeId)) {
        global $wpdb;
          $tableName = $wpdb->prefix.'fma_addon_shortcodes';
          $id = intval($id);
          $shortcodeId = sanitize_text_field($shortcodeId);
          if(!empty($id)) {   
            $shortcodeData = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $tableName WHERE id=%d AND shortcode_id = %s", $id, $shortcodeId ) );
            if($shortcodeData) {
               return $shortcodeData;
            } else {
               wp_die( 'Unauthorized Access');
            }
          } else {
               wp_die( 'Unauthorized Access');
          }
        } else {
            wp_die( 'Unauthorized Access');
        }
    }
    /**
     * Modify shortcode
     **/
    public static function editGeneratedShortcode($id, $shortcodeId) {
        if(!$id && !$shortcodeId) {
            wp_die( 'Unauthorized Access');
        }
        global $wpdb;
        $table_name = $wpdb->prefix.'fma_addon_shortcodes';
        /**
         * Check if shortcode exists in database
         */
        $id = intval($id);
        $shortcodeId = sanitize_text_field($shortcodeId);
        if(!empty($id) && !empty($shortcodeId)) {   
          $shortcodeData = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id=%d AND shortcode_id = %s", $id, $shortcodeId ) );
          if(!$shortcodeData) {
             wp_die( 'Unauthorized Access');
          }
        } 
         //match nonce
         if(isset($_POST['submit']) && wp_verify_nonce( $_POST['fma_edit_nonce_field'], 'fma_edit_nonce' )) {
             _e('System is updating shortcode, please wait...','file-manager-advanced-shortcode');
            $title = isset($_POST['shortcode_title']) ? sanitize_text_field($_POST['shortcode_title']) : 'Shortcode title';
            $type = isset($_POST['login']) ? intval($_POST['login']) : '1';
            $user_roles = isset($_POST['fma_user_role']) ? array_map('sanitize_text_field', $_POST['fma_user_role']) : [];
            $path = isset($_POST['path']) ? str_replace('..', '', htmlentities(trim($_POST['path']))) : '/';
            $path_type = isset($_POST['path_type']) ? sanitize_text_field($_POST['path_type']) : 'inside';
            $url = isset($_POST['url']) ? sanitize_url($_POST['url']) : '';
            $hide = isset($_POST['hide']) ? sanitize_text_field($_POST['hide']) : '';
            $operations = isset($_POST['operations']) ? array_map('sanitize_text_field', $_POST['operations']) : [];
            $block_users = isset($_POST['block_users']) ? sanitize_text_field($_POST['block_users']) : '';
            $view = isset($_POST['view']) ? sanitize_text_field($_POST['view']) : 'list';
            $fma_theme = isset($_POST['fma_theme']) ? sanitize_text_field($_POST['fma_theme']) : 'light';
            $lang = isset($_POST['lang']) ? sanitize_text_field($_POST['lang']) : 'en';
            $dateformat = isset($_POST['dateformat']) ? sanitize_text_field($_POST['dateformat']) : 'M d, Y h:i A';
            $hide_path = isset($_POST['hide_path']) ? sanitize_text_field($_POST['hide_path']) : 'no';
            $enable_trash = isset($_POST['enable_trash']) ? sanitize_text_field($_POST['enable_trash']) : 'no';
            $height = isset($_POST['height']) ? sanitize_text_field($_POST['height']) : '';
            $width = isset($_POST['width']) ? sanitize_text_field($_POST['width']) : '';
            $read = isset($_POST['read']) ? sanitize_text_field($_POST['read']) : '';
            $write = isset($_POST['write']) ? sanitize_text_field($_POST['write']) : '';
            $display_ui_options = isset($_POST['display_ui_options']) ? array_map('sanitize_text_field', $_POST['display_ui_options']) : array();
            $upload_max_size = isset($_POST['upload_max_size']) ? sanitize_text_field($_POST['upload_max_size']) : '0';
            $upload_allow = isset($_POST['upload_allow']) ? sanitize_text_field($_POST['upload_allow']) : 'all';
            $status = isset($_POST['status']) ? intval($_POST['status']) : '1';
            /**
             * Shortcode Data
             */
            $shortcode_data = array();
            $shortcode_data['user_roles'] = $user_roles;
            $shortcode_data['path'] = $path;
            $shortcode_data['path_type'] = $path_type;
            $shortcode_data['url'] = $url;
            $shortcode_data['hide'] = $hide;
            $shortcode_data['operations'] = $operations;
            $shortcode_data['block_users'] = $block_users;
            $shortcode_data['view'] = $view;
            $shortcode_data['theme'] = $fma_theme;
            $shortcode_data['lang'] = $lang;
            $shortcode_data['dateformat'] = $dateformat;
            $shortcode_data['hide_path'] = $hide_path;
            $shortcode_data['enable_trash'] = $enable_trash;
            $shortcode_data['height'] = $height;
            $shortcode_data['width'] = $width;
            $shortcode_data['read'] = $read;
            $shortcode_data['write'] = $write;
            $shortcode_data['display_ui_options'] = $display_ui_options;
            $shortcode_data['upload_max_size'] = $upload_max_size;
            $shortcode_data['upload_allow'] = $upload_allow;
            /**
             * Updating into DB
             */
            $update = $wpdb->update($table_name, 
            array(
             'title' => $title,
             'type' => $type,
             'status' => $status,
             'shortcode_data' => maybe_serialize($shortcode_data),
             'updated_by' => get_current_user_id(),
             'updated_at' => current_time( 'mysql' )
            ),
            array('id' => $id, 'shortcode_id' => $shortcodeId)
         );

         if($update) {
             self::redirect(admin_url('admin.php?page=fma_shortcode_edit&id='.$id.'&shortcodeId='.$shortcodeId.'&nonce='.wp_create_nonce($shortcodeId).'&type=1')) ;
         } else {
             self::redirect(admin_url('admin.php?page=file_manager_advanced_shortcode_generator&type=2')) ;
         }
        }
    }
    /**
     * Delete Process
     */
    public static function deleteShortcode() {
     if(isset($_GET['action']) && 'delete' == sanitize_text_field($_GET['action']) && wp_verify_nonce(sanitize_text_field($_GET['nonce']), 'delete_shortcode_'.sanitize_text_field($_GET['shortcodeId']))) {
       $id = intval($_GET['id']);
       $shortcodeId = sanitize_text_field($_GET['shortcodeId']);
       if(!$id && !$shortcodeId) {
        wp_die( 'Unauthorized Access');
       }
        global $wpdb;
        $table_name = $wpdb->prefix.'fma_addon_shortcodes';
       if(!empty($id) && !empty($shortcodeId)) {   
            $shortcodeData = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id=%d AND shortcode_id = %s", $id, $shortcodeId ) );
            if(!$shortcodeData) {
            wp_die( 'Unauthorized Access');
            }
       }
       /**
        * Deletion Process 
       */ 
      $delete = $wpdb->delete($table_name, array('id' => $id, 'shortcode_id' => $shortcodeId));
      if($delete) {
        self::redirect(admin_url('admin.php?page=file_manager_advanced_shortcode_generator&type=1')) ;
      } else {
        self::redirect(admin_url('admin.php?page=file_manager_advanced_shortcode_generator&type=2')) ;
      }
            
    }
    }
    /**
     * Shortcode Settings
     */
    public static function settingsShortcode() {
        if(isset($_POST['submit']) && wp_verify_nonce( $_POST['fma_shortcode_nonce_field'], 'fma_shortcode_nonce' )) {
		    _e('Saving please wait...','file-manager-advanced');
		   $save = array();
           $save['shortcode_login_message'] = isset($_POST['shortcode_login_message']) ? sanitize_text_field($_POST['shortcode_login_message']) : '';
		   $save['shortcode_unauthorized_message'] = isset($_POST['shortcode_unauthorized_message']) ? sanitize_text_field($_POST['shortcode_unauthorized_message']) : '';
		   $save['shortcode_loading_message'] = isset($_POST['shortcode_loading_message']) ? sanitize_text_field($_POST['shortcode_loading_message']) : '';	   
		  $save = update_option('fma_shortcode_options',$save);
		  if($save) {
            self::redirect(admin_url('admin.php?page=file_manager_advanced_shortcode_settings&type=1')) ;
		  } else {
            self::redirect(admin_url('admin.php?page=file_manager_advanced_shortcode_settings&type=2')) ;
		  }
	   }
    }
    /**
	* Diplay Notices
    */
    public static function notice($type, $message) {
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
    public static function redirect($u) {
        $url = esc_url_raw($u);
        wp_register_script( 'fma-redirect-script', '');
        wp_enqueue_script( 'fma-redirect-script' );
        wp_add_inline_script(
        'fma-redirect-script',
        ' window.location.href="'.$url.'" ;'
    );
    }

         /**
          * generate random key
          */
          public static function getRandomShortcodeKey($n)
            {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';

                for ($i = 0; $i < $n; $i++) {
                    $index = rand(0, strlen($characters) - 1);
                    $randomString .= $characters[$index];
                }

                return $randomString;
            }

         /** 
          * Get WP Roles
          */
       public static function wpUserRoles() {
           global $wp_roles;
           return $wp_roles->roles; 
       }
    }
}
 
?>
