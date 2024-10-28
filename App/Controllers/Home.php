<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\RelatorioModel;

class Home extends BaseController
{

    public $UsuarioModel;
    public $RelatorioModel;

    /**
     * construct
     */
    public function __construct()
    {
        $this->UsuarioModel     = new UsuarioModel();
        $this->RelatorioModel   = new RelatorioModel();
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {

        $dados['dados'] = $this->UsuarioModel->findAll();

        return view("usuario/login", $dados);

    }

    /**
     * login
     *
     * @return void
     */
    public function login()
    {
        return view("usuario/login");
    }

    /**
     * homeAdmin
     *
     * @return void
     */
    public function homeAdmin()
    {
        
        $DbDados = [];

        $DbDados['aRelatorioDia']       = $this->RelatorioModel->RelatorioDia();
        $DbDados['aRelatorioSemana']    = $this->RelatorioModel->RelatorioSemana();
        $DbDados['aRelatorioMes']       = $this->RelatorioModel->RelatorioMes();
        $DbDados['aRelatorioAno']       = $this->RelatorioModel->RelatorioAno();

        return view(
            "restrita/homeAdmin",
            $DbDados
            
        );
        
    }

      /**
     * homeAdmin
     *
     * @return void
     */
    public function home()
    {


       
        return view("restrita/home");
    }


    /**
     * criarConta
     *
     * @return void
     */
    public function criarNovaConta(): string
    {
        return view("criarNovaConta");
    }
}