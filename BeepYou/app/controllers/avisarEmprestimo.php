<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>

<?php

session_start();

include_once("alerta.php");
include_once("../models/Emprestimo.php");

require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$id = $_POST["id_emprestimo"] ?? "";

if (empty($id)) {

    mostrarAlerta(
        "error",
        "Empréstimo inválido!",
        "Não foi possível identificar o empréstimo.",
        "../views/emprestimos.php"
    );
    exit();
}


$objEmprestimo = new Emprestimo();

$emprestimo = $objEmprestimo->BuscarDadosAviso($id);


if (!$emprestimo) {

    mostrarAlerta(
        "error",
        "Empréstimo não encontrado!",
        "Não foi possível localizar este empréstimo.",
        "../views/emprestimos.php"
    );
    exit();
}


$dataPrevista = strtotime($emprestimo["data_prevista"]);
$hoje = strtotime(date("Y-m-d"));

if ($dataPrevista >= $hoje) {

    mostrarAlerta(
        "info",
        "Empréstimo em dia!",
        "Este empréstimo ainda não está atrasado.",
        "../views/emprestimos.php"
    );
    exit();
}


if (empty($emprestimo["email"])) {

    mostrarAlerta(
        "warning",
        "Aluno sem e-mail!",
        "Este aluno não possui um endereço de e-mail cadastrado.",
        "../views/emprestimos.php"
    );
    exit();
}


$nomeAluno = htmlspecialchars($emprestimo["nome_aluno"]);
$nomePatrimonio = htmlspecialchars($emprestimo["nome_patrimonio"]);

$dataPrevistaFormatada = date(
    "d/m/Y",
    strtotime($emprestimo["data_prevista"])
);


$mail = new PHPMailer(true);

try {

    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'fernanda.berns18@gmail.com';
    $mail->Password = 'rodw mahb kknb frjw';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;


    // REMETENTE

    $mail->setFrom(
        'fernanda.berns18@gmail.com',
        'BeepYou'
    );


    // DESTINATÁRIO

    $mail->addAddress(
        $emprestimo["email"],
        $emprestimo["nome_aluno"]
    );


    // CONFIGURAÇÃO//

    $mail->isHTML(true);

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->Subject = 'Aviso de atraso - Empréstimo BeepYou';


    $mail->Body = "
        <div style='font-family:Arial,sans-serif;'>
            <h2 style='color:#112B6D;'> BeepYou - Aviso de atraso </h2>
            <p> Olá, <strong>{$nomeAluno}</strong>! </p>
            <p> Identificamos que o empréstimo abaixo está com a devolução em atraso </p>
            <div style='
                background:#f1f4fa;
                padding:15px;
                border-radius:8px;
                margin:20px 0;'>
                <p> <strong>Patrimônio:</strong> {$nomePatrimonio} </p>
                <p> <strong>Data prevista para devolução:</strong> {$dataPrevistaFormatada}</p>
            </div>
            <p>Pedimos que providencie a devolução do patrimônio o mais breve possível.</p>
            <p>Caso já tenha realizado a devolução, favor desconsiderar esta mensagem. </p>
            <br>
            <p>Atenciosamente,<br><strong>BeepYou</strong></p>
        </div>";

    $mail->send();

    mostrarAlerta(
        "success",
        "Aviso enviado!",
        "O e-mail de atraso foi enviado para {$emprestimo["email"]}.",
        "../views/emprestimos.php"
    );
}
catch(Exception $e)
{
    mostrarAlerta(
        "error",
        "Erro ao enviar!",
        "Não foi possível enviar o aviso por e-mail.",
        "../views/emprestimos.php"
    );
}
exit();
?>