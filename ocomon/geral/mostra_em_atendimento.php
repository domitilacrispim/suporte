<?
 /*                        Copyright 2005 Fl�vio Ribeiro
  
         This file is part of OCOMON.
  
         OCOMON is free software; you can redistribute it and/or modify
         it under the terms of the GNU General Public License as published by
         the Free Software Foundation; either version 2 of the License, or
         (at your option) any later version.
  
         OCOMON is distributed in the hope that it will be useful,
         but WITHOUT ANY WARRANTY; without even the implied warranty of
         MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
         GNU General Public License for more details.
  
         You should have received a copy of the GNU General Public License
         along with Foobar; if not, write to the Free Software
         Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  */


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

?>

<HTML>
<BODY bgcolor=<?print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TAB_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>OcoMon - M�dulo de ocorrências</b></TD>";
                        echo menu_usuario();
                        if ($s_usuario=='admin')
                        {
                                echo menu_admin();
                        }
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>

<BR>
<B>Consulta de ocorrências</B>
<BR>

<?

         if ($rodou == "sim")
        {
                $query = "SELECT * FROM ocorrencias WHERE (";


                if (!empty($numero_inicial) and !empty($numero_final))
                        $query.="(numero>='$numero_inicial' AND numero<='$numero_final')";

                if (!empty($numero_inicial) and empty($numero_final))
                        $query.="(numero>='$numero_inicial')";

                if (empty($numero_inicial) and !empty($numero_final))
                        $query.="(numero<='$numero_final')";

                if ($problema != -1)
                {
                        if (!empty($problema) and $problema != -1)
                        {
                                if (strlen($query)>34)
                                        $query.="AND ";
                                $query.="problema=$problema ";
                        }
                }

                if (!empty($descricao))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="descricao LIKE '%$descricao%' ";
                }

                if (!empty($equipamento))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="equipamento LIKE '%$equipamento%' ";
                }


                if (!empty($sistema) and $sistema != -1)
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="sistema=$sistema ";
                }


                if (!empty($contato))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="contato LIKE '%$contato%' ";
                }


                if (!empty($telefone))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="telefone='$telefone' ";
                }


                if (!empty($local) and $local != -1)
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="local=$local ";
                }


                if (!empty($operador) and $operador != -1)
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="operador='$operador' ";
                }


                if (!empty($data_inicial) and !empty($data_final))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $data_inicial = datam($data_inicial);
                        $data_final = datam($data_final);
                        $query.="data_abertura>='$data_inicial' AND data_abertura<='$data_final'";
                }

                if (!empty($data_inicial) and empty($data_final))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $data_inicial = datam($data_inicial);
                        $query.="data_abertura>='$data_inicial'";
                }

                if (empty($data_inicial) and !empty($data_final))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $data_final = datam($data_final);
                        $query.="data_abertura<='$data_final'";
                }


                if ($status == "Em aberto")
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $status = "Encerrada";
                        $query.="status !=4 ";
                }
                else
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="status=$status ";
                }


                $query.=" ) ORDER BY numero";

                if (strlen($query)>36)
                {
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);
                        if ($linhas==0)
                        {
                                $query = "SELECT ocorrencia FROM assentamentos WHERE (";

                                if (!empty($numero_inicial) and !empty($numero_final))
                                        $query.="(numero>='$numero_inicial' AND numero<='$numero_final')";

                                if (!empty($numero_inicial) and empty($numero_final))
                                        $query.="(numero>='$numero_inicial')";

                                if (empty($numero_inicial) and !empty($numero_final))
                                        $query.="(numero<='$numero_final')";

                                if (!empty($descricao))
                                {
                                        if (strlen($query)>45)
                                                $query.=" AND ";
                                        $query.="assentamento LIKE '%$descricao%' ";
                                }

                                $query.=" ) ORDER BY ocorrencia";

                                $resultado = mysql_query($query);
                                $linhas = mysql_numrows($resultado);
                                if ($linhas!=0)
                                {
                                        $numero = mysql_result($resultado,$linhas-1,0);
                                        $query = "SELECT * FROM ocorrencias WHERE numero=$numero";
                                        $resultado = mysql_query($query);
                                        $linhas = mysql_numrows($resultado);
                                }
                                else
                                {
                                        $aviso = "Nenhuma ocorrência localizada.";
                                        $origem = "consultar.php";
                                        session_register("aviso");
                                        session_register("origem");
                                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                                }
                        }
                }

                $cor=TAB_COLOR;
                $cor1=TD_COLOR;

                print "<TD>";
                if ($linhas>1)
                        print "<TR><TD bgcolor=$cor1><B>Foram encontradas $linhas ocorrências. </B></TD></TR>";
                else
                        print "<TR><TD bgcolor=$cor1><B>Foi encontrada somente 1 ocorrência.</B></TD></TR>";
                print "</TD>";
                print "<TD>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'";
                print "<TR><TD bgcolor=$cor1>N�mero</TD><TD bgcolor=$cor1>Problema</TD><TD bgcolor=$cor1>Contato</TD><TD bgcolor=$cor1>Operador</TD>
                        <TD bgcolor=$cor1>Local</TD><TD bgcolor=$cor1>Abertura</TD><TD bgcolor=$cor1>Status</TD><TD bgcolor=$cor1>Atender</TD></TR>";
                $i=0;
                $j=2;
                while ($i < $linhas)
                {

                        if ($j % 2)
                        {
                                $color =  BODY_COLOR;
                        }
                        else
                        {
                                $color = white;
                        }

                        $j++;
                        ?>
                        <TR>
                        <TD bgcolor=<?print $color;?>><a href=mostra_consulta.php?numero=<?print mysql_result($resultado,$i,0);?>><?print mysql_result($resultado,$i,0);?></a></TD>
                        <?
                                $problemas = mysql_result($resultado,$i,1);
                                $query = "SELECT * FROM problemas WHERE prob_id='$problemas'";
                                $resultado3 = mysql_query($query);
                        ?>
                        <TD bgcolor=<?print $color;?>><?print mysql_result($resultado3,0,1);?></TD>
                        <TD bgcolor=<?print $color;?>><?print mysql_result($resultado,$i,5);?></TD>
                        <TD bgcolor=<?print $color;?>><?print mysql_result($resultado,$i,8);?></TD>
                        <?
                                $local = mysql_result($resultado,$i,7);
                                $query = "SELECT * FROM localizacao WHERE loc_id='$local'";
                                $resultado3 = mysql_query($query);
                        ?>
                        <TD bgcolor=<?print $color;?>><?print mysql_result($resultado3,0,1);?></TD>
                        <TD bgcolor=<?print $color;?>><?print datab(mysql_result($resultado,$i,9));?></TD>
                        <?
                                $status = mysql_result($resultado,$i,11);
                                $query = "SELECT * FROM status WHERE stat_id='$status'";
                                $resultado3 = mysql_query($query);
                        ?>
                        <TD bgcolor=<?print $color;?>><?print mysql_result($resultado3,0,1);?></TD>
                        <?
                                if (mysql_result($resultado3,0,1)!="Em atendimento" and mysql_result($resultado3,0,1)!="Encerrada")
                                {
                                ?>
                                        <TD bgcolor=<?print $color;?>><a href=atender.php?numero=<?print mysql_result($resultado,$i,0);?>>Sim</a></TD>
                                <?
                                }
                                else
                                {
                                ?>
                                        <TD bgcolor=<?print $color;?>>N�o</TD>
                                <?
                                }
                        ?>
                        <?print "</TR>";
                $i++;
                }
                print "</TABLE>";
        }
?>


</body>
</html>
