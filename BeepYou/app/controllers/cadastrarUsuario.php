<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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


if (
    !isset($_SESSION["tipo_usuario"]) ||
    $_SESSION["tipo_usuario"] !== "Administrativo"
) {
    header("Location: ../views/dashboard.php");
    exit();
}

$id_usuario = $_POST["id_usuario"] ?? null;

$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$tipo_usuario = $_POST["tipo_usuario"] ?? "";

$tiposPermitidos = ["Leitor", "Administrativo"];

if (!in_array($tipo_usuario, $tiposPermitidos, true)) {
    mostrarAlerta(
        "error",
        "Tipo de usuário inválido!",
        "",
        "voltar"
    );
    exit();
}

$objUsuario = new User();


if ($id_usuario) {

    if ($objUsuario->EditarUsuario(
        $id_usuario,
        $nome,
        $email,
        $tipo_usuario
    )) {
        mostrarAlerta(
            "success",
            "Usuário alterado com sucesso!",
            "",
            "../views/config.php"
        );
    } else {
        mostrarAlerta(
            "error",
            "Erro ao alterar usuário!",
            "",
            "voltar"
        );
    }

    exit();
}


if ($objUsuario->VerificarEmail($email)) {
    mostrarAlerta(
        "warning",
        "Este e-mail já está cadastrado!",
        "",
        "voltar"
    );
    exit();
}


// Gera senha inicial
$senha = md5(date('H:i:s'));
$senhaCriptografada = md5($senha);


if (
    $objUsuario->CadastrarUsuario(
        $nome,
        $email,
        $senhaCriptografada,
        $tipo_usuario
    )
) {
    mostrarAlerta(
        "success",
        "Usuário cadastrado com sucesso!",
        "O usuário foi cadastrado e receberá a senha inicial por e-mail.",
        "../views/config.php"
    );
} else {
    mostrarAlerta(
        "error",
        "Erro ao cadastrar usuário!",
        "",
        "voltar"
    );
}

exit();
?>