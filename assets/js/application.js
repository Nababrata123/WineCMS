jQuery(document).ready(function() {
	jQuery("#checkall").on( "click", function() {
		jQuery(".checkbox-item").prop('checked', this.checked);
	});

	jQuery(".calender-control").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'mm/dd/yy'
	});

	jQuery(".status-form").submit(function(e) {

		var fields = jQuery("input[type='checkbox']").serializeArray();
		if (fields.length === 0) {

			jQuery('html, body').animate({
		        scrollTop: jQuery("#checkall").offset().top - 100
		    }, 500);
			jQuery('#checkall').popover({trigger: "click focus", content: "Please check atleast one record.", placement: "right"});
			jQuery('#checkall').popover('show');
			e.preventDefault();
		}
	});

	jQuery('.timepicker-control').timepicker({'timeFormat': 'h:i A'});

	jQuery('.popover-control').popover({'html': true, 'template': '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title text-primary"></h3><div class="popover-content"></div></div>'});

	jQuery("tr.deleted input, tr.deleted select, tr.deleted textarea, tr.deleted button").prop('disabled', true);

	jQuery("a.subModalButton").on( "click", function() {
		jQuery("#viewSubscriptionModal .modal-body").load(jQuery(this).attr('data-src'));
	});

});
function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type || 'json';
        var successRes = function(data) {
            success(data);
        }
        var errorRes = function(e) {
            console.log(e);
            alert("Error found \nError Code: " + e.status + " \nError Message: " + e.statusText);
        }
        jQuery.ajax({
            url: url,
            type: method,
            data: data,
            success: successRes,
            error: errorRes,
            dataType: type,
            timeout: 60000
        });
    }
}

function locationInfo() {
    var rootUrl = base_url+"home/location";
    var call = new ajaxCall();
    this.getCities = function(id) {
        jQuery(".cities option:gt(0)").remove();
        var url = rootUrl + '?type=getCities&stateId=' + id;
        var method = "post";
        var data = {};
        jQuery('.cities').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            jQuery('.cities').find("option:eq(0)").html("Select City");
            if (data.tp == 1) {
                jQuery.each(data['result'], function(key, val) {
                    var option = jQuery('<option />');
                    option.attr('value', key).text(val);
                    jQuery('.cities').append(option);
                });
                jQuery(".cities").prop("disabled", false);
            } else {
                alert(data.msg);
            }
        });
    };
    this.getStates = function(id, selectedState=null) {
        jQuery(".states option:gt(0)").remove();
        jQuery(".cities option:gt(0)").remove();
        var url = rootUrl + '?type=getStates&countryId=' + id;
        var method = "post";
        var data = {};
        jQuery('.states').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            jQuery('.states').find("option:eq(0)").html("Select State");
            if (data.tp == 1) {
                jQuery.each(data['result'], function(key, val) {
                    var option = jQuery('<option />');
                    option.attr('value', key).text(val);
                    if (selectedState != null && selectedState == key)
                        option.attr('selected', true);
                    jQuery('.states').append(option);
                });
                jQuery(".states").prop("disabled", false);
            } else {
                alert(data.msg);
            }
        });
    };
    this.getCountries = function(selectedCountry=null) {
        var url = rootUrl + '?type=getCountries';
        var method = "post";
        var data = {};
        jQuery('.countries').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            jQuery('.countries').find("option:eq(0)").html("Select Country");
            console.log(data);
            if (data.tp == 1) {
                jQuery.each(data['result'], function(key, val) {
                    var option = jQuery('<option />');
                    option.attr('value', key).text(val);
                    if (selectedCountry != null && selectedCountry == key)
                        option.attr('selected', true);
                    jQuery('.countries').append(option);
                });
                jQuery(".countries").prop("disabled", false);
            } else {
                alert(data.msg);
            }
        });
    };
}