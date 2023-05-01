<?php
  defined('BASEPATH') OR exit('No direct script access allowed'); 
?>
      </div>
	  <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span><?=my_caption('menu_footer_copyright')?> v<?=my_esc_html($this->setting->version)?></span>
          </div>
        </div>
      </footer>
	  <?php
	    $global_caption = my_caption('global_view') . '||';
		$global_caption .= my_caption('global_edit') . '||';	
		$global_caption .= my_caption('global_delete') . '||';
		$global_caption .= my_caption('global_delete') . '||';
		$global_caption .= my_caption('global_not_revert') . '||';
		$global_caption .= my_caption('global_yes') . '||';
		$global_caption .= my_caption('global_no') . '||';
		$global_caption .= my_caption('global_cancel') . '||';
		$global_caption .= my_caption('global_ok');
		$language = get_cookie('site_lang', TRUE);
		if (!$language) {
			$language = $this->config->item('language');
			my_set_language_cookie($language);
		}
	  ?>
	  <input type="hidden" name="global_base_url" id="global_base_url" value="<?=base_url()?>">
	  <input type="hidden" name="global_user_identifier" id="global_user_identifier" value="<?=my_esc_html($_SESSION['user_ids'])?>">
	  <input type="hidden" name="global_site_language" id="global_site_language" value="<?=my_esc_html($language)?>">
	  <input type="hidden" name="global_caption" id="global_caption" value="<?=my_esc_html($global_caption)?>">
	  <input type="hidden" name="timezone_offset" id="timezone_offset" value="<?=my_timezone_offset($this->config->item('time_reference'), $this->user_timezone)?>">
	  <input type="hidden" name="user_dateformat" id="user_dateformat" value="<?=my_esc_html($this->user_date_format)?>">
	  <input type="hidden" name="user_timeformat" id="user_timeformat" value="<?=my_esc_html($this->user_time_format)?>">
    </div>
  </div>
  <a class="scroll-to-top rounded" href="#wrapper">
    <i class="fas fa-angle-up"></i>
  </a>
  <script src="<?=base_url()?>assets/themes/default/vendor/jquery/jquery.min.js"></script>
  <script src="<?=base_url()?>assets/themes/default/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?=base_url()?>assets/themes/default/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="<?=base_url()?>assets/themes/default/vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
  <script src="<?=base_url()?>assets/themes/default/vendor/blockui/jquery.blockUI.js"></script>
  <script src="<?=base_url()?>assets/themes/default/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="<?=base_url()?>assets/themes/default/vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="<?=base_url()?>assets/themes/default/vendor/chart.js/Chart.min.js"></script>
  <script src="<?=base_url()?>assets/themes/default/js/sb-admin-2.min.js"></script>
  <script src="<?=base_url()?>assets/themes/default/vendor/sweetalert2/sweetalert2.min.js"></script>
  <script src="<?=base_url()?>assets/themes/default/vendor/summernote/summernote.min.js"></script>
  <script src="<?=base_url()?>assets/themes/default/vendor/momentjs/moment.min.js"></script>
  <script src="<?=base_url()?>assets/themes/default/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
  <?php if (!empty($this->setting->dashboard_custom_javascript)) { ?>
	<script src="<?=$this->setting->dashboard_custom_javascript?>"></script>
  <?php 
    }
    if ($this->setting->google_analytics_id != '') {
  ?>
  <!-- Global site tag (gtag.js) - Google Analytics -->
    <input type="hidden" id="google_analytics_id" name="google_analytics_id" value="<?=my_esc_html($this->setting->google_analytics_id)?>">
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?=my_esc_html($this->setting->google_analytics_id)?>"></script>
  <?php
	}
  ?>
  <script src="<?=base_url()?>assets/themes/default/js/app.js?v=<?=$this->setting->version?>"></script>
</body>
</html>