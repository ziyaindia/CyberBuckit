<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header') ?>
<?php
  $action = my_uri_segment(2);
  $action_sub = my_uri_segment(3);
  if ($action == 'documentation_new' || $action_sub == 'new') {  //new
	  $documentation_title = my_caption('support_documentation_new');
	  $documentation_form_action = 'admin/documentation_action/new/';
	  $documentation_enabled = 1;
	  (set_value('documentation_body') == '') ? $documentation_body = '' : $documentation_body = set_value('documentation_body');
	  $documentation_keyword = '';
	  $documentation_preview = '';
  }
  else {  //edit
      $ids = my_uri_segment(4);
      ($ids == '') ? $ids = my_uri_segment(3) : null;
	  $documentation_title = my_caption('support_documentation_edit');
	  $documentation_form_action = 'admin/documentation_action/edit/' . $ids . '/';
	  $documentation_catalog = $rs->catalog;
	  $documentation_subject = $rs->subject;
	  $documentation_enabled = $rs->enabled;
	  (set_value('documentation_body') == '') ? $documentation_body = html_escape($rs->body) : $documentation_body = set_value('documentation_body');
	  $documentation_keyword = $rs->keyword;
	  $documentation_preview = '<a href="' . base_url('home/documentation_view/' . $rs->slug) . '" target="_blank"><u>' . my_caption('global_preview') . '</u></a>';
	  
  }
?>
<input type="hidden" name="documentation_body_value" id="documentation_body_value" value="<?=my_esc_html($documentation_body)?>">
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_esc_html($documentation_title)?></h1>

  <div class="row">
    <div class="col-lg-12">
	  <?php
	    $catalog_list = my_get_catalog('support_documentation');
		if (empty($catalog_list)) {
			$check_catalog_pass = FALSE;
			$this->session->set_flashdata('flash_danger', my_caption('global_catalog_required') . ' <a href="' . base_url('admin/catalog') . '"><u>' . my_caption('catalog_title') . '</u></a>');
		}
		else {
			$check_catalog_pass = TRUE;
		}
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_esc_html($documentation_title)?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url($documentation_form_action), ['method'=>'POST']);
		  ?>
		  <input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('documentation_enabled') == '') ? $checked = $documentation_enabled : $checked = set_value('documentation_enabled');
				  $data = array(
				    'name' => 'documentation_enabled',
					'id' => 'documentation_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="documentation_enabled"><?=my_caption('support_documentation_enabled')?></label>
              </div>
			</div>
			<div class="col-lg-6">
			  <label class="mt-3"><?=my_esc_html($documentation_preview)?></label>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('support_documentation_catalog')?> <a href="<?=base_url('admin/catalog')?>" class="ml-2"><u><?=my_caption('global_goto_catalog_button')?></u></a></label>
			  <?php
			    if (empty($documentation_catalog)) {
					(!empty(set_value('documentation_catalog'))) ? $documentation_catalog = set_value('documentation_catalog') : $documentation_catalog = 1;
				}
				else {
					(!empty(set_value('documentation_catalog'))) ? $documentation_catalog = set_value('documentation_catalog') : null;
				}
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
			    echo form_dropdown('documentation_catalog', $catalog_list, $documentation_catalog, $data);
				echo form_error('documentation_catalog', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><?=my_caption('global_keyword')?></label>
			  <?php
			    (!empty(set_value('documentation_keyword'))) ? $documentation_keyword = set_value('documentation_keyword') : null;
			    $data = array(
				  'name' => 'documentation_keyword',
				  'id' => 'documentation_keyword',
				  'value' => $documentation_keyword,
				  'class' => 'form-control',
				  'placeholder' => my_caption('support_documentation_keyword_placeholder')
				);
				echo form_input($data);
				echo form_error('documentation_keyword', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('support_documentation_subject')?></label>
			  <?php
			    if (empty($documentation_subject)) {
					(!empty(set_value('documentation_subject'))) ? $documentation_subject = set_value('documentation_subject') : $documentation_subject = '';
				}
				else {
					(!empty(set_value('documentation_subject'))) ? $documentation_subject = set_value('documentation_subject') : null;
				}
			    $data = array(
				  'name' => 'documentation_subject',
				  'id' => 'documentation_subject',
				  'value' => $documentation_subject,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('documentation_subject', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('support_documentation_body')?></label>
			  <textarea id="documentation_body" name="documentation_body"></textarea>
			  <?=form_error('documentation_body', '<small class="text-danger">', '</small>')?>
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
				  'value' => my_caption('global_submit'),
				  'class' => 'btn btn-primary mr-2'
			    );
				(!$check_catalog_pass) ? $data['disabled'] = 'disabled' : null;
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