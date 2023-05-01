<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_esc_html($title)?></h6>
        </div>
        <div class="card-body">
		  <div class="row">
		    <div class="col-lg-12">
			  <?php
			    $balance_array = json_decode($rs_user->balance, 1);
			    foreach ($balance_array as $key => $value) {
					echo strtoupper($key) . ' : ' . $value . '<br>';
				}
			  ?>
		    </div>
		  </div>
		</div>
	  </div>