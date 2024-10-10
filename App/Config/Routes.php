<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->group('Home', function($routes) {
    $routes->get('home', 'Home::index');
    $routes->get('login', 'Home::login');
    $routes->get('homeAdmin', 'Home::homeAdmin');
    $routes->get('home', 'Home::home');
});

$routes->group('Login', function($routes) {
    $routes->post('signIn', 'Login::signIn');    
    $routes->get('signOut', 'Login::signOut');
});

$routes->group('Usuario', function($routes) {
    $routes->get("/", 'Usuario::index');
    $routes->get("lista", 'Usuario::index');
    $routes->get("form/(:segment)/(:num)", 'Usuario::form/$1/$2');
    $routes->post("store", 'Usuario::store');
    $routes->post('delete', "Usuario::delete");
});

$routes->group('Funcionario', function($routes) {
    $routes->get("/", 'Funcionario::index');
    $routes->get("lista", 'Funcionario::index');
    $routes->get("form/(:segment)/(:num)", 'Funcionario::form/$1/$2');
    $routes->post("store", 'Funcionario::store');
    $routes->post('delete', "Funcionario::delete");
});

$routes->group('Cargo', function($routes) {
    $routes->get("/", 'Cargo::index');
    $routes->get("lista", 'Cargo::index');
    $routes->get("form/(:segment)/(:num)", 'Cargo::form/$1/$2');
    $routes->post("store", 'Cargo::store');
    $routes->post('delete', "Cargo::delete");
});


$routes->group('Relatorio', function($routes) {
    $routes->get("/", 'Relatorio::index');
    $routes->get("relatorioMovimentacoes", 'Relatorio::relatorioMovimentacoes');
    $routes->get("relatorioItensPorFornecedor", 'Relatorio::relatorioItensPorFornecedor');
});

$routes->group('Log', function($routes) {
    $routes->get("/", 'Log::index');

});

$routes->group('Produto', function($routes) {
    $routes->get("/", 'Produto::index');
    $routes->get("lista", 'Produto::index');
    $routes->get("form/(:segment)/(:num)", 'Produto::form/$1/$2');
    $routes->post("store", 'Produto::store');
    $routes->post('delete', "Produto::delete");
});

$routes->group('Setor', function($routes) {
    $routes->get("/", 'Setor::index');
    $routes->get("lista", 'Setor::index');
    $routes->get("form/(:segment)/(:num)", 'Setor::form/$1/$2');
    $routes->post("store", 'Setor::store');
    $routes->post('delete', "Setor::delete");
});

$routes->group('Fornecedor', function($routes) {
    $routes->get("/", 'Fornecedor::index');
    $routes->get("lista", 'Fornecedor::index');
    $routes->get("form/(:segment)/(:num)", 'Fornecedor::form/$1/$2');
    $routes->post("store", 'Fornecedor::store');
    $routes->post('delete', "Fornecedor::delete");
});

$routes->group('Movimentacao', function($routes) {
    $routes->get("/", 'Movimentacao::index');
    $routes->get("lista", 'Movimentacao::index');
    $routes->get("form/(:segment)/(:num)", 'Movimentacao::form/$1/$2');
    $routes->post("store", 'Movimentacao::store');
    $routes->post('delete', "Movimentacao::delete");
});

$routes->group('FaleConosco', function($routes) {
    $routes->get("formularioEmail", 'FaleConosco::formularioEmail');
    // $routes->get("lista", 'Movimentacao::index');
    // $routes->get("form/(:segment)/(:num)", 'Movimentacao::form/$1/$2');
    // $routes->post("store", 'Movimentacao::store');
    // $routes->post('delete', "Movimentacao::delete");
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