<?
 /*                        Copyright 2005 Fl?vio Ribeiro
  
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

$hoje = date("d/m/Y");

$query = "select * from ocorrencias where numero='$numero'";
        $resultado = mysql_query($query);

        if (mysql_numrows($resultado)>0)
        {
                $linhas = mysql_numrows($resultado)-1;
        }
        else
        {
                $linhas = mysql_numrows($resultado);
        }

        $query2 = "select * from assentamentos where ocorrencia='$numero'";
        $resultado2 = mysql_query($query2);
        $linhas2 = mysql_numrows($resultado2);
?>

<HTML>
<BODY bgcolor=<?print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TAB_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>OcoMon - M?dulo de Ocorr?ncias</b></TD>";
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
<B>Inclus?o de Assentamentos</B>
<BR>

<FORM method="POST" action=<?PHP_SELF?>>
<TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>N?mero:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado,$linhas,0);?><TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Problema:</TD>
                <?
                        $problemas = mysql_result($resultado,$linhas,1);
                        $query = "SELECT * FROM problemas WHERE prob_id='$problemas'";
                        $resultado3 = mysql_query($query);
                ?>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado3,0,1);?></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Sistema:</TD>
                <?
                        $sistemas = mysql_result($resultado,$linhas,4);
                        $query = "SELECT * FROM sistemas WHERE sis_id='$sistemas'";
                        $resultado3 = mysql_query($query);
                ?>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado3,0,1);?></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Descri??o:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado,$linhas,2);?></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
				<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Unidade:</TD>
				<?
				 		$instituicao = mysql_result($resultado,0,13);
                        $query = "SELECT * FROM instituicao WHERE inst_cod=$instituicao";
                        $resultado3 = mysql_query($query);
						$nomeinst = "";
						if (mysql_numrows($resultado3) > 0) {
							$nomeinst=mysql_result($resultado3,0,1);
						}
				?>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $nomeinst;?></TD>
		
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Etiqueta do equipamento:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado,0,3);?></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Contato:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado,$linhas,5);?></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Ramal:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado,$linhas,6);?></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Local:</TD>
                <?
                        $local = mysql_result($resultado,$linhas,7);
                        $query = "SELECT * FROM localizacao WHERE loc_id='$local'";
                        $resultado3 = mysql_query($query);
                ?>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado3,0,1);?></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Operador:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado,$linhas,8);?></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Data de abertura:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print datab(mysql_result($resultado,$linhas,9));?></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Status:</TD>
                <?
                        $status=mysql_result($resultado,0,11);
                        $query4 = "SELECT * FROM status WHERE stat_id=$status";
                        $resultado4 = mysql_query($query4);
                ?>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado4,0,1);?></TD>
        </TABLE>
        </TR>

        <?
        if ($linhas2!=0)
        {
                if ($linhas2==1)
                {
                        $i=$linhas2-1;
                }
                else
                {
                        $i=$linhas2-2;
                }
                while ($i < $linhas2)
                {
                        ?>
                        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Assentamento <?print $i+1;?> de <?print $linhas2;?>:</TD>
                                <TD width="40%" align="left" bgcolor=<?print BODY_COLOR?> valign="top"><?print nl2br(mysql_result($resultado2,$i,2));?></TD>
                                <TD width="5%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Data:</TD>
                                <TD width="15%" align="left" bgcolor=<?print BODY_COLOR?> valign="top"><?print datab(mysql_result($resultado2,$i,3));?></TD>
                                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Respons?vel:</TD>
                                <TD width="10%" align="left" bgcolor=<?print BODY_COLOR?> valign="top"><?print mysql_result($resultado2,$i,4);?></TD>
                        </TABLE>
                        </TR>
                        <?
                        $i++;
                }
        }
        ?>

        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Assentamento:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><TEXTAREA cols="75" rows="5" name="assentamento"></textarea></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE  border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <BR>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar"></TD>
        </TABLE>
        </TR>

        <?
				$assentamento = trim($assentamento);
				
                if (($rodou == "sim") and ($assentamento != ""))
                {
                        $data = datam($hoje);
                        $responsavel = $s_usuario;
                        $query = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel) values ($numero,'$assentamento', '$data', '$responsavel')";
                        $resultado3 = mysql_query($query);

                        if ($resultado3 == 0)
                        {
                                $aviso = "Um erro ocorreu ao tentar incluir um assentamento.";

                        }
                        else
                        {
                                $aviso = "Assentamento incluido com sucesso.";
                        }
                        $origem = "inclui_assentamentos.php";
                        session_register("aviso");
                        session_register("origem");
                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                }else{
						echo "<H1><CENTER>Assentamento n?o pode ser vazio!</CENTER></H1>";
				}

        ?>

</TABLE>
</FORM>
</body>
</html>
