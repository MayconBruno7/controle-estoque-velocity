<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class MovimentacaoItem extends ControllerMain
{

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

        $MovimentacaoItemModel = $this->loadModel("MovimentacaoItem");
        $dados['aItemMovimentacao'] = $MovimentacaoItemModel->listaProdutos($this->getId());

        $SetorModel = $this->loadModel("Setor");
        $dados['aSetorMovimentacao'] = $SetorModel->lista('id');

        $FornecedorModel = $this->loadModel("Fornecedor");
        $dados['aFornecedorMovimentacao'] = $FornecedorModel->lista('id');

        return $this->loadView("restrita/formMovimentacao", $dados);
    }

    // /**
    //  * insert
    //  *
    //  * @return void
    //  */
    // public function insert($data)
    // {

    //     // if (Validator::make($post, $this->model->validationRules)) {
    //     //     return Redirect::page("Movimentacao/form/insert");     // error
    //     // } else {
    //         // Insere os itens da movimentação
           
    //         // if (isset($_SESSION['movimentacao'])) {
    //         //     $MovimentacaoModel      = $this->loadModel("Movimentacao");
    //         //                 $idUltimaMovimentacao  = $MovimentacaoModel->idUltimaMovimentacao('id');
    //         //     foreach ($_SESSION['movimentacao'] as $movimentacao) {
    //         //         if (isset($movimentacao['produtos'])) {
    //         //             foreach ($movimentacao['produtos'] as $produto) {
    //         //                 $id_produto = $produto['id_produto'];
    //         //                 $quantidade = (int)$produto['quantidade'];
    //         //                 $valor_produto = (float)$produto['valor'];
            
    //         //                 // Carregar o modelo MovimentacaoItem
    //         //                 $MovimentacaoItemModel = $this->loadModel("MovimentacaoItem");

                            
            
    //         //                 // Inserir o item da movimentação no banco de dados
    //         //                 $this->model->insert([
    //         //                     "id_movimentacao" => $idUltimaMovimentacao[0]['ultimo_id'],
    //         //                     "id_produtos" => $id_produto,
    //         //                     "quantidade" => $quantidade,
    //         //                     "valor" => $valor_produto
    //         //                 ]);
    //         //             }
    //         //         }
    //         //     }
    //         // }
    //         // // unset($_SESSION['movimentacao']);
    //         var_dump($data);
    //         exit("opa");
    //         if ($this->model->insert([
    //             "id_movimentacoes"  => $post['id_movimentacao'],
    //             "id_produtos"       => $post['id_produtos'],
    //             "quantidade"        => $post['quantidade'],
    //             "valor"             => $post['valor']
    //         ])) {

    //             unset($_SESSION['movimentacao']);
    //             Session::set("msgSuccess", "Item adicionado com sucesso.");
    //         } else {
    //             Session::set("msgError", "Falha tentar inserir um novo item.");
    //         }
    
    //         Redirect::page("Movimentacao");
    //     // }
    // }

    // /**
    //  * insert
    //  *
    //  * @return void
    //  */
    // public function insertProdutoMovimentacao()
    // {
    //     $post = $this->getPost();

    //     $id_movimentacao = isset($post['id_movimentacoes']) ? (int)$post['id_movimentacoes'] : ""; 
    //     $quantidade = (int)$post['quantidade'];
    //     $id_produto = (int)$post['id_produto'];
    //     $valor_produto = (float)$post['valor'];

    //     // var_dump($valor_produto);
    //     // var_dump($id_produto);
    //     // var_dump($quantidade);

    //     if ($this->MovimentacaoItemModel->insert([
    //         "quantidade"    => $quantidade,
    //         "id_produtos"   => $id_produto,
    //         "valor"         => $valor_produto    
    //     ])) {
    //         Session::set("msgSuccess", "Movimentacao adicionada com sucesso.");
    //     } else {
    //         Session::set("msgError", "Falha tentar inserir uma nova Movimentacao.");
    //     }

    //     Redirect::page("Movimentacao");
     
    // }

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