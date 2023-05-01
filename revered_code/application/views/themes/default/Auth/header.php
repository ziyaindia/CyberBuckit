<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$method_name = $this->router->fetch_method();
if ($method_name == 'signup' || $method_name == 'signup_action') {
	$title = my_caption('signup_html_title') . ' - ';
	$description = my_caption('signup_html_description');
}
elseif ($method_name == 'signin' || $method_name == 'signin_action') {
	$title = my_caption('signin_html_title') . ' - ';
	$description = my_caption('signin_html_description');
}
elseif ($method_name == 'forget' || $method_name == 'forget_action') {
	$title = my_caption('forget_html_title') . ' - ';
	$description = my_caption('forget_html_description');
}
elseif ($method_name == 'terms_conditions') {
	$title = my_caption('tc_html_title') . ' - ';
	$description = my_caption('tc_html_description');
}
else {
	$title = '';
	$description = '';
}

?>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?=$description?>">
  <meta name="author" content="<?=my_caption('global_html_author')?>">
  <title><?php echo $title . $this->setting->sys_name; ?></title>
  <link rel="shortcut icon" href="<?=base_url()?>upload/favicon.ico" type="image/x-icon">
  <link href="<?=base_url()?>assets/themes/default/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="<?=base_url()?>assets/themes/default/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/themes/default/css/custom.css" rel="stylesheet">
  <?php if (!empty($this->setting->dashboard_custom_css)) { ?>
    <link type="text/css" href="<?=$this->setting->dashboard_custom_css?>" rel="stylesheet">
  <?php } ?>
</head>