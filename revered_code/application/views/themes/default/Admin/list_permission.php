<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <?php echo form_open(base_url('admin/permission_update/'), ['method'=>'POST']); ?>
  <div class="row">
    <div class="col-lg-6 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('rp_permission_title')?></h1>
	</div>
    <div class="col-lg-6 text-right">
	  <button type="button" class="btn btn-primary mr-2" onclick="simple_input_modal_show('<?=base_url('Admin/rp_new_action/')?>', 'permission', '<?=my_caption('rp_permission_new_button')?>', '<?=my_caption('rp_permission_name')?>')"><?=my_caption('rp_permission_new_button')?></button>
	  <button type="submit" class="btn btn-success mr-3"><?=my_caption('rp_permission_update_button')?></button>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card', array('rs'=>$rs, 'rs_role'=>$rs_role));
	  ?>
	  <div class="table-responsive mt-3">
	    <table class="table table-bordered">
		  <thead>
		    <tr>
			  <th width="20%"><?=my_caption('rp_permission_name')?></th>
			  <?php
			    foreach ($rs_role as $row) {
					echo '<th class="text-center">' . str_replace('_', ' ', $row->name) . '</th>';
 				}
			  ?>
			  <th width="15%"><?=my_caption('global_actions')?></th>
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
				  foreach ($rs as $row_permission) {
					  $i++;
			?>
		    <tr class="font-weight-bold">
			  <td><?=str_replace('_', ' ', $row_permission->name)?></td>
			  <?php
			    $k = 0;
			    foreach ($rs_role as $row_role) {
					$k++;
					$disabled = '';
					$permission_id = $row_role->ids . '_' . $row_permission->ids;
					if ($row_role->permission != '') {
						$role_permission_array = json_decode($row_role->permission, TRUE);
						($role_permission_array[$row_permission->ids]) ? $checked = 'checked' : $checked = '';
					}
					else {
						$checked = '';
					}
					if ($row_permission->built_in && $k==1) { $disabled = 'onclick="return false;"';}
					echo '<td class="text-center"><div class="custom-control custom-checkbox small"><input type="checkbox" name="' . $permission_id . '" id="' . $permission_id . '" value="true" class="custom-control-input" '. $checked . ' ' . $disabled .'><label class="custom-control-label" for="' . $permission_id . '"></label></div></td>';
				}
			  ?>
			  <td>
			    <div class="pull-right">
				  <div class="btn-group">
				    <?php if ($row_permission->built_in == 0) { ?> 
				    <a href="javascript:void(0)" onclick="simple_input_modal_show('<?=base_url('Admin/rp_edit_action/permission/')?>', '<?=my_esc_html($row_permission->ids)?>', '<?=my_caption('rp_permission_edit_title')?>', '<?=my_caption('rp_permission_name')?>', '<?=my_js_escape(str_replace('_', ' ', $row_permission->name))?>')" class="btn btn-success btn-flat btn-sm"><i class="fa fa-edit"></i></a> 
					<a href="javascript:void(0)" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?=my_js_escape(my_caption('global_not_revert'))?>', '<?=base_url('Admin/rp_remove_action/permission/' . $row_permission->ids . '/')?>')" class="btn btn-danger btn-flat btn-sm"><i class="fa fa-trash"></i></a>
				    <?php } else { ?>
					<a href="javascript:void(0)" class="btn btn-sm btn-light text-gray-600"><span class="text"><?=my_caption('rp_permission_built_in')?></span></a>
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
  <?php echo form_close(); ?>
</div>
<?php my_load_view($this->setting->theme, 'footer');?>
<?php my_load_view($this->setting->theme, 'Generic/simple_input_modal');?>