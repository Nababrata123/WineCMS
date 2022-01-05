<div class="container">
    <div class="page-header">
		<h1>
            <?php echo $product->name; ?>
		</h1>
	</div>

    <?php
		//form validation
		echo validation_errors();

		$attributes = array('class' => '', 'id' => '', 'role' => 'form', 'data-toggle' => 'validator');
		echo form_open(base_url('stores/add_to_cart'), $attributes);
    ?>
    <div class="row">
        <div class="col-sm-3">
            <?php if (count($images) > 0) { ?>
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <?php foreach($images as $key => $item) { ?>
                        <li data-target="#carousel-example-generic" data-slide-to="<?php echo $key;?>" <?php echo ($key==0?'class="active"':'');?>></li>
                    <?php } ?>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <?php foreach($images as $key => $item) { ?>
                    <div class="item <?php echo ($key==0?'active':'');?>">
                        <img style="height:200px" alt="<?php echo $item->title;?>" title="<?php echo $item->title;?>" src="<?php echo base_url(DIR_PRODUCT_PICTURE_THUMB.$item->image);?>">
                        <div class="carousel-caption">
                            <?php echo $item->title;?>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <?php }?>

            <?php 
                if (count($images) == 0) { 
                    echo '<img alt="'.$product->name.'" title="'.$product->name.'" data-src="holder.js/200x100%" style="height:200px;width:100%;display:block;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDI0MiAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MjAwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTYwODMzYTQxNDEgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMnB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNjA4MzNhNDE0MSI+PHJlY3Qgd2lkdGg9IjI0MiIgaGVpZ2h0PSIyMDAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI4OS41IiB5PSIxMDUuMSI+MjQyeDIwMDwvdGV4dD48L2c+PC9nPjwvc3ZnPg==" data-holder-rendered="true">';
                } 
            ?>
        </div>
        <div class="col-sm-9">
            <p><?php echo $product->description;?></p>
            <h4><?php echo price_display($product->price);?></h4>
            
            <?php if ($product->discount > 0) {?>
                <p><strong>Discount: <?php echo price_display($product->discount);?></strong></p>
            <?php }?>
            
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <select name="size" class="form-control" required>
                            <option value="" selected>Select a size</option>
                            <?php foreach($sizes as $size) { ?>
                                <?php if (in_array($size->id, explode(",",$product->size_ids))) {?>
                                    <option value="<?php echo $size->name;?>"><?php echo $size->name;?></option>
                                <?php }?>
                            <?php }?>					
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <p>&nbsp;</p>
            <p>
                <input type="hidden" name="product_id" value="<?php echo $product->id;?>">
                <button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span> Add to Cart</button>
            </p>
        </div>
    </div>
    <?php echo form_close();?>
    
    <p>&nbsp;</p>
    <p>&nbsp;</p>

</div>