<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'Front/header')?>
<section class="section-header pb-7 pb-lg-10 bg-primary text-white">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10 text-center">
	    <div class="mb-4">
	      <label class="badge badge-sm badge-success text-uppercase px-3"><?=my_esc_html($rs->catalog)?></label>
	    </div>
	    <h1 class="display-3 mb-4 px-lg-5"><?=my_esc_html($rs->subject)?></h1>
	    <div class="post-meta">
	      <span class="font-weight-bold mr-3"><?=my_esc_html($rs->author)?></span>
		  <span class="post-date mr-3"><?=my_esc_html($rs->created_time)?></span>
	    </div>
	  </div>
    </div>
  </div>
  <div class="pattern bottom"></div>
</section>

<div class="section section-sm bg-white pt-5 text-black">
  <div class="container">
    <div class="row justify-content-center">
	  <div class="col-lg-12">
	    <nav aria-label="breadcrumb mb-5">
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="/"><?=my_caption('front_blog_breadcrumb_home')?></a></li>
			<li class="breadcrumb-item"><a href="<?=base_url('blog')?>"><?=my_caption('front_blog_breadcrumb_blog')?></a></li>
		  </ol>
		</nav>
	    <p class="mt-5 blog_body"><?=my_esc_html($rs->body)?></p>
	  </div>
	</div>
  </div>
</div>

<?php my_load_view($this->setting->theme, 'Front/social_part')?>
<?php my_load_view($this->setting->theme, 'Front/footer')?>