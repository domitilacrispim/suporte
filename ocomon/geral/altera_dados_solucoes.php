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

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TAB_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>OcoMon - Módulo de Administração</b></TD>";
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
<B>Alteração de Soluções</B>
<BR>
<?
                $query = "SELECT * FROM solucoes WHERE numero=$numero";
                $resultado = mysql_query($query);
                ?>
                <FORM method="POST" action=<?PHP_SELF?>>
                <TABLE border="1"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
                <TR>
                <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                        <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Número:</TD>
                        <TD width="10%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado,0,0);?></TD>
                        <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>Data:</TD>
                        <TD width="10%" align="left" bgcolor=<?print BODY_COLOR?>><?print datab(mysql_result($resultado,0,3));?></TD>
                        <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Operador:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?
                $resultado4 = mysql_result($resultado,0,4);
                $resultado5 = mysql_result($resultado,0,4);
                print "<SELECT name='operador' size=1>";
                print "<option value=$resultado5 selected>$resultado4</option>";
                $query = "SELECT * from usuarios order by login";
                $resultado10 = mysql_query($query);
                $linhas10 = mysql_numrows($resultado10);
                $i=0;
                while ($i < $linhas10)
                {
                       ?>
                       <option value="<?print mysql_result($resultado10,$i,0);?>">
                                         <?print mysql_result($resultado10,$i,0);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
                </TABLE>
                </TR>

                <TR>
                <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                        <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Problema:</TD>
                        <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><TEXTAREA cols="84" rows="8" name="problema"><?print nl2br(mysql_result($resultado,0,1));?></textarea></TD>
                </TABLE>
                </TR>

                <TR>
                <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                        <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Solução:</TD>
                        <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><TEXTAREA cols="84" rows="8" name="solucao"><?print nl2br(mysql_result($resultado,0,2));?></textarea></TD>
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

                if ($rodou == "sim")
                {
                        $erro="não";

                        if (empty($problema) or empty($solucao))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

                        if ($erro = "não")
                        {
                                $query = "UPDATE solucoes SET responsavel='$operador', problema='$problema', solucao='$solucao' WHERE numero=$numero";
                                $resultado4 = mysql_query($query);

                                if ($resultado4 == 0)
                                {
                                        $aviso =  "Um erro ocorreu ao tentar alterar dados em soluções.";
                                }
                                else
                                {
                                        $aviso =  "Solução alterada com sucesso.";
                                }
                        }
                        $origem = "solucoes.php";
                        session_register("aviso");
                        session_register("origem");
                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                }

        ?>

</TABLE>
</FORM>

</body>
</html>
