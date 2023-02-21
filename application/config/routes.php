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
|	http://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'login';
$route['logout'] = 'login/logout/index';
$route['dashboard'] = 'administrator/dashboard/index';

$route['component-blank'] = 'administrator/components/blank_page';

$route['master-product'] = 'administrator/product/index';
$route['product-add'] = 'administrator/product/add_product';
$route['product-edit/(:any)'] = 'administrator/product/edit_product/$1';
$route['product-price/(:any)'] = 'administrator/product/detail_price_product/$1';
$route['master-product-category'] = 'administrator/product/category';
$route['master-product-type'] = 'administrator/product/type';
$route['master-typepaid'] = 'administrator/product/type_paid';
$route['master-product-unit'] = 'administrator/product/unit';
$route['master-users'] = 'administrator/user/index';
$route['stock-items'] = 'administrator/stock/index';
$route['selling'] = 'administrator/selling/index';
$route['selling-list'] = 'administrator/selling/selling_view';
$route['selling-detail/(:any)'] = 'administrator/selling/detail_selling/$1';
$route['selling-edit/(:any)'] = 'administrator/selling/selling_edit/$1';
$route['selling/items-category/(:any)'] = 'administrator/selling/items_category/$1';

$route['transaction-in'] = 'administrator/transaction/index/';
$route['transaction-form/(:any)'] = 'administrator/transaction/transaction_in_form/$1';
$route['transaction-indetail/(:any)'] = 'administrator/transaction/transaction_in_details/$1';


$route['stock-opname'] = 'administrator/stock_opname/index/';
$route['stock-opname-form/(:any)'] = 'administrator/stock_opname/stockopname_form/$1';
$route['stock-opname-detail/(:any)'] = 'administrator/stock_opname/stockopname_details/$1';

$route['debt'] = 'administrator/debt/index/';
$route['debt-details/(:any)'] = 'administrator/debt/details/$1';

$route['404_override'] = 'administrator/error/notFound';
$route['translate_uri_dashes'] = FALSE;
