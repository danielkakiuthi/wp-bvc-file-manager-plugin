<?php 

/**
 * Plugin Name: WP BVC - File Manager
 * Description: Implementation of a file manager
 * Version: 1.1
 * Author: BVC
 * Author URI: https://github.com/danielkakiuthi/wp-bvc-file-manager-plugin
 */


if( !defined( 'ABSPATH' ) ) exit; //exit if accessed directly


/*----------------------------------------------------------------------------------------------------------
  --------------------------------------- REQUIRE REQUIREMENTS ---------------------------------------------
  ---------------------------------------------------------------------------------------------------------- */

require_once plugin_dir_path(__FILE__) . 'require/file-repository-route.php';

if ( ! function_exists( 'is_plugin_active' ) ) {
  include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Check if ACF PRO is active
if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
  // Abort all bundling, ACF PRO plugin takes priority
  return;
}

// Check if another plugin or theme has bundled ACF
if ( defined( 'MY_ACF_PATH' ) ) {
  return;
}

// Define path and URL to the ACF plugin.
define( 'MY_ACF_PATH', __DIR__ . '/require/acf/' );
define( 'MY_ACF_URL', plugin_dir_url( __FILE__ ) . 'require/acf/' );

// Require the ACF plugin.
require_once( MY_ACF_PATH . 'acf.php' );



/*----------------------------------------------------------------------------------------------------------
  ----------------------------------------- CUSTOM FUNCTIONS -----------------------------------------------
  ---------------------------------------------------------------------------------------------------------- */

// Customize the URL setting to fix incorrect asset URLs.
function my_acf_settings_url( $url ) {
  return MY_ACF_URL;
}

function addNewFieldGroupsToACF() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
    'key' => 'group_65e124aec23e8',
    'title' => 'File Document',
    'fields' => array(
      array(
        'key' => 'field_65e124afb39ef',
        'label' => 'File',
        'name' => 'file',
        'aria-label' => '',
        'type' => 'file',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'return_format' => 'array',
        'library' => 'uploadedTo',
        'min_size' => '',
        'max_size' => 100,
        'mime_types' => '',
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'filecontainer',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
  ) );
}



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


function createCustomPostTypeFileContainer() {

	$labels = array(
		'name' => _x( 'FileContainers', 'Post Type General Name', 'textdomain' ),
		'singular_name' => _x( 'fileContainer', 'Post Type Singular Name', 'textdomain' ),
		'menu_name' => _x( 'FileContainers', 'Admin Menu text', 'textdomain' ),
		'name_admin_bar' => _x( 'fileContainer', 'Add New on Toolbar', 'textdomain' ),
		'archives' => __( 'fileContainer Archives', 'textdomain' ),
		'attributes' => __( 'fileContainer Attributes', 'textdomain' ),
		'parent_item_colon' => __( 'Parent fileContainer:', 'textdomain' ),
		'all_items' => __( 'All FileContainers', 'textdomain' ),
		'add_new_item' => __( 'Add New fileContainer', 'textdomain' ),
		'add_new' => __( 'Add New', 'textdomain' ),
		'new_item' => __( 'New fileContainer', 'textdomain' ),
		'edit_item' => __( 'Edit fileContainer', 'textdomain' ),
		'update_item' => __( 'Update fileContainer', 'textdomain' ),
		'view_item' => __( 'View fileContainer', 'textdomain' ),
		'view_items' => __( 'View FileContainers', 'textdomain' ),
		'search_items' => __( 'Search fileContainer', 'textdomain' ),
		'not_found' => __( 'Not found', 'textdomain' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'textdomain' ),
		'featured_image' => __( 'Featured Image', 'textdomain' ),
		'set_featured_image' => __( 'Set featured image', 'textdomain' ),
		'remove_featured_image' => __( 'Remove featured image', 'textdomain' ),
		'use_featured_image' => __( 'Use as featured image', 'textdomain' ),
		'insert_into_item' => __( 'Insert into fileContainer', 'textdomain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this fileContainer', 'textdomain' ),
		'items_list' => __( 'FileContainers list', 'textdomain' ),
		'items_list_navigation' => __( 'FileContainers list navigation', 'textdomain' ),
		'filter_items_list' => __( 'Filter FileContainers list', 'textdomain' ),
	);
	$args = array(
		'label' => __( 'fileContainer', 'textdomain' ),
		'description' => __( '', 'textdomain' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-media-document',
		'supports' => array('title', 'author', 'custom-fields'),
		'taxonomies' => array(),
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 80,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => false,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => true,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'filecontainer', $args );

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

/*----------------------------------------------------------------------------------------------------------
  -------------------------------------- List of Tasks to execute ------------------------------------------
  ---------------------------------------------------------------------------------------------------------- */

function onPluginActivationTasks() {

  // create password-protected file manager page (if it does not exist as a published page)
  createFileManagerPage();

  // copy page for File Manager from plugin to current Themes folder
  copyPageFileManagerIntoThemeFolder();
  
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

// register custom post types
add_action( 'init', 'createCustomPostTypeFileContainer', 1 );

// enqueue scripts (Eg. js, css)
add_action('wp_enqueue_scripts', 'filemanager_scripts');

// Fix Error of "Uncaught SyntaxError: Cannot use import statement outside a module"
add_filter('script_loader_tag', 'addTypeModuleAttributeToJavascript', 10, 2);

// upload feature using acf_form to add file to custom field
add_filter('acf/pre_save_post' , 'uploadFileContainer');

// add ACF URL being used to ACF settings
add_filter('acf/settings/url', 'my_acf_settings_url');

// ACF free is not installed, hide ACF admin menu item and ACF Updates menu
if ( ! file_exists( WP_PLUGIN_DIR . '/advanced-custom-fields/acf.php' ) ) {
  add_filter( 'acf/settings/show_admin', '__return_false' );
  add_filter( 'acf/settings/show_updates', '__return_false', 100 );
}

// add new Field Groups to ACF
add_action( 'acf/include_fields', 'addNewFieldGroupsToACF' );