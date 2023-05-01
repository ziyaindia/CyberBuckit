<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>

<div class="container-fluid">
  <?php
  if (!my_check_permission('User Management')) {  //show user dashboard
	  my_load_view($this->setting->theme, 'Generic/dashboard_user');
  }
  else {
	  my_load_view($this->setting->theme, 'Generic/dashboard_admin');
  }
  ?>
</div>
<?php my_load_view($this->setting->theme, 'footer');?>