<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-9 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('support_contact_form_title')?></h1>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('support_contact_form_title')?></h6>
        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="dataTable_contactform" class="table table-bordered">
			  <thead>
			    <tr>
				  <th width="10%"><?=my_caption('global_status')?></th>
				  <th width="15%"><?=my_caption('global_time')?></th>
				  <th width="15%"><?=my_caption('global_catalog')?></th>
				  <th width="15%"><?=my_caption('global_name')?></th>
				  <th width="25%"><?=my_caption('global_email_address')?></th>
				  <th width="20%"><?=my_caption('global_actions')?></th>
			    </tr>
			  </thead>
			</table>
          </div>
		</div>
      </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>
