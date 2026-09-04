<?php

require_once "../../vendor/autoload.php";

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;


$id = $_GET["id"];

$dados = "Patrimônio BeepYou - ID: ".$id;

$result = Builder::create()
    ->writer(new PngWriter())
    ->data($dados)
    ->size(300)
    ->margin(10)
    ->build();


header("Content-Type: ".$result->getMimeType());

echo $result->getString();

?>