<?php

namespace App\Controllers;

use App\Models\FuncionarioModel;
use App\Models\UsuarioModel;
use App\Models\LogModel;
use CodeIgniter\Controller;

class Log extends BaseController
{
    protected $funcionarioModel;
    protected $usuarioModel;
    protected $logModel;

    public function __construct()
    {
        $this->funcionarioModel = new FuncionarioModel();
        $this->usuarioModel = new UsuarioModel();
        $this->logModel = new LogModel();

        // Somente pode ser acessado por usuários administradores
        if (!$this->getAdministrador()) {
            return redirect()->to(base_url('home'));
        }
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $dados = [
            'aFuncionario' => $this->funcionarioModel->findAll(),
            'aUsuario' => $this->usuarioModel->findAll(),
            'aLog' => $this->logModel->findAll()
        ];

        return view('restrita/log', $dados);
    }

    /**
     * viewLog
     *
     * @param int|null $logId
     * @return void
     */
    public function viewLog()
    {
        // Recuperando todos os segmentos da URL
        $segmentos = $this->request->getURI()->getSegments(4);

        // Acessando o primeiro segmento
        $logId = $segmentos[0] ?? null; // Use o índice apropriado para o segmento desejado

        var_dump($logId);
        exit;

        $dados = [
            'aFuncionario' => $this->funcionarioModel->findAll(),
            'aUsuario' => $this->usuarioModel->findAll(),
        ];

        if ($logId !== null) {
            $registro = $this->logModel->find($logId);
            $dados = array_merge($dados, $registro);
        }

        return view('restrita/view_log', $dados);
    }
}
