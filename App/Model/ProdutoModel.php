<?php

use App\Library\ModelMain;
use App\Library\Session;
use App\Library\ControllerMain;


Class ProdutoModel extends ModelMain
{

    public $table = "produto";

    public $validationRules = [
        'descricao' => [
            'label' => 'Descrição',
            'rules' => 'required|min:3|max:50'
        ],
        'condicao' => [
            'label' => 'Condição',
            'rules' => 'required'
        ],
        'nome' => [
            'label' => 'Nome',
            'rules' => 'required'
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
            $rsc = $this->db->dbSelect(

                "SELECT 
                        produto.*, 
                        (SELECT valor FROM movimentacao_item WHERE id_produtos = produto.id LIMIT 1) AS valor
                    FROM 
                    {$this->table}"
            
            );
            
        } else {

            $rsc = $this->db->dbSelect("SELECT 
                    produto.*, 
                    (SELECT valor FROM movimentacao_item WHERE id_produtos = produto.id LIMIT 1) AS valor
                FROM 
                {$this->table}
                WHERE statusRegistro = 1 AND quantidade > 0");
  
        }
        
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    /**
     * lista
     *
     * @param string $orderBy 
     * @return void
     */
    public function listaDeleteProduto($id_produto)
    {

        if (Session::get('usuarioNivel') == 1) {
            $rsc = $this->db->dbSelect(
                "SELECT 
                    produto.*, 
                    (SELECT valor FROM movimentacao_item WHERE id_produtos = produto.id LIMIT 1) AS valor
                FROM 
                    {$this->table}
                WHERE 
                    produto.id = ?", [$id_produto]
            );
            
        } else {

            $rsc = $this->db->dbSelect(
                "SELECT 
                    produto.*, 
                    (SELECT valor FROM movimentacao_item WHERE id_produtos = produto.id LIMIT 1) AS valor
                FROM 
                    {$this->table}
                WHERE 
                    produto.statusRegistro = 1 AND produto.id = ?", [$id_produto]
            );
  
        }
        
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    public function recuperaProduto($idProduto)
    {

        $rsc = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE id = ?", [$idProduto]);
            
        if ($this->db->dbNumeroLinhas($rsc) > 0) {
            return $this->db->dbBuscaArrayAll($rsc);
        } else {
            return [];
        }
    }

    /**
     * updateMovimentacao
     *
     * @param array $movimentacao
     * @param array $aProdutos
     * @return void
     */
    public function updateProduto($id_produto, $id_movimentacao, $tipo_movimentacao, $infoProduto)
    {

        if($id_produto) {

            $condWhere = $id_produto;

            $produto = $this->recuperaProduto($id_produto);
            $quantidadeAtual = $produto[0]['quantidade'];

            if($tipo_movimentacao == 1) {
                $attquantidade = ($quantidadeAtual + $infoProduto['quantidade']);
            } else if ($tipo_movimentacao == 2) {
                $attquantidade = ($quantidadeAtual - $infoProduto['quantidade']);
            }

            $atualizaInformacoesProduto = $this->db->update($this->table, ['id' => $condWhere], ["quantidade" => $attquantidade]);

            foreach ($infoProduto as $item) {
                
                $atualizaProdutosMovimentacao = $this->db->update("movimentacao_item", ['id_movimentacoes' => $id_movimentacao, 'id_produtos' => $id_produto], ['quantidade' => $item]);
                var_dump($atualizaProdutosMovimentacao);
                var_dump([$item]);
                exit;
            }

            if($atualizaInformacoesProduto || $atualizaProdutosMovimentacao) {
                return true;
            }

        } else {
            return false;
        }
    }

    
    public function insertHistoricoProduto($item)
    {
        // var_dump($item);
        // exit;

        $this->db->insert("historico_produto", $item);
    }

    public function pesquisaProduto($dados) {

        // // atribui o valor de search a $dados
        // $dados = $_GET['search'];

        // // select de produtos de acordo com o id daquela categoria de produtos
        // $data = $db->dbSelect("SELECT * FROM produto WHERE (VALOR_UNITARIO LIKE '%$dados%' OR descricao LIKE '%$dados%') ORDER BY descricao");
    }



    // public function form()
    // {
    //     if ($this->getAcao() != "insert") {

    //         try {
    
    //             // Consulta SQL para buscar a data formatada
    //             $dataMod = $this->db->dbSelect("SELECT DATE_FORMAT(dataMod, '%d/%m/%Y ás %H:%i:%s') AS dataModFormatada FROM {$this->table} WHERE id = ?", 'first', [$_GET['id']]);
            
    //             // Verifica se a dataModFormatada está definida e atribui à variável
    //             $dataModFormatada = isset($dataMod->dataModFormatada) ? $dataMod->dataModFormatada : '';
            
    //             // prepara comando SQL
    //             $dados = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE id = ? ", 'first',[$_GET['id']]);
    
            
    //         // se houver erro na conexão com o banco de dados o catch retorna
    //         } catch (Exception $ex) {
    //             echo '<p style="color: red;">ERROR: '. $ex->getMessage(). "</p>";
    //         }
    //     }
        
    //     $dadosHistorico = $this->db->dbSelect("SELECT * FROM historico_produtos");
    
    //     $dadosFornecedor = $this->db->dbSelect("SELECT * FROM fornecedor");
    
    //     if ($dadosFornecedor) {
    //         // Verifica se existe um valor de fornecedor definido no item
    //         $fornecedor_id = isset($dados->fornecedor) ? $dados->fornecedor : "";
    //     }
        
    //     function obterNomeFornecedor($fornecedor_id) {
    //         $result = $this->db->dbSelect("SELECT nome FROM fornecedor WHERE id = ?", 'first', [$fornecedor_id]);
    //         return $result ? $result->nome : '';
    //     }

    //     $nome_fornecedor = isset($fornecedor_id) ? obterNomeFornecedor($fornecedor_id) : '';
    
    
    //     if ($this->getAcao() != 'insert'){
    //         $ConfereHistorico = $this->db->dbSelect("SELECT mi.id_produtos FROM movimentacao_item mi WHERE id_produtos = ?", "first", [$_GET['id']]);
    //     }
    // }
    
    /**
     * getProdutoCombobox
     *
     * @param int $categoria_id 
     * @return array
     */
    // public function getProdutoCombobox($categoria_id) 
    // {
    //     $rsc = $this->db->dbSelect("SELECT p.id, p.descricao 
    //                                 FROM {$this->table} as p
    //                                 INNER JOIN categoria as c ON c.id = p.categoria_id
    //                                 WHERE c.id = ?
    //                                 ORDER BY p.descricao",
    //                                 [$categoria_id]);

    //     if ($this->db->dbNumeroLinhas($rsc) > 0) {
    //         return $this->db->dbBuscaArrayAll($rsc);
    //     } else {
    //         return [];
    //     }
    // }
}