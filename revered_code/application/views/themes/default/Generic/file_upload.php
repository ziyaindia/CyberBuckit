<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<link href="<?=base_url()?>assets/themes/default/vendor/dropzone/dropzone.min.css" type="text/css" rel="stylesheet" />
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('file_upload')?></h1>

  <div class="row">
    <div class="col-lg-9">
	  <?php
		if (empty($catalog_options)) {
			$check_catalog_pass = FALSE;
			$this->session->set_flashdata('flash_danger', my_caption('global_catalog_required') . ' <a href="' . base_url('admin/catalog') . '"><u>' . my_caption('catalog_title') . '</u></a>');
		}
		else {
			$check_catalog_pass = TRUE;
		}
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
		$file_setting_array = json_decode(my_global_setting('file_setting'), TRUE);
		$file_type_array = explode('|', $file_setting_array['file_type']);
		$allowed_file_type = '';
		foreach ($file_type_array as $file_type) {
			$allowed_file_type .= '.' . $file_type . ',';
		}
		$allowed_file_type = rtrim($allowed_file_type, ',');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('file_upload')?></h6>
        </div>
        <div class="card-body">
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
			  <?php
			    echo form_open(base_url('files/file_upload_action'), ['class'=>'dropzone needsclick dz-clickable', 'id'=>'fileupload']);
				echo form_close();
			  ?>
			</div>
		  </div>
		  <?php echo form_open(base_url('files/file_upload_save_action'), ['name'=>'save_files', 'id'=>'save_files']);?>
		  <input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('global_catalog')?> [<a href="<?=base_url('admin/catalog')?>"><?=my_caption('global_goto_catalog_button')?></a>]</label>
			  <?php
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
			    echo form_dropdown('file_catalog', $catalog_options, 1, $data);
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
			  <label><?=my_caption('global_description')?></label>
			  <?php
			    $data = array(
				  'name' => 'description',
				  'id' => 'description',
				  'rows' => 5,
				  'class' => 'form-control'
				);
				echo form_textarea($data);
			  ?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <input type="button" class="btn btn-secondary mr-2" value="<?=my_caption('global_go_back')?>" onclick="javascript:window.history.back()">
			  <?php
			    $data = array(
				  'type' => 'submit',
				  'name' => 'btn_submit_block',
				  'id' => 'btn_submit_block',
				  'value' => my_caption('file_upload_button'),
				  'class' => 'btn btn-primary mr-2'
			    );
				(!$check_catalog_pass) ? $data['disabled'] = 'disabled' : null;
			    echo form_submit($data);
			  ?>
			</div>
		  </div>
		  <?php echo form_close();?>
		</div>
      </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>
<input type="hidden" name="allowed_file_type" id="allowed_file_type" value="<?=my_esc_html($allowed_file_type)?>">
<input type="hidden" name="file_size" id="file_size" value="<?=my_esc_html($file_setting_array['file_size'])?>">
<script src="<?=base_url()?>assets/themes/default/vendor/dropzone/dropzone.min.js"></script>