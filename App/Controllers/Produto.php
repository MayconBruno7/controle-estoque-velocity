<?php

namespace App\Controllers;

use App\Models\ProdutoModel;
use App\Models\HistoricoProdutoModel;
use App\Models\FornecedorModel;
use CodeIgniter\Controller;

class Produto extends BaseController
{
    protected $produtoModel;
    protected $historicoProdutoModel;
    protected $fornecedorModel;

    public function __construct()
    {
        // Carregando os modelos
        $this->produtoModel = new ProdutoModel();
        $this->historicoProdutoModel = new HistoricoProdutoModel();
        $this->fornecedorModel = new FornecedorModel();

        // Verifica se o usuário está logado
        if (!$this->getAdministrador()) {
            return redirect()->to("Home/login");
        }
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $acao = $this->request->getVar('acao');
        
        if ($acao != 'delete') {
            $data['produtos'] = $this->produtoModel->getLista("id", $acao);
        } else {
            $data['produtos'] = $this->produtoModel->listaDeleteProduto($this->request->getVar('parametro'));
        }

        return view("restrita/listaProduto", $data);
    }

    /**
     * form
     *
     * @return void
     */
    public function form($id = null)
    {
        $data = [];

        if ($id !== null) {
            $data = $this->produtoModel->find($id);
        }

        $data['aFornecedor'] = $this->fornecedorModel->findAll();
        $data['aHistoricoProduto'] = $this->historicoProdutoModel->historicoProduto($id, 'id');

        return view("restrita/formProduto", $data);
    }

    /**
     * insert
     *
     * @return void
     */
    public function insert()
    {
        $post = $this->request->getPost();

        if (!$this->validate($this->produtoModel->validationRules)) {
            return redirect()->to("Produto/form/insert")->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($this->produtoModel->insert([
            "nome" => $post['nome'],
            "quantidade" => $post['quantidade'],
            "fornecedor" => $post['fornecedor_id'],
            "statusRegistro" => $post['statusRegistro'],
            "condicao" => $post['condicao'],
            "descricao" => $post['descricao']
        ])) {
            session()->setFlashdata('msgSuccess', "Produto adicionada com sucesso.");
        } else {
            session()->setFlashdata('msgError', "Falha ao tentar inserir uma nova Produto.");
        }

        return redirect()->to("Produto");
    }

    /**
     * update
     *
     * @return void
     */
    public function update()
    {
        $post = $this->request->getPost();

        if (!$this->validate($this->produtoModel->validationRules)) {
            return redirect()->to("Produto/form/update/" . $post['id'])->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateProduto = $this->produtoModel->update($post['id'], [
            "nome" => $post['nome'],
            "quantidade" => $post['quantidade'],
            "fornecedor" => $post['fornecedor_id'],
            "statusRegistro" => $post['statusRegistro'],
            "condicao" => $post['condicao'],
            "descricao" => $post['descricao'],
        ]);

        $insertHistoricoProduto = $this->historicoProdutoModel->insert([
            "id_produtos" => $post['id'],
            "nome_produtos" => $post['nome'],
            "descricao_anterior" => $post['descricao'],
            "quantidade_anterior" => $post['quantidade'],
            "fornecedor_id" => $post['fornecedor_id'],
            "status_anterior" => $post['statusRegistro'],
            "statusItem_anterior" => $post['condicao'],
            "dataMod" => $post['dataMod'],
        ]);

        if ($updateProduto) {
            session()->setFlashdata('msgSuccess', "Produto alterada com sucesso.");
        } else {
            session()->setFlashdata('msgError', "Falha ao tentar alterar a Produto.");
        }

        return redirect()->to("Produto");
    }

    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {
        $id = $this->request->getPost('id');

        if ($this->produtoModel->delete($id)) {
            session()->setFlashdata('msgSuccess', "Produto excluída com sucesso.");
        } else {
            session()->setFlashdata('msgError', "Falha ao tentar excluir a Produto.");
        }

        return redirect()->to("Produto");
    }
}
