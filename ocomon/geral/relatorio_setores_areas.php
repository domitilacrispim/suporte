<html>
<?php

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	
	$s_page_ocomon = "relatorio_setores_areas.php";
	session_register("s_page_ocomon");		



if ($ok != 'Pesquisar')   
{
	print testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);
?>
<head>
   <script language="JavaScript" src="../../includes/javascript/calendar1.js"></script>
</head>
<BR><BR>
<B><center>::: CHAMADOS FECHADOS - SETORES X �REAS DE ATENDIMENTO :::</center></B>
<BR><BR>
<?php
	print "		<FORM action='".$PHP_SELF."' method='post' name='form1'>";
	print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR.">";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">�rea Respons�vel:</td>";
	print "					<td><Select name='area' class='select'>";
	print "							<OPTION value=-1 selected>-->Todos<--</OPTION>";
									$query="select * from sistemas where sis_status not in (0) order by sistema";
									$resultado=mysql_query($query);
									$linhas = mysql_num_rows($resultado);
									while($row=mysql_fetch_array($resultado))
									{
										$sis_id=$row['sis_id'];
										$sis_name=$row['sistema'];
										print "<option value=$sis_id>$sis_name</option>";
									} // while
	print "		 				</Select>";
	print "					 </td>";
	print "				</tr>";	
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Data Inicial:</td>";
	print "					<td><INPUT name='d_ini' class='data'><a href=\"javascript:cal1.popup();\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
	print "				</tr>";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Data Final:</td>";
	print "					<td><INPUT name='d_fim' class='data'><a href=\"javascript:cal2.popup();\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
	print "				</tr>";
	
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Tipo de relat�rio:</td>";
	print "					<td><select name='saida' class='data'>";
	print "							<option value=-1 selected>Relat�rio</option>";
	print "							<option value=1>Gr�fico</option>";
	print "						</select>";
	print "					</td>";
	print "				</tr>";
	print "		</TABLE><br>";
	print "		<TABLE align='center'>";
	print "			<tr>";
	print "	            <TD>";
	print "					<input type='submit' value='Pesquisar' name='ok' >";//onclick='ok=sim'
	print "	            </TD>";
	print "	            <TD>";
	print "					<INPUT type='reset' value='Limpar campos' name='cancelar'>";
	print "				</TD>";
	print "			</tr>";
	print "	    </TABLE>";
	print "</form>";
?>
			<script language="JavaScript"> 
			 // create calendar object(s) just after form tag closed
				 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
				 // note: you can have as many calendar objects as you need for your application
				var cal1 = new calendar1(document.forms['form1'].elements['d_ini']);
				cal1.year_scroll = true;
				cal1.time_comp = false;
				var cal2 = new calendar1(document.forms['form1'].elements['d_fim']);
				cal2.year_scroll = true;
				cal2.time_comp = false;				
			//-->				
			</script>	
	<?		
	print "</BODY>";
    print "</html>"; 
}//if $ok!=Pesquisar

