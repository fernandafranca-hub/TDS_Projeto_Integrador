<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha Aluno</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>
<?php

session_start();

include_once("../models/User.php");
include_once("alerta.php");


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../views/perfil.php");
    exit();
}


if (
    !isset($_SESSION["id_usuarios"]) ||
    !isset($_SESSION["tipo_usuario"]) ||
    $_SESSION["tipo_usuario"] !== "Aluno"
) {

    header("Location: ../../index.html");
    exit();
}


$idUsuario = $_SESSION["id_usuarios"];


$novaSenha = $_POST["nova_senha"] ?? "";
$confirmarSenha = $_POST["confirmar_senha"] ?? "";


if ($novaSenha === "" || $confirmarSenha === "") {

    mostrarAlerta(
        "warning",
        "Senha incompleta",
        "Preencha a nova senha e a confirmação.",
        "../views/perfil.php"
    );

    exit();
}


if ($novaSenha !== $confirmarSenha) {

    mostrarAlerta(
        "warning",
        "Senhas diferentes",
        "A nova senha e a confirmação precisam ser iguais.",
        "../views/perfil.php"
    );

    exit();
}

if (strlen($novaSenha) < 8) {

    mostrarAlerta(
        "warning",
        "Senha inválida",
        "A nova senha deve possuir pelo menos 8 caracteres.",
        "../views/perfil.php"
    );

    exit();
}


if (!preg_match('/[A-Z]/', $novaSenha)) {

    mostrarAlerta(
        "warning",
        "Senha inválida",
        "A nova senha deve possuir pelo menos 1 letra maiúscula.",
        "../views/perfil.php"
    );

    exit();
}


if (!preg_match('/[0-9]/', $novaSenha)) {

    mostrarAlerta(
        "warning",
        "Senha inválida",
        "A nova senha deve possuir pelo menos 1 número.",
        "../views/perfil.php"
    );
    exit();
}


$objUser = new User();
$senhaHash = md5($novaSenha);


$alterouSenha = $objUser->AlterarSenha(
    $idUsuario,
    $senhaHash
);


if (!$alterouSenha) {

    mostrarAlerta(
        "error",
        "Erro",
        "Não foi possível alterar sua senha. Tente novamente.",
        "../views/perfil.php"
    );

    exit();
}


mostrarAlerta(
    "success",
    "Senha alterada!",
    "Sua senha foi alterada com sucesso.",
    "../views/perfil.php"
);
exit();
?>