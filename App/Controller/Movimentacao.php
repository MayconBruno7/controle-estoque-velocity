<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;
use app\Library\Database;

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

        $MovimentacaoItemModel = $this->loadModel("MovimentacaoItem");
        $dados['aItemMovimentacao'] = $MovimentacaoItemModel->listaProdutos($this->getId());

        $SetorModel = $this->loadModel("Setor");
        $dados['aSetorMovimentacao'] = $SetorModel->lista('id');

        $FornecedorModel = $this->loadModel("Fornecedor");
        $dados['aFornecedorMovimentacao'] = $FornecedorModel->lista('id');

        return $this->loadView("restrita/formMovimentacao", $dados);
    }

    public function insert()
    {

        $post = $this->getPost();

        // Verifica se todos os campos do formulário foram enviados
        if (
            isset($post['fornecedor_id'], $post['tipo'], $post['statusRegistro'], $post['setor_id'],
            $post['data_pedido'], $post['motivo'], $post['statusRegistro'])
        ) {
            
        // var_dump($post);
        // exit("opa");
            // Dados da movimentação
            $fornecedor_id = (int)$post['fornecedor_id'];
            $setor_id = (int)$post['setor_id'];
            $data_pedido = $post['data_pedido'];
            $data_chegada = $post['data_chegada'];
            $motivo = $post['motivo'];
            $statusRegistro = (int)$post['statusRegistro'];
            $tipo_movimentacao = (int)$post['tipo'];

            // Dados do produto
            $quantidade = isset($post['quantidade']) ? (int)$post['quantidade'] : '';
            $id_produto = isset($post['id_produto']) ? (int)$post['id_produto'] : '';
            $valor_produto = isset($post['valor']) ? (float)$post['valor'] : '';
            $ProdutoModel = $this->loadModel("Produto");
            $dadosProduto['aProduto'] = $ProdutoModel->recuperaProduto($id_produto);

            if ($this->getAcao() == 'update') {

                // Verificar se há uma sessão de movimentação
                if (!isset($_SESSION['movimentacao'])) {
                    $_SESSION['movimentacao'] = array();
                }
            
                // Verificar se há produtos na sessão de movimentação
                if (!isset($_SESSION['movimentacao'][0]['produtos'])) {
                    $_SESSION['movimentacao'][0]['produtos'] = array();
                }
            
                // Verificar se o produto já está na sessão de movimentação
                $produtoEncontrado = false;
                foreach ($_SESSION['movimentacao'][0]['produtos'] as &$produto_sessao) {
                    if ($produto_sessao['id_produto'] == $id_produto) {
                        // Atualizar a quantidade do produto na sessão
                        $produto_sessao['quantidade'] += $quantidade;
                        $produtoEncontrado = true;
                        break;
                    }
                }
            
                // Session::set("msgSuccess", "Produto adicionado a movimentação.");
            } 
            // else if($this->getAcao() == 'update') {
            //     // Redirect::page("Movimentacao");
            //     Session::set("msgError", "Falha tentar inserir produto na movimentação.");
            // }

            // parte da inserção de movimentações e produtos
            $inserindoMovimentacaoEProdutos = $this->model->insertMovimentacao([
                "id_fornecedor"     => $fornecedor_id,
                "tipo"              => $tipo_movimentacao,
                "statusRegistro"    => $statusRegistro,
                "id_setor"          => $setor_id,
                "data_pedido"       => $data_pedido,
                "data_chegada"      => $data_chegada,
                "motivo"            => $motivo

            ],
            [
                [
                    // "id_movimentacoes"  => '',
                    "id_produtos"       => $id_produto,
                    "quantidade"        => $quantidade,
                    "valor"             => $valor_produto
                ]
            ],
                $tipo_movimentacao
            );

            if($inserindoMovimentacaoEProdutos) {
                Session::destroy('movimentacao');
                Session::destroy('produtos');
                Session::set("msgSuccess", "Movimentação adicionada com sucesso.");
                Redirect::page("Movimentacao");

            }
        }
    }

        /**
     * insert
     *
     * @return void
     */
    public function insertProdutoMovimentacao()
    {
        $post = $this->getPost();

        $id_movimentacao = isset($post['id_movimentacoes']) ? (int)$post['id_movimentacoes'] : ""; 
        $quantidade = (int)$post['quantidade'];
        $id_produto = (int)$post['id_produto'];
        $valor_produto = (float)$post['valor'];

        $ProdutoModel = $this->loadModel("Produto");
        $dadosProduto['aProduto'] = $ProdutoModel->recuperaProduto($id_produto);

        // Verificar se há uma sessão de movimentação
        if (!isset($_SESSION['movimentacao'])) {
            $_SESSION['movimentacao'] = array();
        }

        // Verificar se há produtos na sessão de movimentação
        if (!isset($_SESSION['movimentacao'][0]['produtos'])) {
            $_SESSION['movimentacao'][0]['produtos'] = array();
        }
    
        // Verificar se o produto já está na sessão de movimentação
        $produtoEncontrado = false;
        foreach ($_SESSION['movimentacao'][0]['produtos'] as &$produto_sessao) {
            if ($produto_sessao['id_produto'] == $id_produto) {
                // Atualizar a quantidade do produto na sessão
                $produto_sessao['quantidade'] += $quantidade;
                $produtoEncontrado = true;
                break;
            }
        }
   
        // Se o produto não estiver na sessão de movimentação, adicioná-lo
        if (!$produtoEncontrado) {
            $_SESSION['movimentacao'][0]['produtos'][] = array(
                'nome_produto' => $dadosProduto['aProduto'][0]['nome'],
                'id_produto' => $id_produto,
                'quantidade' => $quantidade,
                'valor' => $valor_produto
            );
        }

        // var_dump( $_SESSION['movimentacao']);
        // exit;
        Session::set("msgSuccess", "Produto adicionado a movimentação.");
        Redirect::page("Movimentacao/form/insert/0");
     
    }


    /**
     * deleteProdutoMovimentacao
     *
     * @return void
     */
    public function deleteProdutoMovimentacao()
    {
        $post = $this->getPost();

        // var_dump($post);
        // exit;  
        $id_movimentacao = isset($post['id_movimentacao']) ? (int)$post['id_movimentacao'] : ""; 
        $quantidadeRemover = (int)$post['quantidadeRemover'];
        $id_produto = (int)$post['id_produto'];
        $tipo_movimentacao = (int)$post['tipo'];

        if(isset($_SESSION['movimentacao'][0]['produtos']) && $this->getAcao() == 'delete') {
            // Verificar se o produto já está na sessão de movimentação
            $produtoEncontrado = false;
            foreach ($_SESSION['movimentacao'][0]['produtos'] as $key => &$produto_sessao) {
                if ($produto_sessao['id_produto'] == $id_produto) {
                    // Atualizar a quantidade do produto na sessão
                    $produto_sessao['quantidade'] -= $quantidadeRemover;

                    if ($produto_sessao['quantidade'] <= 0) {
                        // Remover o produto do array na sessão
                        unset($_SESSION['movimentacao'][0]['produtos'][$key]);
                    }
                    $produtoEncontrado = true;

                    Session::set("msgSuccess", "Produto excluído da movimentação.");
                    Redirect::page("Movimentacao/form/insert/0");
                    break;
                }
            }
        }
   
        if(!isset($_SESSION['movimentacao']) && $this->getAcao() == 'delete') {
            $ProdutoModel = $this->loadModel("Produto");
            $dadosProduto = $ProdutoModel->recuperaProduto($id_produto);
           
            $deletaProduto =  $this->model->deleteInfoProdutoMovimentacao($id_movimentacao, $dadosProduto, $tipo_movimentacao, $quantidadeRemover);

            if($deletaProduto) {
                Session::set("msgSuccess", "Item deletado da movimentação.");
                Redirect::page("Movimentacao/form/update/" . $id_movimentacao);
            }
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
    
        if (
            isset($post['id']) || 
            isset($post['id_movimentacao']) || 
            isset($post['id_produto']) || 
            isset($post['fornecedor_id']) || 
            isset($post['tipo']) || 
            isset($post['statusRegistro']) || 
            isset($post['setor_id']) || 
            isset($post['data_pedido']) || 
            isset($post['data_chegada']) || 
            isset($post['motivo'])
        ) {

            // Dados da movimentação
            $id_movimentacao = isset($post['id']) ? $post['id'] : (isset($post['id_movimentacao']) ? $post['id_movimentacao'] : "");
            $fornecedor_id = isset($post['fornecedor_id']) ? (int)$post['fornecedor_id'] : '';
            $setor_id = isset($post['setor_id']) ? (int)$post['setor_id'] : '';
            $data_pedido = isset($post['data_pedido']) ? $post['data_pedido'] : "";
            $data_chegada = isset($post['data_chegada']) ? $post['data_chegada'] : "";
            $motivo = isset($post['motivo']) ? $post['motivo'] : "";
            $statusRegistro = isset($post['statusRegistro']) ? (int)$post['statusRegistro'] : '';
            $tipo_movimentacao = isset($post['tipo']) ? (int)$post['tipo'] : '';
    
            // Dados do produto
            $id_produto = isset($post['id_produto']) ? $post['id_produto'] : '';
            $quantidades = isset($post['quantidade']) ? $post['quantidade'] : '';
            $valores_produtos = isset($post['valor']) ? $post['valor'] : "";

            // var_dump($post);
            // exit;
    
            $attQuantidade = 0; 
    
            if ($this->getAcao() != 'updateProdutoMovimentacao') {
                $AtualizandoMovimentacaoEProdutos = $this->model->updateMovimentacao(
                    [
                        "id_movimentacao"   => $id_movimentacao
                    ],
                    [
                        "id_fornecedor"     => $fornecedor_id,
                        "tipo"              => $tipo_movimentacao,
                        "statusRegistro"    => $statusRegistro,
                        "id_setor"          => $setor_id,
                        "data_pedido"       => $data_pedido,
                        "data_chegada"      => $data_chegada,
                        "motivo"            => $motivo
                    ],
                    [
                        [
                            "id_produtos"       => $id_produto,
                            "quantidade"        => $quantidades,
                            "valor"             => $valores_produtos
                        ]
                    ],
                );
    
                if ($AtualizandoMovimentacaoEProdutos) {
                    Session::destroy('movimentacao');
                    Session::destroy('produtos');
                    Session::set("msgSuccess", "Movimentacao alterada com sucesso.");
                    return Redirect::page("Movimentacao");
                } else {
                    Session::set("msgError", "Falha tentar alterar a Movimentacao.");
                }
            } else if ($this->getAcao() == 'updateProdutoMovimentacao') {
                $MovimentacaoItemModel = $this->loadModel("MovimentacaoItem");
                $dadosItensMovimentacao = $MovimentacaoItemModel->listaProdutos($this->getId());
          
                $found = false;

                if (!empty($dadosItensMovimentacao)) {
               
                    foreach ($dadosItensMovimentacao as $item) {
                        if ($id_produto == $item['id_prod_mov_itens'] && $id_movimentacao == $item['id_movimentacoes']) {
                            if ($tipo_movimentacao == 1) {
                                $attQuantidade = $item['mov_itens_quantidade'] + $quantidades;
                            } else if ($tipo_movimentacao == 2) {
                                $attQuantidade = $item['mov_itens_quantidade'] - $quantidades;
                           
                            }
                            $acaoProduto = 'update';
                            $found = true;

                            break;
                        }
                    }

                    if (!$found) {
                        $attQuantidade = $quantidades;
                        $acaoProduto = 'insert';
                    }
                } else {
                    $attQuantidade = $quantidades;
                    $acaoProduto = 'insert';
                }

                $AtualizandoInfoProdutoMovimentacao = $this->model->updateInformacoesProdutoMovimentacao(
                    [
                        "id_movimentacao" => $id_movimentacao
                    ],
                    [
                        [
                            "id_produtos" => $id_produto,
                            "quantidade" => $attQuantidade,
                            "valor" => $valores_produtos
                        ]
                    ],
                    [
                        'acaoProduto' => $acaoProduto
                    ],
                    $tipo_movimentacao
                    
                );
                // exit("opa");
    
                if ($AtualizandoInfoProdutoMovimentacao) {
                    Session::destroy('movimentacao');
                    Session::destroy('produtos');
                    Session::set("msgSuccess", "Movimentacao alterada com sucesso.");
                    return Redirect::page("Movimentacao/form/update/" . $id_movimentacao);
                } else {
                    Session::set("msgError", "Falha tentar alterar a Movimentacao.");
                    return Redirect::page("Movimentacao/form/update/" . $id_movimentacao);
                }
            }
        } else {
            Session::set("msgError", "Falha tentar alterar a Movimentacao.");
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