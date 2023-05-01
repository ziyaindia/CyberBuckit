<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html lang="en">
  <head>
    <title><?=my_esc_html($front_setting_array['html_title'])?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="keywords" content="<?=my_esc_html($front_setting_array['html_keyword'])?>">
	<meta name="description" content="<?=my_esc_html($front_setting_array['html_description'])?>">
	<meta name="author" content="<?=my_esc_html($front_setting_array['html_author'])?>">
	<link rel="shortcut icon" href="<?=base_url('upload/favicon.ico')?>" type="image/x-icon">
	<link type="text/css" href="<?=base_url('assets/themes/default/front/vendor/@fortawesome/fontawesome-free/css/all.min.css')?>" rel="stylesheet">
	<link type="text/css" href="<?=base_url('assets/themes/default/front/vendor/prismjs/themes/prism.css')?>" rel="stylesheet">
	<link type="text/css" href="<?=base_url('assets/themes/default/front/css/front.css')?>" rel="stylesheet">
  </head>
  <body>
    <main>
	  <section class="vh-100 d-flex align-items-center justify-content-center">
	    <div class="container">
		  <div class="row">
		    <div class="col-12 text-center d-flex align-items-center justify-content-center">
			  <div>
			    <a href="<?=base_url()?>"><img class="img-fluid w-75" src="<?=base_url('assets/themes/default/front/img/404.svg')?>" alt="404 not found"></a>
				<h1 class="mt-5"><?=my_caption('my404_page_not_found')?></span></h1>
				<p class="lead my-4"><?=my_caption('my404_general_notice')?></p>
				<a class="btn btn-primary animate-hover" href="<?=base_url()?>">
				  <i class="fas fa-chevron-left mr-3 pl-2 animate-left-3"></i><?=my_caption('my404_page_go_back_home')?>
				</a>
			  </div>
			</div>
		  </div>
		</div>
	  </section>
	</main>
  </body>
</html>