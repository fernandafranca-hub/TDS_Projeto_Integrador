<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION["tipo_usuario"]) ||
    $_SESSION["tipo_usuario"] != "Administrativo"
) {
    header("Location: dashboard.php");
    exit();
}

include_once("alerta.php");
include_once("../models/Patrimonio.php");

$objPatrimonio = new Patrimonio();

if(!isset($_SESSION["patrimonios_impressao"]))
{
    mostrarAlerta(
        "warning",
        "Nenhum patrimônio selecionado!",
        "Selecione os patrimônios que deseja imprimir na área de QR Code.",
        "../views/config.php"
    );
    exit();
}

$ids = $_SESSION["patrimonios_impressao"];

$patrimonios = $objPatrimonio->BuscarPatrimoniosSelecionados($ids);

if(!$patrimonios)
{
    mostrarAlerta(
        "error",
        "Patrimônios não encontrados!",
        "Não foi possível localizar os patrimônios selecionados.",
        "../views/config.php"
    );
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Impressão QR Code</title>
<link rel="stylesheet" type="text/css" href="../../public/css/qrcode.css">
</head>

<body>
    <div class="area-impressao">
        <?php foreach($patrimonios as $patrimonio){ ?>
        <div class="etiqueta">
            <h2>BeepYou</h2>
            <?php  $data = $patrimonio["codigo"];
            echo '<img src="'.(new chillerlan\QRCode\QRCode)->render($data).'">';?>
            <p><strong><?php echo $patrimonio["codigo"]; ?></strong></p>
        </div>
        <?php } ?>
    </div>
    <br>
    <button class="btn-imprimir botao-print"onclick="window.print()">Imprimir</button>
    <a href="config.php"> <img alt="Voltar" title="Voltar" src="../../public/img/iconvoltar.png" class="icon"></a>
</body>
</html>
