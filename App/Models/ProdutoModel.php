<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table = 'produto';
    protected $primaryKey = 'id';

    protected $allowedFields = ['descricao', 'detalhamento', 'departamento_id'
                                , 'precoVenda', 'statusRegistro', 'largura'
                                , 'altura', 'profundidade', 'pesoBruto', 'totaCurtida'];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    protected $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required|min_length[3]|max_length[100]'
        ],
        'detalhamento' => [
            'label' => 'Detalhamento',
            'rules' => 'required|min_length[5]'
        ],
        'departamento_id' => [
            'label' => 'Departamento',
            'rules' => 'required|integer'
        ],
        'precoVenda' => [
            'label' => 'Preço de Venda',
            'rules' => 'required|decimal'
        ],
        "statusRegistro", [
            'label' => 'Status',
            'rules' => 'required|integer'
        ],
        'largura' => [
            'label' => 'Largura',
            'rules' => 'required|integer'
        ],
        'altura' => [
            'label' => 'Altura',
            'rules' => 'required|integer'
        ],
        'profundidade' => [
            'label' => 'Profundidade',
            'rules' => 'required|integer'
        ],
        'pesoBruto' => [
            'label' => 'Peso Bruto',
            'rules' => 'required|decimal'
        ]
    ];
}