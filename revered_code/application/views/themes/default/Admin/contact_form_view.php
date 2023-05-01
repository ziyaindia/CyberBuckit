<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('support_contact_form_view')?></h1>

  <div class="row">
    <div class="col-lg-8">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('support_contact_form_view')?></h6>
        </div>
        <div class="card-body">
		  <div class="row">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('global_catalog')?> :</span><br>
			  <?=my_esc_html($rs->catalog)?>
			</div>
			<div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('global_time')?> :</span><br>
			  <?=my_conversion_from_server_to_local_time($rs->created_time, $this->user_timezone, $this->user_dtformat)?>
			   ( From IP: <?=my_esc_html($rs->ip_address)?> )
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('global_email_address')?> :</span><br>
			  <?=my_esc_html($rs->email_address)?>
			</div>
			<div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('global_phone_number')?> :</span><br>
			  <?=my_esc_html($rs->phone)?>
			</div>
		  </div>
		  <hr class="dotted mb-3">
		  <div class="row mb-5">
		    <div class="col-lg-12">
			  <span class="font-weight-bold"><?=my_caption('global_message')?> :</span><br>
			  <?=my_esc_html($rs->message)?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <button type="button" class="btn btn-primary" onclick="window.history.back()"><?=my_caption('global_go_back')?></button>
			</div>
		  </div>
		</div>
      </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>