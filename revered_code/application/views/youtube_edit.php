<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('support_youtube_edit')?></h1>
	</div>
  </div>
</div>
<div class="row">
    <div class="col-lg-9 ml-4">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('Edit Page')?></h6>
        </div>
        <div class="card-body">


<?php echo form_open_multipart('youtube/update/'.$video->id); ?>


   <div class="row form-group mb-4">
		   <div class="col-lg-12">
			      <label for="youtube_id">YouTube ID</label>
			      <input type="text" name="youtube_id" value="<?php echo $video->youtube_id; ?>" class="form-control" required>
         </div>
	</div>

   <div class="row form-group mb-4">
		   <div class="col-lg-12">
			      <label for="thumbnail_name">Thumbnail Name</label>
			      <input type="text" name="thumbnail_name" value="<?php echo $video->thumbnail_name; ?>" class="form-control" required>
         </div>
	</div>

   <div class="row form-group mb-4">
		   <div class="col-lg-12">
			      <label for="thumbnail_file">Upload Thumbnail</label><br><br>
			      <!-- <input type="file" name="thumbnail_file" class="form-control"> -->
               <?php if ($video->thumbnail_file): ?>
      <img src="<?php echo base_url('upload/'.$video->thumbnail_file); ?>" height="150" width="200" /><br>
   <?php endif; ?>
   <input type="file" name="thumbnail_file" /><br>
         </div>
	</div>


   <div class="row form-group mb-4">
		   <div class="col-lg-12">
			      <label for="description">Description</label>

				  <textarea name="description" rows="5" class="form-control" required > <?php echo $video->description; ?></textarea>
               
			     
         </div>
	</div>
   
   <input type="submit" value="Update" class="btn btn-secondary" />
   <a href="<?php echo base_url('user/youtube'); ?>" class="btn btn-secondary">Cancel</a>

<?php echo form_close(); ?>

<?php my_load_view($this->setting->theme, 'footer')?>
