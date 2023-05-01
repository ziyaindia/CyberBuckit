<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('database_backup_title')?></h1>

  <div class="row">
    <div class="col-lg-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="row mb-4">
	    <div class="col-lg-5">
	      <div class="card shadow mb-4">
		    <div class="card-header py-3">
		      <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('database_backup_title')?></h6>
		    </div>
			<?php echo form_open(base_url('admin/database_backup_action/'), ['name'=>'backup', 'id'=>'backup', 'method'=>'POST']);?>
			<input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
			<div class="card-body">
			  <div class="row mb-2">
			    <div class="col-lg-12">
				  <p><?php echo my_caption('database_backup_last_time') . my_conversion_from_server_to_local_time($this->setting->last_backup_time, $this->user_timezone, $this->user_dtformat) ;?></p>
				</div>
			  </div>
			  <div class="row form-group mb-3">
			    <div class="col-lg-12">
				  <label><?=my_caption('database_backup_key_list')?></label>
				  <p>
				    <?php
					  $key_table_array = explode(',', $this->config->item('my_key_table'));
					  $table_list = '';
					  foreach ($key_table_array as $table) {
						  $table_list .= $table . ' | ';
					  }
					  echo substr($table_list, 0, -3);
					?>
				  </p>
				</div>
			  </div>
			  <div class="row form-group mb-4">
			    <div class="col-lg-12">
				  <label><?=my_caption('database_backup_option')?></label>
				  <?php
				  (!empty(set_value('backup_range'))) ? $backup_range = set_value('backup_range') : $backup_range = 'B';
				  $options = array (
				    'A' => my_caption('database_backup_option_key'),
					'B' => my_caption('database_backup_option_whole')
				  );
				  $data = array(
				    'class' => 'form-control selectpicker'
				  );
				  echo form_dropdown('backup_range', $options, $backup_range, $data);
				  echo form_error('backup_range', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			  </div>
			  <div class="row form-group mb-4">
			    <div class="col-lg-12">
				  <label><?=my_caption('database_backup_action')?></label>
				  <?php
				  (!empty(set_value('backup_action'))) ? $backup_action = set_value('backup_action') : $backup_action = 'A';
				  $options = array (
				    'A' => my_caption('database_backup_action_save'),
					'B' => my_caption('database_backup_action_download')
				  );
				  $data = array(
				    'class' => 'form-control selectpicker',
					'id' => 'backup_action'
				  );
				  echo form_dropdown('backup_action', $options, $backup_action, $data);
				  echo form_error('backup_action', '<small class="text-danger">', '</small>');
				  ?>
				</div>
			  </div>
			  <div class="row">
			    <div class="col-lg-6 offset-6 text-right">
				  <?php
				    $data = array(
					  'type' => 'button',
					  'name' => 'btn_backup_block',
					  'id' => 'btn_backup_block',
					  'value' => my_caption('database_backup_button'),
					  'class' => 'btn btn-primary mr-2'
					);
					echo form_submit($data);
					?>
				</div>
			  </div>
			</div>
			<?php echo form_close(); ?>
		  </div>
		  
	    </div>
	    <div class="col-lg-7">
	      <div class="card shadow mb-4">
		    <div class="card-header py-3">
		      <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('database_backup_instructions')?></h6>
		    </div>
			<div class="card-body">
			  <p>1. You are allowed to backup whole database, or only backup key table.</p>
			  <p>2. Key table is defined at line 528 in "/application/config/config.php", You can change as per your need.</p>
			  <p>3. There are two ways of backup: Download to your computer or Save the whole sql dump script on Server-side.</p>
			  <p>4. Logs of each backup are recorded. You can identify them easily.</p>
			</div>
		  </div>
	    </div>
	  </div>
	  <div class="row">
	    <div class="col-lg-12">
		  <div class="card shadow mb-4">
		    <div class="card-header py-3">
			  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('database_backup_log_title')?></h6>
			</div>
			<div class="card-body">
			  <div class="table-responsive">
			    <table id="dataTable_backup" class="table table-bordered">
				  <thead>
				    <tr>
					  <th width="10%"><?=my_caption('database_backup_log_sheet_method')?></th>
					  <th width="15%"><?=my_caption('database_backup_log_sheet_time')?></th>
					  <th width="55%"><?=my_caption('database_backup_log_sheet_file')?></th>
					  <th width="20%"><?=my_caption('database_backup_log_sheet_option')?></th>
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