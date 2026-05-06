<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Dashboard
$routes->get('/', 'Dashboard::index');

// Transactions
$routes->get('transactions', 'Transactions::index');
$routes->get('transactions/create', 'Transactions::create');
$routes->post('transactions/store', 'Transactions::store');
$routes->get('transactions/edit/(:num)', 'Transactions::edit/$1');
$routes->post('transactions/update/(:num)', 'Transactions::update/$1');
$routes->get('transactions/delete/(:num)', 'Transactions::delete/$1');

// Categories
$routes->get('categories', 'Categories::index');
$routes->get('categories/create', 'Categories::create');
$routes->post('categories/store', 'Categories::store');
$routes->get('categories/edit/(:num)', 'Categories::edit/$1');
$routes->post('categories/update/(:num)', 'Categories::update/$1');
$routes->get('categories/delete/(:num)', 'Categories::delete/$1');

// Reports
$routes->get('reports', 'Reports::index');
