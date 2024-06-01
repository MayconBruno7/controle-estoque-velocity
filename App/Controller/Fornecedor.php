<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class Fornecedor extends ControllerMain
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
        $this->loadView("restrita/listaFornecedor", $this->model->lista("id"));
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

        return $this->loadView("restrita/formFornecedor", $dados);
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
            return Redirect::page("Fornecedor/form/insert");     // error
        } else {

            if ($this->model->insert([
                "descricao" => $post['descricao'],
                "statusRegistro" => $post['statusRegistro']
            ])) {
                Session::set("msgSuccess", "Fornecedor adicionada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar inserir uma nova Fornecedor.");
            }
    
            Redirect::page("Fornecedor");
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
            return Redirect::page("Fornecedor/form/update/" . $post['id']);
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
                Session::set("msgSuccess", "Fornecedor alterada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar alterar a Fornecedor.");
            }

            return Redirect::page("Fornecedor");
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
            Session::set("msgSuccess", "Fornecedor excluída com sucesso.");
        } else {
            Session::set("msgError", "Falha tentar excluir a Fornecedor.");
        }

        Redirect::page("Fornecedor");
    }
}