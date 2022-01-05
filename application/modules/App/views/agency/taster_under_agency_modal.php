<div class="modal-content">
	<div class="modal-header bg-info">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Tasters under agency</h4>
	</div>
  
	<div class="modal-body">
		<div class="form-group">
			<div class="col-md-12" style="padding:10px 0;">
				<ul>		  					
				<?php
				if(count($tester_details) > 0){
					$i=0;
					foreach($tester_details as $taster){
						$i++;
				?>
					<li><strong><?php echo $taster->full_name;?></strong></li>
				<?php
					}
				}else{
				?>
					<li><strong>No taster under this agency.</strong></li>
				<?php
				}
				?>
		  		</ul>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
	</div>
</div>
  