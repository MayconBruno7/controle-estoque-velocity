<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Session\Session;

class MovimentacaoModel extends CustomModel
{
    protected $table = 'movimentacao'; // Define a tabela do banco de dados
    protected $primaryKey = 'id'; // Define a chave primária
    protected $allowedFields = ['id_fornecedor', 'tipo', 'statusRegistro', 'id_setor', 'data_pedido', 'data_chegada', 'motivo'];
    protected $validationRules = [
        'setor_id' => 'required|integer',
        'fornecedor_id' => 'required|integer',
        'tipo' => 'required|integer',
        'motivo' => 'required',
        'data_pedido' => 'required|valid_date',
        'statusRegistro' => 'required|integer',
    ];

    /**
     * Lista movimentações
     *
     * @param string $orderBy
     * @return array
     */
    public function getLista($orderBy = 'm.id'): array
    {

        // Utilizando o Query Builder do CodeIgniter 4
        $builder = $this->db->table($this->table . ' m')
        ->select('
            m.id AS id_movimentacao,
            f.nome AS nome_fornecedor,
            m.tipo AS tipo_movimentacao,
            m.data_pedido,
            m.data_chegada')
        // ->distinct() // Adiciona DISTINCT à consulta
        ->join('fornecedor f', 'f.id = m.id_fornecedor', 'left') // Join com a tabela fornecedor
        ->join('movimentacao_item mi', 'mi.id_movimentacoes = m.id', 'left') // Join com a tabela movimentacao_item
        ->join('produto p', 'p.id = mi.id_produtos', 'left'); // Join com a tabela produto

        // Filtra resultados com base no nível do usuário
        if (session()->get('usuarioNivel') != 1) {
            $builder->where('m.statusRegistro', 1);
        }

        // Retorna os resultados
        return $builder->orderBy($orderBy)->get()->getResultArray();
    }

    /**
     * Retorna o ID da última movimentação
     *
     * @return array
     */
    public function idUltimaMovimentacao(): array
    {
        return $this->selectMax('id', 'ultimo_id')->findAll();
    }

    /**
     * Insere uma nova movimentação
     *
     * @param array $movimentacao
     * @param array $aProdutos
     * @return bool
     */
    public function insertMovimentacao($movimentacao, $aProdutos)
    {
        // Inserir movimentação na tabela 'movimentacao' usando o método insertMovimentacao do CustomModel
        $this->inserirMovimentacao($movimentacao);

        // Obter o ID da última inserção
        $ultimoRegistro = $this->insertID(); // Chama insertID() do CustomModel
        
        if ($ultimoRegistro > 0) {
            // Verifica se há produtos a serem inseridos
            if (!empty($aProdutos) && isset($aProdutos[0]['id_produtos']) && $aProdutos[0]['id_produtos'] != '') {
                foreach ($aProdutos as $item) {
                    // Adiciona o ID da movimentação ao item do produto
                    $item['id_movimentacoes'] = $ultimoRegistro;
                    
                    // Inserir o item de produto na tabela movimentacao_item usando o método insertMovimentacaoItem do CustomModel
                    $this->insertMovimentacaoItem($item);
                }
            }
            
            return true; // Tudo ocorreu corretamente
        }
        
        // Se a inserção da movimentação falhar, retornar falso
        return false;
    }

    /**
     * Atualiza uma movimentação existente
     *
     * @param int $idMovimentacao
     * @param array $movimentacao
     * @param array $aProdutos
     * @param bool $prod_info_mov_atualizado
     * @return bool
     */
    public function updateMovimentacao(int $idMovimentacao, $movimentacao, $prod_info_mov_atualizado): bool
    {

    
        // Verifica se a movimentação é válida
        if ($idMovimentacao) {
            // Debug: Verifica se $movimentacao contém dados
            if (empty($movimentacao)) {
                throw new \Exception("O array 'movimentacao' está vazio."); // Levanta uma exceção se estiver vazio
            }
    
            // Atualiza a movimentação na tabela
            $updated = $this->update($idMovimentacao, $movimentacao); // Chamada correta do método update

            // Debug: Verifica se a atualização foi bem-sucedida
            if (!$updated) {
                throw new \Exception("Falha na atualização da movimentação."); // Mensagem de erro se a atualização falhar
            } else {
                return true;
            }
    
            // Se as informações do produto foram atualizadas, faça a limpeza da sessão
            if ($prod_info_mov_atualizado) {
                if (isset($_SESSION['produto_mov_atualizado'])) { // Verifica se a variável existe
                    unset($_SESSION['produto_mov_atualizado']);
                }
                // var_dump($idMovimentacao);
                // var_dump($movimentacao);
                // var_dump($updated);
                // exit;
                return true; // Retorna verdadeiro se tudo ocorreu bem
            }
        }
        return false; // Retorna falso se algo deu errado
    }
    


    /**
     * Atualiza informações do produto na movimentação
     *
     * @param int $id_movimentacao
     * @param array $aProdutos
     * @param array $acao
     * @param int $quantidade_produto
     * @param int|null $quantidade_movimentacao
     * @return bool
     */
    public function updateInformacoesProdutoMovimentacao(int $id_movimentacao, array $aProdutos, array $acao, int $quantidade_produto, int $quantidade_movimentacao = null): bool
    {
        $id_produto = $aProdutos[0]['id_produtos'] ?? '';

        // var_dump($id_movimentacao, $aProdutos, $acao, $quantidade_produto, $quantidade_movimentacao);
        // exit;
      
        if ($id_movimentacao && !empty($id_produto)) {
            foreach ($aProdutos as $item) {
                
                if ($acao['acaoProduto'] == 'update') {
                    $item['quantidade'] = $quantidade_movimentacao;

                    $produto_atualizado = $this->updateMovimentacaoQuantidade(
                        $id_movimentacao,
                        $id_produto,
                        $item['quantidade']
                    );

                    if($produto_atualizado){
                        session()->set('produto_mov_atualizado');
                        return true;
                    } else {
                        return false;
                    }

                } elseif ($acao['acaoProduto'] == 'new') {
                    $item['id_movimentacoes'] = $id_movimentacao;
                    $item['quantidade'] = $quantidade_movimentacao;
                    // Inserir o item de produto na tabela movimentacao_item usando o método insertMovimentacaoItem do CustomModel
                    $produto_inserido = $this->insertMovimentacaoItem($item);

                    if($produto_inserido){
                        session()->set('produto_mov_atualizado');
                        return true;
                    } else {
                        return false;
                    }
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Remove informações do produto na movimentação
     *
     * @param int $id_movimentacao
     * @param array $aProdutos
     * @param int $tipo_movimentacao
     * @param int $quantidadeRemover
     * @return bool
     */
    public function deleteInfoProdutoMovimentacao(int $id_movimentacao, array $aProdutos, int $tipo_movimentacao, int $quantidadeRemover)
    {
        $item_movimentacao = $this->db->table('movimentacao_item')->where([
            'id_movimentacoes' => $id_movimentacao,
            'id_produtos' => $aProdutos['id']
        ])->get()->getRowArray();

        if ($item_movimentacao) {
            $quantidadeAtual = $item_movimentacao['quantidade'];

            if ($quantidadeRemover <= $quantidadeAtual) {
                $novaQuantidadeMovimentacao = $quantidadeAtual - $quantidadeRemover;

                // Atualiza a quantidade usando o CustomModel
                $this->updateMovimentacaoQuantidade($id_movimentacao, $item_movimentacao['id_produtos'], $novaQuantidadeMovimentacao);

                // Remove produtos com quantidade igual a zero usando o CustomModel
                $this->deleteMovimentacaoItemComQuantidadeZero($id_movimentacao, $item_movimentacao['id_produtos']);

                return true;
            } else {
                session()->set('msgError', 'Quantidade maior que a da movimentação.');
                return false;
            }
        } else {
            session()->set('msgError', 'Item não encontrado na movimentação.');
            return false;
        }
    }

    /**
     * Obtém produtos para o combobox
     *
     * @param string $termo
     * @return array
     */
    public function getProdutoCombobox(string $termo)
    {
        if (!empty($termo)) {
            $produtos = $this->db->table('produto')->where('statusRegistro', 1)
                ->like('nome', $termo)
                ->get()->getResultArray();

            return array_map(function ($produto) {
                return [
                    'id' => $produto['id'],
                    'nome' => $produto['nome']
                ];
            }, $produtos);
        }
        return [];
    }
}
