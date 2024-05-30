<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class Categoria extends ControllerMain
{
    /**
     * construct
     *
     * @param array $dados 
     */
    public function __construct($dados)
    {
        $this->auxiliarConstruct($dados);

        // Somente pode ser acessado por usuários adminsitradores
        if (!$this->getAdministrador()) {
            return Redirect::page("Home");
        }
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $this->loadView("restrita/listaCategoria", $this->model->lista("descricao"));
    }

    /**
     * form
     *
     * @return void
     */
    public function form()
    {
        $dados = [];

        if ($this->getAcao() != "insert") {
            $dados = $this->model->getById($this->getId());
        }

        return $this->loadView("restrita/formCategoria", $dados);
    }

    /**
     * insert
     *
     * @return void
     */
    public function insert()
    {
        $post = $this->getPost();

        if (Validator::make($post, $this->model->validationRules)) {
            return Redirect::page("Categoria/form/insert");     // error
        } else {

            if ($this->model->insert([
                "descricao" => $post['descricao'],
                "statusRegistro" => $post['statusRegistro']
            ])) {
                Session::set("msgSuccess", "Categoria adicionada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar inserir uma nova categoria.");
            }
    
            Redirect::page("Categoria");
        }
    }

    /**
     * update
     *
     * @return void
     */
    public function update()
    {
        $post = $this->getPost();

        if (Validator::make($post, $this->model->validationRules)) {
            // error
            return Redirect::page("Categoria/form/update/" . $post['id']);
        } else {

            if ($this->model->update(
                [
                    "id" => $post['id']
                ], 
                [
                    "descricao" => $post['descricao'],
                    "statusRegistro" => $post['statusRegistro']
                ]
            )) {
                Session::set("msgSuccess", "Categoria alterada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar alterar a categoria.");
            }

            return Redirect::page("Categoria");
        }
    }
    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {
        if ($this->model->delete(["id" => $this->getPost('id')])) {
            Session::set("msgSuccess", "Categoria excluída com sucesso.");
        } else {
            Session::set("msgError", "Falha tentar excluir a categoria.");
        }

        Redirect::page("Categoria");
    }
}