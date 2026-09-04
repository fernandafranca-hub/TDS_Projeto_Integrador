<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Emprestimo</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>

<?php


session_start();

header("Content-Type: application/json");

if(!isset($_SESSION["id_usuarios"]))
{
    echo json_encode([
        "sucesso" => false
    ]);
    exit();
}

include_once("../models/Alunos.php");

$objAluno = new Alunos();

if(!isset($_GET["id"]))
{
    echo json_encode([
        "sucesso" => false
    ]);
    exit();
}

$id = $_GET["id"];

$possuiEmprestimo = $objAluno->PossuiEmprestimoAtivo($id);

echo json_encode([
    "sucesso" => true,
    "possuiEmprestimo" => $possuiEmprestimo
]);

exit();