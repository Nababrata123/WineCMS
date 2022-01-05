<?php
//$wine_id_array=explode(",",$wine_id);
if($this->session->userdata('wine_ids'))
{
    //echo 1;die;
    $wine_ids=$this->session->userdata('wine_ids');
    
}
else
{
   // echo 2;die;
    $wine_ids=array();
}
foreach($wine as $value){
?>
<div class="col-md-12" style="margin:6px 0 0 0;"><input type="checkbox" name="wine_id[]" style="margin:3px 2px 0 0; float:left;" class="wine_id" value="<?php echo $value->id;?>" <?php if(in_array($value->id,$wine_ids)){?>checked="checked"<?php}?>><label><?php echo $value->name;?></label></div>
<?php 
	} 
?>
<div class="help-block with-errors"></div>
<script>
var checkboxes = $("#wines  input[type='checkbox']");

checkboxes.on('change', function() {
    //Set the wine id to session using ajax
    var selected_wine=$(this).val();
    wine_id_array.push(selected_wine);
    $.ajax({
		   type:'POST',
		   url:"<?php echo base_url(); ?>App/job/set_wine_id_ajax/",
		   data: {wine_id_array:wine_id_array},
           success:function(data){
		    	$("#fulloptions").val(data);
		   }
		   
	});
    //End
    /*$('#fulloptions').val(
        checkboxes.filter(':checked').map(function(item) {
            var t=$(this).next('label').text();
            var w_id=$(this).val();
            //$(".wine_id").val(w_id);
            $('input[name=wine_id]').val(w_id);
            return t;
            //return this.value;
        }).get().join(', ')
     );*/
});
</script>