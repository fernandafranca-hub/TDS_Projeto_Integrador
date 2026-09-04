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


if(!isset($_SESSION["usuario_visualizar"]))
{
    header("Location: usuarios.php");
    exit();
}

$usuario = $_SESSION["usuario_visualizar"];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - VISUALIZAR USUARIO</title>

    <link rel="stylesheet" type="text/css" href="../../public/css/styles.css">
</head>

<<body>
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
                                        <img src="../../public/img/usuario.png" class="titulo-icone">Visualizar Usuário</h2>
                                        <div class="acoes-topo">
                                            <a href="usuarios.php">
                                                <img src="../../public/img/iconvoltar.png" class="icon" title="Voltar"></a>
                                                <a href="../controllers/editarUsuario.php?id=<?php echo $usuario['id_usuarios']; ?>">
                                                <img src="../../public/img/iconeditar.png" class="icon" title="Editar"></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-cadastro">
                                    <div class="dados-aluno">
                                        <div class="campo-visualizacao">
                                            <label>Nome</label>
                                            <p><?php echo $usuarios["nome"]; ?></p>
                                        </div>
                                        <div class="campo-visualizacao">
                                            <label>E-mail</label>
                                            <p><?php echo $usuarios["email"]; ?></p>
                                        </div>
                                        <div class="campo-visualizacao">
                                            <label>Tipo de Usuário</label>
                                            <p><?php echo $usuarios["tipo_usuario"]; ?></p>
                                        </div>
                                        <div class="campo-visualizacao">
                                            <label>Status</label>
                                            <p><?php echo ($usuarios["ativo"]) ? "Ativo" : "Inativo";?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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