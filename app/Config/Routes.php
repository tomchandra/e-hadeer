<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// auth
$routes->get('/', 'Auth::index');
$routes->post('/auth', 'Auth::auth');
$routes->get('/logout', 'Auth::logout');

// presence
$routes->get('/app/presence', 'Presence::index');
$routes->get('/app/presence/data-presence', 'Presence::get_curent_monthyear_presence_byuser');
$routes->post('/app/presence/upload-image-presence', 'Presence::upload_image_presence');
$routes->post('/app/presence/add', 'Presence::save_presence');

// employee
$routes->get('/app/employee', 'Employee::index');
$routes->get('/app/employee/list', 'Employee::list');
$routes->get('/app/employee/search', 'Employee::search');
$routes->post('/app/employee/add', 'Employee::add');
$routes->post('/app/employee/update', 'Employee::update');
$routes->post('/app/employee/delete', 'Employee::delete');

// user
$routes->get('/app/user', 'User::index');
$routes->get('/app/user/list', 'User::list');
$routes->get('/app/user/search', 'User::search');
$routes->post('/app/user/add', 'User::add');
$routes->post('/app/user/update', 'User::update');
$routes->post('/app/user/delete', 'User::delete');
$routes->post('/app/user/access', 'User::access');

// menu
$routes->get('/app/menu', 'Menu::index');
$routes->get('/app/menu/detail', 'Menu::detail');
$routes->post('/app/menu/add', 'Menu::add');
$routes->post('/app/menu/update', 'Menu::update');

//group
$routes->get('/app/group', 'Group::index');
$routes->get('/app/group/detail-access', 'Group::detail_access');
$routes->post('/app/group/add', 'Group::add');
$routes->post('/app/group/add-access', 'Group::add_access');

//report
$routes->get('/app/report/(:alphanum)', 'Report::index/$1');
$routes->get('/app/report/data-presence', 'Report::get_all_presence_by_month');

// presence



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
