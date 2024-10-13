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
        if (!$this->getAdministrador()) {
            return redirect()->to(base_url('home'));
        }
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
            $data['itemMovimentacao'] = $this->movimentacaoItemModel->listaProdutos($id);
            $data['data'] = $this->model->find($id);
        }

        $data['aSetor'] = $this->setorModel->findAll();
        $data['aFornecedor'] = $this->fornecedorModel->findAll();
        $data['aProduto'] = $this->produtoModel->findAll();

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
            $fornecedor_id = $movimentacao->fornecedor_id;
            $tipo_movimentacao = $movimentacao->tipo_movimentacao;
            $statusRegistro = $movimentacao->statusRegistro;
            $setor_id = $movimentacao->setor_id;
            $data_pedido = $movimentacao->data_pedido;
            $data_chegada = $movimentacao->data_chegada;
            $motivo = $movimentacao->motivo;
            $produtos = $movimentacao->produtos;

            // Se $movimentacao é um objeto
            session()->set('movimentacao', (array)$movimentacao);

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

    public function store()
    {
        $post = $this->request->getPost();

        // Dados da movimentação
        $dataMovimentacao = [
            'id' => !empty($post['id']) ? (int)$post['id'] : null, // Se tiver 'id', será um update, caso contrário será um new
            'fornecedor_id' => (int)$post['fornecedor_id'],
            'setor_id' => (int)$post['setor_id'],
            'data_pedido' => $post['data_pedido'],
            'data_chegada' => $post['data_chegada'],
            'motivo' => $post['motivo'],
            'statusRegistro' => (int)$post['statusRegistro'],
            'tipo' => (int)$post['tipo'],
        ];

        // Verifica se há produtos na sessão
        if (!session()->get('movimentacao')) {
            session()->set('movimentacao', []);
        }

        // Adiciona ou atualiza produtos à sessão
        $produtos = session()->get('movimentacao');
        $produtos[] = $dataMovimentacao;
        session()->set('movimentacao', $produtos);

        // Lógica de inserção ou atualização na base de dados
        if ($this->model->save($dataMovimentacao)) {
            // Mensagem de sucesso dependendo se é inserção ou atualização
            $message = !empty($post['id']) ? 'Movimentação atualizada com sucesso.' : 'Movimentação inserida com sucesso.';
            session()->setFlashdata('msgSuccess', $message);

            // Redireciona para a lista de movimentações
            return redirect()->to(base_url('movimentacao'));
        } else {
            session()->setFlashdata('msgError', 'Erro ao salvar movimentação.');
            return redirect()->to(base_url('movimentacao/form'));
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
    
        // Recuperar produto
        $dadosProduto['aProduto'] = $this->produtoModel->recuperaProduto($id_produto);
    
        // Verificar se há uma sessão de movimentação
        if (!session()->get('movimentacao')) {
            session()->set('movimentacao', ['produtos' => []]); // Inicializa com um array vazio para produtos
        }
    
        // Obter produtos da sessão
        $produtos = session()->get('movimentacao')['produtos'];
        $produtoEncontrado = false;
    
        // Acesse os produtos e verifique se o produto já está na sessão
        foreach ($produtos as &$produto_sessao) {
            if ($produto_sessao['id_produto'] == $id_produto) {
                // Atualizar a quantidade do produto na sessão
                $produto_sessao['quantidade'] += $quantidade;
                $produtoEncontrado = true;
                break; // Saia do loop se o produto foi encontrado e atualizado
            }
        }
    
        // Se o produto não estiver na sessão de movimentação, adicioná-lo
        if (!$produtoEncontrado) {
            // Adiciona o novo produto ao array de produtos
            $produtos[] = array(
                'nome_produto' => $dadosProduto['aProduto']['nome'],
                'id_produto' => $id_produto,
                'quantidade' => $quantidade,
                'valor' => $valor_produto
            );
        }
    
        // Atualiza a sessão mantendo as outras informações da movimentação
        $movimentacaoAtual = session()->get('movimentacao'); // Obtém a movimentação atual
        $movimentacaoAtual['produtos'] = [$produtos]; // Atualiza apenas a parte dos produtos

        session()->set('movimentacao', $movimentacaoAtual); // Atualiza a sessão com todas as informações
    
        // Mensagem de sucesso
        session()->setFlashdata('msgSuccess', 'Produto adicionado à movimentação.');
    
        return redirect()->to(base_url('Movimentacao/form/new/0'));
    }
    
    
    

    public function deleteProdutoMovimentacao()
    {
        $post = $this->request->getPost();
    
        $id_movimentacao = isset($post['id_movimentacao']) ? (int)$post['id_movimentacao'] : ""; 
        $quantidadeRemover = (int)$post['quantidadeRemover'];
        $id_produto = (int)$post['id_produto'];
        $tipo_movimentacao = (int)$post['tipo'];
    
        // Recuperando todos os segmentos da URL
        $segmentos = service('request')->getURI()->getSegments();
        $action = $segmentos[2] ?? null; 
    
        // Verifica se a sessão de movimentação e os produtos estão definidos
        if (session()->has('movimentacao') && isset(session('movimentacao')['produtos']) && $action === 'delete') {
            $produtoEncontrado = false;
    
            // Percorre os produtos na sessão de movimentação
            foreach (session('movimentacao')['produtos'] as $key => &$produto_sessao) { // Usando referência (&)
    
                if ($produto_sessao['id_produto'] == $id_produto) {
                    // Verifica se a quantidade a ser removida é válida
                    if ($quantidadeRemover <= 0) {
                        session()->setFlashdata('msgError', "Quantidade a ser removida deve ser maior que zero.");
                        return redirect()->to('Movimentacao/form/new/0');
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


    
                    session()->setFlashdata('msgSuccess', "Produto excluído da movimentação.");
                    // var_dump(session('movimentacao'), $id_produto, $produto_sessao);
                    // exit; // Mantenha o exit aqui apenas para depuração
                    return redirect()->to('Movimentacao/form/new/0');
                }
            }
    
            // Caso o produto não seja encontrado na sessão, verifique no banco de dados
            if (!$produtoEncontrado) {
                $dadosProduto = $this->produtoModel->recuperaProduto($id_produto);
                $deletaProduto = $this->model->deleteInfoProdutoMovimentacao($id_movimentacao, $dadosProduto, $tipo_movimentacao, $quantidadeRemover);
    
                if ($deletaProduto) {
                    session()->setFlashdata('msgSuccess', "Item deletado da movimentação.");
                    return redirect()->to('Movimentacao/form/update/' . $id_movimentacao);
                }
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
    public function update($action = null)
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

            // Carregando o modelo
            $movimentacaoItemModel = new \App\Models\MovimentacaoItemModel();
            $dadosItensMovimentacao = $movimentacaoItemModel->listaProdutos($id_movimentacao);

            $quantidade_movimentacao = 0;

            foreach ($dadosItensMovimentacao as $item) {
                if ($id_produto == $item['id_prod_mov_itens'] && $id_movimentacao == $item['id_movimentacoes']) {
                    if ($tipo_movimentacao == 1) {
                        $quantidade_movimentacao = $item['quantidade'] + $quantidade;
                    } else if ($tipo_movimentacao == 2) {
                        $quantidade_movimentacao = $item['quantidade'] - $quantidade;
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
            $dadosProduto = $this->model->getProduto($id_produto); // Método para obter dados do produto
            $verificaQuantidadeEstoqueNegativa = true;

            if (!empty($dadosProduto)) {
                if ($dadosProduto[0]['quantidade'] < $quantidade && $tipo_movimentacao == 2) {
                    $verificaQuantidadeEstoqueNegativa = false;
                }
            }

            if ($tipo_movimentacao == 1) {
                $verificaQuantidadeEstoqueNegativa = true;
            }

            if ($action != 'updateProdutoMovimentacao') {
                $atualizandoMovimentacaoEProdutos = $this->model->updateMovimentacao(
                    [
                        "id_movimentacao" => $id_movimentacao
                    ],
                    [
                        "id_fornecedor" => $fornecedor_id,
                        "tipo" => $tipo_movimentacao,
                        "statusRegistro" => $statusRegistro,
                        "id_setor" => $setor_id,
                        "data_pedido" => $data_pedido,
                        "data_chegada" => $data_chegada,
                        "motivo" => $motivo
                    ],
                    [
                        [
                            "id_produtos" => $id_produto,
                            "quantidade" => $quantidade, 
                            "valor" => $valores_produtos
                        ]
                    ],
                    [$produtoMovAtualizado]
                );

                if ($atualizandoMovimentacaoEProdutos) {
                    session()->destroy('movimentacao');
                    session()->destroy('produtos');
                    session()->setFlashdata("msgSuccess", "Movimentação alterada com sucesso.");
                    return redirect()->to("/movimentacao");
                } else {
                    session()->setFlashdata("msgError", "Falha ao tentar alterar a movimentação.");
                }
            } else if ($action == 'updateProdutoMovimentacao') {
                if ($verificaQuantidadeEstoqueNegativa) {
                    $atualizandoInfoProdutoMovimentacao = $this->model->updateInformacoesProdutoMovimentacao(
                        [
                            "id_movimentacao" => $id_movimentacao
                        ],
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
                        session()->set('produto_mov_atualizado', true);
                        session()->destroy('movimentacao');
                        session()->destroy('produtos');
                        session()->setFlashdata("msgSuccess", "Movimentação alterada com sucesso.");
                        return redirect()->to("/movimentacao/form/update/" . $id_movimentacao);
                    }
                } else {
                    session()->setFlashdata("msgError", "Quantidade da movimentação de saída maior que a do produto em estoque.");
                    return redirect()->to("/movimentacao/form/update/" . $id_movimentacao);
                }
            } else {
                session()->setFlashdata("msgError", "Falha ao tentar alterar a movimentação.");
                return redirect()->to("/movimentacao");
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
        // Obter os dados do post
        $request = \Config\Services::request();
        $post = $request->getPost();

        // Dados do produto
        $id_produto = $post['id_produto'] ?? '';
        $quantidades = $post['quantidade'] ?? '';
        $valor_produto = $post['valor'] ?? '';

        // Obter dados de movimentação
        $dadosMovimentacao = $this->model->lista('id');

        // Recuperar dados do produto
        $ItemModel = new \App\Models\ProdutoModel(); // Assumindo que o modelo Produto está em App\Models
        $dadosProduto = $ItemModel->recuperaProduto($id_produto);

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
            $quantidade_produto = (int)$quantidadeProduto - (int)$quantidades;
        } else if ($tipo_movimentacao === 2) {
            $quantidade_produto = (int)$quantidadeProduto + (int)$quantidades;
        }

        // Excluir a movimentação
        if ($this->model->delete($post['id'])) {
            // Atualizar informações do produto, se necessário
            if (!empty($id_produto) || $quantidade_produto) {
                $this->model->updateInformacoesProdutoMovimentacao(
                    [
                        'id_movimentacao' => $post['id']
                    ],
                    [
                        [
                            'id_produtos' => $id_produto,
                            'valor' => $valor_produto
                        ]
                    ],
                    [
                        'acaoProduto' => 'update'
                    ],
                    $quantidade_produto
                );
            }

            // Mensagem de sucesso
            session()->setFlashdata('msgSuccess', 'Movimentacao excluída com sucesso.');
        } else {
            // Mensagem de erro
            session()->setFlashdata('msgError', 'Falha ao tentar excluir a Movimentacao.');
        }

        return redirect()->to('Movimentacao'); // Redireciona para a página de movimentação
    }


    public function getProdutoComboBox()
    {

        $termo = $this->request->getVar('termo'); 

        $dados = $this->model->getProdutoCombobox($termo); 

        echo json_encode($dados);

    }

}