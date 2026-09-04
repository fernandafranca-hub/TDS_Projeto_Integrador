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
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - BUSCA</title>

    <link rel="stylesheet" type="text/css" href="../../public/css/styles.css">
</head>

<body>
<div class="container">
    <main class="dashboard-container">
        <div class="pagina-interna">
            <aside class="menu-lateral">
                <div class="menu-logo">
                    <a href="../views/dashboard.php">
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
                            <a href="../views/alunos.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Visualizar alunos</span>
                            </a>

                            <a href="../views/cadastroAluno.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Cadastrar aluno</span>
                            </a>
                        </div>

                        </div> <div class="menu-grupo"> <div class="menu-item menu-toggle"> <img src="../../public/img/2object.png" alt="" class="menu-icone"> <span class="menu-texto">Patrimônio</span> </div>
                        <div class="submenu">
                            <a href="../views/patrimonio.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Visualizar patrimônios</span>
                            </a>

                            <a href="../views/cadastropatrimonio.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Cadastrar patrimônio</span>
                            </a>
                        </div>

                        </div> <div class="menu-grupo"> <div class="menu-item menu-toggle"> <img src="../../public/img/2rental.png" alt="" class="menu-icone"> <span class="menu-texto">Empréstimos</span> </div>
                        <div class="submenu">
                            <a href="../views/emprestimos.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span>Visualizar empréstimos</span>
                            </a>

                            <a href="../views/cadastroemprestimo.php" class="submenu-item">
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
                                <h2 class="titulo-pagina">Resultado da Busca</h2>                    
                            </div>                    
                            <?php if(empty($resultados)){ ?>
                            <h3>Nenhum resultado encontrado.</h3>
                            <?php } else { ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Resultado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($resultados as $item){ ?>
                                    <tr>
                                        <td><?php echo $item["tipo"]; ?></td>
                                        <td>
                                            <a href="<?php echo $item['pagina']; ?>?id=<?php echo $item['id']; ?>" class="resultado-link">
                                                <?php echo $item["resultado"]; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
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