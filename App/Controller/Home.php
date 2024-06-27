<?php

use App\Library\ControllerMain;
use App\Library\Redirect;

class Home extends ControllerMain
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $this->loadView("usuario/login", $this->dados);

    }

    /**
     * login
     *
     * @return void
     */
    public function login()
    {
        return $this->loadView('usuario/login');
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
        
        return $this->loadView("restrita/homeAdmin");
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
       
        return $this->loadView("restrita/home");
    }


    /**
     * criarConta
     *
     * @return void
     */
    public function criarConta()
    {
        $this->loadHelper('Formulario');
        
        return $this->loadView("usuario/formCriarConta", []);
    }
}