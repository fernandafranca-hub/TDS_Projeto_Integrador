<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salvar Patrimonio</title>
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

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $codigo = $_POST["codigo"];
    $nome = $_POST["nome"];
    $categoria = $_POST["categoria"];
    $descricao = $_POST["descricao"];
    $quantidade = $_POST["quantidade"];
    $status = $_POST["status"];
    $usuarios_idfk = $_SESSION["id_usuarios"];

    $obj = new Patrimonio();

    $id = $obj->CadastrarPatrimonio($codigo, $nome, $categoria, $descricao, $quantidade, $status, $usuarios_idfk);

    if($id)
    {
        header("Location: ../views/etiquetaPatrimonioIndividual.php?id=" . $id);        
        exit();
    }

    mostrarAlerta(
        "error",
        "Erro ao cadastrar patrimônio!",
        "Não foi possível cadastrar o patrimônio.",
        "../views/patrimonio.php"
    );

    exit();
}

?>