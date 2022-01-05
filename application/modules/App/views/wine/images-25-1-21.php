<div class="subnav">
	<div class="container-fluid">
    	<h1><span class="glyphicon glyphicon-picture"></span> Images Management &raquo; <small> <?php echo character_limiter($wine->name, 50);?></small></h1>
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
		<li class="active">Images</li>
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

	<div class="row">
		<div class="col-sm-12">
			<?php
				$attributes = array('class' => 'form-inline', 'id' => 'frmImages', 'data-toggle' => 'validator');
				echo form_open_multipart(base_url('App/wine/images_upload/'.$wine_id), $attributes);
			?>
  				<div class="form-group">
				    <input type="file" name="image" class="form-control" accept="image/jpeg, image/png" id="uploadfile" required >
					<div class="help-block with-errors">&nbsp;</div>
				</div>
				<div class="form-group">
				    <input type="text" id="inputTitle" name="title" class="form-control" placeholder="Enter a title" required >
					<div class="help-block with-errors">&nbsp;</div>
				</div>
				<div class="form-group">
				    <input type="hidden" id="inputOrder" name="order" class="form-control" placeholder="Enter order" value="0" required >
					<div class="help-block with-errors">&nbsp;</div>
				</div>
				<div class="form-group">
					<input type="hidden" name="wine_id" value="<?php echo $wine_id;?>">
					<input type="hidden" name="image_id" id="inputID">
					<input type="hidden" name="action" id="inputAction" value="Add">
				    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Save Image</button>
					<button type="button" class="btn btn-default" onclick="window.location='<?php echo base_url('App/wine/images/'.$wine_id);?>'"><span class="glyphicon glyphicon-refresh"></span> Reset</button>
					<div class="help-block with-errors">&nbsp;</div>
				</div>

			<?php echo form_close();?>
		</div>
	</div>

	<div class="images row">
		<?php foreach($list as $item) { ?>
		<div class="col-md-3 text-center" id="item-<?php echo $item->id;?>" style="cursor:move">
			<p><img class="img-responsive img-thumbnail" alt="<?php echo $item->title;?>" title="<?php echo $item->title;?>" src="<?php echo base_url(DIR_WINE_PICTURE_THUMB.$item->image);?>"></p>
			<p><?php echo $item->title;?></p>

			<a class="btn btn-primary btn-xs" id="btnImageEdit_<?php echo $item->id;?>" href="javascript:;" onclick="editImages('<?php echo $item->id;?>');" title="Edit">
		    	<span class="glyphicon glyphicon-edit"></span> Edit
		    </a>

			<a class="btn btn-danger btn-xs" href="<?php echo base_url('App/wine/images_delete/'.$wine_id.'/id/'.$item->id);?>" onclick="return confirm('Are you sure to delete this?');" title="Delete">
		    	<span class="glyphicon glyphicon-remove"></span> Delete
		    </a>
		</div>
		<?php } ?>
	</div>
</div>

<script>
	function editImages(id) {

		$ele = "#btnImageEdit_"+id;
		jQuery($ele).html('<span class="glyphicon glyphicon-refresh"></span> Loading..');
		//Note: Do the work
		jQuery.ajax({
			type: "POST",
			url: base_url+"App/Wine/images_details/"+id,
			async: true,
			success: function(response){
				console.log(response);
				$id = response.id;
				$title = response.title;
				$file = response.image;
				$order = response.order;

				jQuery("#inputAction").val("Edit");
				jQuery("#inputID").val($id);
				jQuery("#inputTitle").val($title);
				jQuery("#inputOrder").val($order);
				jQuery("#uploadfile").attr("required", false);
				jQuery("#uploadfile").siblings(".help-block").html($file);

				jQuery("#frmImages div").effect("highlight", {}, 3000);
				window.scrollTo(0, 0);
				jQuery("#frmImages").validator('validate'); // validate the form again
				jQuery($ele).html('<span class="glyphicon glyphicon-edit"></span> Edit');
			}
		});
	}

	jQuery(function(){
    	jQuery(".images").sortable({
			//axis: 'x',
			opacity: 0.8,
			cursor: "move",
			placeholder: "sortable-placeholder",
    		update: function( event, ui ) {
				var data = jQuery(this).sortable( "serialize", { key: "sortable[]" } );
				//console.log(data);

				jQuery.ajax({
					type: "POST",
					url: base_url+"app/wine/images_order/",
					data: data,
					async: true,
					success: function(response){
						alert(response);
						
					}
				});
			}
		});
		 	

		/*var sorted = $(".images").sortable( "serialize", { key: "sort" } );
		console.log(sorted);*/
  	});
  	$("#uploadfile").change(function(){
  		var ext = $('#uploadfile').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
		    alert('You have to upload gif , png , jpg , jpeg file format!');
		   $("#uploadfile").val(''); 
		    return false;
		}
		else
		{
			return true;
		}
  	});
</script>
