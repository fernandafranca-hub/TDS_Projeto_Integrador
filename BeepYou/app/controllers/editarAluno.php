<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno</title>
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
include_once("../models/Alunos.php");

$obj = new Alunos();

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $id = $_POST["id_alunos"];
    $matricula = $_POST["matricula"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $curso = $_POST["curso"];
    $resp = $obj->EditarAlunos($id,$nome, $email, $matricula, $telefone, $curso);
    if($resp == TRUE)
    {
        mostrarAlerta(
            "success",
            "Aluno atualizado!",
            "Os dados do aluno foram alterados com sucesso.",
            "../views/alunos.php"
        );
    }
    else
    {
        mostrarAlerta(
            "error",
            "Erro ao atualizar aluno!",
            "Não foi possível salvar as alterações.",
            "voltar"
        );
    }
}

if(isset($_GET["id"]))
{
    $id = $_GET["id"];
    $alunos = $obj->BuscarAlunoPorId($id);
    
    if(!$alunos)
    {
        mostrarAlerta(
            "error",
            "Aluno não encontrado!",
            "Não foi possível localizar este aluno.",
            "voltar"
        );
    }
    include_once("../views/editaraluno.php");
    exit();
}
?>
