<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-user"></span>Add Zone</h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/zone');?>"><span class="glyphicon glyphicon-user"></span> Zone</a></li>
    			<li class="active"><a href="<?php echo base_url('App/zone/add');?>"><span class="glyphicon glyphicon-plus-sign"></span> Add Zone</a></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/zone');?>">Zone Management</a></li>
		<li class="active">Add Zone</li>
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
		echo form_open(base_url('App/zone/add'),$attributes);
      ?>
	<div class="col-sm-12">

      	<fieldset>
    		<legend>Basic Info</legend>
	      	
    		<input type="hidden" name="user_type" value="sales_rep">
		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Zone Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="name" class="form-control" id="zoneName" placeholder="Enter zone name" value="<?php echo set_value('name'); ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Zone Details</label>
		  		<div class="col-sm-7">
		  			<textarea name="details" class="form-control" id="zoneDetails" placeholder="Enter zone details"><?php echo set_value('details'); ?></textarea>
		  			
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
			  		<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Save Zone</button> or <a href="<?php echo base_url('App/zone');?>">Cancel</a>
			  	</div>
		  	</div>
	  	</fieldset>
	</div>

	
	<?php echo form_close();?>
</div>
