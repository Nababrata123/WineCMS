<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-filter"></span> Edit Wine &raquo; <small> <?php echo character_limiter($wine->name, 50);?></small></h1>
        <div id="sub-menu" class="pull-right">
        	<ul class="nav nav-pills">
				<li><?php echo render_link('index', '<span class="glyphicon glyphicon-filter"></span> Wine');?></li>
				<li><?php echo render_link('add', '<span class="glyphicon glyphicon-plus-sign"></span> Add Wine');?></li>
    		</ul>
        </div>
    </div>
</div>


<div class="container-fluid main">
	<ol class="breadcrumb">
		<li><a href="<?php echo base_url('dashboard');?>">Dashboard</a></li>
		<li><a href="<?php echo base_url('App/wine');?>">Wine Management</a></li>
		<li class="active">Edit Wine</li>
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
		echo form_open_multipart(base_url('App/wine/edit/'.$wine->id), $attributes);
    ?>
	<div class="col-sm-6">

      	<fieldset>
    		<legend>Basic Info</legend>
    		<div class="form-group">
		  		<label for="inputName" class="col-sm-3 control-label">UPC Code *</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="upc_code" class="form-control" id="inputName" placeholder="Enter upc code" value="<?php echo $wine->upc_code; ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputName" class="col-sm-3 control-label">Product Name *</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="name" class="form-control" id="inputName" placeholder="Enter wine name" value="<?php echo $wine->name; ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="inputName" class="col-sm-3 control-label">Brand *</label>
		  		<div class="col-sm-7">
		  			<input type="text" name="brand" class="form-control" id="inputName" placeholder="Enter wine brand" value="<?php echo $wine->brand; ?>" required>
		  			<div class="help-block with-errors"></div>
		  		</div>
		  	</div>

			<div class="form-group">
				<label for="inputDiscount" class="col-sm-3 control-label">Year</label>
				<div class="col-sm-7">
					<input type="text" name="year" class="form-control" id="year" placeholder="Enter the year" value="<?php echo $wine->year; ?>">
					<div class="help-block with-errors"></div>
				</div>
			</div>

			<div class="form-group">
				<label for="inputSize" class="col-sm-3 control-label">Category *</label>
				<div class="col-sm-7">
					<select id="category_id" name="category_id" class="form-control" required>
		  				<option value="">Choose category</option>

		  				<?php foreach ($list as $item) {?>
						<option value="<?php echo $item['id'];?>" <?php if ($wine->category_id == $item['id']) {echo "selected";}?>><?php echo $item['name'];?></option>
						<?php }?>
		  			</select>
					<div class="help-block with-errors"></div>
				</div>
			</div>


			

			<div class="form-group">
				<label for="inputPic" class="col-sm-3 control-label">Image *</label>
				<div class="col-sm-7">
					<input type="file" name="pics[]" class="form-control" id="inputPic" placeholder="Upload an Image" multiple>
					<div class="help-block with-errors">Multiple images can be choosen.</div>
				</div>
			</div>
	  	</fieldset>
	</div>

	<div class="col-sm-6">
		<fieldset>
    		<legend>Other Info</legend>

			<div class="form-group">
		  		<label for="inputTag" class="col-sm-3 control-label">Type</label>
		  		<div class="col-sm-7">
			  		<input type="text" name="type" class="form-control" id="inputTag" placeholder="Enter type" value="<?php echo $wine->type; ?>">
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputDesc" class="col-sm-3 control-label">Description</label>
		  		<div class="col-sm-7">
			  		<textarea name="description" class="form-control" id="inputDesc" placeholder="Enter description"><?php echo $wine->description; ?></textarea>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

		  	<div class="form-group">
		  		<label for="inputTag" class="col-sm-3 control-label">Size *</label>
		  		<div class="col-sm-7">
		  		<div class="input-group">
			  		<input type="text" name="size" class="form-control" id="size" placeholder="Enter size" value="<?php echo $wine->size; ?>" required>
			  		<div class="input-group-addon">ml</div>
			  	</div>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
		  	<?php
		  		$flavour=$wine->flavour;
		  		$flavour_array=explode(',',$flavour);
		  	?>
		  	<div class="form-group">
		  		<label for="inputDesc" class="col-sm-3 control-label">Company</label>
		  		<div class="col-sm-7">
			  		<!--ROYAL<input type="checkbox" name="flavour[]" value="royal" checked required>
			  		MIX<input type="checkbox" name="flavour[]" value="mix" <?php //if(in_array('mix',$flavour_array)){echo 'checked';}?>>-->

			  		ROYAL<input type="radio" name="flavour" value="royal" <?php if(in_array('royal',$flavour_array)){echo 'checked';}?>>
			  		MYX<input type="radio" name="flavour" value="mix" <?php if(in_array('mix',$flavour_array)){echo 'checked';}?>>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>
            <?php
		  		$c_type=$wine->company_type;
		  		$c_type_array=explode(',',$c_type);
		  	?>
            <div class="form-group">
		  		<label for="inputDesc" class="col-sm-3 control-label">Company Type</label>
		  		<div class="col-sm-7">
			  		<!--ROYAL<input type="checkbox" name="flavour[]" value="royal" checked required>
			  		MIX<input type="checkbox" name="flavour[]" value="mix">-->
			  		Kayco&nbsp;<input type="checkbox" name="c_type[]" value="kayco" <?php if(in_array('kayco',$c_type_array)){echo 'checked';}?>>
                    &nbsp;
			  		Other&nbsp;<input type="checkbox" name="c_type[]" value="other" <?php if(in_array('other',$c_type_array)){echo 'checked';}?>>
			  		<div class="help-block with-errors"></div>
			  	</div>
		  	</div>

			<div class="form-group">
		  		<label for="" class="col-sm-3 control-label">Status</label>
		  		<div class="col-sm-7">
			  		<div class="radio">
					  <label for="checkboxActive">
					    <input type="radio" name="status" id="checkboxActive" value="active" <?php if($wine->status != "inactive") echo "checked";?>>
					    Active
					  </label>
					</div>
					<div class="radio">
					  <label for="checkboxinactive">
					    <input type="radio" name="status" id="checkboxinactive" value="inactive" <?php if($wine->status == "inactive") echo "checked";?>>
					    In-active
					  </label>
					</div>
				</div>
			</div>

		  	<div class="form-group">
		  		<div class="col-sm-offset-3 col-sm-6">
			  		<button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure to save the data?');"><span class="glyphicon glyphicon-ok-sign" ></span> Save</button> or <a href="<?php echo base_url('App/wine');?>">Cancel</a>
			  	</div>
		  	</div>
    	</fieldset>
	</div>


	<?php echo form_close();?>
</div>

<script type="text/javascript">
	$(function() {
  $('#year,#size').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
})
	$(document).ready(function(){
		//autocomplete off 
		$( document ).on( 'focus', ':input', function(){
			$( this ).attr( 'autocomplete', 'off' );
		});
	});
</script>