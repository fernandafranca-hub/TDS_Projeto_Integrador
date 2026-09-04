<?php

require_once "../../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {

    // Configuração do servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // SUA CONTA GMAIL
    $mail->Username = 'fernanda.berns18@gmail.com';

    // SENHA DE APP GERADA NO GOOGLE
    $mail->Password = 'rodw mahb kknb frjw';

    // Criptografia
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;


    // Remetente
    $mail->setFrom(
        'fernanda.berns18@gmail.com',
        'BeepYou'
    );


    // DESTINATÁRIO DO TESTE
    $mail->addAddress(
        'fernanda.berns@hotmail.co.uk'
    );


    // Assunto
    $mail->Subject = 'Teste PHPMailer - BeepYou';


    // Mensagem
    $mail->isHTML(true);

    $mail->Body = '
        <h2>Teste de envio</h2>

        <p>Olá!</p>

        <p>
            Este é um teste de envio de e-mail
            utilizando o <strong>PHPMailer</strong>
            através do Gmail.
        </p>

        <p>
            Se você recebeu esta mensagem,
            o SMTP está funcionando corretamente.
        </p>

        <p>
            <strong>BeepYou</strong>
        </p>
    ';


    // Envia
    $mail->send();

    echo "E-mail enviado com sucesso!";

} catch (Exception $e) {

    echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";

}

?>