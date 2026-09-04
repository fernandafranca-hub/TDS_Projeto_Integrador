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

include_once("../models/Emprestimo.php");
include_once("../models/Alunos.php");
include_once("../models/Patrimonio.php");

$objEmprestimo = new Emprestimo();

$ultimosEmprestimos = $objEmprestimo->UltimosEmprestimos();

require '../../vendor/autoload.php';

use ChartJs\ChartJS;


/* GRÁFICOS*/

$options = [
    'responsive' => true,
    'plugins' => [
        'legend' => [
            'position' => 'bottom'
        ],
        'title' => [
            'display' => true
        ]
    ],
    'cutout' => '65%'
];


/* GRÁFICO DE ALUNOS */

$objAluno = new Alunos();

$statusAlunos = $objAluno->TotalAlunosStatus();

$ativos = $statusAlunos["ativos"];
$inativos = $statusAlunos["inativos"];

$dataAlunos = [
    'labels' => ['Ativos', 'Inativos'],
    'datasets' => [[
        'data' => [$ativos, $inativos],
        'backgroundColor' => ['#6A46F5', '#112B6D'],
        'borderColor' => '#ffffff',
        'borderWidth' => 3
    ]]
];

$graficoAlunos = new ChartJS(
    'doughnut',
    $dataAlunos,
    $options,
    [
        'id' => 'graficoAlunos',
        'width' => 500,
        'height' => 500
    ]
);

$totalAlunos = $ativos + $inativos;


/*  GRÁFICO DE PATRIMÔNIOS*/

$objPatrimonio = new Patrimonio();

$statusPatrimonio = $objPatrimonio->TotalPatrimonioStatus();


$patrimoniosDisponiveis = 0;
$patrimoniosEmprestados = 0;

foreach ($statusPatrimonio as $status) {

    if ($status["status"] == "Disponível") {
        $patrimoniosDisponiveis = $status["total"];
    }

    if ($status["status"] == "Emprestado") {
        $patrimoniosEmprestados = $status["total"];
    }
}

$dataPatrimonio = [
    'labels' => ['Disponíveis', 'Emprestados'],
    'datasets' => [[
        'data' => [
            $patrimoniosDisponiveis,
            $patrimoniosEmprestados
        ],
        'backgroundColor' => ['#6A46F5', '#112B6D'],
        'borderColor' => '#ffffff',
        'borderWidth' => 3
    ]]
];

$graficoPatrimonio = new ChartJS(
    'doughnut',
    $dataPatrimonio,
    $options,
    [
        'id' => 'graficoPatrimonio',
        'width' => 500,
        'height' => 500
    ]
);

$totalPatrimonio =
    $patrimoniosDisponiveis +
    $patrimoniosEmprestados;


/* GRÁFICO DE EMPRÉSTIMOS */

$statusEmprestimos = $objEmprestimo->TotalEmprestimosPrazo();

$emprestimosEmDia = 0;
$emprestimosAtrasados = 0;

foreach ($statusEmprestimos as $status) {

    if ($status["situacao"] == "Em dia") {
        $emprestimosEmDia = $status["total"];
    }

    if ($status["situacao"] == "Atrasado") {
        $emprestimosAtrasados = $status["total"];
    }
}

$dataEmprestimos = [
    'labels' => ['Em dia', 'Atrasados'],
    'datasets' => [[
        'data' => [
            $emprestimosEmDia,
            $emprestimosAtrasados
        ],
        'backgroundColor' => ['#6A46F5', '#112B6D'],
        'borderColor' => '#ffffff',
        'borderWidth' => 3
    ]]
];

$graficoEmprestimos = new ChartJS(
    'doughnut',
    $dataEmprestimos,
    $options,
    [
        'id' => 'graficoEmprestimos',
        'width' => 500,
        'height' => 500
    ]
);

$totalEmprestimos =
    $emprestimosEmDia +
    $emprestimosAtrasados;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../../public/css/styles.css">
