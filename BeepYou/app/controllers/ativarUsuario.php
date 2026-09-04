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

    if($objUsuario->AtivarUsuario($id))
    {
        mostrarAlerta(
            "success",
            "Usuário ativado com sucesso!",
            "",
            "../views/config.php"
        );
    }
    else
    {
        mostrarAlerta(
            "error",
            "Erro ao ativar usuário!",
            "",
            "voltar"
        );
    }
}
?>
