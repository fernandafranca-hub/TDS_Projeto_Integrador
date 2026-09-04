<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salvar Configurações</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>

<?php

session_start();

if(!isset($_SESSION["id_usuarios"]))
{
    header("Location: ../../index.html");
    exit();
}

if($_SESSION["tipo_usuario"] != "Administrativo")
{
    header("Location: ../views/dashboard.php");
    exit();
}

include_once("alerta.php");
include_once("../models/Configuracao.php");


if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $dias = $_POST["dias_emprestimo"] ?? "";

    if($dias == "")
    {
        mostrarAlerta(
            "warning",
            "Informe a quantidade de dias.",
            "",
            "voltar"
        );
        exit();
    }

    if(!is_numeric($dias) || $dias < 1)
    {
        mostrarAlerta(
            "warning",
            "Quantidade inválida.",
            "Informe uma quantidade de dias maior que zero.",
            "voltar"
        );
        exit();
    }

    $dias = (int)$dias;

    $objConfiguracao = new Configuracao();

    if($objConfiguracao->AlterarDiasEmprestimo($dias))
    {
        mostrarAlerta(
            "success",
            "Configuração salva!",
            "O prazo dos novos empréstimos foi atualizado.",
            "../views/config.php"
        );
    }
    else
    {
        mostrarAlerta(
            "error",
            "Erro ao salvar configuração.",
            "Não foi possível alterar o prazo dos empréstimos.",
            "voltar"
        );
    }
}
?>

