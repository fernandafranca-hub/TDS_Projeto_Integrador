<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
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

$objUsuario = new User();

$id = $_POST["id_usuario"];
$nome = $_POST["nome"];
$email = $_POST["email"];
$tipo_usuario = $_POST["tipo_usuario"];

echo '.';


if($objUsuario->EditarUsuario($id,$nome,$email,$tipo_usuario))
{
    mostrarAlerta(
        "success",
        "Usuário alterado!",
        "Os dados do usuário foram alterados com sucesso.",
        "../controllers/configUsuario.php"
    );
}
else
{
    mostrarAlerta(
        "error",
        "Erro ao alterar usuário!",
        "Não foi possível salvar as alterações.",
        "voltar"
    );
}


header("Location: configUsuario.php#tabelaUsuarios");
exit();

?>