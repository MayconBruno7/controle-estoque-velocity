<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\UploadImages;
use App\Library\Validator;
use App\Library\Session;
// use App\Library\ModelMain;


class Peca extends ControllerMain
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
            return Redirect::page("Home/login");
        }

    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        if($this->getAcao() != 'delete') {
            $this->loadView("restrita/listaPeca", $this->model->lista("id", $this->getAcao()));
        } else if($this->getAcao() == 'delete') {
            $this->loadView("restrita/listaPeca", $this->model->listaDeletePeca($this->getOutrosParametros(4)));
        }
    }

     /**
     * form
     *
     * @return void
     */
    public function form() {

        $dados = [];

        if ($this->getAcao() != 'new') {
            $dados = $this->model->getById($this->getId());
        }

        return $this->loadView(
            "restrita/formPeca",
            $dados
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
            return Redirect::page("Peca/form/insert");
        } else {

            if ($this->model->insert([
                "nome_peca"             => $post['nome_peca'],
                "valor_peca"            => $post['valor_peca'],
                "descricao_peca"        => $post['descricao_peca'],
                "statusRegistro"        => $post['statusRegistro']
            ])) {
                Session::set("msgSuccess", "Peça adicionada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar inserir uma nova Peca.");
            }
    
            Redirect::page("Peca");
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
            return Redirect::page("Peca/form/update/" . $post['id']);    // error
        } else {

            if ($post) 
            {
                     
                $updatePeca = $this->model->update(
                    [
                        "id" => $post['id']
                    ], 
                    [
                       "nome_peca"             => $post['nome_peca'],
                        "valor_peca"            => $post['valor_peca'],
                        "descricao_peca"        => $post['descricao_peca'],
                        "statusRegistro"        => $post['statusRegistro']

                    ]
                );
        
                if($updatePeca) {
                    Session::set("msgSuccess", "Peça alterada com sucesso.");
                } 

            } else {
                Session::set("msgError", "Falha tentar alterar a Peca.");
            }

            return Redirect::page("Peca");
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
            Session::set("msgSuccess", "Peca excluída com sucesso.");
        } else {
            Session::set("msgError", "Falha tentar excluir a Peca.");
        }

        Redirect::page("Peca");
    }

}