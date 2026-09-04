<?php
$token = $_GET["token"] ?? "";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - Nova Senha</title>
    <link rel="stylesheet" type="text/css" href="../../public/css/styles.css">
</head>
<body>
    <div class="container">
        <main class="login-container">
            <div class="login-card">
                <header class="logo-area">
                    <img src="../../public/img/1.png" alt="LogoBeepYou">
                    <h2>Nova Senha</h2>
                </header>
                <form action="../controllers/novasenha.php" method="POST" class="form-login">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <label for="senha"> Nova senha </label>
                    <input type="password"  id="senha" name="senha" class="buscar" placeholder="Digite sua nova senha" autocomplete="new-password"
                    pattern="^(?=.*[A-Z])(?=.*\d).{8,}$" title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um número." required>
                    <label for="confirmar"> Confirmar senha</label>
                    <input type="password" id="confirmar" name="confirmar" class="buscar"
                    placeholder="Confirme sua senha" autocomplete="new-password" required>
                    <div class="options">
                        <a href="../../index.html">Voltar para o login </a>
                    </div>
                    <button type="submit" class="btn-entrar">Alterar Senha </button>
                </form>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>