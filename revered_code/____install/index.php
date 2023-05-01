<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
?>
<!doctype html>
<html lang="en">

  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?=$html_title?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <link href="https://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/gsdk-bootstrap-wizard.css" rel="stylesheet" />
  </head>
  
  <body>
    <div class="image-container set-full-height" style="background-image: url('assets/img/wizard-boat.jpg')">
	  <div class="container">
	    <div class="row">
		  <div class="col-sm-8 col-sm-offset-2">
            <div class="wizard-container">
			  <div class="card wizard-card" data-color="azzure" id="wizard">
			    <form method="post" id="installation_form" name="installation_form">
				<?php
				  $current_url = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				  $base_url = str_ireplace('install/index.php', '', $current_url);
				?>
				<input type="hidden" name="base_url" id="base_url" value="<?=$base_url?>">
				<div class="wizard-header">
                  <h3>
				    <b><?=$title?><b>
					<br>
                    <small><?=$notification?></small>
					<br>
                  </h3>
               	</div>
				<div class="wizard-navigation">
				  <ul>
	                <li><a href="#S1" data-toggle="tab"><?=$step_title_1?></a></li>
	                <li><a href="#S2" data-toggle="tab"><?=$step_title_2?></a></li>
	                <li><a href="#S3" data-toggle="tab"><?=$step_title_3?></a></li>
	              </ul>
				</div>
                <div class="tab-content">
				  <div class="tab-pane" id="S1">
				    <div class="row">
					  <div class="col-lg-6 col-lg-offset-1 text-left">
					    <h5>PHP Version and Extensions :</h5>
					  </div>
					</div>
				    <div class="row">
					  <div class="col-lg-10 col-lg-offset-1 text-left">
					    <table class="table table-hover">
						  <tbody>
						    <tr>
							  <td width="50%" class="text-left">PHP Version ( >= <?=$required_php_version?> )</td>
							  <td class="text-right">
							    <?php
								  $check_requirement_result = TRUE;
								  if (detect_php_version()) {
									  $result = '<i class="fa fa-check text-success"></i>';
								  }
								  else {
									  $result = '<i class="fa fa-times text-danger"></i>';
									  $check_requirement_result = FALSE;
								  }
								  echo $result;
								?>
							  </td>
							</tr>
							<?php
							foreach ($detect_compentent_list as $compentent) {
								$detect_result = detect_compentent($compentent);
								if ($detect_result == 'UnKnown') { //unknow
									$result = '<i class="fa fa-question text-warning"></i>';
								}
								elseif ($detect_result == 'Yes') {
									$result = '<i class="fa fa-check text-success"></i>';
								}
								else {
									$result = '<i class="fa fa-times text-danger"></i>';
									$check_requirement_result = FALSE;
								}
							?>
						    <tr>
							  <td class="text-left"><?=$compentent?></td>
							  <td class="text-right"><?=$result?></td>
							</tr>
							<?php } ?>
						  </tbody>
						</table>
					  </div>
					</div>
				    <div class="row">
					  <div class="col-lg-6 col-lg-offset-1 text-left">
					    <h5>Files and Diretory Writable Permissions :</h5>
					  </div>
					</div>
				    <div class="row">
					  <div class="col-lg-10 col-lg-offset-1 text-left">
					    <table class="table table-hover">
						  <tbody>
						    <?php
							if (!file_exists('../.htaccess')) {
								$check_requirement_result = FALSE;
							?>
						    <tr>
							  <td class="text-left">
							    The '.htaccess' file in the script's root directory is missed, <a href="https://support.cyberbukit.com/help-center/articles/1/7/17/htaccess-file-is-missed" target="_blank">see solution<a>
							  </td>
							  <td class="text-right"><i class="fa fa-times text-danger"></i></td>
							</tr>
							<?php
							}
							foreach ($detect_directory_list as $directory) {
								if (is_writable('../' . $directory)) {
									$result = '<i class="fa fa-check text-success"></i>';
								}
								else {
									$result = '<i class="fa fa-times text-danger"></i>';
									$check_requirement_result = FALSE;
								}
							?>
						    <tr>
							  <td class="text-left">/<?=$directory?></td>
							  <td class="text-right"><?=$result?></td>
							</tr>
							<?php } ?>
						  </tbody>
						</table>
					  </div>
					</div>
                  </div>
				  <input type="hidden" name="check_requirement_result" id="check_requirement_result" value="<?=$check_requirement_result?>">
                  <div class="tab-pane" id="S2">
				    <div class="row">
					  <div class="col-12">
					    <h4 class="info-text">Database Credentials</h4>
					  </div>
					</div>
					<div class="row">
					  <div class="col-lg-5 col-lg-offset-1">
					    <div class="form-group">
						  <label><span class="text-danger">*</span> Database Host</label>
                          <input type="text" class="form-control" id="db_host" name="db_host" value="localhost" required>
                        </div>
                      </div>
					  <div class="col-lg-5">
					    <div class="form-group">
						  <label><span class="text-danger">*</span> Database Name</label>
                          <input type="text" class="form-control" id="db_name" name="db_name" required>
                        </div>
                      </div>
					</div>
					<div class="row">
					  <div class="col-lg-5 col-lg-offset-1">
					    <div class="form-group">
						  <label><span class="text-danger">*</span> Database Username</label>
                          <input type="text" class="form-control" id="db_username" name="db_username" required>
                        </div>
                      </div>
					  <div class="col-lg-5">
					    <div class="form-group">
						  <label>Database Password</label>
                          <input type="password" class="form-control" id="db_password" name="db_password">
                        </div>
                      </div>
					</div>
					<div class="row">
					  <div class="col-lg-10 col-lg-offset-1">
					    <div class="form-group">
						  <label>Table Prefix (optional)</label>
                          <input type="text" class="form-control" id="db_prefix" name="db_prefix" placeholder="letter, number, underscore only, and different from the database's name">
                        </div>
                      </div>
					</div>
				  </div>
				  <div class="tab-pane" id="S3">
				    <div class="row">
					  <div class="col-lg-10 col-lg-offset-1">
					    <div class="form-group">
						  <label>Envato Purchase Code</label>
                          <input type="text" class="form-control" id="purchase_code" name="purchase_code">
                        </div>
					  </div>
				    </div>
					<div class="row">
					  <div class="col-lg-10 col-lg-offset-1">
					    <span id="alert_text" class="text-danger"></span>
						<span id="success_text" class="text-success">
						  Congratulations, The script is installed successfully!<br>
						  Now, you should :<br>
						  1. Remove the "install" directory.<br>
						  2. <a href="../">Click here</a> to sign in the system (Username:admin, Password:admin).
						</span>
					  </div>
					</div>
                  </div>
                </div>
                <div class="wizard-footer">
                  <div class="pull-right">
                    <input type='button' class='btn btn-next btn-fill btn-info btn-wd btn-sm' name='next' id='next' value='Next' />
                    <input type='button' class='btn btn-finish btn-fill btn-info btn-wd btn-sm' name='finish' id='finish' value='Finish' />
                  </div>
                  <div class="pull-left">
                    <input type='button' class='btn btn-previous btn-fill btn-default btn-wd btn-sm' name='previous' id='previous' value='Previous' />
                  </div>
                  <div class="clearfix"></div>
                </div>
                </form>
              </div>
            </div>
		  </div>
        </div>
      </div>
	</div>
  </body>
  
  <script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
  <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="assets/js/jquery.bootstrap.wizard.js" type="text/javascript"></script>
  <script src="assets/js/gsdk-bootstrap-wizard.js"></script>
  <script src="assets/js/jquery.validate.min.js"></script>
  <script src="assets/js/jquery.blockUI.js"></script>
  <script src="assets/js/app.js"></script>
  
  
</html>