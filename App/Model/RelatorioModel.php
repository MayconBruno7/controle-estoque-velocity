<?php

use App\Library\ModelMain;
use App\Library\Session;

Class RelatorioModel extends ModelMain
{
    public $table = "movimentacao_item";

    public function RelatorioDia($dia)
    {
        $rsc = $this->db->dbSelect("SELECT mi.id_movimentacoes, m.data_pedido, p.descricao, mi.quantidade, mi.valor, m.tipo
            FROM movimentacao m
            INNER JOIN movimentacao_item mi ON m.id = mi.id_movimentacoes
            INNER JOIN produto p ON mi.id_produtos = p.id
            WHERE DATE(m.data_pedido) = ?
            ORDER BY m.data_pedido ASC", [$dia]);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function RelatorioSemana($dataInicio, $dataFinal)
    {
        $rsc = $this->db->dbSelect("SELECT mi.id_movimentacoes, m.data_pedido, p.descricao, mi.quantidade, mi.valor, m.tipo
            FROM movimentacao m
            INNER JOIN movimentacao_item mi ON m.id = mi.id_movimentacoes
            INNER JOIN produto p ON mi.id_produtos = p.id
            WHERE m.data_pedido BETWEEN ? AND ?
            ORDER BY m.data_pedido ASC", [$dataInicio, $dataFinal]);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function RelatorioMes($mes)
    {
        $rsc = $this->db->dbSelect("SELECT mi.id_movimentacoes, m.data_pedido, p.descricao, mi.quantidade, mi.valor, m.tipo
            FROM movimentacao m
            INNER JOIN movimentacao_item mi ON m.id = mi.id_movimentacoes
            INNER JOIN produto p ON mi.id_produtos = p.id
            WHERE DATE_FORMAT(m.data_pedido, '%Y-%m') = ?
            ORDER BY m.data_pedido ASC", [$mes]);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function RelatorioAno($ano)
    {
        $rsc = $this->db->dbSelect("SELECT mi.id_movimentacoes, m.data_pedido, p.descricao, mi.quantidade, mi.valor, m.tipo
            FROM movimentacao m
            INNER JOIN movimentacao_item mi ON m.id = mi.id_movimentacoes
            INNER JOIN produto p ON mi.id_produtos = p.id
            WHERE DATE_FORMAT(m.data_pedido, '%Y') = ?
            ORDER BY m.data_pedido ASC", [$ano]);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
}

