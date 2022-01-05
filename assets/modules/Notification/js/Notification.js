// App.js
jQuery(document).ready(function() {

	jQuery("input[name='type']").click(function() {
	    if (jQuery("input[name='type']:checked").val() == "customers") {
	       jQuery('#customerDetails').show();
	    }
	    else {
	      jQuery('#customerDetails').hide();
	    }
	});

	jQuery("#previewBtn").click(function() {

		jQuery("#usersPreview").html('<span class="glyphicon glyphicon-refresh"></span> Loading users..');
		$data = jQuery('#send-notify-form').serialize();

		jQuery.ajax({
	        type: "POST",
	        url: base_url+"notification/notifications/get_users/",
	        data: $data,
	        async: true,
	        success: function(response){

				$table = '<table class="table table-striped table-responsive" width="100%">';
				$table += '<tbody>';

				$i = 0;
				$.each(response.users, function( index, item ) {
					$table += '<tr>';
					$table += '<td><input type="checkbox" checked name="users['+$i+'][id]" class="checkbox-item" value="'+item.id+'"></td>';
					$table += '<td>'+item.first_name+' '+item.last_name+' ('+item.email+')<input type="hidden" name="users['+$i+'][name]" value="'+item.first_name+' '+item.last_name+'"><input type="hidden" name="users['+$i+'][email]" value="'+item.email+'"></td>';
					$table += '</tr>';
					$i++;
				});
				$table += '</tbody>';
				$table += '</table>';

				jQuery("#usersPreview").html($table);
	        	//console.log(response);
	        }
	    });
	});

});

function viewRecipients(id, type) {

	if (id == "") {
		return;
	}

	jQuery("#notificationUsersModal tbody").html('<span class="glyphicon glyphicon-refresh"></span> Loading..');

	jQuery.ajax({
		type: "POST",
		url: base_url+"notification/notifications/get_notification_users/"+id,
		data: '&type='+type,
		async: true,
		success: function(response){

			$table = '';
			$.each(response.users, function( index, item ) {
				$table += '<tr>';
				$table += '<td>'+item.name+'</td>';
				$table += '<td>'+item.email+'</td>';
				$table += '<td>';
				if (item.status == 'unread') {
					$table += 'Unread';
				} else {
					$table += 'Read';
				}
				$table += '</td>';
				$table += '</tr>';
			});
			jQuery("#notificationUsersModal tbody").html($table);
			//console.log(response);
		}
	});
}
