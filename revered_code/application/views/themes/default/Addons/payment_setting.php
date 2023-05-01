<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('addons_payment_gateway_setting')?></h1>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-8">
	  <?php
	    $payment_setting_array = json_decode($this->setting->payment_setting, 1);
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('addons_payment_gateway_setting')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('payment/setting_action/'), ['method'=>'POST']);
		  ?>
		  <div class="row form-group mb-5">
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('addons_payment_active_payment_gateway')?></label>
			  <?php
			    (array_key_exists('addon_gateway', $payment_setting_array)) ? $addons_pg_active = $payment_setting_array['addon_gateway'] : $addons_pg_active = '0';
			    (set_value('addons_pg_active') != '') ? $addons_pg_active = set_value('addons_pg_active') : null;
			    $options = array(
				  '0' => 'None',
				  'PayPal' => 'PayPal',
				  'Stripe' => 'Stripe',
				  'razorpay' => 'Razorpay',
				  'paystack' => 'Paystack',
				  'yoomoney' => 'Yoomoney'
				);
			    $data = array(
				  'id' => 'addons_pg_active',
			      'class' => 'form-control selectpicker'
			    );
			    echo form_dropdown('addons_pg_active', $options, $addons_pg_active, $data);
			    echo form_error('addons_pg_active', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  
		  
		  <div id="block_razorpay">
		    <div class="row form-group mb-4">
			  <div class="col-lg-6">
			    <label><b><?=my_caption('addons_payment_razorpay_setting')?></b></label>
				<div class="custom-control custom-checkbox mt-3">
				  <?php
				    (array_key_exists('razorpay_one_time_enabled', $payment_setting_array)) ? $checked = $payment_setting_array['razorpay_one_time_enabled'] : $checked = 0;
				    (set_value('addons_pg_razorpay_one_time') != '') ? $checked = set_value('addons_pg_razorpay_one_time') : null;
				    $data = array(
					  'name' => 'addons_pg_razorpay_one_time',
					  'id' => 'addons_pg_razorpay_one_time',
					  'value' => '1',
					  'checked' => $checked,
					  'class' => 'custom-control-input'
					);
					echo form_checkbox($data);
				  ?>
				  <label class="custom-control-label" for="addons_pg_razorpay_one_time"><?php echo my_caption('global_enabled') . ' ' . my_caption('payment_type_one_time');?></label>
				</div>
			  </div>
			  <div class="col-lg-6">
				<label>&nbsp;</label>
				<div class="custom-control custom-checkbox mt-3">
				  <?php
				    (array_key_exists('razorpay_recurring_enabled', $payment_setting_array)) ? $checked = $payment_setting_array['razorpay_recurring_enabled'] : $checked = 0;
				    (set_value('addons_pg_razorpay_recurring') != '') ? $checked = set_value('addons_pg_razorpay_recurring') : null;
					$data = array(
					  'name' => 'addons_pg_razorpay_recurring',
					  'id' => 'addons_pg_razorpay_recurring',
					  'value' => '1',
					  'checked' => $checked,
					  'class' => 'custom-control-input'
					);
					echo form_checkbox($data);
				  ?>
				  <label class="custom-control-label" for="addons_pg_razorpay_recurring"><?php echo my_caption('global_enabled'). ' '. my_caption('payment_type_recurring');?></label>
				</div>
			  </div>
			</div>
			<div class="row form-group mt-4 mb-4">
			  <div class="col-lg-6">
			    <label><span class="text-danger">*</span> <?=my_caption('addons_payment_razorpay_key_id')?></label>
				<?php
				  (array_key_exists('razorpay_key_id', $payment_setting_array)) ? $addons_pg_razorpay_key_id = $payment_setting_array['razorpay_key_id'] : $addons_pg_razorpay_key_id = '';
				  (set_value('addons_pg_razorpay_key_id') != '') ? $addons_pg_razorpay_key_id = set_value('addons_pg_razorpay_key_id') : null;
				  $data = array(
				    'name' => 'addons_pg_razorpay_key_id',
					'id' => 'addons_pg_razorpay_key_id',
					'value' => $addons_pg_razorpay_key_id,
					'class' => 'form-control'
				  );
				  echo form_input($data);
				  echo form_error('addons_pg_razorpay_key_id', '<small class="text-danger">', '</small>');
				?>
			  </div>
			  <div class="col-lg-6">
			    <label><span class="text-danger">*</span> <?=my_caption('addons_payment_razorpay_key_secret')?></label>
				<?php
				  (array_key_exists('razorpay_key_secret', $payment_setting_array)) ? $addons_pg_razorpay_key_secret = $payment_setting_array['razorpay_key_secret'] : $addons_pg_razorpay_key_secret = '';
				  (set_value('addons_pg_razorpay_key_secret') != '') ? $addons_pg_razorpay_key_secret = set_value('addons_pg_razorpay_key_secret') : null;
				  ($this->config->item('my_demo_mode')) ? $addons_pg_razorpay_key_secret = '********' : null;
				  $data = array(
				    'name' => 'addons_pg_razorpay_key_secret',
					'id' => 'addons_pg_razorpay_key_secret',
					'value' => $addons_pg_razorpay_key_secret,
					'class' => 'form-control'
				  );
				  echo form_input($data);
				  echo form_error('addons_pg_razorpay_key_secret', '<small class="text-danger">', '</small>');
				?>
			  </div>
			</div>
			<div class="row form-group mt-4 mb-4">
			  <div class="col-lg-6">
			    <label><span class="text-danger">*</span> <?=my_caption('addons_payment_razorpay_webhook_secret')?></label>
				<?php
				  (array_key_exists('razorpay_webhook_secret', $payment_setting_array)) ? $addons_pg_razorpay_webhook_secret = $payment_setting_array['razorpay_webhook_secret'] : $addons_pg_razorpay_webhook_secret = '';
				  (set_value('addons_pg_razorpay_webhook_secret') != '') ? $addons_pg_razorpay_webhook_secret = set_value('addons_pg_razorpay_webhook_secret') : null;
				  $data = array(
				    'name' => 'addons_pg_razorpay_webhook_secret',
					'id' => 'addons_pg_razorpay_webhook_secret',
					'value' => $addons_pg_razorpay_webhook_secret,
					'class' => 'form-control'
				  );
				  echo form_input($data);
				  echo form_error('addons_pg_razorpay_webhook_secret', '<small class="text-danger">', '</small>');
				?>
			  </div>
			  <div class="col-lg-6">
			    <label><span class="text-danger">*</span> <?=my_caption('payment_paypal_webhook_url')?></label>
				<p class="mt-2"><u><?=base_url() . 'webhook/razorpay/'?></u></p>
			  </div>
			</div>
			<div class="row form-group mt-4 mb-4">
			  <div class="col-lg-6">
			    <label><span class="text-danger">*</span> <?=my_caption('payment_webhook_required_event')?></label>
				<p class="mt-2">payment.captured, subscription.charged</p>
			  </div>
			</div>
		  </div>
		  
		  
		  <div id="block_paystack">
		    <div class="row form-group mb-4">
			  <div class="col-lg-6">
			    <label><b><?=my_caption('addons_payment_paystack_setting')?></b></label>
				<div class="custom-control custom-checkbox mt-3">
				  <?php
				    (array_key_exists('paystack_one_time_enabled', $payment_setting_array)) ? $checked = $payment_setting_array['paystack_one_time_enabled'] : $checked = 0;
				    (set_value('addons_pg_paystack_one_time') != '') ? $checked = set_value('addons_pg_paystack_one_time') : null;
				      $data = array(
						'name' => 'addons_pg_paystack_one_time',
						'id' => 'addons_pg_paystack_one_time',
						'value' => '1',
						'checked' => $checked,
						'class' => 'custom-control-input'
					  );
					  echo form_checkbox($data);
				  ?>
				  <label class="custom-control-label" for="addons_pg_paystack_one_time"><?php echo my_caption('global_enabled') . ' ' . my_caption('payment_type_one_time');?></label>
				</div>
			  </div>
			  <div class="col-lg-6">
				<label>&nbsp;</label>
				<div class="custom-control custom-checkbox mt-3">
				  <?php
				    (array_key_exists('paystack_recurring_enabled', $payment_setting_array)) ? $checked = $payment_setting_array['paystack_recurring_enabled'] : $checked = 0;
				    (set_value('addons_pg_paystack_recurring') != '') ? $checked = set_value('addons_pg_paystack_recurring') : null;
					$data = array(
					  'name' => 'addons_pg_paystack_recurring',
					  'id' => 'addons_pg_paystack_recurring',
					  'value' => '1',
					  'checked' => $checked,
					  'class' => 'custom-control-input'
					);
					echo form_checkbox($data);
				  ?>
				  <label class="custom-control-label" for="addons_pg_paystack_recurring"><?php echo my_caption('global_enabled'). ' '. my_caption('payment_type_recurring');?></label>
				</div>
			  </div>
			</div>
			<div class="row form-group mt-4 mb-4">
			  <div class="col-lg-6">
			    <label><span class="text-danger">*</span> <?=my_caption('addons_payment_paystack_secret_key')?></label>
				<?php
				  (array_key_exists('paystack_secret_key', $payment_setting_array)) ? $addons_pg_paystack_secret_key = $payment_setting_array['paystack_secret_key'] : $addons_pg_paystack_secret_key = '';
				  (set_value('addons_pg_paystack_secret_key') != '') ? $addons_pg_paystack_secret_key = set_value('addons_pg_paystack_secret_key') : null;
				  ($this->config->item('my_demo_mode')) ? $addons_pg_paystack_secret_key = '********' : null;
				  $data = array(
				    'name' => 'addons_pg_paystack_secret_key',
					'id' => 'addons_pg_paystack_secret_key',
					'value' => $addons_pg_paystack_secret_key,
					'class' => 'form-control'
				  );
				  echo form_input($data);
				  echo form_error('addons_pg_paystack_secret_key', '<small class="text-danger">', '</small>');
				?>
			  </div>
			  <div class="col-lg-6">
			    <label><span class="text-danger">*</span> <?=my_caption('addons_payment_paystack_public_key')?></label>
				<?php
				  (array_key_exists('paystack_public_key', $payment_setting_array)) ? $addons_pg_paystack_public_key = $payment_setting_array['paystack_public_key'] : $addons_pg_paystack_public_key = '';
				  (set_value('addons_pg_paystack_public_key') != '') ? $addons_pg_paystack_public_key = set_value('addons_pg_paystack_public_key') : null;
				  $data = array(
				    'name' => 'addons_pg_paystack_public_key',
					'id' => 'addons_pg_paystack_public_key',
					'value' => $addons_pg_paystack_public_key,
					'class' => 'form-control'
				  );
				  echo form_input($data);
				  echo form_error('addons_pg_paystack_public_key', '<small class="text-danger">', '</small>');
				?>
			  </div>
			</div>
			<div class="row form-group mt-4 mb-4">
			  <div class="col-lg-6">
			    <label><span class="text-danger">*</span> <?=my_caption('payment_paypal_webhook_url')?></label>
				<p class="mt-2"><u><?=base_url() . 'webhook/paystack/'?></u></p>
			  </div>
			</div>
		  </div>


		  <div id="block_yoomoney">
		    <div class="row form-group mb-4">
			  <div class="col-lg-6">
			    <label><b><?=my_caption('addons_payment_yoomoney_setting')?></b></label>
				<div class="custom-control custom-checkbox mt-3">
				  <?php
				    (array_key_exists('yoomoney_one_time_enabled', $payment_setting_array)) ? $checked = $payment_setting_array['yoomoney_one_time_enabled'] : $checked = 0;
				    (set_value('addons_pg_yoomoney_one_time') != '') ? $checked = set_value('addons_pg_yoomoney_one_time') : null;
				      $data = array(
						'name' => 'addons_pg_yoomoney_one_time',
						'id' => 'addons_pg_yoomoney_one_time',
						'value' => '1',
						'checked' => $checked,
						'class' => 'custom-control-input'
					  );
					  echo form_checkbox($data);
				  ?>
				  <label class="custom-control-label" for="addons_pg_yoomoney_one_time"><?php echo my_caption('global_enabled') . ' ' . my_caption('payment_type_one_time');?></label>
				</div>
			  </div>
			  <div class="col-lg-6">
				<label>&nbsp;</label>
				<div class="custom-control custom-checkbox mt-3">
				  <?php
				    (array_key_exists('yoomoney_recurring_enabled', $payment_setting_array)) ? $checked = $payment_setting_array['yoomoney_recurring_enabled'] : $checked = 0;
				    (set_value('addons_pg_yoomoney_recurring') != '') ? $checked = set_value('addons_pg_yoomoney_recurring') : null;
					$data = array(
					  'name' => 'addons_pg_yoomoney_recurring',
					  'id' => 'addons_pg_yoomoney_recurring',
					  'value' => '1',
					  'checked' => $checked,
					  'class' => 'custom-control-input'
					);
					echo form_checkbox($data);
				  ?>
				  <label class="custom-control-label" for="addons_pg_yoomoney_recurring"><?php echo my_caption('global_enabled'). ' '. my_caption('payment_type_recurring');?></label>
				</div>
			  </div>
			</div>
			<div class="row form-group mt-4 mb-4">
			  <div class="col-lg-6">
			    <label><span class="text-danger">*</span> <?=my_caption('addons_payment_yoomoney_shop_id')?></label>
				<?php
				  (array_key_exists('yoomoney_shop_id', $payment_setting_array)) ? $addons_payment_yoomoney_shop_id = $payment_setting_array['yoomoney_shop_id'] : $addons_payment_yoomoney_shop_id = '';
				  (set_value('addons_payment_yoomoney_shop_id') != '') ? $addons_payment_yoomoney_shop_id = set_value('addons_payment_yoomoney_shop_id') : null;
				  ($this->config->item('my_demo_mode')) ? $addons_payment_yoomoney_shop_id = '********' : null;
				  $data = array(
				    'name' => 'addons_pg_yoomoney_shop_id',
					'id' => 'addons_pg_yoomoney_shop_id',
					'value' => $addons_payment_yoomoney_shop_id,
					'class' => 'form-control'
				  );
				  echo form_input($data);
				  echo form_error('addons_pg_yoomoney_shop_id', '<small class="text-danger">', '</small>');
				?>
			  </div>
			  <div class="col-lg-6">
			    <label><span class="text-danger">*</span> <?=my_caption('addons_payment_yoomoney_secret_key')?></label>
				<?php
				  (array_key_exists('yoomoney_secret_key', $payment_setting_array)) ? $addons_payment_yoomoney_secret_key = $payment_setting_array['yoomoney_secret_key'] : $addons_payment_yoomoney_secret_key = '';
				  (set_value('addons_payment_yoomoney_secret_key') != '') ? $addons_payment_yoomoney_secret_key = set_value('addons_payment_yoomoney_secret_key') : null;
				  $data = array(
				    'name' => 'addons_pg_yoomoney_secret_key',
					'id' => 'addons_pg_yoomoney_secret_key',
					'value' => $addons_payment_yoomoney_secret_key,
					'class' => 'form-control'
				  );
				  echo form_input($data);
				  echo form_error('addons_pg_yoomoney_secret_key', '<small class="text-danger">', '</small>');
				?>
			  </div>
			</div>
			<div class="row form-group mt-4 mb-4">
			  <div class="col-lg-6">
			    <label><span class="text-danger">*</span> <?=my_caption('payment_paypal_webhook_url')?></label>
				<p class="mt-2"><u><?=base_url() . 'webhook/yoomoney/'?></u></p>
			  </div>
			</div>
		  </div>

		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <?php
			    $data = array(
				  'type' => 'submit',
				  'name' => 'btn_change',
				  'id' => 'btn_change',
				  'value' => my_caption('global_save_changes'),
				  'class' => 'btn btn-primary mr-2'
			    );
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