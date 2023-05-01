<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<input type="hidden" name="global_caption" id="global_caption" value="">
<input type="hidden" name="global_base_url" id="global_base_url" value="<?=base_url()?>">
<script src="<?=base_url()?>assets/themes/default/vendor/jquery/jquery.min.js"></script>
<script src="<?=base_url()?>assets/themes/default/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url()?>assets/themes/default/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?=base_url()?>assets/themes/default/vendor/blockui/jquery.blockUI.js"></script>
<script src="<?=base_url()?>assets/themes/default/js/sb-admin-2.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php
  //GDPR
  $gdpr_array = json_decode($this->setting->gdpr, TRUE);
  if ($gdpr_array['enabled']) {
?>
  <input type="hidden" id="cookie_message" name="cookie_message" value="<?=my_esc_html($gdpr_array['cookie_message'])?>">
  <input type="hidden" id="cookie_policy_link_text" name="cookie_policy_link_text" value="<?=my_esc_html($gdpr_array['cookie_policy_link_text'])?>">
  <input type="hidden" id="cookie_policy_link" name="cookie_policy_link" value="<?=my_esc_html($gdpr_array['cookie_policy_link'])?>">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/themes/default/vendor/cookieconsent/cookieconsent.min.css" />
  <script src="<?=base_url('assets/themes/default/vendor/cookieconsent/cookieconsent.min.js')?>" data-cfasync="false"></script>
<?php
  }
  if (!empty($this->setting->dashboard_custom_javascript)) {
?>
    <script src="<?=$this->setting->dashboard_custom_javascript?>"></script>
<?php
  }
  if ($this->setting->google_analytics_id != '') {
?>
  <!-- Global site tag (gtag.js) - Google Analytics -->
    <input type="hidden" id="google_analytics_id" name="google_analytics_id" value="<?=my_esc_html($this->setting->google_analytics_id)?>">
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?=my_esc_html($this->setting->google_analytics_id)?>"></script>
<?php } ?>
<script src="<?=base_url()?>assets/themes/default/js/app.js"></script>