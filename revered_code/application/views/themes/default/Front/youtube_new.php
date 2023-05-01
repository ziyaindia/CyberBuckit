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
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('Youtube List')?></h6>
        </div>
        <div class="card-body">
		 
            <?php echo form_open_multipart('youtube/save_video'); ?>

            <div class="row form-group mb-4">
		       <div class="col-lg-12">
			      <label for="youtube_id"><span class="text-danger">*</span>YouTube ID</label>
			      <input type="text" name="youtube_id" class="form-control" required >
                  
               </div>
		   </div>

           <div class="row form-group mb-4">
		       <div class="col-lg-12">
			      <label for="thumbnail_name"><span class="text-danger">*</span>Thumbnail Name</label>
			      <input type="text" name="thumbnail_name" class="form-control" required >
                  
               </div>
		   </div>

           <div class="row form-group mb-4">
		       <div class="col-lg-12">
			      <label for="thumbnail_file"><span class="text-danger">*</span>Upload Thumbnail</label>
			      <input type="file" name="thumbnail_file" class="form-control" required>
                  
               </div>
		   </div>

           <div class="row form-group mb-4">
		       <div class="col-lg-12">
			      <label for="description"><span class="text-danger">*</span>Description</label>
			      <textarea name="description" rows="5" class="form-control" required></textarea>
                  
               </div>
		   </div>
      



            <button type="submit" class="btn btn-secondary" >Save</button>
            <a href="<?php echo base_url('user/youtube'); ?>"class="btn btn-secondary">Cancel</a>
            <?php echo form_close(); ?>
		</div>
      </div>
	</div>
  </div>

  



<?php my_load_view($this->setting->theme, 'footer')?>


<!-- <label for="youtube_id" class="form-control">YouTube ID</label>
            <input type="text" name="youtube_id" required>
            <br>
            <label for="thumbnail_name">Thumbnail Name</label>
            <input type="text" name="thumbnail_name" required>
            <br>
            <label for="thumbnail_file">Upload Thumbnail</label>
            <input type="file" name="thumbnail_file" >
            <br>
            <label for="description">Description</label>
            <textarea name="description" rows="5" required></textarea>
            <br> -->