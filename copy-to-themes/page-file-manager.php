<?php

if( !is_user_logged_in() ) {
 wp_redirect(esc_url(site_url('/')));
 exit;
}

acf_form_head();
get_header();

while (have_posts()) {

  the_post();
  ?>

  <div >
    <br><br><br><br><br><br><br>
    <h2>File Manager</h2>
    
    <!-- Save new file -->
    <!-- <form method="POST" enctype="multipart/form-data">
      <div class="create-file">
        <label for="inputNewFile" class="headline headline--medium">Choose a file:</label>
        <input type="file" id="inputNewFile" name="inputNewFile" multiple="false" accept="image/png,image/jpeg,.pdf,.doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
        <button class="submit-file">Submit</button>
      </div>
    </form> -->
    <div id="content">
      <?php
      acf_form(array(
        'post_id'       => 'new_post',
        'post_title'    => false,
        'post_content'  => false,
        'uploader' => 'basic',
        'submit_value' => __("Upload", 'acf'),
        'updated_message' => __("Post updated", 'acf'),
        'label_placement' => 'left',
        'new_post'      => array(
          'post_title'  => 'POST_CREATED_BY_ACF_FORM',
          'post_type'     => 'filecontainer',
          'post_status'   => 'publish'
        )
      ));
      ?>
    </div>

    <!-- Display files from current user -->
    <h4>My files list:</h4>
    <ul class="min-list link-list" id="my-file-containers">
      <?php 
        $queryFileContainers = new WP_Query(array(
          'post_type'=> 'fileContainer',
          'posts_per_page' => -1,
          'author' => get_current_user_id()
        ));
        while($queryFileContainers->have_posts()) {
          $queryFileContainers->the_post(); 
          $publishedDate = get_post_datetime( get_sub_field('document')['id'], 'modified', 'gmt' );
          ?>
          <li data-container="<?php the_ID(); ?>">
            <h4 readonly class="file-title-field" type="text"><?php echo str_replace('Private: ', '', esc_attr(get_the_title())); ?></h4>
            <span>Upload Date: <?php echo $publishedDate->format( 'Y-m-d H:i:s' ) ?></span>
            </br>
            <?php   
            $file = get_field('file');
            if($file): ?>
              <span>Download Link: </span>
              <span class="download-file">
                <i class="fa fa-pencil" aria-hidden=true></i>
                <a href="<?php echo $file['url'] ?>" download><?php echo $file['filename']; ?></a>
              </span>
              <?php endif; ?>
            <span class="delete-file">
              <i class="fa fa-trash-o" aria-hidden=true></i>Delete
            </span>
          </li>
        <?php }
      ?>
    </ul>

  </div>

<?php }
get_footer();
?>
