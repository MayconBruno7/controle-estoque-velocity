<?php

use App\Library\ModelMain;
use App\Library\Session;

Class RelatorioModel extends ModelMain
{
    public $table = "movimentacao_item";

    // Movimentacoes
    public function RelatorioDia($dia = null)
    {
        // Se $dia não for fornecido, use a data atual
        if ($dia === null) {
            $dia = date('Y-m-d');
        }

        $rsc = $this->db->dbSelect(
            "SELECT 
                SUM(mi.quantidade) AS quantidadeDia,
                m.data_pedido,
                mi.id_movimentacoes,
                p.descricao,
                mi.quantidade,
                mi.valor,
                m.tipo
            FROM movimentacao m
            INNER JOIN movimentacao_item mi ON m.id = mi.id_movimentacoes
            INNER JOIN produto p ON mi.id_produtos = p.id
            WHERE DATE(m.data_pedido) = ?
            GROUP BY m.id
            ORDER BY m.data_pedido ASC
        ", [$dia]);


        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function RelatorioSemana($dataInicio = null, $dataFinal = null)
    {
        // Se $dataInicio ou $dataFinal não forem fornecidos, use a semana atual
        if ($dataInicio === null || $dataFinal === null) {
            // Defina a data atual
            $hoje = new DateTime();
            
            // Encontre o início da semana (segunda-feira)
            $inicioSemana = clone $hoje;
            $inicioSemana->modify('monday this week');
            
            // Encontre o fim da semana (domingo)
            $fimSemana = clone $hoje;
            $fimSemana->modify('sunday this week');

            // Formate as datas no formato desejado (d-m-Y)
            $dataInicio = $inicioSemana->format('Y-m-d');
            $dataFinal = $fimSemana->format('Y-m-d');
        }

        $rsc = $this->db->dbSelect(
            "SELECT 
                SUM(mi.quantidade) AS quantidadeSemana,
                mi.id_movimentacoes, 
                m.data_pedido, 
                p.descricao, 
                mi.quantidade, 
                mi.valor, 
                m.tipo  
            FROM movimentacao m
            INNER JOIN movimentacao_item mi ON m.id = mi.id_movimentacoes
            INNER JOIN produto p ON mi.id_produtos = p.id
            WHERE m.data_pedido BETWEEN ? AND ?
            GROUP BY m.id
            ORDER BY m.data_pedido ASC

        ", [$dataInicio, $dataFinal]);


        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function RelatorioMes($mes = null)
    {
        // Se $mes não for fornecido, use o mês atual
        if ($mes === null) {
            // Defina a data atual e formate-a para 'Y-m'
            $mes = date('Y-m');
        }

        $rsc = $this->db->dbSelect(
            "SELECT 
                SUM(mi.quantidade) AS quantidadeMes,
                mi.id_movimentacoes, 
                m.data_pedido, 
                p.descricao, 
                mi.quantidade, 
                mi.valor, 
                m.tipo
            FROM movimentacao m
            INNER JOIN movimentacao_item mi ON m.id = mi.id_movimentacoes
            INNER JOIN produto p ON mi.id_produtos = p.id
            WHERE DATE_FORMAT(m.data_pedido, '%Y-%m') = ?
            GROUP BY m.id
            ORDER BY m.data_pedido ASC", [$mes]);

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }


    public function RelatorioAno($ano = null)
    {
        // Se $ano não for fornecido, use o ano atual
        if ($ano === null) {
            // Defina a data atual e formate-a para 'Y'
            $ano = date('Y');
        }
    
        $rsc = $this->db->dbSelect(
            "SELECT 
                SUM(mi.quantidade) AS quantidadeAno,
                mi.id_movimentacoes, 
                m.data_pedido, 
                p.descricao, 
                mi.quantidade, 
                mi.valor, 
                m.tipo
            FROM movimentacao m
            INNER JOIN movimentacao_item mi ON m.id = mi.id_movimentacoes
            INNER JOIN produto p ON mi.id_produtos = p.id
            WHERE DATE_FORMAT(m.data_pedido, '%Y') = ?
             GROUP BY m.id
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

