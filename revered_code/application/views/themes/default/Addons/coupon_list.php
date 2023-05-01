<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('addons_coupon_list')?></h1>
	</div>
	<div class="col-lg-6 text-right">
	  <a href="<?=base_url('coupon/add')?>" class="btn btn-primary mr-4"><?=my_caption('addons_coupon_add')?></a>
    </div>
  </div>
  <div class="row">
    <?php
	  if (empty($rs)) {
	?>
	<div class="col-lg-4">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_coupon')?></h6>
        </div>
        <div class="card-body">
		  <?=my_caption('addons_coupon_empty')?>, <a href="<?=base_url('coupon/add')?>"><?=my_caption('addons_coupon_add')?></a>
		</div>
      </div>
	</div>
	<?php
	  }
	  else {
		  foreach ($rs as $row) {
			  ($row->discount_type == 'A') ? $discount = $row->discount_amount . '% OFF' : $discount = $row->discount_amount . ' OFF';
			  if ($row->enabled) {
				  if ((my_server_time() > $row->valid_till) || ($row->use_times_limit > 0 && ($row->use_times_count >= $row->use_times_limit))) {
					  $status = '<span class="badge badge-danger">' . my_caption('addons_coupon_status_invalid') . '</span>';
				  }
				  else {
					  $status = '<span class="badge badge-success">' . my_caption('addons_coupon_status_valid') . '</span>';
				  }
			  }
			  else {
				  $status = '<span class="badge badge-danger">' . my_caption('addons_coupon_status_invalid') . '</span>';
			  }
			  $limit = $row->use_times_limit;
			  ($limit == 0) ? $limit = 'unlimited' : null;
	?>
    <div class="col-lg-4">
	  <div class="card shadow mb-5 mr-3">
	    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_esc_html($row->name)?></h6>
		  <div class="dropdown no-arrow">
		    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
			  <div class="dropdown-header"><?=my_caption('global_actions')?> :</div>
			  <a class="dropdown-item" href="<?=base_url('coupon/edit/' . $row->ids)?>"><i class="fas fa-edit text-gray-500 mr-2"></i><?=my_caption('global_edit')?></a>
		      <a class="dropdown-item" href="<?=base_url('coupon/edit/' . $row->ids . '#coupon_log')?>"><i class="fas fa-bars text-gray-500 mr-2"></i><?=my_caption('addons_coupon_usage_log')?></a>
			  <a class="dropdown-item mt-2" href="javascript:void(0)" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?=my_js_escape(my_caption('global_not_revert'))?>', '<?=base_url('coupon/remove_action/' . $row->ids . '/')?>')"><i class="fa fa-trash text-gray-500 mr-2"></i><?=my_caption('global_delete')?></a>
            </div>
          </div>
        </div>
		<div class="card-body">
		  <div class="row">
		    <div class="col-lg-12 text-center">
			  <a href="<?=my_esc_html(base_url('coupon/edit/' . $row->ids))?>">
			    <i class="fas fa-ticket-alt fa-10x"></i>
			  </a>
			</div>
		  </div>
		  <div class="row mt-3">
			<div class="col-lg-8 offset-1 text-left">
			  <div class="text-primary mt-3"><?php echo my_caption('addons_coupon_status') . ' : ' . $status ?></div>
			  <div class="text-primary mt-3 mb-3"><?php echo my_caption('addons_coupon_code') . ' : ' . my_esc_html($row->code)?></div>
			  <div class="text-primary mt-3"><?php echo my_caption('addons_coupon_discount') . ' : ' . $discount ?></div>
			  <div class="text-primary mt-3"><?php echo my_caption('addons_coupon_hit_times') . ' : ' . $row->use_times_count . ' / ' . $limit;?></div>
			  <div class="text-primary mt-3 mb-3"><?php echo my_caption('addons_coupon_valid_period') . ' : ' . date($this->user_date_format, strtotime($row->valid_from)) . ' ~ ' . date($this->user_date_format, strtotime($row->valid_till))?></div>
			</div>
		  </div>
		  
		</div>
	  </div>
	</div>
	<?php
	      }
	  }
	?>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>