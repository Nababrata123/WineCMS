jQuery(document).ready(function() {
	jQuery("#checkall").click(function() {
		jQuery(".checkbox-item").prop('checked', this.checked);
	});

	jQuery(".calender-control").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'mm/dd/yy'
	});

    jQuery(".public-calender-control").datepicker({
		changeMonth: true,
		changeYear: true,
        dateFormat: 'mm/dd/yy',
        maxDate: "-16y",
        yearRange: "c-50:c+0"
    });
    
	jQuery('.timepicker-control').timepicker({'timeFormat': 'h:i A'});

	jQuery('.popover-control').popover({'html': true});

	jQuery("tr.deleted input, tr.deleted select, tr.deleted textarea, tr.deleted button").prop('disabled', true);

	
});

function showNotifications() {
    var url = base_url+"account/get_notifications";
    var call = new ajaxCall();
    var method = "post";
    var data = {};
    
    call.send(data, url, method, function(response) {
        $html = '<dl>';
		jQuery.each(response.notifications, function( index, item ) {
			$html += '<a href="javascript:;" data-target="#notificationDetailsModal" data-toggle="modal" onclick="viewNotification('+item.id+')">';
			if (item.status == 'unread') {
				$html += '<dt>'+item.subject+'</dt>';
			} else {
				$html += '<dd>'+item.subject+'</dd>';
			}
			$html += '</a>';
			$html += '<dd>'+item.description.replace(/<\/?[^>]+(>|$)/g, "").substring(0, 50)+'</dd>';
			$html += '<dd class="text-right"><a href="javascript:;" data-target="#notificationDetailsModal" data-toggle="modal" onclick="viewNotification('+item.id+')">view more <i class="fa fa-arrow-right"></i></a></dd>';
		});
		if (response.notifications.length == 0) {
			$html += '<dd>All caught up!</dd>';
		}
		$html += '</dl>';

		if (response.unread > 0) {
			jQuery("#notificationBtn .badge").html(response.unread);
		}
		jQuery("#notificationBtn").attr('data-content', $html);
    });

}


function viewNotification(id) {

	if (id == "") {
		return;
	}

	jQuery("#notificationDetailsModal .modal-body").html('<span class="glyphicon glyphicon-refresh"></span> Loading..');

    var url = base_url+"account/get_notification_details/"+id;
    var call = new ajaxCall();
    var method = "post";
    var data = {};
    
    call.send(data, url, method, function(response) {
        jQuery("#notificationDetailsModal .modal-title").html(response.notification.subject);

		$html = '';
		$html += '<p class="text-right"><small>Sent on '+response.notification.created_on+'</p>';
		$html += '<p>'+response.notification.description+'</p>';
        $html += '<p></p>';

        jQuery.each(response.videos, function( index, item ) {
			
			$html += '<p><a href="'+base_url+'account/library_details/'+item.video_id+'">Watch video</a></p>';
		});
		jQuery("#notificationDetailsModal .modal-body").html($html);
    });
}
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

// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
    //console.log('statusChangeCallback');
    //console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
        // Logged into your app and Facebook.
        getAPI();
    } else if (response.status === 'not_authorized') {
		// The person is logged into Facebook, but not your app.
		alert('Please log into this app.');
	} else {
        // The person is not logged into your app or we are unable to tell.
        alert('Please log into Facebook.');
    }
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
/*function checkLoginState() {
    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    });
}*/

window.fbAsyncInit = function() {
    FB.init({
        appId      : '554803701534806',
        cookie     : true,  // enable cookies to allow the server to access 
                        // the session
        xfbml      : true,  // parse social plugins on this page
        version    : 'v2.11' // use graph api version 2.8
    });

    // Now that we've initialized the JavaScript SDK, we call 
    // FB.getLoginStatus().  This function gets the state of the
    // person visiting this page and can return one of three states to
    // the callback you provide.  They can be:
    //
    // 1. Logged into your app ('connected')
    // 2. Logged into Facebook, but not your app ('not_authorized')
    // 3. Not logged into Facebook and can't tell if they are logged into
    //    your app or not.
    //
    // These three cases are handled in the callback function.

    /*FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    });*/

};

// Load the SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function getAPI() {
    //console.log('Welcome!  Fetching your information.... ');
    FB.api('/me?fields=id,first_name,last_name,email,picture,address,birthday', function(response) {
        console.log(response);
        //alert('Thanks for logging in, ' + response.first_name + response.last_name + '!');


        jQuery.ajax({
	        type: "POST",
	        url: base_url+"home/do_social_login",
	        data: "&social_id="+response.id+"&first_name="+response.first_name+"&last_name="+response.last_name+"&email="+response.email+"&source=fb",
	        async: false,
	        success: function(response){
	            if (response['success']) //success
	            {
	                setTimeout(function(){ location.reload(); }, 1000);
	            } 
                alert(response['message']);
	        }
	    });
    });
}

function fb_login() {
	FB.login(function(response) {
		// Set the access token for future use
		deleteCookie("user_acess_token");
		setCookie("user_acess_token", response['authResponse'].accessToken, 1);

		statusChangeCallback(response);
	}, {
		scope: 'public_profile,email,publish_actions'
	});
}


// Cookie setter
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// Cookie Getter
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

// Cookie Delete
function deleteCookie(cname) {
	document.cookie = cname + "=;expires=Wed 01 Jan 1970";
}