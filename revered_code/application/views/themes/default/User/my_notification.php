<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
<div class="row">
    <div class="col-lg-9 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('notification_my_title')?></h1>
	</div>
  </div>

  <div class="row">
    <div class="col-lg-12">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('notification_my_title')?></h6>
        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="dataTable_notification" class="table table-bordered">
			  <thead>
			    <tr>
				  <th width="8%"></th>
				  <th width="12%"><?=my_caption('global_time')?></th>
				  <th width="58%"><?=my_caption('notification_subject')?></th>
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
