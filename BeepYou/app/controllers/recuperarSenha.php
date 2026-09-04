<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>

<?php

session_start();

include_once("alerta.php");
include_once("../models/User.php");

require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$objUsuario = new User();

$email = $_POST["email"] ?? "";

if(empty($email))
{
    mostrarAlerta(
        "warning",
        "Digite seu e-mail!",
        "",
        "voltar"
    );
    exit();
}

$usuario = $objUsuario->VerificarEmail($email);

if(!$usuario)
{
    mostrarAlerta(
        "error",
        "E-mail não encontrado!",
        "Não encontramos um usuário ativo com este e-mail.",
        "voltar"
    );
    exit();
}

$token = bin2hex(random_bytes(32));

if(!$objUsuario->CriarTokenRecuperacao($email, $token))
{
    mostrarAlerta(
        "error",
        "Erro ao gerar recuperação!",
        "Não foi possível gerar o link de recuperação.",
        "voltar"
    );
    exit();
}


$link = "http://localhost/BeepYou/app/views/redefinirsenha.php?token=" . $token;
$mail = new PHPMailer(true);

try
{
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'fernanda.berns18@gmail.com';
    $mail->Password = 'rodw mahb kknb frjw';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom(
        'fernanda.berns18@gmail.com',
        'BeepYou'
    );

    $mail->addAddress(
        $usuario["email"],
        $usuario["nome"]
    );

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->Subject = 'Recuperação de senha - BeepYou';

    $mail->Body = "
        <h2>Recuperação de senha</h2>
        <p>Olá, {$usuario["nome"]}!</p>
        <p>Recebemos uma solicitação para redefinir sua senha no sistema <strong>BeepYou</strong>.   </p>
        <p> Clique no botão abaixo para criar uma nova senha:  </p>
        <p> <a href='{$link}'
               style='
                    display:inline-block;
                    padding:12px 20px;
                    background:#4CAF50;
                    color:white;
                    text-decoration:none;
                    border-radius:5px;'>
                Redefinir minha senha
            </a>
        </p>
        <p>Se você não solicitou essa alteração, simplesmente ignore este e-mail.  </p>
        <p><strong>BeepYou</strong> </p>";

    $mail->send();

    mostrarAlerta(
        "success",
        "E-mail enviado com sucesso!",
        "Enviamos um link para redefinir sua senha.",
        "../../index.html"
    );
}
catch(Exception $e)
{
    mostrarAlerta(
        "error",
        "Erro ao enviar o e-mail!",
        "Não foi possível enviar o link de recuperação.",
        "voltar"
    );
}
exit();
?>