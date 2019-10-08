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
        include ("var_sessao.php");      // Tem que estar em primeiro por causa do header!
        include ("funcoes.inc");
        include ("config.inc.php");
        include ("logado.php");

        $hoje = date("Y-m-d H:i:s");

?>

<HTML>
<BODY bgcolor=<?print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TAB_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>InvMon - controle de inventário  -  Usuário: <font color=red>$s_usuario</font></b></TD>";
                        echo menu_usuario();
                        if ($s_usuario=='admin')
                        {
                                echo menu_admin(TD_COLOR);
                        }
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>


        <BR>
        <B>Cadastro de modelos:</B>
        <BR>
<?


        print "<TD align=right bgcolor=$cor1><a href=incluir_modelo.php>Incluir modelo</a></TD><BR>";
        $cor  = TAB_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;


        $query = "SELECT * FROM modelos where modelo_desc <> 'N/A' ORDER BY modelo_cod";
        $resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);   // $resultado-1 porque não quero contar a linha N/A

        if ($linhas == 0)
        {
                echo mensagem("Não foi encontrado nenhum modelo cadastrado no sistema.");
                exit;
        }
        if ($linhas>1)
                print "<TR><TD bgcolor=$cor1><B>Foram encontrados $linhas modelos cadastradas no sistema. </B></TD></TR>";
        else
                print "<TR><TD bgcolor=$cor1><B>Foi encontrado somente 1  modelo cadastrado no sistema.</B></TD></TR>";
        print "</TD>";

        print "<TD>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'>";
        print "<TR><TD bgcolor=$cor1><b>Código</TD><TD bgcolor=$cor1><b>modelo</TD><TD bgcolor=$cor1><b>Alterar</TD><TD bgcolor=$cor1><b>Excluir</TD>";
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
                <TD bgcolor=<?print $color;?>><a href=mostra_consulta.php?=emBreve<?print mysql_result($resultado,$i,0);?>><?print mysql_result($resultado,$i,0);?></a></TD>
                <td bgcolor=<?print $color;?>><? print mysql_result($resultado,$i,1);?></td>
                <TD bgcolor=<?print $color;?>><a href=altera_dados_modelo.php?modelo_cod=<?print mysql_result($resultado,$i,0);?>>Alterar</a></TD>
                <TD bgcolor=<?print $color;?>><a href=exclui_dados_modelo.php?modelo_cod=<?print mysql_result($resultado,$i,0);?>>Excluir</a></TD>


                <?
                  /*      $problemas = mysql_result($resultado,$i,1);
                        $query = "SELECT * FROM problemas WHERE prob_id='$problemas'";
                        $resultado3 = mysql_query($query);   */
                print "</TR>";
                $i++;
        }
        print "</TABLE>";


        print "<TABLE border='0' cellpadding='0' cellspacing='0' align='center' width='100%' bgcolor='$cor3'>";
        print "<TR width=100%>";
        print "&nbsp;";
        print "</TR>";

        print "<TD>";


?>
</BODY>
</HTML>
