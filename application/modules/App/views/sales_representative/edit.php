<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Edit Sales Representative &raquo; <small> <?php echo $sales_representative->first_name." ".$sales_representative->last_name;?></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/sales_representative');?>"><span class="glyphicon glyphicon-user"></span> Sales Representative</a></li>
    			<li><a href="<?php echo base_url('App/sales_representative/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Sales Representative</a></li>
    		</ul>
        </div>
    </div>
</div>


<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/sales_representative');?>">Sales Representative Management</a></li>
		<li class="active">Edit Sales Representative</li>
	</ol>
	<?php
		if($this->session->flashdata('message_type')) {
			if($this->session->flashdata('message')) {

				echo '<div class="alert alert-'.$this->session->flashdata('message_type').' alert-dismissable">';
				echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				echo $this->session->flashdata('message');
				echo '</div>';
			}
		}
	?>
	<?php
		//form validation
		echo validation_errors();

		$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
		echo form_open(base_url('App/sales_representative/edit/'.$sales_representative->id), $attributes);
    ?>
	<div class="col-sm-6">
		<fieldset>
    		<legend>Basic Info</legend>

	      	

		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">First Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="first_name" class="form-control nf" id="inputFirstName" placeholder="Enter first name" value="<?php echo $sales_representative->first_name;?>" required autocomplete="off">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Last Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="last_name" class="form-control nf" id="inputLastName" placeholder="Enter last name" value="<?php echo $sales_representative->last_name;?>" required autocomplete="off">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputEmail" class="col-sm-3 control-label">Email address</label>
		  		<div class="col-sm-7">
			  		<input type="email" name="email" class="form-control" id="inputEmail" placeholder="Enter email address" value="<?php echo $sales_representative->email;?>" required autocomplete="off">
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<!--<div class="form-group">
		  		<label for="inputPassword" class="col-sm-3 control-label">Password</label>
		  		<div class="col-sm-7">
			  		<input type="password" name="password" data-minlength="6" class="form-control" id="inputPassword" placeholder="Enter password" >
			  		<div class="help-block with-errors">Please leave the password blank, if you don't want to update.</div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Confirm Password</label>
		  		<div class="col-sm-7">
		  			<input type="password" name="c_password" class="form-control" id="inputConfirmPassword" data-match="#inputPassword" data-match-error="Whoops, these don't match" placeholder="Confirm password" >
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>-->

		  	<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Status</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="status" id="checkboxActive" value="active" <?php if($sales_representative->status != "inactive") echo "checked";?>>
					    Active
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if($sales_representative->status == "inactive") echo "checked";?>>
					    In-active
					  </label>
					</div>
				</div>
			</div>

		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure to save the data?');"><span class="glyphicon glyphicon-ok-sign"></span> Save Sales Representative</button> or <a href="<?php echo base_url('App/sales_representative');?>">Cancel</a>
			  	</div>
		  	</div>
		</fieldset>
	</div>
	<div class="col-sm-6">
		<fieldset>
    		<legend>Other</legend>

    		<?php
    			
    			
    			foreach ($user_meta as $field) {

    				$field_name = $field['name'];
    				$value = '';
    				foreach ($sales_representative->meta as $data) {
    					//print "<pre>"; print_r($data);
    					if ($data->meta_key == $field_name) {
    						//$field_name."=".$data->meta_key."<br>";
    						$value = $data->meta_value;
    					}
    				}
    				echo render_field($field, $value);
    			}
    		?>
    	</fieldset>

    	<fieldset>
    		<legend>Audit Info</legend>
    		<p>
    			<span class="glyphicon glyphicon-info-sign"></span> Last Updated on:
		    	<?php if (!is_null($sales_representative->updated_on)) {?>
			    	<small><?php echo datetime_display($sales_representative->updated_on);?></small>
			    	by <small><?php echo $sales_representative->updated_by_name;?></small>
		    	<?php } else {echo "N/A";}?>
		    </p>

		    <p>
		    	<span class="glyphicon glyphicon-info-sign"></span> Created on:
		    	<?php if (!is_null($sales_representative->created_on)) {?>
			    	<small><?php echo datetime_display($sales_representative->created_on);?></small>
			    	by <small><?php echo $sales_representative->created_by_name;?></small>
			    <?php } else {echo "N/A";}?>
			</p>

		    <p>
		    	<span class="glyphicon glyphicon-info-sign"></span> Last Login on:
		    	<?php if (!is_null($sales_representative->last_login)) {?>
		    		<small><?php echo datetime_display($sales_representative->last_login);?></small>
		    	<?php } else {echo "N/A";}?>
		    </p>

			<p><a class="btn btn-sm btn-danger <?php echo ($sales_representative->id == $this->session->userdata('id'))?'disabled':'';?>" onclick="return confirm('Are you sure you want to delete the sales_representative account?')" href="<?php echo base_url('App/sales_representative/delete/'.$sales_representative->id);?>"><span class="glyphicon glyphicon-trash"></span> Delete this Sales Representative</a></p>
    	</fieldset>
	</div>
	<?php echo form_close();?>
</div>
<script>
/*$('.nf').bind('keyup blur',function(){ 
    var node = $(this);
    node.val(node.val().replace(/[^a-z]/g,'') ); }
);*/
	
	$(document).ready(function(){
		$(document).ready(function(){
			//autocomplete off 
			$( document ).on( 'focus', ':input', function(){
				$( this ).attr( 'autocomplete', 'new-password' );
			});
		});
	});
	$("#inputPhone").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
       
            return false;
		}	
	});
</script>