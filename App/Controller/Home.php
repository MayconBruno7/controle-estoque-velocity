<?php

use App\Library\ControllerMain;

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
        return $this->loadView("restrita/homeAdmin");
    }

      /**
     * homeAdmin
     *
     * @return void
     */
    public function home()
    {
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