<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salvar Aluno</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>

<?php

session_start();


if (!isset($_SESSION["id_usuarios"]))
{
    header("Location: ../../index.html");
    exit();
}


include_once("alerta.php");
include_once("../models/Alunos.php");
include_once("../models/User.php");

require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    $matricula = $_POST["matricula"] ?? "";
    $nome      = $_POST["nome"] ?? "";
    $email     = $_POST["email"] ?? "";
    $telefone  = $_POST["telefone"] ?? "";
    $curso     = $_POST["curso"] ?? "";

    if (
        empty($matricula) ||
        empty($nome) ||
        empty($email) ||
        empty($telefone) ||
        empty($curso)
    )
    {
        mostrarAlerta(
            "warning",
            "Preencha todos os campos!",
            "Todos os campos são obrigatórios.",
            "voltar"
        );
        exit();
    }

    $objUser  = new User();
    $objAluno = new Alunos();

    if ($objUser->VerificarEmail($email))
    {
        mostrarAlerta(
            "warning",
            "Este e-mail já está cadastrado!",
            "Informe outro e-mail para o aluno.",
            "voltar"
        );
        exit();
    }


    $senha = md5(date('H:i:s'));
    $senhaCriptografada = md5($senha);

    $id_usuario_aluno = $objUser->CadastrarUsuarioAluno(
        $nome,
        $email,
        $senhaCriptografada
    );

    if (!$id_usuario_aluno)
    {
        mostrarAlerta(
            "error",
            "Erro ao criar acesso!",
            "Não foi possível criar o acesso do aluno.",
            "voltar"
        );
        exit();
    }

    $alunoCadastrado = $objAluno->CadastrarAlunos(
    $matricula,
    $nome,
    $telefone,
    $email,
    $curso,
    $id_usuario_aluno
);


    if (!$alunoCadastrado)
    {
        mostrarAlerta(
            "error",
            "Erro ao cadastrar aluno!",
            "O acesso foi criado, mas não foi possível cadastrar o aluno.",
            "voltar"
        );
        exit();
    }

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

        /* REMETENTE */

        $mail->setFrom(
            'fernanda.berns18@gmail.com',
            'BeepYou'
        );

        $mail->addAddress(
            $email,
            $nome
        );

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->Subject = 'Acesso ao BeepYou';
        $mail->Body = "

            <h2>Bem-vindo ao BeepYou!</h2>
            <p>Olá, <strong>{$nome}</strong>!</p>
            <p>Seu acesso ao sistema<strong>BeepYou</strong> foi criado. </p>
            <p>Utilize os dados abaixo para realizar seu primeiro acesso:</p>
             <p>
                <a href='#########index.html'
                    style='
                    display:inline-block;
                    padding:12px 24px;
                    background-color:#112B6D;
                    color:#ffffff;
                    text-decoration:none;
                    border-radius:6px;
                    font-weight:bold;'>
                    Acessar o BeepYou
                </a>
            </p>  
            <p><strong>E-mail:</strong> {$email}
                <br>
                <strong>Senha inicial:</strong> {$senha}
            </p>

            <p><strong>Importante:</strong> por segurança, no primeiro acesso você deverá criar uma nova senha. </p>
            <p> Caso o botão acima não funcione, acesse:
                <br>
                <a href='############/index.html'></a>
            </p>
            <p>Atenciosamente,
                <br>
                <strong>BeepYou</strong>
            </p>";
        $mail->send();

        mostrarAlerta(
            "success",
            "Aluno cadastrado com sucesso!",
            "Os dados de acesso foram enviados para o e-mail do aluno.",
            "../views/alunos.php"
        );
    }
    catch (Exception $e)
    {
        mostrarAlerta(
            "warning",
            "Aluno cadastrado!",
            "O aluno foi cadastrado, mas não foi possível enviar o e-mail com a senha inicial.",
            "../views/alunos.php"
        );
    }
    exit();
}
?>