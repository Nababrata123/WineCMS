<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-select.css">
<style>
  .bootstrap-select .dropdown-menu {
    width:100%;
  }
  .bootstrap-select {
      width: 100% \0;
      vertical-align: middle;
      width: 100% !important;
}
.show-tick.bootstrap-select .dropdown-menu .selected span.check-mark{
    left: 2px;
}
  </style>
<style>
.sw { border:2px solid #ccc; width:396px; height:206px; overflow-y: scroll;
    margin-left: 15px;}
.adjustmin {margin-left: 5px !important; width:120px;}
.adjustam {width:105px;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/sweetalert.css">

<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span> Edit User &raquo; <small> <?php echo $usersDetails->first_name." ".$usersDetails->last_name;?></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/users');?>"><span class="glyphicon glyphicon-user"></span> Users</a></li>
    			<li><a href="<?php echo base_url('App/users/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add User</a></li>
    		</ul>
        </div>
    </div>
</div>


<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/users');?>">Users Management</a></li>
		<li class="active">Edit User</li>
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
		echo form_open(base_url('App/users/edit/'.$usersDetails->id), $attributes);
    ?>
	<div class="col-sm-6">

      	<fieldset>
    		<legend>Basic Info</legend>
			<input type="hidden" name="user_type" value="brand_wise_users">

		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">First Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="first_name" class="form-control nf" id="inputFirstName" placeholder="Enter first name" value="<?php echo $usersDetails->first_name; ?>" autocomplete="off">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Last Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="last_name" class="form-control nf" id="inputLastName" placeholder="Enter last name" value="<?php echo $usersDetails->last_name; ?>" autocomplete="off">
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputEmail" class="col-sm-3 control-label">Email address</label>
		  		<div class="col-sm-7">
			  		<input type="email" name="email" class="form-control" id="inputEmail" placeholder="Enter email address" value="<?php echo $usersDetails->email; ?>" autocomplete="off">
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	

		  	<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Status</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="status" id="checkboxActive" value="active" <?php if($usersDetails->status == "active") echo "checked";?>>
					    Active
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if($usersDetails->status == "inactive") echo "checked";?>>
					    In-active
					  </label>
					</div>
				</div>
			</div>

		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary" id="submit_button"><span class="glyphicon glyphicon-ok-sign"></span> Save User</button> or <a href="<?php echo base_url('App/users');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>
	
	<div class="col-sm-6">
		<fieldset>
    		<legend>Others Info</legend>

    		<!-- <div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Phone</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[phone]" data-minlength="10" maxlength="14" class="form-control nuf" id="phone" placeholder="Enter phone number" value="<?php echo $usersDetails->meta[0]->meta_value; ?>">
			  		<div class="help-block with-errors">14 digit phone number</div>
			  	</div>
		  	</div>

    		<div class="form-group">
		  		<label for="inputPhone" class="col-sm-3 control-label">Address</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="meta[adress]"  class="form-control" id="home_adress" placeholder="Enter address" value="<?php echo $usersDetails->meta[1]->meta_value; ?>" required autocomplete="off">
			  		<div class="help-block with-errors">Address</div>
			  	</div>
		  	</div> -->

			  <?php
		  		$wine_id=$usersDetails->meta[0]->meta_value;
		  		$wine_id_array=explode(",",$wine_id);
		  	?>

			<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Sales Rep</label>
          
		  		<div class="col-sm-7">
					  
						<select name="user_id" class="form-control">
			  			<option value="">Select Sales Rep</option>
			  			<?php
			  				foreach($sales_rep as $value){		  
			  			?>
						  <option value="<?php echo $value['id'];?>" <?php if($value['id']==$sales_rep_id){echo "selected";}?>><?php echo $value['last_name']." ".$value['first_name'];?></option>

			  			<?php } ?>
			  		</select>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

			<div class="form-group">
			<label for="inputPhone" class="col-sm-3 control-label">Select Brand(s)*</label>
			<div class="col-sm-7">
				<select class="chosen-select form-control" id="wines" name="wine_id[]" multiple="multiple" data-placeholder="Select wine(s)" autocomplete="off">
					<?php
						foreach($wine as $value){
					?>
						<option value="<?php echo $value->id;?>" <?php if(in_array($value->id,$wine_id_array)){echo "selected";}?>><?php echo $value->name;?></option>
					<?php 
						} 
					?>
				</select>
				<div class="help-block with-errors"></div>
			</div>
		</div>

    	</fieldset>
	</div>
	<?php echo form_close();?>
</div>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css">
<script src="<?php echo base_url()?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 
<script src="<?php echo base_url();?>assets/js/bootstrap-select.js"></script>
<script type="text/javascript">
	$(".chosen-select").chosen();
	
</script>
<script>
/*$('.nf').bind('keyup blur',function(){ 
    var node = $(this);
    node.val(node.val().replace(/[^a-z]/g,'') ); }
);*/
	$(document).ready(function(){
		//autocomplete off 
		$( document ).on( 'focus', ':input', function(){
			$( this ).attr( 'autocomplete', 'off' );
		});

		$("form").submit(function(e){
            e.preventDefault();
            
            if(!$("#submit_button").hasClass("disabled"))
            {
                var c=confirm('Are you save these details');
				
                if(c==true)
                {
				 if($('#wines').val()==null){
						$('#wines').trigger('chosen:activate');
						swal("Oops!", "Please select at least one brand!", "warning");
						return false;
					}else{
						$('form').unbind('submit').submit();
					}
					
                }else{
					return false;
				}
            }
            else
            {
                return false;
            } 
	});    
});

	$(".nuf").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     /*if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
       
               return false;
    }*/
		if (e.shiftKey || e.ctrlKey || e.altKey) {
			e.preventDefault();
		} 
		else 
		{
			var key = e.keyCode;
			if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105))) {
			e.preventDefault();
			}
		}
	});
</script>