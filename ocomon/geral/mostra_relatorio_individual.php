<?
 /*                        Copyright 2005 Flávio Ribeiro
  
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
<BODY>

<?
		

        //$query  = "SELECT * FROM ocorrencias WHERE numero='$numero'";
        $query = $QRY["ocorrencias_full_ini"]." where numero in (".$numero.") order by numero";
		$resultado = mysql_query($query);
		$row = mysql_fetch_array($resultado);
        $linhas = mysql_numrows($resultado);
		
		
		
		

        $query2 = "select * from assentamentos where ocorrencia='$numero'";
        $resultado2 = mysql_query($query2);
        $linhas2=mysql_numrows($resultado2);

        if ($linhas==0)
        {
                $aviso = "Nenhuma_ocorrencia_localizada.";
                $origem = "relatorio_individual.php";
                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php?aviso=$aviso&origem=$origem\">";
        }

        $linhas = 0;

        print "<BR><B>OcoMon - Relatório para atendimento.</B><BR>";
       
?>
<TABLE border="0" align="center" width="100%">
        <TR>
                <hr>
				<TD width="20%" align="left"><b>Número:</b></TD>
                <TD colspan='3' width="80%" align="left"><?print $row['numero'];?></TD>
        </TR>
        <TR>
                <TD width="20%" align="left"><b>Problema:<b></TD>
                <TD style="{text-align:justify;}" width="30%" align="left"><?print $row['problema'];?></TD>
                <TD width="20%" align="left"><b>Área de Atendimento:</b></TD>
                <TD width="30%" align="left"><?print $row['area'];?></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" valign="top"><b>Descrição:</b></TD>
                <TD  colspan='3' width="80%" align="left"><?print nl2br($row['descricao']);?></TD>
        </TR>
        <?
                if ($linhas2!=0)
                {
                        $i=0;
                        while ($i < $linhas2)
                        {
							$OP = mysql_result($resultado2,$i,4);
							$qryOP = "select * from usuarios where user_id = ".$OP."";
							$execOP = mysql_query($qryOP);
							$rowOP = mysql_fetch_array($execOP) or die($qryOP);
						?>
                                <TR>
                                        <TD width="20%" align="left" valign="top">Assentamento <?print $i+1;?> de <?print $linhas2;?> por <?print $rowOP['nome'];?> em <?print datab(mysql_result($resultado2,$i,3));?></TD>
                                        <TD style="{text-align:justify;}" colspan='3' width="40%" align="left" valign="top"><?print nl2br(mysql_result($resultado2,$i,2));?></TD>
                                </TR>
                        <?
                        $i++;
                        }
                }
        ?>
        <TR>
				<TD width="20%" align="left" valign="top"><b>Unidade:</b></TD>
                <TD width="30%" align="left" valign="top"><?print $row['unidade'];?></TD>
		
                <TD width="20%" align="left" valign="top"><b>Etiqueta do equipamento:</b></TD>
                <TD width="30%" align="left" valign="top"><?print $row['etiqueta'];?></TD>
        </TR>
        <TR>
                <TD width="20%" align="left"><b>Contato:</b></TD>
                <TD width="30%" align="left"><?print $row['contato'];?></TD>
                <TD width="20%" align="left"><b>Ramal:</b></TD>
                <TD width="30%" align="left"><?print $row['telefone'];?></TD>
        </TR>
        <TR>
                <TD width="20%" align="left"><b>Local:</b></TD>
                <TD width="30%" align="left"><?print $row['setor'];?></TD>
                <TD width="20%" align="left"><b>Operador:</b></TD>
                <TD width="30%" align="left"><?print $row['nome'];?></TD>
        </TR>

        <?
        //if (mysql_result($resultado,0,11) == 4)
		if ($row['status_cod']== 4)
        {
        ?>
                <TR>
                        <TD width="20%" align="left"><b>Data de abertura:</b></TD>
                        <TD width="30%" align="left"><?print datab($row['data_abertura']);?></TD>
                        <TD width="20%" align="left"><b>Data de encerramento:</b></TD>
                        <TD width="30%" align="left"><?print datab($row['data_fechamento']);?></TD>
				</tr>
				<tr>	
					<TD width="20%" align="left"><b>Status:</b></TD>
                    <TD colspan='3' width="80%" align="left" bgcolor="white"><?print $row['chamado_status'];?></TD>
                </TR>
        <?
        }
        else
        {
                ?>
                <TR>
                        <TD width="20%" align="left"><b>Data de abertura:</b></TD>
                        <TD width="30%" align="left"><?print datab($row['data_abertura']);?></TD>
                        <TD width="20%" align="left"><b>Status:<b></TD>
                        <TD width="30%" align="left" bgcolor="white"><?print $row['chamado_status'];?></TD>
                </TR>
                <?
        }

        ?>

       
		
        <TR>
            <TABLE border="0"  align="center" width="100%"> 
				<hr>
				<TD width="20%" align="left"><b>Atendimento em:</b></TD>
				<TD width="30%" align="left">&nbsp;</TD>
                <TD width="20%" align="left"><b>Operador:</b></TD>
                <TD width="30%" align="left">&nbsp;</TD>
				
			</table>
			<hr>
		</TR>

        
		
        <?
        $i = 0;
        while ($i<=10)
        {
        ?>
                <TR>
					<TABLE border="0"  align="center" width="100%"> 
                        <TD colspan='4' width="100%" align="center">
                                <?
                                $j = 0;
                                while ($j<160)
                                {
                                        print "-";
                                        $j++;
                                }
                                ?>
                        </TD>
					</table>
				</TR>
        <?
        $i++;
        }
        ?>

        

        <TR>
        
		<TABLE border="0"  align="center" width="100%">
		<hr>
                <TD width="20%" align="left">Nome do usuário:</TD>
                <TD width="30%" align="left">&nbsp;</TD>
                <TD width="20%" align="left">Assinatura do usuário:</TD>
                <TD width="30%" align="left">&nbsp;</TD>
        </TABLE>
        </TR>

</TABLE>


</BODY>
</HTML>


