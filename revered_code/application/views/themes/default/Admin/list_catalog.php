<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-9 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('catalog_title')?></h1>
	</div>
    <div class="col-lg-3 text-right">
	  <button type="button" class="btn btn-primary mr-4" onclick="catalog_modal_show('<?=base_url('admin/catalog_add_action')?>', '', '<?=my_caption('catalog_new')?>', '')"><?=my_caption('catalog_new')?></button>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <div class="table-responsive mt-3">
	    <table class="table table-bordered">
		  <thead>
		    <tr>
			  <th width="10%">#</th>
			  <th width="32%"><?=my_caption('catalog_type')?></th>
			  <th width="33%"><?=my_caption('catalog_name')?></th>
			  <th width="25%"><?=my_caption('global_actions')?></th>
			</tr>
          </thead>
          <tbody>
		    <?php
			  if (empty($rs)) {
			?>
			<tr><td colspan="4"><?=my_caption('global_no_entries_found')?></td></tr>
			<?php
			  }
			  else {
				  $i = 0;
				  foreach ($rs as $row) {
					  $i++;
			?>
		    <tr>
			  <td><?=my_esc_html($i)?></td>
			  <td><?=my_caption('catalog_caption_' . $row->type)?></td>
			  <td><?=my_esc_html($row->name)?></td>
			  <td>
			    <div class="pull-right">
				  <div class="btn-group">
				    <a href="javascript:void(0)" onclick="catalog_modal_show('<?=base_url('admin/catalog_edit_action')?>', '<?=my_esc_html($row->ids)?>', '<?=my_caption('catalog_edit')?>', '<?=my_esc_html($row->type)?>', '<?=my_js_escape($row->name)?>', '<?=my_js_escape($row->description)?>')" class="btn btn-success btn-flat btn-sm"><i class="fa fa-edit"></i></a>
					<a href="javascript:void(0)" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?=my_js_escape(my_caption('global_not_revert'))?>', '<?=base_url('Admin/catalog_remove_action/' . $row->ids . '/')?>')" class="btn btn-danger btn-flat btn-sm"><i class="fa fa-trash"></i></a>
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
<?php my_load_view($this->setting->theme, 'Generic/catalog_modal');?>