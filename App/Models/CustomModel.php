<?php 

namespace App\Models;

use CodeIgniter\Model;

class CustomModel extends Model
{
    protected $currentUser;

    public function __construct()
    {
        
        parent::__construct();
        $this->currentUser = session()->has('current_user') ? session()->get('current_user') : null;
  
    }

    public function inserirMovimentacao($row = null, bool $returnID = true)
    {
        try {
            $currentUser = $this->currentUser;
            $this->db->query("SET @current_user = '{$currentUser}'");
            
            // Executa a inserção e verifica se foi bem-sucedida
            if ($this->db->table('movimentacao')->insert($row, $returnID) === false) {
                throw new \Exception($this->db->error()['message']);
            }
            
            return $this->db->insertID(); // Retorna o ID inserido, caso o $returnID seja verdadeiro
        } catch (\Exception $e) {
            log_message('error', 'Erro ao inserir movimentação: ' . $e->getMessage());
            return false; // Ou, você pode retornar uma resposta mais descritiva
        }
    }


    public function insertMovimentacaoItem($row = null)
    {
        // Define a variável de usuário atual na sessão
        $currentUser = $this->currentUser;

        // Define a variável no MySQL
        $this->db->query("SET @current_user = '{$currentUser}'");

        // Chama o método parent para inserir os dados
        return $this->db->table('movimentacao_item')->insert($row);
    }

    // Atualiza a quantidade de itens na movimentação
    public function updateMovimentacaoQuantidade($id_movimentacao, $id_produto, $novaQuantidade)
    {
        // Define a variável de usuário atual no MySQL
        $this->db->query("SET @current_user = '{$this->currentUser}'");

        // Executa a atualização da quantidade
        return $this->db->table('movimentacao_item')->update(
            ['quantidade' => $novaQuantidade],
            [
                'id_movimentacoes' => $id_movimentacao,
                'id_produtos' => $id_produto
            ]
        );
    }

    // Deleta itens da movimentação com quantidade igual a zero
    public function deleteMovimentacaoItemComQuantidadeZero($id_movimentacao, $id_produto)
    {
        // Define a variável de usuário atual no MySQL
        $this->db->query("SET @current_user = '{$this->currentUser}'");

        // Executa a exclusão do item com quantidade zero
        return $this->db->table('movimentacao_item')->delete([
            'id_movimentacoes' => $id_movimentacao,
            'id_produtos' => $id_produto,
            'quantidade' => 0
        ]);
    }
    
    public function insert($row = null, bool $returnID = true) // Alterado aqui
    {
        // Define a variável de usuário atual na sessão
        $currentUser = $this->currentUser;

        // Define a variável no MySQL
        $this->db->query("SET @current_user = '{$currentUser}'");

        // Chama o método parent para inserir os dados
        return parent::insert($row, $returnID);
    }

    public function update($id = null, $data = null): bool
    {
        // Define a variável de usuário atual na sessão
        $currentUser = $this->currentUser;

        // Execute a atualização no banco de dados
        $this->db->query("SET @current_user = '{$currentUser}'"); // Define a variável no MySQL

        // Chama o método parent para atualizar os dados
        return parent::update($id, $data);
    }
    
    public function delete($id = null, bool $purge = false)
    {
        // Define a variável de usuário atual na sessão
        $currentUser = $this->currentUser;

        // Define a variável no MySQL
        $this->db->query("SET @current_user = '{$currentUser}'"); // Define a variável no MySQL

        // Chama o método parent para deletar os dados
        return parent::delete($id, $purge);
    }
}
