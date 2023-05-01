<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('file_manager')?></h1>
  <div class="row">
    <div class="col-lg-12">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
		(my_uri_segment(3) == '') ? $seg3 = 'all' : $seg3 = my_uri_segment(3);
		(my_uri_segment(4) == '') ? $seg4 = 'all' : $seg4 = my_uri_segment(4);
	  ?>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-3">
	  <div class="card mb-4 py-3 border-left-primary">
	    <div class="card-body">
		  <b><?=my_caption('file_manager_file_type_filter')?></b> :
		  <br>
		  <div class="mt-2">
		    <?php ($seg3 == 'all') ? $filter_show = '<b>' . my_caption('global_all') . '</b>' : $filter_show = my_caption('global_all'); ?>
		    <a href="<?=base_url('files/file_manager/all/' . $seg4)?>"><?=my_esc_html($filter_show)?></a>
			<?php ($seg3 == 'image') ? $filter_show = '<b>' . my_caption('file_manager_file_type_image') . '</b>' : $filter_show = my_caption('file_manager_file_type_image'); ?>
			<a href="<?=base_url('files/file_manager/') . 'image/' . $seg4?>" class="ml-2"><?=my_esc_html($filter_show)?></a>
			<?php ($seg3 == 'av') ? $filter_show = '<b>' . my_caption('file_manager_file_type_av') . '</b>' : $filter_show = my_caption('file_manager_file_type_av'); ?>
			<a href="<?=base_url('files/file_manager/') . 'av/' . $seg4?>" class="ml-2"><?=my_esc_html($filter_show)?></a>
			<?php ($seg3 == 'document') ? $filter_show = '<b>' . my_caption('file_manager_file_type_document') . '</b>' : $filter_show = my_caption('file_manager_file_type_document'); ?>
			<a href="<?=base_url('files/file_manager/') . 'document/' . $seg4?>" class="ml-2"><?=my_esc_html($filter_show)?></a>
			<?php ($seg3 == 'other') ? $filter_show = '<b>' . my_caption('global_others') . '</b>' : $filter_show = my_caption('global_others'); ?>
			<a href="<?=base_url('files/file_manager/') . 'other/' . $seg4?>" class="ml-2"><?=my_esc_html($filter_show)?></a>
		  </div>
		  <br><br>
		  <hr class="dotted">
		    <a class="btn btn-primary btn-block" href="<?=base_url('files/file_upload')?>"><?=my_caption('file_upload')?></a>
		  <hr class="dotted">
		  <br><br>
		  <b><a href="<?=base_url('admin/catalog')?>"><?=my_caption('file_upload_catalog')?></a></b>:
		  <br><br>
		  <?php
		    ($seg4 == 'all') ? $catalog_show = '<b>' . my_caption('file_manager_all_files') . '</b>' : $catalog_show = my_caption('file_manager_all_files');
		    echo '<div><a href="' . base_url('files/file_manager/' . $seg3) . '"><i class="far fa-folder-open"></i> ' . $catalog_show . '</a></div><hr>';
		    foreach ($catalog_array as $catalog) {
				(str_replace('_nbsp_', ' ', $seg4) == $catalog) ? $catalog_show = '<b>' . $catalog . '</b>' : $catalog_show = $catalog;
				echo '<div><a href="' . base_url('files/file_manager/'. $seg3 . '/') . str_replace(' ', '_nbsp_', $catalog) . '"><i class="far fa-folder-open"></i> ' . $catalog_show . '</a></div><hr>';
			}
		  ?>
		</div>
      </div>
	</div>
	<div class="col-lg-9">
	  <?php
	    if (empty($file_list)) {
			echo '<h4>' . my_caption('file_manager_no_file') . '</h4>';
		}
		else {
			foreach ($file_list as $file) {
                $file_type_detect = my_get_file_icon($file->file_ext);
                if ($file_type_detect == 'img') {
					$img_thumb = $this->config->item('my_upload_directory') . '/' . $file->catalog . '/' . $file->ids . '_thumb.' . $file->file_ext;
					(file_exists($img_thumb)) ? $img_url = base_url() . $img_thumb : $img_url = base_url() . $this->config->item('my_upload_directory') . $file->catalog . '/' . $file->ids . '.' . $file->file_ext;
					$img_block = '<img id="' . $file->ids . '" class="fm-list-image" src="' . $img_url . '">';
				}
				else {
					$img_block = '<div id="' . $file->ids . '" class="fm-list-image-icon"><i class="far ' . $file_type_detect . '"></i></div>';
				}
				$description_block = '<div class="fm-file-introduction"><span title="' . $file->original_filename . '">'. substr($file->original_filename, 0, 20) . '</span><br><small>' . my_caption('file_manager_upload_time') . ' : '. my_conversion_from_server_to_local_time($file->created_time, $this->user_timezone, $this->user_date_format) . '</small></div>';
				echo '<div class="fm-file-box"><div class="fm-file">' . $img_block . $description_block . '</div></div>';
			}
		}
	  ?>
	</div>
  </div>
</div>
<div class="modal fade" id="fm-modal-view" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
	  <div class="modal-header">
	    <h5 class="modal-title" id="fm-modal-view-title"></h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <i aria-hidden="true" class="fas fa-times"></i>
		</button>
		<input type="hidden" id="fm-base-url" value="<?=base_url()?>">
		<input type="hidden" id="fm-modal-view-file-ids" value="">
      </div>
      <div class="modal-body">
	    <div class="row">
		  <div class="col-lg-12">
		    <div id="fm-modal-preview"></div>
		  </div>
		</div>
	    <div class="row mt-3">
		  <div class="col-lg-10 offset-2">
		    <p class="fm-view-introduction">
		      <div><b><?=my_caption('file_manager_file_name')?> : </b><span id="fm-modal-view-file-name"></span></div>
			  <div><b><?=my_caption('file_upload_catalog')?> : </b><span id="fm-modal-view-file-catalog"></span></div>
			  <div><b><?=my_caption('file_manager_upload_time')?> : </b><span id="fm-modal-view-file-time"></span></div>
			  <div><b><?=my_caption('global_description')?> : </b><span id="fm-modal-view-file-description"></span></div>
			  <div><b><?=my_caption('file_manager_file_uri')?> : </b><span id="fm-modal-view-file-path" class="fm-view-introduction"></span></div>
			</p>
		  </div>
		</div>
      </div>
      <div class="modal-footer">
        <a id="fm-modal-view-download" href="" class="btn btn-primary font-weight-bold"><?=my_caption('global_download')?></a>
		<button type="button" class="btn btn-danger font-weight-bold" onclick="actionQuery('<?=my_caption('file_manager_delete_file')?>', '<?=my_caption('global_not_revert')?>', '<?=base_url('files/file_remove_action/')?>' + $('#fm-modal-view-file-ids').val(), '')"><?=my_caption('global_delete')?></button>
		<button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>