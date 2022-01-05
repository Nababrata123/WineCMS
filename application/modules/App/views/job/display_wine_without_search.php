
<?php
foreach($wine as $value){
?>
<div class="col-md-12" style="margin:6px 0 0 0;"><input type="checkbox" name="wine_id[]" style="margin:3px 2px 0 0; float:left;" class="wine_id" value="<?php echo $value->id;?>"><label><?php echo $value->name;?></label></div>

<?php 
	} 
?>

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
    /*var pre_wine=$('#fulloptions').val();
    var sep=",";
    $('#fulloptions').val(
        checkboxes.filter(':checked').map(function(item) {
            var w_id=$(this).val();
            var t=$(this).next('label').text();
            $('input[name=wine_id]').val(w_id);
            return t+sep;
            //return this.value;
        }).get().join(', ')+pre_wine
     );*/
    
});
</script>