<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal fade" id="simple_input_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
  <?php echo form_open('', ['method'=>'POST', 'name'=>'sim_submit_to', 'id'=>'sim_submit_to']);?>
  <input type="hidden" name="sim_hidden" id="sim_hidden">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	  <div class="modal-header">
	    <h5 class="modal-title" id="sim_title"></h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i aria-hidden="true" class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
	    <div class="form-group row">
		  <div class="col-lg-12">
		    <label id="sim_input_label"></label>
			<input type="text" class="form-control" id="sim_value" name="sim_value" onkeydown="KeyDown('simReadySubmit')" autocomplete="off">
			<small id="sim_err_msg" class="text-danger"></small>
		  </div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btn_submit" onclick="simReadySubmit()" class="btn btn-primary mr-2"><span id="loading_spinner" class="spinner-border spinner-border-sm mr-1 style-display-none" role="status" aria-hidden="true"></span> <?=my_caption('global_submit')?></button>
		<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=my_caption('global_simple_input_modal_close_button')?></button>
      </div>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>