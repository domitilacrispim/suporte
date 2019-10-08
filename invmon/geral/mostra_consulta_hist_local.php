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
	$cab->set_title(HTML_TITLE);
	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);

	$hoje = date("Y-m-d H:i:s");


	$clausula = "";
	$msgData = "";
	if ((!empty($dInicio)) and (!empty($dFinal))) {

		$min= $dInicio;
		$max= $dFinal;			 
		 $dInicio =substr(datam($dInicio),0,10);
		 $dFinal =substr(datam($dFinal),0,10);
		$clausula = "and h.hist_data between '$dInicio' and '$dFinal'"; 
		$msgData = "no período entre $min e $max";
	}

	if ($comp_local!=-1) {
	   $clausula.= " and h.hist_local = $comp_local";	
		$setorOk = true;
		
	} else {
		$setorOK = false;		
	}
 
 
 if (!empty($comp_local)) {
 
 
 $query = "SELECT c.comp_inv AS etiqueta, c.comp_inst AS instituicao, c.comp_local AS tipo_local, 
 			i.inst_nome AS instituicao_nome, c.comp_tipo_equip AS tipo, t.tipo_nome AS equipamento, 
			l.local AS locais, l.loc_id as local_cod, m.marc_nome as modelo, m.marc_cod as tipo_marca, f.fab_nome as fabricante,
			f.fab_cod as tipo_fab, s.situac_cod as situac_cod,
			h.hist_data AS DATA , extract(DAY FROM hist_data ) AS DIA, 
			extract(MONTH FROM hist_data ) AS MES, extract( year FROM hist_data ) AS ANO
			FROM equipamentos AS c, instituicao AS i, localizacao AS l, historico AS h, 
			tipo_equip AS t, marcas_comp as m, fabricantes as f, situacao as s
			
			WHERE
			 c.comp_inv = h.hist_inv AND c.comp_inst = h.hist_inst AND c.comp_fab = f.fab_cod and
			h.hist_local = l.loc_id AND h.hist_inv = c.comp_inv  AND i.inst_cod = h.hist_inst AND
			 c.comp_tipo_equip = t.tipo_cod AND m.marc_cod = c.comp_marca and c.comp_situac = s.situac_cod
			";

		$equip='';
		
		if (($comp_tipo_equip != -1) and ($comp_tipo_equip!='')) 
		{
			
			 $query.= " and t.tipo_cod = $comp_tipo_equip";		
		} else $equip = "Todos";
			 
		
		$query.=" AND c.comp_local <> h.hist_local $clausula group by etiqueta ORDER BY equipamento,etiqueta";
			 

        $resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);
        $row = mysql_fetch_array($resultado);
