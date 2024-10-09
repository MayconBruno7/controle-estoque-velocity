<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('sobrenos', 'Home::sobrenos');
$routes->get('contato', 'Home::contato');
$routes->post('contatoEnviaEmail', 'Home::contatoEnviaEmail');
$routes->get('login', 'Home::login');
$routes->get('criarNovaConta', 'Home::criarNovaConta');
$routes->get('carrinhoCompras', 'Home::carrinhoCompras');
$routes->get('carrinhoPagamento', 'Home::carrinhoPagamento');
$routes->get('carrinhoConfirmacao', 'Home::carrinhoConfirmacao');
$routes->get('produtoDetalhe/(:num)', 'Home::produtoDetalhe/$1');

// Crud Departamento
$routes->group('Departamento', function($routes) {
    $routes->get("/", 'Departamento::index');
    $routes->get("lista", 'Departamento::index');
    $routes->get("form/(:segment)/(:num)", 'Departamento::form/$1/$2');
    $routes->post("store", 'Departamento::store');
    $routes->post('delete', "Departamento::delete");
});