<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('gs_tc_edit')?></h1>

  <div class="row">
    <div class="col-lg-8">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('gs_tc_edit')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('admin/general_setting_tc_action/'), ['method'=>'POST']);
			$tc_array = json_decode($this->setting->terms_conditions, TRUE);
			(set_value('tc_body') == '') ? $tc_body = html_escape($tc_array['body']) : $tc_body = set_value('tc_body');
		  ?>
		  <input type="hidden" name="tc_body_value" id="tc_body_value" value="<?=my_esc_html($tc_body)?>">
		  <div class="row form-group mb-4">
			<div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('gs_tc_Title')?></label>
			  <?php
			    (set_value('tc_title') == '') ? $tc_title = $tc_array['title'] : $tc_title = set_value('tc_title');
			    $data = array(
				  'name' => 'tc_title',
				  'id' => 'tc_title',
				  'value' => $tc_title,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('tc_title', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
			<div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('gs_tc_body')?></label>
			  <textarea id="tc_body" name="tc_body"></textarea>
			  <?=form_error('tc_body', '<small class="text-danger">', '</small>')?>
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
				  'value' => my_caption('global_save_changes'),
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