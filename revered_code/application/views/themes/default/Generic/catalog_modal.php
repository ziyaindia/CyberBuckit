<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal fade" id="catalog_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
  <?php echo form_open('', ['method'=>'POST', 'name'=>'catalog_submit_to', 'id'=>'catalog_submit_to']);?>
  <input type="hidden" name="catalog_hidden_ids" id="catalog_hidden_ids">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	  <div class="modal-header">
	    <h5 class="modal-title" id="catalog_title"></h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i aria-hidden="true" class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
	    <div class="form-group row">
		  <div class="col-lg-12">
		    <label><?=my_caption('catalog_type')?></label>
			<?php
			  $options = [
			    'support_contact_form' => my_caption('catalog_caption_support_contact_form'),
				'support_faq' => my_caption('catalog_caption_support_faq'),
				'support_documentation' => my_caption('catalog_caption_support_documentation'),
				'support_ticket' => my_caption('catalog_caption_support_ticket'),
				'blog_catalog' => my_caption('catalog_caption_blog_catalog'),
				'file_manager_folder' => my_caption('catalog_caption_file_manager_folder')
			  ];
			  $data = array(
			    'id' => 'catalog_type',
			    'class' => 'form-control'
			  );
			  echo form_dropdown('catalog_type', $options, '', $data);
			  ?>
		  </div>
		</div>
	    <div class="form-group row">
		  <div class="col-lg-12">
		    <label><?=my_caption('catalog_name')?></label>
			<input type="text" class="form-control" id="catalog_name" name="catalog_name" onkeydown="KeyDown('catalogReadySubmit')" autocomplete="off">
			<small id="catalog_err_msg" class="text-danger"></small>
		  </div>
		</div>
	    <div class="form-group row">
		  <div class="col-lg-12">
		    <label><?=my_caption('catalog_description')?></label>
			<textarea id="catalog_description" name="catalog_description" rows=5 class="form-control"></textarea>
			<small id="catalog_description_err_msg" class="text-danger"></small>
		  </div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btn_submit" onclick="catalogReadySubmit()" class="btn btn-primary mr-2"><?=my_caption('global_submit')?></button>
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=my_caption('global_simple_input_modal_close_button')?></button>
      </div>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>