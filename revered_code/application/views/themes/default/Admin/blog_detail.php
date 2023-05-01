<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header') ?>
<?php
  $action = my_uri_segment(2);
  if ($action == 'blog_new' || $action =='blog_new_action') {  //new
	  $blog_title = my_caption('blog_new');
	  $blog_form_action = 'admin/blog_new_action/';
	  $blog_enabled = 1;
	  (set_value('blog_body') == '') ? $blog_body = '' : $blog_body = set_value('blog_body');
	  $blog_keyword = '';
	  $blog_preview = '';
	  $blog_button_text = my_caption('global_submit');
	  $blog_cover = '';
  }
  else {  //edit
      $ids = my_uri_segment(3);
	  $blog_title = my_caption('blog_edit');
	  $blog_form_action = 'admin/blog_edit_action/' . $ids;
	  $blog_catalog = $rs->catalog;
	  $blog_subject = $rs->subject;
	  $blog_enabled = $rs->enabled;
	  (set_value('blog_body') == '') ? $blog_body = html_escape($rs->body) : $blog_body = set_value('blog_body');
	  $blog_keyword = $rs->keyword;
	  $blog_preview = '<a href="' . base_url('blog/view/' . $rs->slug) . '" target="_blank"><u>' . my_caption('global_preview') . '</u></a>';
	  $blog_button_text = my_caption('global_save_changes');
	  ($rs->cover_photo == '') ? $blog_cover = '' : $blog_cover = '<a href="' . base_url($this->config->item('my_upload_directory') . '/blog/' . $rs->cover_photo . '?dummy=' . random_string('alnum', 6)) . '" target="blank" class="ml-3 mr-3"><u>' . my_caption('blog_view_cover_photo') . '</u></a>' . my_caption('blog_view_cover_photo_notice');
  }
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-9 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_esc_html($blog_title)?></h1>
	</div>
	<div class="col-lg-3 text-right">
	  <a href="<?=base_url('admin/blog')?>" class="btn btn-primary mr-2"><?=my_caption('blog_manager')?></a>
	</div>
  </div>

  <div class="row">
    <div class="col-lg-12">
	  <?php
	    $catalog_list = my_get_catalog('blog_catalog');
		if (empty($catalog_list)) {
			$check_catalog_pass = FALSE;
			$this->session->set_flashdata('flash_danger', my_caption('global_catalog_required') . ' <a href="' . base_url('admin/catalog') . '"><u>' . my_caption('catalog_title') . '</u></a>');
		}
		else {
			$check_catalog_pass = TRUE;
		}
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <input type="hidden" name="blog_body_value" id="blog_body_value" value="<?=my_esc_html($blog_body)?>">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_esc_html($blog_title)?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open_multipart(base_url($blog_form_action), ['method'=>'POST']);
		  ?>
		  <input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
		  <div class="row form-group">
		    <div class="col-lg-6 mb-2">
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('blog_enabled') == '') ? $checked = $blog_enabled : $checked = set_value('blog_enabled');
				  $data = array(
				    'name' => 'blog_enabled',
					'id' => 'blog_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="blog_enabled"><?=my_caption('blog_publish')?></label>
              </div>
			</div>
			<div class="col-lg-6 mb-2">
			  <label class="mt-3"><?=my_esc_html($blog_preview)?></label>
			</div>
		  </div>
		  <div class="row form-group">
		    <div class="col-lg-6 mb-2">
			  <label><span class="text-danger">*</span> <?=my_caption('blog_catalog')?> <a href="<?=base_url('admin/catalog')?>" class="ml-2"><u><?=my_caption('global_goto_catalog_button')?></u></a></label>
			  <?php
			    if (empty($blog_catalog)) {
					(!empty(set_value('blog_catalog'))) ? $blog_catalog = set_value('blog_catalog') : $blog_catalog = 1;
				}
				else {
					(!empty(set_value('blog_catalog'))) ? $blog_catalog = set_value('blog_catalog') : null;
				}
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
			    echo form_dropdown('blog_catalog', $catalog_list, $blog_catalog, $data);
				echo form_error('blog_catalog', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6 mb-2">
			  <label><?=my_caption('global_keyword')?></label>
			  <?php
			    (!empty(set_value('blog_keyword'))) ? $blog_keyword = set_value('blog_keyword') : null;
			    $data = array(
				  'name' => 'blog_keyword',
				  'id' => 'blog_keyword',
				  'value' => $blog_keyword,
				  'class' => 'form-control',
				  'placeholder' => my_caption('support_documentation_keyword_placeholder')
				);
				echo form_input($data);
				echo form_error('blog_keyword', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group">
		    <div class="col-lg-6 mb-2">
			  <label><span class="text-danger">*</span> <?=my_caption('blog_subject')?></label>
			  <?php
			    if (empty($blog_subject)) {
					(!empty(set_value('blog_subject'))) ? $blog_subject = set_value('blog_subject') : $blog_subject = '';
				}
				else {
					(!empty(set_value('blog_subject'))) ? $blog_subject = set_value('blog_subject') : null;
				}
			    $data = array(
				  'name' => 'blog_subject',
				  'id' => 'blog_subject',
				  'value' => $blog_subject,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('blog_subject', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6 mb-2">
			  <label><?=my_caption('blog_cover_photo')?><?=my_esc_html($blog_cover)?></label>
			  <div>
			    <input type='file' id="userfile" name="userfile" accept=".jpg" class="mt-1">
			  </div>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('blog_body')?></label>
			  <textarea id="blog_body" name="blog_body"></textarea>
			  <?=form_error('blog_body', '<small class="text-danger">', '</small>')?>
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
				  'value' => $blog_button_text,
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