<?php

namespace App\Controllers;

use App\Models\SetorModel;
use App\Models\FuncionarioModel;
use CodeIgniter\Controller;

class Setor extends BaseController
{
    protected $setorModel;
    protected $funcionarioModel;

    public function __construct()
    {
        $this->setorModel = new SetorModel();
        $this->funcionarioModel = new FuncionarioModel();

        // Só acessa se tiver logado
        if (!$this->getAdministrador()) {
            return redirect()->to('/home');
        }
    }

    public function index()
    {
        $data['setores'] = $this->setorModel->getLista("id");
        return view('restrita/listaSetor', $data);
    }

    public function form($id = null)
    {
        $data = $id ? $this->setorModel->getById($id) : [];
        $data['aFuncionario'] = $this->funcionarioModel->lista('id');
        return view('restrita/formSetor', $data);
    }

    public function insert()
    {
        $post = $this->request->getPost();

        if ($this->validate($this->setorModel->validationRules)) {
            if ($this->setorModel->insert([
                "nome" => $post['nome'],
                "responsavel" => $post['funcionarios'],
                "statusRegistro" => $post['statusRegistro']
            ])) {
                session()->set('msgSuccess', 'Setor adicionada com sucesso.');
            } else {
                session()->set('msgError', 'Falha ao tentar inserir uma nova Setor.');
            }
            return redirect()->to('/setor');
        } else {
            return redirect()->to('/setor/form')->withInput();
        }
    }

    public function update()
    {
        $post = $this->request->getPost();

        if ($this->validate($this->setorModel->validationRules)) {
            if ($this->setorModel->update($post['id'], [
                "nome" => $post['nome'],
                "responsavel" => $post['funcionarios'],
                "statusRegistro" => $post['statusRegistro']
            ])) {
                session()->set('msgSuccess', 'Setor alterada com sucesso.');
            } else {
                session()->set('msgError', 'Falha ao tentar alterar a Setor.');
            }
            return redirect()->to('/setor');
        } else {
            return redirect()->to('/setor/form/' . $post['id'])->withInput();
        }
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        if ($this->setorModel->delete($id)) {
            session()->set('msgSuccess', 'Setor excluída com sucesso.');
        } else {
            session()->set('msgError', 'Falha ao tentar excluir a Setor.');
        }

        return redirect()->to('/setor');
    }
}
