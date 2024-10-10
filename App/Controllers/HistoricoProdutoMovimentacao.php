<?php

namespace App\Controllers;

use App\Models\HistoricoProdutoMovimentacaoModel;
use CodeIgniter\Controller;

class HistoricoProdutoMovimentacao extends BaseController
{
    protected $historicoProdutoMovimentacaoModel;

    public function __construct()
    {
        $this->historicoProdutoMovimentacaoModel = new HistoricoProdutoMovimentacaoModel();

        // Só acessa se estiver logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('home'));
        }
    }

    /**
     * index
     *
     * @param int|null $produtoId
     * @return void
     */
    public function index(int $produtoId = null)
    {
        if ($produtoId) {
            $dados = $this->historicoProdutoMovimentacaoModel->historico_produto_movimentacao($produtoId);
            return view('restrita/historico_produto_movimentacao', ['dados' => $dados]);
        } else {
            return redirect()->to(base_url('home'))->with('msgError', 'ID do produto não fornecido.');
        }
    }
}
