<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\UploadImages;
use App\Library\Validator;
use App\Library\Session;
use App\Library\ModelMain;


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

        parent::__construct($dados); // Chama o construtor da classe pai

        // Inicializa o modelo
        $this->model = new ProdutoModel();
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
        $this->loadView("restrita/listaProduto", $this->model->lista("descricao"));
    }

     /**
     * form
     *
     * @return void
     */
    public function form() {

        // $ProdutoModel = $this->model;
        // $dataModFormatada = '';

        // if ($this->getAcao() != "insert") {
        //     $dataMod = null;
        //     if (isset($_GET['id'])) {
        //         try {
        //             $dataMod = $this->model->getById($_GET['id']);
        //             $dataModFormatada = isset($dataMod['dataModFormatada']) ? $dataMod['dataModFormatada'] : '';
        //             $dados = $dataMod;
        //         } catch (Exception $ex) {
        //             echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
        //         }
        //     }
        // }

        // $dadosHistorico = $this->model->lista();
        // $dadosFornecedor = $this->model->lista();

        // if ($dadosFornecedor) {
        //     $fornecedor_id = isset($dados['fornecedor']) ? $dados['fornecedor'] : "";
        // }

        // $nome_fornecedor = isset($fornecedor_id) ? $this->obterNomeFornecedor($fornecedor_id) : '';

        // if ($this->getAcao() != 'insert') {
        //     $ConfereHistorico = $this->model->getById(isset($_GET['id']) ? $_GET['id'] : '');
        // }


        // if ($this->getAcao() != 'new') {
        //     $DbDados = $this->model->getById($this->getId());
        // }

        // $DbDados['aProduto'] = $ProdutoModel->lista('descricao');
        // $DbDados['dataModFormatada'] = $dataModFormatada;
        // $DbDados['dadosHistorico'] = $dadosHistorico;
        // $DbDados['dadosFornecedor'] = $dadosFornecedor;
        // $DbDados['fornecedor_id'] = $fornecedor_id;

        // $DbDados = [];


        // $DbDados['aProduto'] = $ProdutoModel->lista('descricao');


        // return $this->loadView("restrita/formProduto", $DbDados);

        // public $table = "fornecedor";

        $FornecedorModel = $this->loadModel("Fornecedor");

        $DbDados = [];

        if ($this->getAcao() != 'new') {
            $DbDados = $this->model->getById($this->getId());
        }

        $DbDados['aFornecedor'] = $FornecedorModel->lista('nome');

        return $this->loadView(
            "restrita/formProduto",
            $DbDados
        );
    }

    // private function obterNomeFornecedor($fornecedor_id) {
    //     $result = $this->model->getById($fornecedor_id);
    //     return $result ? $result['nome'] : '';
    // }

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

            if (!empty($_FILES['imagem']['name'])) {

                // Faz uploado da imagem
                $nomeRetornado = UploadImages::upload($_FILES, 'produto');

                // se for boolean, significa que o upload falhou
                if (is_bool($nomeRetornado)) {
                    Session::set( 'inputs' , $post );
                    return Redirect::page("Produto/form/update/" . $post['id']);
                }

            } else {
                $nomeRetornado = $post['nomeImagem'];
            }

            if ($this->model->insert([
                "descricao"         => $post['descricao'],
                "caracteristicas"   => $post['caracteristicas'],
                "statusRegistro"    => $post['statusRegistro'],
                "categoria_id"      => $post['categoria_id'],
                "saldoEmEstoque"    => strNumber($post['saldoEmEstoque']),
                "precoVenda"        => strNumber($post['precoVenda']),
                "precoPromocao"     => strNumber($post['precoPromocao']),
                "custoTotal"        => strNumber($post['custoTotal']),
                'imagem'            => $nomeRetornado
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

            if (!empty($_FILES['imagem']['name'])) {

                // Faz uploado da imagem
                $nomeRetornado = UploadImages::upload($_FILES, 'produto');

                // se for boolean, significa que o upload falhou
                if (is_bool($nomeRetornado)) {
                    Session::set( 'inputs' , $post );
                    return Redirect::page("Produto/form/update/" . $post['id']);
                }

                UploadImages::delete($post['nomeImagem'], 'produto');

            } else {
                $nomeRetornado = $post['nomeImagem'];
            }

            if ($this->model->update(
                [
                    "id" => $post['id']
                ], 
                [
                    "descricao"         => $post['descricao'],
                    "caracteristicas"   => $post['caracteristicas'],
                    "statusRegistro"    => $post['statusRegistro'],
                    "categoria_id"      => $post['categoria_id'],
                    "saldoEmEstoque"    => strNumber($post['saldoEmEstoque']),
                    "precoVenda"        => strNumber($post['precoVenda']),
                    "precoPromocao"     => strNumber($post['precoPromocao']),
                    "custoTotal"        => strNumber($post['custoTotal']),
                    'imagem'            => $nomeRetornado
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