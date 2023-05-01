<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'Front/header')?>
      <section class="section-header bg-primary text-white pb-9 pb-lg-13 mb-4 mb-lg-6">
	    <div class="container">
		  <div class="row justify-content-center">
		    <div class="col-12 col-md-8 text-center">
			  <h1 class="display-2 mb-3"><?=my_caption('front_contact_form_title')?></h1>
			  <p class="lead"><?=my_caption('front_contact_form_slogan')?></p>
			</div>
		  </div>
		</div>
		<div class="pattern bottom"></div>
	  </section>
      <div class="section section-lg pt-0">
	    <div class="container mt-n8 mt-lg-n13 z-2">
		  <div class="row justify-content-center">
		    <div class="col-12">
			  <div class="card border-light shadow-soft p-2 p-md-4 p-lg-5">
			    <div class="card-body">
				  <?php
				    echo form_open(base_url('home/contact_action/'), ['method'=>'POST']);
					my_load_view($this->setting->theme, 'Front/alert_part');
				  ?>
				    <div class="row">
					  <div class="col-12 col-md-6 mb-3">
					    <div class="form-group">
						  <label class="form-label text-dark" for="contact_name"><?=my_caption('front_contact_form_name')?> <span class="text-danger">*</span></label>
						  <div class="input-group">
						    <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fas fa-user-alt"></i></span>
							</div>
						    <?php
						      $data = array(
							    'name' => 'contact_name',
							    'id' => 'contact_name',
							    'value' => set_value('contact_name'),
							    'class' => 'form-control',
								'placeholder' => my_caption('front_contact_form_name')
							  );
							  echo form_input($data);
						    ?>
						  </div>
                          <?php echo form_error('contact_name', '<small class="text-danger">', '</small>');?>						  
                        </div>
                      </div>
                      <div class="col-12 col-md-6 mb-3">
                        <div class="form-group">
                          <label class="form-label text-dark" for="contact_catalog"><?=my_caption('front_contact_form_catalog')?> <span class="text-danger">*</span></label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-align-justify"></i></span>
                            </div>
							<?php
							  (!empty(set_value('contact_catalog'))) ? $contact_catalog = set_value('contact_catalog') : $contact_catalog = 0;
							  $data = array(
							    'class' => 'custom-select'
							  );
							  echo form_dropdown('contact_catalog', my_get_catalog('support_contact_form'), $contact_catalog, $data);
							?>
                          </div>
						  <?php echo form_error('contact_catalog', '<small class="text-danger">', '</small>'); ?>
                        </div>
                      </div>
                      <div class="col-12 col-md-6 mb-3">
                        <div class="form-group">
                          <label class="form-label text-dark" for="contact_email"><?=my_caption('front_contact_form_email')?> <span class="text-danger">*</span></label>
						  <div class="input-group">
						    <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
							</div>
						    <?php
						      $data = array(
							    'name' => 'contact_email',
							    'id' => 'contact_email',
							    'value' => set_value('contact_email'),
							    'class' => 'form-control',
								'placeholder' => my_caption('front_contact_form_email')
							  );
							  echo form_input($data);
						    ?>
						  </div>
						  <?php echo form_error('contact_email', '<small class="text-danger">', '</small>'); ?>
                        </div>
                      </div>
                      <div class="col-12 col-md-6 mb-4">
                        <div class="form-group">
                          <label class="form-label text-dark" for="contact_phone"><?=my_caption('front_contact_form_phone')?></label>
						  <div class="input-group">
						    <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fas fa-phone-square-alt"></i></span>
							</div>
						    <?php
						      $data = array(
							    'name' => 'contact_phone',
							    'id' => 'contact_phone',
							    'value' => set_value('contact_phone'),
							    'class' => 'form-control',
								'placeholder' => my_caption('front_contact_form_phone')
							  );
							  echo form_input($data);
						    ?>
						  </div>
						  <?php echo form_error('contact_phone', '<small class="text-danger">', '</small>'); ?>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label class="form-label text-dark" for="contact_message"><?=my_caption('front_contact_form_message')?> <span class="text-danger">*</span></label>
						  <?php
						    $data = array(
							  'name' => 'contact_message',
							  'id' => 'contact_message',
							  'value' => set_value('contact_message'),
							  'class' => 'form-control',
							  'rows' => 8,
							  'placeholder' => my_caption('front_contact_form_message_placeholder')
							);
							echo form_textarea($data);
							echo form_error('contact_message', '<small class="text-danger">', '</small>');
						  ?>
                        </div>
					  </div>
					  <?php
						if ($this->setting->recaptcha_enabled) { 
						  $recaptcha_array = json_decode($this->setting->recaptcha_detail, TRUE);
					  ?>
						  <div class="col-12 mt-3">
					        <div class="form-group">
					          <div class="g-recaptcha" data-sitekey="<?php echo my_esc_html($recaptcha_array['site_key']); ?>"></div>
						      <?php echo form_error('g-recaptcha-response', '<small class="text-danger">', '</small>');?>
					        </div>
						  </div>
					  <?php
					      echo '<div class="col-12">';
					    }
						else {
							echo '<div class="col-12 text-center">';
						}
					  ?>
						<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-secondary mt-3 animate-up-2"><span class="mr-2"><i class="fas fa-paper-plane"></i></span><?=my_caption('front_contact_form_submit')?></button>
                      </div>
                    </div>
                  <?php echo form_close(); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<?php my_load_view($this->setting->theme, 'Front/social_part')?>
<?php my_load_view($this->setting->theme, 'Front/footer')?>