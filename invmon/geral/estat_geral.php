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

		
        $cor  = TAB_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;

		$dados = array(); //Array que irá guardar os valores para montar o gráfico
		$legenda = array ();
		
############################################################################################		
		
		
		
############################################################################################		
		
				$queryInst = "SELECT * from instituicao order by inst_nome";
				$resultadoInst = mysql_query($queryInst);
				$linhasInst = mysql_num_rows($resultadoInst);		
		
		?>
			<div id="Layer2" style="position:absolute; left:80%; top:176px; width:15%; height:40%; z-index:2; ">  <!-- Ver: overflow: auto    não funciona para o Mozilla-->

				
				<b>Unidade:</font></font></b>
				<FORM name="form1" method="post" action="<?$PHP_SELF?>" >				
				<select style="background-color: <?echo $cor3;?>; font-family:tahoma; font-size:11px;" name="instituicao[]" size="<?echo $linhasInst+1;?>" multiple="yes">
				
		<?
               // $i=0;
                		print "<option value=-1 selected>TODAS</option>";
				while ($i < $linhasInst)
                {
                       ?>
                       <option value="<?print mysql_result($resultadoInst,$i,0);?>">
                                         <?print mysql_result($resultadoInst,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
				</select>		
				<br><input style="background-color: <?echo $cor1;?>;" type="submit" value="Aplicar" name="OK">
				
				</form>
				<!--</div> -->
			</div>
				
				<?
####################################################################################################					
					$saida="";
					for ($i=0; $i<count($instituicao); $i++){
						
						$saida.= "$instituicao[$i],";
					}
						if (strlen($saida)>1) {
							$saida = substr($saida,0,-1);
						}
						//echo $saida;
					
					$msgInst = "";
					if (($saida=="")||($saida=="-1")) {
						$clausula = "";
						$clausula2 = "";
						$msgInst = "TODAS";
					} else {
						$sqlA ="select inst_nome as inst from instituicao where inst_cod in ($saida)";
						$resultadoA = mysql_query($sqlA);
						$rowA = mysql_fetch_array($resultadoA);
			  			if (($resultadoA = mysql_query($sqlA)) && (mysql_num_rows($resultadoA) > 0) ) {			  
						  	while ($rowA = mysql_fetch_array($resultadoA)) {			
								$msgInst.= $rowA['inst'].', ';
							//print "<pre> array: ".$rowA["inst"]."</pre>";
							}
							$msgInst = substr($msgInst,0,-2);
						}
						
						$clausula = "where comp_inst in ($saida)";
						$clausula2 = " and C.comp_inst in ($saida) ";
						
					}
######################################################################################################		
				


		$queryB = "SELECT count(*) from equipamentos $clausula";
		$resultadoB = mysql_query($queryB);
		$total = mysql_result($resultadoB,0);
		
		
		
				
		// Select para retornar a quantidade e percentual de equipamentos cadastrados no sistema
		$query = "SELECT count(*) as Quantidade, count(*)*100/$total as Percentual,
					 T.tipo_nome as Equipamento, T.tipo_cod as tipo 
					FROM equipamentos as C, tipo_equip as T   
					WHERE C.comp_tipo_equip = T.tipo_cod $clausula2
					GROUP by C.comp_tipo_equip ORDER BY Quantidade desc,Equipamento" ;	
		
		$resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);
		$row = mysql_fetch_array($resultado);  
		
		
		
#######################################################################				
######################################################################################
			//Tabela de quantidade de equipamentos cadastrados por dia	
			
			//Tabela de quantidade de equipamentos cadastrados por dia	
			
       print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='$cor3'>";
                
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print "<tr><td width=60% align=center><b>Estatística de equipamentos cadastrados. <p>Unidade: $msgInst</p></b></td></tr>";

  
        print "<TD>";
		print "<fieldset><legend>Quadro geral</legend>";                
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='$cor3'>";
        print "<TR><TD bgcolor=$cor3><b>Equipamento</TD><TD bgcolor=$cor3><b>Quantidade</TD><TD bgcolor=$cor3><b>Percentual</TD></tr>";        
        $i=0;
        $j=2;
  
  
  if (($resultado = mysql_query($query)) && (mysql_num_rows($resultado) > 0) ) {
  while ($row = mysql_fetch_array($resultado)) {
                $color =  BODY_COLOR;
                $j++;
                ?>
                <TR>
                <TD bgcolor=<?print $color;?>><a href=mostra_consulta_comp.php?comp_tipo_equip=<?print $row['tipo']?>&ordena=fab_nome,modelo,local,etiqueta title='Listar todos os equipamentos deste tipo que estão cadastrados no sistema.'><?print $row["Equipamento"]?></a></TD>
				<TD bgcolor=<?print $color;?>><?print $row["Quantidade"]?></TD>
				<TD bgcolor=<?print $color;?>><?print $row["Percentual"]."%"?></TD>

                <?
                print "</TR>";
                $dados[]=$row['Quantidade'];
				$legenda[]=$row['Equipamento'];
				$i++;

        }
       }
        print "<TR><TD bgcolor=$cor3><b>Total</TD><TD bgcolor=$cor3><font color='red'><b>$total</font></TD><TD bgcolor=$cor3><b>100%</TD></tr>";        					
		print "</TABLE>";
		
		for ($i=0; $i<count($dados);$i++){
				$valores.="data%5B%5D=".$dados[$i]."&";  
		}
		for ($i=0; $i<count($legenda); $i++){
				$valores.="legenda%5B%5D=".$legenda[$i]."&";
		}
			$valores = substr($valores,0,-1);
		
		
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

					$nome = "titulo=Gráfico geral de equipamentos cadastrados.";
					$msgInst= "Unidade: ".$msgInst;
					print "<tr><td width=60% align=center><input type=button class=button value=Gráfico onClick=\"return popup('graph_geral_barras.php?".$valores."&".$nome."&instituicao=".$msgInst."')\"></td></tr>";				
					print "<tr><td width=80% align=center><b>Sistema em desenvolvimento pelo setor de Helpdesk  do <a href='http://www.unilasalle.edu.br' target='_blank'>Unilasalle</a>.</b></td></tr>";				
					print "</TABLE>";				
				//print "</fieldset>";

              
?>        


</BODY>
</HTML>
