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
        // $categoria = $this->loadModel("Categoria");
        // $this->dados['aCategoria'] = $categoria->lista();
    
        // $this->loadView("home", $this->dados);

        $this->loadView("usuario/login", $this->dados);

    }

    /**
     * produto
     *
     * @return void
     */
    public function produto()
    {
        $CategoriaModel = $this->loadModel("categoria");

        $aCategoria = $CategoriaModel->lista();

        return $this->loadView("produto", $aCategoria);
    }

    /**
     * contato
     *
     * @return void
     */
    public function contato()
    {
        $this->loadView("contato");
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