<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar patrimonio</title>
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
include_once("../models/Patrimonio.php");
$obj = new Patrimonio();

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $id = $_POST["id_patrimonio"];
    $codigo = $_POST["codigo"];
    $nome = $_POST["nome"];
    $categoria = $_POST["categoria"];
    $descricao = $_POST["descricao"];
    $quantidade = $_POST["quantidade"];
    $status = $_POST["status"];

    if($obj->EditarPatrimonio($id, $codigo, $nome, $categoria, $descricao, $quantidade, $status))
    {
        mostrarAlerta(
            "success",
            "Patrimônio atualizado!",
            "Os dados do patrimônio foram alterados com sucesso.",
            "../views/patrimonio.php"
        );
        exit();
    }
    else
    {
        mostrarAlerta(
            "error",
            "Erro ao atualizar patrimônio!",
            "Não foi possível salvar as alterações.",
            "voltar"
        );
        exit();
    }
}


if(isset($_GET["id"]))
{
    $id = $_GET["id"];
    $patrimonio = $obj->BuscarPatrimonioPorId($id);
    if(!$patrimonio)
    {
        mostrarAlerta(
            "error",
            "Patrimônio não encontrado!",
            "Não foi possível localizar este patrimônio.",
            "voltar"
        );
        exit();
    }
    include_once("../views/editarpatrimonio.php");
}
?>