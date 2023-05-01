<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-9 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('support_faq')?></h1>
	</div>
	<div class="col-lg-3 text-right">
	  <a href="<?=base_url('admin/faq_new')?>" class="btn btn-primary mr-2"><?=my_caption('support_faq_new')?></a>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('support_faq')?></h6>
        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="dataTable_faq" class="table table-bordered">
			  <thead>
			    <tr>
				  <th width="20%"><?=my_caption('support_faq_catalog')?></th>
				  <th width="70%"><?=my_caption('support_faq_subject')?></th>
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
<?php my_load_view($this->setting->theme, 'footer')?>