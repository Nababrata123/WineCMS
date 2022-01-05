<div class="container">
    <div class="page-header">
		<h1>
            Stores
			<p><small>Store section is currently under development. Exclusive deals
and products will be coming soon.</small></p>
		</h1>
	</div>

</div>

<?php
exit;
?>

    <div class="row">
        <div class="col-sm-12 text-left">
            <h3>Latest Products</h3>
        </div>

        <?php foreach($products as $product) { ?>
        <div class="col-sm-6 col-md-3">
            <div class="thumbnail">
                <?php
					if ($product->image == "") {
						echo '<img alt="'.$product->name.'" title="'.$product->name.'" data-src="holder.js/200x100%" style="height:200px;width:100%;display:block;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDI0MiAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MjAwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTYwODMzYTQxNDEgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMnB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNjA4MzNhNDE0MSI+PHJlY3Qgd2lkdGg9IjI0MiIgaGVpZ2h0PSIyMDAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI4OS41IiB5PSIxMDUuMSI+MjQyeDIwMDwvdGV4dD48L2c+PC9nPjwvc3ZnPg==" data-holder-rendered="true">';
					} else {
						echo '<img alt="'.$product->name.'" title="'.$product->name.'" class="img-responsive" style="max-width:100%; height:200px" src="'.base_url(DIR_PRODUCT_PICTURE_THUMB.$product->image).'">';
					}
				?>
                <!---->
                <div class="caption">
                    <h4><a href="<?php echo base_url('stores/details/'.$product->id);?>"><?php echo character_limiter($product->name, 30);?></a></h4>
                    <p><strong><?php echo price_display($product->price);?></strong></p>
                    <!--<p><?php echo character_limiter($product->description, 70);?></p>-->
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="col-sm-12 text-right">
            <p><a href="#">view more..</a></p>
        </div>
    </div>
    
    <p>&nbsp;</p>
    <p>&nbsp;</p>

</div>