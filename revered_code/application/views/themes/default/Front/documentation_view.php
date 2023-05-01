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
	  <section class="section section-lg bg-white line-bottom-light">
	    <div class="container">
		  <div class="row justify-content-center">
		    <div class="col-3 col-lg-3">
			  <h4 class="mb-3"><?=my_caption('front_documentation_catalog_title')?></h4>
			  <ul>
			    <?php
				  foreach ($documentation_catalog as $catalog) {
					  echo '<li class="mb-2"><a href="' . base_url($this->home_url . 'documentation_list/' . $catalog->ids) . '">' . $catalog->name  . '</a></li>';
				  }
				?>
			  </ul>
            </div>
		    <div class="col-9 col-lg-9">
			  <nav aria-label="breadcrumb">
			    <ol class="breadcrumb breadcrumb-transparent">
				  <li class="breadcrumb-item"><a href="<?=base_url($this->home_url)?>"><?=my_caption('front_header_index')?></a></li>
				  <li class="breadcrumb-item"><a href="<?=base_url($this->home_url . 'documentation')?>"><?=my_caption('front_header_support_documentation')?></a></li>
				  <li class="breadcrumb-item"><?=my_caption('global_view')?></li>
				</ol>
			  </nav>
			  <?php
			    if (!empty($documentation_view)) {
			  ?>
			      <div class="row">
			        <div class="col-12">
				      <h4 class="mt-4"><?=my_esc_html($documentation_view->subject)?></h4>
				      <p class="mt-4"><?=my_esc_html($documentation_view->body)?></p>
				    </div>
			      </div>
			  <?php
			    }
				else {
					echo my_caption('support_documentation_no_entries');
				}
			  ?>
            </div>
          </div>
        </div>
      </section>
<?php my_load_view($this->setting->theme, 'Front/social_part')?>
<?php my_load_view($this->setting->theme, 'Front/footer')?>
