<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions/
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method/ The segments in a
| URL normally follow this pattern:
|
|	example/com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL/
|
| Please see the user guide for complete details:
|
|	https://codeigniter/com/userguide3/general/routing/html
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
| URI contains no data/ In the above example, the "welcome" class
| would be loaded/
|
|	$route['404_override'] = '';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route/
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes/ '-' isn't a valid
| class or method name character, so it requires translation/
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments/
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
$route['auth/ajax_validate_login'] = 'AuthController/ajax_validate_login';

// Pendaftaran Penanggung Jawab + Token Logic
$route['daftar/submit_pj'] = 'DaftarPJController/submit_pj';
$route['shortlink'] = 'DaftarPJController/generate_link_view'; 
$route['daftar/link_pj'] = 'DaftarPJController/link_pj'; 
$route['daftar/pj/(:any)'] = 'DaftarPJController/pj/$1';

// Route Dashboard
$route['dashboard'] = 'HomeController/index'; 

// Routes Menu Admin
$route['dashboard/admin/view'] = 'AdminController/view'; 
$route['dashboard/admin/create'] = 'AdminController/create';  
$route['dashboard/admin/store'] = 'AdminController/store';    
$route['dashboard/admin/edit/(:any)'] = 'AdminController/edit/$1';  
$route['dashboard/admin/update/(:num)'] = 'AdminController/update/$1';  
$route['dashboard/admin/delete/(:num)'] = 'AdminController/delete/$1'; 

// Routes Menu Kaling
$route['dashboard/kaling/view'] = 'KalingController/view';
$route['dashboard/kaling/create'] = 'KalingController/create';
$route['dashboard/kaling/store'] = 'KalingController/store';
$route['dashboard/kaling/edit/(:any)'] = 'KalingController/edit/$1';
$route['dashboard/kaling/update/(:any)'] = 'KalingController/update/$1';
$route['dashboard/kaling/delete/(:any)'] = 'KalingController/delete/$1';

// Routes Menu Penanggung Jawab
$route['dashboard/pj/view'] = 'PJController/view';
$route['dashboard/pj/create'] = 'PJController/create';
$route['dashboard/pj/store'] = 'PJController/store';
$route['dashboard/pj/edit/(:num)'] = 'PJController/edit/$1';
$route['dashboard/pj/update/(:num)'] = 'PJController/update/$1';
$route['dashboard/pj/delete/(:num)'] = 'PJController/delete/$1';

// Routes Menu Wilayah
$route['dashboard/wilayah/view'] = 'WilayahController/view';
$route['dashboard/wilayah/create'] = 'WilayahController/create';
$route['dashboard/wilayah/store'] = 'WilayahController/store';
$route['dashboard/wilayah/edit/(:num)'] = 'WilayahController/edit/$1';
$route['dashboard/wilayah/update/(:num)'] = 'WilayahController/update/$1';
$route['dashboard/wilayah/delete/(:num)'] = 'WilayahController/delete/$1';

// Routes Menu Penghuni
$route['dashboard/penghuni/view'] = 'PenghuniController/index';
$route['dashboard/penghuni/viewpj'] = 'PenghuniController/index_pj';
$route['dashboard/penghuni/create_pj'] = 'PenghuniController/create_pj';
$route['dashboard/penghuni/store_pj'] = 'PenghuniController/store_pj';
$route['dashboard/penghuni/detail/(:num)'] = 'PenghuniController/detail_admin/$1';
$route['dashboard/penghuni/details/(:num)'] = 'PenghuniController/detail_pj/$1';
$route['dashboard/penghuni/create_admin'] = 'PenghuniController/create_admin';
$route['dashboard/penghuni/store_admin'] = 'PenghuniController/store_admin';
$route['dashboard/penghuni/verifikasi/(:num)/(:any)'] = 'PenghuniController/verifikasi/$1/$2';
$route['dashboard/penghuni/delete/(:num)'] = 'PenghuniController/delete/$1';

// Routes untuk Surat
$route['dashboard/surat/izin-tinggal'] = 'SuratController/form_surat_izin_tinggal';
$route['dashboard/surat/pernyataan'] = 'SuratController/form_surat_pernyataan';
$route['dashboard/surat/view'] = 'SuratController/index';
$route['dashboard/surat/SIT/(:any)'] = 'SuratController/generate_surat_izin_tinggal/$1';
$route['dashboard/surat/SP/(:any)'] = 'SuratController/generate_surat_pernyataan/$1';

// Routes untuk Laporan
$route['dashboard/report/view'] = 'LaporanController/index';
$route['dashboard/report/reportpj'] = 'LaporanController/report_pj';
$route['dashboard/report/reportpendatang'] = 'LaporanController/report_pendatang';
