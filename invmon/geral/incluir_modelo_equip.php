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

include ("var_sessao.php");
include ("funcoes.inc");
include ("config.inc.php");
include ("logado.php");


?>

<HTML>
<BODY bgcolor=<?print BODY_COLOR?>>

<?

        if ($s_nivel!=1)
        {
                echo "<META HTTP-EQUIV=REFRESH   CONTENT=\"0;
                        URL=index.php\">";
        }
?>
<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TAB_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>InvMon - Controle de Invent�rio  -  Usu�rio: <font color=red>$s_usuario</font></b></TD>";
                        if ($s_nivel==1)
                        {
                                
								echo menu_usuario_admin(TD_COLOR);
                        } 
						else
						        echo menu_usuario();
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>

<BR>
<B>Inclus�o de tipos de equipamentos</B>
<BR>

<FORM method="POST" action=<?PHP_SELF?>>
<TABLE border="1"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TAB_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Tipo:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="tipo_nome" maxlength="30" size="100"></TD>
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
                        $erro="n�o";

                        if (empty($tipo_nome))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM tipo_equip WHERE tipo_nome='$tipo_nome'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas > 0)
                        {
                                $aviso = "Esse equipamento j� est� cadastrado!";
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM marcas_comp";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);
                        $num=0;
                        if ($linhas>0)
                                $num = mysql_result($resultado,$linhas-1,0);
                        $num++;

                        if ($erro == "n�o")
                        {
                                $query = "INSERT INTO tipo_equip (tipo_nome) values ('$tipo_nome')";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao incluir equipamento.";
                                }
                                else
                                {
                                        $aviso = "OK. Equipamento incluido com sucesso.";
                                }
                        }
                        $origem = "incluir_modelo_equip.php";
                        session_register("aviso");
                        session_register("origem");
                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                }
        ?>


</TABLE>
</FORM>

</body>
</html>
