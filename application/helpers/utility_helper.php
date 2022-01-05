<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if(!function_exists('datetime_display')){
    function datetime_display($date, $default = '')
    {
    	if ($date == "" || $date == "0000-00-00 00:00:00") {
            if ($default == '') {
                return;
            } else {
                return $default;
            }

    	}

    	return date("m/d/Y h:i a", strtotime($date));
    }
}

if(!function_exists('date_display')){
	function date_display($date)
    {
    	if ($date == "" || $date == "0000-00-00") {
    		return;
    	}

    	return date("m/d/Y", strtotime($date));
    }
}


if(!function_exists('time_display')){
	function time_display($time, $meridiem = false, $blank = false)
    {
    	if ($time == "") {
    		return;
    	}
    	if ($meridiem)
			$format = "H:i a";
		else
			$format = "H:i";
    	$time = date($format, strtotime($time));

		if ($time == "00:00" && !$blank) {
			$time = "";
		}
		return $time;
    }
}

if(!function_exists('phone_display')){
	function phone_display($phone)
    {
        $output = "";
        if ($phone <> "") {
            $output = '<a href="callto:'.$phone.'">'.$phone.'</a>';
        }
        return $output;
    }
}

if(!function_exists('email_display')){
	function email_display($email)
    {
        $output = "";
        if ($email <> "") {
            $output = '<a href="mailto:'.$email.'">'.$email.'</a>';
        }
        return $output;
    }
}

if(!function_exists('name_display')){
	function name_display($firstname, $middlename, $lastname)
    {
        $output = "";
        if ($firstname <> "") {
            $output .= $firstname." ";
        }
        if ($middlename <> "") {
            $output .= $middlename." ";
        }
        if ($lastname <> "") {
            $output .= $lastname;
        }
        return $output;
    }
}

if(!function_exists('get_age')){
	function get_age($dob)
    {
        if ($dob == '') {
            return '';
        }
        $from = new DateTime($dob);
        $to   = new DateTime('today');
        return $from->diff($to)->y;
    }
}

if(!function_exists('order_status_display')){
	function order_status_display($status)
    {
        if ($status == "pending") {
            return '<span class="text-muted">Pending</span>';
        } else if ($status == "packed") {
            return '<span class="text-warning">Packed</span>';
        } else if ($status == "shipped") {
            return '<span class="text-info">Shipped</span>';
        } else if ($status == "complete") {
            return '<span class="text-success">Delivered</span>';
        } else if ($status == "canceled") {
            return '<span class="text-danger">Cancelled</span>';
        }
    }
}

if(!function_exists('order_progressbar')){
	function order_progressbar($status)
    {
        if ($status == "pending") {
            return '<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width:25%;">Pending</div></div>';
        } else if ($status == "packed") {
            return '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%;">Packed</div></div>';
        } else if ($status == "shipped") {
            return '<div class="progress"><div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:75%;">Shipped</div></div>';
        } else if ($status == "complete") {
            return '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%;">Delivered</div></div>';
        } else if ($status == "canceled") {
            return '<div class="progress"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%;">Cancelled</div></div>';
        } 
    }
}

if(!function_exists('status_display')){
    function status_display($status)
    {
        if ($status == "active") {
            return '<span class="label label-success">Active</span>';
        } else {
            return '<span class="label label-warning">In-active</span>';
        }
    }
}

if(!function_exists('status_view')){
    function status_view($status)
    {
        if ($status == "1") {
            return '<span class="label label-success">Active</span>';
        } else {
            return '<span class="label label-warning">In-active</span>';
        }
    }
}

if(!function_exists('payment_status_display')){
	function payment_status_display($status)
    {
        if ($status == "complete") {
            return '<span class="label label-success">Complete</span>';
        } else {
            return '<span class="label label-warning">Pending</span>';
        }
    }
}


if(!function_exists('price_display')){
	function price_display($price)
    {
        if ($price <> "") {
            return CURRENCY_SYMBOL.number_format($price,2);
        } else {
            return CURRENCY_SYMBOL."0";
        }
    }
}
