<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-home"></span> Edit store &raquo; <small> <?php echo $store->name;?></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/store');?>"><span class="glyphicon glyphicon-home"></span> Store</a></li>
    			<li><a href="<?php echo base_url('App/store/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Store</a></li>
    		</ul>
        </div>
    </div>
</div>


<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/store');?>">Store Management</a></li>
		<li class="active">Edit store</li>
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
		echo form_open_multipart(base_url('App/store/edit/'.$store->id), $attributes);
    ?>
	<div class="col-sm-6">

      	<fieldset>
    		<legend>Basic Info</legend>
	      	
    		
		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Name *</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="name" class="form-control" id="inputFirstName" placeholder="Enter name" value="<?php echo $store->name; ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Address *</label>
		  		<div class="col-sm-7">
		  			
		  			<input type="text" name="adress" class="form-control" id="inputEmail" placeholder="Enter address" value="<?php echo $store->adress; ?>" required >
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Suite / Apt </label>
		  		<div class="col-sm-7">
		  			
		  			<input type="text" name="suite_number" class="form-control" id="inputEmail" placeholder="Enter suite or apartment number" value="<?php echo $store->suite_number; ?>">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
            <!-- <div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Apartment number</label>
		  		<div class="col-sm-7">
		  			
		  			<input type="text" name="appartment_number" class="form-control" id="inputEmail" placeholder="Enter apartment number" value="<?php echo $store->appartment_number; ?>">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div> -->

		  	<div class="form-group">
		  		<label for="inputEmail" class="col-sm-3 control-label">City *</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="city" class="form-control" id="inputEmail" placeholder="Enter city" value="<?php echo $store->city; ?>" required >
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputPassword" class="col-sm-3 control-label">State *</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="state"  class="form-control" id="inputPassword" placeholder="Enter state" value="<?php echo $store->state; ?>" required>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Zip Code *</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="zipcode" class="form-control" id="inputConfirmPassword"  placeholder="Enter zip code" value="<?php echo $store->zipcode; ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Status</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="status" id="checkboxActive" value="active" <?php if($store->status != "inactive") echo "checked";?>>
					    Active
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if($store->status == "inactive") echo "checked";?>>
					    In-active
					  </label>
					</div>
				</div>
			</div>

		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure to save the data?');"><span class="glyphicon glyphicon-ok-sign"></span> Save Store</button> or <a href="<?php echo base_url('App/store');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>

	<div class="col-sm-6">
		<fieldset>
    		<legend>Basic Info</legend>
    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Phone</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="phone" data-minlength="10" maxlength="14" class="form-control" id="phone" placeholder="Enter phone number" value="<?php echo $store->phone; ?>">
			  		<div class="help-block with-errors">14 digit phone number</div>
			  	</div>
		  	</div>
    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Store Manager</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="store_manager"  class="form-control" id="store_manager" placeholder="Enter store manager name" value="<?php echo $store->store_manager; ?>">
			  		<div class="help-block with-errors"></div>
			  		
			  	</div>
			<div class="form-group">
                	<label for="inputEmail" class="col-sm-3 control-label">Store Email</label>
                <div class="col-sm-7">
                    <input type="email" name="email_address"  class="form-control" id="inputEmail" placeholder="Enter store Email" value="<?php echo $store->email; ?>">
                    <div class="help-block with-errors"></div>
                    
                </div>
            </div>
		  	
    		 <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Sales Rep *</label>
		  		<div class="col-sm-7">
			  		
			  			<?php
			  				$sales_rep_string=$store->sales_rep;
			  				$sales_rep_array=explode("#",$sales_rep_string);
			  				/*foreach($sales_rep as $value){
			  			?>
			  			<?php echo $value->first_name." ".$value->last_name;?>

			  			<input type="checkbox" name="sales_rep[]" class="single-checkbox" value="<?php echo $value->id;?>" <?php if(in_array($value->id, $sales_rep_array)){echo "checked";}?>>
			  			
			  			<?php }*/ ?>
			  			<select class="chosen-select form-control" name="sales_rep[]" multiple="multiple" data-placeholder="Sales representative..." required>
			  				<?php
			  				foreach($sales_rep as $value){
			  				?>
						  <option value="<?php echo $value->id;?>"<?php if(in_array($value->id, $sales_rep_array)){echo "selected";}?>><?php echo $value->last_name." ".$value->first_name;?></option>
						   <?php } ?>
						</select>
			  			<div class="help-block with-errors"></div>
			  		
			  		
			  	</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Account Number</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="account_number"  class="form-control" id="store_manager" placeholder="Enter account number" value="<?php echo $store->account_number; ?>">
			  		<div class="help-block with-errors"></div>
			  		
			  	</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Special Request</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="special_request"  class="form-control" id="store_manager" placeholder="Enter special request" value="<?php echo $store->special_request; ?>">
			  		<div class="help-block with-errors"></div>
			  		
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Zone *</label>
		  		<div class="col-sm-7">
			  		<select name="zone"  required class="form-control">
			  			<option value="">Select zone</option>
			  			<?php

			  				
			  				foreach($zone as $value){
			  			?>
			  			<option value="<?php echo $value->id;?>" <?php if($value->id==$store->zone){echo "selected";}?>><?php echo $value->name;?></option>
			  			
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  		
			  	</div>
		  	</div>
		  	<?php
		  		$flavour=$store->wine_sell_type;
		  		$flavour_array=explode('/',$flavour);
		  	?>
		  <div class="form-group">
		  		<label for="inputDesc" class="col-sm-3 control-label">Product Type *</label>
		  		<div class="col-sm-7">
					Royal<input type="checkbox" name="wine_sell_type[]" value="royal" <?php if(in_array('royal',$flavour_array)){echo 'checked';}?>>
			  		MYX<input type="checkbox" name="wine_sell_type[]" value="mix" <?php if(in_array('mix',$flavour_array)){echo 'checked';}?>>
					Kayco<input type="checkbox" name="wine_sell_type[]" value="kayco" <?php if(in_array('kayco',$flavour_array)){echo 'checked';}?>>
			  		Other<input type="checkbox" name="wine_sell_type[]" value="other" <?php if(in_array('other',$flavour_array)){echo 'checked';}?>>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
				<label for="inputPic" class="col-sm-3 control-label">Store logo *</label>
				<div class="col-sm-7">
					<input type="file" name="pics" class="form-control" id="inputPic" placeholder="Upload logo">
					
				</div>
			</div>
		  	
		  	
    	</fieldset>
	</div>
	<?php echo form_close();?>
</div>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
	//$(".chosen-select").chosen({max_selected_options: 3});
	$(".chosen-select").chosen();
	/*var limit = 2;
	$('input.single-checkbox').on('change', function(evt) {
	   if($(this).siblings(':checked').length >= limit) {
	       this.checked = false;
	   }
	});*/
	$(document).ready(function(){
		//autocomplete off 
		$( document ).on( 'focus', ':input', function(){
			$( this ).attr( 'autocomplete', 'new-password' );
		});
	});
</script>