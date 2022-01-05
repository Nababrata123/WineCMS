<option value="">Select Taster</option>
<?php
	$this->load->model('Job_model');
	$taster_id_array=explode(",",$taster_id);
	foreach($tester as $value)
	{
		$role_id=$this->Job_model->get_user_role('users',$value['id']);
			  					if($role_id=='5')
			  					{
			  						$agency_name=$this->Job_model->get_agency_name('user_meta',$value['id']);
			  					}
?>
		<option value="<?php echo $value['id'];?>" <?php if(in_array($value['id'],$taster_id_array)){echo "selected";}?>>
			<?php 
				if($role_id=='5')
			  				{
			  					echo $agency_name;
			  				}
			  				else
			  				{
			  					echo $value['last_name']." ".$value['first_name'];
			  				}
			?>
				
			</option>
			  			
<?php 
	} 
?>