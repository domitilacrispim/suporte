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
	
	$s_page_invmon = "estat_modelo_memoria.php";
	session_register("s_page_invmon");			

	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);
	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);

        $hoje = date("Y-m-d H:i:s");

		
        $cor  = TAB_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;

		$queryB = "SELECT count(*) from equipamentos where comp_memo is not null";
		$resultadoB = mysql_query($queryB);
		$total = mysql_result($resultadoB,0);
		
		
		$queryAux="SELECT count(*) AS quantidade, m.marc_cod AS cod_marca, m.marc_nome AS modelo
					FROM marcas_comp AS m, equipamentos AS c
					WHERE c.comp_marca = m.marc_cod group by m.marc_nome";
					
		$resultadoAux = mysql_query($queryAux);
	  	$linhasAux = mysql_num_rows($resultadoAux);
		$rowAux = mysql_fetch_array($resultadoAux);  
		
#######################################################################################################
			
       print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='80%' bgcolor='$cor3'>";
                
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print "<tr><td width=80% align=center><b>Modelos de equipamento por distribuição de memória:</b></td></tr>";

  
        print "<TD>";
        print "<fieldset><legend>Modelo X memória</legend>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='80%' bgcolor='$cor3'>";
        print "<TR><TD bgcolor=$cor3><b>Equipamento</TD><TD bgcolor=$cor3><b>Modelo</TD><TD bgcolor=$cor3><b>Memória</TD><TD bgcolor=$cor3><b>Quantidade</TD><TD bgcolor=$cor3><b>Percentual por modelo</TD></tr>";        
  
#######################################################################################################

		
		  if (($resultadoAux = mysql_query($queryAux)) && (mysql_num_rows($resultadoAux) > 0) ) {
		  	while ($rowAux = mysql_fetch_array($resultadoAux)) {
		
				$tipo_modelo = $rowAux['cod_marca'];
				$qtd_equip = $rowAux['quantidade'];
		
		
		
		$query= "SELECT count( m.marc_nome ) AS qtd, count( * ) /$qtd_equip * 100 AS porcento, count(me.mdit_desc_capacidade)as qtd_memo, 
					t.tipo_nome AS equipamento, f.fab_nome AS fabricante, m.marc_nome AS modelo, me.mdit_desc_capacidade as memoria, 
					me.mdit_cod as memo_cod, m.marc_cod AS tipo_modelo
					FROM equipamentos AS c, marcas_comp AS m, tipo_equip AS t, fabricantes AS f, modelos_itens as me
					WHERE c.comp_marca = m.marc_cod AND comp_tipo_equip = t.tipo_cod AND comp_fab = f.fab_cod and 
					c.comp_memo=me.mdit_cod and me.mdit_tipo=7 and c.comp_marca = $tipo_modelo
					GROUP BY modelo, memoria
					ORDER BY fabricante,modelo,qtd_memo, memoria,equipamento DESC";		
		
		
			$resultado = mysql_query($query);
    	    $linhas = mysql_num_rows($resultado);
			$row = mysql_fetch_array($resultado);  
		
		
#######################################################################				
######################################################################################

  
  if (($resultado = mysql_query($query)) && (mysql_num_rows($resultado) > 0) ) {
  while ($row = mysql_fetch_array($resultado)) {
                $color =  BODY_COLOR;
              
                ?>
                <TR>

				<TD bgcolor=<?print $color;?>><?print $row['equipamento']?></TD>
                <TD bgcolor=<?print $color;?>><a href=mostra_consulta_comp.php?comp_marca=<?print $row['tipo_modelo']?>&comp_memo=<?print $row['memo_cod']?>&ordena=fab_nome,modelo,local,etiqueta title='Exibe a relação de equipamentos desse modelo cadastrados no sistema.'><?print $row['fabricante'].' '.$row['modelo']?></TD>
				<TD bgcolor=<?print $color;?>><?print $row['memoria'].' MB'?></TD>
				<TD bgcolor=<?print $color;?>><?print $row['qtd_memo']?></TD>
				<TD bgcolor=<?print $color;?>><?print $row['porcento'].'%'?></TD>
                <?
                print "</TR>";
              

        }
       }
					
					
		}        
	}
		
		print "<TR><TD bgcolor=$cor3><b></TD><TD bgcolor=$cor3><b>
				</TD><TD bgcolor=$cor3><b></TD>
				<TD bgcolor=$cor3><b>Total: $total</TD>
				<TD bgcolor=$cor3><b>100%</b></TD></tr>";        												
					print "</TABLE>";
					print "</fieldset>";
				
					
					
###############################################################################					
					
					
					print "<TABLE width=80% align=center>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";

					//print "<tr><td width=80% align=center><b><a href=relatorio_geral.php>Relatório Geral</a>.</b></td></tr>";				
					print "</TABLE>";				


					print "<TABLE width=80% align=center>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";

					print "<tr><td width=80% align=center><b>Sistema em desenvolvimento pelo setor de Helpdesk  do <a href='http://www.unilasalle.edu.br' target='_blank'>Unilasalle</a>.</b></td></tr>";				
					print "</TABLE>";				
				

              
?>        


</BODY>
</HTML>
