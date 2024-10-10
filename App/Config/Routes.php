<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('Home', 'Home::index');
$routes->get('Home/homeAdmin', 'Home::homeAdmin');
$routes->get('Home/home', 'Home::home');

$routes->post('Login/signIn', 'Login::signIn');    
$routes->get('Login/signOut', 'Login::signOut');

$routes->group('Usuario', function($routes) {
    $routes->get("/", 'Usuario::index');
    $routes->get("lista", 'Usuario::index');
    $routes->get("form/(:segment)/(:num)", 'Usuario::form/$1/$2');
    $routes->post("store", 'Usuario::store');
    $routes->post('delete', "Usuario::delete");
});

// $routes->get('Usuario', 'Usuario::index');


// $routes->get('sobrenos', 'Home::sobrenos');
// $routes->get('contato', 'Home::contato');
// $routes->post('contatoEnviaEmail', 'Home::contatoEnviaEmail');
// $routes->get('login', 'Home::login');
// $routes->get('criarNovaConta', 'Home::criarNovaConta');
// $routes->get('carrinhoCompras', 'Home::carrinhoCompras');
// $routes->get('carrinhoPagamento', 'Home::carrinhoPagamento');
// $routes->get('carrinhoConfirmacao', 'Home::carrinhoConfirmacao');
// $routes->get('produtoDetalhe/(:num)', 'Home::produtoDetalhe/$1');

// Crud Departamento
// $routes->group('Departamento', function($routes) {
//     $routes->get("/", 'Departamento::index');
//     $routes->get("lista", 'Departamento::index');
//     $routes->get("form/(:segment)/(:num)", 'Departamento::form/$1/$2');
//     $routes->post("store", 'Departamento::store');
//     $routes->post('delete', "Departamento::delete");
// });

// Crud Login
// $routes->group('Login', function($routes) {
    
//     $routes->get("lista", 'Departamento::index');
//     $routes->get("form/(:segment)/(:num)", 'Departamento::form/$1/$2');
//     $routes->post("store", 'Departamento::store');
//     $routes->post('delete', "Departamento::delete");
// });