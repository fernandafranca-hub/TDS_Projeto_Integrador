<?php

class Busca
{
    private $pdo;


    public function __construct()
    {
        include_once("Connect.php");

        $conexao = new Connect();

        $this->pdo = $conexao->conectarBanco();
    }

    private function normalizarCampo($campo)
    {
        return "
            translate(
                lower(
                    coalesce($campo, '')
                ),
                'áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÔÖÚÙÛÜÇ',
                'aaaaaeeeeiiiiooooouuuucaaaaaeeeeiiiiooooouuuuc'
            )
        ";
    }

    private function normalizarTexto($texto)
    {
        $texto = mb_strtolower(
            $texto,
            'UTF-8'
        );


        return strtr(
            $texto,
            [
                'á' => 'a',
                'à' => 'a',
                'ã' => 'a',
                'â' => 'a',
                'ä' => 'a',

                'é' => 'e',
                'è' => 'e',
                'ê' => 'e',
                'ë' => 'e',

                'í' => 'i',
                'ì' => 'i',
                'î' => 'i',
                'ï' => 'i',

                'ó' => 'o',
                'ò' => 'o',
                'õ' => 'o',
                'ô' => 'o',
                'ö' => 'o',

                'ú' => 'u',
                'ù' => 'u',
                'û' => 'u',
                'ü' => 'u',

                'ç' => 'c'
            ]
        );
    }

    private function formatarData($data)
    {
        if(empty($data))
        {
            return "";
        }


        $timestamp = strtotime($data);


        if($timestamp === false)
        {
            return $data;
        }


        return date(
            "d/m/Y",
            $timestamp
        );
    }


