<?php

class User
{
    private string $nome;
    private string $login;
    private string $password;
    private string $telefone;
    private string $descricao;
    private $pdo;

    public function __construct()
    {
        include_once("Connect.php");
        $conexao = new Connect();
        $this->pdo = $conexao->conectarBanco();
    }


    public function ValidarLogin($email, $senha)
    {
        $this->login = $email;
        $this->password = $senha;

        $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha AND ativo = TRUE";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':email', $this->login);
        $stmt->bindParam(':senha', $this->password);

        $stmt->execute();
        $vetor = $stmt->fetch(PDO::FETCH_ASSOC);

        if(isset($vetor["email"]) && isset($vetor["senha"])) 
        {
            $_SESSION["tipo_usuario"] = $vetor["tipo_usuario"];
            $_SESSION["id"] = $vetor["id_usuarios"];
            $_SESSION["id_usuarios"] = $vetor["id_usuarios"];
            $_SESSION["usuarios"] = $vetor["nome"];
            $_SESSION["email"] = $vetor["email"];

            /* primeiro acesso */
            $_SESSION["primeiro_acesso"] = $vetor["primeiro_acesso"];
            return true;
        }
        return false;
    }

   // SE EU QUISER EDITAR O PERFIL DO "SECRETARIO"
    public function EditarPerfil($nome, $email, $telefone)
    {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone WHERE email = :email;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);

        return $stmt->execute();    
    
        $usuarioLogado = $_SESSION['usuario'] ?? 'usuario';
     
        $pastaDestino = __DIR__ . "/../../fotos_perfil/";

        if (!is_dir($pastaDestino)) 
        {
            mkdir($pastaDestino, 0777, true);
        }

        if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] != 0)
        {
            return false;
        }

        $arquivo = $_FILES['arquivo'];

        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($extensao, $permitidas)) 
        {
            return false;
        }

        $usuarioLimpo = preg_replace('/[^a-zA-Z0-9_\-]/', '', $usuarioLogado);
       
        $novoNomeArquivo = md5($usuarioLimpo) . "." . $extensao;

        $caminhoArquivo = $pastaDestino . $novoNomeArquivo;
        $url = "../../fotos_perfil/".$novoNomeArquivo; 

        if (move_uploaded_file($arquivo['tmp_name'], $caminhoArquivo)) 
        {
            $sql = "UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone, descricao = :descricao, url = :url WHERE email = :email;";                   
              
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);        
        $stmt->bindParam(':url', $url);

        if ($stmt->execute()) 
        {
            echo '<script>
                alert("Perfil atualizado com sucesso!");
                window.location.href="../views/perfil.php";
            </script>';
        }
        else 
        {
        echo "Erro ao atualizar contato.";
        }
            return $novoNomeArquivo;
        }

        return false;
    }

    public function CadastrarUsuario($nome, $email, $senha, $tipo_usuario)
    {
        if (!in_array($tipo_usuario, ['Leitor', 'Administrativo'], true)) {
            return false;
        }

        $sql = "INSERT INTO usuarios (nome, email, senha, tipo_usuario, primeiro_acesso, ativo)
                VALUES (:nome, :email, :senha, :tipo_usuario, :primeiro_acesso, :ativo)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":nome", $nome);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":senha", $senha);
        $stmt->bindValue(":tipo_usuario", $tipo_usuario);
        $stmt->bindValue(":primeiro_acesso", true);
        $stmt->bindValue(":ativo", true);

        return $stmt->execute();
    }
 

    

    public function ListarUmUsuario($email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);        
        if($stmt->execute())
        {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return($result);
        }
        else
        {
            return (FALSE);
        }
       
    }


    public function ListarTodosUsuarios()
    {
        $sql = "SELECT * FROM usuarios WHERE tipo_usuario IN ('Leitor', 'Administrativo')
                ORDER BY id_usuarios DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
      
    
    public function BuscarUsuarioPorId($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id_usuarios = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id",$id);

        if($stmt->execute())
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }


    public function EditarPerfilAluno($id, $nome, $email, $telefone)
    {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone  WHERE id_usuarios = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":nome", $nome);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":telefone", $telefone);
        $stmt->bindValue(":id", $id);

        return $stmt->execute();
    }

    public function EmailExisteOutroUsuario($email, $id)
    {
        $sql = "SELECT id_usuarios FROM usuarios WHERE LOWER(email) = LOWER(:email) AND id_usuarios <> :id LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function EditarUsuario($id, $nome, $email, $tipo_usuario)
    {
        if (!in_array($tipo_usuario, ['Leitor', 'Administrativo'], true)) {
            return false;
        }

        $sql = "UPDATE usuarios SET nome = :nome, email = :email, tipo_usuario = :tipo_usuario
                WHERE id_usuarios = :id AND tipo_usuario IN ('Leitor', 'Administrativo')";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":nome", $nome);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":tipo_usuario", $tipo_usuario);
        $stmt->bindValue(":id", $id);

        return $stmt->execute();
    }
  


    public function InativarUsuario($id)
    {
        $sql = "UPDATE usuarios SET ativo = FALSE WHERE id_usuarios = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id",$id);
        return $stmt->execute();
    }


    public function AtivarUsuario($id)
    {

        $sql = "UPDATE usuarios SET ativo = TRUE WHERE id_usuarios = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id",$id);

        return $stmt->execute();
    }



    public function AlterarSenha($id, $senha)
    {
        $sql = "UPDATE usuarios SET senha = :senha,  primeiro_acesso = FALSE WHERE id_usuarios = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":senha", $senha);
        $stmt->bindValue(":id", $id);

        return $stmt->execute();
    }


    public function VerificarEmail($email)
    {
        $sql = "SELECT id_usuarios, nome, email FROM usuarios WHERE email = :email  AND ativo = TRUE";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }   
    

    public function CriarTokenRecuperacao($email, $token)
    {
        $sql = "UPDATE usuarios SET token_recuperacao = :token WHERE email = :email";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":token", $token);
        $stmt->bindValue(":email", $email);

        return $stmt->execute();
    }


    public function BuscarPorToken($token)
    {
        $sql = "SELECT id_usuarios, nome, email FROM usuarios  WHERE token_recuperacao = :token  AND ativo = TRUE";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":token", $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function LimparTokenRecuperacao($id)
    {
        $sql = "UPDATE usuarios SET token_recuperacao = NULL WHERE id_usuarios = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }

    
    public function CadastrarUsuarioAluno($nome, $email, $senha)
    {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo_usuario, primeiro_acesso, ativo)
        VALUES (:nome, :email, :senha, 'Aluno', TRUE, TRUE) RETURNING id_usuarios";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":nome", $nome);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":senha", $senha);

        if ($stmt->execute())
        {
            return $stmt->fetchColumn();
        }
        return false;
    }
}
?>



  