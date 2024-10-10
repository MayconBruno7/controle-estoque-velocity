<?php

namespace App\Controllers;

use App\Models\CargoModel;
use CodeIgniter\Controller;

class Cargo extends BaseController
{
    protected $cargoModel;

    public function __construct()
    {
        $this->cargoModel = new CargoModel();
        
        // Verifica se o usuário é administrador antes de permitir o acesso
        if (!$this->getAdministrador()) {
            return redirect()->to(site_url("Home"));
        }
    }

    /**
     * Exibe a lista de cargos
     */
    public function index()
    {
        $data['cargos'] = $this->cargoModel->orderBy('id')->findAll();
        return view('restrita/listaCargo', $data);
    }

    /**
     * Formulário de inserção/edição de cargos
     */
    public function form($action = null, $id = null)
    {
        $data = [];

        if ($action !== 'insert' && $id !== null) {
            $data['cargo'] = $this->cargoModel->find($id);
        }

        $data['action'] = $action;

        return view('restrita/formCargo', $data);
    }

    /**
     * Insere um novo cargo
     */
    public function insert()
    {
        $post = $this->request->getPost();

        // Validação com regras definidas no model
        if (!$this->validate($this->cargoModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($this->cargoModel->save([
            'nome' => $post['nome'],
            'statusRegistro' => $post['statusRegistro']
        ])) {
            session()->set('msgSuccess', 'Cargo adicionada com sucesso.');
        } else {
            session()->set('msgError', 'Falha ao tentar inserir uma nova Cargo.');
        }

        return redirect()->to(site_url('Cargo'));
    }

    /**
     * Atualiza um cargo existente
     */
    public function update($id = null)
    {
        $post = $this->request->getPost();

        // Validação com regras definidas no model
        if (!$this->validate($this->cargoModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($this->cargoModel->update($id, [
            'nome' => $post['nome'],
            'statusRegistro' => $post['statusRegistro']
        ])) {
            session()->set('msgSuccess', 'Cargo alterada com sucesso.');
        } else {
            session()->set('msgError', 'Falha ao tentar alterar a Cargo.');
        }

        return redirect()->to(site_url('Cargo'));
    }

    /**
     * Exclui um cargo
     */
    public function delete($id)
    {
        if ($this->cargoModel->delete($id)) {
            session()->set('msgSuccess', 'Cargo excluída com sucesso.');
        } else {
            session()->set('msgError', 'Falha ao tentar excluir a Cargo.');
        }

        return redirect()->to(site_url('Cargo'));
    }
}
