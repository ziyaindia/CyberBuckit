<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-9 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('rp_role_title')?></h1>
	</div>
    <div class="col-lg-3 text-right">
	  <button type="button" class="btn btn-primary mr-4" onclick="simple_input_modal_show('<?=base_url('Admin/rp_new_action/')?>', 'role', '<?=my_caption('rp_role_new_button')?>', '<?=my_caption('rp_role_name')?>')"><?=my_caption('rp_role_new_button')?></button>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <div class="table-responsive mt-3">
	    <table class="table table-bordered">
		  <thead>
		    <tr>
			  <th width="10%">#</th>
			  <th width="65%"><?=my_caption('rp_role_name')?></th>
			  <th width="25%"><?=my_caption('global_actions')?></th>
			</tr>
          </thead>
          <tbody>
		    <?php
			  if (empty($rs)) {
			?>
			<tr><td colspan="3"><?=my_caption('global_no_entries_found')?></td></tr>
			<?php
			  }
			  else {
				  $i = 0;
				  foreach ($rs as $row) {
					  $i++;
			?>
		    <tr>
			  <td><?=my_esc_html($i)?></td>
			  <td><?=str_replace('_', ' ', $row->name)?></td>
			  <td>
			    <div class="pull-right">
				  <div class="btn-group">
				    <?php if ($row->built_in == 0) { ?>
				    <a href="javascript:void(0)" onclick="simple_input_modal_show('<?=base_url('Admin/rp_edit_action/role/')?>', '<?=my_esc_html($row->ids)?>', '<?=my_caption('rp_role_edit_title')?>', '<?=my_caption('rp_role_name')?>', '<?=my_js_escape(str_replace('_', ' ' , $row->name))?>')" class="btn btn-success btn-flat btn-sm"><i class="fa fa-edit"></i></a>
					<a href="javascript:void(0)" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?=my_js_escape(my_caption('global_not_revert'))?>', '<?=base_url('Admin/rp_remove_action/role/' . $row->ids . '/')?>')" class="btn btn-danger btn-flat btn-sm"><i class="fa fa-trash"></i></a>
				    <?php } else { ?>
					<a href="javascript:void(0)" class="btn btn-sm btn-light text-gray-600"><span class="text"><?=my_caption('rp_role_built_in')?></span></a>
					<?php } ?>
				  </div>
				</div>
			  </td>
			</tr>
			<?php
			      }
			  }
			?>
		  </tbody>
		</table>
	  </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer');?>
<?php my_load_view($this->setting->theme, 'Generic/simple_input_modal');?>