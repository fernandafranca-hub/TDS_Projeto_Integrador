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

ini_set('display_errors',1);
error_reporting(E_ALL);

require '../../vendor/autoload.php';

use chillerlan\QRCode\QRCode;

include_once("../models/Patrimonio.php");


if(!isset($_SESSION["id_usuarios"]))
{
    header("Location: ../../index.html");
    exit();
}


if(!isset($_SESSION["patrimonios_impressao"]) || empty($_SESSION["patrimonios_impressao"]))
{
    echo "Nenhum patrimônio selecionado.";
    exit();
}


$objPatrimonio = new Patrimonio();
$ids = $_SESSION["patrimonios_impressao"];
$patrimonios = [];

foreach($ids as $id)
{
    $patrimonio = $objPatrimonio->BuscarPatrimonioPorId($id);

    if($patrimonio)
    {
        $patrimonios[] = $patrimonio;
    }
}

if(count($patrimonios) == 0)
{
    echo "Nenhum patrimônio encontrado.";
    exit();
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>BeepYou - Impressão QR Code</title>
<link rel="stylesheet" href="../../public/css/qrcode.css">
</head>
<body>
    <div class="topo-impressao">
        <a href="config.php"><img src="../../public/img/iconvoltar.png" alt="Voltar" title="Voltar" class="icon"> </a>
    </div>
    <div class="pagina-impressao">
        <?php foreach($patrimonios as $patrimonio){ ?>
        <div class="etiqueta">
            <h2>BeepYou</h2>
            <?php $codigo = $patrimonio["codigo"]; echo '<img src="'.(new QRCode)->render($codigo).'" alt="QR Code">';?>
            <p><strong><?php echo $patrimonio["codigo"]; ?></strong></p>
            <span><?php echo $patrimonio["nome"]; ?></span>
        </div>
        <?php } ?>
    </div>
    <div class="acoes-impressao">
        <button class="btn-imprimir" onclick="window.print()">Imprimir QR Codes</button>
    </div>
</body>
</html>
