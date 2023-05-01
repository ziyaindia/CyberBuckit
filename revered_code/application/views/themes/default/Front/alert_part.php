<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
  if(!empty($this->session->flashdata('flash_success'))) {
	  $alert_type = 'success';
	  $alert_info = $this->session->flashdata('flash_success');
	  unset($_SESSION['flash_success']);
  }

  if(!empty($this->session->flashdata('flash_danger'))) {
	  $alert_type = 'danger';
	  $alert_info = $this->session->flashdata('flash_danger');
	  unset($_SESSION['flash_danger']);
  }

  if(!empty($this->session->flashdata('flash_warning'))) {
	  $alert_type = 'warning';
	  $alert_info = $this->session->flashdata('flash_warning');
	  unset($_SESSION['flash_warning']);
  }
  
  if (!empty($alert_type)) {
	  $alert_html = '<div class="row"><div class="col-12 mb-3"><div class="alert alert-' . $alert_type . '">' . $alert_info . '</div></div></div>';
  }
  else {
	  $alert_html = '';
  }
  
  echo my_esc_html($alert_html);
?>
		  
