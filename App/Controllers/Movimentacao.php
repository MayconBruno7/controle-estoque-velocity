<?php

namespace App\Controllers;

use App\Models\model;
use App\Models\MovimentacaoItemModel;
use App\Models\SetorModel;
use App\Models\FornecedorModel;
use App\Models\MovimentacaoModel;
use App\Models\ProdutoModel;

class Movimentacao extends BaseController
{
    protected $model;
    protected $movimentacaoItemModel;
    protected $setorModel;
    protected $fornecedorModel;
    protected $produtoModel;


    public function __construct()
    {

        // Injeção de dependência dos modelos
        $this->model = new MovimentacaoModel();
        $this->movimentacaoItemModel = new MovimentacaoItemModel();
        $this->setorModel = new SetorModel();
        $this->fornecedorModel = new FornecedorModel();
        $this->produtoModel = new ProdutoModel();

        // Redirecionar se o usuário não estiver logado
        // if (!$this->getAdministrador()) {
        //     return redirect()->to(base_url('home'));
        // }
    }

    public function index()
    {
        $data['movimentacoes'] = $this->model->getLista();
        return view('restrita/listaMovimentacao', $data);
    }

    public function form($action, $id = null)
    {
        $data['action'] = $action;
        $data['data'] = null;
        $data['errors'] = [];

        if ($action != "new" && $id !== null) {
            $data['aItemMovimentacao'] = $this->movimentacaoItemModel->listaProdutos($id);
            $data['data'] = $this->model->find($id);
        }

        $data['aSetor'] = $this->setorModel->findAll();
        $data['aFornecedor'] = $this->fornecedorModel->findAll();
        $data['aProduto'] = $this->produtoModel->findAll();

        // var_dump($data);
        // exit;
        return view('restrita/formMovimentacao', $data);
    }

