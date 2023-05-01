<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-9 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('upgrade_software_title')?></h1>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="table-responsive mt-3">
	    <table class="table table-bordered">
		  <thead>
		    <tr>
			  <th width="5%">#</th>
			  <th width="15%"><?=my_caption('upgrade_software_type')?></th>
			  <th width="35%"><?=my_caption('upgrade_software_name')?></th>
			  <th width="13%"><?=my_caption('upgrade_software_current_version')?></th>
			  <th width="13%"><?=my_caption('upgrade_software_latest_version')?></th>
			  <th width="21%"><?=my_caption('global_actions')?></th>
			</tr>
          </thead>
          <tbody>
			<?php
			  $btn_no_action_available = '<a href="javascript:void(0)" class="btn btn-sm btn-light text-gray-600"><i class="fas fa-thumbs-up mr-2"></i>' . my_caption('upgrade_software_no_action_available') . '</a>';
			  ($main_version['current_version'] != $main_version['latest_version']) ? $btn = '<a class="btn btn-primary btn-sm" href="' . base_url('admin/upgrade_software_view') . '"><i class="fas fa-eye mr-2"></i>' . my_caption('upgrade_software_view_upgrade') . '</a>' : $btn = $btn_no_action_available;
			  echo '<tr><td>1</td>' . '<td>' . $main_version['type'] . '</td><td>' . $main_version['name'] . '</td><td>' . $main_version['current_version'] . '</td><td>' . $main_version['latest_version'] . '</td><td>' . $btn . '</td></tr>';
			  $i = 2;
			  foreach ($addons_version as $version) {
				  ($version['current_version'] != $version['latest_version']) ? $btn = '<a class="btn btn-primary btn-sm" href="' . base_url('admin/upgrade_software_view/' . $version['id']) . '"><i class="fas fa-eye mr-2"></i>' . my_caption('upgrade_software_view_upgrade') . '</a>' : $btn = $btn_no_action_available;
				  echo '<tr><td>' . $i . '</td>' . '<td>' . $version['type'] . '</td><td>' . $version['name'] . '</td><td>' . $version['current_version'] . '</td><td>' . $version['latest_version'] . '</td><td>' . $btn . '</td></tr>';
				  $i++;
			  }
			?>
		  </tbody>
		</table>
	  </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer');?>
<?php my_load_view($this->setting->theme, 'Generic/simple_input_modal');?>