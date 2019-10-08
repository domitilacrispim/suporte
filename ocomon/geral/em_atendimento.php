<?PHP


  include ("layout.inc");
  echo "<blockquote>";

  //conecta no banco para realizar a estatística
 $db = mysql_connect("localhost","ocomon") or die ("erro server");
 mysql_select_db("ocomon",$db) or die ("erro banco");

 echo "<table border=0 align='center' width=\"100%\"><tr><td align='center' width=\"100%\"><font size=\"4\" color=\"blue\"><b>Tecle F5 para atualizar a página!</b></tr></td></table>";


 $codStat = 2;
 $existe = "SELECT status from ocorrencias where status = $codStat";
        if (($result = mysql_query($existe)) && (mysql_num_rows($result) > 0) ){

 echo "<table border=0 align='center' width=\"100%\">";

 echo "<tr><td width=\"100%\"><font size=\"3\"><b>Confira aqui os chamados que estão<font color=\"#bd3103\"> em atendimento </font> pelo Helpdesk:</b></tr></td>";
 echo "</table>";



 //$sql = "SELECT O.codigo as candidatos_codigo, C.PRO_ID as candidatos_PRO_ID,C.nome as candidatos_nome, C.email as candidatos_email, L.nome as linguas_nome, C1.nome as cursos1_nome, C2.nome as cursos2_nome, C3.cidade as cidades_cidade, C.fone as candidatos_fone, C.datainscr as candidatos_datainscr, C.datapagto as candidatos_datapagto, C.selecao as candidatos_selecao, C.endereco as candidatos_endereco, C.cep as candidatos_cep, C.uf as candidatos_uf, C.import as candidatos_import
        $consulta = "(O.status = $codStat)";
 $sql = "SELECT O.problema as problema,
        P.prob_id as prob_id,
        P.problema as descricao_prob ,
        O.status as status,
        O.contato as contato,
        O.operador as operador,

        L.loc_id as loc_id,
        L.local as local_cod,
        O.local as local,
        O.numero as numero,
        U.nome as nome,
        S.status as status_desc,
        S.stat_id as status_id
        FROM ocorrencias as O,
        problemas as P,
        localizacao as L,
        usuarios as U,
        status as S
        WHERE
        (O.problema = P.prob_id
        AND U.login = O.operador
        AND L.loc_id = O.local
        AND O.status = S.stat_id)
        AND $consulta order by numero";



  // O.status = 2 or O.status = 5 or O.status = 6 or O.status = 7 or O.status = 8 or O.status = 11)

 //monta a tabela com as respostas

 echo "<table border=1 align='center' width='80%'>";

 echo "<tr><td><b>Nº do Chamado</td><td><b>Problema</td><td><b>Contato</td><td><b>Local</td><td><b>Operador</td><td><b>Situação</td>";



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
         echo $row["contato"];
         echo "</td>";
         echo "<td>";
         echo $row["local_cod"];
         echo "</td>";
         echo "<td>";
         echo $row["nome"];
         echo "</td>";
         echo "<td>";
         echo $row["status_desc"];
         echo "</td>";

         echo "</tr>";

         }
   echo "</tr>";
   echo "</tr>";

   echo"</table>";
  }
 }
################################################

