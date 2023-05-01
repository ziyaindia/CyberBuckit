<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
      <section class="section section-lg pb-5 bg-soft">
        <div class="container">
          <div class="row"> 
            <div class="col-12 text-center mb-5">
              <h2 class="mb-4"><?=my_caption('front_follow_us')?></h2>
			  <p class="lead mb-5"><?=my_caption('front_follow_us_slogan')?></p>
              <?php
			    if ($front_setting_array['social_facebook']) {
			  ?>
			  <a href="https://www.facebook.com/<?=my_esc_html($front_setting_array['social_facebook'])?>" target="_blank" class="icon icon-lg text-gray mr-5">
                <i class="fab fa-facebook"></i>
              </a>
              <?php
			    }
			    if ($front_setting_array['social_twitter']) {
			  ?>
			  <a href="https://twitter.com/<?=my_esc_html($front_setting_array['social_twitter'])?>" target="_blank" class="icon icon-lg text-gray mr-5">
                <i class="fab fa-twitter"></i>
              </a>
              <?php
			    }
			    if ($front_setting_array['social_linkedin']) {
			  ?>
			  <a href="https://www.linkedin.com/company/<?=my_esc_html($front_setting_array['social_linkedin'])?>" target="_blank" class="icon icon-lg text-gray mr-5">
                <i class="fab fa-linkedin"></i>
              </a>
              <?php
			    }
			    if ($front_setting_array['social_github']) {
			  ?>
			  <a href="https://github.com/<?=my_esc_html($front_setting_array['social_github'])?>" target="_blank" class="icon icon-lg text-gray mr-5">
                <i class="fab fa-github-alt"></i>
              </a>
              <?php
			    }
			  ?>
            </div>
          </div> 
        </div>    
      </section>
