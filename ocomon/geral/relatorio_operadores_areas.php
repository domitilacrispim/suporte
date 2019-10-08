<html>
<head>
   <script language="JavaScript" src="../../includes/javascript/calendar1.js"></script>
<?php

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
?>

</head>

<?php

if ($ok != 'Pesquisar')   
{
	print testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);
    print "\n<BR><BR>";
	print "\n<B><center>::: OPERADORES X ÁREAS DE ATENDIMENTO :::</center></B><BR><BR>";
	print "\n	<FORM action='".$PHP_SELF."' method='post' name='form1'>";
	print "\n	<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR." >
				<tr>
					<td bgcolor=".TD_COLOR.">Área Responsável:</td>
					<td><Select name='area' class='select' size=1>";
	print "\n							<OPTION value=-1 selected>-->Todos<--</OPTION>";
									$query="select * from sistemas where sis_status not in (0) order by sistema";
									$resultado=mysql_query($query);
									$linhas = mysql_num_rows($resultado);
									while($row=mysql_fetch_array($resultado))
									{
										$sis_id=$row['sis_id'];
										$sis_name=$row['sistema'];
										print "<option value=$sis_id>$sis_name</option>";
									} // while
	print "\n		 				</Select>";
	print "					 </td>";
	print "				</tr>";	
	
	print "\n					<td bgcolor=".TD_COLOR.">Data Inicial:</td>";
	print "\n					<td><INPUT name='d_ini' class='data'><a href=\"javascript:cal1.popup();\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
	print "\n			</tr>";
	print "\n			<tr>";
	print "\n				<td bgcolor=".TD_COLOR.">Data Final:</td>";
	print "\n				<td><INPUT name='d_fim' class='data'><a href=\"javascript:cal2.popup();\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
	print "\n			</tr>";
	
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Tipo de relatório:</td>";
	print "					<td><select name='saida' class='data'>";
	print "							<option value=-1 selected>Normal</option>";
	print "							<option value=1>Gráfico</option>";
	print "						</select>";
	print "					</td>";
	print "				</tr>";
	print "		</TABLE><br>";
	print "		<TABLE align='center'>";
	print "			<tr>";
	print "	            <TD>";
	print "					<input type='submit' class='btPadrao' value='Pesquisar' name='ok' >";//onclick='ok=sim'
	print "	            </TD>";
	print "	            <TD>";
	print "					<INPUT type='reset'  class='btPadrao' value='Limpar campos' name='cancelar'>";
	print "				</TD>";
	print "			</tr>";
	print "\n    </TABLE>";
	print "\n</form>";
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
</BODY>
</html>

<?php
}//if $ok!=Pesquisar

else //if $ok==Pesquisar
{
    
	$hora_inicio = ' 00:00:00';
	$hora_fim = ' 23:59:59';            
    


	
    $query = "select count(o.numero) quantidade, u.nome operador, s.sistema area  
	                 from ocorrencias as o, usuarios as u, sistemas as s
					 where o.operador = u.user_id and u.AREA = s.sis_id ";
	
	if (!empty($area) and ($area != -1)) // variavel do select name
	{ 
	    $query .= " and o.sistema = $area";
	} 
	
	if ((empty($d_ini)) and (empty($d_fim))) 
	{
		$aviso = "O período deve ser informado.";
        $origem = 'javascript:history.back()';
        session_register("aviso");
        session_register("origem");
        print "<script>window.alert('O período deve ser informado!'); history.back();</script>";
		//echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
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
		   
			print "<table class='centro' cellspacing='0' border='0' align='center' >";
			print "<tr><td colspan='2'><b>PERÍODO DE ".$d_ini." a ".$d_fim."</b></td></tr>";
			print "</table>";		   
		   
		   
		   $query .= " and o.data_fechamento >= '$d_ini_completa' and o.data_fechamento <= '$d_fim_completa' and
					    o.data_atendimento is not null 
					         group by o.operador order by area,quantidade desc";
							
		   $resultado = mysql_query($query);     
		   $linhas = mysql_num_rows($resultado);  
		
			
		    if ($linhas==0) 
			   {
		       		$aviso = "Não há dados no período informado. <br>Refaça sua pesquisa. ";
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
						
                            echo "\n<br><br>";
							$background = '#CDE5FF';	 
							print "<p class='titulo' >OPERADORES X ÁREAS DE ATENDIMENTO</p>";
                            print "<table class='centro' cellspacing='0' border='1' align='center'>";
							
                            print "<tr><td bgcolor=$background><B>ÁREA DE ATENDIMENTO</td>
									   <td bgcolor=$background ><B>OPERADOR</td>
                                       <td bgcolor=$background ><B>QUANTIDADE</td>
								  </tr>";
                              $total = 0;    
							 while ($row = mysql_fetch_array($resultado)) {
								print "<tr>";
								print "<td>".$row['area']."</td><td>".$row['operador']."</td><td>".$row['quantidade']."</td>";
								print "</tr>";
								$total+=$row['quantidade'];
							}							 
							
							print "<tr><td colspan='2'><b>TOTAL</b></td><td><b>".$total."</b></td></tr>";
							print "<form><input type='button' class='btPadrao' value='Voltar' name='ok' onClick='history.go(-1);return true;'></form>";
							
							break;
			
						case 1: 
							print "<div style='text-align:center'>";
							print "<img src='graph_operador_area.php?d_ini=".$d_ini."&d_fim=".$d_fim."&area=".$area."'>";
							print "</div>";

							print "<form><input type='button' class='btPadrao' value='Voltar' name='ok' onClick='history.go(-1);return true;'></form>";
							break;
					} // switch		
				} //if($linhas==0)
			}//if  $d_ini_completa <= $d_fim_completa
			else 
			{
				$aviso = "A data final não pode ser menor <br>do que a data inicial.<br>Refaça sua pesquisa.";
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
<?php
}//if $ok==Pesquisar
?>
