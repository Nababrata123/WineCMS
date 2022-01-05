// Admin.js
jQuery(document).ready(function() {

	jQuery('#inputRole').on('change', function() {
		$token = jQuery("#inputRole option:selected").attr('data-token');
		updateRole($token);
	});

	if (jQuery("#inputRole").attr('type') == 'hidden') {
		updateRole(jQuery("#inputRole").attr('data-token'));
	} else {
		updateRole(jQuery("#inputRole option:selected").attr('data-token'));
	}
});

function updateRole($token) {

	//alert($token);
	if($token == 'bar_admin') {
		jQuery("#inputLocation").parent().parent().removeClass('hide');
		jQuery("#inputLocation").prop('required',true);
	} else {
		jQuery("#inputLocation").parent().parent().addClass('hide');
		jQuery("#inputLocation").prop('required',false);
	}
}
