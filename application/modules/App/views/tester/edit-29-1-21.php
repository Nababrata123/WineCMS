<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/sweetalert.css">
<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Edit Taster &raquo; <small> <?php echo $tester->first_name." ".$tester->last_name;?></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/tester');?>"><span class="glyphicon glyphicon-user"></span>Taster</a></li>
    			<li><a href="<?php echo base_url('App/tester/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Taster</a></li>
    		</ul>
        </div>
    </div>
</div>


<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/tester');?>">Taster Management</a></li>
		<li class="active">Edit Taster</li>
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
		echo form_open(base_url('App/tester/edit/'.$tester->id), $attributes);
    ?>

    	<div class="col-sm-6">

      	<fieldset>
    		<legend>Basic Info</legend>
	      	
    		<input type="hidden" name="user_type" value="tester">
    		<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">The taster is created by:</label>
		  		<div class="col-sm-7"><?php echo $taster_name;?></div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">First Name *</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="first_name" class="form-control nf" id="inputFirstName" placeholder="Enter first name" value="<?php echo $tester->first_name; ?>" required autocomplete="off">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Last Name *</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="last_name" class="form-control nf" id="inputLastName" placeholder="Enter last name" value="<?php echo $tester->last_name; ?>" required autocomplete="off">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputEmail" class="col-sm-3 control-label">Email Address *</label>
		  		<div class="col-sm-7">
			  		<input type="email" name="email" class="form-control" id="inputEmail" placeholder="Enter email address" value="<?php echo $tester->email; ?>" required autocomplete="off">
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	

		  	<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Status</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="status" id="checkboxActive" value="active" <?php if($tester->status != "inactive") echo "checked";?>>
					    Active
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if($tester->status == "inactive") echo "checked";?>>
					    In-active
					  </label>
					</div>
				</div>
			</div>

		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary" id="submit" onclick="return validate();"><span class="glyphicon glyphicon-ok-sign"></span> Save User</button> or <a href="<?php echo base_url('App/tester');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>
	
	<div class="col-sm-6">
		<fieldset>
    		<legend>Other</legend>

    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">City</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[city]"  class="form-control" id="city" placeholder="Enter city" value="<?php if(isset($user_meta['city'])){echo $user_meta['city'];}?>" autocomplete="off">
			  		<div class="help-block with-errors">City</div>
			  	</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">State</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[state]"  class="form-control" id="state" placeholder="Enter state" value="<?php if(isset($user_meta['city'])){echo $user_meta['state'];}?>" autocomplete="off">
			  		<div class="help-block with-errors">State</div>
			  	</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Zip Code</label>
		  		<div class="col-sm-7">
			  		<input type="number" name="meta[zipcode]"  class="form-control" id="zipcode" placeholder="Enter zip code" value="<?php if(isset($user_meta['city'])){echo $user_meta['zipcode'];}?>" autocomplete="off">
			  		<div class="help-block with-errors">Zip Code</div>
			  	</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Address</label>
		  		<div class="col-sm-7">
			  		
                    <textarea name="meta[address]"  class="form-control" id="address" placeholder="Enter address" autocomplete="off"><?php if(isset($user_meta['address'])){echo $user_meta['address'];}?></textarea>
			  		<div class="help-block with-errors">Address</div>
			  	</div>
		  	</div>
            <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Suite / Apt</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[suite_number]"  class="form-control" id="suite_number" placeholder="Enter Suite or apartment number" value="<?php if(isset($user_meta['suite_number'])){echo $user_meta['suite_number'];}?>" autocomplete="off">
			  		<div class="help-block with-errors">Suite or apartment number</div>
			  	</div>
		  	</div>
            <!-- <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Apartment number</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[appartment_number]"  class="form-control" id="appartment_number" placeholder="Enter Apartment number" value="<?php if(isset($user_meta['appartment_number'])){echo $user_meta['appartment_number'];}?>">
			  		<div class="help-block with-errors">Apartment number</div>
			  	</div>
		  	</div> -->
    		 <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Cell Number</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[phone]" data-minlength="10" maxlength="14" class="form-control" id="phone" placeholder="Enter phone number" value="<?php echo $user_meta['phone']; ?>" autocomplete="off">
			  		<div class="help-block with-errors">14 digit phone number</div>
			  	</div>
		  	</div>
			<?php
                if($tester->created_by!=7){
                    $display="none";
                }
                else
                {
                    $display="block";
                }
                
            ?>
		  	<div class="form-group" id="rate_per_hour_div" style="display:<?php echo $display;?>">
		  		<label for="inputPhone" class="col-sm-3 control-label">Rate Per Hour *</label>
		  		<div class="col-sm-7">
			  		<input type="number" name="meta[rate_per_hour]"  class="form-control" id="rate_per_hour" placeholder="Enter rate per hour" value="<?php if(isset($user_meta['rate_per_hour'])){echo $user_meta['rate_per_hour'];} ?>" required autocomplete="off">
			  		<div class="help-block with-errors">Rate Per Hour</div>
			  	</div>
		  	</div>
			
    		 <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Zone *</label>
		  		<div class="col-sm-7">
			  		<select name="meta[zone][]" multiple="multiple" required class="form-control">
			  			<?php
			  				$zone_id=$user_meta['zone'];
			  				$zone_id_array=explode(',',$zone_id);
			  				foreach($zone as $value){
			  			?>
			  			<option value="<?php echo $value->id;?>" <?php if(in_array($value->id,$zone_id_array)){echo "selected";}?>><?php echo $value->name;?></option>
			  			
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors">Zone</div>
			  	</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Vendor Number *</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[manual_account_number]"  maxlength="10" class="form-control" id="manual_account_number" placeholder="Enter vendor number" value="<?php echo $user_meta['manual_account_number']; ?>" required autocomplete="off">
			  		<div class="help-block with-errors">Vendor number</div>
			  	</div>
		  	</div>
            <!--Set tester under any agency or not-->
            <?php
                if($tester->created_by!=7){
                    $display="block";
					$chk_val='1';
                }
                else
                {
                    $display="none";
					$chk_val='';
                }
                
            ?>
            <input type="hidden" id="checked_val" value="'<?php echo $chk_val;?>" name="checked_val">
		  	<div class="form-group">
		  		<label for="inputPhone" class="col-sm-6 control-label">Want to create a taster under agency? </label>
		  		<div class="col-sm-4">
			  		<input type="checkbox" name="chk" id="chk" value="1" <?php if($tester->created_by!=7){echo "checked";}?> >
			  	</div>
		  	</div>
            
		  	<div class="form-group" style="display:<?php echo $display;?>" id="agency_div">
		  		<label for="inputPhone" class="col-sm-3 control-label">Agency</label>
		  		<div class="col-sm-7">
			  		<select name="agency_id" class="form-control" id="agency_drop">
			  			<option value="">Select agency</option>
			  			<?php
			  				foreach($agency as $value){
                                $agency_name=get_username('agency',$value->id);
			  			?>
			  			<option value="<?php echo $value->id;?>" <?php if($tester->created_by==$value->id){echo "selected";}?> ><?php echo $agency_name;?></option>
			  			
			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors">Agency</div>
			  	</div>
		  	</div>
			<div class="form-group" style="display: <?php echo $display;?>" id="tasters_rate_div">
                <label for="inputPhone" class="col-sm-3 control-label">Agency's rate</label>
                <div class="col-sm-7">
                    <input type="number" name="meta[tasters_rate]"  min="1" class="form-control" id="tasters_rate" placeholder="Enter agency's rate" value="<?php if(isset($user_meta['tasters_rate']))echo $user_meta['tasters_rate']; ?>" autocomplete="off">
                    <div class="help-block with-errors">Agency's rate</div>
                </div>
            </div>
		  	
    	</fieldset>
	</div>

	<?php echo form_close();?>
</div>
<script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 
<script type="text/javascript">
	$('#chk').click(function() {
        if ($(this).is(':checked')) {
            $('#checked_val').val("1");
            $("#agency_div").show();
            $("#tasters_rate_div").show();
            $('#agency_drop').prop('required',true);
			$('#tasters_rate').prop('required',true);
            $('#rate_per_hour_div').hide();
            $('#rate_per_hour').prop('required',false);
			$('#agency_drop').focus();
            $('#tasters_rate').focus();
			$('#rate_per_hour').attr('disabled',true);
			$('#rate_per_hour').val('');           
            
        }
        else
        {
            $('#checked_val').val("");
        	$('#agency_drop').prop('required',false);
			$('#tasters_rate').prop('required',false);
        	$("#agency_div").hide();
            $("#tasters_rate_div").hide();
			$('#rate_per_hour_div').show();
			$('#rate_per_hour').prop('required',true);
			$('#rate_per_hour').focus();
			$('#rate_per_hour').attr('disabled',false);
			$('#tasters_rate').val('');
			$('#agency_drop').val('');
			$('#tasters_rate').attr('disabled',true);
        }
    });
    function validate(){
		var ret = confirm('Are you sure to save the data?');
		if(ret == true){
			if($('#chk').is(':checked') && $('#agency_drop').val()==''){
				$('#agency_drop').focus();
				swal("Oops!", "Select an agency.", "warning");
				return false;
			}else if($('#chk').is(':checked') && $('#tasters_rate').val()==''){
				$('#tasters_rate').focus();
				//swal("Oops!", "Please enter taster rate.", "warning");
				swal("Oops!", "Please enter agency's rate.", "warning");
				return false;
			}else if(!$('#chk').is(':checked') && $('#rate_per_hour').val()==''){
				$('#rate_per_hour').focus();
				swal("Oops!", "Please enter rate per hour.", "warning");
				return false;
			}else{
				if($('#submit').hasClass('disabled')){
					$('#submit').removeClass('disabled');
				}
				return true;
			}
		}else{
			return false;
		}
	}
</script>
<script>
/*$('.nf').bind('keyup blur',function(){ 
    var node = $(this);
    node.val(node.val().replace(/[^a-z]/g,'') ); }
);*/
$("#phone").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
       
               return false;
    }
   });
$("#rate_per_hour").keyup(function(){
	if($(this).val() > 999){
      $(this).val('');
      return false;
    }
});
</script>