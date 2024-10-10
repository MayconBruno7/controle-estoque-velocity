<?php

namespace App\Controllers;

use App\Models\FornecedorModel;
use App\Models\EstadoModel;
use App\Models\CidadeModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;

class Fornecedor extends BaseController
{
    protected $fornecedorModel;

    public function __construct()
    {
        $this->fornecedorModel = new FornecedorModel();

        // Só acessa se estiver logado
        if (!$this->getAdministrador()) {
            return redirect()->to(base_url('home'));
        }
    }

    /**
     * index
     *
     * @return void
     */
    public function index(): string
    {
        $data['fornecedores'] = $this->fornecedorModel->orderBy('id', 'ASC')->findAll();
        return view('restrita/listaFornecedor', $data);
    }

    /**
     * form
     *
     * @param string|null $acao
     * @param int|null $id
     * @return string
     */
    public function form(string $acao = 'insert', int $id = null): string
    {
        $data = [];

        if ($acao !== 'insert') {
            $data = $this->fornecedorModel->find($id);
        }

        $estadoModel = new EstadoModel();
        $data['aEstado'] = $estadoModel->orderBy('id', 'ASC')->findAll();

        $cidadeModel = new CidadeModel();
        $data['aCidade'] = $cidadeModel->orderBy('id', 'ASC')->findAll();

        return view('restrita/formFornecedor', $data);
    }

    /**
     * insert
     *
     * @return RedirectResponse
     */
    public function insert(): RedirectResponse
    {
        $post = $this->request->getPost();

        if (!$this->validate($this->fornecedorModel->getValidationRules())) {
            return redirect()->to(base_url('fornecedor/form/insert'))->withInput();
        }

        $data = [
            'nome' => $post['nome'],
            'cnpj' => preg_replace('/[^0-9]/', '', $post['cnpj']),
            'endereco' => $post['endereco'],
            'cidade' => $post['cidade'],
            'estado' => $post['estado'],
            'bairro' => $post['bairro'],
            'numero' => $post['numero'],
            'telefone' => preg_replace('/[^0-9]/', '', $post['telefone']),
            'statusRegistro' => $post['statusRegistro']
        ];

        if ($this->fornecedorModel->insert($data)) {
            session()->setFlashdata('msgSuccess', 'Fornecedor adicionada com sucesso.');
        } else {
            session()->setFlashdata('msgError', 'Falha ao tentar inserir uma nova Fornecedor.');
        }

        return redirect()->to(base_url('fornecedor'));
    }

    /**
     * update
     *
     * @return RedirectResponse
     */
    public function update(): RedirectResponse
    {
        $post = $this->request->getPost();

        if (!$this->validate($this->fornecedorModel->getValidationRules())) {
            return redirect()->to(base_url('fornecedor/form/update/' . $post['id']))->withInput();
        }

        $data = [
            'nome' => $post['nome'],
            'cnpj' => preg_replace('/[^0-9]/', '', $post['cnpj']),
            'endereco' => $post['endereco'],
            'cidade' => $post['cidade'],
            'estado' => $post['estado'],
            'bairro' => $post['bairro'],
            'numero' => $post['numero'],
            'telefone' => preg_replace('/[^0-9]/', '', $post['telefone']),
            'statusRegistro' => $post['statusRegistro']
        ];

        if ($this->fornecedorModel->update($post['id'], $data)) {
            session()->setFlashdata('msgSuccess', 'Fornecedor alterada com sucesso.');
        } else {
            session()->setFlashdata('msgError', 'Falha ao tentar alterar a Fornecedor.');
        }

        return redirect()->to(base_url('fornecedor'));
    }

    /**
     * delete
     *
     * @return RedirectResponse
     */
    public function delete(): RedirectResponse
    {
        $id = $this->request->getPost('id');

        if ($this->fornecedorModel->delete($id)) {
            session()->setFlashdata('msgSuccess', 'Fornecedor excluída com sucesso.');
        } else {
            session()->setFlashdata('msgError', 'Falha ao tentar excluir a Fornecedor.');
        }

        return redirect()->to(base_url('fornecedor'));
    }

    /**
     * requireAPI
     *
     * @param string|null $cnpj
     * @return void
     */
    public function requireAPI(string $cnpj = null)
    {
        if ($cnpj) {
            $data = $this->fornecedorModel->requireAPI($cnpj);
            return $this->response->setJSON($data);
        } else {
            return $this->response->setJSON(['error' => 'Parâmetro CNPJ não fornecido na requisição.']);
        }
    }

    /**
     * getCidadeComboBox
     *
     * @param int|null $estadoId
     * @return void
     */
    public function getCidadeComboBox(int $estadoId = null)
    {
        $cidadeModel = new CidadeModel();
        $dados = $cidadeModel->where('estado_id', $estadoId)->findAll();

        if (empty($dados)) {
            $dados[] = ['id' => ''];
        }

        return $this->response->setJSON($dados);
    }
}
