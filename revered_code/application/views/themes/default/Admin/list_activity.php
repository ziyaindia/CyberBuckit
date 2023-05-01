<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
<div class="row">
    <div class="col-lg-9 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('al_users_title')?></h1>
	</div>
    <div class="col-lg-3 text-right">
	  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=my_caption('al_delete_log')?></button>
      <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
	    <a class="dropdown-item" href="javascript:void(0)" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?php echo my_caption('al_delete_log') . ' ' . my_caption('al_delete_log_A')?>', '<?=base_url('admin/users_activity_log_delete/A')?>')"><?=my_caption('al_delete_log_A')?></a>
		<a class="dropdown-item" href="javascript:void(0)" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?php echo my_caption('al_delete_log') . ' ' . my_caption('al_delete_log_B')?>', '<?=base_url('admin/users_activity_log_delete/B')?>')"><?=my_caption('al_delete_log_B')?></a>
		<a class="dropdown-item" href="javascript:void(0)" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?php echo my_caption('al_delete_log') . ' ' . my_caption('al_delete_log_C')?>', '<?=base_url('admin/users_activity_log_delete/C')?>')"><?=my_caption('al_delete_log_C')?></a>
		<a class="dropdown-item" href="javascript:void(0)" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?php echo my_caption('al_delete_log') . ' ' . my_caption('al_delete_log_D')?>', '<?=base_url('admin/users_activity_log_delete/D')?>')"><?=my_caption('al_delete_log_D')?></a>
		<a class="dropdown-item" href="javascript:void(0)" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?php echo my_caption('al_delete_log') . ' ' . my_caption('al_delete_log_E')?>', '<?=base_url('admin/users_activity_log_delete/E')?>')"><?=my_caption('al_delete_log_E')?></a>
	    <a class="dropdown-item" href="javascript:void(0)" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?php echo my_caption('al_delete_log') . ' ' . my_caption('al_delete_log_F')?>', '<?=base_url('admin/users_activity_log_delete/F')?>')"><?=my_caption('al_delete_log_F')?></a>
		<a class="dropdown-item" href="javascript:void(0)" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?php echo my_caption('al_delete_log') . ' ' . my_caption('al_delete_log_G')?>', '<?=base_url('admin/users_activity_log_delete/G')?>')"><?=my_caption('al_delete_log_G')?></a>
	  </div>
	</div>
  </div>

  <div class="row">
    <div class="col-lg-12">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('al_users_title')?></h6>
        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="dataTable_activity_admin" class="table table-bordered">
			  <thead>
			    <tr>
				  <th width="8%"><?=my_caption('al_sheet_event_level')?></th>
				  <th width="12%"><?=my_caption('al_sheet_time')?></th>
				  <th width="12%"><?=my_caption('al_sheet_event')?></th>
				  <th width="68%"><?=my_caption('al_sheet_identifier_detail')?></th>
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
