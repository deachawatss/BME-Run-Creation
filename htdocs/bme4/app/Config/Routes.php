<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Home::index');

// Authentication routes
$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::login');
    $routes->get('login', 'Auth::login');
    $routes->post('authenticate', 'Auth::authenticate');
    $routes->get('logout', 'Auth::logout');
    $routes->post('logout', 'Auth::logout');
    $routes->get('check-session', 'Auth::checkSession');
    $routes->post('extend-session', 'Auth::extendSession');
    $routes->get('change-password', 'Auth::changePassword');
    $routes->post('change-password', 'Auth::changePassword');
    $routes->get('profile', 'Auth::profile');
    $routes->post('validate-token', 'Auth::validateToken');
});

// LDAP Test routes (for development/testing)
$routes->group('LdapTest', function($routes) {
    $routes->get('/', 'LdapTest::index');
    $routes->get('testConnection', 'LdapTest::testConnection');
    $routes->post('testAuth', 'LdapTest::testAuth');
    $routes->get('phpInfo', 'LdapTest::phpInfo');
});

// Main application routes
$routes->get('home', 'Home::index');

// CreateRunBulk routes (both lowercase and capitalized)
$routes->get('CreateRunBulk', 'CreateRunBulk::index');
$routes->get('CreateRunPartial', 'CreateRunPartial::index');
$routes->group('createrunbulk', function($routes) {
    $routes->get('/', 'CreateRunBulk::index');
    $routes->post('ajaxlist', 'CreateRunBulk::ajaxlist');
    $routes->post('create', 'CreateRunBulk::create');
    $routes->post('update/(:num)', 'CreateRunBulk::update/$1');
    $routes->post('delete/(:num)', 'CreateRunBulk::delete/$1');
    $routes->get('getNextRunNumber', 'CreateRunBulk::getNextRunNumber');
    $routes->post('getBatchListPaginated', 'CreateRunBulk::getBatchListPaginated');
    $routes->get('getRunDetails/(:num)', 'CreateRunBulk::getRunDetails/$1');
});

// CreateRunPartial routes
$routes->group('createrunpartial', function($routes) {
    $routes->get('/', 'CreateRunPartial::index');
    $routes->post('ajaxlist', 'CreateRunPartial::ajaxlist');
    $routes->post('create', 'CreateRunPartial::create');
    $routes->post('update/(:num)', 'CreateRunPartial::update/$1');
    $routes->post('delete/(:num)', 'CreateRunPartial::delete/$1');
    $routes->get('getNextRunNumber', 'CreateRunPartial::getNextRunNumber');
    $routes->post('getBatchListPaginated', 'CreateRunPartial::getBatchListPaginated');
    $routes->get('getRunDetails/(:num)', 'CreateRunPartial::getRunDetails/$1');
});

// RunPartial routes (alternative access)
$routes->group('runpartial', function($routes) {
    $routes->get('/', 'CreateRunPartial::index');
    $routes->post('ajaxlist', 'CreateRunPartial::ajaxlist');
    $routes->post('create', 'CreateRunPartial::create');
    $routes->post('update/(:num)', 'CreateRunPartial::update/$1');
    $routes->post('delete/(:num)', 'CreateRunPartial::delete/$1');
    $routes->get('getNextRunNumber', 'CreateRunPartial::getNextRunNumber');
    $routes->post('getBatchListPaginated', 'CreateRunPartial::getBatchListPaginated');
    $routes->get('getRunDetails/(:num)', 'CreateRunPartial::getRunDetails/$1');
});

// RunBulk routes (alternative access)
$routes->group('runbulk', function($routes) {
    $routes->get('/', 'CreateRunBulk::index');
    $routes->post('ajaxlist', 'CreateRunBulk::ajaxlist');
    $routes->post('create', 'CreateRunBulk::create');
    $routes->post('update/(:num)', 'CreateRunBulk::update/$1');
    $routes->post('delete/(:num)', 'CreateRunBulk::delete/$1');
    $routes->get('getNextRunNumber', 'CreateRunBulk::getNextRunNumber');
    $routes->post('getBatchListPaginated', 'CreateRunBulk::getBatchListPaginated');
    $routes->get('getRunDetails/(:num)', 'CreateRunBulk::getRunDetails/$1');
});

// API routes for AJAX calls
$routes->group('api', function($routes) {
    $routes->post('createrunbulk/list', 'CreateRunBulk::ajaxlist');
    $routes->post('createrunbulk/create', 'CreateRunBulk::create');
    $routes->post('createrunbulk/update/(:num)', 'CreateRunBulk::update/$1');
    $routes->post('createrunbulk/delete/(:num)', 'CreateRunBulk::delete/$1');
    $routes->post('createrunpartial/list', 'CreateRunPartial::ajaxlist');
    $routes->post('createrunpartial/create', 'CreateRunPartial::create');
    $routes->post('createrunpartial/update/(:num)', 'CreateRunPartial::update/$1');
    $routes->post('createrunpartial/delete/(:num)', 'CreateRunPartial::delete/$1');
    $routes->post('auth/check-session', 'Auth::checkSession');
    $routes->post('auth/extend-session', 'Auth::extendSession');
});

// Utility routes
$routes->get('about', 'Home::about');
$routes->get('help', 'Home::help');
$routes->get('system-info', 'Home::systemInfo');

