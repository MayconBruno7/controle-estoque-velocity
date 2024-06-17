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
        // $this->auxiliarConstruct($dados);

        // // Inicializa o modelo
        // $this->model = new ProdutoModel();
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

            // if (!empty($_FILES['imagem']['name'])) {

            //     // Faz uploado da imagem
            //     $nomeRetornado = UploadImages::upload($_FILES, 'produto');

            //     // se for boolean, significa que o upload falhou
            //     if (is_bool($nomeRetornado)) {
            //         Session::set( 'inputs' , $post );
            //         return Redirect::page("Produto/form/update/" . $post['id']);
            //     }

            // } else {
            //     // $nomeRetornado = $post['nomeImagem'];
            // }

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

            // if (!empty($_FILES['imagem']['name'])) {

            //     // Faz uploado da imagem
            //     $nomeRetornado = UploadImages::upload($_FILES, 'produto');

            //     // se for boolean, significa que o upload falhou
            //     if (is_bool($nomeRetornado)) {
            //         Session::set( 'inputs' , $post );
            //         return Redirect::page("Produto/form/update/" . $post['id']);
            //     }

            //     UploadImages::delete($post['nomeImagem'], 'produto');

            // } else {
            //     $nomeRetornado = $post['nomeImagem'];
            // }

            if ($this->model->update(
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
                    "dataMod"               => $post['dataMod'] = 'NOW()'
                ],

                [
                    "id_produtos"           => $post['id'],
                    "nome_produtos"         => setValor('nome'),
                    "descricao_anterior"    => setValor('descricao'),
                    "quantidade_anterior"   => setValor('quantidade'),
                    "fornecedor_id"         => setValor('fornecedor'),
                    "status_anterior"       => setValor('statusRegistro'),
                    "statusItem_anterior"   => setValor('condicao'),
                    "dataMod"               => setValor('dataMod'),


                ]
            )) {
                Session::set("msgSuccess", "Produto alterada com sucesso.");
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

    /**
     * getProdutoCombo
     *
     * @return string
     */
    public function getProdutoComboBox()
    {
        $dados = $this->model->getProdutoComboBox($this->getId());

        if (count($dados) == 0) {
            $dados[] = [
                "id" => "",
                "descricao" => "... Selecione uma Categoria ..."
            ];
        }

        echo json_encode($dados);
    }
}