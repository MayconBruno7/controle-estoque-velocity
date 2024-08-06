<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class OrdemServico extends ControllerMain
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
        $this->loadView("restrita/listaOrdemServico", $this->model->lista("id"));
    }

    /**
     * form
     *
     * @return void
     */
    public function form()
    {
        $dados = [];

        $PecaModel = $this->loadModel("Peca");
        $dados['aPeca'] = $PecaModel->listaPeca($this->getId());

        if ($this->getAcao() != "insert") {
            $registro = $this->model->getById($this->getId());
            // Mescla os dados de $registro com os dados existentes em $dados
            $dados = array_merge($dados, $registro);
        }
        return $this->loadView("restrita/formOrdemServico", $dados);
    }
    
    public function getPecaComboBox()
    {

        $dados = $this->model->getPecaCombobox($this->getOutrosParametros(2)); 
    

        echo json_encode($dados);

    
    }

    public function insert()
    {

        $post = $this->getPost();

        // var_dump($post);
        // exit;

        // Verifica se todos os campos do formulário foram enviados
        if (
            isset($post['cliente_nome'], $post['telefone_cliente'], $post['modelo_dispositivo'], $post['imei_dispositivo'],
            $post['tipo_servico'], $post['descricao_servico'], $post['problema_reportado'], $post['data_abertura'], 
            $post['status'], $post['observacoes'], $post['quantidade'], $post['id_peca'], $post['valor'])
        ) {
         
            // Dados da ordem de servico
            $cliente_nome = $post['cliente_nome'];
            $telefone_cliente = $post['telefone_cliente'];
            $modelo_dispositivo = $post['modelo_dispositivo'];
            $imei_dispositivo = $post['imei_dispositivo'];
            $tipo_servico = $post['tipo_servico'];
            $descricao_servico = $post['descricao_servico'];
            $problema_reportado = $post['problema_reportado'];
            $data_abertura = $post['data_abertura'];
            $status = $post['status'];
            $observacoes = $post['observacoes'];

            // Dados da peça
            $quantidade = isset($post['quantidade']) ? (int)$post['quantidade'] : '';
            $id_peca = isset($post['id_peca']) ? (int)$post['id_peca'] : '';
            $valor_peca = isset($post['valor']) ? (float)$post['valor'] : '';

            $PecaModel = $this->loadModel("Peca");
            $dadosProduto = $PecaModel->recuperaPeca($id_peca);
          
            if ($this->getAcao() == 'update') {
                
                // Verificar se há uma sessão de movimentação
                if (!isset($_SESSION['ordem_servico'])) {
                    $_SESSION['ordem_servico'] = array();
                }
            
                // Verificar se há produtos na sessão de movimentação
                if (!isset($_SESSION['ordem_servico'][0]['produtos'])) {
                    $_SESSION['ordem_servico'][0]['produtos'] = array();
                }
            
                // Verificar se o produto já está na sessão de movimentação
                $produtoEncontrado = false;
                foreach ($_SESSION['ordem_servico'][0]['produtos'] as &$produto_sessao) {
                    if ($produto_sessao['id_peca'] == $id_peca) {
                        // Atualizar a quantidade do produto na sessão
                        $produto_sessao['quantidade'] += $quantidade;
                        $produtoEncontrado = true;
                        break;
                    }
                }
            } 
            
                // parte da inserção de movimentações e produtos
                $inserindoMovimentacaoEProdutos = $this->model->insertOrdemServico([
                    "cliente_nome"              => $cliente_nome,
                    "telefone_cliente"          => $telefone_cliente,
                    "modelo_dispositivo"        => $modelo_dispositivo,
                    "imei_dispositivo"          => $imei_dispositivo,
                    "tipo_servico"              => $tipo_servico,
                    "descricao_servico"         => $descricao_servico,
                    "problema_reportado"        => $problema_reportado,
                    "status"                    => $status,
                    "data_abertura"             => $data_abertura,
                    "observacoes"               => $observacoes,
                
                ],
                [
                    [
                        // "d_ordem_servico"  => '',
                        "id_peca"           => $id_peca,
                        "quantidade"        => $quantidade,
                    ]
                ]
                );

                
                // exit('opa');
            
                if($inserindoMovimentacaoEProdutos) {
                    Session::destroy('ordem_servico');
                    Session::destroy('produtos');
                    Session::set("msgSuccess", "Movimentação adicionada com sucesso.");
                    Redirect::page("OrdemServico");
                }
        } else {
            Session::set("msgError", "Dados do formulário insuficientes.");
            Redirect::page("OrdemServico/form/insert/0");
        }
    }

    /**
     * insertProdutoMovimentacao
     *
     * @return void
     */
    public function insertProdutoMovimentacao()
    {
        $post = $this->getPost();

        // var_dump($post);
        // exit;

        $id_ordem_servico = isset($post['id_ordem_servico']) ? $post['id_ordem_servico'] : ""; 
        $quantidade = $post['quantidade'];
        $id_peca = $post['id_peca'];
        $valor_produto = (float)$post['valor'];

        $PecaModel = $this->loadModel("Peca");
        $dadosProduto['aPeca'] = $PecaModel->recuperaPeca($id_peca);

        // Verificar se há uma sessão de movimentação
        if (!isset($_SESSION['ordem_servico'])) {
            $_SESSION['ordem_servico'] = array();
        }

        // Verificar se há produtos na sessão de movimentação
        if (!isset($_SESSION['ordem_servico'][0]['produtos'])) {
            $_SESSION['ordem_servico'][0]['produtos'] = array();
        }
    
        // Verificar se o produto já está na sessão de movimentação
        $produtoEncontrado = false;
        foreach ($_SESSION['ordem_servico'][0]['produtos'] as &$produto_sessao) {
            if ($produto_sessao['id_peca'] == $id_peca) {
                // Atualizar a quantidade do produto na sessão
                $produto_sessao['quantidade'] += $quantidade;
                $produtoEncontrado = true;
                break;
            }
        }
   
        // Se o produto não estiver na sessão de movimentação, adicioná-lo
        if (!$produtoEncontrado) {
            $_SESSION['ordem_servico'][0]['produtos'][] = array(
                'nome_peca' => $dadosProduto['aPeca'][0]['nome_peca'],
                'id_peca' => $id_peca,
                'quantidade' => $quantidade,
                'valor' => $valor_produto
            );
        }

        Session::set("msgSuccess", "Peça adicionada a ordem de serviço.");
        Redirect::page("OrdemServico/form/insert/0");
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
            isset($post['cliente_nome'], $post['telefone_cliente'], $post['modelo_dispositivo'], $post['imei_dispositivo'],
            $post['tipo_servico'], $post['descricao_servico'], $post['problema_reportado'], $post['data_abertura'], 
            $post['status'], $post['observacoes'], $post['quantidade'], $post['id_peca'], $post['valor'])
        ) {

            // Dados da ordem de servico
            $id_ordem_servico = $post['id'];
            $cliente_nome = $post['cliente_nome'];
            $telefone_cliente = $post['telefone_cliente'];
            $modelo_dispositivo = $post['modelo_dispositivo'];
            $imei_dispositivo = $post['imei_dispositivo'];
            $tipo_servico = $post['tipo_servico'];
            $descricao_servico = $post['descricao_servico'];
            $problema_reportado = $post['problema_reportado'];
            $data_abertura = $post['data_abertura'];
            $status = $post['status'];
            $observacoes = $post['observacoes'];

            // Dados da peça
            $quantidade = isset($post['quantidade']) ? (int)$post['quantidade'] : '';
            $id_peca = isset($post['id_peca']) ? (int)$post['id_peca'] : '';
            $valor_peca = isset($post['valor']) ? (float)$post['valor'] : '';

            $produtoMovAtualizado = isset($_SESSION['produto_mov_atualizado']) ? $_SESSION['produto_mov_atualizado'] : [];

            $found = false;

            (int)$quantidade_peca = (int)$quantidade; 

            $MovimentacaoItemModel = $this->loadModel("OrdemServico");
            $dadosItensMovimentacao = $MovimentacaoItemModel->recuperaPecaOS($id_peca);

            $quantidade_movimentacao = 0;

            // foreach ($dadosItensMovimentacao as $index => $item) {
            //     if ($id_peca == $item['id_prod_mov_itens'] && $id_ordem_servico == $item['id_movimentacoes']) {
            //         if ($tipo_movimentacao == 1) {
            //             $quantidade_movimentacao = $item['quantidade'] + (int)$quantidades;
            //         } else if ($tipo_movimentacao == 2) {
            //             $quantidade_movimentacao =  $item['quantidade'] - (int)$quantidades;
            //         }
            //         break;
            //     } else {
            //         $quantidade_movimentacao = (int)$quantidades;
            //     }
            // }

            if (!empty($dadosItensMovimentacao)) {
             
                foreach ($dadosItensMovimentacao as $item) {

                    if ($id_peca == $item['id_peca'] && $id_ordem_servico == $item['id_ordem_servico']) {
                        $acaoProduto = 'update';
                        $found = true;

                        break;
                    }
                }     

                if (!$found) {
                    $acaoProduto = 'insert';
                }
         
            } else {
                $quantidade_movimentacao = (int)$quantidade;
                if(isset($post['id_peca'])) {
                    if ($id_peca == $post['id_peca'] && $id_ordem_servico == $post['id_ordem_servico']) {
                        $acaoProduto = 'insert';
                    }
                }   
            }

            if (!empty($dadosProduto)) {
                if ($dadosProduto[0]['quantidade'] >= $quantidade) {
                    $verificaQuantidadeEstoqueNegativa = true;
                } else if ($dadosProduto[0]['quantidade'] < $quantidade) {
                    $verificaQuantidadeEstoqueNegativa = false;
                } 
            }
            
            if ($this->getAcao() != 'updateProdutoMovimentacao') {

                $AtualizandoMovimentacaoEProdutos = $this->model->updateOrdemServico(
                    [
                        "id"   => $id_ordem_servico
                    ],
                    [
                        "cliente_nome"      => $cliente_nome,
                        "telefone_cliente"  => $telefone_cliente,
                        "modelo_dispositivo"=> $modelo_dispositivo,
                        "imei_dispositivo"  => $imei_dispositivo,
                        "tipo_servico"      => $tipo_servico,
                        "descricao_servico" => $descricao_servico,
                        "problema_reportado"=> $problema_reportado,
                        "status"            => $status,
                        "data_abertura"     => $data_abertura,
                        "observacoes"       => $observacoes,
                    ],
                    [
                        [
                            "id_peca"       => $id_peca,
                            "quantidade"    => $quantidade,
                            "valor"         => $valor_peca
                        ]
                    ],

                );

                if ($AtualizandoMovimentacaoEProdutos) {
                    Session::destroy('OrdemServico');
                    Session::destroy('produtos');
                    Session::set("msgSuccess", "OrdemServico alterada com sucesso.");
                    return Redirect::page("OrdemServico");
                } else {
                    Session::set("msgError", "Falha tentar alterar a Movimentacao.");
                }

            } else if ($this->getAcao() == 'updateProdutoMovimentacao') {

                if ($verificaQuantidadeEstoqueNegativa) {
                    $AtualizandoInfoProdutoMovimentacao = $this->model->updateInformacoesProdutoMovimentacao(
                        [
                            "id" => $id_ordem_servico
                        ],
                        [
                            [
                                "id_pecas"           => $id_peca,
                                // "quantidade"            => $quantidades,
                                "valor"                 => $valor_peca
                            ]
                        ],
                        [
                            'acaoProduto' => $acaoProduto
                        ],
                        $quantidade_movimentacao
                        
                    );

                    if ($AtualizandoInfoProdutoMovimentacao) {
                        if (!isset($_SESSION['produto_mov_atualizado'])) {
                            $_SESSION['produto_mov_atualizado'] = true;
                        }
                        
                        Session::destroy('movimentacao');
                        Session::destroy('produtos');
                        Session::set("msgSuccess", "Movimentacao alterada com sucesso.");
                        return Redirect::page("Movimentacao/form/update/" . $id_ordem_servico); 
                    }

                } else {
                    Session::set("msgError", "Quantidade da movimentação de saída maior que a do produto em estoque.");
                    return Redirect::page("Movimentacao/form/update/" . $id_ordem_servico);
                }
            } else {
                Session::set("msgError", "Falha tentar alterar a Movimentacao.");
                return Redirect::page("Movimentacao");
            }
        }
    }

    /**
     * deleteProdutoMovimentacao
     *
     * @return void
     */
    public function deleteProdutoMovimentacao()
    {
        $post = $this->getPost();

        $id_movimentacao = isset($post['id_movimentacao']) ? (int)$post['id_movimentacao'] : ""; 
        $quantidadeRemover = (int)$post['quantidadeRemover'];
        $id_produto = (int)$post['id_produto'];
        $tipo_movimentacao = (int)$post['tipo'];

        if(isset($_SESSION['ordem_servico'][0]['produtos']) && $this->getAcao() == 'delete') {
            // Verificar se o produto já está na sessão de movimentação
            $produtoEncontrado = false;
            foreach ($_SESSION['ordem_servico'][0]['produtos'] as $key => &$produto_sessao) {
                if ($produto_sessao['id_produto'] == $id_produto) {
                    // Atualizar a quantidade do produto na sessão
                    $produto_sessao['quantidade'] -= $quantidadeRemover;

                    if ($produto_sessao['quantidade'] <= 0) {
                        // Remover o produto do array na sessão
                        unset($_SESSION['ordem_servico'][0]['produtos'][$key]);
                    }
                    $produtoEncontrado = true;

                    Session::set("msgSuccess", "Produto excluído da movimentação.");
                    Redirect::page("OrdemServico/form/insert/0");
                    break;
                }
            }
        }
   
        if(!isset($_SESSION['ordem_servico']) && $this->getAcao() == 'delete') {
            $ProdutoModel = $this->loadModel("Peca");
            $dadosProduto = $ProdutoModel->recuperaPeca($id_produto);

            $deletaProduto =  $this->model->deleteInfoProdutoMovimentacao($id_movimentacao, $dadosProduto, $tipo_movimentacao, $quantidadeRemover);

            if($deletaProduto) {
                Session::set("msgSuccess", "Item deletado da movimentação.");
                Redirect::page("OrdemServico/form/update/" . $id_movimentacao);
            }
        }
        
    }

    public function requireimprimirOS() {

        $this->model->imprimirOS($this->getOutrosParametros(2));
    }

    // 
    // /**
    //  * delete
    //  *
    //  * @return void
    //  */
    // public function delete()
    // {
    //     if ($this->model->delete(["id" => $this->getPost('id')])) {
    //         Session::set("msgSuccess", "Cargo excluída com sucesso.");
    //     } else {
    //         Session::set("msgError", "Falha tentar excluir a Cargo.");
    //     }

    //     Redirect::page("Cargo");
    // }


}