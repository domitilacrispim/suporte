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
<html>
<BODY bgcolor=<?print BODY_COLOR?>>

<?
        print  "<b>USUÁRIO: <font color=red>$usuario</font></b>";
        echo menu_usuario();
        if ($usuario=='admin')
        {
                echo menu_admin();
        }
?>

<H2>Alteração de usuários</H2>

<FORM method="POST" action=altera_dados_usuario.php>
<TABLE border="1"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Login:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="login" maxlength="100" size="100"></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="0"  cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <BR>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="    Ok    " name="ok" onclick="ok=sim"></TD>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar"></TD>
        </TABLE>
        </TR>
</TABLE>
</FORM>

                <?
                /*
                if ($rodou=="sim");
                {
                        $query = "select * from usuarios where login='$login    '";
                        $resultado = mysql_query($query);

                        $linhas = mysql_numrows($resultado);

                        $cor=TAB_COLOR;
                        $cor1=TD_COLOR;

                        print "<HR>";
                        print "Resultado da consulta:";
                        print "<TD>";
                        print "<TABLE border='1' align='center' width='100%' bgcolor='$cor'";
                        print "<TR><TD bgcolor=$cor1>Login</TD><TD bgcolor=$cor1>Nome</TD><TD bgcolor=$cor1>Data de Inclusão</TD>
                        <TD bgcolor=$cor1>Data de admissão</TD><TD bgcolor=$cor1>E-mail</TD><TD bgcolor=$cor1>Ramal</TD></TR>";
                        $i=0;
                        while ($i < $linhas)
                        {
                                print "<TR>";?>
                                <TD <?print BODY_COLOR?>><a href=altera_dados_usuario.php?login=<?print mysql_result($resultado,$i,0);?>><?print mysql_result($resultado,$i,0);?><a></TD>
                                <TD <?print BODY_COLOR?>><?print mysql_result($resultado,$i,1);?></TD>
                                <TD <?print BODY_COLOR?>><?print mysql_result($resultado,$i,3);?></TD>
                                <TD <?print BODY_COLOR?>><?print mysql_result($resultado,$i,4);?></TD>
                                <TD <?print BODY_COLOR?>><?print mysql_result($resultado,$i,5);?></TD>
                                <TD <?print BODY_COLOR?>><?print mysql_result($resultado,$i,6);?></TD>
                                <?print "</TR>";
                                $i++;
                        }
                        print "</TABLE>";
                }
                */
                ?>

</body>
</html>