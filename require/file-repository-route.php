<?php 

add_action('rest_api_init', 'fileContainerRoutes');


function fileContainerRoutes() {
  // register_rest_route('file-repository-api/v1', 'manageFileContainers', array(
  //   'methods' => 'POST',
  //   'callback' => 'createFileContainer'
  // ));

  register_rest_route('file-repository-api/v1', 'manageFileContainers', array(
    'methods' => 'DELETE',
    'callback' => 'deleteFileContainer'
  ));
}


// function createFileContainer($data) {

//   if(is_user_logged_in()) {
//     if($_FILES['inputNewFile']!=NULL) {
//       return wp_insert_post(array(
//         'post_type'=> 'fileContainer',
//         'post_status' => 'publish',
//         'post_title' => 'REPLACE_TITLE_LATER',
//         'meta_input' => array(
//           'file' => $_FILES['inputNewFile'],
//           '_file' => 'group_65e124aec23e8'
//         )
//       ));
//     }
//     else {
//       return "Please attach a file before submit.";
//     }
//   }
//   else {
//     die('Only logged in users can create a new file.');
//   }
// }


function deleteFileContainer($data) {
  $postId = sanitize_text_field($data['postId']);
  
  if (wp_delete_post($postId, true)) {
    return 'Congrats fileContainer deleted.';
  }
  else {
    return "Something went wrong. Post wasn't deleted";
  }
  // if(get_current_user_id()==get_post_field('post_author', $postId) && get_post_type($postId)=='fileContainer') {
  //   wp_delete_post($postId, true);
  //   return 'Congrats fileContainer deleted.';
  // }
  // else {
  //   die("user Id: " . ${get_post_type($postId)} . " You do not have permission to delete that fileContainer.");
  // }
}


// Deal with images uploaded from the front-end while allowing ACF to do it’s thing
function uploadFileContainer($post_id) {

  if ( !function_exists('wp_handle_upload') ) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
  }
  
  // Move file to media library
  $movefile = wp_handle_upload( $_FILES['inputNewFile'], array('test_form' => false) );
  
  // If move was successful, insert WordPress attachment
  if ( $movefile && !isset($movefile['error']) ) {
    $wp_upload_dir = wp_upload_dir();
    $attachment = array(
      'guid' => $wp_upload_dir['url'] . '/' . basename($movefile['file']),
      'post_mime_type' => $movefile['type'],
      'post_title' => preg_replace( `/\.[^.]+$/`, '', basename($movefile['file']) ),
      'post_content' => '',
      'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment($attachment, $movefile['file']);
    
    // Assign the file as the featured image
    set_post_thumbnail($post_id, $attach_id);
    update_field('my_image_upload', $attach_id, $post_id);
  }
  
  return $post_id;
}
  
