<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$tc_array = json_decode($setting->terms_conditions, TRUE);

?>
<!DOCTYPE html>
<html lang="en">

<?php my_load_view($this->setting->theme, 'Auth/header');?>

<body class="bg-gradient-primary">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0 min-height-880">
            <div class="row mb-5 mt-5">
			  <div class="col-lg-10 offset-1">
			    <h3 class="ml-4"><?=my_esc_html($tc_array['title'])?></h3>
			  </div>
			</div>
			<hr>
            <div class="row mt-5">
			  <div class="col-lg-10 offset-1 mb-5">
			    <p class="ml-4"><?=my_esc_html($tc_array['body'])?></p>
			  </div>
			</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php my_load_view($setting->theme, 'Auth/footer')?>
</body>

</html>