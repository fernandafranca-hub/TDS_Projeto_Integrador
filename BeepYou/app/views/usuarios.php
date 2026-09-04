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

include_once("../models/User.php");

$objUsuario = new User();

$usuarios = $objUsuario->ListarTodosUsuarios();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - USUARIOS</title>

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
                    <div class="menu-grupo">
                        <div class="menu-item menu-toggle">
                            <a href="alunos.php" class="menu-link">
                                <img src="../../public/img/2student.png" alt="" class="menu-icone">
                                <span class="menu-texto"> Alunos  </span>
                            </a>
                            <span class="menu-seta"></span>
                        </div>
                        <div class="submenu">
                            <a href="cadastroAluno.php" class="submenu-item">
                                <span class="submenu-linha">└</span>
                                <span> Cadastrar aluno</span>
                            </a>
                        </div>
                    </div>
                    <div class="menu-grupo">
                        <div class="menu-item menu-toggle">
                            <a href="patrimonio.php" class="menu-link">
                                <img src="../../public/img/2object.png" alt="" class="menu-icone">
                                <span class="menu-texto">Patrimônio</span>
                            </a>
                            <span class="menu-seta"></span>
                        </div>
                        <div class="submenu">
                            <a href="cadastropatrimonio.php" class="submenu-item">
                                <span class="submenu-linha"> └ </span>
                                <span> Cadastrar patrimônio </span>
                            </a>
                        </div>
                    </div>

                    <div class="menu-grupo">
                        <div class="menu-item menu-toggle">
                            <a href="emprestimos.php" class="menu-link">
                                <img src="../../public/img/2rental.png"  alt=""  class="menu-icone">
                                <span class="menu-texto"> Empréstimos </span>
                            </a>
                            <span class="menu-seta"></span>
                        </div>
                        <div class="submenu">
                            <a href="cadastroemprestimo.php" class="submenu-item">
                                <span class="submenu-linha"> └ </span>
                                <span> Cadastrar empréstimo </span>
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
                                    <img src="../../public/img/usuario.png" class="titulo-icone">Usuários</h2>
                                    <a href="cadastroUsuario.php" class="btn-adicionar"> Adicionar usuário</a>
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($usuarios as $usuario){ ?>
                                    <tr>
                                        <td><a href="../controllers/visualizarUsuario.php?id=<?php echo $usuario['id_usuarios']; ?>" 
                                            class="link-visualizar">
                                            <?php echo $usuario["nome"]; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $usuario["email"]; ?>
                                        </td>
                                        <td><?php echo $usuario["tipo_usuario"]; ?>
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox"
                                                <?php echo ($usuario["ativo"]) ? "checked" : ""; ?>
                                                onclick="return confirmarInativacaoUsuario(this, <?php echo $usuario['id_usuarios']; ?>)">
                                                <span class="slider"></span>
                                            </label>
                                        </td>
                                    </tr>
                                    <?php } ?>
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
