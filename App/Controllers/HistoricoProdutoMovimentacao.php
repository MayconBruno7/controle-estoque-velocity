<?php

namespace App\Controllers;

use App\Models\HistoricoProdutoMovimentacaoModel;

class HistoricoProdutoMovimentacao extends BaseController
{
    protected $historicoProdutoMovimentacaoModel;

    public function __construct()
    {
        $this->historicoProdutoMovimentacaoModel = new HistoricoProdutoMovimentacaoModel();

        // Só acessa se estiver logado
        if (!$this->getUsuario()) {
            return redirect()->to(base_url('home'));
        }
    }

    /**
     * index
     *
     * @param int|null $produtoId
     * @return void
     */
    public function index($produtoId, $action = null)
    {
        if ($produtoId) {
            // Verifica se $produtoId é um número e não um array
            if (!is_array($produtoId)) {
                $dados = $this->historicoProdutoMovimentacaoModel->historicoProdutoMovimentacao((int)$produtoId);
                return view('restrita/HistoricoProdutoMovimentacao', [
                    'dados' => $dados,
                    'action' => $action
                ]);
            } else {
                return redirect()->to(base_url('home'))->with('msgError', 'ID do produto é inválido.');
            }
        } else {
            return redirect()->to(base_url('home'))->with('msgError', 'ID do produto não fornecido.');
        }
    }
    
    
}
