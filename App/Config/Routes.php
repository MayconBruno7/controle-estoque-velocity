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

    // profile
    $routes->get("profile/view/(:num)", 'Usuario::profile/view/$1');

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
    // $routes->get("getDados", 'Relatorio::getDados/$1');

    $routes->get('getDados/(:any)/(:any)/(:any)/(:any)', 'Relatorio::getDados/$1/$2/$3');

    // rota da home para os relatorios do gráfico
    $routes->get('getDados/(:any)/(:any)/(:any)', 'Relatorio::getDados/$1/$2/$3');


});

$routes->group('Log', function($routes) {
    $routes->get("/", 'Log::index');
    $routes->get('viewLog/view/(:num)', 'Log::viewLog');

});

$routes->group('Produto', function($routes) {
    $routes->get("/", 'Produto::index');
    $routes->get("lista", 'Produto::index');
    $routes->get("form/(:segment)/(:num)", 'Produto::form/$1/$2');
    $routes->post("store", 'Produto::store');
    $routes->post('delete', "Produto::delete");

    // exclui o produto da movimentacao na parte de inserção 
    $routes->get("index/(:segment)/(:num)/(:num)", 'Produto::index/$1/$2/$3');

    // exclui o produto da movimentação na parte de update 
    $routes->get('index/(:segment)/(:num)/(:num)/(:num)/(:num)', 'Produto::index/$1/$2/$3/$4/$5');

});

// $routes->group('HistoricoProdutoMovimentacao', function($routes) {
$routes->get("HistoricoProdutoMovimentacao/index/(:num)/(:segment)", 'HistoricoProdutoMovimentacao::index/$1/$2');
$routes->get("HistoricoProduto/getHistoricoProduto", 'HistoricoProduto::getHistoricoProduto');


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

    $routes->get("getCidadeComboBox", 'Fornecedor::getCidadeComboBox');
    $routes->get("requireAPI", 'Fornecedor::requireAPI');
});

$routes->group('Movimentacao', function($routes) {
    $routes->get("/", 'Movimentacao::index');
    $routes->get("lista", 'Movimentacao::index');
    $routes->get("form/(:segment)/(:num)", 'Movimentacao::form/$1/$2');

    // adiciona a seção na movimentação quando é insert
    $routes->post("salvarSessao/(:segment)/(:num)", 'Movimentacao::salvarSessao/$1/$2');

    // exclui o produto da movimentação na parte de inserção 
    $routes->post("deleteProdutoMovimentacao/(:segment)", 'Movimentacao::deleteProdutoMovimentacao/$1');

    // recupera o produto no modal
    $routes->get("getProdutoComboBox", 'Movimentacao::getProdutoComboBox');

    // adiciona produtos a movimentação no insert
    $routes->post("newProdutoMovimentacao/(:segment)", 'Movimentacao::newProdutoMovimentacao/$1');

    $routes->post("update/updateProdutoMovimentacao/(:num)", 'Movimentacao::update/updateProdutoMovimentacao/$1');
    // adiciona o insert da movimentação e limpa as sessões
    $routes->post("new", 'Movimentacao::new');

    // rota para o update da movimentação
    $routes->post("update", 'Movimentacao::update');

    $routes->get('form/(:segment)/(:num)/home', 'Movimentacao::form/$1/$2');

    $routes->post("store", 'Movimentacao::store');
    $routes->post('delete', "Movimentacao::delete");
});

$routes->group('FaleConosco', function($routes) {
    $routes->get("formularioEmail", 'FaleConosco::formularioEmail');

});

