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

	$s_page_invmon = "estoque.php";
	session_register("s_page_invmon");			
	
	$cab = new headers;
	$cab->set_title(HTML_TITLE);

	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);

        $hoje = date("Y-m-d H:i:s");


		
        $query = "SELECT * FROM estoque, itens, modelos_itens, localizacao where estoq_tipo = item_cod
					and estoq_tipo = mdit_tipo and estoq_desc = mdit_cod and estoq_local = loc_id
					order by item_nome, estoq_desc";
        $resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);   // $resultado-1 porque não quero contar a linha N/A

        if ($linhas == 0)
        {
                echo "<b><p align=center> Não foi encontrado nenhum item em estoque no sistema.<br><a href=incluir_estoque.php>Incluir item em estoque</a></b></p>";
                exit;
        }
        
		if ($linhas>1){
                //print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' >";				
                print "<br>";
				print "<table class=corpo>";
				print "<tr>";
				print "<TD width='400' align='left'><B>Foram encontrados <font color=red>$linhas</font> itens em estoque</B></TD>";
	//			print "<TD width='200' align='left' ><a href=incluir_item.php>Incluir componente</a></td>";
				print "<TD width='224' align='left' ><a href=incluir_estoque.php>Incluir item em estoque</a></td>";
				print "</tr>";
				print "</table>";
																																																					    			    
		}
		else
               {
                print "<br><TR><TD><B>Foi encontrada somente 1  item em estoque no sistema.</B></TD></TR>";
                print "<TD width='624' align='left' ><a href=incluir_estoque.php>   Incluir item em estoque</a></td>";
               }
             print "</td>";

        print "<TD>";
        //print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'>";
        print "<table width='100%'>";
		print "<TR class='header'><Td><b>Tipo</TD><TD><b>Descrição</TD><TD><b>Quantidade</TD><TD><b>Localização</TD><TD><b>Comentário</TD><TD><b>Alterar</TD><TD><b>Excluir</TD>";
        $i=0;
        $j=2;
        while ($row = mysql_fetch_array($resultado))
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
				print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
                ?>
                <TD><? print $row['item_nome'];?></TD>
                <td><? print $row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade']." ".$row['mdit_sufixo'];?></td>
                <td><? print $row['estoq_qnt'];?></td>
                <td><? print $row['local'];?></td>
                <td><? print $row['estoq_comentario'];?></td>
                <TD><a href=altera_dados_estoque.php?estoq_cod=<?print $row['estoq_cod'];?>>Alterar</a></TD>
                <TD><a href=exclui_dados_estoque.php?estoq_cod=<?print $row['estoq_cod'];?>>Excluir</a></TD>


                <?
                print "</TR>";
                $i++;
        }
        print "</TABLE>";

/*
        print "<TABLE border='0' cellpadding='0' cellspacing='0' align='center' width='100%' bgcolor='$cor3'>";
        print "<TR width='100%'>";
        print "&nbsp;";
        print "</TR>";

        print "<TD>";
*/

?>
</BODY>
</HTML>
