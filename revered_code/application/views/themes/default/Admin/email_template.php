<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-9 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('et_title')?></h1>
	</div>
    <div class="col-lg-3 text-right">
	  <a href="<?=base_url('admin/email_template_new')?>" class="btn btn-primary mr-4"><?=my_caption('et_email_new')?></a>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="table-responsive mt-3">
	    <table class="table table-bordered">
		  <thead>
		    <tr>
			  <th width="5%">#</th>
			  <th width="20%"><?=my_caption('et_email_purpose')?></th>
			   <th width="60%"><?=my_caption('et_email_subject')?></th>
			  <th width="15%"><?=my_caption('global_actions')?></th>
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
			  <td><?=my_esc_html($row->purpose)?></td>
			  <td><?=my_esc_html($row->subject)?></td>
			  <td>
			    <div class="pull-right">
				  <div class="btn-group">
				    <div class="btn-group">
					  <a href="<?=base_url('admin/email_template_edit/' . $row->ids)?>" class="btn btn-success btn-flat btn-sm"><i class="fa fa-edit"></i></a>
					  <?php
					  if ($row->built_in == 0) {
						  echo '<a href="javascript:void(0)" onclick="actionQuery(\'' . my_caption('global_are_you_sure') . '\',\'' . my_caption('global_not_revert') . '\', \'' .base_url('admin/email_template_remove/' . $row->ids) . '\')" class="btn btn-danger btn-flat btn-sm"><i class="fa fa-trash"></i></a>';
					  }
					  ?>
					</div>
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