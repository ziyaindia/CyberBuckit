<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'Front/header')?>
      <section class="section-header bg-primary text-white">
	    <div class="container">
	      <div class="row justify-content-center">
		    <div class="col-12 col-md-8 text-center">
	 	      <h1 class="display-2 mb-3"><?=my_caption('front_documentation_title')?></h1>
              <p class="lead"><?=my_caption('front_documentation_slogan')?></p>
            </div>
          </div>
        </div>
      </section>
	  
	  
      <section class="section section-lg pt-6 line-bottom-light">
	    <div class="container">
	      <div class="row mt-3">
		    <?php
			  if (!empty($documentation_catalog)) {
				  foreach ($documentation_catalog as $catalog) {
			?>
		    <div class="col-12 col-md-6">
		      <div class="card shadow-soft border-light mb-5">
			    <div class="card-body" style="width:100%">
			      <div class="d-flex flex-column flex-lg-row p-3">
				    <div class="mb-3 mb-lg-0">
				      <div class="icon icon-primary"><i class="far fa-life-ring"></i></div>
                    </div>
                    <div class="pl-lg-4">
                      <h5 class="mb-3"><a href="<?php echo base_url($this->home_url . 'documentation_list/') . $catalog->ids; ?>"><?=my_esc_html($catalog->name)?></a></h5>
                      <p><?=my_esc_html($catalog->description)?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
			<?php
			      }
			  }
			  else {
				  echo '<div class="mb-4 ml-2"><h4>' . my_caption('global_no_entries_found') . '</h4></div>';
			  }
			?>
		  </div>
        </div>
      </section>
<?php my_load_view($this->setting->theme, 'Front/social_part')?>
<?php my_load_view($this->setting->theme, 'Front/footer')?>