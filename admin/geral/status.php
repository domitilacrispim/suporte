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

	$s_page_admin = "status.php";
	session_register("s_page_admin");

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);	
	?>
        <BR>
        <B>Cadastro de Status</B>
        <BR>

        <?

        $query = "SELECT S.*, STC.*
			FROM `status`  as S left join status_categ as STC on S.stat_cat = STC.stc_cod order by S.status";
        $resultado = mysql_query($query);
        print "<TD align=right bgcolor=$cor1><a href=incluir_status.php> Incluir status </a></TD><BR>";
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem("Não há nenhum status cadastrado.");
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<TD>";
                print "Existe(m) <b>$linhas</b> status cadastrado(s).<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'>";
                print "<TR class='header'><TD><b>Status</b></TD><TD><b>Dependência</b></TD><TD><b>Painel</b></TD><TD><b>Alterar</b></TD><TD><b>Excluir</b></TD>";
                $i=0;
                $j=2;
                while ($row =mysql_fetch_array($resultado))
                {
                        if ($j % 2)
                        {
                                $color =  BODY_COLOR;
								$trClass = "lin_par";
                        }
                        else
                        {
                                $color = white;
								$trClass = "lin_impar";
                        }
                        $j++;
						//Não existe tabela para definir o paineis então faço esse controle para imprimir qual é o painel
						if ($row['stat_painel'] == 1) $painel = "Superior"; else
						if ($row['stat_painel'] == 2) $painel = "Inferior"; else						
						if ($row['stat_painel'] == 3) $painel = "Oculto"; else
							$painel = "";
					   print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
					   ?>
                        <TD><?print $row['status'];?></TD>
						<TD><?print $row['stc_desc'];?></TD>
						<TD><?print $painel;?></TD>
                        <?
                        print "<td><a onClick=\"redirect('altera_dados_status.php?stat_id=".$row['stat_id']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
                        print "<td><a onClick=\"confirma('Tem Certeza que deseja excluir esse status do sistema?','exclui_dados_status.php?stat_id=".$row['stat_id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
						
						
						print "</TR>";
                        $i++;
                }
                print "</TABLE>";
         }
        ?>

</body>
</html>
