<?php

use App\Library\ModelMain;
use App\Library\Session;

Class OrdemServicoModel extends ModelMain
{
    public $table = "";

    // public $validationRules = [
    //     'nome' => [
    //         'label' => 'nome',
    //         'rules' => 'required|min:3|max:50'
    //     ],
    //     'statusRegistro' => [
    //         'label' => 'Status',
    //         'rules' => 'required|int'
    //     ]
    // ];

    /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function lista($orderBy = 'id')
    {
        if (Session::get('usuarioNivel') == 1) {
            $rsc = $this->db->dbSelect("SELECT
                os.id AS ordem_id,
                os.cliente_nome,
                os.telefone_cliente,
                os.modelo_dispositivo,
                os.imei_dispositivo,
                os.descricao_servico,
                os.tipo_servico,
                os.problema_reportado,
                os.data_abertura,
                os.data_fechamento,
                os.status,
                os.observacoes,
                p.id AS peca_id,
                p.nome_peca,
                p.quantidade,
                p.valor_peca,
                p.descricao_peca
            FROM
                ordens_servico os
            INNER JOIN
                ordens_servico_pecas osp ON os.id = osp.id_ordem_servico
            INNER JOIN
                pecas p ON osp.id_peca = p.id;
            ");
            
        } else {
            $rsc = $this->db->dbSelect("SELECT * FROM logs ORDER BY {$orderBy}");
            
        }

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
    
}