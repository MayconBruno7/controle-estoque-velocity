<?php

use App\Library\ModelMain;
use App\Library\Session;

Class FuncionarioModel extends ModelMain
{
    public $table = "funcionarios";

    public $validationRules = [
        'nome' => [
            'label' => 'nome',
            'rules' => 'required|min:3|max:80'
        ],
        'cpf' => [
            'label' => 'cpf',
            'rules' => 'required|min:14'
        ], 
        'salario' => [
            'label' => 'salario',
            'rules' => 'required|decimal'
        ],
        'statusRegistro' => [
            'label' => 'Status',
            'rules' => 'required|int'
        ]
    ];

    /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function lista($orderBy = 'id')
    {
        if (Session::get('usuarioNivel') == 1) {
            // $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
            $rsc = $this->db->dbSelect("SELECT funcionarios.*, setor.nome AS nome_do_setor FROM {$this->table} LEFT JOIN setor ON funcionarios.setor = setor.id ORDER BY {$orderBy}");
            
        } else {
            $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE statusRegistro = 1 ORDER BY {$orderBy}");
            
        }

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
}