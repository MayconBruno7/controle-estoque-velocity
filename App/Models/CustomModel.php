<?php 

namespace App\Models;

use CodeIgniter\Model;

class CustomModel extends Model
{
    protected $currentUser;

    public function __construct()
    {
        parent::__construct();
        $this->currentUser = session()->get('current_user'); // Altere para a chave correta do usuário
    
        // Verificação de depuração
        if ($this->currentUser === null) {
            throw new \Exception('Current user is not set in the session.');
        }
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
