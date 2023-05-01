<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'Front/header')?>
      <section class="section-header pb-8 pb-lg-9 mb-1 mb-lg-1 bg-primary text-white">
	    <div class="container">
		  <div class="row justify-content-center">
		    <div class="col-12 col-md-8 text-center">
			  <h1 class="display-2 mb-3"><?=my_caption('front_blog_title')?></h1>
              <p class="lead"></p>
            </div>
          </div>
        </div>
        <div class="pattern bottom"></div>
      </section>
	  <section class="section section-lg line-bottom-light mt-5">
	    <div class="container mt-n7 mt-lg-n12 z-2">
		  <div class="row">
		    <?php
			  foreach ($rs as $row) {
				  $photo_name = $row->cover_photo;
				  ($photo_name == '') ? $photo_name = 'default.jpg' : null;
			?>
		    <div class="col-12 col-md-4 mb-4">
			  <div class="card bg-white border-light shadow-soft p-4 rounded">
			    <a href="<?=base_url('blog/view/' . $row->slug)?>"><img src="<?php echo base_url($this->config->item('my_upload_directory') . 'blog/' . $photo_name) ;?>" class="card-img-top"></a>
				<div class="card-body p-0 pt-4">
				  <a href="<?=base_url('blog/view/' . $row->slug)?>" class="h3"><?=my_esc_html($row->subject)?></a>
				  <div class="d-flex align-items-center my-4">
					<h6 class="text-muted small mb-0"><?=my_esc_html($row->author)?></h6>
				  </div>
				</div>
			  </div>
			</div>
			<?php
			  }
			?>
		  </div>
		  <div class="d-flex justify-content-center w-100 mt-5">
		    <nav aria-label="page navigation example">
		      <?=my_esc_html($pagination_html)?>
			</nav>
		  </div>
		</div>
	  </section>
<?php my_load_view($this->setting->theme, 'Front/social_part')?>
<?php my_load_view($this->setting->theme, 'Front/footer')?>