</head>
<body>
    <div class="container">
        <main class="dashboard-container">
            <div class="pagina-interna">
                <aside class="menu-lateral">
                    <div class="menu-logo">
                        <a href="dashboard.php">
                            <img src="../../public/img/1.png" alt="Logo BeepYou" class="imgtopo">
                        </a>
                    </div>             
                    <div class="menu-usuario">
                        <span class="menu-bemvindo"> Bem-vindo, </span>
                        <br>
                        <strong><?php echo $_SESSION['usuarios']; ?></strong>
                        <br>
                        <br>
                    </div>
                    <a href="dashboard.php" class="menu-item">
                        <img src="../../public/img/2dashboard.png" alt="" class="menu-icone"> 
                        <span class="menu-texto"> Início </span>
                    </a>
                     <div class="menu-grupo"> <div class="menu-item menu-toggle"> <img src="../../public/img/2student.png" alt="" class="menu-icone"> <span class="menu-texto">Alunos</span> </div>
                        <div class="submenu">
                            <a href="alunos.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Visualizar alunos</span>
                            </a>

                            <a href="cadastroAluno.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Cadastrar aluno</span>
                            </a>
                        </div>

                        </div> <div class="menu-grupo"> <div class="menu-item menu-toggle"> <img src="../../public/img/2object.png" alt="" class="menu-icone"> <span class="menu-texto">Patrimônio</span> </div>
                        <div class="submenu">
                            <a href="patrimonio.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Visualizar patrimônios</span>
                            </a>

                            <a href="cadastropatrimonio.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Cadastrar patrimônio</span>
                            </a>
                        </div>

                        </div> <div class="menu-grupo"> <div class="menu-item menu-toggle"> <img src="../../public/img/2rental.png" alt="" class="menu-icone"> <span class="menu-texto">Empréstimos</span> </div>
                        <div class="submenu">
                            <a href="emprestimos.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Visualizar empréstimos</span>
                            </a>

                            <a href="cadastroemprestimo.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Cadastrar empréstimo</span>
                            </a>
                        </div>
                    </div>              

                    <?php if ($_SESSION["tipo_usuario"] == "Administrativo") { ?>
                        <a href="../views/config.php" class="menu-item">
                            <img src="../../public/img/2settings.png" alt="" class="menu-icone">
                            <span class="menu-texto"> Configurações </span>
                        </a>
                    <?php } ?>
                    <a href="../controllers/logoff.php" class="menu-item menu-sair">
                        <img src="../../public/img/exit.png"  alt="" class="menu-icone">
                        <span class="menu-texto"> Sair </span>
                    </a>
                </aside>
                <main class="conteudo-pagina">
                    <button id="btnMenu" class="btn-menu" aria-label="Abrir menu">
                        ☰
                    </button>
                    <div class="area-busca">
                        <form class="pesquisa" action="../controllers/buscar.php" method="GET">
                            <div class="campo-pesquisa">
                                <input type="search" id="pesquisa" name="pesquisa" class="buscar" 
                                placeholder="Digite o que deseja pesquisar" required>
                                <button class="btn-lupa" type="submit">  
                                    <img src="../../public/img/iconpesquisa.png" class="icone-lupa" alt="Pesquisar">
                                </button>
                            </div>
                        </form>
                    </div>
                    <section class="table-box dashboard-estatisticas">                  
                        <div class="cards-estatisticas">                      
                            <div class="card-estatistica">
                                <h4>Alunos</h4>
                                <div class="grafico-card">
                                    <?php echo $graficoAlunos; ?>
                                </div>
                            </div>
                            <div class="card-estatistica">
                                <h4> Patrimônios</h4>
                                <div class="grafico-card">
                                    <?php echo $graficoPatrimonio; ?>
                                </div>
                            </div>
                            <div class="card-estatistica">
                                <h4> Empréstimos </h4>
                                <div class="grafico-card">
                                    <?php echo $graficoEmprestimos; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="table-dashboard">
                        <table> <caption>Últimos empréstimos realizados</caption>
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Item</th>
                                    <th>Patrimônio</th>
                                    <th>Retirada</th>
                                    <th>Devolução</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($ultimosEmprestimos) > 0){ ?>
                                <?php foreach($ultimosEmprestimos as $emprestimo){ ?>
                                <tr>
                                    <td><?php echo $emprestimo["alunos"]; ?></td>
                                    <td><?php echo $emprestimo["item"]; ?></td>
                                    <td><?php echo $emprestimo["codigo"]; ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($emprestimo["data_emprestimo"]));?></td>
                                    <td><?php echo date("d/m/Y", strtotime($emprestimo["data_prevista"]));?></td>
                                    <td><span><?php echo $emprestimo["status"]; ?></span></td>
                                </tr>   
                                <?php } ?>
                                <?php } else { ?>
                                <tr>
                                    <td colspan="6"> Nenhum empréstimo realizado. </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
        </main>
    </div>
    <script src="../../public/js/configuracoes.js?v=2"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="../../vendor/ejdamm/chart.js-php/js/Chart.min.js"></script>
    <script src="../../vendor/ejdamm/chart.js-php/js/driver.js"></script>

    <script>
    (function() {
        loadChartJsPhp();

        setTimeout(function()
        {
            atualizarCoresGraficos();
        }, 100);
    })();
    </script>
    <button id="btnVoltarTopo" title="Voltar ao topo" aria-label="Voltar ao topo">
        <img src="../../public/img/voltar.png" alt="Voltar ao topo">
    </button>
</body>
</html>