$codStat = 1;
$existe = "SELECT status from ocorrencias where status = $codStat";
        if (($result = mysql_query($existe)) && (mysql_num_rows($result) > 0) ){
           echo "<p></p>";
           echo "<table border=0 align='center' width=\"100%\">";
           echo "<tr><td width=\"100%\"><font size=\"3\"><b>Esses são os chamados que ainda estão <font color=\"#bd3103\">aguardando atendimento</font>, verifique se o seu está aqui:</b></tr></td>";
           echo "</table>";
           //echo "<p></p>";
            //conecta no banco para realizar a estatística
     //       $db = mysql_connect("localhost","ocomon") or die ("erro server");
     //       mysql_select_db("ocomon",$db) or die ("erro banco");

 //$sql = "SELECT O.codigo as candidatos_codigo, C.PRO_ID as candidatos_PRO_ID,C.nome as candidatos_nome, C.email as candidatos_email, L.nome as linguas_nome, C1.nome as cursos1_nome, C2.nome as cursos2_nome, C3.cidade as cidades_cidade, C.fone as candidatos_fone, C.datainscr as candidatos_datainscr, C.datapagto as candidatos_datapagto, C.selecao as candidatos_selecao, C.endereco as candidatos_endereco, C.cep as candidatos_cep, C.uf as candidatos_uf, C.import as candidatos_import

            $consulta = "(O.status = $codStat)"; //     (O.status = 11 or O.status = 5)
            $sql = "SELECT O.problema as problema,
                    P.prob_id as prob_id,
                    P.problema as descricao_prob ,
                    O.status as status,
                    O.contato as contato,
                    O.operador as operador,
                    L.loc_id as loc_id,
                    L.local as local_cod,
                    O.local as local,
                    O.numero as numero,
                    U.nome as nome,
                    O.telefone as ramal,
                    S.status as status_desc,
                    S.stat_id as status_id
                    FROM ocorrencias as O,
                    problemas as P,
                    localizacao as L,
                    usuarios as U,
                    status as S
                    WHERE
                    (O.problema = P.prob_id
                    AND U.login = O.operador
                    AND L.loc_id = O.local
                    AND O.status = S.stat_id)
                    AND $consulta order by numero";



  // O.status = 2 or O.status = 5 or O.status = 6 or O.status = 7 or O.status = 8 or O.status = 11)

 //monta a tabela com as respostas

                  echo "<table border=1 align='center'  width='80%'>";

                  echo "<tr><td><b>Nº do Chamado</td><td><b>Problema</td><td><b>Contato</td><td><b>Local</td><td><b>Ramal</td><td><b>Situação</td>";



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
                  echo $row["contato"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["local_cod"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["ramal"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["status_desc"];
                  echo "</td>";

                  echo "</tr>";

                  }
                  echo "</tr>";
                  echo "</tr>";

                  echo"</table>";
           }

   }




###############################################################
$codStat = 11;
$existe = "SELECT status from ocorrencias where status = $codStat";
        if (($result = mysql_query($existe)) && (mysql_num_rows($result) > 0) ){

           echo "<p></p>";
           echo "<table border=0 align='center' width=\"100%\">";
           echo "<tr><td width=\"100%\"><font size=\"3\"><b>Verifique se o seu chamado foi <font color=\"#bd3103\">encaminhado para Manutenção Externa</font>:</b></tr></td>";
           echo "</table>";

            //conecta no banco para realizar a estatística
     //       $db = mysql_connect("localhost","ocomon") or die ("erro server");
     //       mysql_select_db("ocomon",$db) or die ("erro banco");

 //$sql = "SELECT O.codigo as candidatos_codigo, C.PRO_ID as candidatos_PRO_ID,C.nome as candidatos_nome, C.email as candidatos_email, L.nome as linguas_nome, C1.nome as cursos1_nome, C2.nome as cursos2_nome, C3.cidade as cidades_cidade, C.fone as candidatos_fone, C.datainscr as candidatos_datainscr, C.datapagto as candidatos_datapagto, C.selecao as candidatos_selecao, C.endereco as candidatos_endereco, C.cep as candidatos_cep, C.uf as candidatos_uf, C.import as candidatos_import

            $consulta = "(O.status = $codStat)"; //     (O.status = 11 or O.status = 5)
            $sql = "SELECT O.problema as problema,
                    P.prob_id as prob_id,
                    P.problema as descricao_prob ,
                    O.status as status,
                    O.contato as contato,
                    O.operador as operador,
                    L.loc_id as loc_id,
                    L.local as local_cod,
                    O.local as local,
                    O.numero as numero,
                    U.nome as nome,
                    S.status as status_desc,
                    S.stat_id as status_id
                    FROM ocorrencias as O,
                    problemas as P,
                    localizacao as L,
                    usuarios as U,
                    status as S
                    WHERE
                    (O.problema = P.prob_id
                    AND U.login = O.operador
                    AND L.loc_id = O.local
                    AND O.status = S.stat_id)
                    AND $consulta order by numero";



  // O.status = 2 or O.status = 5 or O.status = 6 or O.status = 7 or O.status = 8 or O.status = 11)

 //monta a tabela com as respostas

                  echo "<table border=1 align='center'  width='80%'>";

                  echo "<tr><td><b>Nº do Chamado</td><td><b>Problema</td><td><b>Contato</td><td><b>Local</td><td><b>Operador</td><td><b>Situação</td>";



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
                  echo $row["contato"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["local_cod"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["nome"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["status_desc"];
                  echo "</td>";

                  echo "</tr>";

                  }
                  echo "</tr>";
                  echo "</tr>";

                  echo"</table>";
           }

   }


###############################################################################

$codStat = 5;
$existe = "SELECT status from ocorrencias where status = $codStat";
        if (($result = mysql_query($existe)) && (mysql_num_rows($result) > 0) ){
             echo "<p></p>";
           echo "<table border=0 align='center' width=\"100%\">";
           echo "<tr><td width=\"100%\"><font size=\"3\"><b>Verifique se o seu chamado foi <font color=\"#bd3103\">encaminhado para a ZYX</font>:</b></tr></td>";
           echo "</table>";

            //conecta no banco para realizar a estatística
     //       $db = mysql_connect("localhost","ocomon") or die ("erro server");
     //       mysql_select_db("ocomon",$db) or die ("erro banco");

 //$sql = "SELECT O.codigo as candidatos_codigo, C.PRO_ID as candidatos_PRO_ID,C.nome as candidatos_nome, C.email as candidatos_email, L.nome as linguas_nome, C1.nome as cursos1_nome, C2.nome as cursos2_nome, C3.cidade as cidades_cidade, C.fone as candidatos_fone, C.datainscr as candidatos_datainscr, C.datapagto as candidatos_datapagto, C.selecao as candidatos_selecao, C.endereco as candidatos_endereco, C.cep as candidatos_cep, C.uf as candidatos_uf, C.import as candidatos_import

            $consulta = "(O.status = $codStat)"; //     (O.status = 11 or O.status = 5)
            $sql = "SELECT O.problema as problema,
                    P.prob_id as prob_id,
                    P.problema as descricao_prob ,
                    O.status as status,
                    O.contato as contato,
                    O.operador as operador,
                    L.loc_id as loc_id,
                    L.local as local_cod,
                    O.local as local,
                    O.numero as numero,
                    U.nome as nome,
                    S.status as status_desc,
                    S.stat_id as status_id
                    FROM ocorrencias as O,
                    problemas as P,
                    localizacao as L,
                    usuarios as U,
                    status as S
                    WHERE
                    (O.problema = P.prob_id
                    AND U.login = O.operador
                    AND L.loc_id = O.local
                    AND O.status = S.stat_id)
                    AND $consulta order by numero";



  // O.status = 2 or O.status = 5 or O.status = 6 or O.status = 7 or O.status = 8 or O.status = 11)

 //monta a tabela com as respostas

                  echo "<table border=1 align='center'  width='80%'>";

                  echo "<tr><td><b>Nº do Chamado</td><td><b>Problema</td><td><b>Contato</td><td><b>Local</td><td><b>Operador</td><td><b>Situação</td>";



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
                  echo $row["contato"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["local_cod"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["nome"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["status_desc"];
                  echo "</td>";

                  echo "</tr>";

                  }
                  echo "</tr>";
                  echo "</tr>";

                  echo"</table>";
           }

   }


 #################################################################################

 $codStat = 6;
$existe = "SELECT status from ocorrencias where status = $codStat";
        if (($result = mysql_query($existe)) && (mysql_num_rows($result) > 0) ){
            echo "<p></p>";
           echo "<table border=0 align='center' width=\"100%\">";
           echo "<tr><td width=\"100%\"><font size=\"3\"><b>Verifique se o seu chamado foi encaminhado para <font color=\"#bd3103\">a área de Sistemas:</font></b></tr></td>";
           echo "</table>";

            //conecta no banco para realizar a estatística
     //       $db = mysql_connect("localhost","ocomon") or die ("erro server");
     //       mysql_select_db("ocomon",$db) or die ("erro banco");

 //$sql = "SELECT O.codigo as candidatos_codigo, C.PRO_ID as candidatos_PRO_ID,C.nome as candidatos_nome, C.email as candidatos_email, L.nome as linguas_nome, C1.nome as cursos1_nome, C2.nome as cursos2_nome, C3.cidade as cidades_cidade, C.fone as candidatos_fone, C.datainscr as candidatos_datainscr, C.datapagto as candidatos_datapagto, C.selecao as candidatos_selecao, C.endereco as candidatos_endereco, C.cep as candidatos_cep, C.uf as candidatos_uf, C.import as candidatos_import

            $consulta = "(O.status = $codStat)"; //     (O.status = 11 or O.status = 5)
            $sql = "SELECT O.problema as problema,
                    P.prob_id as prob_id,
                    P.problema as descricao_prob ,
                    O.status as status,
                    O.contato as contato,
                    O.operador as operador,
                    L.loc_id as loc_id,
                    L.local as local_cod,
                    O.local as local,
                    O.numero as numero,
                    U.nome as nome,
                    S.status as status_desc,
                    S.stat_id as status_id
                    FROM ocorrencias as O,
                    problemas as P,
                    localizacao as L,
                    usuarios as U,
                    status as S
                    WHERE
                    (O.problema = P.prob_id
                    AND U.login = O.operador
                    AND L.loc_id = O.local
                    AND O.status = S.stat_id)
                    AND $consulta order by numero";



  // O.status = 2 or O.status = 5 or O.status = 6 or O.status = 7 or O.status = 8 or O.status = 11)

 //monta a tabela com as respostas

                  echo "<table border=1 align='center'  width='80%'>";

                  echo "<tr><td><b>Nº do Chamado</td><td><b>Problema</td><td><b>Contato</td><td><b>Local</td><td><b>Operador</td><td><b>Situação</td>";



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
                  echo $row["contato"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["local_cod"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["nome"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["status_desc"];
                  echo "</td>";

                  echo "</tr>";

                  }
                  echo "</tr>";
                  echo "</tr>";

                  echo"</table>";
           }

   }


#######################################################################################

$codStat = 7;
$existe = "SELECT status from ocorrencias where status = $codStat";
        if (($result = mysql_query($existe)) && (mysql_num_rows($result) > 0) ){
           echo "<p></p>";
           echo "<table border=0 align='center' width=\"100%\">";
           echo "<tr><td width=\"100%\"><font size=\"3\"><b>Confirme se o seu chamado está <font color=\"#bd3103\">agendado </font>para uma data específica:</b></tr></td>";
           echo "</table>";

            //conecta no banco para realizar a estatística
     //       $db = mysql_connect("localhost","ocomon") or die ("erro server");
     //       mysql_select_db("ocomon",$db) or die ("erro banco");

 //$sql = "SELECT O.codigo as candidatos_codigo, C.PRO_ID as candidatos_PRO_ID,C.nome as candidatos_nome, C.email as candidatos_email, L.nome as linguas_nome, C1.nome as cursos1_nome, C2.nome as cursos2_nome, C3.cidade as cidades_cidade, C.fone as candidatos_fone, C.datainscr as candidatos_datainscr, C.datapagto as candidatos_datapagto, C.selecao as candidatos_selecao, C.endereco as candidatos_endereco, C.cep as candidatos_cep, C.uf as candidatos_uf, C.import as candidatos_import

            $consulta = "(O.status = $codStat)"; //     (O.status = 11 or O.status = 5)
            $sql = "SELECT O.problema as problema,
                    P.prob_id as prob_id,
                    P.problema as descricao_prob ,
                    O.status as status,
                    O.contato as contato,
                    O.operador as operador,
                    L.loc_id as loc_id,
                    L.local as local_cod,
                    O.local as local,
                    O.numero as numero,
                    U.nome as nome,
                    S.status as status_desc,
                    S.stat_id as status_id
                    FROM ocorrencias as O,
                    problemas as P,
                    localizacao as L,
                    usuarios as U,
                    status as S
                    WHERE
                    (O.problema = P.prob_id
                    AND U.login = O.operador
                    AND L.loc_id = O.local
                    AND O.status = S.stat_id)
                    AND $consulta order by numero";



  // O.status = 2 or O.status = 5 or O.status = 6 or O.status = 7 or O.status = 8 or O.status = 11)

 //monta a tabela com as respostas

                  echo "<table border=1 align='center'  width='80%'>";

                  echo "<tr><td><b>Nº do Chamado</td><td><b>Problema</td><td><b>Contato</td><td><b>Local</td><td><b>Operador</td><td><b>Situação</td>";



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
                  echo $row["contato"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["local_cod"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["nome"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["status_desc"];
                  echo "</td>";

                  echo "</tr>";

                  }
                  echo "</tr>";
                  echo "</tr>";

                  echo"</table>";
           }

   }


########################################################################


$codStat = 8;
$existe = "SELECT status from ocorrencias where status = $codStat";
        if (($result = mysql_query($existe)) && (mysql_num_rows($result) > 0) ){
           echo "<p></p>";
           echo "<table border=0 align='center' width=\"100%\">";
           echo "<tr><td width=\"100%\"><font size=\"3\"><b>Verifique se o seu chamado foi <font color=\"#bd3103\">encaminhado para a área de Redes</font>:</b></tr></td>";
           echo "</table>";
           //echo "<p></p>";
            //conecta no banco para realizar a estatística
     //       $db = mysql_connect("localhost","ocomon") or die ("erro server");
     //       mysql_select_db("ocomon",$db) or die ("erro banco");

 //$sql = "SELECT O.codigo as candidatos_codigo, C.PRO_ID as candidatos_PRO_ID,C.nome as candidatos_nome, C.email as candidatos_email, L.nome as linguas_nome, C1.nome as cursos1_nome, C2.nome as cursos2_nome, C3.cidade as cidades_cidade, C.fone as candidatos_fone, C.datainscr as candidatos_datainscr, C.datapagto as candidatos_datapagto, C.selecao as candidatos_selecao, C.endereco as candidatos_endereco, C.cep as candidatos_cep, C.uf as candidatos_uf, C.import as candidatos_import

            $consulta = "(O.status = $codStat)"; //     (O.status = 11 or O.status = 5)
            $sql = "SELECT O.problema as problema,
                    P.prob_id as prob_id,
                    P.problema as descricao_prob ,
                    O.status as status,
                    O.contato as contato,
                    O.operador as operador,
                    L.loc_id as loc_id,
                    L.local as local_cod,
                    O.local as local,
                    O.numero as numero,
                    U.nome as nome,
                    S.status as status_desc,
                    S.stat_id as status_id
                    FROM ocorrencias as O,
                    problemas as P,
                    localizacao as L,
                    usuarios as U,
                    status as S
                    WHERE
                    (O.problema = P.prob_id
                    AND U.login = O.operador
                    AND L.loc_id = O.local
                    AND O.status = S.stat_id)
                    AND $consulta order by numero";



  // O.status = 2 or O.status = 5 or O.status = 6 or O.status = 7 or O.status = 8 or O.status = 11)

 //monta a tabela com as respostas

                  echo "<table border=1 align='center'  width='80%'>";

                  echo "<tr><td><b>Nº do Chamado</td><td><b>Problema</td><td><b>Contato</td><td><b>Local</td><td><b>Operador</td><td><b>Situação</td>";



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
                  echo $row["contato"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["local_cod"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["nome"];
                  echo "</td>";
                  echo "<td>";
                  echo $row["status_desc"];
                  echo "</td>";

                  echo "</tr>";

                  }
                  echo "</tr>";
                  echo "</tr>";

                  echo"</table>";
           }

   }





   echo "</blockquote>";

 ?>

