<?php
session_start();

if(!isset($_SESSION["id_usuarios"]))
{
header("Location: ../../index.html");
exit();
}

include_once("alerta.php");

echo '.';

if(isset($_GET["id"]))
{
        $id = $_GET["id"];
        include_once("../models/Patrimonio.php");
        $obj = new Patrimonio();

    if($obj->ExcluirPatrimonio($id))
    {
        mostrarAlerta(
            "success",
            "Patrimônio excluído!",
            "O patrimônio foi excluído com sucesso.",
            "../views/patrimonio.php"
        );
    }
    else
    {
        mostrarAlerta(
            "error",
            "Erro ao excluir patrimônio!",
            "Não foi possível excluir o patrimônio.",
            "voltar"
        );
    }
}
?>