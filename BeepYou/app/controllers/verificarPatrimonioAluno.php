<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Patrimonio</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>

<?php

session_start();

include_once("../models/Patrimonio.php");
include_once("../models/Emprestimo.php");


if (
    !isset($_SESSION["id_usuarios"]) ||
    !isset($_SESSION["id_alunos"]) ||
    $_SESSION["tipo_usuario"] != "Aluno"
) {
    header("Location: ../../index.html");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../views/inicio.php");
    exit();
}

$codigo = trim($_POST["codigo"] ?? "");

if (empty($codigo)) {
    header("Location: ../views/scan_patrimonio.php?acao=emprestar");
    exit();
}

$objPatrimonio = new Patrimonio();


$patrimonio = $objPatrimonio->BuscarPatrimonioPorCodigoSemUsuario($codigo);

if (!$patrimonio) {
    $_SESSION["erro_patrimonio"] =
        "Não foi encontrado nenhum patrimônio com o código informado.";
    header("Location: ../views/scan_patrimonio.php?acao=emprestar");
    exit();
}

if ($patrimonio["status"] !== "Disponível") {
    $_SESSION["erro_patrimonio"] =
        "Este patrimônio não está disponível para empréstimo.";

    header("Location: ../views/scan_patrimonio.php?acao=emprestar");
    exit();
}


$objEmprestimo = new Emprestimo();

$emprestimoAtivo =
    $objEmprestimo->BuscarEmprestimoAtivoPorPatrimonio(
        $patrimonio["id_patrimonio"]
    );


if ($emprestimoAtivo) {

    
    $objPatrimonio->AlterarStatusPatrimonio(
        $patrimonio["id_patrimonio"],
        "Emprestado"
    );

    $_SESSION["erro_patrimonio"] =
        "Este patrimônio já possui um empréstimo ativo.";

    header("Location: ../views/scan_patrimonio.php?acao=emprestar");
    exit();
}

$_SESSION["patrimonio_confirmacao"] = $patrimonio;


header(
    "Location: ../views/confirmarEmprestimo.php"
);

exit();