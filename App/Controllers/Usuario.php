<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\FuncionarioModel;
use App\Models\CargoModel;


use CodeIgniter\Controller;
// use App\Libraries\Session; // Certifique-se de que este caminho está correto
// use App\Libraries\Redirect; // Certifique-se de que este caminho está correto
// use App\Libraries\Validator; // Certifique-se de que este caminho está correto


$this->load->library('session');

class Usuario extends Controller
{
    protected $model;
    public $FuncionarioModel;
    public $CargoModel;


    public function __construct()
    {
        $this->model = new UsuarioModel(); // Inicializa o modelo de usuário
        $this->FuncionarioModel = new FuncionarioModel();
        $this->CargoModel = new CargoModel();
   
    }

    /**
     * lista
     *
     * @return void
     */
    public function index()
    {
        // Somente pode ser acessado por usuários administradores
        if (!$this->getAdministrador()) {
            return redirect("Home");
        }

        $data['usuarios'] = $this->model->getLista();
        return view('usuario/listaUsuario', $data);
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
            // buscar o usuário pelo $id no banco de dados
            $data = $this->model->find($id);
        }

        $data['aFuncionario'] =  $this->FuncionarioModel->getLista();

        return view('usuario/formUsuario', $data);
    }

    public function profile()
    {
        $data = [];

        if ($this->request->getMethod() === 'post') {
            // buscar o usuário pelo $id no banco de dados
            $data = $this->model->find($this->request->getPost('id'));
        }

        $data['aFuncionario'] = $this->FuncionarioModel->recuperaFuncionario(session()->get('id_funcionario'));

        $data['aCargo'] = $this->CargoModel->getLista();

        return view('usuario/profile', $data);
    }

    /**
     * new - insere um novo usuário
     *
     * @return void
     */
    public function insert()
    {
        $post = $this->request->getPost();

        // Valida dados recebidos do formulário
        if (Validator::make($post, $this->model->validationRules)) {
            return redirect("Usuario/form/insert");
        } else {
            $data = [
                "statusRegistro" => $post['statusRegistro'],
                "nivel" => $post['nivel'],
                "nome" => $post['nome'],
                "email" => $post['email'],
                "senha" => password_hash($post['senha'], PASSWORD_DEFAULT),
                "id_funcionario" => $post['funcionarios']
            ];

            if ($this->model->insert($data)) {
                return redirect("Usuario", ["msgSuccess" => "Usuário inserido com sucesso!"]);
            } else {
                return redirect("Usuario", ["msgError" => "Falha na inserção dos dados do Usuário!"]);
            }
        }
    }

    /**
     * update
     *
     * @return void
     */    
    public function update()
    {
        $post = $this->request->getPost();

        // Valida dados recebidos do formulário
        if (Validator::make($post, $this->model->validationRules)) {
            return redirect("Usuario/form/update");
        } else {
            $data = [
                "nome" => $post['nome'],
                "statusRegistro" => $post['statusRegistro'],
                "nivel" => $post['nivel'],
                "email" => $post['email'],
                "id_funcionario" => $post['funcionarios']
            ];

            if ($this->model->update($post['id'], $data)) {
                return redirect("Usuario", ["msgSuccess" => "Usuário alterado com sucesso!"]);
            } else {
                return redirect("Usuario", ["msgError" => "Falha na alteração dos dados do Usuário!"]);
            } 
        }    
    }

    /**
     * delete - Exclui um usuário no banco de dados
     *
     * @return void
     */
    public function delete()
    {
        $post = $this->request->getPost();

        if ($this->model->delete($post['id'])) {
            return redirect("Usuario", ["msgSuccess" => "Usuário excluído com sucesso!"]);
        } else {
            return redirect("Usuario", ["msgError" => "Falha na exclusão do Usuário!"]);
        }   
    }

    /**
     * trocaSenha - Chama a view Trocar a senha
     *
     * @return void
     */
    public function trocaSenha()
    {
        return view("usuario/formTrocaSenha");
    }

    /**
     * atualizaSenha - Atualiza a senha do usuário
     *
     * @return void
     */
    public function atualizaTrocaSenha() 
    {
        $post = $this->request->getPost();
        $userAtual = $this->model->find($post["id"]);

        if ($userAtual) {
            if (password_verify(trim($post["senhaAtual"]), $userAtual['senha'])) {
                if (trim($post["novaSenha"]) == trim($post["novaSenha2"])) {
                    $lUpdate = $this->model->update($post['id'], ['senha' => password_hash($post["novaSenha"], PASSWORD_DEFAULT)]);

                    if ($lUpdate) {
                        return redirect("Usuario/trocaSenha", ["msgSuccess" => "Senha alterada com sucesso!"]);  
                    } else {
                        return redirect("Usuario/trocaSenha", ["msgError" => "Falha na atualização da nova senha!"]);    
                    }
                } else {
                    return redirect("Usuario/trocaSenha", ["msgError" => "Nova senha e conferência da senha estão divergentes!"]);                  
                }
            } else {
                return redirect("Usuario/trocaSenha", ["msgError" => "Senha atual informada não confere!"]);               
            }
        } else {
            return redirect("Usuario/trocaSenha", ["msgError" => "Usuário inválido!"]);   
        }
    }

    /**
     * perfil
     *
     * @return void
     */
    public function perfil()
    {
        $this->loadHelper("formulario");
        return view("admin/formPerfil", $this->model->find(    session()->get
('userCodigo')));
    }

    /**
     * atualizaPerfil
     *
     * @return void
     */
    public function atualizaPerfil()
    {
        $post = $this->request->getPost();

        if ($this->model->update($post['id'], ['nome' => $post['nome'], 'email' => $post['email']])) {
            $this->session->set_userdata("usuarioLogin", $post['nome']);
            $this->session->set_userdata("usuarioEmail", $post['email']);

            return redirect("Usuario/perfil", ["msgSuccess" => "Perfil atualizado com sucesso!"]);  
        } else {
            return redirect("Usuario/perfil", ["msgError" => "Falha na atualização do seu perfil, favor tentar novamente mais tarde!"]);  
        }
    }

    private function getAdministrador()
    {
        // Implementar lógica para verificar se o usuário é administrador
    }

    private function loadHelper($helper)
    {
        helper($helper);
    }
}
