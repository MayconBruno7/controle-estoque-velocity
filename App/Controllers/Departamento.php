<?php

namespace App\Controllers;

use App\Models\DepartamentoModel;

class Departamento extends BaseController
{
    public $DepartamentoModel;

    /**
     * construct
     */
    public function __construct()
    {
        $this->DepartamentoModel = new DepartamentoModel();
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $data['dados'] = $this->DepartamentoModel
                            ->orderBy("descricao")
                            ->findAll();

        return view("admin/listaDepartamento", $data);
    }

    /**
     * form
     *
     * @param mixed $action 
     * @param mixed $id 
     * @return void
     */
    public function form($action = null, $id = null)
    {
        $data['action'] = $action;
        $data['data']   = null;
        $data['errors'] = [];

        if ($action != "new") {
            $data['data'] = $this->DepartamentoModel->find($id);
        }

        return view("admin/formDepartamento", $data);
    }

    /**
     * store
     *
     * @return void
     */
    public function store()
    {
        $post = $this->request->getPost();

        if ($this->DepartamentoModel->save([
            'id'                => ($post['id'] == "" ? null : $post['id']),
            "descricao"         => $post['descricao'],
            "statusRegistro"    => $post['statusRegistro']
        ])) {
            return redirect()->to("/Departamento")->with('msgSucess', "Dados atualizados com sucesso.");
        } else {
            return view("admin/formDepartamento", [
                "action"    => $post['action'],
                'data'      => $post,
                'errors'    => $this->DepartamentoModel->errors()
            ]);
        }
    }

    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {
        if ($this->DepartamentoModel->delete($this->request->getPost('id'))) {
            return redirect()->to('/Departamento')->with('msgSucess', 'Dados excluídos com sucesso.');
        } else {
            return redirect()->to('/Departamento')->with('msgError', 'Erro ao excluir dados.');
        }
    }
}