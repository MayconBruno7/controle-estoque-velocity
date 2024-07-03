<?php

use App\Library\ModelMain;
use App\Library\Session;

Class RelatorioModel extends ModelMain
{
    public $table = "movimentacao_item";

    // Movimentacoes
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

    // fornecedor
    public function RelatorioDiaItemFornecedor($dataInicio, $id_fornecedor)
    {
        $rsc = $this->db->dbSelect("SELECT 
            p.id AS id_produto,
            p.nome AS nome_produto,
            p.descricao AS descricao,
            f.id AS id_fornecedor,
            f.nome AS nome_fornecedor,
            m.data_pedido,
            m.tipo,
            mi.quantidade,
            mi.valor
        FROM 
            fornecedor f
        JOIN 
            movimentacao m ON f.id = m.id_fornecedor
        JOIN 
            movimentacao_item mi ON m.id = mi.id_movimentacoes
        JOIN 
            produto p ON mi.id_produtos = p.id
        WHERE 
            DATE(m.data_pedido) = ?
            AND m.id_fornecedor = ?
            ORDER BY m.data_pedido ASC", [$dataInicio, $id_fornecedor]);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function RelatorioSemanaItemFornecedor($dataInicio, $dataFinal, $id_fornecedor)
    {
        $rsc = $this->db->dbSelect("SELECT mi.id_movimentacoes, m.data_pedido, p.descricao, mi.quantidade, mi.valor, m.tipo
            FROM movimentacao m
            INNER JOIN movimentacao_item mi ON m.id = mi.id_movimentacoes
            INNER JOIN produto p ON mi.id_produtos = p.id
            WHERE m.data_pedido BETWEEN ? AND ?
            AND m.id_fornecedor = ?
            ORDER BY m.data_pedido ASC", 
            [$dataInicio, $dataFinal, $id_fornecedor]
        );

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function RelatorioMesItemFornecedor($dataInicio, $id_fornecedor)
    {
        $rsc = $this->db->dbSelect("SELECT mi.id_movimentacoes, m.data_pedido, p.descricao, mi.quantidade, mi.valor, m.tipo
            FROM movimentacao m
            INNER JOIN movimentacao_item mi ON m.id = mi.id_movimentacoes
            INNER JOIN produto p ON mi.id_produtos = p.id
            WHERE DATE_FORMAT(m.data_pedido, '%Y-%m') = ?
            AND m.id_fornecedor = ?
            ORDER BY m.data_pedido ASC", 
            [$dataInicio, $id_fornecedor]
        );

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function RelatorioAnoItemFornecedor($ano, $id_fornecedor)
    {
        $rsc = $this->db->dbSelect("SELECT mi.id_movimentacoes, m.data_pedido, p.descricao, mi.quantidade, mi.valor, m.tipo
            FROM movimentacao m
            INNER JOIN movimentacao_item mi ON m.id = mi.id_movimentacoes
            INNER JOIN produto p ON mi.id_produtos = p.id
            INNER JOIN fornecedor f ON m.id_fornecedor = f.id
            WHERE DATE_FORMAT(m.data_pedido, '%Y') = ?
            AND m.id_fornecedor = ?
            ORDER BY m.data_pedido ASC", 
            [$ano, $id_fornecedor]);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
}

