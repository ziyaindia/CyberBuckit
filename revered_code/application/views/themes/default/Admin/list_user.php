<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
  my_load_view($this->setting->theme, 'header');
  $caption_list_user = my_caption('global_are_you_sure') . '||';
  $caption_list_user .= my_caption('user_impersonate_confirm') . '||';
  $caption_list_user .= my_caption('user_sheet_signin');
?>
<input type="hidden" id="caption_list_user" name="caption_list_user" value="<?=my_esc_html($caption_list_user)?>">
<input type="hidden" id="user_ids" name="user_ids" value="<?=my_uri_segment(3)?>">
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('user_list_title')?></h1>
	</div>
    <div class="col-lg-8 text-right">
	  <button type="button" class="btn btn-info mr-2" onclick="window.location.href='<?=base_url('admin/invite_user')?>'"><?=my_caption('user_list_invite_user')?></button>
	  <button type="button" class="btn btn-primary mr-2" onclick="window.location.href='<?=base_url('admin/new_user')?>'"><?=my_caption('user_list_new_user')?></button>
	  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=my_caption('global_toggle')?></button>
      <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
	    <a class="dropdown-item" href="<?=base_url('admin/list_user')?>"><?=my_caption('user_sheet_all_users')?></a>
		<a class="dropdown-item" href="<?=base_url('admin/list_user/active')?>"><?=my_caption('user_sheet_active_users')?></a>
		<a class="dropdown-item" href="<?=base_url('admin/list_user/today')?>"><?=my_caption('user_sheet_signup_today')?></a>
		<a class="dropdown-item" href="<?=base_url('admin/list_user/pending')?>"><?=my_caption('user_sheet_pending_users')?></a>
		<a class="dropdown-item" href="<?=base_url('admin/list_user/deactived')?>"><?=my_caption('user_sheet_deactivated_users')?></a>
	  </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('user_list_title')?></h6>
        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="dataTable_list_user" class="table table-bordered">
			  <thead>
			    <tr>
				  <th><?=my_caption('user_sheet_status')?></th>
				  <th><?=my_caption('user_sheet_created_date')?></th>
				  <th><?=my_caption('global_username')?></th>
				  <th><?=my_caption('user_sheet_email_address')?></th>
				  <th><?=my_caption('user_sheet_fullname')?></th>
				  <th><?=my_caption('global_actions')?></th>
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
