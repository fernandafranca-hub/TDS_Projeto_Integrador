<?php

class Emprestimo
{
    private $pdo;


    public function __construct()
    {
        include_once("Connect.php");
        $conexao = new Connect();
        $this->pdo = $conexao->conectarBanco();
    }

    
    public function CadastrarEmprestimo($alunos, $patrimonio, $data_emprestimo, $data_prevista, $observacao, $usuarios) { 

        $sql = "SELECT id_emprestimos FROM emprestimos WHERE patrimonio_idfk = :patrimonio AND status = 'Emprestado' LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue( ":patrimonio", $patrimonio, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            return false;
        }

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            return false;
        }

        $sql = "SELECT id_patrimonio, status, usuarios_idfk FROM patrimonio WHERE id_patrimonio = :patrimonio
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":patrimonio", $patrimonio, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            return false;
        }

        $dadosPatrimonio = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dadosPatrimonio) {
            return false;
        }


        if ($dadosPatrimonio["status"] !== "Disponível") {
            return false;
        }

        if (
            (int)$dadosPatrimonio["usuarios_idfk"] !== (int)$usuarios) {
            return false;
        }


        try {

            $this->pdo->beginTransaction();

            $sql = "UPDATE patrimonio  SET status = 'Emprestado' WHERE id_patrimonio = :patrimonio
            AND usuarios_idfk = :usuarios AND status = 'Disponível'";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":patrimonio", $patrimonio, PDO::PARAM_INT);

            $stmt->bindValue(":usuarios", $usuarios, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                $this->pdo->rollBack();
                return false;
            }

            if ($stmt->rowCount() === 0) {
                $this->pdo->rollBack();
                return false;
            }


            $sql = "INSERT INTO emprestimos(alunos_idfk, patrimonio_idfk, data_emprestimo, data_prevista, observacao, status, usuarios_idfk)
            VALUES(:alunos, :patrimonio, :data_emprestimo, :data_prevista, :observacao, 'Emprestado', :usuarios)
            RETURNING id_emprestimos";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":alunos", $alunos, PDO::PARAM_INT);
            $stmt->bindValue(":patrimonio", $patrimonio, PDO::PARAM_INT);
            $stmt->bindValue(":data_emprestimo", $data_emprestimo);
            $stmt->bindValue( ":data_prevista", $data_prevista);
            $stmt->bindValue(":observacao", $observacao);
            $stmt->bindValue(":usuarios", $usuarios, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                $this->pdo->rollBack();
                return false;
            }
           
            $this->pdo->commit();
            $idEmprestimo = $stmt->fetchColumn();
            return $idEmprestimo;

        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }


    public function BuscarEmprestimoAtivoPorPatrimonio($id_patrimonio)
    {
        $sql = "SELECT emprestimos.*, alunos.nome AS nome_aluno, patrimonio.nome AS nome_patrimonio, patrimonio.codigo AS codigo_patrimonio
        FROM emprestimos INNER JOIN alunos ON alunos.id_alunos = emprestimos.alunos_idfk
        INNER JOIN patrimonio ON patrimonio.id_patrimonio = emprestimos.patrimonio_idfk
        WHERE emprestimos.patrimonio_idfk = :patrimonio AND emprestimos.status = 'Emprestado'
        LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":patrimonio", $id_patrimonio, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }


    public function ListarTodosEmprestimos()
    {        
        if (isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"] === "Aluno" && isset($_SESSION["id_alunos"])) 
            {
                $sql = "SELECT emprestimos.id_emprestimos, alunos.nome AS nome_aluno, patrimonio.nome AS nome_patrimonio, patrimonio.codigo AS codigo_patrimonio, emprestimos.data_emprestimo, 
                emprestimos.data_prevista, emprestimos.data_devolucao, emprestimos.observacao, emprestimos.status, emprestimos.usuarios_idfk
                FROM emprestimos
                INNER JOIN alunos ON alunos.id_alunos = emprestimos.alunos_idfk
                INNER JOIN patrimonio ON patrimonio.id_patrimonio = emprestimos.patrimonio_idfk
                WHERE emprestimos.alunos_idfk = :aluno
                AND emprestimos.status = 'Emprestado'
                ORDER BY CASE WHEN emprestimos.data_prevista < CURRENT_DATE THEN 0 ELSE 1 END, emprestimos.data_prevista ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":aluno", $_SESSION["id_alunos"], PDO::PARAM_INT);
            }

        else 
            {
                $sql = "SELECT emprestimos.id_emprestimos, alunos.nome AS nome_aluno, patrimonio.nome AS nome_patrimonio, patrimonio.codigo AS codigo_patrimonio, emprestimos.data_emprestimo,
                emprestimos.data_prevista, emprestimos.data_devolucao, emprestimos.observacao, emprestimos.status, emprestimos.usuarios_idfk
                FROM emprestimos INNER JOIN alunos ON alunos.id_alunos = emprestimos.alunos_idfk
                INNER JOIN patrimonio ON patrimonio.id_patrimonio = emprestimos.patrimonio_idfk
                WHERE emprestimos.usuarios_idfk = :usuarios AND emprestimos.status = 'Emprestado'
                ORDER BY CASE WHEN emprestimos.data_prevista < CURRENT_DATE THEN 0 ELSE 1 END, emprestimos.data_prevista ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"], PDO::PARAM_INT);
            }


        if ($stmt->execute()) 
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return [];
    }


    public function BuscarEmprestimoPorId($id)
    {
        $sql = "SELECT emprestimos.*, alunos.nome AS nome_aluno, patrimonio.nome AS nome_patrimonio, patrimonio.codigo AS codigo_patrimonio
        FROM emprestimos INNER JOIN alunos ON alunos.id_alunos = emprestimos.alunos_idfk
        INNER JOIN patrimonio ON patrimonio.id_patrimonio = emprestimos.patrimonio_idfk
        WHERE emprestimos.id_emprestimos = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue( ":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) 
            {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        return false;
    }


    public function BuscarEmprestimoPorIdAluno($id, $id_aluno)
    {
        $sql = "SELECT emprestimos.*, alunos.nome AS nome_aluno, patrimonio.nome AS nome_patrimonio, patrimonio.codigo AS codigo_patrimonio
        FROM emprestimos INNER JOIN alunos ON alunos.id_alunos = emprestimos.alunos_idfk
        INNER JOIN patrimonio ON patrimonio.id_patrimonio = emprestimos.patrimonio_idfk
        WHERE emprestimos.id_emprestimos = :id AND emprestimos.alunos_idfk = :aluno";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":aluno", $id_aluno,  PDO::PARAM_INT);

        if ($stmt->execute()) 
            {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        return false;
    }


    public function DevolverEmprestimo($id)
    {
        try {
            $this->pdo->beginTransaction();
            $sql = "SELECT patrimonio_idfk FROM emprestimos WHERE id_emprestimos = :id AND status = 'Emprestado' LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                {
                    $this->pdo->rollBack();
                    return false;
                }

            $emprestimo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$emprestimo) 
                {
                    $this->pdo->rollBack();
                    return false;
                }

            $idPatrimonio = $emprestimo["patrimonio_idfk"];

            $sql = "UPDATE emprestimos SET data_devolucao = CURRENT_DATE, status = 'Devolvido'
            WHERE id_emprestimos = :id AND status = 'Emprestado'";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue( ":id", $id, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                {
                    $this->pdo->rollBack();
                    return false;
                }

            if ($stmt->rowCount() === 0) 
                {
                    $this->pdo->rollBack();
                    return false;
                }

            $sql = "UPDATE patrimonio SET status = 'Disponível' WHERE id_patrimonio = :patrimonio";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":patrimonio", $idPatrimonio, PDO::PARAM_INT);

            if (!$stmt->execute()) 
                {
                    $this->pdo->rollBack();
                    return false;
                }

            $this->pdo->commit();

            return true;

        } 
        catch (Exception $e) 
        {
            if ($this->pdo->inTransaction()) 
                {
                    $this->pdo->rollBack();
                }
            return false;
        }
    }

    public function EditarEmprestimo($id, $alunos, $patrimonio, $data_emprestimo, $data_prevista, $observacao, $status) 
        {
            if ($status === "Devolvido") 
            {
                $sql = "UPDATE emprestimos SET alunos_idfk = :alunos, patrimonio_idfk = :patrimonio, data_emprestimo = :data_emprestimo,
                data_prevista = :data_prevista, observacao = :observacao, status = :status, data_devolucao = CURRENT_DATE WHERE id_emprestimos = :id";
            }
            else 
            {
                $sql = "UPDATE emprestimos SET alunos_idfk = :alunos, patrimonio_idfk = :patrimonio, data_emprestimo = :data_emprestimo, data_prevista = :data_prevista,
                observacao = :observacao, status = :status, data_devolucao = NULL WHERE id_emprestimos = :id";
            }

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":alunos", $alunos, PDO::PARAM_INT);
            $stmt->bindValue(":patrimonio", $patrimonio, PDO::PARAM_INT);
            $stmt->bindValue(":data_emprestimo", $data_emprestimo);
            $stmt->bindValue(":data_prevista", $data_prevista);
            $stmt->bindValue(":observacao", $observacao);
            $stmt->bindValue(":status", $status);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            return $stmt->execute();
        }

    public function ExcluirEmprestimo($id)
        {
            $sql = "DELETE FROM emprestimos WHERE id_emprestimos = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            return $stmt->execute();
        }


    public function UltimosEmprestimos()
    {
       
        if (isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"] === "Aluno" && isset($_SESSION["id_alunos"])) 
        {
            $sql = "SELECT alunos.nome AS alunos, patrimonio.nome AS item, patrimonio.codigo, emprestimos.data_emprestimo, emprestimos.data_prevista, emprestimos.status
            FROM emprestimos INNER JOIN alunos ON emprestimos.alunos_idfk = alunos.id_alunos
            INNER JOIN patrimonio ON emprestimos.patrimonio_idfk = patrimonio.id_patrimonio
            WHERE emprestimos.alunos_idfk = :aluno
            ORDER BY emprestimos.id_emprestimos DESC
            LIMIT 5";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":aluno", $_SESSION["id_alunos"], PDO::PARAM_INT);
        }

        else 
        {

            $sql = "SELECT alunos.nome AS alunos, patrimonio.nome AS item, patrimonio.codigo, emprestimos.data_emprestimo, emprestimos.data_prevista, emprestimos.status
            FROM emprestimos INNER JOIN alunos ON emprestimos.alunos_idfk = alunos.id_alunos
            INNER JOIN patrimonio ON emprestimos.patrimonio_idfk = patrimonio.id_patrimonio
            WHERE emprestimos.usuarios_idfk = :usuarios
            ORDER BY emprestimos.id_emprestimos DESC
            LIMIT 5";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"], PDO::PARAM_INT);
        }

        if ($stmt->execute()) 
        {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }


    public function ListarEmprestimosPorPatrimonio($id_patrimonio)
    {
        $sql = "SELECT emprestimos.*, alunos.nome AS nome_aluno, patrimonio.nome AS nome_patrimonio, patrimonio.codigo AS codigo_patrimonio
        FROM emprestimos INNER JOIN alunos ON emprestimos.alunos_idfk = alunos.id_alunos
        INNER JOIN patrimonio ON emprestimos.patrimonio_idfk = patrimonio.id_patrimonio
        WHERE emprestimos.patrimonio_idfk = :id
        ORDER BY emprestimos.id_emprestimos DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id_patrimonio, PDO::PARAM_INT);

        if ($stmt->execute()) 
        {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }


    public function TotalEmprestimos()
    {
        if (isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"] === "Aluno") 
        {
            $sql = "SELECT COUNT(*) AS total FROM emprestimos WHERE alunos_idfk = :aluno";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":aluno", $_SESSION["id_alunos"], PDO::PARAM_INT);
        }

        else 
        {
            $sql = "SELECT COUNT(*) AS total FROM emprestimos WHERE usuarios_idfk = :usuarios";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)["total"];
    }


    public function TotalEmprestimosStatus()
    {
        if (isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"] === "Aluno") 
        {
            $sql = "SELECT status, COUNT(*) AS total FROM emprestimos WHERE alunos_idfk = :aluno GROUP BY status";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":aluno", $_SESSION["id_alunos"], PDO::PARAM_INT);
        }

        else 
        {
            $sql = "SELECT status, COUNT(*) AS total FROM emprestimos WHERE usuarios_idfk = :usuarios GROUP BY status";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function TotalEmprestimosPrazo()
    {
        if (isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"] === "Aluno") 
        {
            $sql = "SELECT CASE WHEN data_prevista < CURRENT_DATE AND status = 'Emprestado' 
            THEN 'Atrasado' ELSE 'Em dia' END AS situacao, COUNT(*) AS total
            FROM emprestimos WHERE alunos_idfk = :aluno AND status = 'Emprestado' GROUP BY situacao";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":aluno", $_SESSION["id_alunos"], PDO::PARAM_INT);
        }

        else 
        {
            $sql = "SELECT CASE WHEN data_prevista < CURRENT_DATE AND status = 'Emprestado' THEN 'Atrasado'
            ELSE 'Em dia' END AS situacao, COUNT(*) AS total
            FROM emprestimos WHERE usuarios_idfk = :usuarios AND status = 'Emprestado' GROUP BY situacao";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":usuarios", $_SESSION["id_usuarios"], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function BuscarDadosAviso($id)
    {
        $sql = "SELECT emprestimos.id_emprestimos, emprestimos.data_emprestimo, emprestimos.data_prevista, emprestimos.status,
        alunos.id_alunos, alunos.nome AS nome_aluno, alunos.email, alunos.telefone, patrimonio.nome AS nome_patrimonio, patrimonio.codigo AS codigo_patrimonio
        FROM emprestimos INNER JOIN alunos ON alunos.id_alunos = emprestimos.alunos_idfk
        INNER JOIN patrimonio ON patrimonio.id_patrimonio = emprestimos.patrimonio_idfk
        WHERE emprestimos.id_emprestimos = :id AND emprestimos.status = 'Emprestado'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) 
        {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }


    public function ListarEmprestimosAtivosPorAluno($id_aluno)
    {
        $sql = "SELECT emprestimos.id_emprestimos, emprestimos.data_emprestimo, emprestimos.data_prevista, emprestimos.status, patrimonio.nome AS nome_patrimonio, patrimonio.codigo AS codigo_patrimonio
        FROM emprestimos INNER JOIN patrimonio ON patrimonio.id_patrimonio = emprestimos.patrimonio_idfk
        WHERE emprestimos.alunos_idfk = :aluno AND emprestimos.status = 'Emprestado'
        ORDER BY emprestimos.id_emprestimos DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":aluno", $id_aluno, PDO::PARAM_INT);

        if ($stmt->execute()) 
        {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function ListarEmprestimosPorAluno($id_aluno)
    {
        $sql = "SELECT emprestimos.id_emprestimos, patrimonio.nome AS nome_patrimonio, patrimonio.codigo AS codigo_patrimonio, emprestimos.data_emprestimo,
        emprestimos.data_prevista, emprestimos.data_devolucao, emprestimos.status
        FROM emprestimos INNER JOIN patrimonio ON patrimonio.id_patrimonio = emprestimos.patrimonio_idfk
        WHERE emprestimos.alunos_idfk = :aluno ORDER BY emprestimos.data_emprestimo DESC, emprestimos.id_emprestimos DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":aluno", $id_aluno, PDO::PARAM_INT
        );

        if ($stmt->execute()) 
        {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
}