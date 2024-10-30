<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartamentoModel extends CustomModel
{
    protected $table = 'departamento';
    protected $primaryKey = 'id';

    protected $allowedFields = ['descricao', 'statusRegistro'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    /*
    protected $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required|min_length[3]|max_length[50]'
        ],
        "statusRegistro", [
            'label' => 'Status',
            'rules' => 'required|integer'
        ]
    ];
    */
}