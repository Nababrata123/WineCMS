<?php
foreach($wine as $value){
?>
	<option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
<?php 
	} 
?>