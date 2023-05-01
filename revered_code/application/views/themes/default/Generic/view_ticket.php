<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('support_ticket_view')?></h1>
  <?php
    my_load_view($this->setting->theme, 'Generic/show_flash_card');
    if (my_check_permission('Support Management') && $this->router->fetch_class() == 'admin') {
		$view_mode = 'admin';
	}
	else {
		$view_mode = 'user';
	}
	$ticket_setting_array = json_decode($this->setting->ticket_setting, 1);
  ?>
  <div class="row">
    <div class="col-lg-8">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('support_ticket_view')?></h6>
        </div>
        <div class="card-body">
		  <div class="row">
		    <div class="col-lg-10">
			  <h5><?php echo '<b>#' .$rs->id . '</b> ' . $rs->subject?></h5>
			</div>
		  </div>
		  <div class="row">
		    <div class="col-lg-1 mt-3">
			  <div class="circle-with-letter-blue"><b><?=ucfirst(substr($rs->user_fullname, 0, 1))?></b></div>
			</div>
			<div class="col-lg-11 mt-3">
			  <div class="ticket-block-arrow"></div>
			  <div class="ticket-block">
			    <div class="mt-1 ml-2">
				  <?php if ($view_mode == 'admin') { ?>
				    <i class="far fa-trash-alt hand-cursor" onclick="actionQuery('<?=my_caption('support_ticket_notice_delete_ticket')?>', '<?=my_caption('global_not_revert')?>', '<?php echo base_url('admin/ticket_remove/full/') . $rs->ids ;?>', '')"></i>
				  <?php } ?>
				  <small><?php echo '<b>' . $rs->user_fullname . '</b>, ' . my_caption('support_ticket_word_reported') . ' ' . my_conversion_from_server_to_local_time($rs->created_time, $this->user_timezone, $this->user_dtformat);?></small></div>
			    <div class="mt-2 ml-2"><?=my_esc_html($rs->description)?><br></div>
              </div>
			</div>
		  </div>
		  <?php
		    foreach ($rs_follow as $row) {
				($row->source == 'user') ? $style = 'circle-with-letter-blue' : $style = 'circle-with-letter-grey';
		  ?>
		  <div class="row">
		    <div class="col-lg-1 mt-3">
			  <div class="<?=my_esc_html($style)?>"><b><?=ucfirst(substr(my_esc_html($row->user_fullname), 0, 1))?></b></div>
			</div>
			<div class="col-lg-11 mt-3">
			  <div class="ticket-block-arrow"></div>
			  <div class="ticket-block">
			    <div class="mt-1 ml-2">
				  <?php if ($view_mode == 'admin') { ?>
				    <i class="far fa-trash-alt hand-cursor" onclick="actionQuery('<?=my_caption('support_ticket_notice_delete_followup')?>', '<?=my_caption('global_not_revert')?>', '<?php echo base_url('admin/ticket_remove/followup/') . $rs->ids . '/' . $row->ids ;?>', '')"></i> 
				  <?php } ?>
				  <small><?php echo '<b>' . $row->user_fullname . '</b>, ' . my_caption('support_ticket_word_replied') . ' ' . my_conversion_from_server_to_local_time($row->created_time, $this->user_timezone, $this->user_dtformat);?></small></div>
			    <div class="mt-2 ml-2"><?=my_esc_html($row->description)?><br></div>
              </div>
			</div>
		  </div>
		  <?php
		    }
		  ?>
		  <?php 
		    if ($rs->main_status != 0) {
		      echo form_open(base_url($view_mode . '/ticket_view_action/'), ['method'=>'POST']);
		  ?>
		      <input type="hidden" name="ids_father" id="ids_father" value="<?=my_esc_html($rs->ids)?>">
			  <input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
			  <input type="hidden" id="rating" name="rating" value="<?=my_esc_html($rs->rating)?>">
			  <input type="hidden" id="current_method" name="current_method" value="<?=my_esc_html($this->router->fetch_method())?>">
		      <div class="row mt-4">
		        <div class="col-lg-1 mt-3">
			      <div class="circle-with-letter-grey"><b><?=ucfirst(substr($_SESSION['full_name'], 0, 1))?></b></div>
			    </div>
		 	    <div class="col-lg-11 mt-3">
			      <?php
			        (set_value('ticket_reply') == '') ? $ticket_reply = '' : $ticket_reply = set_value('ticket_reply');
			      ?>
				  <input type="hidden" name="ticket_reply_value" id="ticket_reply_value" value="<?=my_esc_html($ticket_reply)?>">
			      <div class="mb-2"><b><?=my_caption('global_reply')?></b></div>
			      <textarea id="ticket_reply" name="ticket_reply"></textarea>
			      <?=form_error('ticket_reply', '<small class="text-danger">', '</small>')?>
			    </div>
		      </div>
		      <div class="row">
			    <div class="col-lg-6 offset-6 text-right">
			      <?php
			        $data = array(
				      'type' => 'button',
				      'name' => 'btn_ticket_close',
				      'id' => 'btn_ticket_close',
				      'value' => my_caption('support_ticket_button_close_ticket'),
				      'class' => 'btn btn-light text-gray-600 mr-2 mt-3',
				      'onclick' => 'actionQuery(\'' . my_caption('support_ticket_notice_close_ticket') . '\', \'' . my_caption('global_not_revert') . '\', \'' . base_url($view_mode . '/ticket_close_action/') . $rs->ids . '\', \'\')'
			        );
			        echo form_submit($data);
			      ?>
			      <?php
				    if ($view_mode == 'admin') { //agent replys, determine whether send email to user, if yes use blockUI in the view
						($ticket_setting_array['notify_user']) ? $btn_id = 'btn_submit_block' : $btn_id = 'btn_submit';
					}
					else {  //user replys, determine whether send email to agent, if yes use blockUI in the view
						($ticket_setting_array['notify_agent_list'] != '') ? $btn_id = 'btn_submit_block' : $btn_id = 'btn_submit';
					}
			        $data = array(
				      'type' => 'submit',
				      'name' => $btn_id,
				      'id' => $btn_id,
				      'value' => my_caption('global_reply'),
				      'class' => 'btn btn-primary mr-2 mt-3'
			        );
			        echo form_submit($data);
			      ?>
			    </div>
		      </div>
		  <?php 
		      echo form_close();
			}
			else {
				echo '<div class="row mt-4"><div class="col-lg-11 offset-1"><h5>' . my_caption('support_ticket_notice_close') . '</h5></div></div>';
			}
		  ?>
		</div>
      </div>
	</div>
	<div class="col-lg-4">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('global_information')?></h6>
        </div>
        <div class="card-body">
		  <div class="row">
		    <div class="col-lg-12">
			  <?php
			    switch ($rs->main_status) {
					case 0 :
					  $status = '<span class="badge badge-light">Closed</span>';
					  break;
					case 1 :
					  ($view_mode == 'admin') ? $badge = 'success' : $badge = 'warning';
					  $status = '<span class="badge badge-' . $badge . '">Replied</span>';
					  break;
					case 2 :
					  ($view_mode == 'admin') ? $badge = 'warning' : $badge = 'success';
					  $status = '<span class="badge badge-' . $badge . '">Pending</span>';
					  break;
				}
			  ?>
			  <?=my_caption('global_status')?> : <?=my_esc_html($status)?>
			</div>
			<?php if ($view_mode == 'admin') { ?>
		      <div class="col-lg-12 mt-3">
			    <?=my_caption('support_ticket_report_user')?> : <u><a href="<?php echo my_esc_html(base_url('admin/edit_user/') . $rs->user_ids);?>" target="_blank"><?=my_esc_html($rs->user_fullname)?></a></u>
			  </div>
			<?php } ?>
			<div class="col-lg-12 mt-3">
			  <?php echo my_caption('global_catalog') . ' : ' . $rs->catalog?>
			</div>
			<div class="col-lg-12 mt-3">
			  <?php
			    switch ($rs->priority) {
					case 0 :
					  $priority = my_caption('support_ticket_priority_low');
					  break;
					case 1 :
					  $priority = my_caption('support_ticket_priority_normal');
					  break;
					case 2 :
					  $priority = my_caption('support_ticket_priority_high');
					  break;
				}
			    echo my_caption('support_ticket_priority') . ' : ' . $priority
			  ?>
			</div>
			<div class="col-lg-12 mt-3">
			  <?php echo my_caption('support_ticket_created_time') . ' : ' . my_conversion_from_server_to_local_time($rs->created_time, $this->user_timezone, $this->user_dtformat);?>
			</div>
			<div class="col-lg-12 mt-3">
			  <?php echo my_caption('support_ticket_updated_time') . ' : ' . my_conversion_from_server_to_local_time($rs->updated_time, $this->user_timezone, $this->user_dtformat);?>
			</div>
			<div class="col-lg-12 mt-3">
			  <?php
			    switch ($rs->rating) {
					case 0 :
					  $rating = my_caption('support_ticket_rating_0');
					  break;
					case 1 :
					  $rating = my_caption('support_ticket_rating_1');
					  break;
					case 2 :
					  $rating = my_caption('support_ticket_rating_2');
					  break;
					case 3 :
					  $rating = my_caption('support_ticket_rating_3');
					  break;
				}
			  ?>
			  <?php echo my_caption('support_ticket_rating') . ' : ' . $rating;?>
			</div>
		  </div>
		</div>
      </div>
	  <?php if ($view_mode == 'user' && $ticket_setting_array['rating']) { ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('support_ticket_rating')?></h6>
        </div>
        <div class="card-body">
		  <div class="row">
		    <div class="col-lg-12">
			  <a id="Rating3" class="hand-cursor" onclick="actionQuery('<?=my_caption('support_ticket_rating')?>', '<?=my_caption('support_ticket_rating_3')?>, <?=my_caption('global_are_you_sure')?>', '<?=base_url('user/ticket_rating_action/' . $rs->ids . '/3/')?>', '')"><span class="dot-green mr-1"></span> <?=my_caption('support_ticket_rating_3')?></a>
			</div>
		    <div class="col-lg-12 mt-3">
			  <a id="Rating2" class="hand-cursor" onclick="actionQuery('<?=my_caption('support_ticket_rating')?>', '<?=my_caption('support_ticket_rating_2')?>, <?=my_caption('global_are_you_sure')?>', '<?=base_url('user/ticket_rating_action/' . $rs->ids . '/2/')?>', '')"><span class="dot-yellow mr-1"></span> <?=my_caption('support_ticket_rating_2')?></a>
			</div>
		    <div class="col-lg-12 mt-3">
			  <a id="Rating1" class="hand-cursor" onclick="actionQuery('<?=my_caption('support_ticket_rating')?>', '<?=my_caption('support_ticket_rating_1')?>, <?=my_caption('global_are_you_sure')?>', '<?=base_url('user/ticket_rating_action/' . $rs->ids . '/1/')?>', '')"><span class="dot-red mr-1"></span> <?=my_caption('support_ticket_rating_1')?></a>
			</div>
		  </div>
		</div>
      </div>
	  <?php } ?>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>