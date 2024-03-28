<?php

if( !(is_user_logged_in() && get_user_meta( wp_get_current_user()->ID, 'canAccessFileManager', true)==1 ) ) {
  wp_redirect(esc_url(site_url('/')));
  exit;
}


get_header();

while (have_posts()) {

  the_post();
  ?>

  <div>
    <div id="file-manager-banner"></div>
    <h2 id="file-manager-title">File Manager</h2>
    <p><?php echo do_shortcode('
      [file_manager_advanced 
        login="yes"
        path="/file-manager-folder"
        hide="plugins"
        operations="all"
        view="grid"
        theme="light"
        lang ="en"
        upload_allow="all"
      ]'); ?></p>
    </div>

  

  </div>

<?php }
get_footer();
?>
