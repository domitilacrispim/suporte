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

	$s_page_invmon = "estat_equipporlocal.php";
	session_register("s_page_invmon");			

	$cab = new headers;
	$cab->set_title(HTML_TITLE);
	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);
        
	$hoje = date("Y-m-d H:i:s");

		
        $cor  = TAB_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;

		$queryB = "SELECT count(*) from equipamentos";
		$resultadoB = mysql_query($queryB);
		$total = mysql_result($resultadoB,0);
		
		
		
				
		// Select para retornar a quantidade e percentual de equipamentos cadastrados no sistema
		$query= "select count(l.local)as qtd, count(*)/$total*100 as porcento, 
			l.local as local, l.loc_id as tipo_local, t.tipo_nome as equipamento, t.tipo_cod as tipo 
			from equipamentos as c, 
			tipo_equip as t, localizacao as l where c.comp_tipo_equip = t.tipo_cod 
			and c.comp_local = l.loc_id  group by local, equipamento order by qtd desc ,
			local asc";		
		
		$resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);
		$row = mysql_fetch_array($resultado);  
		
		
		
#######################################################################				
######################################################################################
			//Tabela de quantidade de equipamentos cadastrados por dia	
			
			//Tabela de quantidade de equipamentos cadastrados por dia	
			
       print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='80%' bgcolor='$cor3'>";
                
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print "<tr><td width=80% align=center><b>Total de equipamentos cadastrados por Setor:</b></td></tr>";

  
        print "<TD>";
        print "<fieldset><legend>Equipamentos X setor</legend>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='80%' bgcolor='$cor3'>";
        print "<TR><TD bgcolor=$cor3><b>Local</TD><TD bgcolor=$cor3><b>Equipamento</TD><TD bgcolor=$cor3><b>Quantidade</TD><TD bgcolor=$cor3><b>Percentual</TD></tr>";        
        $i=0;
        $j=2;
  
  
  if (($resultado = mysql_query($query)) && (mysql_num_rows($resultado) > 0) ) {
  while ($row = mysql_fetch_array($resultado)) {
                $color =  BODY_COLOR;
                $j++;
                ?>
                <TR>
                <TD bgcolor=<?print $color;?>><a href=mostra_consulta_comp.php?comp_tipo_equip=<?print $row['tipo']?>&comp_local=<?print $row['tipo_local']?>&ordena=modelo,etiqueta title='Exibe a listagem de equipamentos cadastrados desse tipo para esse local.'><?print $row["local"]?></TD>
				<TD bgcolor=<?print $color;?>><?print $row["equipamento"]?></TD>
				<TD bgcolor=<?print $color;?>><?print $row["qtd"]?></TD>
				<TD bgcolor=<?print $color;?>><?print $row["porcento"]."%"?></TD>
                <?
                print "</TR>";
                $i++;

        }
       }
        print "<TR><TD bgcolor=$cor3><b></TD><TD bgcolor=$cor3><b></TD><TD bgcolor=$cor3><b>Total: $total</TD><TD bgcolor=$cor3></TD></tr>";        												
		print "</TABLE>";
		print "</fieldset>";			
					
###############################################################################					
					
					
					print "<TABLE width=80% align=center>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";

					//print "<tr><td width=80% align=center><b><a href=relatorio_geral.php>Relat�rio Geral</a>.</b></td></tr>";				
					print "</TABLE>";				


					print "<TABLE width=80% align=center>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";

					print "<tr><td width=80% align=center><b>Sistema em desenvolvimento pelo setor de Helpdesk  do <a href='http://www.unilasalle.edu.br' target='_blank'>Unilasalle</a>.</b></td></tr>";				
					print "</TABLE>";	
					//print "</fieldset>";			
				

              
?>        


</BODY>
</HTML>
