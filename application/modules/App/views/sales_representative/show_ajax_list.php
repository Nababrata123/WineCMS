<?php if (count($users) == 0) { ?>
	            <tr>
	            	<td colspan="100%">Sorry!! No Records found.</td>
	            </tr>
	            <?php } ?>
	            <?php foreach($users as $item) { ?>
	            <tr>
	            	<td><input type="checkbox" name="item_id[<?php echo $item->id;?>]" class="checkbox-item" value="Y"></td>
	            	<td><?php echo $item->first_name . " " .  $item->last_name;?> <?php echo ($item->id == $this->session->userdata('id'))?'<span class="text-primary">(you)</span>':'';?></td>
	            	<td><?php echo $item->email;?></td>
	            	
					<td><?php echo ($item->last_login)?datetime_display($item->last_login):'--'?></td>
	            	<td>
	            	<?php
	            		if ($item->status == "active") {
	            			echo '<span class="label label-success">Active</span>';
	            		} else {
	            			echo '<span class="label label-warning">In-active</span>';
	            		}
	            	?>
	            	</td>
	            	<td>
	            		<a class="btn btn-info btn-xs" href="javascript:void(0)" title="View" onclick="open_modal(<?php echo $item->id;?>)">
	            			<span class="glyphicon glyphicon-eye-open"></span> View
	            		</a>
	            	</td>
	            	<td>
						<a class="btn btn-info btn-xs" href="<?php echo base_url('App/sales_representative/reset_pass/'.$item->id);?>" onclick="return confirm('Do you really want to reset the password for this user?');" title="Reset Password">
	            			<span class="glyphicon glyphicon-lock"></span> Reset Password
	            		</a>
	            		<a class="btn btn-primary btn-xs" href="<?php echo base_url('App/sales_representative/edit/'.$item->id);?>" title="Edit">
	            			<span class="glyphicon glyphicon-edit"></span> Edit
	            		</a>
	            		<!--<a class="btn btn-danger btn-xs <?php echo ($item->id == $this->session->userdata('id'))?'disabled':'';?>" href="<?php echo base_url('App/sales_representative/delete/'.$item->id);?>" onclick="return confirm('Are you sure you want to delete this user account?');" title="Delete">
	            			<span class="glyphicon glyphicon-trash"></span> Delete
	            		</a>-->
                        <a class="btn btn-danger btn-xs delete_button <?php echo ($item->id == $this->session->userdata('id'))?'disabled':'';?>" href="javascript:void(0)"  title="Delete" data-id="<?php echo $item->id;?>">
	            			<span class="glyphicon glyphicon-trash"></span> Delete
	            		</a>
	            	</td>
	            </tr>
	            <?php } ?>