<?php

namespace App\Controllers;

use App\Models\MovimentacaoModel;
use App\Models\MovimentacaoItemModel;
use App\Models\SetorModel;
use App\Models\FornecedorModel;
use App\Models\ProdutoModel;
use CodeIgniter\Controller;

class Movimentacao extends BaseController
{
    protected $movimentacaoModel;
    protected $movimentacaoItemModel;
    protected $setorModel;
    protected $fornecedorModel;
    protected $produtoModel;

    public function __construct()
    {
        // Injeção de dependência dos modelos
        $this->movimentacaoModel = new MovimentacaoModel();
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
        $data['movimentacoes'] = $this->movimentacaoModel->getLista();
        return view('restrita/listaMovimentacao', $data);
    }

    public function form($action, $id = null)
    {
        $data['action'] = $action;
        $data['data'] = null;
        $data['errors'] = [];

        if ($action != "new" && $id !== null) {
            $data['itemMovimentacao'] = $this->movimentacaoItemModel->listaProdutos($id);
            $data['data'] = $this->movimentacaoModel->find($id);
        }

        $data['aSetor'] = $this->setorModel->findAll();
        $data['aFornecedor'] = $this->fornecedorModel->findAll();
        $data['aProduto'] = $this->produtoModel->findAll();

        return view('restrita/formMovimentacao', $data);
    }

    public function store()
    {
        $post = $this->request->getPost();

        // Dados da movimentação
        $dataMovimentacao = [
            'id' => !empty($post['id']) ? (int)$post['id'] : null, // Se tiver 'id', será um update, caso contrário será um insert
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
        if ($this->movimentacaoModel->save($dataMovimentacao)) {
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


    public function insertProdutoMovimentacao()
    {
        $post = $this->request->getPost();

        // Verifica se a movimentação está na sessão
        if (!session()->get('movimentacao')) {
            session()->set('movimentacao', []);
        }

        $produtos = session()->get('movimentacao');
        $idProduto = (int)$post['id_produto'];

        $produto = $this->produtoModel->find($idProduto);
        $quantidade = (int)$post['quantidade'];

        // Adiciona ou atualiza o produto na sessão
        foreach ($produtos as &$item) {
            if ($item['id_produto'] === $idProduto) {
                $item['quantidade'] += $quantidade;
                session()->setFlashdata('msgSuccess', 'Produto atualizado na movimentação.');
                return redirect()->to(base_url('movimentacao/form'));
            }
        }

        $produtos[] = [
            'id_produto' => $idProduto,
            'quantidade' => $quantidade,
            'nome_produto' => $produto['nome'],
            'valor' => (float)$post['valor'],
        ];

        session()->set('movimentacao', $produtos);
        session()->setFlashdata('msgSuccess', 'Produto adicionado à movimentação.');

        return redirect()->to(base_url('movimentacao/form'));
    }

    public function deleteProdutoMovimentacao($idProduto)
    {
        if (session()->get('movimentacao')) {
            $produtos = session()->get('movimentacao');

            foreach ($produtos as $key => $produto) {
                if ($produto['id_produto'] == $idProduto) {
                    unset($produtos[$key]);
                    session()->set('movimentacao', $produtos);
                    session()->setFlashdata('msgSuccess', 'Produto removido da movimentação.');
                    break;
                }
            }
        }

        return redirect()->to(base_url('movimentacao/form'));
    }



//     public function delete($id)
//     {
//         if ($this->movimentacaoModel->delete($id)) {
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

            $acaoProduto = $found ? 'update' : 'insert';

            // Validação de quantidade em estoque
            $dadosProduto = $this->movimentacaoModel->getProduto($id_produto); // Método para obter dados do produto
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
                $atualizandoMovimentacaoEProdutos = $this->movimentacaoModel->updateMovimentacao(
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
                    $atualizandoInfoProdutoMovimentacao = $this->movimentacaoModel->updateInformacoesProdutoMovimentacao(
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
        $dadosMovimentacao = $this->movimentacaoModel->lista('id');

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
        if ($this->movimentacaoModel->delete($post['id'])) {
            // Atualizar informações do produto, se necessário
            if (!empty($id_produto) || $quantidade_produto) {
                $this->movimentacaoModel->updateInformacoesProdutoMovimentacao(
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

        $dados = $this->movimentacaoModel->getProdutoCombobox($this->getOutrosParametros(2)); 

        echo json_encode($dados);

    }

}