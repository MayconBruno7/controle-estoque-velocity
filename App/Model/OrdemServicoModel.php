<?php

use App\Library\ModelMain;
use App\Library\Session;

Class OrdemServicoModel extends ModelMain
{
    public $table = "ordens_servico";

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
        // if (Session::get('usuarioNivel') == 1) {
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
                osp.quantidade AS quantidade_peca_ordem,
                p.id AS peca_id,
                p.nome_peca,
                p.valor_peca,
                p.descricao_peca
            FROM
                ordens_servico os
            INNER JOIN
                ordens_servico_pecas osp ON os.id = osp.id_ordem_servico
            INNER JOIN
                pecas p ON osp.id_peca = p.id;
            ");
            
        // } 
        // else {
        //     $rsc = $this->db->dbSelect("SELECT * FROM logs ORDER BY {$orderBy}");
            
        // }

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }
    
    /**
     * getPecaCombobox
     *
     * @param int $estado 
     * @return array
     */
    public function getPecaCombobox($termo)
    {
        // Verifica se foi fornecido um termo de pesquisa válido
        if (!empty($termo)) {
            // Realiza a consulta no banco de dados
            $rsc = $this->db->select(
                "pecas",
                "all",
                [
                    'where' => [
                        'nome_peca' => ['LIKE', $termo],
                    ]
                ]
            );

            // Array para armazenar os resultados
            $produtos = [];
            foreach ($rsc as $produto) {
                $produtos[] = [
                    'id' => $produto['id'],
                    'nome_peca' => $produto['nome_peca']
                ];
            }

            return $produtos;
        }

        return [];
    }

    public function idUltimaMovimentacao()
    {

        $rsc = $this->db->dbSelect("SELECT MAX(id) AS ultimo_id FROM movimentacao");

        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    /**
     * insertMovimentacao
     *
     * @param array $movimentacao
     * @param array $aProdutos
     * @return void
     */
    public function insertOrdemServico($ordem_servico, $aPecas)
    {

        $ultimoRegistro = $this->db->insert($this->table, $ordem_servico);

        // var_dump($aPecas);
        // exit;
        
        if ($ultimoRegistro > 0) {

            if($aPecas[0]['id_peca'] != '') {
                foreach ($aPecas as $item) {

                    $item["id_ordem_servico"] = $ultimoRegistro;

                    // exit('opa');

                    $this->db->insert("ordens_servico_pecas", $item);
                }
            }

            return true;

        } else {
            return false;
        }
    }

    /**
     * updateMovimentacao
     *
     * @param array $movimentacao
     * @param array $aProdutos
     * @return void
     */
    public function updateOrdemServico($idMovimentacao, $movimentacao, $aProdutos)
    {

        if($idMovimentacao) {

            $condWhere = $idMovimentacao['id'];

            $atualizaInformacoesMovimentacao = $this->db->update($this->table, ['id' => $condWhere], $movimentacao);

            if($atualizaInformacoesMovimentacao) {
                return true;
            }

        } else {
            return false;
        }
    }

    public function updateInformacoesProdutoMovimentacao($id_movimentacao, $aProdutos, $acao, $quantidade_movimentacao = null)
    {

        $id_produto = isset($aProdutos[0]['id_produtos']) ? $aProdutos[0]['id_produtos'] : "";

        if($id_movimentacao && $id_produto != "") {
            $condWhere = $id_movimentacao['id_movimentacao'];

            foreach ($aProdutos as $item) {
                if($acao['acaoProduto'] == 'update') {
                    $item['quantidade'] = $quantidade_movimentacao;

                    $atualizaProdutosMovimentacao = $this->db->update("movimentacao_item", ['id_movimentacoes' => $condWhere, 'id_produtos' => $id_produto], $item);

                    if($atualizaProdutosMovimentacao) {
                        return true;
                    }
                }

                else if($acao['acaoProduto'] == 'insert'){

                    $item['id_movimentacoes'] = $id_movimentacao['id_movimentacao'];
                    $item['quantidade'] = $quantidade_movimentacao;

                    $insereProdutosMovimentacao = $this->db->insert("movimentacao_item", $item);

                    if($insereProdutosMovimentacao) {
                        return true;
                    }
                    
    
                } else {
                    echo "erro";
                }
            }

        } else {
            return false;
        }
    }

    public function deleteInfoProdutoMovimentacao($id_movimentacao, $aProdutos, $tipo_movimentacao, $quantidadeRemover)
    {

        $item_movimentacao = $this->db->select(
            "ordens_servico_pecas",
            "all",
            [
            "where" => ["id_ordem_servico" => $id_movimentacao, "id_peca" => $aProdutos[0]["id"]]
            ]
        );

        // var_dump($item_movimentacao);
        // var_dump($id_movimentacao);
        // var_dump($aProdutos);
        // var_dump($tipo_movimentacao);
        // var_dump($quantidadeRemover);
        // exit;

        if ($item_movimentacao) {

            // recupera a quantidade atual do item na movimentação
            $quantidadeAtual = $item_movimentacao[0]['quantidade'];

            // Verifica se a quantidade a ser removida não ultrapassa a quantidade atual na comanda
            if ($quantidadeRemover <= $quantidadeAtual) {
                // Subtrai a quantidade a ser removida da quantidade atual na comanda
                $novaQuantidadeMovimentacao = ($quantidadeAtual - $quantidadeRemover);

                // Atualiza a tabela movimetacao_itens com a nova quantidade
                $atualizaInfoProdutosMovimentacao = $this->db->update("ordens_servico_pecas", ['id_ordem_servico' => $id_movimentacao, 'id_peca' => $item_movimentacao[0]['id_peca']], ['quantidade' => $novaQuantidadeMovimentacao]);

                //Verifica se o produto existe
                if ($atualizaInfoProdutosMovimentacao) {
                    // Remove os produtos com quantidade igual a zero da movimentação
                    $qtdZero = $this->db->delete('ordens_servico_pecas', ['id_ordem_servico' => $id_movimentacao, 'id_peca' =>  $item_movimentacao[0]['id_peca'], 'quantidade' => 0]);
                    
                    return true;

                } else {
                    exit("msgError Erro ao atualizar produto na movimentação.");
                    Session::set("msgError", "Erro ao atualizar produto na movimentação.");
                    return false;
                }
            } else {
                exit("msgError Quantidade maior que a da movimentação..");
                Session::set("msgError", "Quantidade maior que a da movimentação.");
                return false;
                
            }
        } else {
            exit("msgError Item não encontrado na movimentação.");
            Session::set("msgError", "Item não encontrado na movimentação.");
            return false;
        }
    }

    public function imprimirOS($id_ordem_servico) {

        require('assets/vendors/vendor/autoload.php');

        $result = $this->db->select(
            "ordens_servico",
            "all",
            [
            "where" => ["id" => $id_ordem_servico]
            ]
        );

        $resultA = $this->db->select(
            "ordens_servico_pecas",
            "all",
            [
            "where" => ["id_ordem_servico" => $id_ordem_servico]
            ]
        );

        $result_pecas = $this->db->select(
            "pecas",
            "all",
            [
            "where" => ["id" => $resultA[0]['id_peca']]
            ]
        );

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Adicionar a imagem
        $pdf->Image(baseUrl() . 'assets/img/brasao-pmrl.png', 98, 10, 15);

        // Adicionar espaço abaixo da imagem
        $pdf->Ln(20);

        // Título
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Ordem de Serviço'), 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);

        // // Cabeçalho da tabela principal
        // $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', 'Campo'), 1, 0, 'C');
        // $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Valor'), 1, 1, 'C');

        // Função para alternar cores
        function alternaCor($pdf, $line_number) {
            if ($line_number % 2 == 0) {
                $pdf->SetFillColor(230, 230, 230);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }
        }

        // Dados da tabela principal com linhas zebrada
        $linhas = [
            // ['ID:', $result[0]['id']],
            [iconv('UTF-8', 'ISO-8859-1', 'Nome do Cliente:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['cliente_nome'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Telefone:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['telefone_cliente'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Modelo do Dispositivo:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['modelo_dispositivo'])],
            [iconv('UTF-8', 'ISO-8859-1', 'IMEI:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['imei_dispositivo'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Descrição do Serviço:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['descricao_servico'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Tipo de Serviço:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['tipo_servico'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Problema Reportado:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['problema_reportado'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Data de Abertura:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['data_abertura'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Status:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['status'])],
            [iconv('UTF-8', 'ISO-8859-1', 'Observações:'), iconv('UTF-8', 'ISO-8859-1', $result[0]['observacoes'])],
        ];

        // Largura das colunas
        $largura_campo = 60;
        $largura_valor = $pdf->GetPageWidth() - $largura_campo - 20;

        $line_number = 0;
        foreach ($linhas as $linha) {
            alternaCor($pdf, $line_number);
            $pdf->SetX(10);
            $pdf->Cell($largura_campo, 10, $linha[0], 1, 0, 'L', true);
            $pdf->SetX(10 + $largura_campo);
            $pdf->MultiCell($largura_valor, 10, $linha[1], 1, 'L', true);
            $line_number++;
        }

        // Adicionar espaço antes da seção de peças
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Orçamento'), 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);

        // Cabeçalho da tabela de peças
        $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', 'Peça'), 1, 0, 'C');
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Valor'), 1, 1, 'C');

        $total = 0;

        // Dados das peças
        foreach ($result_pecas as $peca) {
            $peca_nome = isset($peca['nome_peca']) ? trim($peca['nome_peca']) : 'N/A';
            $valor = isset($peca['valor_peca']) ? (float)$peca['valor_peca'] : 0.0;

            $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', $peca_nome), 1, 0, 'L');
            $pdf->Cell(0, 10, number_format($valor, 2, ',', '.'), 1, 1, 'R');

            $total += $valor;
        }

        // Adicionando uma linha para o total
        $pdf->Ln(10);
        $pdf->Cell(60, 10, 'Total', 1, 0, 'L');
        $pdf->Cell(0, 10, number_format($total, 2, ',', '.'), 1, 1, 'R');

        $pdf->Ln(20);
        $pdf->Cell(0, 10, '___________________________________________', 0, 1, 'C'); // Centralizado na página
        $pdf->Cell(0, 10, $result[0]['cliente_nome'], 0, 1, 'C'); // Centralizado na página

        $pdf->Output();
    }
}