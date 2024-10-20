<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\RelatorioModel;
use App\Models\FuncionarioModel;
use App\Models\UsuarioRecuperaSenhaModel;

use CodeIgniter\HTTP\RedirectResponse;

class Login extends BaseController 
{
    protected $usuarioModel;
    protected $relatorioModel;
    protected $usuarioRecuperaSenhaModel;
    protected $funcionarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->relatorioModel = new RelatorioModel();
        $this->usuarioRecuperaSenhaModel = new UsuarioRecuperaSenhaModel();
        $this->funcionarioModel = new FuncionarioModel();
    }

    public function signIn()
    {
        $post = $this->request->getPost();

        // Criação de super usuário
        $superUser = $this->usuarioModel->criaSuperUser();

        if ($superUser > 0) {
            return redirect()->to(base_url('Home/login'))->with('msgError', 'Falha na criação do super usuário');
        }

        // Buscar usuário no banco de dados
        $usuario = $this->usuarioModel->getUserEmail($post['email']);

        if ($usuario) {
            // var_dump($post, $post["senha"], $usuario['senha']);
            // exit;
            // Validar a senha
            if (!password_verify(trim($post["senha"]), $usuario['senha'])) {
                return redirect()->to(base_url('Home/login'))->with('msgError', 'Usuário ou senha inválidos.');
            } 
        
            // Validar status do usuário
            if ($usuario['statusRegistro'] == 2) {
                return redirect()->
                to(base_url('Home/login'))->with('msgError', 'Usuário inativo.');
            }   

            // Definir sessão do usuário logado
            session()->set([
                'usuarioId'    => $usuario['id'],
                'usuarioLogin' => $usuario['nome'],
                'usuarioEmail' => $usuario['email'],
                'usuarioNivel' => $usuario['nivel'],
                'id_funcionario' => $usuario['id_funcionario'],
                "current_user" => $usuario['id']
            ]);

            // Recuperar imagem do funcionário
            $funcionario = $this->funcionarioModel->recuperaFuncionario($usuario['id_funcionario']);

            // Verifica se $funcionario é um array e não está vazio
            if (!empty($funcionario) && isset($funcionario[0]['imagem'])) {
                session()->set('usuarioImagem', $funcionario[0]['imagem']);
            } else {
                // Trate o caso em que não há funcionário ou imagem disponível
                session()->set('usuarioImagem', 'default_image_path'); // Substitua pelo caminho da imagem padrão, se necessário
            }

            // Gerenciamento de cookies
            if (isset($_COOKIE['username']) && $_COOKIE['username'] !== $post["email"]) {
                setcookie('username', '', time() - 3600, "/");
                setcookie('password', '', time() - 3600, "/");
            }

            if (isset($post['remember'])) {
                setcookie('username', $post["email"], time() + (86400 * 30), "/");
                setcookie('password', $post["senha"], time() + (86400 * 30), "/");
            }

            $redirectUrl = '';

            if (session()->get('usuarioNivel') == 1) {
                $redirectUrl = 'Home/homeAdmin';
            } elseif (session()->get('usuarioNivel') == 11) {
                $redirectUrl = 'Home/home';
            } 

            return redirect()->to(base_url($redirectUrl));
        } else {
            return redirect()->to(base_url('Home/login'))->with('msgError', 'Usuário ou senha inválidos.');
        }
    }

    public function signOut()
    {
        session()->destroy();
        return redirect()->to(base_url('Home/home'));
    }

    public function solicitaRecuperacaoSenha()
    {
        return view('usuario/formSolicitaRecuperacaoSenha');
    }

    public function gerarLinkRecuperaSenha()
    {
        $post = $this->request->getPost();
        $usuario = $this->usuarioModel->getUserEmail($post['email']);

        if (!$usuario) {
            return redirect()->to(base_url('login/solicitaRecuperacaoSenha'))->with('msgError', 'E-mail não encontrado.');
        }

        $created_at = date('Y-m-d H:i:s');
        $chave = sha1($usuario['id'] . $usuario['senha'] . date('YmdHis', strtotime($created_at)));
        $link = base_url('login/recuperarSenha/' . $chave);

        $corpoEmail = 'Clique no link para recuperar sua senha: <a href="'. $link .'">Recuperar a senha</a>';
        // $enviado = Email::enviaEmail('noreply@exemplo.com', 'Recuperação de Senha', 'Recuperação de Senha', $corpoEmail, $usuario['email']);

        // if ($enviado) {
        //     $usuarioRecuperaSenhaModel = new UsuarioRecuperaSenhaModel();
        //     $usuarioRecuperaSenhaModel->desativaChaveAntigas($usuario['id']);
        //     $usuarioRecuperaSenhaModel->insert(['usuario_id' => $usuario['id'], 'chave' => $chave, 'created_at' => $created_at]);

        //     return redirect()->to(base_url('Home/login'))->with('msgSuccess', 'Link de recuperação enviado!');
        // }

        return redirect()->to(base_url('login/solicitaRecuperacaoSenha'))->with('msgError', 'Falha ao enviar o e-mail.');
    }

    public function recuperarSenha($chave)
    {
        $usuarioRecuperaSenhaModel = new UsuarioRecuperaSenhaModel();
        $userChave = $usuarioRecuperaSenhaModel->getRecuperaSenhaChave($chave);

        if ($userChave) {
            $validade = strtotime("+1 hour", strtotime($userChave['created_at']));
            if (time() <= $validade) {
                $usuario = $this->usuarioModel->find($userChave['usuario_id']);
                if ($usuario && sha1($userChave['usuario_id'] . $usuario['senha'] . date('YmdHis', strtotime($userChave['created_at']))) === $chave) {
                    session()->set('recuperaSenha', ['usuario_id' => $usuario['id'], 'recupera_id' => $userChave['id']]);
                    return view('usuario/formRecuperarSenha', ['usuario' => $usuario]);
                }
            }
        }
        return redirect()->to(base_url('login/solicitaRecuperacaoSenha'))->with('msgError', 'Chave inválida ou expirada.');
    }

    public function atualizaRecuperaSenha()
    {
        $post = $this->request->getPost();

        if ($post['NovaSenha'] !== $post['NovaSenha2']) {
            return redirect()->back()->with('msgError', 'Senhas não conferem.');
        }

        $usuarioRecuperaSenhaModel = new UsuarioRecuperaSenhaModel();
        $usuario = $this->usuarioModel->find($post['id']);
        if ($usuario) {
            $usuarioRecuperaSenhaModel->update($post['usuariorecuperasenha_id'], ['statusRegistro' => 2]);
            $this->usuarioModel->update($usuario['id'], ['senha' => password_hash($post['NovaSenha'], PASSWORD_DEFAULT)]);
            return redirect()->to(base_url('Home/login'))->with('msgSuccess', 'Senha alterada com sucesso!');
        }

        return redirect()->back()->with('msgError', 'Falha ao atualizar senha.');
    }

    public function novaContaVisitante()
    {
        $post = $this->request->getPost();

        if (!$this->validate($this->usuarioModel->validationRules)) {
            return redirect()->to(base_url('Home/criarConta'))->with('msgError', 'Dados inválidos.');
        }

        $post['senha'] = password_hash($post['senha'], PASSWORD_DEFAULT);
        if ($this->usuarioModel->insert($post)) {
            return redirect()->to(base_url('Home/login'))->with('msgSuccess', 'Usuário criado com sucesso!');
        }

        return redirect()->to(base_url('Home/criarConta'))->with('msgError', 'Falha ao criar usuário.');
    }
}
