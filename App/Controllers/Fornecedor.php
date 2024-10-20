<?php

namespace App\Controllers;

use App\Models\FornecedorModel;
use App\Models\EstadoModel;
use App\Models\CidadeModel;
use CodeIgniter\Controller;

class Fornecedor extends BaseController
{
    protected $fornecedorModel;
    protected $estadoModel;
    protected $cidadeModel;

    public function __construct()
    {
        $this->fornecedorModel = new FornecedorModel();
        $this->estadoModel = new EstadoModel();
        $this->cidadeModel = new CidadeModel();

        // Só acessa se estiver logado
        if (!$this->getUsuario()) {
            return redirect()->to('home');
        }
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $data['fornecedores'] = $this->fornecedorModel->orderBy('id', 'ASC')->findAll();
        return view('restrita/listaFornecedor', $data);
    }

    /**
     * form
     *
     * @param string|null $action
     * @param int|null $id
     * @return string
     */
    public function form(string $action = null, int $id = null)
    {
        $data['action'] = $action;
        $data['data']   = null;
        $data['errors'] = [];

        $data['aEstado'] = $this->estadoModel->orderBy('id', 'ASC')->findAll();

        $data['aCidade'] = $this->cidadeModel->orderBy('id', 'ASC')->findAll();

        if ($action !== 'insert') {
            $data['data'] = $this->fornecedorModel->find($id);
        }

        return view('restrita/formFornecedor', $data);
    }

    /**
     * store
     *
     * @return void
     */
    public function store()
    {
        $post = $this->request->getPost();

        if ($this->fornecedorModel->save([
            'id' => ($post['id'] == "" ? null : $post['id']),
            'nome' => $post['nome'],
            'cnpj' => preg_replace('/[^0-9]/', '', $post['cnpj']),
            'endereco' => $post['endereco'],
            'cidade' => $post['cidade'],
            'estado' => $post['estado'],
            'bairro' => $post['bairro'],
            'numero' => $post['numero'],
            'telefone' => preg_replace('/[^0-9]/', '', $post['telefone']),
            'statusRegistro' => $post['statusRegistro']
        ])) {
            return redirect()->to("/Fornecedor")->with('msgSucess', "Dados atualizados com sucesso.");
        }else {
            return view("restrita/formFornecedor", [
                "action"    => $post['action'],
                'data'      => $post,
                'errors'    => $this->fornecedorModel->errors()
            ]);
        }
        
        return redirect()->to(base_url('Fornecedor'));
    }

    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {
        $id = $this->request->getPost('id');

        if ($this->fornecedorModel->delete($id)) {
            session()->setFlashdata('msgSuccess', 'Fornecedor excluída com sucesso.');
        } else {
            session()->setFlashdata('msgError', 'Falha ao tentar excluir a Fornecedor.');
        }

        return redirect()->to(base_url('Fornecedor'));
    }

    /**
     * requireAPI
     *
     * @param string|null $cnpj
     * @return void
     */
    public function requireAPI()
    {

        $cnpj = $this->request->getVar('cnpj'); 

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
    public function getCidadeComboBox()
    {

        // Recupera o ID do estado da URL
        $estadoId = $this->request->getVar('estadoId'); // Supondo que você está passando o parâmetro como "estadoId"

        $cidadeModel = new CidadeModel();

        $dados = $cidadeModel->where('estado', $estadoId)->findAll();
        // var_dump($dados);
        // exit;
        if (empty($dados)) {
            $dados[] = ['id' => ''];
        }

        return $this->response->setJSON($dados);
    }
}
