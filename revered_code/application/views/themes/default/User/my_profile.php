<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('my_basic_data');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('mp_title')?></h1>
  <?php echo form_open_multipart(base_url('user/my_profile_action/'), ['method'=>'POST']); ?>
  <div class="row">
    <div class="col-lg-8">
	  <?php
		$data['rs'] = $rs;
		$data['card_title'] = my_caption('mp_title');
	    my_load_view($this->setting->theme, 'Generic/show_flash_card', $data);
		my_load_view($this->setting->theme, 'Generic/user_profile', $data);
	   ?>
	</div>
	<div class="col-lg-4">
	  <?php
	    if ($this->payment_swtich) {
			$view_data['rs_user'] = $rs;
			$view_data['title'] = my_caption('mp_my_balance');
		    my_load_view($this->setting->theme, 'Generic/user_balance_list', $view_data);
		}
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('mp_my_avatar')?></h6>
        </div>
        <div class="card-body">
		  <div class="row">
		    <div class="col-lg-12 mb-4">
			  <?php $img_url = base_url('upload/avatar/' . $this->user_avatar) . '?dummy=' . random_string('alnum', 6); ?>
		      <img id="img" src="<?=my_esc_html($img_url)?>" class="avatar">
		    </div>
		  </div>
		  <div class="row mb-2">
		    <div class="col-lg-12">
		      <input type='file' id="userfile" name="userfile" class="mb-3" accept=".png,.gif,.jpg,.jpeg">
			  <?php echo form_error('userfile', '<small class="text-danger">', '</small>'); ?>
		    </div>
		  </div>
		</div>
	  </div>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>
