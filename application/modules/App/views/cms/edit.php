<script src="<?php echo base_url();?>ckeditor/ckeditor.js"></script>
<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-zone"></span> Edit Cms &raquo; <small> <?php echo $cms->title;?></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
        		<li><a href="<?php echo base_url('App/cms');?>"><span class="glyphicon glyphicon-zone"></span>Cms</a></li>
    			<li><a href="<?php echo base_url('App/cms/add');?>"><span class="glyphicon glyphicon-plus-sign"></span>Add Cms</a></li>
    		</ul>
        </div>
    </div>
</div>


<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/cms');?>">Cms Management</a></li>
		<li class="active">Edit Cms</li>
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
		echo form_open(base_url('App/cms/edit/'.$cms->id), $attributes);
    ?>
	<div class="col-sm-6">
		<fieldset>
    		<legend>Basic Info</legend>

	      	

		  	<div class="form-group">
		  		<label for="inputFirstName" class="col-sm-3 control-label">Cms Name</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="title" class="form-control" id="CmsName" placeholder="Enter Cms name" value="<?php echo $cms->title; ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="inputLastName" class="col-sm-3 control-label">Content</label>
		  		<div class="col-sm-7">
		  			<textarea name="content" class="form-control ckeditor" id="CmsDetails" placeholder="Enter Cms details" required><?php echo $cms->content; ?></textarea>
		  			
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	
		  	<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Status</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="status" id="checkboxActive" value="active" <?php if($cms->status != "Inactive") echo "checked";?>>
					    Active
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if($cms->status == "Inactive") echo "checked";?>>
					    In-active
					  </label>
					</div>
				</div>
			</div>

		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure to save the data?');"><span class="glyphicon glyphicon-ok-sign"></span> Save</button> or <a href="<?php echo base_url('App/cms');?>">Cancel</a>
			  	</div>
		  	</div>
		</fieldset>
	</div>
	<div class="col-sm-6">
		
    	<fieldset>
    		<legend>Audit Info</legend>
    		<p>
    			<span class="glyphicon glyphicon-info-sign"></span> Last Updated on:
		    	<?php if (!is_null($cms->updated_on)) {?>
			    	<small><?php echo datetime_display($cms->updated_on);?></small>
			    	by <small><?php echo $cms->updated_by_name;?></small>
		    	<?php } else {echo "N/A";}?>
		    </p>

		    <p>
		    	<span class="glyphicon glyphicon-info-sign"></span> Created on:
		    	<?php if (!is_null($cms->created_on)) {?>
			    	<small><?php echo datetime_display($cms->created_on);?></small>
			    	by <small><?php echo $cms->created_by_name;?></small>
			    <?php } else {echo "N/A";}?>
			</p>

		    

			<p><a class="btn btn-sm btn-danger <?php echo ($cms->id == $this->session->userdata('id'))?'disabled':'';?>" onclick="return confirm('Are you sure you want to delete the cms?')" href="<?php echo base_url('App/cms/delete/'.$cms->id);?>"><span class="glyphicon glyphicon-trash"></span> Delete this cms</a></p>
    	</fieldset>
	</div>
	<?php echo form_close();?>
</div>
<script>
	$(document).ready(function(){
		//autocomplete off 
		$( document ).on( 'focus', ':input', function(){
			$( this ).attr( 'autocomplete', 'off' );
		});
	});
</script>
