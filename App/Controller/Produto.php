<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\UploadImages;
use App\Library\Validator;
use App\Library\Session;
// use App\Library\ModelMain;


class Produto extends ControllerMain
{

    /**
     * construct
     *
     * @param array $dados 
     */
    public function __construct($dados)
    {
        $this->auxiliarConstruct($dados);
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        if($this->getAcao() != 'delete') {
            $this->loadView("restrita/listaProduto", $this->model->lista("id", $this->getAcao()));
        } else if($this->getAcao() == 'delete') {
            $this->loadView("restrita/listaProduto", $this->model->listaDeleteProduto($this->getOutrosParametros(4)));
        }
    }

     /**
     * form
     *
     * @return void
     */
    public function form() {

        $HistoricoProdutoModel = $this->loadModel('HistoricoProduto');
        $FornecedorModel = $this->loadModel("Fornecedor");

        $DbDados = [];

        if ($this->getAcao() != 'new') {
            $DbDados = $this->model->getById($this->getId());
        }

        $DbDados['aFornecedor'] = $FornecedorModel->lista('id');
        $idProduto = $this->getId();
        
        $DbDados['aHistoricoProduto'] = $HistoricoProdutoModel->historicoProduto('id', $idProduto);

        return $this->loadView(
            "restrita/formProduto",
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
            return Redirect::page("Produto/form/insert");
        } else {

            if ($this->model->insert([
                "nome"                  => $post['nome'],
                "quantidade"            => $post['quantidade'],
                "fornecedor"            => $post['fornecedor_id'],
                "statusRegistro"        => $post['statusRegistro'],
                "condicao"              => $post['condicao'],
                "descricao"             => $post['descricao']
            ])) {
                Session::set("msgSuccess", "Produto adicionada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar inserir uma nova Produto.");
            }
    
            Redirect::page("Produto");
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
            return Redirect::page("Produto/form/update/" . $post['id']);    // error
        } else {

            if ($post) 
            {
                     
                $updateProduto = $this->model->update(
                    [
                        "id" => $post['id']
                    ], 
                    [
                        "nome"                  => $post['nome'],
                        "quantidade"            => $post['quantidade'],
                        "fornecedor"            => $post['fornecedor_id'],
                        "statusRegistro"        => $post['statusRegistro'],
                        "condicao"              => $post['condicao'],
                        "descricao"             => $post['descricao'],
                    ],
    
                    
                );
        
                $insertHistoricoProduto = $this->model->insertHistoricoProduto(
                    [
                        "id_produtos"           => $post['id'],
                        "nome_produtos"         => setValor('nome'),
                        "descricao_anterior"    => setValor('descricao'),
                        "quantidade_anterior"   => setValor('quantidade'),
                        "fornecedor_id"         => $post['fornecedor_id'],
                        "status_anterior"       => setValor('statusRegistro'),
                        "statusItem_anterior"   => setValor('condicao'),
                        "dataMod"               => $post['dataMod'],
    
                    ]
                );

                if($updateProduto) {
                    Session::set("msgSuccess", "Produto alterada com sucesso.");
                } 

            } else {
                Session::set("msgError", "Falha tentar alterar a Produto.");
            }

            return Redirect::page("Produto");
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
            Session::set("msgSuccess", "Produto excluída com sucesso.");
        } else {
            Session::set("msgError", "Falha tentar excluir a Produto.");
        }

        Redirect::page("Produto");
    }

}