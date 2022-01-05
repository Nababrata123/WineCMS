// Public.js
jQuery(document).ready(function() {

	jQuery('#inputQnty').on('keyup', function() {
		$qnty = jQuery(this).val();
		$rate = 99.00;
		$total = parseFloat($rate * $qnty).toFixed(2);
		//console.log($total);
		jQuery('#inputPrice').val($rate.toFixed(2));
		jQuery('#inputTotal').val($total);

	});

});
