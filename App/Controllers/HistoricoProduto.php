<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\HistoricoProdutoModel;

class HistoricoProduto extends BaseController
{
    protected $historicoProdutoModel;

    public function __construct()
    {
        $this->historicoProdutoModel = new HistoricoProdutoModel();

        // Só acessa se estiver logado
        if (!$this->getUsuario()) {
            return redirect()->to(base_url('home'));
        }
    }

    /**
     * getHistoricoProduto
     *
     * @param int|null $produtoId
     * @return void
     */
    public function getHistoricoProduto()
    {

        $dataMod = $this->request->getVar('dataMod');

        if ($dataMod) {
            $dados = $this->historicoProdutoModel->getHistoricoProduto($dataMod);
            return $this->response->setJSON($dados);
        } else {
            return $this->response->setJSON(['error' => 'ID do produto não fornecido.']);
        }
    }
}
