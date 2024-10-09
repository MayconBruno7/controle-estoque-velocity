<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Home extends BaseController
{

    public $UsuarioModel;

    /**
     * construct
     */
    public function __construct()
    {
        $this->UsuarioModel = new UsuarioModel();
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {

        $dados['dados'] = $this->UsuarioModel;
                            // ->orderBy("descricao")
                            // ->findAll();
        return view("usuario/login", $dados);

    }

    /**
     * login
     *
     * @return void
     */
    public function login(): string
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
        // Somente pode ser acessado por usuários adminsitradores
        if (!$this->getAdministrador()) {
            return Redirect::page("Home");
        }

        $RelatorioModel = $this->loadModel('Relatorio');

        $DbDados = [];

        $DbDados['aRelatorioDia'] = $RelatorioModel->RelatorioDia();
        $DbDados['aRelatorioSemana'] = $RelatorioModel->RelatorioSemana();
        $DbDados['aRelatorioMes'] = $RelatorioModel->RelatorioMes();
        $DbDados['aRelatorioAno'] = $RelatorioModel->RelatorioAno();

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

        // Só acessa se tiver logado
        if (!$this->getUsuario()) {
            return Redirect::page("Home");
        }
       
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