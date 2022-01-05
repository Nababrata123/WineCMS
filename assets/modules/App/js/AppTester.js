// App.js
jQuery(document).ready(function() {

	//jQuery('textarea#inputContent').wysihtml5();
});

function updateSearchFields(field, ope, q) {

	//alert(1);
	if (field == "") {
		return false;
	}

	jQuery.ajax({
		type: "POST",
		url: base_url+"App/tester/get_search_options/",
		data: "field="+field+"&ope="+ope+"&q="+q,
		async: true,
		success: function(response){
			//console.log(response);
			jQuery("#inputSearchWrapper").html(response.search_field);
			jQuery("#inputOperatorWrapper").html(response.search_ope);

			jQuery(".calender-control").datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: 'mm/dd/yy'
			});

			///jQuery('#customer-search-form').validator('update');
		}
	});

}
