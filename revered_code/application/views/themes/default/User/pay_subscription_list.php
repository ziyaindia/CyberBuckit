<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
  my_load_view($this->setting->theme, 'header');
  $caption_subscription_action = my_caption('payment_subscription_cancel') . '||';
  $caption_subscription_action .= my_caption('payment_subscription_resume') . '||';
  $caption_subscription_action .= my_caption('payment_subscription_cancel_query_title') . '||';
  $caption_subscription_action .= my_caption('payment_subscription_cancel_query_body') . '||';
  $caption_subscription_action .= my_caption('payment_subscription_resume_query_title') . '||';
  $caption_subscription_action .= my_caption('payment_subscription_resume_query_body');
?>
<input type="hidden" id="caption_subscription_action" name="caption_subscription_action" value="<?=my_esc_html($caption_subscription_action)?>">
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('menu_sidebar_my_payment_subscription')?></h1>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('menu_sidebar_my_payment_subscription')?></h6>
        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="dataTable_subscription_user" class="table table-bordered">
			  <thead>
			    <tr>
				  <th width="12%"><?=my_caption('global_status')?></th>
				  <th width="48%"><?=my_caption('payment_item_name_label')?></th>
				  <th width="15%"><?=my_caption('payment_subscription_start_time')?></th>
				  <th width="15%"><?=my_caption('payment_subscription_end_time')?></th>
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
