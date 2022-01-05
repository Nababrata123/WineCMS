<style>
div.modal-footer {
  text-align: center;
}
</style>
<!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Accept or Reject Job</h4>
      </div>
      <p id="error"></p>
      
	  <?php $date_now = date("Y-m-d");?>
    <?php if($date_now > $tasting_date) {?>
        <div class="modal-body">
        <div class="form-group">
            <div style="text-align: center;">You cannot accept/reject the job. The tasting date is over.</div>
        </div>
      </div>
    <div class="modal-footer">
       <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
      <?php }else{?>
    <input type="hidden" id="user_id" name="user_id" value="<?php echo $agency_id;?>">
    <input type="hidden" id="job_id" name="job_id" value="<?php echo $job_id;?>">
      <div class="modal-body">
        <div class="form-group">
          <div class="col-md-12" style="padding:10px 0;">
            <label class="col-sm-3 control-label">Accept:</label>
            <div class="col-sm-3" ><input type="radio" name="accept" value="1" checked="checked"></div>
          
            <label class="col-sm-3 control-label">Reject:</label>
            <div class="col-sm-3"><input type="radio" name="accept" value="0"></div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <input type="submit" class="btn btn-warning" id="approve" name="approve" value="Submit">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
      <?php }?>
      
    </div>
  
<script type="text/javascript">
	$("#approve").click(function(){
		var radioValue = $("input[name='accept']:checked").val();
		var user_id=$("#user_id").val();
		var job_id=$("#job_id").val();
		$.ajax({
			type:'POST',
			url:"<?php echo base_url(); ?>Agency/job/accept_job/",
			data: {job_id:job_id,user_id:user_id,accept:radioValue},
			success:function(data){
				var obj = jQuery.parseJSON(data);
				// alert(obj.res);
				if(obj.res === "success")
				{
				//alert('vv');
				$('#myModal').modal('hide');
				location.reload();
				}
				else if(obj.res === "rejected")
				{
				var errorDiv = '<div class="alert alert-warning"><strong>You have already rejected the job.</strong></div>';
				$("#error").html(errorDiv);
				}
				else
				{
				var errorDiv = '<div class="alert alert-warning"><strong>The job is accepted by other taster.You can not accept the job!</strong></div>';

				//$('#myModal').modal('hide');
				$("#error").html(errorDiv);
				}
			}
		});
	});
</script>