<?php

    require_once "library/protectUser.php";
    require_once "vendor/autoload.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //

    $mail = new PHPMailer();

    try {

            //Server settings
            $mail->CharSet      = "utf-8";
            //$mail->SMTPDebug    = SMTP::DEBUG_SERVER;                                 // Habilitar saída de depuração detalhada
            $mail->isSMTP(true);                                                        // Enviar usando SMTP
            $mail->Host         = 'smtp.gmail.com';                                     // Host
            $mail->SMTPAuth     = true;                                                 // Habilitar autenticação SMTP
            $mail->Username     = 'maycon7ads@gmail.com';             
            // $mail->Password     = 'f@@dy2021';  
            $mail->Password     = "gail vmtv oogi kjyx";                      
            $mail->SMTPSecure   = PHPMailer::ENCRYPTION_SMTPS;                          // Habilitar criptografia TLS implícita
            $mail->Port         = 465;

            //Recipients
            // $mail->setFrom("informatica@rosariodalimeira.mg.gov.br", $_POST['nome']);                            // Rementente
            $mail->addAddress('maycon7ads@gmail.com', 'Setor de informática');           // Destinatário
            //$mail->addReplyTo('info@example.com', 'Information');                     // E-mail de resposta
            //$mail->addCC('cc@example.com');                                           // cópia
            //$mail->addBCC('bcc@example.com');                                         // Cópia oculta
    
            // Anexos
            //$mail->addAttachment('/var/tmp/file.tar.gz');                             // Adicionar Anexos
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');
    
            require_once "library/Database.php";

            $db = new Database();
            
             $data = $db->dbSelect("SELECT * FROM itens WHERE quantidade <= 3");

            // String para armazenar itens com quantidade menor ou igual a 3
            $itensParaNotificar = '';
        
            foreach ($data as $row) {
                // Verifica se a quantidade é menor ou igual a 3
                if ($row) {
                    // Concatena os dados dos itens no corpo do e-mail
                    $itensParaNotificar .= "Informamos por meio deste e-mail que o item {$row['nomeItem']} está com apenas {$row['quantidade']} unidade(s) em estoque.<br>";
                }
                // Restante do seu loop
            }
        
            // Enviar e-mail se houver itens para notificar
            if (!empty($itensParaNotificar)) {
                //Content
                $mail->isHTML(true);                                                        // Defina o formato do e-mail para HTML
                $mail->Subject = "Alerta de estoque";
                $mail->Body    = $itensParaNotificar;                                        // Corpo do e-mail no formato HTML
                $mail->AltBody = strip_tags($itensParaNotificar);                           // Corpo do e-mail no formato texto
        
                if ($mail->send()) {
                    return header("Location: listaItens.php?msgSucesso=E-mail enviado com sucesso.");
                } else {
                    return header("Location: listaItens.php?msgError=Error ao tentar enviar e-mail: " . $mail->ErrorInfo);
                }
            } else {
                // Não há itens para notificar
                return header("Location: listaItens.php?msgSucesso=Nenhum item para notificar.");
            }
        } catch (\Exception $e) {
            return header("Location: listaItens.php?msgError=Error ao tentar enviar e-mail: " . $mail->ErrorInfo);
        }