<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span>Add Store</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/store');?>"><span class="glyphicon glyphicon-user"></span> Store</a></li>
    			<li class="active"><a href="<?php echo base_url('App/store/add');?>"><span class="glyphicon glyphicon-plus-sign"></span> Add Store</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/store');?>">Store Management</a></li>
		<li class="active">Add Store</li>
	</ol>

	<?php
		//form validation
		echo validation_errors();

		$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
		echo form_open_multipart(base_url('App/store/add'),$attributes);
      ?>
	<div class="col-sm-6">

      	<fieldset>
    		<legend>Basic Info</legend>
	      	
    		
		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="name" class="form-control" id="inputFirstName" placeholder="Enter name" value="<?php echo set_value('name'); ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Adress</label>
		  		<div class="col-sm-7">
		  			
		  			<input type="text" name="adress" class="form-control" id="inputEmail" placeholder="Enter adress" value="<?php echo set_value('adress'); ?>" required >
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputEmail" class="col-sm-3 control-label">City</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="city" class="form-control" id="inputEmail" placeholder="Enter city" value="<?php echo set_value('city'); ?>" required >
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputPassword" class="col-sm-3 control-label">State</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="state"  class="form-control" id="inputPassword" placeholder="Enter state" required>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputConfirmPassword" class="col-sm-3 control-label">Zipcode</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="zipcode" class="form-control" id="inputConfirmPassword"  placeholder="Enter zipcode" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Status</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="status" id="checkboxActive" value="active" <?php if(set_value('status') != "inactive") echo "checked";?>>
					    Active
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if(set_value('status') == "inactive") echo "checked";?>>
					    In-active
					  </label>
					</div>
				</div>
			</div>

		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Save Store</button> or <a href="<?php echo base_url('App/store');?>">Cancel</a>
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
			  		<input type="text" name="phone" data-minlength="10" maxlength="10" class="form-control" id="phone" placeholder="Enter phone number" value="<?php echo set_value('phone'); ?>" required>
			  		<div class="help-block with-errors">10 digit phone number</div>
			  	</div>
		  	</div>
    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Store Manager</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="store_manager"  class="form-control" id="store_manager" placeholder="Enter store manager" value="<?php echo set_value('store_manager'); ?>" required>
			  		<div class="help-block with-errors"></div>
			  		
			  	</div>
		  	</div>
    		 
		  	
    		 <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Sales Rep</label>
		  		<div class="col-sm-7">
			  		
			  			<?php
			  				/*foreach($sales_rep as $value){
			  			?>
			  			<?php echo $value->first_name." ".$value->last_name;?>

			  			<input type="checkbox" name="sales_rep[]" class="single-checkbox" value="<?php echo $value->id;?>">
			  			
			  			<?php }*/ ?>
			  			<select class="chosen-select form-control" name="sales_rep[]" multiple="multiple" data-placeholder="Sales representative...">
			  				<?php
			  				foreach($sales_rep as $value){
			  				?>
						  <option value="<?php echo $value->id;?>"><?php echo $value->first_name." ".$value->last_name;?></option>
						   <?php } ?>
						</select>
			  			<div class="help-block with-errors"></div>
			  		
			  		
			  	</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Account Number</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="account_number"  class="form-control" id="store_manager" placeholder="Enter account number" value="<?php echo set_value('account_number'); ?>" required>
			  		<div class="help-block with-errors"></div>
			  		
			  	</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Special Request</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="special_request"  class="form-control" id="store_manager" placeholder="Enter special request" value="<?php echo set_value('special_request'); ?>" required>
			  		<div class="help-block with-errors"></div>
			  		
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Zone</label>
		  		<div class="col-sm-7">
			  		<select name="zone"  required class="form-control">
			  			<option value="">Select zone</option>
			  			<?php
			  				foreach($zone as $value){
			  			?>
			  			<option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
			  			
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  		
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputDesc" class="col-sm-3 control-label">Wine sell type *</label>
		  		<div class="col-sm-7">
			  		ROYAL<input type="checkbox" name="wine_sell_type[]" value="royal" checked required>
			  		MIX<input type="checkbox" name="wine_sell_type[]" value="mix">
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
				<label for="inputPic" class="col-sm-3 control-label">Store logo</label>
				<div class="col-sm-7">
					<input type="file" name="pics" class="form-control" id="inputPic" placeholder="Upload logo"  required>
					<div class="help-block with-errors"></div>
				</div>
			</div>
		  	
		  	
    	</fieldset>
	</div>
	
	<?php echo form_close();?>
</div>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
	$(".chosen-select").chosen({max_selected_options: 2});
	/*var limit = 2;
	$('input.single-checkbox').on('change', function(evt) {
	   if($(this).siblings(':checked').length >= limit) {
	       this.checked = false;
	   }
	});*/

	

</script>