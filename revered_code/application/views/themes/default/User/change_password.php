<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('cp_title')?></h1>

  <div class="row">
    <div class="col-lg-8 col-md-12">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('cp_title')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('user/change_password_action/'), ['method'=>'POST']);
		  ?>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('cp_old_password_label')?></label>
			  <?php
			    $data = array(
				  'name' => 'old_password',
				  'id' => 'old_password',
				  'value' => set_value('old_password'),
				  'class' => 'form-control'
				);
				echo form_password($data);
				echo form_error('old_password', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('cp_new_password_label')?></label>
			  <?php
			    $data = array(
				  'name' => 'new_password',
				  'id' => 'new_password',
				  'value' => set_value('new_password'),
				  'class' => 'form-control'
				);
				echo form_password($data);
				echo form_error('new_password', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('cp_new_password_confirm_label')?></label>
			  <?php
			    $data = array(
				  'name' => 'new_password_confirm',
				  'id' => 'new_password_confirm',
				  'value' => set_value('new_password_confirm'),
				  'class' => 'form-control'
				);
				echo form_password($data);
				echo form_error('new_password_confirm', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <?php
			    $data = array(
				  'type' => 'submit',
				  'name' => 'btn_change',
				  'id' => 'btn_change',
				  'value' => my_caption('cp_change_button'),
				  'class' => 'btn btn-primary mr-2'
			    );
			    echo form_submit($data);
			  ?>
			</div>
		  </div>
		  <?php echo form_close(); ?>
		</div>
      </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>