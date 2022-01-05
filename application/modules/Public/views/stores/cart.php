<div class="container">
    <div class="page-header">
		<h1>
            My Cart
			<p><small>Manage your cart here.</small></p>
		</h1>
	</div>

    <?php echo form_open('stores/update_cart'); ?>
    <div class="table-responsive">
		<!-- Table -->
	    <table class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th colspan="2">Item Description</th>
                    <th>QTY</th>
                    <th class="text-right">Item Price</th>
                    <th class="text-right">Sub-Total</th>
                </tr>
            </thead>
            <?php $i = 1; ?>

            <tbody>
            <?php //print "<pre>"; print_r($this->cart->contents()); ?>
            <?php foreach ($this->cart->contents() as $items): ?>
                <?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>
                <tr>
                    <td width="80">
                        <?php if (isset($items['image']) && $items['image'] <> ""):?>
                            <img class="img-thumbnail img-responsive" style="width:80px;" alt="<?php echo $items['name'];?>" title="<?php echo $items['name'];?>" src="<?php echo base_url(DIR_PRODUCT_PICTURE_THUMB.$items['image']);?>">
                        <?php else: ?>
                            <img class="img-thumbnail img-responsive" style="width:80px;" alt="<?php echo $items['name'];?>" title="<?php echo $items['name'];?>" data-src="holder.js/200x100%" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDI0MiAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MjAwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTYwODMzYTQxNDEgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMnB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNjA4MzNhNDE0MSI+PHJlY3Qgd2lkdGg9IjI0MiIgaGVpZ2h0PSIyMDAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI4OS41IiB5PSIxMDUuMSI+MjQyeDIwMDwvdGV4dD48L2c+PC9nPjwvc3ZnPg==" data-holder-rendered="true">
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo base_url('stores/details/'.$items['id']);?>"><?php echo str_replace("_"," ",$items['name']); ?></a>
                        <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
                            <p>
                                <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>
                                    <strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />
                                <?php endforeach; ?>
                            </p>
                            <p><a href="<?php echo base_url('stores/remove_cart/'.$items['rowid']);?>"><span class="glyphicon glyphicon-ban-circle"></span> Remove</a></p>
                        <?php endif; ?>
                    </td>
                    <td><?php echo form_input(array('name' => $i.'[qty]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '3')); ?></td>
                    <td class="text-right"><?php echo $this->cart->format_number($items['price']); ?></td>
                    <td class="text-right">$<?php echo $this->cart->format_number($items['subtotal']); ?></td>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="3"><?php echo form_button(array('type' => 'submit', 'content' => "<span class='glyphicon glyphicon-refresh'></span> Update your Cart", 'class' => 'btn btn-primary')); ?> <?php echo form_button(array('content' => "Checkout <span class='glyphicon glyphicon-chevron-right'></span>", 'class' => 'btn btn-success', 'onclick' => "location.href='".base_url('stores/checkout')."'")); ?></td>
                    <td class="text-right"><strong>Total</strong></td>
                    <td class="text-right"><strong>$<?php echo $this->cart->format_number($this->cart->total()); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php echo form_close();?>

    <p>&nbsp;</p>
    
    <p>&nbsp;</p>
    <p>&nbsp;</p>

</div>