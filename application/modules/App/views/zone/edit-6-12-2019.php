<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-zone"></span> Edit Zone &raquo; <small> <?php echo $zone->name;?></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/zone');?>"><span class="glyphicon glyphicon-zone"></span>Zone</a></li>
    			<li><a href="<?php echo base_url('App/zone/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Zone</a></li>
    		</ul>
        </div>
    </div>
</div>


<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/zone');?>">Zone Management</a></li>
		<li class="active">Edit Zone</li>
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
		echo form_open(base_url('App/zone/edit/'.$zone->id), $attributes);
    ?>
	<div class="col-sm-6">
		<fieldset>
    		<legend>Basic Info</legend>

	      	

		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Zone Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="name" class="form-control" id="zoneName" placeholder="Enter zone name" value="<?php echo $zone->name;?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Zone Details</label>
		  		<div class="col-sm-7">
		  			
		  			<textarea name="details" class="form-control" id="zoneDetails" placeholder="Enter zone details"><?php echo $zone->details; ?></textarea>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	
		  	<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Status</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="status" id="checkboxActive" value="active" <?php if($zone->status != "Inactive") echo "checked";?>>
					    Active
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if($zone->status == "Inactive") echo "checked";?>>
					    In-active
					  </label>
					</div>
				</div>
			</div>

		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure to save the data?');"><span class="glyphicon glyphicon-ok-sign"></span> Save zone</button> or <a href="<?php echo base_url('App/zone');?>">Cancel</a>
			  	</div>
		  	</div>
		</fieldset>
	</div>
	<div class="col-sm-6">
		
    	<fieldset>
    		<legend>Audit Info</legend>
    		<p>
    			<span class="glyphicon glyphicon-info-sign"></span> Last Updated on:
		    	<?php if (!is_null($zone->updated_on)) {?>
			    	<small><?php echo datetime_display($zone->updated_on);?></small>
			    	by <small><?php echo $zone->updated_by_name;?></small>
		    	<?php } else {echo "N/A";}?>
		    </p>

		    <p>
		    	<span class="glyphicon glyphicon-info-sign"></span> Created on:
		    	<?php if (!is_null($zone->created_on)) {?>
			    	<small><?php echo datetime_display($zone->created_on);?></small>
			    	by <small><?php echo $zone->created_by_name;?></small>
			    <?php } else {echo "N/A";}?>
			</p>

		    

			<p><a class="btn btn-sm btn-danger <?php echo ($zone->id == $this->session->userdata('id'))?'disabled':'';?>" onclick="return confirm('Are you sure you want to delete the zone account?')" href="<?php echo base_url('App/zone/delete/'.$zone->id);?>"><span class="glyphicon glyphicon-trash"></span> Delete this zone</a></p>
    	</fieldset>
	</div>
	<?php echo form_close();?>
</div>
