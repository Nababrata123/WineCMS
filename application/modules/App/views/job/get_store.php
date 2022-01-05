<option value="">Select store</option>
<?php
    foreach($store as $value){
?>
<option value="<?php echo $value['id'];?>" <?php if(isset($hidden_store) && $value['id'] == $hidden_store){echo 'selected';}?>><?php echo $value['name'];?></option>
<?php } ?>