    public function BuscarTudo($pesquisa, $id_usuario)
    {
        
        $pesquisa = trim($pesquisa);


        if($pesquisa == "")
        {
            return [];
        }

        $palavras = preg_split(
            '/\s+/',
            $pesquisa,
            -1,
            PREG_SPLIT_NO_EMPTY
        );


        if(empty($palavras))
        {
            return [];
        }


        $palavrasNormalizadas = [];


        foreach($palavras as $palavra)
        {
            $palavrasNormalizadas[] =
                $this->normalizarTexto($palavra);
        }


        $condicoesAluno = [];


        foreach($palavrasNormalizadas as $palavra)
        {
            $campos = [];

            $campos[] =
                $this->normalizarCampo("a.nome") .
                " LIKE ?";

            $parametrosAluno[] =
                "%" . $palavra . "%";

            $campos[] =
                $this->normalizarCampo("a.email") .
                " LIKE ?";

            $parametrosAluno[] =
                "%" . $palavra . "%";


            $campos[] =
                $this->normalizarCampo("a.telefone") .
                " LIKE ?";

            $parametrosAluno[] =
                "%" . $palavra . "%";


            $campos[] =
                $this->normalizarCampo("a.matricula") .
                " LIKE ?";

            $parametrosAluno[] =
                "%" . $palavra . "%";


            $campos[] =
                $this->normalizarCampo("a.curso") .
                " LIKE ?";

            $parametrosAluno[] =
                "%" . $palavra . "%";


            $condicoesAluno[] =
                "(" .
                implode(" OR ", $campos) .
                ")";
        }


        $condicoesPatrimonio = [];

        foreach($palavrasNormalizadas as $palavra)
        {
            $campos = [];           

            $campos[] =
                $this->normalizarCampo("p.codigo") .
                " LIKE ?";

            $parametrosPatrimonio[] =
                "%" . $palavra . "%";

            $campos[] =
                $this->normalizarCampo("p.nome") .
                " LIKE ?";

            $parametrosPatrimonio[] =
                "%" . $palavra . "%";

            $campos[] =
                $this->normalizarCampo("p.categoria") .
                " LIKE ?";

            $parametrosPatrimonio[] =
                "%" . $palavra . "%";

            $campos[] =
                $this->normalizarCampo("p.descricao") .
                " LIKE ?";

            $parametrosPatrimonio[] =
                "%" . $palavra . "%";

            $campos[] =
                $this->normalizarCampo("p.status") .
                " LIKE ?";

            $parametrosPatrimonio[] =
                "%" . $palavra . "%";

            $campos[] =
                "CAST(p.quantidade AS TEXT) ILIKE ?";

            $parametrosPatrimonio[] =
                "%" . $palavra . "%";


            $condicoesPatrimonio[] =
                "(" .
                implode(" OR ", $campos) .
                ")";
        }


        $condicoesEmprestimo = [];

        foreach($palavrasNormalizadas as $palavra)
        {
            $campos = [];

            $campos[] =
                $this->normalizarCampo("a.nome") .
                " LIKE ?";

            $parametrosEmprestimo[] =
                "%" . $palavra . "%";

            $campos[] =
                $this->normalizarCampo("p.nome") .
                " LIKE ?";

            $parametrosEmprestimo[] =
                "%" . $palavra . "%";


            $campos[] =
                $this->normalizarCampo("p.codigo") .
                " LIKE ?";

            $parametrosEmprestimo[] =
                "%" . $palavra . "%";

            $campos[] =
                $this->normalizarCampo("e.status") .
                " LIKE ?";

            $parametrosEmprestimo[] =
                "%" . $palavra . "%";

            $campos[] =
                $this->normalizarCampo("e.observacao") .
                " LIKE ?";

            $parametrosEmprestimo[] =
                "%" . $palavra . "%";

            $campos[] =
                "CAST(e.data_emprestimo AS TEXT) ILIKE ?";

            $parametrosEmprestimo[] =
                "%" . $palavra . "%";


            $campos[] =
                "CAST(e.data_prevista AS TEXT) ILIKE ?";

            $parametrosEmprestimo[] =
                "%" . $palavra . "%";


            $campos[] =
                "CAST(e.data_devolucao AS TEXT) ILIKE ?";

            $parametrosEmprestimo[] =
                "%" . $palavra . "%";


            $condicoesEmprestimo[] =
                "(" .
                implode(" OR ", $campos) .
                ")";
        }


        $sqlAluno = " SELECT 'Aluno' AS tipo, a.id_alunos AS id, a.nome AS resultado, 'visualizarAluno.php' AS pagina,
                a.nome AS campo_nome,
                a.email AS campo_email,
                a.telefone AS campo_telefone,
                a.matricula AS campo_matricula,
                a.curso AS campo_curso
            FROM alunos a
            INNER JOIN usuarios u ON u.id_usuarios = a.usuarios_idfk
            WHERE " . implode(" AND ", $condicoesAluno) . " AND u.id_usuarios = ?";


        $sqlPatrimonio = " SELECT 'Patrimônio' AS tipo, p.id_patrimonio AS id,
                CONCAT( p.codigo, ' - ', p.nome) AS resultado, 'visualizarPatrimonio.php' AS pagina,
                p.codigo AS campo_codigo,
                p.nome AS campo_nome,
                p.categoria AS campo_categoria,
                p.descricao AS campo_descricao,
                p.status AS campo_status,
                CAST(p.quantidade AS TEXT) AS campo_quantidade
            FROM patrimonio p
            INNER JOIN usuarios u ON u.id_usuarios = p.usuarios_idfk
            WHERE " . implode(" AND ", $condicoesPatrimonio) . " AND u.id_usuarios = ?";

        $sqlEmprestimo = " SELECT 'Empréstimo' AS tipo, e.id_emprestimos AS id,
                CONCAT(a.nome, ' - ', p.nome) AS resultado, 'visualizarEmprestimo.php' AS pagina,
                a.nome AS campo_aluno,
                p.nome AS campo_patrimonio,
                p.codigo AS campo_codigo,
                e.status AS campo_status,
                e.observacao AS campo_observacao,
                e.data_emprestimo AS campo_data_emprestimo,
                e.data_prevista AS campo_data_prevista,
                e.data_devolucao AS campo_data_devolucao
            FROM emprestimos e
            INNER JOIN alunos a ON a.id_alunos = e.alunos_idfk
            INNER JOIN patrimonio p ON p.id_patrimonio = e.patrimonio_idfk
            INNER JOIN usuarios u ON u.id_usuarios = e.usuarios_idfk
            WHERE " . implode(" AND ", $condicoesEmprestimo) . " AND u.id_usuarios = ?";


        $stmtAluno = $this->pdo->prepare($sqlAluno);
        $parametrosAluno[] = $id_usuario;
        $stmtAluno->execute($parametrosAluno);
        $alunos = $stmtAluno->fetchAll(PDO::FETCH_ASSOC);

        $stmtPatrimonio = $this->pdo->prepare($sqlPatrimonio);
        $parametrosPatrimonio[] = $id_usuario;
        $stmtPatrimonio->execute($parametrosPatrimonio);
        $patrimonios = $stmtPatrimonio->fetchAll(PDO::FETCH_ASSOC);

        $stmtEmprestimo = $this->pdo->prepare($sqlEmprestimo);
        $parametrosEmprestimo[] = $id_usuario;
        $stmtEmprestimo->execute($parametrosEmprestimo);
        $emprestimos = $stmtEmprestimo->fetchAll(PDO::FETCH_ASSOC);

        $resultados = [];

        foreach($alunos as $item)
        {
            $motivos = [];


            foreach($palavrasNormalizadas as $palavra)
            {
                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_nome"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Nome: " . $item["campo_nome"];

                    continue;
                }


                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_email"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "E-mail: " . $item["campo_email"];

                    continue;
                }


                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_telefone"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Telefone: " . $item["campo_telefone"];

                    continue;
                }


                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_matricula"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Matrícula: " . $item["campo_matricula"];

