<?PHP



 //conecta no banco para realizar a estatística
 $db = mysql_connect("localhost","ocomon") or die ("erro server");
 mysql_select_db("ocomon",$db) or die ("erro banco");

 //$sql = "SELECT O.codigo as candidatos_codigo, C.PRO_ID as candidatos_PRO_ID,C.nome as candidatos_nome, C.email as candidatos_email, L.nome as linguas_nome, C1.nome as cursos1_nome, C2.nome as cursos2_nome, C3.cidade as cidades_cidade, C.fone as candidatos_fone, C.datainscr as candidatos_datainscr, C.datapagto as candidatos_datapagto, C.selecao as candidatos_selecao, C.endereco as candidatos_endereco, C.cep as candidatos_cep, C.uf as candidatos_uf, C.import as candidatos_import

 $sql = "SELECT O.problema as problema,
        P.prob_id as prob_id,
        P.problema as descricao_prob ,
        O.status as status,
        O.contato as contato,
        O.operador as operador,
        L.loc_id as loc_id,
        L.local as local_cod,
        O.local as local,
        O.sistema as sistemas,
        S.sis_id as sis_id,
        S.sistema as sis_desc,
        O.telefone as telefone,
        O.data_abertura as abertura,
        O.numero as numero,
        U.login as login,
        U.nome as nome
        FROM ocorrencias as O,
        problemas as P,
        localizacao as L,
        sistemas as S,
        usuarios as U
        WHERE (O.status = 2)
        AND (O.problema = P.prob_id)
        AND (S.sis_id = O.sistema)
        AND (U.login = O.operador)
        AND (L.loc_id = O.local )";





 //monta a tabela com as respostas
 echo "<table border = 1>";
 echo "<tr><td>Número</td><td>Problema</td><td>Sistema</td><td>Contato</td><td>Telefone</td><td>Local</td><td>Operador</td><td>Data Abertura</td>";
 if (($result = mysql_query($sql)) && (mysql_num_rows($result) > 0) ) {
  while ($row = mysql_fetch_array($result)) {
         echo "<tr>";
         echo "<td>";
         echo $row["numero"];
         echo "</td>";
         echo "<td>";
         echo $row["descricao_prob"];
         echo "</td>";
         echo "<td>";
         echo $row["sis_desc"];
         echo "</td>";
         echo "<td>";
         echo $row["contato"];
         echo "</td>";
         echo "<td>";
         echo $row["telefone"];
         echo "</td>";
         echo "<td>";
         echo $row["local_cod"];
         echo "</td>";
         echo "<td>";
         echo $row["nome"];
         echo "</td>";
         echo "<td>";
         echo $row["abertura"];
         echo "</td>";
         echo "</tr>";
         }
  echo "</table>";
  }










 ?>

