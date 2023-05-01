<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
	  <div class="card border-left-primary shadow h-100 py-2">
	    <div class="card-body">
		  <div class="row no-gutters align-items-center">
		    <div class="col mr-2">
			  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"><a href="<?=base_url('admin/list_user')?>"><?=my_caption('dashboard_users_amount')?></a></div>
			  <div class="h5 mb-0 font-weight-bold text-gray-800"><?=my_esc_html($users_amount)?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-comments fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
	
	<div class="col-xl-3 col-md-6 mb-4">
	  <div class="card border-left-warning shadow h-100 py-2">
	    <div class="card-body">
		  <div class="row no-gutters align-items-center">
		    <div class="col mr-2">
			  <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"><a href="<?=base_url('admin/list_user/pending')?>"><?=my_caption('dashboard_pending_users')?></a></div>
			  <div class="h5 mb-0 font-weight-bold text-gray-800"><?=my_esc_html($user_pending_amount)?></div>
			</div>
			<div class="col-auto">
			  <i class="fas fa-comments fa-2x text-gray-300"></i>
			</div>
		  </div>
        </div>
      </div>
    </div>
	
	<div class="col-xl-3 col-md-6 mb-4">
	  <div class="card border-left-info shadow h-100 py-2">
	    <div class="card-body">
		  <div class="row no-gutters align-items-center">
		    <div class="col mr-2">
			  <div class="text-xs font-weight-bold text-info text-uppercase mb-1"><a href="<?=base_url('admin/list_user/today')?>"><?=my_caption('dashboard_signup_today')?></a></div>
			  <div class="h5 mb-0 font-weight-bold text-gray-800"><?=my_esc_html($user_today_amount)?></div>
			</div>
			<div class="col-auto">
			  <i class="fas fa-comments fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1"><a href="<?=base_url('admin/list_online')?>"><?=my_caption('dashboard_online_users')?></a></div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?=my_esc_html($user_online_amount)?></div>
              </div>
            <div class="col-auto">
              <i class="fas fa-comments fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div> 
  </div>  
  
  <div class="row">
    <div class="col-xl-8">
	  <div class="card shadow mb-4 min-height-666">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('dashboard_recent_week_chart')?></h6>
        </div>
        <div class="card-body">
          <div class="chart-area">
		    <canvas id="admin_dashboard_chart_signup" class="mt-4 mb-5"></canvas>
			<input type="hidden" name="signup_last_six_days_date" id="signup_last_six_days_date" value="<?=my_esc_html($signup_last_six_days_date)?>">
			<input type="hidden" name="signup_last_six_days_amount" id="signup_last_six_days_amount" value="<?=my_esc_html($signup_last_six_days_amount)?>">
		  </div>
        </div>
      </div>
	</div>
	
	<div class="col-xl-4">
	  <div class="card shadow mb-4 min-height-666">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><a href="<?=base_url('admin/list_user')?>"><?=my_caption('dashboard_recent_signup')?></a></h6>
		</div>
        <div class="card-body">
		  <table class="table table-hover borderless">
		    <tbody>
            <?php
			foreach ($rs_user as $row) {
				($this->config->item('my_demo_mode')) ? $email_address = '*****' . '' . substr($row->email_address, 5) : $email_address = $row->email_address;
				$img = '<img class="img-profile rounded-circle rounded-circle-small mr-3" src="' . base_url('upload/avatar/' . $row->avatar) . '?dummy=' . random_string('alnum', 6) . '">';
				echo '<tr><td class="align-middle"><a href="' . base_url('admin/edit_user/' . $row->ids) . '" class="text-secondary">' . $img . $email_address . '<label class="badge badge-light text-gray-500 ml-3">' . my_conversion_from_server_to_local_time($row->created_time, $this->user_timezone, $this->user_dtformat) . '</label>' . '</a></td></tr>';
			}
			?>
			</tbody>
		  </table>
        </div>
      </div>
    </div>
  </div>