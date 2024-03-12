class FileManager {

  // My Constructors
  constructor() {
    if(document.querySelector("#my-file-containers")) {
      this.events();
    }
  }


  // My Events
  events() {
    console.log("Events was called");
    jQuery(".delete-file").on("click", this.deleteFileContainer.bind(this));
  }


  // My Methods
  createFileContainer(e) {
    var newFileContainer = {
      'title': 'REPLACE_TITLE_LATER',
      'content': 'REPLACE_CONTENT_LATER',
    }

    jQuery.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', fileManagerData.nonce);
      },
      url: `${fileManagerData.root_url}/wp-json/file-repository-api/v1/manageFileContainers`,
      type: 'POST',
      data: newFileContainer,
      success: (response) => {
        jQuery('#inputNewFile').val('');
        jQuery('<li>Imagine real data here</li>').prependTo('#my-file-containers').hide().slideDown();

        console.log('Congrats');
        console.log(response);
      },
      error: (response) => {
        console.log('Sorry');
        console.log(response);
      }
    });
  }


  deleteFileContainer(e) {
    console.log("deleteFileContainer was called");
    var fileContainerBoxToDelete = jQuery(e.target).closest("tr");

    jQuery.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', fileManagerData.nonce);
      },
      url: `${fileManagerData.root_url}/wp-json/file-repository-api/v1/manageFileContainers`,
      type: 'DELETE',
      data: {'postId': fileContainerBoxToDelete.data('container')},
      success: (response) => {
        fileContainerBoxToDelete.slideUp();
        console.log('Congrats');
        console.log(response);
      },
      error: (response) => {
        console.log('Sorry');
        console.log(response);
      }
    });
  }


}

export default FileManager;