########################################################################################################

		if (strlen($equip)<4) {
			$equip=$row['equipamento'];    
		}
		
		if ($setorOk) {
			$msg2= $row['locais'];
		} else {
			$msg2= "TODOS";
		}
		

        if ($linhas == 0)
        {
				echo mensagem("Não foi encontrado nenhum registro de remanejamento de equipamento do tipo consultado apartir do setor selecionado!<br><a href=consulta_hist_local.php>Voltar</a>");
				exit;
		} else
				//print "<TR><TD bgcolor=$cor1><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt><B>Foram encontrados <font color=red>$linhas</font> registros de equipamentos do tipo </b><i>$equip</i><b> que foram remanejados a partir do setor <i><a href=mostra_consulta_comp.php?comp_local=$row[local_cod]&ordena=equipamento,fab_nome,modelo,etiqueta title='Exibe a listagem de equipamentos cadastrados no(a) ".$row['locais'].".'>$row[locais]</a></i>.</B></font></font></TD></TR><br><br>";
				print "<TR><TD bgcolor=$cor1><B>Foram encontrados <font color=red>$linhas</font> registros de equipamentos do tipo </b><i>$equip</i><b> que foram remanejados a partir do setor <i><a href=mostra_consulta_comp.php?comp_local=$row[local_cod]&ordena=equipamento,fab_nome,modelo,etiqueta title='Exibe a listagem de equipamentos cadastrados no(a) $row[locais]'>$msg2</a></i> $msgData.</b></TD></TR>"; 			
			print"<br><br>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' >";
        	print "<TR class='header'><TD><b>Etiqueta</TD><TD><b>Unidade</TD><TD><b>Tipo</TD><TD>Modelo</TD><TD><b>Localização atual</TD><TD><b>Histórico</TD>";
        $i=0;
        $j=2;
        // while ($i < $linhas)
  if (($resultado = mysql_query($query)) && (mysql_num_rows($resultado) > 0) ) {
  while ($row = mysql_fetch_array($resultado)) {


                if ($j % 2)
                {
                        $trClass= "lin_par";
                        if (($row['situac_cod']==4)or ($row['situac_cod']==5)) { //Equipamento trocado ou furtado!!
							$color='#FF0000';
							$alerta = "style='{color:white;}'";
						} else {
							$color =  BODY_COLOR;
                			$alerta = "";                
						}

                }
                else
                {
                       $trClass= "lin_impar";
                        if (($row['situac_cod']==4)or ($row['situac_cod']==5)) { //Equipamento trocado ou furtado!!
							$color='#FF0000';
							$alerta = "style='{color:white;}'";
						} else {
							$color =  white;
                			$alerta = "";                
						}

                }
                $j++;
				
				$local_atual = $row['tipo_local'];
				$queryB = "Select l.local as loc_atual from localizacao as l where l.loc_id = $local_atual";
				$resultadoB = mysql_query($queryB);
				$rowB = mysql_fetch_array($resultadoB);
                
				
				?>
                <TR class='<?print $trClass;?>'>
                <TD><a <?echo $alerta;?> href=mostra_consulta_inv.php?comp_inv=<?print $row["etiqueta"]?>&comp_inst=<?print $row["instituicao"]?> title="Exibe os detalhes de cadastro desse equipamento."><?print $row["etiqueta"];?></a></TD>
                <td><a <?echo $alerta;?> href=mostra_consulta_comp.php?comp_inst=<?print $row["instituicao"]?>&ordena=equipamento,fab_nome,modelo,local,etiqueta title="Exibe a listagem de equipamentos cadastrados para Unidade <?echo $row['instituicao_nome']?>."><?print $row["instituicao_nome"]?></a></td>
                <td><a <?echo $alerta;?> href=mostra_consulta_comp.php?comp_tipo_equip=<?print $row["tipo"]?>&ordena=fab_nome,modelo,local,etiqueta title="Exibe a listagem de todos equipamentos do tipo <?echo $row['equipamento']?> cadastrados no sistema."><? print $row["equipamento"]?></a></td>
                <td><a <?echo $alerta;?> href=mostra_consulta_comp.php?comp_marca=<?print $row["tipo_marca"]?>&ordena=local,etiqueta title="Exibe a listagem de todos equipamentos do modelo <? print $row["fabricante"].' '.$row["modelo"]?>."><? print $row["fabricante"].' '.$row["modelo"]?></a></td>
                <TD><a <?echo $alerta;?> href=mostra_consulta_comp.php?comp_local=<?print $row["tipo_local"]?>&ordena=equipamento,fab_nome,modelo,etiqueta title="Exibe a listagem de equipamentos cadastrados no(a) <? print $rowB["loc_atual"]?>."><? print $rowB["loc_atual"]?></a></td>
                <TD><a <?echo $alerta;?> href=mostra_historico.php?comp_inst=<?print $row["instituicao"]?>&comp_inv=<?print $row["etiqueta"]?> title="Exibe o histórico do equipamento selecionado.">Histórico</a></td>

                <?
                  /*      $problemas = mysql_result($resultado,$i,1);
                        $query = "SELECT * FROM problemas WHERE prob_id='$problemas'";
                        $resultado3 = mysql_query($query);   */
                print "</TR>";
                $i++;

        }
       }
        print "</TABLE>";


        print "<TABLE border='0' cellpadding='0' cellspacing='0' align='center' width='100%' bgcolor='$cor3'>";
        print "<TR width=100%>";
        print "&nbsp;";
        print "</TR>";

        print "<TD>";

 	}

		else { //Se não for passado o código de inventário e a Unidade como parâmetro!!
						$aviso = "Dados incompletos, preencha todos os campos de consulta!";
                        $origem = "consulta_inv.php";
                        session_register("aviso");
                        session_register("origem");
                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";	


			}
		//echo "$query";
//echo "$row[ccusto]";

?>
</BODY>
</HTML>


        
				


