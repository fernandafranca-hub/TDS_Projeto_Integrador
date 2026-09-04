
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devolver Patrimoniot</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>

<?php

session_start();

include_once("alerta.php");
include_once("../models/Emprestimo.php");
include_once("../models/Patrimonio.php");


if (
    !isset($_SESSION["id_usuarios"]) ||
    !isset($_SESSION["id_alunos"]) ||
    $_SESSION["tipo_usuario"] != "Aluno"
) {
    header("Location: ../../index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../views/devolver.php");
    exit();
}


$codigo = trim($_POST["codigo"] ?? "");
$idEmprestimo = trim($_POST["id_emprestimo"] ?? "");

if (empty($codigo) || empty($idEmprestimo)) {

    mostrarAlerta(
        "warning",
        "Dados incompletos!",
        "Informe o código do patrimônio e selecione o empréstimo.",
        "../views/devolver.php"
    );

    exit();
}

$objEmprestimo = new Emprestimo();
$objPatrimonio = new Patrimonio();


$emprestimo = $objEmprestimo->BuscarEmprestimoPorIdAluno(
    $idEmprestimo,
    $_SESSION["id_alunos"]
);

if (!$emprestimo) 
{

    mostrarAlerta(
        "error",
        "Empréstimo não encontrado!",
        "Não foi possível localizar o empréstimo selecionado.",
        "../views/devolver.php"
    );
    exit();
}

if ($emprestimo["status"] !== "Emprestado") {

    mostrarAlerta(
        "warning",
        "Empréstimo já devolvido!",
        "Este patrimônio já foi registrado como devolvido.",
        "../views/devolver.php"
    );
    exit();
}

$idPatrimonio = $emprestimo["patrimonio_idfk"];

$patrimonio = $objPatrimonio->BuscarPatrimonioPorIdSemUsuario(
    $idPatrimonio
);


if (!$patrimonio) {

    mostrarAlerta(
        "error",
        "Patrimônio não encontrado!",
        "Não foi possível localizar o patrimônio relacionado a este empréstimo.",
        "../views/devolver.php"
    );
    exit();
}


if ((string)$patrimonio["codigo"] !== (string)$codigo) {

    mostrarAlerta(
        "error",
        "Código incorreto!",
        "O código informado não corresponde ao patrimônio selecionado.",
        "../views/devolver.php"
    );
    exit();
}


$resultado = $objEmprestimo->DevolverEmprestimo(
    $idEmprestimo
);

if ($resultado) {

    mostrarAlerta(
        "success",
        "Devolução realizada!",
        "O patrimônio foi devolvido com sucesso.",
        "../views/inicio.php"
    );
    exit();
}


mostrarAlerta(
    "error",
    "Erro ao devolver!",
    "Não foi possível registrar a devolução do patrimônio.",
    "../views/devolver.php"
);

exit();