    public function salvarSessao()
    {

        // Receber dados em JSON da requisição
        $movimentacao = $this->request->getJSON(); // Get JSON enviado pelo cliente

        // Verificar se os dados foram recebidos
        if ($movimentacao) {
            // Aqui você pode salvar os dados no banco ou processar conforme necessário
            // Exemplo de como acessar os valores:
            // $fornecedor_id = $movimentacao->fornecedor_id;
            // $tipo_movimentacao = $movimentacao->tipo_movimentacao;
            // $statusRegistro = $movimentacao->statusRegistro;
            // $setor_id = $movimentacao->setor_id;
            // $data_pedido = $movimentacao->data_pedido;
            // $data_chegada = $movimentacao->data_chegada;
            // $motivo = $movimentacao->motivo;
            // $produtos = $movimentacao->produtos;

            session()->set('movimentacao', [
                'fornecedor_id' => $movimentacao->fornecedor_id,
                'tipo_movimentacao' => $movimentacao->tipo_movimentacao,
                'statusRegistro' => $movimentacao->statusRegistro,
                'setor_id' => $movimentacao->setor_id,
                'data_pedido' => $movimentacao->data_pedido,
                'data_chegada' => $movimentacao->data_chegada,
                'motivo' => $movimentacao->motivo,
                'produtos' => $movimentacao->produtos // Atualiza apenas a parte dos produtos
            ]);

            // Retornar uma resposta JSON de sucesso
            return $this->response->setJSON([
                // Salva os dados na sessão
                'status' => 'success',
                'message' => 'Movimentação salva com sucesso'
            ]);

        } else {
            // Caso os dados não tenham sido enviados corretamente, retorna um erro
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Dados inválidos ou não enviados corretamente'
            ]);
        }


    }

    public function new()
    {
        $post = $this->request->getPost(); // Obtendo dados do POST

        // Verifica se todos os campos necessários do formulário foram enviados
        if (isset($post['fornecedor_id'],
            $post['tipo'],
            $post['statusRegistro'],
            $post['setor_id'],
            $post['data_pedido'],
            $post['motivo']))
        {

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

            // Carregar o model de Produto
            $ProdutoModel = new ProdutoModel(); // Não precisamos mais de `loadModel()`
            $dadosProduto = $ProdutoModel->recuperaProduto($id_produto);

            // Recuperando todos os segmentos da URL
            $segmentos = $this->request->getURI()->getSegments(3);

            // Acessando o primeiro segmento
            $action = $segmentos[1] ?? null;

            // Se estamos em modo de atualização
            if ($action == 'update') {
                // Verificar se a sessão de movimentação existe
                $session = session();
                if (!$session->has('movimentacao')) {
                    $session->set('movimentacao', ['produtos' => []]);
                }

                $movimentacao = $session->get('movimentacao');
                $produtos = isset($movimentacao['produtos']) ? $movimentacao['produtos'] : [];

                // Verificar se o produto já está na sessão de movimentação
                $produtoEncontrado = false;
                foreach ($produtos as &$produto_sessao) {
                    if ($produto_sessao['id_produto'] == $id_produto) {
                        // Atualiza a quantidade do produto na sessão
                        $produto_sessao['quantidade'] += $quantidade;
                        $produtoEncontrado = true;
                        break;
                    }
                }

                // Caso o produto não tenha sido encontrado, adiciona-o à sessão
                if (!$produtoEncontrado) {

                    $produtos[] = [
                        'id_produto' => $id_produto,
                        'quantidade' => $quantidade,
                        'valor' => $valor_produto
                    ];
                }

                // Atualiza a sessão com os produtos modificados
                $movimentacao['produtos'] = $produtos;
                $session->set('movimentacao', $movimentacao);
            }

            // Verificação de estoque
            $verificaQuantidadeEstoqueNegativa = false;
            if (isset($id_produto) && $id_produto != '') {

                if ($dadosProduto['quantidade'] >= $quantidade && $tipo_movimentacao == '2') {
                    $verificaQuantidadeEstoqueNegativa = true;

                } elseif ($dadosProduto['quantidade'] < $quantidade && $tipo_movimentacao == '2') {
                    $verificaQuantidadeEstoqueNegativa = false;
                  
                }
            }

            // Se for movimentação de entrada, estoque negativo não importa
            if ($tipo_movimentacao == '1') {
                $verificaQuantidadeEstoqueNegativa = true;
            }

            // Se a verificação de estoque for bem-sucedida
            if ($verificaQuantidadeEstoqueNegativa) {
                // Inserir movimentação e produtos
                $inserir = $this->model->insertMovimentacao([
                    'id_fornecedor'    => $fornecedor_id,
                    'tipo'             => $tipo_movimentacao,
                    'statusRegistro'   => $statusRegistro,
                    'id_setor'         => $setor_id,
                    'data_pedido'      => $data_pedido,
                    'data_chegada'     => $data_chegada,
                    'motivo'           => $motivo
                ], [
                    [
                        'id_produtos'      => $id_produto,
                        'quantidade'       => $quantidade,
                        'valor'            => $valor_produto
                    ]
                ]);

                if($inserir) {
                    // Limpa a sessão e redireciona
                    session()->has('prod_mov_atualizado') ? session()->set('produto_mov_atualizado', true) : "";
                    session()->has('movimentacao') ? session()->destroy('movimentacao') : "";
                    session()->has('produtos') ? session()->destroy('produtos') : "";
                    return redirect()->to("Movimentacao")->with('msgSuccess', 'Movimentação adicionada com sucesso.');

                }
            } else {
                return redirect()->to("Movimentacao/form/new/0")->with('msgError', 'Quantidade da movimentação de saída maior que a do produto em estoque.');
            }
        } else {
            return redirect()->to("Movimentacao/form/new/0")->with('msgError', 'Dados do formulário insuficientes.');
        }
    }

    public function newProdutoMovimentacao()
    {
        $post = $this->request->getPost();

        // Obtenha os dados do produto do POST
        $id_movimentacao = isset($post['id_movimentacoes']) ? (int)$post['id_movimentacoes'] : "";
        $quantidade = (int)$post['quantidade'];
        $id_produto = (int)$post['id_produto'];
        $valor_produto = (float)$post['valor'];

        $produtoEncontrado = false;

        // Recuperar produto e garantir que o resultado seja um array
        $dadosProduto['aProduto'] = (array) $this->produtoModel->recuperaProduto($id_produto);

        // Verificar se há uma sessão de movimentação
        if (!session()->has('movimentacao')) {
            // Inicializa a movimentação com um array vazio para produtos
            session()->set('movimentacao', [
                'produtos' => [] // Corrigido para garantir que seja um array
            ]);
        }

        // Obter produtos da sessão e garantir que seja um array
        $movimentacao = session()->get('movimentacao');
        $produtos = isset($movimentacao['produtos']) ? (array) $movimentacao['produtos'] : [];

        // Acesse os produtos e verifique se o produto já está na sessão
        foreach ($produtos as &$produto_sessao) {
            // Se o produto na sessão for um objeto, converta para array
            if (is_object($produto_sessao)) {
                $produto_sessao = (array) $produto_sessao;
            }

            if (isset($produto_sessao['id_produto']) && $produto_sessao['id_produto'] == $id_produto) {
                $produtoEncontrado = true;

                // Converter a quantidade para um inteiro para evitar problemas com strings
                $quantidadeAtual = (int)$produto_sessao['quantidade'];
                $quantidadeNova = $quantidadeAtual + $quantidade;

                // Atualizar a quantidade do produto na sessão
                $produto_sessao['quantidade'] = $quantidadeNova;

                break; // Saia do loop se o produto foi encontrado e atualizado
            }
        }

        // Se o produto não estiver na sessão de movimentação, adicioná-lo
        if (!$produtoEncontrado) {
            // Adiciona o novo produto ao array de produtos
            $produtos[] = [
                'nome_produto' => $dadosProduto['aProduto']['nome'],
                'id_produto' => $id_produto,
                'quantidade' => $quantidade,
                'valor' => $valor_produto
            ];
        }

        // Atualiza a sessão mantendo as outras informações da movimentação
        $movimentacaoAtual = session()->get('movimentacao'); // Obtém a movimentação atual
        $movimentacaoAtual['produtos'] = $produtos; // Atualiza apenas a parte dos produtos

        session()->set('movimentacao', $movimentacaoAtual); // Atualiza a sessão com todas as informações
        return redirect()->to('Movimentacao/form/new/0')->with('msgSuccess', 'Produto adicionado à movimentação.');

        
    }

    public function deleteProdutoMovimentacao()
    {
        $post = $this->request->getPost();

        // var_dump($post);
        // exit;

        $id_movimentacao = isset($post['id_movimentacao']) ? (int)$post['id_movimentacao'] : "";
        $quantidadeRemover = (int)$post['quantidadeRemover'];
        $id_produto = (int)$post['id_produto'];
        $tipo_movimentacao = (int)$post['tipo'];

        // Recuperando todos os segmentos da URL
        $segmentos = service('request')->getURI()->getSegments();
        $action = $segmentos[2] ?? null;

        $produtoEncontrado = false;

        // Verifica se a sessão de movimentação e os produtos estão definidos
        if (session()->has('movimentacao') && isset(session('movimentacao')['produtos']) && $action === 'delete') {
            // Percorre os produtos na sessão de movimentação
            foreach (session('movimentacao')['produtos'] as $key => &$produto_sessao) { // Usando referência (&)

                if ($produto_sessao['id_produto'] == $id_produto) {
                    // Verifica se a quantidade a ser removida é válida
                    if ($quantidadeRemover <= 0) {
                        return redirect()->to('Movimentacao/form/new/0')->with('msgError', "Quantidade a ser removida deve ser maior que zero.");
                    }

                    // Atualiza a quantidade do produto na sessão
                    $produto_sessao['quantidade'] -= $quantidadeRemover;

                    // Se a quantidade for menor ou igual a zero, remove o produto da sessão
                    if ($produto_sessao['quantidade'] <= 0) {
                        unset($_SESSION['movimentacao']['produtos'][$key]);
                    }

                    $produtoEncontrado = true;

                    // Atualiza a sessão mantendo as outras informações da movimentação
                    $movimentacaoAtual = session()->get('movimentacao'); // Obtém a movimentação atual
                    $movimentacaoAtual['produtos'] = [$produto_sessao]; // Atualiza apenas a parte dos produtos

                    session()->set('movimentacao', $movimentacaoAtual); // Atualiza a sessão com todas as informações

                    return redirect()->to('Movimentacao/form/new/0')->with('msgSuccess', "Produto excluído da movimentação.");

                }
            }   
        }
   
            
        // var_dump($post);
        // exit;

        // Caso o produto não seja encontrado na sessão, verifique no banco de dados
        if(!session()->has('movimentacao') && $action == 'delete') {
            $dadosProduto = $this->produtoModel->recuperaProduto($id_produto);
            // var_dump($post);
            // exit;
    
            $deletaProduto = $this->model->deleteInfoProdutoMovimentacao($id_movimentacao, $dadosProduto, $tipo_movimentacao, $quantidadeRemover);

            if ($deletaProduto) {
                return redirect()->to('Movimentacao/form/update/' . $id_movimentacao)->with('msgSuccess', "Item deletado da movimentação.");

            }
        }

        return redirect()->to(base_url('Movimentacao/form/new/0'));
    }




