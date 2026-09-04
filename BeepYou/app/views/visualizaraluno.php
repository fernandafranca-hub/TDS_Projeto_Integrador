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


if(!isset($_SESSION["aluno_visualizar"]))
{
    header("Location: alunos.php");
    exit();
}

$aluno = $_SESSION["aluno_visualizar"];
$emprestimos = $_SESSION["emprestimos_aluno"] ?? [];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - VISUALIZAR ALUNOS</title>

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
                                <div class="titulo-area">
                                    <h2 class="titulo-pagina">                                                                   
                                    <img src="../../public/img/1aluno.png" class="titulo-icone">
                                    Visualizar Aluno</h2>
                                    <div class="acoes-topo">                                         
                                        <a href="alunos.php">
                                            <span class="icon-acao icon-voltar" title="Voltar"></span>
                                        </a>
                                        <a href="../controllers/editarAluno.php?id=<?php echo $aluno['id_alunos']; ?>">
                                            <span class="icon-acao icon-editar" title="Editar"></span>
                                        </a>                                       
                                    </div>
                                </div>
                            </div>

                            <div class="form-cadastro">
                                <div class="dados-aluno">
                                    <div class="campo-visualizacao">
                                        <label>Matrícula</label>
                                        <p><?php echo $aluno['matricula']; ?></p>
                                    </div>

                                    <div class="campo-visualizacao">
                                        <label>Nome</label>
                                        <p><?php echo $aluno['nome']; ?></p>
                                    </div>

                                    <div class="campo-visualizacao">
                                        <label>E-mail</label>
                                        <p><?php echo $aluno['email']; ?></p>
                                    </div>

                                    <div class="campo-visualizacao">
                                        <label>Telefone</label>
                                        <p><?php echo $aluno['telefone']; ?></p>
                                    </div>

                                    <div class="campo-visualizacao">
                                        <label>Curso</label>
                                        <p><?php echo $aluno['curso']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="table-box">

                            <div class="table-header">
                                <h2 class="titulo-pagina">
                                <img src="../../public/img/1emprestimo.png" class="titulo-icone">
                                Histórico de Empréstimos </h2>
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Patrimônio</th>
                                        <th>Data Empréstimo</th>
                                        <th>Data Prevista</th>
                                        <th>Data Devolução</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($emprestimos)){ ?>
                                    <?php foreach($emprestimos as $emprestimo) 
                                        { ?>

                                    <tr>
                                        <td><?php echo $emprestimo["nome_patrimonio"]; ?></td>
                                        <td><?php echo date("d/m/Y", strtotime($emprestimo["data_emprestimo"])); ?></td>
                                        <td><?php echo date("d/m/Y", strtotime($emprestimo["data_prevista"])); ?></td>
                                        <td><?php 
                                            if($emprestimo["data_devolucao"]) 
                                            { echo date("d/m/Y", strtotime($emprestimo["data_devolucao"]));
                                            } 
                                            else 
                                            { 
                                                echo "-"; 
                                            } ?> 
                                        </td>
                                        <td><?php echo $emprestimo["status"]; ?></td>
                                    </tr>
                                    <?php 
                                    } ?> 
                                    <?php 
                                    } 
                                    else
                                    { ?>
                                    <tr>
                                        <td> </td>
                                    </tr>
                                    <?php 
                                    } ?>
                                </tbody>
                            </table>
                        </section>
                    </main>
                </div>
            </main>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
    <button id="btnVoltarTopo" title="Voltar ao topo" aria-label="Voltar ao topo">
        <img src="../../public/img/voltar.png" alt="Voltar ao topo">
    </button>
</body>
</html>  
                