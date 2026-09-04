<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeepYou - Recuperar Senha</title>
    <link rel="stylesheet" type="text/css" href="../../public/css/styles.css">
</head>
<body>
    <div class="container">
        <main class="login-container">
            <div class="login-card">
                <header class="logo-area">
                    <img src="../../public/img/1.png" alt="LogoBeepYou">
                    <h2>Bem-vindo ao BeepYou</h2>
                </header>
                <h2>Recuperar Senha</h2>
                <form action="../controllers/recuperarSenha.php" method="POST" class="form-login">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="buscar" placeholder="seu@email.com" autocomplete="email" required>
                    <button type="submit" class="btn-entrar"> Enviar link </button>
                    <div class="options">
                        <a href="../../index.html">Voltar para o login </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/js/configuracoes.js"></script>
</body>
</html>