//     public function delete($id)
//     {
//         if ($this->model->delete($id)) {
//             session()->setFlashdata('msgSuccess', 'Movimentação removida com sucesso.');
//         } else {
//             session()->setFlashdata('msgError', 'Erro ao remover movimentação.');
//         }

//         return redirect()->to(base_url('movimentacao'));
//     }
// }


    /**
     * update
     *
     * @return void
     */
    public function update()
    {
        $post = $this->request->getPost();

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

            // Recuperando todos os segmentos da URL
            $segmentos = service('request')->getURI()->getSegments();
            $action = $segmentos[2] ?? null;

            // Dados da movimentação
            $id_movimentacao = $post['id'] ?? $post['id_movimentacao'] ?? "";
            $fornecedor_id = (int)($post['fornecedor_id'] ?? '');
            $setor_id = (int)($post['setor_id'] ?? '');
            $data_pedido = $post['data_pedido'] ?? "";
            $data_chegada = $post['data_chegada'] ?? "";
            $motivo = $post['motivo'] ?? "";
            $statusRegistro = (int)($post['statusRegistro'] ?? '');
            $tipo_movimentacao = (int)($post['tipo'] ?? '');

            // Dados do produto
            $id_produto = $post['id_produto'] ?? '';
            $quantidade = (int)($post['quantidade'] ?? 0);
            $valores_produtos = $post['valor'] ?? "";

            $produtoMovAtualizado = session()->get('produto_mov_atualizado') ?? [];

            $found = false;

            $dadosItensMovimentacao =  $this->movimentacaoItemModel->listaProdutos($id_movimentacao);

            $quantidade_movimentacao = 0;

            foreach ($dadosItensMovimentacao as $item) {
                if ($id_produto == $item['id_prod_mov_itens'] && $id_movimentacao == $item['id_movimentacoes']) {
                    if ($tipo_movimentacao == 1) {
                        $quantidade_movimentacao = $item['mov_itens_quantidade'] + $quantidade;

                    } else if ($tipo_movimentacao == 2) {
                        $quantidade_movimentacao = $item['mov_itens_quantidade'] - $quantidade;
                    }
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $quantidade_movimentacao = $quantidade;
            }

            $acaoProduto = $found ? 'update' : 'new';

            // Validação de quantidade em estoque
            $dadosProduto = $this->produtoModel->recuperaProduto($id_produto); // Método para obter dados do produto
            $verificaQuantidadeEstoqueNegativa = true;

            if (!empty($dadosProduto)) {
                if ($dadosProduto['quantidade'] < $quantidade && $tipo_movimentacao == 2) {
                    $verificaQuantidadeEstoqueNegativa = false;
                }
            }

            if ($tipo_movimentacao == 1) {
                $verificaQuantidadeEstoqueNegativa = true;
            }

            if ($action != 'updateProdutoMovimentacao') {
                
           
                $atualizandoMovimentacaoEProdutos = $this->model->updateMovimentacao(
                    $id_movimentacao,
                    [
                        "id_fornecedor" => $fornecedor_id,
                        "tipo" => $tipo_movimentacao,
                        "statusRegistro" => $statusRegistro,
                        "id_setor" => $setor_id,
                        "data_pedido" => $data_pedido,
                        "data_chegada" => $data_chegada,
                        "motivo" => $motivo
                    ],
                    $produtoMovAtualizado
                );
                
                if ($atualizandoMovimentacaoEProdutos) {
                    session()->has('prod_mov_atualizado') ? session()->set('produto_mov_atualizado', true) : "";
                    session()->has('movimentacao') ? session()->destroy('movimentacao') : '';
                    session()->has('movimentacao') ? session()->destroy('produtos') : '';

                    return redirect()->to('/Movimentacao')->with("msgSuccess", "Movimentação alterada com sucesso.");

                } else {
                    return redirect()->to('/Movimentacao/form/update/' . $id_movimentacao)->with("msgError", "Falha ao tentar alterar a movimentação.");
                }
            } else if ($action == 'updateProdutoMovimentacao') {

              
                if ($verificaQuantidadeEstoqueNegativa) {
                    $atualizandoInfoProdutoMovimentacao = $this->model->updateInformacoesProdutoMovimentacao(
                        $id_movimentacao,
                        [
                            [
                                "id_produtos" => $id_produto,
                                "valor" => $valores_produtos
                            ]
                        ],
                        [
                            'acaoProduto' => $acaoProduto
                        ],
                        $quantidade,
                        $quantidade_movimentacao
                    );

                    if ($atualizandoInfoProdutoMovimentacao) {
                        session()->has('prod_mov_atualizado') ? session()->set('produto_mov_atualizado', true) : "";
                        session()->has('movimentacao') ? session()->destroy('movimentacao') : "";
                        session()->has('produtos') ? session()->destroy('produtos') : "";

                        return redirect()->to('/Movimentacao/form/update/' . $id_movimentacao)->with("msgSuccess", "Movimentação alterada com sucesso.");

                    }
                } else {
                    return redirect()->to('/Movimentacao/form/update/' . $id_movimentacao)->with("msgError", "Quantidade da movimentação de saída maior que a do produto em estoque.");
                    
                }
            } else {
                return redirect()->to('/Movimentacao/form/update/' . $id_movimentacao)->with("msgError", "Falha ao tentar alterar a movimentação.");
                
            }
        }
    }


    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {
        $post = $this->request->getPost();

        // Dados do produto
        $id_produto = $post['id_produto'] ?? '';
        $quantidades = $post['quantidade'] ?? '';
        $valor_produto = $post['valor'] ?? '';

        $nova_quantidade_produto = 0;

        // Obter dados de movimentação
        $dadosMovimentacao = $this->model->getLista();

        // Recuperar dados do produto
        $dadosProduto = $this->produtoModel->recuperaProduto($id_produto);
      

        $quantidadeProduto = $dadosProduto[0]['quantidade'] ?? 0;

        // Loop através de cada movimentação para encontrar o tipo
        $tipo_movimentacao = null; // Inicializa a variável
        foreach ($dadosMovimentacao as $movimentacao) {
            if ($post['id'] == $movimentacao['id_movimentacao']) {
                $tipo_movimentacao = $movimentacao['tipo_movimentacao'];
                break; // Sai do loop após encontrar a movimentação
            }
        }

        // Atualizar a quantidade do produto com base no tipo de movimentação
        if ($tipo_movimentacao === 1) {
            $nova_quantidade_produto = (int)$quantidadeProduto - (int)$quantidades;
        } else if ($tipo_movimentacao === 2) {
            $nova_quantidade_produto = (int)$quantidadeProduto + (int)$quantidades;
        }

        // Excluir a movimentação
        if ($this->model->delete($post['id'])) {
       
            // Atualizar informações do produto, se necessário
            if (!empty($id_produto)) {
                $this->model->updateInformacoesProdutoMovimentacao(
                    $post['id'],
                    [
                        [
                            'id_produtos' => $id_produto,
                            'valor' => $valor_produto
                        ]
                    ],
                    [
                        'acaoProduto' => 'update'
                    ],
                    $nova_quantidade_produto
                );
            }

            // Mensagem de sucesso
            return redirect()->to('/Movimentacao')->with('msgSuccess', 'Movimentacao excluída com sucesso.');

        } else {
            // Mensagem de erro
            return redirect()->to('/Movimentacao')->with('msgError', 'Falha ao tentar excluir a Movimentacao.');
        }
    }

    public function getProdutoComboBox()
    {

        $termo = $this->request->getVar('termo');

        $dados = $this->model->getProdutoCombobox($termo);

        echo json_encode($dados);

    }

}