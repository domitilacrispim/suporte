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
	
	$s_page_invmon = "estat_equippordia.php";
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
		
		
		
	$queryC = "SELECT count(extract(day from comp_data)) as QTD_DIA, extract(day from comp_data)as DIA, 
				extract(month from comp_data) as MES , extract(year from comp_data) as ANO,
				concat(date_format(comp_data,'%d'),'/',date_format(comp_data,'%m'),'/',extract(year from comp_data))as tipo_data 
				FROM equipamentos group by ano,mes,dia order by ano desc ,mes desc,dia desc";	
		
		
		
		$resultadoC = mysql_query($queryC);
        $linhasC = mysql_num_rows($resultadoC);
		$rowC = mysql_fetch_array($resultadoC);  
		
		
		
		
#######################################################################				
######################################################################################
			//Tabela de quantidade de equipamentos cadastrados por dia	
			
			//Tabela de quantidade de equipamentos cadastrados por dia	
			
       print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='$cor3'>";
                
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print "<tr><td width=60% align=center><b>Quantidade de equipamentos cadastrados por dia:</b></td></tr>";

  
        print "<TD>";
        print "<fieldset><legend>Cadastros por dia</legend>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='$cor3'>";
        print "<TR><TD bgcolor=$cor3><b>Data</TD><TD bgcolor=$cor3><b>Quantidade</TD></tr>";        
        $i=0;
        $j=2;
  
  
  if (($resultadoC = mysql_query($queryC)) && (mysql_num_rows($resultadoC) > 0) ) {
  while ($rowC = mysql_fetch_array($resultadoC)) {
                $color =  BODY_COLOR;
                $j++;
                ?>
                <TR>

				<TD bgcolor=<?print $color;?>><a href=mostra_consulta_comp.php?comp_data=<?print $rowC['tipo_data']?>&ordena=equipamento,modelo,local,etiqueta><?print $rowC["DIA"].'/'.$rowC["MES"].'/'.$rowC["ANO"]?></a></TD>
                <TD bgcolor=<?print $color;?>><?print $rowC["QTD_DIA"]?></TD>

                <?
                print "</TR>";
                $i++;

        }
       }
        print "<TR><TD bgcolor=$cor3><b></TD><TD bgcolor=$cor3><b>Total: $total</TD></tr>";        					
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
					//print "</fieldset>";			
				

              
?>        


</BODY>
</HTML>
