<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('software_license_title')?></h1>

  <div class="row mb-4">
    <div class="col-lg-8 col-md-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card border-left-success py-2">
	    <div class="card-body">
		  <p>
		    <b>Important Notice:</b><br>
			1. Basic license policy: one license, on active installation.<br>
			2. If you want to install the script using another domain, or migrate to another server, you need to uninstall the license first.<br>
			3. This operation doesn't affect any of your data or database structure. It just removes the license.<br>
			4. You won't be able to sigin after you uninstall the license.<br>
			5. If you accidentally uninstall the license, you can upload the original install folder and reinstall. Your data won't get lost.<br>
			6. We highly recommend you to read the documentation about the license policy.
		  </p>
		</div>
	  </div>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-8 col-md-12">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('software_license_title')?></h6>
        </div>
        <div class="card-body">
		  <div class="row">
		    <div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('software_license_purchase_code')?></label>
			  <input type="text" class="form-control" value="<?=$purchase_code?>" readonly>
			</div>
		  </div>
		  <div class="row">
		    <div class="col-lg-12 text-right">
			  <button type="button" id="admin_uninstall_license_btn" name="admin_uninstall_license_btn" class="btn btn-primary mt-4 mb-3 mr-1"><?=my_caption('software_license_btn_uninstall')?></button>
			</div>
		  </div>
		</div>
      </div>
	</div>
  </div>
  
</div>
<?php my_load_view($this->setting->theme, 'footer')?>