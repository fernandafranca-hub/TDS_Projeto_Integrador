<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil Aluno</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>


<?php

session_start();

include_once("../models/Alunos.php");
include_once("../models/User.php");
include_once("alerta.php");



if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../views/perfil.php");
    exit();
}


if (
    !isset($_SESSION["id_alunos"]) ||
    !isset($_SESSION["id_usuarios"]) ||
    !isset($_SESSION["tipo_usuario"]) ||
    $_SESSION["tipo_usuario"] !== "Aluno"
) {
    header("Location: ../../index.html");
    exit();
}


$idAluno = $_SESSION["id_alunos"];
$idUsuario = $_SESSION["id_usuarios"];

$email = trim($_POST["email"] ?? "");
$telefone = trim($_POST["telefone"] ?? "");


if ($email === "") {

    mostrarAlerta(
        "warning",
        "E-mail obrigatório",
        "Informe seu e-mail.",
        "../views/perfil.php"
    );

    exit();
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    mostrarAlerta(
        "warning",
        "E-mail inválido",
        "Digite um endereço de e-mail válido.",
        "../views/perfil.php"
    );

    exit();
}


$objAluno = new Alunos();
$objUser = new User();


$alunoAtual = $objAluno->BuscarAlunoPorId($idAluno);
$usuarioAtual = $objUser->BuscarUsuarioPorId($idUsuario);


if (!$alunoAtual || !$usuarioAtual) {

    session_destroy();

    mostrarAlerta(
        "error",
        "Erro",
        "Não foi possível localizar seus dados.",
        "../../index.html"
    );

    exit();
}


$emailAtual = trim($alunoAtual["email"] ?? "");
$telefoneAtual = trim($alunoAtual["telefone"] ?? "");


$emailAlterado = strtolower($email) !== strtolower($emailAtual);
$telefoneAlterado = $telefone !== $telefoneAtual;

if ($emailAlterado) {

    $emailExiste = $objUser->EmailExisteOutroUsuario(
        $email,
        $idUsuario
    );

    if ($emailExiste) {

        mostrarAlerta(
            "warning",
            "E-mail já cadastrado",
            "Este e-mail já está sendo utilizado por outro usuário.",
            "../views/perfil.php"
        );

        exit();
    }
}

if (!$emailAlterado && !$telefoneAlterado) {

    mostrarAlerta(
        "info",
        "Nenhuma alteração",
        "Nenhum dado foi alterado.",
        "../views/perfil.php"
    );

    exit();
}


$matricula = $alunoAtual["matricula"];
$nome = $alunoAtual["nome"];
$curso = $alunoAtual["curso"];


$atualizouAluno = $objAluno->EditarAlunos( $idAluno, $nome, $email, $matricula, $telefone, $curso);

if (!$atualizouAluno) {

    mostrarAlerta(
        "error",
        "Erro",
        "Não foi possível atualizar os dados do aluno.",
        "../views/perfil.php"
    );

    exit();
}

$nomeUsuario = $usuarioAtual["nome"];

$atualizouUsuario = $objUser->EditarPerfilAluno( $idUsuario, $nomeUsuario, $email, $telefone);

if (!$atualizouUsuario) {

    mostrarAlerta(
        "error",
        "Erro",
        "Os dados do aluno foram atualizados, mas não foi possível atualizar os dados do usuário.",
        "../views/perfil.php"
    );

    exit();
}


$_SESSION["email"] = $email;

mostrarAlerta(
    "success",
    "Perfil atualizado!",
    "Seus dados foram atualizados com sucesso.",
    "../views/perfil.php"
);

exit();

?>