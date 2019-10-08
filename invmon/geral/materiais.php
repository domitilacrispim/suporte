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
	
	$s_page_invmon = "materiais.php";
	session_register("s_page_invmon");		
	
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);

	$traduz = array("mat_cod" => $TRANS["col_codigo"],
		"mat_nome"=> $TRANS["col_descricao"],
		"mat_caixa" =>$TRANS["col_caixa"],
		"mat_qtd"=> $TRANS["col_quantidade"]
		); 





		$stilo = "style='{height:17px; width:30px; background-color:#DDDCC5; color:#5E515B; font-size:11px;}'"; //Estilo dos botões de navegação
		$stilo2 = "style='{height:17px; width:50px; background-color:#DDDCC5; color:#5E515B;font-size:11px;}'";


		$queryTotal = "SELECT * from materiais";
		$resultadoTotal = mysql_query($queryTotal);
        $linhasTotal = mysql_num_rows($resultadoTotal);

		
		$query = "SELECT mat.* , marc.* 
								FROM materiais AS mat
								LEFT  JOIN marcas_comp as marc ON mat.mat_modelo_equip = marc.marc_cod ";
		
		
      		if (empty($ordena)) {
		$ordena="mat_cod";}
		$query.= " order by $ordena";

		$traduzOrdena = strtr("$ordena", $traduz);		
		//$query = "SELECT * from materiais order by $ordena";        

		
				if (empty($min))  {
					$min =0; //Posso passar esse valor direto por parâmetro se eu quiser!!
				};
				if (empty($max))  {
					$max =50; //Posso passar esse valor direto por parâmetro se eu quiser!!
					if ($max>$linhasTotal) {$max=$linhasTotal;};
					$maxAux = $max;
				};

				if ($avanca==">") {
					$min+=$max;
					if ($min >($linhasTotal-$max)) {$min=($linhasTotal-$max);};
				}else
				if ($avanca==">>") {
					$min=$linhasTotal-$max;
				} else
				if ($avanca=="".$TRANS["bt_todos"]."") {
					$max=$linhasTotal;
					$min=0;
				} else
				if ($avanca=="<") {
					if (($max==$linhasTotal)and ($min==0)) {$max=$maxAux; $min=$linhasTotal;}; //Está exibindo todos os registros na tela!
					$min-=$max;
					if ($min<0) {$min=0;};
				} else
				if ($avanca=="<<") {
					$min=0;
					$max=$maxAux;
				}
		
		$query.=" LIMIT $min,$max";	   
		
		
		$resultado = mysql_query($query);
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem($TRANS["alerta_nao_encontrado"]);
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<TD>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' >";
				?><FORM method="POST" action=<?PHP_SELF?>><?
				print "<TR>";
				$min++;
				if ($avanca=="Todos") {$top=$linhasTotal;} else$top=$min+($max-1);
				print "<br>";
				print "<TD width='750' align='left'><B>Foram encontrados <font color=red>$linhasTotal</font> documentos cadastrados ordenador por <u>$traduzOrdena</u>. Mostrados de <font color=red>$min</font> a <font color=red>$top</font>.</B></font></font></TD>";
				print "<TD width='50' align='left' ></td>";
				print "<TD width='224' align='left' ><input  type='submit' $stilo name='avanca' value='<<' title='Visualiza os $max primeiros registros.'> <input  type='submit' $stilo name='avanca' value='<' title='Visualiza os $max registros anteriores.'> <input  type='submit' $stilo name='avanca' value='>' title='Visualiza os próximos $max registros.'> <input  type='submit' $stilo name='avanca' value='>>' title='Visualiza os últimos $max registros.'> <input  type='submit' $stilo2 name='avanca' value=".$TRANS["bt_todos"]." title='Visualiza todos os $linhasTotal registros.'></td>";
				print "</tr>";
				$min--;
				print "<input type=hidden value=$min name=min>";
				print "<input type=hidden value=$max name=max>";
				print "<input type=hidden value=$maxAux name=maxAux>";
				print "</form>";
                print "</table>";
				
                print "<TABLE border='0' cellpadding='3' cellspacing='0' align='center' width='100%'>";
				print "<TR class='header'><TD><b><a href='materiais.php?ordena=mat_cod'>Codigo</a></b>
								</TD><TD><b><a href='materiais.php?ordena=mat_nome'>Descrição</a></b></TD>
								<TD><b>Modelo de equipamento</b>
								<TD><b><a href='materiais.php?ordena=mat_qtd'>Quantidade</a></b></TD>
								<TD><b><a href='materiais.php?ordena=mat_caixa'>Caixa</a></b><TD bgcolor=$cor1>Alterar</TD>
								<TD>Excluir</TD></TR>";
                $i=0;
                $j=2;
                while ($row = mysql_fetch_array($resultado))
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
                        <TD><?print $row['mat_cod'];?></TD>
                        <TD><?print $row['mat_nome'];?></TD>
						<TD><?print $row['marc_nome'];?></TD>
                        <TD><?print $row['mat_qtd'];?></TD>
                        <TD><?print $row['mat_caixa'];?></TD>
						<TD><a href=altera_dados_documento.php?mat_cod=<?print $row['mat_cod'];?>>Alterar</a></TD>
						<TD><a href=exclui_dados_documento.php?mat_cod=<?print $row['mat_cod'];?>>Excluir</a></TD>

                        <?print "</TR>";
                        $i++;
                }
                
                print "<TABLE border='0' cellpadding='3' cellspacing='0' align='center' width='100%' >";
				?><FORM method="POST" action=<?PHP_SELF?>><?
				print "<TR>";
				$min++;
				if ($avanca=="Todos") {$top=$linhasTotal;} else$top=$min+($max-1);
				print "<TD width='750' bgcolor='white' align='left'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><B>Foram encontrados <font color=red>$linhasTotal</font> documentos cadastrados. Mostrados de <font color=red>$min</font> a <font color=red>$top</font>.</B></font></font></TD>";
				print "<TD width='50' bgcolor='white' align='left'></td>";
				print "<TD width='224' bgcolor='white' align='left'><input type='submit' $stilo name='avanca' value='<<' title='Visualiza os $max primeiros registros.'> <input  type='submit' $stilo name='avanca' value='<' title='Visualiza os $max registros anteriores.'> <input  type='submit' $stilo name='avanca' value='>' title='Visualiza os próximos $max registros.'> <input  type='submit' $stilo name='avanca' value='>>' title='Visualiza os últimos $max registros.'> <input  type='submit' $stilo2 name='avanca' value='Todos' title='Visualiza todos os $linhasTotal registros.'></td>";
				print "</tr>";
				$min--;
				print "<input type=hidden value=$min name=min>";
				print "<input type=hidden value=$max name=max>";
				print "<input type=hidden value=$maxAux name=maxAux>";
				print "</form>";
                print "</table>";
				
				//print "<br>".$query;
				//print "</TABLE>";
         }
        ?>


</body>
</html>
