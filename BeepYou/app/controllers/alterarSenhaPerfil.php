<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha Perfil</title>
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
    $_SESSION["tipo_usuario"] != "Aluno" ||
    !isset($_SESSION["id_usuarios"])
) {
    header("Location: ../../index.html");
    exit();
}

$objUser = new User();

$id = $_SESSION["id_usuarios"];

if ($_SERVER["REQUEST_METHOD"] != "POST")
{
    header("Location: ../views/perfil.php");
    exit();
}

$novaSenha = $_POST["nova_senha"] ?? "";
$confirmarSenha = $_POST["confirmar_senha"] ?? "";


if ($novaSenha == "" || $confirmarSenha == "")
{
    mostrarAlerta(
        "warning",
        "Preencha todos os campos.",
        "Informe a nova senha e confirme a nova senha.",
        "../views/alterarSenhaPerfil.php"
    );
    exit();
}


if ($novaSenha != $confirmarSenha)
{
    mostrarAlerta(
        "warning",
        "Senhas diferentes.",
        "A confirmação da nova senha não confere.",
        "../views/alterarSenhaPerfil.php"
    );
    exit();
}


if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $novaSenha))
{
    mostrarAlerta(
        "warning",
        "Senha inválida.",
        "A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um número.",
        "../views/alterarSenhaPerfil.php"
    );
    exit();
}

$novaSenhaCriptografada = md5($novaSenha);


if ($objUser->AlterarSenha($id, $novaSenhaCriptografada))
{
    mostrarAlerta(
        "success",
        "Senha alterada com sucesso!",
        "Sua senha foi atualizada com sucesso.",
        "../views/perfil.php"
    );
    exit();
}


mostrarAlerta(
    "error",
    "Erro ao alterar senha.",
    "Não foi possível atualizar sua senha. Tente novamente.",
    "../views/alterarSenhaPerfil.php"
);
exit();
?>