else //if $ok==Pesquisar
{
    
	$hora_inicio = ' 00:00:00';
	$hora_fim = ' 23:59:59';            
    


	
    $query = "SELECT count(*)  AS quantidade, l.local AS setor, s.sistema AS area FROM ocorrencias AS o, localizacao AS l, sistemas AS s
						WHERE o.sistema = s.sis_id AND o.local = l.loc_id";
	
	if (!empty($area) and ($area != -1)) // variavel do select name
	{ 
	    $query .= " and o.sistema = $area";
	} 
	
	if ((empty($d_ini)) and (empty($d_fim))) 
	{
		$aviso = "O per�odo deve ser informado.";
        	$origem = 'javascript:history.back()';
        	session_register("aviso");
        	session_register("origem");
        	//echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
		print "<script>window.alert('O per�odo deve ser informado!'); history.back();</script>";
	}
	else
	{
		$d_ini = str_replace("-","/",$d_ini);
	   	$d_fim = str_replace("-","/",$d_fim);       
       
	   	$d_ini_nova = converte_dma_para_amd($d_ini);
       	$d_fim_nova = converte_dma_para_amd($d_fim);
	   
	   	$d_ini_completa = $d_ini_nova.$hora_inicio;
       	$d_fim_completa = $d_fim_nova.$hora_fim;
	
	
		if($d_ini_completa <= $d_fim_completa)
	    	{ 
		   
		   
			print "<table class='centro' cellspacing='0' border='0' align='center'>";
			print "\n<tr>
				<td colspan='2'><b>PER�ODO DE ".$d_ini." a ".$d_fim."</b></td>
				</tr>";
			print "</table>";		   
		   
		   	$query .= " and o.data_fechamento >= '$d_ini_completa' and o.data_fechamento <= '$d_fim_completa' and
					  o.data_atendimento is not null 
				GROUP  BY l.local, s.sistema order by quantidade desc,l.local";
							
		   	$resultado = mysql_query($query);     
		   	$linhas = mysql_num_rows($resultado);  
		
			
		    	if (($linhas==0) )
			{
		       	$aviso = "N�o h� dados no per�odo informado. <br>Refa�a sua pesquisa.";
			     	$origem = 'javascript:history.back()';
			     	session_register("aviso");
			     	session_register("origem");
		            	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
		       } 
		    	else //if($linhas==0)
		   	   	{
			   		$campos=array();	
					switch($saida)
					{
						case -1: 
						
                            			echo "<br><br>";
							$background = '#CDE5FF';	 
							print "<p class='titulo'>CHAMADOS FECHADOS - SETORES X �REAS DE ATENDIMENTO</p>";
                            			print "<table class='centro' cellspacing='0' border='1' align='center' style='nth-child(odd).background:#ff0000' >";
							
                            			print "<tr><td bgcolor=$background><B>SETOR</td>
							<td  bgcolor=$background ><B>�REA DE ATENDIMENTO</td>
                                       		<td bgcolor=$background ><B>QUANTIDADE</td>
							</tr>";
                             			$total = 0;     
							while ($row = mysql_fetch_array($resultado)) {
								print "<tr>";
								print "<td style='text-align:left'>".$row['setor']."</td>
									<td>".$row['area']."</td>
									<td style='text-align:right'>".$row['quantidade']."</td>";
								print "</tr>";
								$total+=$row['quantidade'];
							}							 
							print "<tr><td colspan='2'><b>TOTAL</b></td><td><b>".$total."</b></td></tr>";
							
							break;
			
						case 1: 
							print "<div style='text-align:center'>";
							print "<img src='graph_setor_area.php?d_ini=".$d_ini."&d_fim=".$d_fim."'>";
							print "</div>";

							//Gera um gr�fico pra cada �rea com os seus problemas
  							$query = 	"SELECT 
					                	s.sis_id,
								s.sistema  AS area,
								count(*)   AS quantidade
							FROM ocorrencias AS o, 
            							localizacao AS l, 
            							sistemas    AS s, 
       	    						problemas   AS p
							WHERE p.prob_area = s.sis_id
        						  AND o.local     = l.loc_id 
        						  AND o.problema  = p.prob_id
        						  AND o.data_fechamento >= '".$d_ini_completa."'  and o.data_fechamento <= '".$d_fim_completa."'
        						  AND o.data_atendimento is not null 
							GROUP BY s.sistema 
							ORDER BY quantidade DESC";
  							$resultado = mysql_query($query);     
  							$graficos = mysql_num_rows($resultado);  // numero de graficos

							print "<p class='titulo'>�REAS DE ATENDIMENTO</p>";
  
  							while ($row = mysql_fetch_array($resultado)) {
								print "<div style='text-align:center'>";
								print "<img src='graph_setores.php?d_ini=".$d_ini."&d_fim=".$d_fim."&areaid=".$row['sis_id']."&area=".$row['area']."'>";
								print "</div>";
							}
							print "<form><input type='button' class='btPadrao' value='Voltar' name='ok' onClick='history.go(-1);return true;'></form>";
							break;
					} // switch		
				} //if($linhas==0)
			}//if  $d_ini_completa <= $d_fim_completa
			else 
			{
				$aviso = "A data final n�o pode ser menor <br>do que a data inicial.<br>Refa�a sua pesquisa.";
		        	$origem = 'javascript:history.back()';
		        	session_register("aviso");
		        	session_register("origem");
		        	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
			}
		}//if ((empty($d_ini)) and (empty($d_fim)))
	?>
        <script type='text/javascript'>

			function popup(pagina)	{ //Exibe uma janela popUP
				x = window.open(pagina,'popup','width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
				//x.moveTo(100,100);
				x.moveTo(window.parent.screenX+100, window.parent.screenY+100);	  	
				return false
			}
             
			function popup_alerta(pagina)	{ //Exibe uma janela popUP
                		x = window.open(pagina,'_blank','width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
                		//x.moveTo(100,100);
                		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);	  	
                		return false
             		}
        </script>
    <?        
        
}//if $ok==Pesquisar
?>
