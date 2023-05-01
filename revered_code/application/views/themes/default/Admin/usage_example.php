<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Usage Example</h1>

  <div class="row">
    <div class="col-lg-6">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary">Accessible Only when user's role is Super Admin or Admin.</h6>
        </div>
        <div class="card-body">
		  <p>
		    <code>
			if (my_check_role('Super Admin') || my_check_role('Admin')) {<br>
				<?=str_repeat('&nbsp;', 4)?>echo 'Welcome to Admin Area.';<br>
			}<br>
			else {<br>
			    <?=str_repeat('&nbsp;', 4)?>echo 'You are not allowed to enter this area.';<br>
			}
			</code>
		  </p>
		</div>
      </div>
	</div>
    <div class="col-lg-6">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary">Accessible Only when user has the permission of Database Backup.</h6>
        </div>
        <div class="card-body">
		  <p>
		    <code>
		    if (my_check_permission('Database Backup')) {<br>
			   <?=str_repeat('&nbsp;', 4)?>echo 'Please backup your database.';<br>
			}<br>
			else { <br>
			    <?=str_repeat('&nbsp;', 4)?>echo 'You have no enough permission to perform this operation.';<br>
			}
		    </code>
		  </p>
		</div>
      </div>
	</div>
  </div>
  
  <div class="row">
    <div class="col-lg-6">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary">Accessible Only when user signed in.</h6>
        </div>
        <div class="card-body">
		  <p>
		    <code>
		    if ($_SESSION['user_ids']) {<br>
			   <?=str_repeat('&nbsp;', 4)?>echo 'Welcome Back!';<br>
			}<br>
			else { <br>
			    <?=str_repeat('&nbsp;', 4)?>header("Location: signin.php");<br>
			}
		    </code>
		  </p>
		</div>
      </div>
	</div>
    <div class="col-lg-6">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary">Accessible Only for Super Admin.</h6>
        </div>
        <div class="card-body">
		  <p>
		    <code>
		    if ($_SESSION['is_admin']) {<br>
			   <?=str_repeat('&nbsp;', 4)?>echo 'Welcome to Super Admin Area.';<br>
			}<br>
			else { <br>
			    <?=str_repeat('&nbsp;', 4)?>echo 'You are not allowed to enter this area.';<br>
			}
		    </code>
		  </p>
		</div>
      </div>
	</div>
  </div>

  <div class="row">
    <div class="col-lg-6">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary">Check whether a subscription is expired for a signed-in user.</h6>
        </div>
        <div class="card-body">
		  <p>
		    <code>
		    if (my_check_subscription_by_item('payment_item_ids')) {<br>
			   <?=str_repeat('&nbsp;', 4)?>echo 'Your subscription is active.';<br>
			}<br>
			else { <br>
			    <?=str_repeat('&nbsp;', 4)?>echo 'Your subscription is expired.';<br>
			}
		    </code>
		  </p>
		</div>
      </div>
	</div>
  </div>
  
</div>
<?php my_load_view($this->setting->theme, 'footer')?>