                    continue;
                }


                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_curso"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Curso: " . $item["campo_curso"];

                    continue;
                }
            }

            $resultado = $item["resultado"];

            if(
                !empty($motivos) &&
                count($motivos) > 0
            )
            {
                $nomeNormalizado =
                    $this->normalizarTexto(
                        $item["campo_nome"]
                    );


                $pesquisaEstaNoNome = false;


                foreach($palavrasNormalizadas as $palavra)
                {
                    if(
                        stripos(
                            $nomeNormalizado,
                            $palavra
                        ) !== false
                    )
                    {
                        $pesquisaEstaNoNome = true;

                        break;
                    }
                }


                if(!$pesquisaEstaNoNome)
                {
                    $resultado .=
                        " — " .
                        implode(
                            " | ",
                            array_unique($motivos)
                        );
                }
            }


            $resultados[] = [
                "tipo" =>
                    $item["tipo"],

                "id" =>
                    $item["id"],

                "resultado" =>
                    $resultado,

                "pagina" =>
                    $item["pagina"]
            ];
        }

        foreach($patrimonios as $item)
        {            
            $resultado =
                $item["resultado"];

            $motivos = [];

            foreach($palavrasNormalizadas as $palavra)
            {
                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_codigo"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    continue;
                }


                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_nome"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    continue;
                }


                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_categoria"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Categoria: " .
                        $item["campo_categoria"];

                    continue;
                }


                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_descricao"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Descrição: " .
                        $item["campo_descricao"];

                    continue;
                }


                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_status"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Status: " .
                        $item["campo_status"];

                    continue;
                }


                if(
                    stripos(
                        $item["campo_quantidade"],
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Quantidade: " .
                        $item["campo_quantidade"];

                    continue;
                }
            }


            if(!empty($motivos))
            {
                $resultado .=
                    " — " .
                    implode(
                        " | ",
                        array_unique($motivos)
                    );
            }


            $resultados[] = [
                "tipo" =>
                    $item["tipo"],

                "id" =>
                    $item["id"],

                "resultado" =>
                    $resultado,

                "pagina" =>
                    $item["pagina"]
            ];
        }

        foreach($emprestimos as $item)
        {            
            $resultado =
                $item["resultado"];


            $motivos = [];


            foreach($palavrasNormalizadas as $palavra)
            {
                /*
                 * Nome do aluno
                 */

                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_aluno"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    continue;
                }

                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_patrimonio"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    continue;
                }


                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_codigo"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    continue;
                }

                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_status"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Status: " .
                        $item["campo_status"];

                    continue;
                }

              
                if(
                    stripos(
                        $this->normalizarTexto(
                            $item["campo_observacao"]
                        ),
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Observação: " .
                        $item["campo_observacao"];

                    continue;
                }

                $dataEmprestimo =
                    $this->formatarData(
                        $item["campo_data_emprestimo"]
                    );


                if(
                    stripos(
                        $dataEmprestimo,
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Data do empréstimo: " .
                        $dataEmprestimo;

                    continue;
                }

                $dataPrevista =
                    $this->formatarData(
                        $item["campo_data_prevista"]
                    );


                if(
                    stripos(
                        $dataPrevista,
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Data prevista: " .
                        $dataPrevista;

                    continue;
                }

                $dataDevolucao =
                    $this->formatarData(
                        $item["campo_data_devolucao"]
                    );


                if(
                    stripos(
                        $dataDevolucao,
                        $palavra
                    ) !== false
                )
                {
                    $motivos[] =
                        "Data de devolução: " .
                        $dataDevolucao;

                    continue;
                }
            }

            if(!empty($motivos))
            {
                $resultado .=
                    " — " .
                    implode(
                        " | ",
                        array_unique($motivos)
                    );
            }


            $resultados[] = [
                "tipo" =>
                    $item["tipo"],

                "id" =>
                    $item["id"],

                "resultado" =>
                    $resultado,

                "pagina" =>
                    $item["pagina"]
            ];
        }

        usort(
            $resultados,
            function($a, $b)
            {
                $ordem = [
                    "Aluno" => 1,
                    "Empréstimo" => 2,
                    "Patrimônio" => 3
                ];


                if(
                    $ordem[$a["tipo"]] !=
                    $ordem[$b["tipo"]]
                )
                {
                    return
                        $ordem[$a["tipo"]] -
                        $ordem[$b["tipo"]];
                }


                return strcasecmp(
                    $a["resultado"],
                    $b["resultado"]
                );
            }
        );

        return $resultados;
    }
}
?>

