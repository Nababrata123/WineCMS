<div class="subnav">
	<div class="container-fluid">
		<h1><span class="glyphicon glyphicon-heart-empty"></span> Edit Category &raquo; <small> <?php echo $category->name;?></small></h1>

        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
				<li><?php echo render_link('index', '<span class="glyphicon glyphicon-heart-empty"></span> Category');?></li>
				<li><?php echo render_link('add', '<span class="glyphicon glyphicon-plus-sign"></span> Create New Category');?></li>
    		</ul>
        </div>
    </div>
</div>

<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/category');?>">Manage Category</a></li>
		<li class="active">Edit Category</li>
	</ol>

	<?php
		//form validation
		echo validation_errors();

		$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
		echo form_open_multipart(base_url('App/category/edit/'.$category->id), $attributes);
   	?>

	<div class="col-sm-6">

		<fieldset>
    		<legend>Basic Info</legend>
    		<div class="form-group">
		  		<label for="inputName" class="col-sm-3 control-label">Parent Category</label>
		  		<div class="col-sm-7">
		  			<!-- Get all category -->
		  			

		  			<select id="parent_id" name="parent_id" class="form-control">
		  				<option value="0">Root</option>
		  				<?php foreach ($list as $item) {?>
						<option value="<?php echo $item['id'];?>" <?php if ($category->parent_id == $item['id']) {echo "selected";}?>><?php echo $item['name'];?></option>
						<?php }?>
		  			</select>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
			<div class="form-group">
		  		<label for="inputName" class="col-sm-3 control-label">Category Name*</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="name" class="form-control" id="inputName" placeholder="Enter size" value="<?php echo $category->name; ?>" required >
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Status</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="status" id="checkboxActive" value="active" <?php if($category->status != "inactive") echo "checked";?>>
					    Active
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if($category->status == "inactive") echo "checked";?>>
					    In-active
					  </label>
					</div>
				</div>
			</div>

			<p>&nbsp;</p>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-6">
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Save Category</button> or <a href="<?php echo base_url('App/category');?>">Cancel</a>
				</div>
			</div>
		</fieldset>
	</div>

	<div class="col-sm-6">

		<fieldset>
    		<legend>Audit Info</legend>
    		<p>
    			<span class="glyphicon glyphicon-info-sign"></span> Last Updated on:
		    	<?php if (!is_null($category->updated_on)) {?>
			    	<small><?php echo datetime_display($category->updated_on);?></small>
			    	by <small><?php echo $category->updated_by_name;?></small>
		    	<?php } else {echo "N/A";}?>
		    </p>

		    <p>
		    	<span class="glyphicon glyphicon-info-sign"></span> Created on:
		    	<?php if (!is_null($category->created_on)) {?>
			    	<small><?php echo datetime_display($category->created_on);?></small>
			    	by <small><?php echo $category->created_by_name;?></small>
			    <?php } else {echo "N/A";}?>
			</p>

			<p><a class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this?')" href="<?php echo base_url('App/category/delete/'.$category->id);?>"><span class="glyphicon glyphicon-trash"></span> Delete this Category</a></p>
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
