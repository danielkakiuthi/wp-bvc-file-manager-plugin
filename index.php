<?php 

/**
 * Plugin Name: WP BVC - File Manager
 * Description: Implementation of a file manager
 * Version: 2.0
 * Author: BVC
 * Author URI: https://github.com/danielkakiuthi/wp-bvc-file-manager-plugin
 */


if( !defined( 'ABSPATH' ) ) exit; //exit if accessed directly


/*----------------------------------------------------------------------------------------------------------
  --------------------------------------- REQUIRE REQUIREMENTS ---------------------------------------------
  ---------------------------------------------------------------------------------------------------------- */

//----------------------------------------- Custom Requirements ---------------------------------------



//--------------------------------- File Manager Advanced Requirement ---------------------------------
if ( ! function_exists( 'is_plugin_active' ) ) {
  include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Check if File Manager Advanced is active
if ( is_plugin_active( 'file-manager-advanced/file_manager_advanced.php' ) ) {
  // Abort all bundling, File Manager Advanced is installed
  return;
}

// Check if another plugin or theme has bundled FMA
if ( defined( 'MY_FMA_PATH' ) ) {
  return;
}

// Define path and URL to the FMA plugin.
define( 'MY_FMA_PATH', __DIR__ . '/require/file-manager-advanced/' );
define( 'MY_FMA_URL', plugin_dir_url( __FILE__ ) . 'require/file-manager-advanced/' );

// Require the ACF plugin.
require_once( MY_FMA_PATH . 'file_manager_advanced.php' );


//----------------------------- File Manager Advanced Shortcode Requirement ----------------------------

// Check if File Manager Advanced Shortcode is active
if ( is_plugin_active( 'file-manager-advanced-shortcode/file-manager-advanced-shortcode.php' ) ) {
  // Abort all bundling, File Manager Advanced Shortcode is installed
  return;
}

// Check if another plugin or theme has bundled FMAS
if ( defined( 'MY_FMAS_PATH' ) ) {
  return;
}

// Define path and URL to the FMAS plugin.
define( 'MY_FMAS_PATH', __DIR__ . '/require/file-manager-advanced-shortcode/' );
define( 'MY_FMAS_URL', plugin_dir_url( __FILE__ ) . 'require/file-manager-advanced-shortcode/' );

// Require the ACF plugin.
require_once( MY_FMAS_PATH . 'file-manager-advanced-shortcode.php' );



/*----------------------------------------------------------------------------------------------------------
  ----------------------------------------- CUSTOM FUNCTIONS -----------------------------------------------
  ---------------------------------------------------------------------------------------------------------- */






function createFileManagerPage() {
  $slug = 'file-manager';

  $queryFileManagerPagesPublished = new WP_Query(array(
    'pagename' => $slug,
    'post_type' => 'page',
    'post_status' => 'publish'
  ));
  $queryFileManagerPagesDrafted = new WP_Query(array(
    'pagename' => $slug,
    'post_type' => 'page',
    'post_status' => 'draft'
  ));

  //CASE File Manager Page already exists and is published: do nothing
  if($queryFileManagerPagesPublished->have_posts()) {
    return;
  }
  //CASE File Manager Page already exists but is only drafted: publish the draft
  else if($queryFileManagerPagesDrafted->have_posts()) {
    while($queryFileManagerPagesDrafted->have_posts()) {
      $queryFileManagerPagesDrafted->the_post();
      $updatePost = array(
        'ID' => get_the_ID(),
        'post_status' => 'publish',
        'post_content' => ''
      );
      wp_update_post($updatePost);
    }
  }
  //CASE File Manager Page doesn't exist: create new File Manager Page published
  else {
    $newPost = array(
      'post_name' => $slug,
      'post_type' => 'page',
      'post_status' => 'publish',
      'post_title' => 'File Manager',
      'post_content' => ''
    );
    $insertPage = wp_insert_post($newPost);
  }
}


function unpublishFileManagerPage() {
  $slug = 'file-manager';

  $queryFileManagerPagesPublished = new WP_Query(array(
    'pagename' => $slug,
    'post_type' => 'page',
    'post_status' => 'publish'
  ));
  //CASE File Manager Page is published: delete page
  if($queryFileManagerPagesPublished->have_posts()) {
    while($queryFileManagerPagesPublished->have_posts()) {
      $queryFileManagerPagesPublished->the_post();
      wp_delete_post(get_the_ID(), true);
    }
  }
}


function copyPageFileManagerIntoThemeFolder() {

  $filename = 'page-file-manager.php';
  $plugin_dir = plugin_dir_path( __FILE__ ) . 'copy-to-themes/' . $filename;
  $theme_dir = get_stylesheet_directory() . '/' . $filename;

  if (!copy($plugin_dir, $theme_dir)) {
      echo "failed to copy $plugin_dir to $theme_dir...\n";
  }
}


function deletePageFileManagerFromThemeFolder() {
  
  $filename = 'page-file-manager.php';
  $theme_dir = get_stylesheet_directory() . '/' . $filename;
  if (unlink($theme_dir)) {
    echo 'The file ' . $theme_dir . ' was deleted successfully!';
  } else {
    echo 'There was a error deleting the file ' . $theme_dir;
  }
}


function createFileManagerFolderIfDoesNotExist() {
  if (!file_exists('../wp-content/file-manager-folder')) {
    mkdir('../wp-content/file-manager-folder', 0777, true);
}
}




function filemanager_scripts() {
  wp_enqueue_script('main-filemanager-js', plugins_url('/src/index.js', __FILE__), array('jquery'), '1.0', true);
  wp_enqueue_style('main-filemanager-css', plugins_url('/css/style.css', __FILE__));
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

  wp_localize_script('main-filemanager-js', 'fileManagerData', array(
    'root_url' => get_site_url(),
    'nonce' => wp_create_nonce('wp_rest')
  ));
}


function addTypeModuleAttributeToJavascript($tag, $handle) {
  if ( $handle !== 'main-filemanager-js' ) {
     return $tag;
  }
  // needed in case you already have a type='javascript' attribute
  $new_tag = str_replace("type='text/javascript'", '', $tag);
  // adding type='module'
  $new_tag = str_replace(" src", " type='module' defer src", $tag);
  return $new_tag;
}


function hideFileManagerAdvancedMenu() {
  remove_menu_page( 'file_manager_advanced_ui' );
}

/*----------------------------------------------------------------------------------------------------------
  -------------------------------------- List of Tasks to execute ------------------------------------------
  ---------------------------------------------------------------------------------------------------------- */

function onPluginActivationTasks() {

  // create password-protected file manager page (if it does not exist as a published page)
  createFileManagerPage();

  // copy page for File Manager from plugin to current Themes folder
  copyPageFileManagerIntoThemeFolder();

  // create file-manager-folder if it does not exist
  createFileManagerFolderIfDoesNotExist();
  
}


function onPluginDeactivationTasks() {

  // unpublish file manager page (change to draft)
  unpublishFileManagerPage();

  // delete copied page for File Manager from current Themes folder
  deletePageFileManagerFromThemeFolder();
}



/*----------------------------------------------------------------------------------------------------------
  -------------------------------------------- HOOK CALLS --------------------------------------------------
  ---------------------------------------------------------------------------------------------------------- */

// on plugin activation
register_activation_hook(__FILE__, 'onPluginActivationTasks');

// on plugin deactivation
register_deactivation_hook( __FILE__, 'onPluginDeactivationTasks' );



// enqueue scripts (Eg. js, css)
add_action('wp_enqueue_scripts', 'filemanager_scripts');

// Fix Error of "Uncaught SyntaxError: Cannot use import statement outside a module"
add_filter('script_loader_tag', 'addTypeModuleAttributeToJavascript', 10, 2);

// hide 'File Manager' Menu item in wp-admin
add_action( 'admin_menu', 'hideFileManagerAdvancedMenu' );
