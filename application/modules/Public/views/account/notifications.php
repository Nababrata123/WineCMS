 <div class="container text-left">
    <div class="row profile">
		<div class="col-md-12">
            <div class="profile-content pa-5">
                   <h3 class="page-heading">
                        Notifications
                        
                    </h3>

                <?php
					//form validation
					echo validation_errors();

					if($this->session->flashdata('message_type')) {
						if($this->session->flashdata('message')) {

							echo '<div class="alert alert-'.$this->session->flashdata('message_type').' alert-dismissable">';
							echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
							echo $this->session->flashdata('message');
							echo '</div>';
						}
					}
				?>
								
				<!-- Form section -->
				<?php
					$attributes = array('class' => '', 'id' => 'notificationsform', 'role' => 'form', 'data-toggle' => 'validator');
					echo form_open_multipart(base_url('account/notifications'), $attributes);
				?>
					<div class="row">
						<div class="col-md-12">
														
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="email" id="" value="1" <?php echo (isset($notification)&&$notification->email==1?"checked":"");?>> Email Notification
									</label>
								</div>
								<div class="help-block with-errors"></div>
							</div>
								
                            <div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="push" id="" value="1" <?php echo (isset($notification)&&$notification->push==1?"checked":"");?>> Push Notification
									</label>
							    </div>
							    <div class="help-block with-errors"></div>
							</div>
						

							<button type="submit" class="btn btn-success">Save</button>
						
						</div>
					</div>
				<?php echo form_close();?>	
                <p>&nbsp;</p>
                <p>&nbsp;</p>
            </div>
        </div>
    </div>
</div>