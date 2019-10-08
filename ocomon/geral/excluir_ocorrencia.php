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

        $query = "select * from ocorrencias where numero='$numero'";
        $resultado = mysql_query($query);

        $numero=mysql_result($resultado,0,0);

        $query2 = "select * from assentamentos where ocorrencia='$numero'";
        $resultado2 = mysql_query($query2);
        $linhas=mysql_numrows($resultado2);


?>

<HTML>
<BODY bgcolor=<?php echo BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?php echo TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?php echo TAB_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>OcoMon - M�dulo de Administra��o</b></TD>";
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
<B>Exclus�o de ocorrências<BR>
ATEN��O - Confira TODOS os dados antes de excluir um ocorrência. A exclus�o � definitiva.</B>
<BR>

<FORM method="POST" action=<?PHP_SELF?>>
<TABLE border="1"  align="center" width="100%" bgcolor=<?php echo TAB_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php echo TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>N�mero:</TD>
                <TD width="80%" align="left" bgcolor=<?php echo BODY_COLOR?>><?php echo mysql_result($resultado,0,0);?><TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php echo TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Problema:</TD>
                <?
                        $problemas = mysql_result($resultado,0,1);
                        $query = "SELECT * FROM problemas WHERE prob_id=$problemas";
                        $resultado3 = mysql_query($query);
                ?>
                <TD width="30%" align="left" bgcolor=<?php echo BODY_COLOR?>><?php echo mysql_result($resultado3,0,1);?></TD>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Sistema:</TD>
                <?
                        $sistemas = mysql_result($resultado,0,4);
                        $query = "SELECT * FROM sistemas WHERE sis_id=$sistemas";
                        $resultado3 = mysql_query($query);
                ?>
                <TD width="30%" align="left" bgcolor=<?php echo BODY_COLOR?>><?php echo mysql_result($resultado3,0,1);?></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php echo TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?> valign="top">Descri��o:</TD>
                <TD width="80%" align="left" bgcolor=<?php echo BODY_COLOR?>><?php echo mysql_result($resultado,0,2);?></TD>
        </TABLE>
        </TR>

        <?

        if ($linhas!=0)
        {
                $i=0;
                while ($i < $linhas)
                {
                        ?>
                        <TABLE border="1"  align="center" width="100%" bgcolor=<?php echo TAB_COLOR?>>
                                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?> valign="top">Assentamento <?php echo $i+1;?> de <?php echo $linhas;?>:</TD>
                                <TD width="40%" align="left" bgcolor=<?php echo BODY_COLOR?> valign="top"><?php echo nl2br(mysql_result($resultado2,$i,2));?></TD>
                                <TD width="5%" align="left" bgcolor=<?php echo TD_COLOR?> valign="top">Data:</TD>
                                <TD width="15%" align="left" bgcolor=<?php echo BODY_COLOR?> valign="top"><?php echo datab(mysql_result($resultado2,$i,3));?></TD>
                                <TD width="10%" align="left" bgcolor=<?php echo TD_COLOR?> valign="top">Respons�vel:</TD>
                                <TD width="10%" align="left" bgcolor=<?php echo BODY_COLOR?> valign="top"><?php echo mysql_result($resultado2,$i,4);?></TD>
                        </TABLE>
                        </TR>
                        <?
                        $i++;
                }
        }
        ?>

        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php echo TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Equipamento:</TD>
                <TD width="80%" align="left" bgcolor=<?php echo BODY_COLOR?>><?php echo mysql_result($resultado,0,3);?></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php echo TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Contato:</TD>
                <TD width="30%" align="left" bgcolor=<?php echo BODY_COLOR?>><?php echo mysql_result($resultado,0,5);?></TD>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Ramal:</TD>
                <TD width="30%" align="left" bgcolor=<?php echo BODY_COLOR?>><?php echo mysql_result($resultado,0,6);?></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php echo TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Local:</TD>
                <?
                        $local = mysql_result($resultado,0,7);
                        $query = "SELECT * FROM localizacao WHERE loc_id='$local'";
                        $resultado3 = mysql_query($query);
                ?>
                <TD width="30%" align="left" bgcolor=<?php echo BODY_COLOR?>><?php echo mysql_result($resultado3,0,1);?></TD>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Operador:</TD>
                <TD width="30%" align="left" bgcolor=<?php echo BODY_COLOR?>><?php echo mysql_result($resultado,0,8);?></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php echo TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Data de abertura:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print datab(mysql_result($resultado,0,9));?></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Status:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado,0,11);?></TD>
        </TABLE>
        </TR>
        <TR>
        <TR>
        <TABLE border="0"  cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <BR>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                                <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar" onClick="redirect('ocorrencias.php')"></TD>
        </TABLE>
        </TR>

        <?
                        if ($rodou == "sim")
                        {
                                $query = "DELETE FROM ocorrencias WHERE numero=$numero";
                                $resultado = mysql_query($query);

                                $query2 = "delete from assentamentos where ocorrencia = $numero";
                                $resultado2 = mysql_query($query2);
                                
                                $query3 = "delete from tempo_status where ts_ocorrencia = $numero";
								$resultado3 = mysql_query($query3);
								
								
								if (($resultado == 0) || ($resultado2==0) || ($resultado3==0)) 
                                {
                                        $aviso = "Um erro ocorreu ao tentar excluir a ocorrência do sistema.";
                                }
                                else
                                {
                                        $aviso = "OK. ocorrência excluida com sucesso.";
                                }
                                $origem = "ocorrencias.php";
                                session_register("aviso");
                                session_register("origem");
                                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                        }
        ?>

</TABLE>
</FORM>

</body>
</html>
