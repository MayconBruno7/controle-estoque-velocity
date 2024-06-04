<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class Movimentacao extends ControllerMain
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
        $this->loadView("restrita/listaMovimentacao", $this->model->lista("id"));
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

        

        return $this->loadView("restrita/formMovimentacao", $dados);
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
            return Redirect::page("Movimentacao/form/insert");     // error
        } else {

            if ($this->model->insert([
                "nome" => $post['nome'],
                "statusRegistro" => $post['statusRegistro']
            ])) {
                Session::set("msgSuccess", "Movimentacao adicionada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar inserir uma nova Movimentacao.");
            }
    
            Redirect::page("Movimentacao");
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
            return Redirect::page("Movimentacao/form/update/" . $post['id']);
        } else {

            if ($this->model->update(
                [
                    "id" => $post['id']
                ], 
                [
                    "nome" => $post['nome'],
                    "statusRegistro" => $post['statusRegistro']
                ]
            )) {
                Session::set("msgSuccess", "Movimentacao alterada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar alterar a Movimentacao.");
            }

            return Redirect::page("Movimentacao");
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
            Session::set("msgSuccess", "Movimentacao excluída com sucesso.");
        } else {
            Session::set("msgError", "Falha tentar excluir a Movimentacao.");
        }

        Redirect::page("Movimentacao");
    }
}