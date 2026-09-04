<?php
require 'vendor/autoload.php';
use ChartJs\ChartJS;

$data = [
    'labels' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'sab'],
    'datasets' => [[
        'data' =>[8, 7, 8, 9, 6,10],
        'backgroundColor' => '#ccc',
        'borderColor' => '#e5801d',
        'label' => 'Legend'
    ]]
];
$options = ['responsive' => false];
$attributes = ['id' => 'example', 'width' => 500, 'height' => 500];
$Line = new ChartJS('radar',$data, $options, $attributes);

?><!DOCTYPE html>
<html>
  <head>
    <title>Chart.js-PHP</title>
  </head>
  <body>
    <?php
      echo $Line;
    ?>
    <script src="vendor/Ejdamm/Chart.js-PHP/js/Chart.min.js"></script>
    <script src="vendor/Ejdamm/Chart.js-PHP/js/driver.js"></script>
    <script>
      (function() {
        loadChartJsPhp();
      })();
    </script>
  </body>
</html>