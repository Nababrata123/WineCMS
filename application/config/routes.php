 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Auth/index';
$route['auth'] = 'Auth/index';
$route['auth/(.+)'] = "Auth/$1";
$route['auth/do_login'] = "Auth/do_login";
$route['auth/dashboard'] = "Auth/dashboard";
$route['dashboard'] = "Auth/dashboard";

$route['recover_password/(:any)/(:any)'] = "Public/Home/recover_password/$1/$2";
$route['jobrating/(:any)/(:any)'] = "Public/Home/job_rating/$1/$2";
$route['thank_you'] = "Public/Home/thank_you";
$route['admin'] = "Admin";
$route['admin/(.+)'] = "Admin/$1";
$route['profile'] = "Admin/users/profile";
$route['master_password'] = "Admin/users/master_password";
$route['app'] = "App";

//For api
$route['api/sales_representative/(.+)'] = "App/api/sales_representative/$1";
$route['api/tester/(.+)'] = "App/api/tester/$1";
$route['api/store/(.+)'] = "App/api/store/$1";
$route['api/wine/(.+)'] = "App/api/wine/$1";
$route['api/zone/(.+)'] = "App/api/zone/$1";
$route['api/profile/(.+)']="App/api/profile/$1";
$route['api/agency/(.+)'] = "App/api/agency/$1";
$route['api/job/(.+)'] = "App/api/job/$1";
//End Api



//Agency login portal

$route['agency'] = 'Agency/index';
$route['agency/(.+)'] = "Agency/$1";
//$route['agency/do_login'] = "Agency/do_login";
$route['agency/dashboard'] = "Agency/dashboard";
$route['Agency/profile'] = "Agency/profile";
//$route['dashboard'] = "Agency/dashboard";

//End agency
//For cms
$route['(:any)']="Public/Cms/pages/$1";


// //Users login portal

// $route['users'] = 'Users/index';
// $route['users/(.+)'] = "Users/$1";
// $route['App/users'] = "App/users";
// $route['users/profile'] = "Users/profile";


// //End Users
//For cms
$route['(:any)']="Public/Cms/pages/$1";

//$route['agency']="9";
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
