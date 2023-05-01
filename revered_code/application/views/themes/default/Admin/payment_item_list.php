<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('payment_item_title')?></h1>
	</div>
	<div class="col-lg-8 text-right">
	  <button type="button" class="btn btn-primary mr-2" onclick="window.location.href='<?=base_url('admin/payment_item_add/')?>'"><?=my_caption('payment_add_item_title')?></button>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="row">
	    <div class="col-lg-12">
		  <div class="card shadow mb-4">
		    <div class="card-header py-3">
			  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('payment_item_list')?></h6>
			</div>
			<div class="card-body">
			  <div class="table-responsive">
			    <table id="dataTable_payment_item" class="table table-bordered">
				  <thead>
				    <tr>
					  <th width="10%"><?=my_caption('global_status')?></th>
					  <th width="10%"><?=my_caption('payment_type_label')?></th>
					  <th width="40%"><?=my_caption('payment_item_name_label')?></th>
					  <th width="15%"><?=my_caption('payment_item_price_label')?></th>
					  <th width="15%"><?=my_caption('payment_recurring')?></th>
					  <th width="10%"><?=my_caption('global_actions')?></th>
					</tr>
				  </thead>
				</table>
		      </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>