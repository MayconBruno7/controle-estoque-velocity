<?php


use App\Library\ControllerMain;
use App\Library\Email;
use App\Library\Session;
use App\Library\Redirect;

class FaleConosco extends ControllerMain 
{

    public function formularioEmail() 
    {
        return $this->loadView("restrita/faleConosco");
    }

    public function enviarEmail()
    {
        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitiza e obtém os dados do formulário
            $emailRemetente = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $nomeRemetente = filter_input(INPUT_POST, 'nome');
            $assunto = filter_input(INPUT_POST, 'assunto');
            $telefone = filter_input(INPUT_POST, 'telefone');
            $mensagem = filter_input(INPUT_POST, 'mensagem');

            // Verifica se o e-mail é válido
            if (!$emailRemetente) {
                echo "Email inválido!";
                return;
            }

            // Prepara o corpo do e-mail
            $corpoEmail = $mensagem . '<br><br> Para mais informações para o suporte, me ligue pelo telefone: ' . $telefone . ' ou me mande um email: ' . $emailRemetente;

            // Envia o e-mail
            $lRetMail = Email::enviaEmail(
                $emailRemetente,        /* Email do Remetente */
                $nomeRemetente,         /* Nome do Remetente */
                $assunto,               /* Assunto do e-mail */
                $corpoEmail,            /* Corpo do E-mail */
                'teste@gmail.com'  /* Destinatário do E-mail */
            );

            // Verifica se o e-mail foi enviado com sucesso
            if ($lRetMail) {
                Session::set("msgSuccess", "Email enviado com sucesso.");
                Redirect::page('FaleConosco/formularioEmail');
            } else {
                Session::set("msgError", "Falha tentar enviar o email.");
                Redirect::page('FaleConosco/formularioEmail');
            }
        }
    }

};
