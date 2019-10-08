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
<B>Encerramento de ocorrências</B>
<BR>

<FORM method="POST" action=<?PHP_SELF?>>
<TABLE border="1"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>N�mero:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="numero" maxlength="100" size="10"></TD>
        </TABLE>
        </TR>
        <TR>
        <TR>
        <TABLE border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <BR>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="    Ok    " name="ok"></TD>
                        <input type="hidden" name="rodou" value="sim">
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar"></TD>
        </TABLE>
        </TR>
        <?
                if ($rodou == "sim")
                {
                        $query  = "SELECT * FROM ocorrencias WHERE numero='$numero'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);
                        if ($linhas==0)
                        {
                                $aviso = "Nenhuma ocorrência localizada.";
                                $origem = "encerra_ocorrencia.php";
                                session_register("aviso");
                                session_register("origem");
                                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                        }
                        else
                        {
                                if (mysql_result($resultado,$i,11)==4)
                                {
                                        $operador = mysql_result($resultado,$i,8);
                                        echo mensagem("ocorrência numero $numero j� encerrada por $operador.");
                                        exit;
                                }
                                $cor=TAB_COLOR;
                                $cor1=TD_COLOR;

                                print "<BR>";
                                print "<TD>";
                                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'";
                                print "<TR><TD bgcolor=$cor1>N�mero</TD><TD bgcolor=$cor1>Problema</TD><TD bgcolor=$cor1>Contato</TD><TD bgcolor=$cor1>Operador</TD>
                                        <TD bgcolor=$cor1>Local</TD><TD bgcolor=$cor1>Abertura</TD><TD bgcolor=$cor1>Status</TD></TR>";
                                $i = 0;
                                while ($i < $linhas)
                                {
                                        ?>
                                        <TR>
                                        <TD bgcolor="white"><a href=encerramento.php?numero=<?print mysql_result($resultado,$i,0);?>><?print mysql_result($resultado,$i,0);?><a></TD>
                                        <?
                                                $problemas = mysql_result($resultado,$i,1);
                                                $query = "SELECT * FROM problemas WHERE prob_id='$problemas'";
                                                $resultado3 = mysql_query($query);
                                        ?>
                                        <TD bgcolor=<?print $color;?>><?print mysql_result($resultado3,0,1);?></TD>
                                        <TD bgcolor="white"><?print mysql_result($resultado,$i,5);?></TD>
                                        <TD bgcolor=<?print $color;?>><?print mysql_result($resultado,$i,8);?></TD>
                                        <?
                                                $local = mysql_result($resultado,$i,7);
                                                $query = "SELECT * FROM localizacao WHERE loc_id='$local'";
                                                $resultado3 = mysql_query($query);
                                        ?>
                                        <TD bgcolor=<?print $color;?>><?print mysql_result($resultado3,0,1);?></TD>

                                        <TD bgcolor="white"><?print datab(mysql_result($resultado,$i,9));?></TD>
                                        <?
                                                $status = mysql_result($resultado,$i,11);
                                                $query = "SELECT * FROM status WHERE stat_id='$status'";
                                                $resultado3 = mysql_query($query);
                                        ?>
                                        <TD bgcolor=<?print $color;?>><?print mysql_result($resultado3,0,1);?></TD>
                                        <?
                                        $i++;
                                }
                        }
                }
        ?>
</TABLE>
</FORM>
</body>
</html>
