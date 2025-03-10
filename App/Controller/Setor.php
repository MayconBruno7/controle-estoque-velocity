<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class Setor extends ControllerMain
{
    /**
     * construct
     *
     * @param array $dados 
     */
    public function __construct($dados)
    {
        $this->auxiliarConstruct($dados);

        // Só acessa se tiver logado
        if (!$this->getUsuario()) {
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
        $this->loadView("restrita/listaSetor", $this->model->lista("id"));
    }

    /**
     * form
     *
     * @return void
     */
    public function form()
    {
        
        $DbDados = [];

        if ($this->getAcao() != 'new') {
            $DbDados = $this->model->getById($this->getId());
        }

        $FuncionarioModel = $this->loadModel("Funcionario");

        $DbDados['aFuncionario'] = $FuncionarioModel->lista('id');

        return $this->loadView(
            "restrita/formSetor",
            $DbDados
        );

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
            // error
            return Redirect::page("Setor/form/insert");
        } else {

            if ($this->model->insert([
                "nome"              => $post['nome'],
                "responsavel"       => $post['funcionarios'],
                "statusRegistro"    => $post['statusRegistro']
            ])) {
                Session::set("msgSuccess", "Setor adicionada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar inserir uma nova Setor.");
            }
    
            Redirect::page("Setor");
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
            return Redirect::page("Setor/form/update/" . $post['id']);    // error
        } else {
            if ($this->model->update(
                [
                    "id" => $post['id']
                ], 
                [
                    "nome"              => $post['nome'],
                    "responsavel"       => $post['funcionarios'],
                    "statusRegistro"    => $post['statusRegistro']
                ]
            )) {
                Session::set("msgSuccess", "Setor alterada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar alterar a Setor.");
            }

            return Redirect::page("Setor");
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
            Session::set("msgSuccess", "Setor excluída com sucesso.");
        } else {
            Session::set("msgError", "Falha tentar excluir a Setor.");
        }

        Redirect::page("Setor");
    }
}