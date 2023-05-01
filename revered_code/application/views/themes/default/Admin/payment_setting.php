<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('my_basic_data');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('payment_setting')?></h1>

  <div class="row">
    <div class="col-lg-8">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('payment_setting')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('admin/payment_setting_action/'), ['method'=>'POST']);
			$payment_setting_array = json_decode($this->setting->payment_setting, TRUE);
			$invoice_setting_array = json_decode($this->setting->invoice_setting, TRUE);
		  ?>
		  <input type="hidden" id="act" name="act" value="payment_setting">
		  <div class="row form-group mb-4">
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_gateway_type')?></label>
			  <?php
			    (set_value('payment_type') == '') ? $payment_type = $payment_setting_array['type'] : $payment_type  = set_value('payment_type');
			    $options = array(
				  'disabled' => my_caption('payment_type_disabled'),
				  'sandbox' => my_caption('payment_type_sandbox'),
				  'live' => my_caption('payment_type_live'),
				);
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
			    echo form_dropdown('payment_type', $options, $payment_type, $data);
			    echo form_error('payment_type', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_feature')?></label>
			  <?php
			    (set_value('payment_feature') == '') ? $payment_feature = $payment_setting_array['feature'] : $payment_feature  = set_value('payment_feature');
			    $options = array(
				  'both' => my_caption('payment_feature_both'),
				  'one-time' => my_caption('payment_feature_one_time'),
				  'subscription' => my_caption('payment_feature_subscription'),
				);
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
			    echo form_dropdown('payment_feature', $options, $payment_feature, $data);
			    echo form_error('payment_feature', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_tax_rate')?></label>
			  <?php
			    (!empty($payment_setting_array['tax_rate'])) ? $payment_tax_rate = $payment_setting_array['tax_rate'] : $payment_tax_rate = 0;
			    (set_value('payment_tax_rate') != '') ? $payment_tax_rate = set_value('payment_tax_rate') : null;
			    $data = array(
				  'name' => 'payment_tax_rate',
				  'id' => 'payment_tax_rate',
				  'value' => $payment_tax_rate,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('payment_tax_rate', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <hr class="dotted">
		  <div class="row form-group mb-2">
		    <div class="col-lg-6">
			  <label><b><a name="Stripe"><?=my_caption('payment_stripe')?></a></b></label>
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('stripe_one_time_enabled') == '') ? $checked = $payment_setting_array['stripe_one_time_enabled'] : $checked = set_value('stripe_one_time_enabled');
				  $data = array(
				    'name' => 'stripe_one_time_enabled',
					'id' => 'stripe_one_time_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="stripe_one_time_enabled"><?php echo my_caption('global_enabled') . ' ' . my_caption('payment_type_one_time');?></label>
              </div>
			</div>
		    <div class="col-lg-6">
			  <label>&nbsp;</label>
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('stripe_recurring_enabled') == '') ? $checked = $payment_setting_array['stripe_recurring_enabled'] : $checked = set_value('stripe_recurring_enabled');
				  $data = array(
				    'name' => 'stripe_recurring_enabled',
					'id' => 'stripe_recurring_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="stripe_recurring_enabled"><?php echo my_caption('global_enabled'). ' '. my_caption('payment_type_recurring');?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mt-4 mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_stripe_publishable_key')?></label>
			  <?php
			    (set_value('stripe_publishable_key') == '') ? $stripe_publishable_key = $payment_setting_array['stripe_publishable_key'] : $stripe_publishable_key = set_value('stripe_publishable_key');
			    $data = array(
				  'name' => 'stripe_publishable_key',
				  'id' => 'stripe_publishable_key',
				  'value' => $stripe_publishable_key,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('stripe_publishable_key', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_stripe_secret_key')?></label>
			  <?php
			    (set_value('stripe_secret_key') == '') ? $stripe_secret_key = $payment_setting_array['stripe_secret_key'] : $stripe_secret_key = set_value('stripe_secret_key');
			    ($this->config->item('my_demo_mode')) ? $stripe_secret_key = '********' : null;
				$data = array(
				  'name' => 'stripe_secret_key',
				  'id' => 'stripe_secret_key',
				  'value' => $stripe_secret_key,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('stripe_secret_key', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mt-4 mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_stripe_signing_secret')?></label>
			  <?php
			    (set_value('stripe_signing_secret') == '') ? $stripe_signing_secret = $payment_setting_array['stripe_signing_secret'] : $stripe_signing_secret = set_value('stripe_signing_secret');
			    ($this->config->item('my_demo_mode')) ? $stripe_signing_secret = '********' : null;
				$data = array(
				  'name' => 'stripe_signing_secret',
				  'id' => 'stripe_signing_secret',
				  'value' => $stripe_signing_secret,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('stripe_signing_secret', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mt-4 mb-4">
			<div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_stripe_webhook_endpoint_url')?></label>
			  <p class="mt-2"><u><?=base_url() . 'webhook/stripe/'?></u></p>
			</div>
		  </div>
		  <div class="row form-group mt-4 mb-4">
			<div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_webhook_required_event')?> (2 events in total)</label>
			  <p class="mt-2">checkout.session.completed , customer.subscription.updated</p>
			</div>
		  </div>
		  <hr class="dotted">
		  <div class="row form-group mb-2">
		    <div class="col-lg-6">
			  <label><b><a name="PayPal"><?=my_caption('payment_paypal')?></a></b></label>
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('paypal_one_time_enabled') == '') ? $checked = $payment_setting_array['paypal_one_time_enabled'] : $checked = set_value('paypal_one_time_enabled');
				  $data = array(
				    'name' => 'paypal_one_time_enabled',
					'id' => 'paypal_one_time_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="paypal_one_time_enabled"><?php echo my_caption('global_enabled'). ' '. my_caption('payment_type_one_time');?></label>
              </div>
			</div>
		    <div class="col-lg-6">
			  <label>&nbsp;</label>
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('paypal_recurring_enabled') == '') ? $checked = $payment_setting_array['paypal_recurring_enabled'] : $checked = set_value('paypal_recurring_enabled');
				  $data = array(
				    'name' => 'paypal_recurring_enabled',
					'id' => 'paypal_recurring_enabled',
					'value' => '1',
					'checked' => $checked,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="paypal_recurring_enabled"><?php echo my_caption('global_enabled'). ' '. my_caption('payment_type_recurring');?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mt-4 mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_paypal_client_id')?></label>
			  <?php
			    (set_value('paypal_client_id') == '') ? $paypal_client_id = $payment_setting_array['paypal_client_id'] : $paypal_client_id = set_value('paypal_client_id');
			    $data = array(
				  'name' => 'paypal_client_id',
				  'id' => 'paypal_client_id',
				  'value' => $paypal_client_id,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('paypal_client_id', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_paypal_secret')?></label>
			  <?php
			    (set_value('paypal_secret') == '') ? $paypal_secret = $payment_setting_array['paypal_secret'] : $paypal_secret = set_value('paypal_secret');
			    ($this->config->item('my_demo_mode')) ? $paypal_secret = '********' : null;
				$data = array(
				  'name' => 'paypal_secret',
				  'id' => 'paypal_secret',
				  'value' => $paypal_secret,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('paypal_secret', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mt-4 mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_paypal_webhook_id')?></label>
			  <?php
			    (set_value('paypal_webhook_id') == '') ? $paypal_webhook_id = $payment_setting_array['paypal_webhook_id'] : $paypal_webhook_id = set_value('paypal_webhook_id');
			    ($this->config->item('my_demo_mode')) ? $paypal_webhook_id = '********' : null;
				$data = array(
				  'name' => 'paypal_webhook_id',
				  'id' => 'paypal_webhook_id',
				  'value' => $paypal_webhook_id,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('paypal_webhook_id', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mt-4 mb-4">
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_paypal_webhook_url')?></label>
			  <p class="mt-2"><u><?=base_url() . 'webhook/paypal/'?></u></p>
			</div>
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_paypal_live_return_url')?></label>
			  <p class="mt-2"><u><?=base_url() . 'user/pay_now/'?></u></p>
			</div>
		  </div>
		  <div class="row form-group mt-4 mb-4">
			<div class="col-lg-12">
			  <label><span class="text-danger">*</span> <?=my_caption('payment_webhook_required_event')?> (2 events in total)</label>
			  <p class="mt-2">Checkout order approved , Payment sale completed</p>
			</div>
		  </div>
		  <hr class="dotted">
		  <div class="row form-group">
		    <div class="col-lg-12">
			  <label><b><?=my_caption('payment_invoice_setting_title')?></b></label>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('payment_invoice_setting_format')?></label>
			  <?php
			    (array_key_exists('invoice_format', $invoice_setting_array)) ? $invoice_format = $invoice_setting_array['invoice_format'] : $invoice_format = 'pdf';
			    (set_value('payment_invoice_format') != '') ? $invoice_format  = set_value('payment_invoice_format') : null;
			    $options = array(
				  'pdf' => 'PDF',
				  'html' => 'HTML'
				);
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
			    echo form_dropdown('payment_invoice_format', $options, $invoice_format, $data);
			    echo form_error('payment_invoice_format', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('payment_invoice_setting_company')?></label>
			  <?php
			    (set_value('payment_invoice_setting_company') == '') ? $payment_invoice_setting_company = $invoice_setting_array['company_name'] : $payment_invoice_setting_company = set_value('payment_invoice_setting_company');
			    $data = array(
				  'name' => 'payment_invoice_setting_company',
				  'id' => 'payment_invoice_setting_company',
				  'value' => $payment_invoice_setting_company,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('payment_invoice_setting_company', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><?=my_caption('payment_invoice_setting_company_number')?></label>
			  <?php
			    (!empty($invoice_setting_array['company_number'])) ? $payment_company_number = $invoice_setting_array['company_number'] : $payment_company_number = '';
			    (set_value('payment_company_number') != '') ? $payment_company_number = set_value('payment_company_number') : null;
				$data = array(
				  'name' => 'payment_company_number',
				  'id' => 'payment_company_number',
				  'value' => $payment_company_number,
				  'class' => 'form-control'
				);
				echo form_input($data);
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
			<div class="col-lg-6">
			  <label><?=my_caption('payment_invoice_setting_tax_number')?></label>
			  <?php
			    (!empty($invoice_setting_array['tax_number'])) ? $payment_tax_number = $invoice_setting_array['tax_number'] : $payment_tax_number = '';
			    (set_value('payment_tax_number') != '') ? $payment_tax_number = set_value('payment_tax_number') : null;
				$data = array(
				  'name' => 'payment_tax_number',
				  'id' => 'payment_tax_number',
				  'value' => $payment_tax_number,
				  'class' => 'form-control'
				);
				echo form_input($data);
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><?=my_caption('payment_invoice_setting_tel')?></label>
			  <?php
			    (set_value('payment_invoice_setting_tel') == '') ? $payment_invoice_setting_tel = $invoice_setting_array['phone'] : $payment_invoice_setting_tel = set_value('payment_invoice_setting_tel');
				$data = array(
				  'name' => 'payment_invoice_setting_tel',
				  'id' => 'payment_invoice_setting_tel',
				  'value' => $payment_invoice_setting_tel,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('payment_invoice_setting_tel', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('payment_invoice_setting_address_line_1')?></label>
			  <?php
			    (set_value('payment_invoice_setting_address_line_1') == '') ? $payment_invoice_setting_address_line_1 = $invoice_setting_array['address_line_1'] : $payment_invoice_setting_address_line_1 = set_value('payment_invoice_setting_address_line_1');
			    $data = array(
				  'name' => 'payment_invoice_setting_address_line_1',
				  'id' => 'payment_invoice_setting_address_line_1',
				  'value' => $payment_invoice_setting_address_line_1,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('payment_invoice_setting_address_line_1', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><?=my_caption('payment_invoice_setting_address_line_2')?></label>
			  <?php
			    (set_value('payment_invoice_setting_address_line_2') == '') ? $payment_invoice_setting_address_line_2 = $invoice_setting_array['address_line_2'] : $payment_invoice_setting_address_line_2 = set_value('payment_invoice_setting_address_line_2');
				$data = array(
				  'name' => 'payment_invoice_setting_address_line_2',
				  'id' => 'payment_invoice_setting_address_line_2',
				  'value' => $payment_invoice_setting_address_line_2,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('payment_invoice_setting_address_line_2', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-lg-12 text-right">
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