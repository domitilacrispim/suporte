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

	$s_page_admin = "sistemas.php";
	session_register("s_page_admin");	

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);	
	?>

        <BR>
        <B>Áreas de atendimento</B>
        <BR>

        <?

        $query = "SELECT * from sistemas order by sistema";
        $resultado = mysql_query($query);
        print "<TD align='right'><a href=incluir_sistema.php>Incluir área de atendimento</a></TD><BR>";
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem("Não há nenhuma área cadastrada.");
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<TD>";
                print "Existe(m) <b>$linhas</b> áreas(s) de atendimento cadastrada(s).<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%'>";
                print "<TR class='header'><TD><b>Área</b></TD><TD><b>Atende chamados</b><TD><b>E-mail</b></TD><TD><b>Status</b></TD><TD><b>Alterar</b></TD><TD><b>Excluir</b></TD>";
                $j=2;
                $i=0;
				while ($row=mysql_fetch_array($resultado))
                {
                        if ($j % 2)
                        {
								$trClass = "lin_par";
                        }
                        else
                        {
								$trClass = "lin_impar";
                        }
                        $j++;
						if ($row['sis_status'] == 0) $status ='INATIVO'; else $status = 'ATIVO';
						print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
                        ?>
                        <TD><?print $row['sistema'];?></TD>
						<TD><?print transbool($row['sis_atende']);?></TD>
						<TD><?print $row['sis_email'];?></TD>
						<TD><?print $status;?></TD>
						<?
                        print "<td><a onClick=\"redirect('altera_dados_sistema.php?sis_id=".$row['sis_id']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
                        print "<td><a onClick=\"confirma('Tem Certeza que deseja excluir essa área do sistema?','exclui_dados_sistema.php?sis_id=".$row['sis_id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
						
						
						print "</TR>";
                      
					$i++;
				}
                print "</TABLE>";
         }
        ?>


</body>
</html>
