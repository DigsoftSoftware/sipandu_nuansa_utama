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
|	https://codeigniter.com/userguide3/general/routing.html
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
|		    my-controller/my-method	-> my_controller/my_method
*/
$route['404_override'] = 'ErrorController/error_404';
$route['dashboard/error'] = 'ErrorController/error_403';
$route['translate_uri_dashes'] = FALSE;

// Auth dan Loginnya
$route['default_controller'] = 'AuthController/login';
$route['auth'] = 'AuthController/login';
$route['auth/do_login'] = 'AuthController/do_login';
$route['auth/logout'] = 'AuthController/logout';

// Pendaftaran Penanggung Jawab + Token Logic
$route['daftar/submit_pj'] = 'DaftarPJController/submit_pj';
$route['shortlink'] = 'DaftarPJController/generate_link_view'; 
$route['daftar/link_pj'] = 'DaftarPJController/link_pj'; 
$route['daftar/pj/(:any)'] = 'DaftarPJController/pj/$1';

// Route Semua Dashboard
$route['dashboard/admin'] = 'AdminDashboardController/index'; 
$route['dashboard/kaling'] = 'KalingDashboardController/index';
$route['dashboard/pj'] = 'PenanggungJawabDashboardController/index';

// Routes Menu Admin
$route['dashboard/admin/view'] = 'AdminDaftarDashboardController/view'; 
$route['dashboard/admin/create'] = 'AdminDaftarDashboardController/create';  
$route['dashboard/admin/store'] = 'AdminDaftarDashboardController/store';    
$route['dashboard/admin/edit/(:num)'] = 'AdminDaftarDashboardController/edit/$1';  
$route['dashboard/admin/update/(:num)'] = 'AdminDaftarDashboardController/update/$1';  
$route['dashboard/admin/delete/(:num)'] = 'AdminDaftarDashboardController/delete/$1'; 

// Routes Menu Kaling
$route['dashboard/kaling/view'] = 'KalingDaftarDashboardController/view';
$route['dashboard/kaling/create'] = 'KalingDaftarDashboardController/create';
$route['dashboard/kaling/store'] = 'KalingDaftarDashboardController/store';
$route['dashboard/kaling/edit/(:num)'] = 'KalingDaftarDashboardController/edit/$1';
$route['dashboard/kaling/update/(:num)'] = 'KalingDaftarDashboardController/update/$1';
$route['dashboard/kaling/delete/(:num)'] = 'KalingDaftarDashboardController/delete/$1';

// Routes Menu Penanggung Jawab
$route['dashboard/pj/view'] = 'PenanggungJawabDashboardController/view';
$route['dashboard/pj/edit/(:num)'] = 'PenanggungJawabDashboardController/edit/$1';
$route['dashboard/pj/update/(:num)'] = 'PenanggungJawabDashboardController/update/$1';
$route['dashboard/pj/delete/(:num)'] = 'PenanggungJawabDashboardController/delete/$1';

// Routes Menu Wilayah
$route['dashboard/wilayah/view'] = 'WilayahDashboardController/view';
$route['dashboard/wilayah/create'] = 'WilayahDashboardController/create';
$route['dashboard/wilayah/store'] = 'WilayahDashboardController/store';
$route['dashboard/wilayah/edit/(:num)'] = 'WilayahDashboardController/edit/$1';
$route['dashboard/wilayah/update/(:num)'] = 'WilayahDashboardController/update/$1';
$route['dashboard/wilayah/delete/(:num)'] = 'WilayahDashboardController/delete/$1';
