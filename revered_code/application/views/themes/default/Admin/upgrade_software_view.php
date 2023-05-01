<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('upgrade_software_view_upgrade')?></h1>
  
  <div class="row">
    <div class="col-lg-12 mb-4">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card border-left-success py-2">
	    <div class="card-body">
		  <p>
		    <b>Important! Read First</b> :<br>
			1. The most important tips: <b>Backup all of your files and database before proceeding.</b><br>
			2. Upgrade should be done version by version, so you may perform several times to get the latest version.<br>
			3. Upgrade may spend some time. Don't refresh the page while you are upgrading. It depends on the network connection.<br>
			4. In principle, you're able to upgrade here only when your support is not expired. Otherwise, you need to download the latest version from Envato.<br>
			5. If you have done some customization work on this script, this upgrade will overwrite your changes, see (6) for the solution.<br>
			6. All the files that will be affected by the upgrade will be listed in the changelog, this is helpful for you to handle your former customization work.<br>
			7. Tips again: Backup all of your files and database before proceeding.<br>
			<span class="text-danger">8. To get the correct result, after the final time of upgrade, you should clear the cache of this website in your browser.</span>
		  </p>
		</div>
	  </div>
    </div>
	<div class="col-lg-12">
	  <?php echo form_open(base_url('admin/upgrade_software_action/'), ['method'=>'POST', 'name'=>'admin_upgrade_form', 'id'=>'admin_upgrade_form']);?>
	  <input type="hidden" id="current_version" name="current_version" value="<?=$current_version?>">
	  <input type="hidden" id="ready_version" name="ready_version" value="<?=$ready_version?>">
	  <input type="hidden" id="script_ids" name="script_ids" value="<?=$script_ids?>">
	  <div class="table-responsive mt-2 mb-4">
	    <table class="table table-bordered">
		  <thead>
		    <tr>
			  <th width="5%" class="text-center">#</th>
			  <th width="25%"><?=my_caption('global_item')?></th>
			  <th width="70%"><?=my_caption('global_value')?></th>
			</tr>
          </thead>
          <tbody>
			<tr>
			  <td class="text-center">1</td>
			  <td><b><?=my_caption('upgrade_software_name')?></b></td>
			  <td>[<?=$script_type?>] <?=$script_name?></td>
			</tr>
			<tr>
			  <td class="text-center">2</td>
			  <td><b><?=my_caption('upgrade_software_current_version')?></b></td>
			  <td>v<?=$current_version?></td>
			</tr>
			<tr>
			  <td class="text-center">3</td>
			  <td><b><?=my_caption('upgrade_software_latest_version')?></b></td>
			  <td>
			    <?php
				  if ($latest_version != 'UnKnown') {
					  echo 'v' . $latest_version;
				  }
				  else {
					  echo my_esc_html($latest_version);
				  }
				?>
				[ <?=my_caption('upgrade_software_release_date')?>: <?=$latest_version_date?> ] <br>
				<?php if ($ready_version != $current_version) { echo my_caption('upgrade_software_perform_time_head') . '<b>' . $total_version . '</b>' . my_caption('upgrade_software_perform_time_tail'); }?>
			  </td>
			</tr>
			<?php
			  if ($ready_version == $current_version) {
			?>
			<tr>
			  <td></td>
			  <td colspan=2 class="text-left">
			    <button type="button" class="btn btn-sm btn-light text-gray-600 mr-2 mt-2 mb-2"><i class="fas fa-thumbs-up mr-2"></i><?=my_caption('upgrade_software_no_action_available')?></button>
			  </td>
			</tr>
			<?php
			  }
			  else {
			      if ($ready_version != $latest_version) {
					   $j = 5;
					   $k = 6;
			?>
			  <tr>
			    <td class="text-center">4</td>
			    <td><b><?=my_caption('upgrade_software_ready_version')?></b></td>
			    <td>v<?=$ready_version?> [ <?=my_caption('upgrade_software_release_date')?>: <?=$ready_version_date?> ] <br> <?=my_caption('upgrade_software_ready_version_notice')?></td>
			  </tr>
			<?php
				  }
				  else {
					  $j = 4;
					  $k = 5;
				  }
			?>
			<tr>
			  <td class="text-center">5</td>
			  <td><b><?=my_caption('upgrade_software_ready_version_changelog')?> of v<?=$ready_version?></b></td>
			  <td><?=my_reverse_from_input_for_html($ready_version_changelog, TRUE)?></td>
			</tr>
			<tr>
			  <td class="text-center">6</td>
			  <td><b><?=my_caption('upgrade_software_check_result')?></b></td>
			  <td>
			    <?php
				  if ($writeable) {
					  echo '<div class="text-success">' . my_caption('upgrade_software_check_result_passed') . '</div>';
				  }
				  else {
					  echo '<div class="text-danger ml-3">' . my_caption('upgrade_software_check_result_not_passed') . ' ( ' . FCPATH . ' )</div>';
				?>
				<p class="ml-3 mr-2 mt-2">
				  Solution :<br>
				  It's extremely unsafe when your entire site is writable. But for automatic upgrade purpose, you have to do that. You could grant writable permission to your whole site and revoke after the upgrade. Generally the upgrade will only take you less than one minute. Here are the instructions :<br>
				  Let's assume you are using Apache on Linux ( It's almost same on other http server software ). For the typical configuration the simplest way to grant writable permission to the whole site is using this command :<br>
				  <code>chown -Rf www-data:www-data <?=FCPATH?></code><br>
				  Now, Refresh the current page, and you may be able to upgrade.<br>
				  After upgrade, you should revoke the writable permission, and you may use this command :<br>
				  <code>chown -Rf root:root <?=FCPATH?></code><br>
				  Please notice that it's not always the root group and user, it depends on your system, make sure you check before you do.<br>
				  <b>Tips: Make sure you revoke the writable permission after the upgrade.</b>
				</p>
				<?php
				  }
				?>
			  </td>
			</tr>
			<tr>
			  <td class="text-right" colspan=3>
			  <?php
			    $data = array(
				  'type' => 'button',
				  'name' => 'admin_upgrade_btn_start',
				  'id' => 'admin_upgrade_btn_start',
				  'value' => my_caption('upgrade_software_btn_upgrade_to') . $ready_version,
				  'class' => 'btn btn-primary mr-2 mt-2 mb-2'
				);
				(!$writeable) ? $data['disabled'] = 'disabled' : null;
				echo form_submit($data);
			  ?>
			  </td>
			</tr>
			<?php
			  }
			?>
		  </tbody>
		</table>
	  </div>
	  <?php echo form_close(); ?>
    </div>
  </div>
  
</div>
<?php my_load_view($this->setting->theme, 'footer')?>