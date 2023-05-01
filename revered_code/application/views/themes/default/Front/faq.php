<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'Front/header')?>
      <section class="section-header pb-8 pb-lg-9 mb-1 mb-lg-1 bg-primary text-white">
	    <div class="container">
		  <div class="row justify-content-center">
		    <div class="col-12 col-md-8 text-center">
			  <h1 class="display-2 mb-3"><?=my_caption('front_faq_title')?></h1>
              <p class="lead"><?=my_caption('front_faq_slogan')?></p>
            </div>
          </div>
        </div>
        <div class="pattern bottom"></div>
      </section>
	  
	  <section class="section section-lg bg-white line-bottom-light">
	    <div class="container">
		  <div class="row justify-content-center">
		    <div class="col-12 col-lg-12">
			  <div class="accordion">
			    <?php
				  if (!empty($rs_catalog)) {
					  $i = 0;
					  foreach ($rs_catalog as $catalog) {
						  $catalog_name = $catalog->name;
						  echo '<div class="mb-6"><div class="mb-4 ml-2"><h4>' . $catalog_name . '</h4></div>';
						  if (!empty($rs_faq)) {
							  $faq_item_html = '';
							  foreach ($rs_faq as $faq) {
								  if ($catalog_name == $faq->catalog) {
									  $i++;
									  $faq_item_html = '<div class="card card-sm card-body border border-light rounded mb-3"><div data-target="#panel-' . $i . '" class="accordion-panel-header" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="panel-' . $i . '"><span class="h6 mb-0">';
									  $faq_item_html .= $faq->subject . '</span><span class="icon"><i class="fas fa-angle-down"></i></span></div><div class="collapse" id="panel-' . $i . '"><div class="pt-3"><p class="mb-0">';
									  $faq_item_html .= $faq->body;
									  $faq_item_html .= '</p></div></div></div>';
									  echo my_esc_html($faq_item_html);
								  }
							  }
						  }
						  echo '</div>';
					  }
				  }
				  else {
					  echo '<div class="mb-4 ml-2"><h4>' . my_caption('global_no_entries_found') . '</h4></div>';
				  }
				?>
			  </div>
            </div>
          </div>
        </div>
      </section>
<?php my_load_view($this->setting->theme, 'Front/social_part')?>
<?php my_load_view($this->setting->theme, 'Front/footer')?>