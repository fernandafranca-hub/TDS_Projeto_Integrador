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
$objEmprestimo = new Emprestimo();
$emprestimos = $objEmprestimo->ListarTodosEmprestimos();

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - EMPRÉSTIMOS</title>

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

                    <a href="dashboard.php"class="menu-item">
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
                        <section class="table-box">
                            <div class="table-header">
                                <h2 class="titulo-pagina">
                                <img src="../../public/img/1emprestimo.png" class="titulo-icone">Empréstimos</h2>
                                <a href="cadastroemprestimo.php" class="btn-adicionar"> + Novo empréstimo</a>
                            </div>
                            <table class="tabela-emprestimos">                                
                                <colgroup>
                                    <col class="col-aluno">
                                    <col class="col-patrimonio">
                                    <col class="col-data">
                                    <col class="col-data-prevista">
                                    <col class="col-situacao">
                                    <col class="col-acoes">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>Aluno</th>
                                        <th>Patrimônio</th>
                                        <th>Data empréstimo</th>
                                        <th>Data prevista</th>
                                        <th>Situação</th>
                                        <th>Ações</th>
                                    </tr>                                
                                </thead>
                                <tbody>
                                        <?php foreach($emprestimos as $emprestimo): ?>
                                        <?php
                                            $dataPrevista = strtotime($emprestimo["data_prevista"]);
                                            $hoje = strtotime(date("Y-m-d"));
                                            $atrasado = $dataPrevista < $hoje;
                                        ?>
                                    <tr class="efect <?php echo $atrasado ? 'linha-atrasada' : ''; ?>">
                                        <td><?php echo htmlspecialchars($emprestimo["nome_aluno"]); ?></td>
                                        <td>
                                            <a href="../controllers/visualizarEmprestimo.php?id=<?php echo $emprestimo['id_emprestimos']; ?>"
                                            class="link-visualizar">
                                                <?php echo htmlspecialchars($emprestimo["nome_patrimonio"]); ?>
                                            </a>
                                        </td>
                                        <td><?php echo date("d/m/Y", strtotime($emprestimo["data_emprestimo"])); ?></td>
                                        <td><?php echo date("d/m/Y", strtotime($emprestimo["data_prevista"])); ?></td>
                                        <td>
                                            <?php if($atrasado): ?>
                                            <span class="status-emprestimo atrasado">🔴 Atrasado </span>
                                            <?php else: ?>
                                            <span class="status-emprestimo em-dia">🟢 Em dia</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="acoes-emprestimo">
                                                <form action="../controllers/devolverEmprestimo.php" method="POST" class="form-devolver" onsubmit="return confirmarDevolucao(this);">
                                                    <input type="hidden" name="id_emprestimo" value="<?php echo $emprestimo['id_emprestimos']; ?>">
                                                    <button type="submit" class="btn-devolver">Devolver </button>
                                                </form>
                                                <?php if($atrasado): ?>
                                                <button type="button" class="btn-aviso" onclick="avisarEmprestimo(<?php echo $emprestimo['id_emprestimos']; ?>)">✉ Avisar</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </section>
                    </main>
                </div>
            </main>
        </div>
    <script src="../../public/js/configuracoes.js?v=2"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <button id="btnVoltarTopo" title="Voltar ao topo" aria-label="Voltar ao topo">
        <img src="../../public/img/voltar.png" alt="Voltar ao topo">
    </button>
</body>
</html>