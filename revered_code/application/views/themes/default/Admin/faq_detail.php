<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header') ?>
<?php
  $action = my_uri_segment(2);
  $action_sub = my_uri_segment(3);
  if ($action == 'faq_new' || $action_sub == 'new') {  //new
	  $faq_title = my_caption('support_faq_new');
	  $faq_form_action = 'admin/faq_action/new';
  }
  else {  //edit
      $ids = my_uri_segment(4);
      ($ids == '') ? $ids = my_uri_segment(3) : null;
	  $faq_title = my_caption('support_faq_edit');
	  $faq_form_action = 'admin/faq_action/edit/' . $ids;
	  $faq_catalog = $rs->catalog;
	  $faq_subject = $rs->subject;
	  $faq_body = $rs->body;
	  
  }
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_esc_html($faq_title)?></h1>

  <div class="row">
    <div class="col-lg-12">
	  <?php
	    $catalog_list = my_get_catalog('support_faq');
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
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_esc_html($faq_title)?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url($faq_form_action), ['method'=>'POST']);
		  ?>
		  <input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('support_faq_catalog')?> <a href="<?=base_url('admin/catalog')?>" class="ml-2"><u><?=my_caption('global_goto_catalog_button')?></u></a></label>
			  <?php
			    if (empty($faq_catalog)) {
					(!empty(set_value('faq_catalog'))) ? $faq_catalog = set_value('faq_catalog') : $faq_catalog = 1;
				}
				else {
					(!empty(set_value('faq_catalog'))) ? $faq_catalog = set_value('faq_catalog') : null;
				}
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
			    echo form_dropdown('faq_catalog', $catalog_list, $faq_catalog, $data);
				echo form_error('faq_catalog', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('support_faq_subject')?></label>
			  <?php
			    if (empty($faq_subject)) {
					(!empty(set_value('faq_subject'))) ? $faq_subject = set_value('faq_subject') : $faq_subject = '';
				}
				else {
					(!empty(set_value('faq_subject'))) ? $faq_subject = set_value('faq_subject') : null;
				}
			    $data = array(
				  'name' => 'faq_subject',
				  'id' => 'faq_subject',
				  'value' => $faq_subject,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('faq_subject', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('support_faq_body')?></label>
			  <?php
			    if (empty($faq_body)) {
					(!empty(set_value('faq_body'))) ? $faq_body = set_value('faq_body') : $faq_body = '';
				}
				else {
					(!empty(set_value('faq_body'))) ? $faq_body = set_value('faq_body') : null;
				}
			    $data = array(
				  'name' => 'faq_body',
				  'id' => 'faq_body',
				  'value' => $faq_body,
				  'rows' => 12,
				  'class' => 'form-control'
				);
				echo form_textarea($data);
				echo form_error('faq_body', '<small class="text-danger">', '</small>');
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