<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>


<?php

session_start();

include_once("alerta.php");
include_once("../models/User.php");



if ($_SERVER["REQUEST_METHOD"] != "POST")
{
    header("Location: ../../index.html");
    exit();
}


$email = trim($_POST["username"] ?? "");
$senha = $_POST["password"] ?? "";


if ($email == "" || $senha == "")
{
    mostrarAlerta(
        "warning",
        "Preencha os campos.",
        "Informe seu e-mail e sua senha.",
        "../../index.html"
    );

    exit();
}


$senha = md5($senha);
$obj = new User();

$resp = $obj->ValidarLogin($email,$senha);


if (!$resp)
{
    mostrarAlerta(
        "error",
        "Erro de login!",
        "Senha ou usuário inválido, tente novamente.",
        "../../index.html"
    );
    exit();
}


$resultado = $obj->ListarUmUsuario($email);

if (!$resultado)
{
    session_destroy();
    mostrarAlerta(
        "error",
        "Erro!",
        "Não foi possível carregar os dados do usuário.",
        "../../index.html"
    );
    exit();
}


$_SESSION["id_usuario"] = $resultado["id_usuarios"];
$_SESSION["id_usuarios"] = $resultado["id_usuarios"];
$_SESSION["email"] = $resultado["email"];
$_SESSION["login"] = md5($resultado["email"]);
$_SESSION["usuarios"] = $resultado["nome"];
$_SESSION["tipo_usuario"] = $resultado["tipo_usuario"];
$_SESSION["primeiro_acesso"] = $resultado["primeiro_acesso"];


if ($_SESSION["tipo_usuario"] == "Aluno")
{
    include_once("../models/Alunos.php");

    $objAluno = new Alunos();

    $aluno = $objAluno->BuscarAlunoPorEmail($email);


    if (!$aluno)
    {
        session_destroy();

        mostrarAlerta(
            "error",
            "Cadastro não encontrado!",
            "Seu usuário existe, mas seu cadastro de aluno não foi encontrado.",
            "../../index.html"
        );

        exit();
    }


    $_SESSION["id_alunos"] = $aluno["id_alunos"];

    $_SESSION["id_usuarios"] = $aluno["usuarios_idfk"];



    if ($_SESSION["primeiro_acesso"] == true)
    {
        header("Location: ../views/alterarSenhaAluno.php");
        exit();
    }


    header("Location: ../views/inicio.php");
    exit();
}


if ($_SESSION["tipo_usuario"] == "Administrativo")
{
    $_SESSION["id_usuarios"] = $resultado["id_usuarios"];



    if ($_SESSION["primeiro_acesso"] == true)
    {
        header("Location: ../views/alterarSenha.php");
        exit();
    }


    header("Location: ../views/dashboard.php");
    exit();
}

session_destroy();

mostrarAlerta(
    "error",
    "Tipo de usuário inválido.",
    "Não foi possível identificar o tipo da sua conta.",
    "../../index.html"
);
exit();
?>