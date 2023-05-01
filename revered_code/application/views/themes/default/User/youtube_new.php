<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('support_youtube_new')?></h1>
	</div>
  </div>
</div>
<div class="row">
  <div class="col-lg-9 ml-4">
	   <div class="card shadow mb-4">
	      <div class="card-header py-3">
		         <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('Add Youtube Video')?></h6>
        </div>
        <div class="card-body">
  <?php echo form_open_multipart('youtube/save_video', 'id="myForm" onsubmit="return validateForm()"'); ?>

  <div class="row form-group mb-4">
    <div class="col-lg-12">
      <label for="youtube_id"><span class="text-danger">*</span>YouTube ID</label>
      <input type="text" name="youtube_id" class="form-control" >
      <small id="youtube_id_error" class="text-danger"></small>
    </div>
  </div>

  <div class="row form-group mb-4">
    <div class="col-lg-12">
      <label for="thumbnail_name"><span class="text-danger">*</span>Thumbnail Name</label>
      <input type="text" name="thumbnail_name" class="form-control">
      <small id="thumbnail_name_error" class="text-danger"></small>
    </div>
  </div>

  <div class="row form-group mb-4">
    <div class="col-lg-12">
      <label for="thumbnail_file"><span class="text-danger">*</span>Upload Thumbnail</label>
      <input type="file" name="thumbnail_file" id="file-input" class="form-control">
      <small class="form-text text-muted">Accepted file types: JPG,PNG,JPEG. Maximum file size: 5MB. Recommended thumbnail size: 200x200 pixels.</small>
      <small id="thumbnail_file_error" class="text-danger"></small>
    </div>
  </div>

  <div class="row form-group mb-4">
    <div class="col-lg-12">
      <label for="description"><span class="text-danger">*</span>Description</label>
      <textarea name="description" rows="5" class="form-control"></textarea>
      <small id="description_error" class="text-danger"></small>
    </div>
  </div>

  
   <input type="submit" value="Save" class="btn btn-secondary" />
   <a href="<?php echo base_url('user/youtube'); ?>" class="btn btn-secondary">Cancel</a>
  <?php echo form_close(); ?>
</div>

         </div>
	    </div>
  </div>
<!-- <form action="youtube/save_video" method="post" enctype="multipart/form-data"> -->

<script>

  function validateForm() {
  var youtube_id = document.forms["myForm"]["youtube_id"].value;
  var thumbnail_name = document.forms["myForm"]["thumbnail_name"].value;
  var thumbnail_file = document.forms["myForm"]["thumbnail_file"].value;
  var description = document.forms["myForm"]["description"].value;
  var isValid = true;

  if (youtube_id.trim() == "") {
    document.getElementById("youtube_id_error").innerHTML = "The YouTube ID field is required.";
    isValid = false;
  } else {
    document.getElementById("youtube_id_error").innerHTML = "";
  }

  if (thumbnail_name.trim()  == "") {
    document.getElementById("thumbnail_name_error").innerHTML = "The Thumbnail Name field is required.";
    isValid = false;
  } else {
    document.getElementById("thumbnail_name_error").innerHTML = "";
  }

  if (thumbnail_file == "") {
  
    document.getElementById("thumbnail_file_error").innerHTML = "The Upload Thumbnail field is required.";
    isValid = false;
    
  } else {
    document.getElementById("thumbnail_file_error").innerHTML = "";
  }

  if (description.trim()  == "") {
    document.getElementById("description_error").innerHTML = "The Description field is required.";
    isValid = false;
  } else {
    document.getElementById("description_error").innerHTML = "";
  }

  return isValid;
}

</script>

<script>
// Add event listener for file input
document.getElementById('file-input').addEventListener('change', function() {
  // Get the selected file
  var file = this.files[0];
  // Define the allowed file types and size
  var allowedTypes = ['image/jpeg', 'image/png','image/jpg'];
  var maxSize = 5 * 1024 * 1024; // 2 MB
  // Check if the file type and size are valid
  if (!allowedTypes.includes(file.type)) {
    alert('Please select a JPG,JPEG or PNG image file.');
    this.value = ''; // Clear the selected file
  } else if (file.size > maxSize) {
    alert('Please select a file smaller than 5 MB.');
    this.value = ''; // Clear the selected file
  }
});
</script>

<?php my_load_view($this->setting->theme, 'footer')?>
