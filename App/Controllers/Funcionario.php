<?php

namespace App\Controllers;

use App\Models\FuncionarioModel;
use App\Models\SetorModel;
use App\Models\CargoModel;
use CodeIgniter\Controller;

class Funcionario extends BaseController
{
    protected $model;
    protected $setorModel;
    protected $cargoModel;

    public function __construct()
    {
        $this->model = new FuncionarioModel(); // Inicializa o modelo de funcionário
        $this->setorModel = new SetorModel();
        $this->cargoModel = new CargoModel();

        // Somente pode ser acessado por usuários administradores
        if (!$this->getAdministrador()) {
            return redirect("Home");
        }
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $data['funcionarios'] = $this->model->getLista(); // Obtem a lista de funcionários
        return view('restrita/listaFuncionario', $data);
    }

    /**
     * form
     *
     * @return void
     */
    public function form($action = null, $id = null)
    {
        $data['data'] = null;
    
        if ($action != "new" && $id !== null) {
            $data['data'] = $this->model->find($id); // Busca o funcionário pelo ID
        }
        
        $data['action'] = $action;
        $data['errors'] = [];

        $data['aSetor'] = $this->setorModel->getLista(); // Obtem a lista de setores
        $data['aCargo'] = $this->cargoModel->getLista(); // Obtem a lista de cargos

        // var_dump($data['action']);
        // exit;

        return view('restrita/formFuncionario', $data);
    }

    /**
     * store
     *
     * @return void
     */
    public function store()
    {
        $post = $this->request->getPost();

        if ($this->model->save([
            'id' => ($post['id'] == "" ? null : $post['id']),
            'nome' => $post['nome'],
            'cpf' => preg_replace("/[^0-9]/", "", $post['cpf']),
            'telefone' => preg_replace("/[^0-9]/", "", $post['telefone']),
            'setor' => $post['setor'],
            'cargo' => $post['cargo'],
            'salario' => preg_replace("/[^0-9,]/", "", $post['salario']),
            'statusRegistro' => $post['statusRegistro'],
            // 'imagem' => !empty($_FILES['imagem']['name']) ? UploadImages::upload($_FILES, 'funcionarios') : $post['nomeImagem']
        ])) {
            return redirect()->to("/Funcionario")->with('msgSuccess', "Funcionário inserido com sucesso!");
        } else {
            return view("restrita/formFuncionario", [
                // 'action' => $post['action'],
                'data' => $post,
                'errors' => $this->model->errors()
            ]);
        }
    }

    // /**
    //  * update
    //  *
    //  * @return void
    //  */
    // public function update()
    // {
    //     $post = $this->request->getPost();

    //     if ($this->model->save([
    //         'id' => $post['id'],
    //         'nome' => $post['nome'],
    //         'cpf' => preg_replace("/[^0-9]/", "", $post['cpf']),
    //         'telefone' => preg_replace("/[^0-9]/", "", $post['telefone']),
    //         'setor' => $post['setor'],
    //         'cargo' => $post['cargo'],
    //         'salario' => preg_replace("/[^0-9,]/", "", $post['salario']),
    //         'statusRegistro' => $post['statusRegistro'],
    //         // 'imagem' => !empty($_FILES['imagem']['name']) ? UploadImages::upload($_FILES, 'funcionarios') : $post['nomeImagem']
    //     ])) {
    //         return redirect()->to("/Funcionario")->with('msgSuccess', "Funcionário atualizado com sucesso!");
    //     } else {
    //         return redirect()->to("/Funcionario/form/update/{$post['id']}")->with('msgError', "Falha ao atualizar o funcionário.");
    //     }
    // }

    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {
        $post = $this->request->getPost();

        if ($this->model->delete($post['id'])) {
            return redirect()->to("/Funcionario")->with("msgSuccess", "Funcionário excluído com sucesso!");
        } else {
            return redirect()->to("/Funcionario")->with("msgError", "Falha ao excluir o funcionário!");
        }
    }
}
