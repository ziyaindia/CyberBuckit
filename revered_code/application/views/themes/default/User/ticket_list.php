<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
<div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('support_ticket_my_title')?></h1>
	</div>
    <div class="col-lg-8 text-right">
	  <button type="button" class="btn btn-primary mr-2" onclick="window.location.href='<?=base_url('user/ticket_new')?>'"><?=my_caption('support_ticket_new')?></button>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('support_ticket_my_title')?></h6>
        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="dataTable_ticket_list" class="table table-bordered">
			  <thead>
			    <tr>
				  <th width="8%"><?=my_caption('global_status')?></th>
				  <th width="15%"><?=my_caption('support_ticket_created_time')?></th>
				  <th width="15%"><?=my_caption('support_ticket_updated_time')?></th>
				  <th width="52%"><?=my_caption('support_ticket_subject')?></th>
				   <th width="10%"><?=my_caption('global_view')?></th>
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