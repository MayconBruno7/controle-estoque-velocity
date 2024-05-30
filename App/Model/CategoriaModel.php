<?php

use App\Library\ModelMain;

Class CategoriaModel extends ModelMain
{
    public $table = "categoria";

    public $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required|min:3|max:50'
        ],
        'statusRegistro' => [
            'label' => 'Status',
            'rules' => 'required|int'
        ]
    ];
}