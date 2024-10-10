<?php

namespace App\Models;

use CodeIgniter\Model;

class FuncionarioModel extends Model
{
    protected $table = 'funcionario'; // Define a tabela do banco de dados
    protected $primaryKey = 'id'; // Define a chave primária
    // protected $returnType = 'array'; // Define o tipo de retorno
    // protected $useSoftDeletes = false; // Defina como true se você usar Soft Deletes

    protected $allowedFields = [
        'nome',
        'cpf',
        'setor',
        'salario',
        'statusRegistro'
    ]; // Campos permitidos para inserção e atualização

    protected $validationRules = [
        'nome' => [
            'label' => 'Nome',
            'rules' => 'required|min_length[3]|max_length[80]'
        ],
        'cpf' => [
            'label' => 'CPF',
            'rules' => 'required|min_length[14]'
        ],
        'salario' => [
            'label' => 'Salário',
            'rules' => 'required|decimal'
        ],
        'statusRegistro' => [
            'label' => 'Status',
            'rules' => 'required|integer'
        ]
    ];

    // protected $validationMessages = [
    //     // Personalize as mensagens de validação aqui, se necessário
    // ];

    // protected $skipValidation = false; // Mude para true se não quiser validação em determinadas situações

    /**
     * Lista todos os funcionários, com base no nível de usuário
     *
     * @param string $orderBy
     * @return array
     */
    public function getLista($orderBy = 'id')
    {
        // Consulta com base no nível do usuário
        if (session()->get('usuarioNivel') == 1) {
            return $this->select('funcionario.*, setor.nome AS nome_do_setor')
                ->join('setor', 'funcionario.setor = setor.id', 'left')
                ->orderBy($orderBy)
                ->findAll();
        } else {
            return $this->where('statusRegistro', 1)
                ->orderBy($orderBy)
                ->findAll();
        }
    }

    /**
     * Recupera um funcionário específico pelo ID
     *
     * @param int $id
     * @return array|null
     */
    public function recuperaFuncionario($id)
    {
        if (session()->get('usuarioNivel') == 1) {
            return $this->select('funcionario.*, setor.nome AS nome_do_setor')
                ->join('setor', 'funcionario.setor = setor.id', 'left')
                ->where('funcionario.id', $id)
                ->first();
        } else {
            return $this->where('statusRegistro', 1)
                ->where('funcionario.id', $id)
                ->first();
        }
    }
}
