<?

 /*                        Copyright 2005 Fl�io Ribeiro
  
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
    $conec = new conexao;
    $PDO = $conec->conectaPDO();
	$s_page_invmon = "abertura.php";
	session_register("s_page_invmon");		
	
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);
	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);
	
	$s_page_invmon = "abertura.php";
	session_register($s_page_invmon);	
	
    $hoje = date("d-m-Y H:i:s");
	//geraLog(LOG_PATH.'invmon.txt',$hoje,$s_usuario,'abertura.php','p�ina inicial');	
	
    $cor  = TAB_COLOR;
    $cor1 = TD_COLOR;
    $cor3 = BODY_COLOR;
		
	$dados = array(); //Array que ir�guardar os valores para montar o gr�ico
	$legenda = array ();
	
	
	//$queryB = "SELECT count(*) from equipamentos";
	$queryB = $QRY["total_equip"]." where comp_inst not in (".INST_TERCEIRA.")";
	$resultadoB = $PDO->query($queryB);
	$row = $resultadoB->fetch(PDO::FETCH_ASSOC);
	//$total = mysql_result($resultadoB,0);
	$total = $row["total"];
	
	// Select para retornar a quantidade e percentual de equipamentos cadastrados no sistema
	$query = "SELECT count(*) as Quantidade, count(*)*100/$total as Percentual,
		 T.tipo_nome as Equipamento, T.tipo_cod as tipo
		FROM equipamentos as C, tipo_equip as T  
		WHERE C.comp_tipo_equip = T.tipo_cod and C.comp_inst not in (".INST_TERCEIRA.") 
		GROUP by C.comp_tipo_equip ORDER BY Quantidade desc,Equipamento";	
	
	$resultado = $PDO->query($query);
	$linhas = $resultado->rowCount();
	//$row = mysql_fetch_array($resultado);  

		

#########################################################################
       //print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='$cor3'>";
       print "<table class=estat60 align=center>";         
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";

					print "<tr><td align=center><b>".$TRANS["abert_titulo"].": <font color=red>$total</b></td></tr>";

  
        print "<TD>";
        print "<fieldset><legend>".$TRANS["quadro"]."</legend>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%'>";
		//print "<table class=estat60 align=center>";
        print "<TR><TD><b>".$TRANS["equip"]."</TD><TD><b>".$TRANS["qtd"]."</TD><TD><b>".$TRANS["perc"]."</TD></tr>";        
        $i=0;
        $j=2;
  
		  while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
		                $color =  BODY_COLOR;
		                $j++;
		                
						
						print "<tr id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
						?>
		                
						
		                <TD><a href='mostra_consulta_comp.php?comp_tipo_equip=<?print $row['tipo']?>&ordena=fab_nome,modelo,local,etiqueta' title='<?print $TRANS["hint_geral"]?>.'><?print $row["Equipamento"]?></a></TD>
						<TD><?print $row["Quantidade"]?></TD>
						<TD><?print $row["Percentual"]."%"?></TD>
		
		                <?
		                print "</TR>";
		                $dados[]=$row['Quantidade'];
						$legenda[]=$row['Equipamento'];
						$i++;
		
		  }
					
        print "<TR><TD><b>".$TRANS["total"]."</TD><TD><b>$total</TD><TD><b>100%</TD></tr>";        
					
		print "</TABLE>";
		
		
		for ($i=0; $i<count($dados);$i++){
				$valores.="data%5B%5D=".$dados[$i]."&";  
		}
		for ($i=0; $i<count($legenda); $i++){
				$valores.="legenda%5B%5D=".$legenda[$i]."&";
		}
			$valores = substr($valores,0,-1);
		
		
		
		print "</fieldset>";		
					
					
					print "<TABLE align=center>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";

					print "<tr><td width=60% align=center><b><a href=mostra_consulta_comp.php?visualiza=relatorio&ordena=equipamento,modelo,etiqueta title='".$TRANS["hint_relat_geral"]."'>".$TRANS["relat_geral"]."</a>.</b></td></tr>";				
					print "</TABLE>";				


					print "<TABLE>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";
					print"<tr><TD></TD></tr>";

					$nome = "titulo=".$TRANS["tit_graf_geral"]."";
					print "<tr><td width=60% align=center><input type=button class=button value=".$TRANS["grafico"]." onClick=\"return popup('graph_geral_barras.php?".$valores."&".$nome."&instituicao=".$msgInst."')\"></td></tr>";				
					
					
					print "<tr><td width=60%
align=center><b>".$TRANS["em_desenv"]." <a
href=http://www.intranet.lasalle.tche.br/cinfo/helpdesk TARGET=_blank
title='".$TRANS["hint_desenv"]."'>Helpdesk Unilasalle</a>.</b></td></tr>";	
		
									

					print "</TABLE>";	
					//print "</fielset>";			
				

	$cab->set_foot();	              

?>        


