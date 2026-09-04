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

require_once("../../vendor/autoload.php");
include_once("../controllers/alerta.php");
include_once("../models/Patrimonio.php");

$objPatrimonio = new Patrimonio();

$id = $_GET["id"] ?? "";

if(empty($id))
{
    mostrarAlerta(
        "error",
        "Patrimônio inválido!",
        "Não foi possível identificar o patrimônio cadastrado.",
        "../views/patrimonio.php"
    );
    exit();
}

$patrimonio = $objPatrimonio->BuscarPatrimonioPorId($id);

if(!$patrimonio)
{
    mostrarAlerta(
        "error",
        "Patrimônio não encontrado!",
        "Não foi possível localizar o patrimônio cadastrado.",
        "../views/patrimonio.php"
    );
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BeepYou - QR Code</title>
<link rel="stylesheet" type="text/css" href="../../public/css/qrcode.css">
</head>
<body>
    <div class="area-impressao">
        <div class="etiqueta">
            <h2>BeepYou</h2>
            <?php
            $data = $patrimonio["codigo"];
            echo '<img src="' . (new chillerlan\QRCode\QRCode)->render($data) .'">'; ?>
            <p><strong><?php echo htmlspecialchars($patrimonio["codigo"]); ?></strong> </p>
            <p><?php echo htmlspecialchars($patrimonio["nome"]); ?></p>
        </div>
    </div>
    <br>  

    <button class="btn-imprimir botao-print" onclick="window.print()">Imprimir</button>
   <div class="topo-etiqueta">
        <a href="patrimonio.php">
            <img src="../../public/img/iconvoltar.png" alt="Voltar" title="Voltar" class="icon"></a>
    </div>    
</body>
</html>