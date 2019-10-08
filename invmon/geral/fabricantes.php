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
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;
		if ($popup) {
			$auth->testa_user_hidden($s_usuario,$s_nivel,$s_nivel_desc,2);

		} else
			$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);



        $cor  = TAB_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;


        $query = "SELECT f.*, t.* FROM fabricantes as f, tipo_item as t where
					f.fab_tipo = t.tipo_it_cod ORDER BY tipo_it_desc,fab_nome";
        $resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);   // $resultado-1 porque não quero contar a linha N/A

        if ($linhas == 0)
        {
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' >";				
				print "<tr>";
				print "<TD width='400' align='left'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><B>Nenhum fabricante cadastrado. </B></font></font></TD>";
				print "<TD width='200' align='left' ><a href=incluir_fabricante.php><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>Incluir fabricante </font></font></a></td>";
				print "<TD width='224' align='left' ></td>";
				print "</tr>";
        } else
        {
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' >";				
				print "<tr>";
				print "<TD width='400' align='left'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><B>Foram encontrados <font color=red>$linhas</font> fabricantes cadastrados. </B></font></font></TD>";
				print "<TD width='200' align='left' ><a href=incluir_fabricante.php><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>Incluir fabricante </font></font></a></td>";
				print "<TD width='224' align='left' ></td>";
				print "</tr>";

        }
        print "</TD>";

        print "<TD>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
        print "<TR class='header'><TD><b>fabricante</TD><TD><b>Tipo</TD><TD><b>Alterar</TD><TD><b>Excluir</TD>";
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
				print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
                ?>

                <td><? print $row["fab_nome"];?></td>
                <td><? print $row["tipo_it_desc"];?></td>
                <?
				print "<td><a onClick=\"redirect('altera_dados_fabricante.php?fab_cod=".$row['fab_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
				print "<td><a onClick=\"confirma('Tem Certeza que deseja excluir esse registro do sistema?','exclui_dados_fabricante.php?fab_cod=".$row['fab_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
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
