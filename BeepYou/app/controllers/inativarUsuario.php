<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inativar usuario</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>

<?php
session_start();

include_once("../models/User.php");
include_once("alerta.php");

if(!isset($_SESSION["id_usuarios"]))
{
    header("Location: ../../index.html");
    exit();
}

if(isset($_GET["id"]))
{
    $id = $_GET["id"];

    $objUsuario = new User();
    
    echo '.';

    if($objUsuario->InativarUsuario($id))
    {
        mostrarAlerta(
            "success",
            "Usuário inativado!",
            "O usuário foi inativado com sucesso.",
            "../views/config.php"
        );        
    }
    else
    {
        mostrarAlerta(
            "error",
            "Erro ao inativar usuário!",
            "Não foi possível inativar o usuário.",
            "voltar"
        );
    }
}
?>