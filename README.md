# BeepYou

Sistema web desenvolvido para gerenciamento de **empréstimos de livros e notebooks em escolas**.

O BeepYou foi desenvolvido para substituir o processo manual de empréstimos, permitindo que administradores cadastrem alunos e patrimônios, enquanto os alunos podem consultar patrimônios disponíveis, realizar empréstimos e devoluções e acompanhar seu histórico.

## Tecnologias utilizadas

* PHP
* HTML5
* CSS
* JavaScript
* PostgreSQL
* Apache HTTP Server (Apache24)
* PDO
* Chart.js
* QR Code

## Funcionalidades

### Administração

* Cadastro de alunos
* Cadastro de patrimônios
* Gerenciamento de patrimônios
* Gerenciamento de empréstimos
* Consulta de alunos
* Consulta de patrimônios
* Dashboard com informações do sistema
* Busca de informações
* Relatórios

### Aluno

* Login
* Primeiro acesso
* Alteração de senha
* Consulta de perfil
* Consulta de patrimônios disponíveis
* Empréstimo através de QR Code
* Devolução
* Histórico de empréstimos
* Visualização das datas de empréstimo e devolução

## Interface

O sistema possui uma interface específica para alunos, desenvolvida pensando principalmente em dispositivos móveis.

Fluxo principal:

Início → Patrimônios disponíveis → Empréstimo → Histórico → Perfil

## Segurança

* Sessões PHP para controle de acesso
* Controle de usuários por tipo
* PDO para conexão com PostgreSQL
* `htmlspecialchars()` para exibição segura de dados
* Controle de acesso às páginas
* Fluxo de alteração de senha no primeiro acesso

# Instalação

## Instalação do Apache24

O BeepYou utiliza o Apache HTTP Server para executar o sistema localmente através do `localhost`.

### 1. Baixar o Apache24

Baixe o Apache24 para Windows e extraia os arquivos.

Coloque a pasta `Apache24` diretamente no disco `C:`.

Caminho esperado: `C:\Apache24`

### 2. Abrir o CMD como administrador

Abra o Prompt de Comando (CMD) como administrador.

Execute:

```cmd
cd C:\Apache24\bin
```

### 3. Instalar o Apache como serviço

Execute:

```cmd
httpd.exe -k install
```

### 4. Iniciar o Apache

Execute:

```cmd
httpd.exe -k start
```

### 5. Parar o Apache

Caso seja necessário parar o servidor:

```cmd
httpd.exe -k stop
```

### 6. Reiniciar o Apache

Para reiniciar o servidor:

```cmd
httpd.exe -k restart
```

### 7. Colocar o projeto no Apache

O projeto deve ser colocado dentro da pasta `htdocs`.

Caminho:

`C:\Apache24\htdocs`

Crie ou copie a pasta do projeto para:

`C:\Apache24\htdocs\BeepYou`

A estrutura principal ficará semelhante a:

```text
C:\Apache24\htdocs\BeepYou
├── controllers
├── models
├── public
├── views
└── index.html
```

### 8. Acessar o sistema

Com o Apache iniciado, abra o navegador e acesse:

`http://localhost/BeepYou`

## Instalação do PostgreSQL

O BeepYou utiliza o PostgreSQL como sistema de gerenciamento do banco de dados.

### 1. Baixar o PostgreSQL

Baixe o PostgreSQL para Windows através da página oficial:

https://www.postgresql.org/download/windows/

Utilize o instalador para Windows.

### 2. Instalar o PostgreSQL

Durante a instalação, recomenda-se instalar:

* PostgreSQL Server
* pgAdmin 4
* Command Line Tools

Durante a instalação:

* Defina uma senha para o usuário `postgres`
* Mantenha a porta padrão `5432`
* Finalize a instalação

### 3. Criar o banco de dados

O banco de dados completo do BeepYou está anexado ao próprio repositório.

Após instalar o PostgreSQL, utilize o arquivo do banco presente no projeto para criar e configurar o banco de dados.

Nome do banco de dados:

`BeepYou`

### 4. Configurar a conexão

A configuração da conexão com o banco de dados está localizada no arquivo:

`models/Connect.php`

Configure os dados de acordo com a instalação do PostgreSQL:

```php
$this->host = "localhost";
$this->dbname = "BeepYou";
$this->password = "SUA_SENHA";
$this->user = "postgres";
$this->port = "5432";
```

Substitua `SUA_SENHA` pela senha definida durante a instalação do PostgreSQL.

Não publique senhas reais ou outras credenciais no GitHub.

# Estrutura do projeto

A estrutura principal do projeto é organizada da seguinte forma:

```text
BeepYou
│
├── controllers
│   ├── ...
│
├── models
│   ├── Alunos.php
│   ├── Connect.php
│   ├── Emprestimo.php
│   ├── Patrimonio.php
│   ├── User.php
│   └── ...
│
├── public
│   ├── css
│   ├── img
│   └── ...
│
├── views
│   ├── dashboard.php
│   ├── cadastroemprestimo.php
│   ├── historico.php
│   ├── perfil.php
│   └── ...
│
├── index.html
└── README.md
```

# Executando o BeepYou

Após realizar a instalação e configuração:

1. Inicie o PostgreSQL.
2. Inicie o Apache24.
3. Verifique se o projeto está dentro de `C:\Apache24\htdocs`.
4. Configure a conexão com o banco em `models/Connect.php`.
5. Abra o navegador.
6. Acesse `http://localhost/BeepYou`.

# Sobre o projeto

O BeepYou é um sistema web desenvolvido para facilitar e modernizar o gerenciamento de empréstimos de livros e notebooks em escolas.

O sistema busca substituir processos manuais, facilitando o controle dos patrimônios, empréstimos, devoluções e informações dos alunos.
