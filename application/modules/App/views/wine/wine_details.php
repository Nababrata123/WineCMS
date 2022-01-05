
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Wine Details</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">
			<div class="col-md-12">
				<label class="col-sm-3 control-label">Brand:</label>
				<div class="col-sm-2"><?php echo $wine->brand;?></div>
          
				<label class="col-sm-3 control-label">Category:</label>
				<div class="col-sm-2"><?php echo $category_name;?></div>
			</div>
			<div class="col-md-12">
				<label class="col-sm-3 control-label">Company:</label>
				<div class="col-sm-3"><?php echo ($wine->flavour=='mix'?'MYX':$wine->flavour);?></div>
          
				<!-- <label class="col-sm-4 control-label">Company Type:</label>
				<div class="col-sm-2"><?php echo $wine->company_type;?></div> -->
			</div>
			<div class="col-md-12">
				<label class="col-sm-3 control-label">Total bottles sampled:</label>
				<div class="col-sm-3"><?php echo $bottles_sampled;?></div>
          
				<label class="col-sm-3 control-label">Total bottles sold:</label>
				<div class="col-sm-3"><?php echo $bottles_sold;?></div>
			</div>
			<div class="col-md-12">
				<label class="col-sm-3 control-label">Total Opened bottles sampled:</label>
				<div class="col-sm-3"><?php echo $open_bottles_sampled;?></div>
			</div>
			<div class="col-md-12">
				<label class="col-sm-3 control-label">Description:</label>
				<div class="col-sm-9"><?php echo $wine->description;?></div>
			</div>
          
        </div>
        
        
        
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
  