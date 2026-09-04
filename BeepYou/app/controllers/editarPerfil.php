<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
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

include_once("alerta.php");
include_once("../models/User.php");

echo '.';

$objUsuario = new User();

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];

    if($objUsuario->EditarPerfil($nome, $email, $telefone))
    {
        $_SESSION["usuarios"] = $nome;
        $_SESSION["email"] = $email;
        $_SESSION["telefone"] = $telefone;

        mostrarAlerta(
            "success",
            "Perfil atualizado!",
            "Os dados do perfil foram alterados com sucesso.",
            "../controllers/configUsuario.php"
        );
        exit();
    }
    else
    {
        mostrarAlerta(
            "error",
            "Erro ao atualizar perfil!",
            "Não foi possível salvar as alterações.",
            "voltar"
        );
        exit();
    }
}
?>