<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!empty($this->session->flashdata('flash_success'))) {
	echo my_card('success', $this->session->flashdata('flash_success'));
	unset($_SESSION['flash_success']);
}

if(!empty($this->session->flashdata('flash_danger'))) {
	echo my_card('danger', $this->session->flashdata('flash_danger'));
	unset($_SESSION['flash_danger']);
}

if(!empty($this->session->flashdata('flash_warning'))) {
	echo my_card('warning', $this->session->flashdata('flash_warning'));
	unset($_SESSION['flash_warning']);
}
?>