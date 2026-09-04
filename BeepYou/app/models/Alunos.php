<?php
class Alunos
{
    private string $matricula;
    private string $nome;
    private string $email;
    private string $telefone;
    private string $curso;
    private string $usuarios_idfk;
    private $pdo;

    public function __construct()
    {
        include_once("Connect.php");
        $conexao = new Connect();
        $this->pdo = $conexao->conectarBanco();
    }


    public function CadastrarAlunos($matricula, $nome, $telefone, $email, $curso, $usuarios_idfk)
    {

        $this->matricula = $matricula;
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->curso = $curso;
        $this->usuarios_idfk = $usuarios_idfk;
    
        $sql = "INSERT INTO alunos (matricula, nome, email, telefone, curso, usuarios_idfk) 
        VALUES (:matricula, :nome, :email, :telefone, :curso, :usuarios_idfk);";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telefone', $this->telefone);
        $stmt->bindParam(':matricula', $this->matricula);
        $stmt->bindParam(':curso', $this->curso);
        $stmt->bindParam(':usuarios_idfk', $this->usuarios_idfk);
        
        if($stmt->execute())
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    


    public function EditarAlunos($id_alunos, $nome, $email, $matricula, $telefone, $curso)
    {
        $sql = "UPDATE alunos SET matricula = :matricula, email = :email, telefone = :telefone, nome = :nome, curso = :curso WHERE id_alunos = :id";          

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':matricula', $matricula);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':id', $id_alunos);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':curso', $curso);

        if($stmt->execute())
        {
            return(TRUE);
        }
            else
        {
           return(FALSE);
        }
    }
    


    public function ExcluirAluno($id_alunos)
    {
        $sql = "DELETE FROM alunos WHERE id_alunos = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id_alunos);

        return $stmt->execute();
    }



    public function AlunosRecentes($usuarios_idfk)
    {
        $sql = "SELECT * FROM alunos WHERE usuarios_idfk = :usuarios ORDER BY id_alunos DESC LIMIT 5";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":usuarios", $usuarios_idfk);

        if ($stmt->execute()) 
        {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
            return [];
    }

   
    public function AtivarAluno($id)
    {
        $sql = "UPDATE alunos SET ativo = TRUE WHERE id_alunos = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

 

    public function InativarAluno($id_alunos)
    {
        $sql = "UPDATE alunos SET ativo = false WHERE id_alunos = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id", $id_alunos);

        return $stmt->execute();
    }



    public function BuscarAlunoPorId($id)
    {
        $sql = "SELECT * FROM alunos WHERE id_alunos = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        if($stmt->execute())
        {
        return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }


    public function TotalAlunos()
    {
        $sql = "SELECT COUNT(*) AS total FROM alunos";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)["total"];
    }

    

   public function TotalAlunosStatus()
    {
        $sql = "SELECT SUM(CASE WHEN ativo = TRUE THEN 1 ELSE 0 END) AS ativos,
        SUM(CASE WHEN ativo = FALSE THEN 1 ELSE 0 END) AS inativos FROM alunos";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function ListarAlunosAtivos()
    {
        $sql = "SELECT * FROM alunos WHERE ativo = TRUE ORDER BY nome ASC";

        $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute())
        {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    
    
    public function EmprestimoAtivo($id_alunos)
    {
        $sql = "SELECT COUNT(*) FROM emprestimos WHERE alunos_idfk = :id_alunos AND status = 'Emprestado'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id_alunos", $id_alunos);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }


    public function PossuiEmprestimoAtivo($id_alunos)
    {
        $sql = "SELECT id_emprestimos FROM emprestimos WHERE alunos_idfk = :id_alunos
        AND usuarios_idfk = :usuarios AND status = 'Emprestado'LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":id_alunos", $id_alunos);
        $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"]);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function ListarTodosAlunos()
    {
        $sql = "SELECT alunos.*, EXISTS ( SELECT 1 FROM emprestimos WHERE emprestimos.alunos_idfk = alunos.id_alunos
        AND emprestimos.usuarios_idfk = :usuarios AND emprestimos.status = 'Emprestado') AS possui_emprestimo FROM alunos
        ORDER BY alunos.id_alunos ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":usuarios", $_SESSION["id_usuarios"]);

        if($stmt->execute())
        {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }


    public function BuscarAlunoPorEmail($email)
    {
        $sql = "SELECT * FROM alunos  WHERE email = :email LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":email", $email);

        if ($stmt->execute())
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }


    public function BuscarDadosPerfilAluno($idAluno)
    {
        $sql = "SELECT alunos.id_alunos, alunos.nome, alunos.email, alunos.telefone, alunos.matricula,
        alunos.curso, alunos.usuarios_idfk, usuarios.email AS email_usuario, usuarios.telefone AS telefone_usuario
        FROM alunos INNER JOIN usuarios ON usuarios.id_usuarios = alunos.usuarios_idfk
        WHERE alunos.id_alunos = :id LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $idAluno);

        if ($stmt->execute())
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }
}
?>

