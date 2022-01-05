<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


//My custom constant
defined('BASE_URL')      						OR define('BASE_URL', 'http://localhost/wine/');
defined('HTTP_CSS_PATH')      					OR define('HTTP_CSS_PATH', BASE_URL.'assets/css/');
defined('HTTP_IMAGES_PATH')      				OR define('HTTP_IMAGES_PATH', BASE_URL.'assets/images/');
defined('HTTP_JS_PATH')      					OR define('HTTP_JS_PATH', BASE_URL.'assets/js/');
defined('HTTP_ROYALWINEDATA_PATH')      		OR define('HTTP_ROYALWINEDATA_PATH', BASE_URL.'oU6iW5oZ0fI8hV9z/RoyalWineData.csv');  //Royal Wine data path 


defined('THEME')      							OR define('THEME', 'default');
defined('TEMPLATE_PATH')      					OR define('TEMPLATE_PATH', 'templates/'.THEME.'/template');
defined('PUBLIC_THEME')      					OR define('PUBLIC_THEME', 'public');
defined('PUBLIC_TEMPLATE_PATH')      			OR define('PUBLIC_TEMPLATE_PATH', 'templates/'.PUBLIC_THEME.'/template');
defined('HTTP_THEME_PATH')      				OR define('HTTP_THEME_PATH', BASE_URL.'assets/themes/'.THEME.'/');
defined('HTTP_PUBLIC_THEME_PATH')      			OR define('HTTP_PUBLIC_THEME_PATH', BASE_URL.'assets/themes/'.PUBLIC_THEME.'/');

defined('DIR_THEME')      						OR define('DIR_THEME', 'assets/themes/'.THEME.'/');
defined('DIR_CSS')      						OR define('DIR_CSS', 'assets/css/');
defined('DIR_JS')      							OR define('DIR_JS', 'assets/js/');

defined('CURRENCY_SYMBOL')      				OR define('CURRENCY_SYMBOL', '$');
defined('RECORD_PER_PAGE')     	 				OR define('RECORD_PER_PAGE', 10);

defined('DIR_PROFILE_PICTURE')      			OR define('DIR_PROFILE_PICTURE', 'assets/profile/');
defined('DIR_PROFILE_PICTURE_THUMB')      		OR define('DIR_PROFILE_PICTURE_THUMB', 'assets/profile/thumb/');

defined('PROFILE_THUMB_SIZE')      	        	OR define('PROFILE_THUMB_SIZE', 140);

defined('SITE_NAME')      						OR define('SITE_NAME', 'WINE');
defined('CONTACT_EMAIL')      					OR define('CONTACT_EMAIL', 'vj.avalgate@gmail.com');
defined('NO_REPLY_EMAIL')      					OR define('NO_REPLY_EMAIL', 'no-reply@wine.com');
defined('RESET_PASSWORD_LINK_VALIDITY')			OR define('RESET_PASSWORD_LINK_VALIDITY', 3600); // 1 Hour

defined('DIR_WINE_PICTURE')      			OR define('DIR_WINE_PICTURE', 'assets/wine/');
defined('DIR_WINE_PICTURE_THUMB')      		OR define('DIR_WINE_PICTURE_THUMB', 'assets/wine/thumb/');
defined('WINE_PICTURE_THUMB_SIZE')      		OR define('WINE_PICTURE_THUMB_SIZE', 181);

defined('DIR_STORE_LOGO')      			OR define('DIR_STORE_LOGO', 'assets/store/');
defined('DIR_STORE_LOGO_THUMB')      		OR define('DIR_STORE_LOGO_THUMB', 'assets/store/thumb/');
defined('STORE_LOGO_SIZE')      		OR define('STORE_LOGO_SIZE', 181);
//For manager verification details
defined('DIR_SIGNATURE_IMAGE')      			OR define('DIR_SIGNATURE_IMAGE', 'assets/wine_signature_image/');

defined('DIR_SIGNATURE_IMAGE_SIZE')      		OR define('DIR_SIGNATURE_IMAGE_SIZE', 181);

//For expense details
defined('DIR_EXPENSE_IMAGE')      			OR define('DIR_EXPENSE_IMAGE', 'assets/wine_expense_details_image/');

defined('DIR_EXPENSE_IMAGE_SIZE')      		OR define('DIR_EXPENSE_IMAGE_SIZE', 181);

//For question answer images
defined('DIR_QUESTION_ANSWER_IMAGE')      			OR define('DIR_QUESTION_ANSWER_IMAGE', 'assets/wine_question_answer_image/');

defined('DIR_TASTING_SETUP_IMAGE')      			OR define('DIR_TASTING_SETUP_IMAGE', 'assets/wine_tasting_setup_image/');

defined('DIR_QUESTION_ANSWER_IMAGE_SIZE')      		OR define('DIR_QUESTION_ANSWER_IMAGE_SIZE', 181);

defined('GEOCODE_API')      					OR define('GEOCODE_API', 'AIzaSyCrWLa_d9buVdQM1RGT6a1JjzB5gTwKVJ8');

defined('PUSH_API_ACCESS_KEY')      			OR define('PUSH_API_ACCESS_KEY', 'AAAAtIptN0A:APA91bEvPjECzj3VLpQcnOSGttJT5DhxmIsOMbnGotNbVOEo8riLD7JEZcZe43mIR43h6gmoRKT9DnU3M-0e45E9D8xYwfrSWgnEP2XkeXaHlF6yjiKEHV3sD8q5yIJmxPbnGtItlSWx');
defined('PUSH_PASS_PHRASE')      				OR define('PUSH_PASS_PHRASE', 'wine');
defined('PUSH_CHANNEL_NAME')      				OR define('PUSH_CHANNEL_NAME', '');

define('STRIPE_TEST_MODE', 					true);
if (STRIPE_TEST_MODE) {
	define('STRIPE_API_KEY', 'sk_test_M6hjAyDt9Zhb1j1pXhxpLJeG'); // Test mode Key
	define('STRIPE_PUBLIC_KEY', 'pk_test_F7enaGEBbmmgBG9EZzUHtFeT'); // Test mode Key
} else {
	define('STRIPE_API_KEY', ''); // Live mode Key
	define('STRIPE_PUBLIC_KEY', ''); // Live mode Key
}


defined('SIGNUP_TOKEN')			OR define('SIGNUP_TOKEN', 'tAuD8VypHS9Q');
defined('Q_A_EMAIL')      				OR define('Q_A_EMAIL', 'vj.avalgate@gmail.com');

defined('JOB_RATING_ONE')      		OR define('JOB_RATING_ONE', 'Z1&&FABc3h');
defined('JOB_RATING_TWO')      		OR define('JOB_RATING_TWO', 'Y2&&EcD1x');
defined('JOB_RATING_THREE')      		OR define('JOB_RATING_THREE', 'W3##MHb4k');
defined('JOB_RATING_FOUR')      		OR define('JOB_RATING_FOUR', 'X4$$NBs5L');
defined('JOB_RATING_FIVE')      		OR define('JOB_RATING_FIVE', 'V5@@PjB2S');