<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Session\Session;

class MovimentacaoModel extends CustomModel
{
    protected $table = 'movimentacao'; // Define a tabela do banco de dados
    protected $primaryKey = 'id'; // Define a chave primária
    protected $allowedFields = ['setor_id', 'fornecedor_id', 'tipo', 'motivo', 'data_pedido', 'statusRegistro']; // Campos permitidos para inserção/atualização
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
        // Utilizando a tabela base diretamente com o nome armazenado em $this->table
        $builder = $this->db->table($this->table . ' m')
            ->select('
                m.id AS id_movimentacao, 
                f.nome AS nome_fornecedor, 
                m.tipo AS tipo_movimentacao, 
                m.data_pedido, 
                m.data_chegada')
            ->join('fornecedor f', 'f.id = m.id_fornecedor', 'left') // Certifique-se de que `fornecedor_id` é a chave correta
            ->join('movimentacao_item mi', 'mi.id_movimentacoes = m.id', 'left')
            ->join('produto p', 'p.id = mi.id_produtos', 'left');

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
    public function insertMovimentacao(array $movimentacao, array $aProdutos): bool
    {
        $ultimoRegistro = $this->insert($movimentacao);

        if ($ultimoRegistro) {
            if (!empty($aProdutos[0]['id_produtos'])) {
                foreach ($aProdutos as $item) {
                    $item['id_movimentacoes'] = $ultimoRegistro;
                    $this->db->table('movimentacao_item')->insert($item);
                }
            }
            return true;
        }
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
    public function updateMovimentacao(int $idMovimentacao, array $movimentacao, array $aProdutos, bool $prod_info_mov_atualizado): bool
    {
        if ($idMovimentacao) {
            $this->update($idMovimentacao, $movimentacao);
            if ($prod_info_mov_atualizado) {
                unset($_SESSION['produto_mov_atualizado']);
                return true;
            }
        }
        return false;
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

        if ($id_movimentacao && !empty($id_produto)) {
            foreach ($aProdutos as $item) {
                if ($acao['acaoProduto'] == 'update') {
                    $item['quantidade'] = $quantidade_movimentacao;
                    $this->db->table('movimentacao_item')->update($item, [
                        'id_movimentacoes' => $id_movimentacao,
                        'id_produtos' => $id_produto
                    ]);
                    return true;
                } elseif ($acao['acaoProduto'] == 'insert') {
                    $item['id_movimentacoes'] = $id_movimentacao;
                    $item['quantidade'] = $quantidade_movimentacao;
                    $this->db->table('movimentacao_item')->insert($item);
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
                $this->db->table('movimentacao_item')->update(['quantidade' => $novaQuantidadeMovimentacao], [
                    'id_movimentacoes' => $id_movimentacao,
                    'id_produtos' => $item_movimentacao['id_produtos']
                ]);

                // Remove produtos com quantidade igual a zero
                $this->db->table('movimentacao_item')->delete([
                    'id_movimentacoes' => $id_movimentacao,
                    'id_produtos' => $item_movimentacao['id_produtos'],
                    'quantidade' => 0
                ]);

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
