<?php
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

$excluidos = "2,6,10"; //ítens excluídos para a pesquisa nas áreas;
$PERIODO = 1; //PERÍDO EM ANOS PRÓXIMOS PARA VERIFICAÇÃO DE VENCIMENTO DE GARANTIA DOS EQUIPAMENTOS
$ANO_ATUAL = date("Y");
$ANO_PROX = $ANO_ATUAL+$PERIODO;

if ($ok != 'Pesquisar')   
{
	print testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);
	print "<html>";
	print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar1.js\"></script></head>";					
	print "	<BR><BR>";
	print "	<B><center>::: Quadro de chamados por área de atendimento :::</center></B><BR><BR>";
	print "		<FORM action='".$PHP_SELF."' method='post' name='form1'>";
	print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR.">";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Área Responsável:</td>";
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
	
	print "					<td bgcolor=".TD_COLOR.">Data Inicial:</td>";
	print "					<td><INPUT name='d_ini' class='data'><a href=\"javascript:cal1.popup();\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
	print "				</tr>";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Data Final:</td>";
	print "					<td><INPUT name='d_fim' class='data'><a href=\"javascript:cal2.popup();\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
	print "				</tr>";
	
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Tipo de relatório:</td>";
	print "					<td><select name='saida' class='data'>";
	print "							<option value=-1 selected>Normal</option>";
//	print "							<option value=1>Relatório 1 linha</option>";
	print "						</select>";
	print "					</td>";
	print "				</tr>";
	print "		</TABLE><br>";
	print "		<TABLE align='center'>";
	print "			<tr>";
	print "	            <TD>";
	print "					<input type='submit' value='Pesquisar' name='ok' onclick='ok=sim'>";
	print "	            </TD>";
	print "	            <TD>";
	print "					<INPUT type='reset' value='Limpar campos' name='cancelar'>";
	print "				</TD>";
	print "			</tr>";
	print "	    </TABLE>";
	print " </form>";
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
	
	if ((empty($d_ini)) and (empty($d_fim))) 
	{
		$aviso = "O período deve ser informado.";
        $origem = 'javascript:history.back()';
        session_register("aviso");
        session_register("origem");
        //echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
		print "<script>window.alert('O período deve ser informado!'); history.back();</script>";
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
			/*
			$SW = 9; $HW = 11;//CÓDIGOS DAS ÁREAS HELPDESK E CONSTAT (SOFTWARE E HARDWARE)//
			$HW_PREV = 107; $HW_PREV = 87; //CÓDIGOS DAS PREVENTIVAS DE SOFTWARE E HARDWARE//
			$SUCATA = 7; $TROCADO_FURTADO = '4,5';
			*/
			print "<table class='centro' cellspacing='0' border='0' align='center'>";
				print "<tr><td colspan='2'><b>PERÍODO DE ".$d_ini." a ".$d_fim."</b></td></tr>";
			print "</table><br>";		   
		   
			
				print "<p align='center'>QUADRO GERAL DE CHAMADOS DO PERÍODO</p>";
				//print "<blockquote>";
				print "<table cellspacing='0' border='1' align='center'>";
				print "<tr><td><b>ÁREA DE ATENDIMENTO</b></TD><td colspan='3' align='center'><b>CHAMADOS</b></td></tr>";
				print "<tr><td></td><td>ABERTOS</td><td>FECHADOS</td><td>CANCELADOS</td></tr>";

			if($area==-1) {
				$query_areas="select * from sistemas where sis_status not in (0) order by sistema";		
			} else
				$query_areas="select * from sistemas where sis_id in ($area) order by sistema";
			$exec_qry_areas = mysql_query($query_areas);
			
			$totalAbertos = 0;
			$totalFechados = 0;
			$totalCancelados = 0;
			while ($row = mysql_fetch_array($exec_qry_areas)){
			
					
						$query_ab_sw = "SELECT count(*) AS abertos, s.sistema AS area 
											FROM ocorrencias AS o, sistemas AS s
											WHERE o.sistema = s.sis_id AND o.data_abertura >= '".$d_ini_completa."' AND 
											o.data_abertura <= '".$d_fim_completa."' and s.sis_id in (".$row['sis_id'].") group by area";
						$exec_ab_sw = mysql_query($query_ab_sw);
						$row_ab_sw = mysql_fetch_array($exec_ab_sw);
						
						$query_fe_sw = "SELECT count(*) AS fechados, s.sistema AS area
											FROM ocorrencias AS o, sistemas AS s
											WHERE o.sistema = s.sis_id AND o.data_fechamento >= '".$d_ini_completa."' AND 
											o.data_fechamento <= '".$d_fim_completa."' and s.sis_id in (".$row['sis_id'].")  group by area";
						$exec_fe_sw = mysql_query($query_fe_sw);
						$row_fe_sw = mysql_fetch_array($exec_fe_sw);
						
						$query_ca_sw = "SELECT count(*) AS cancelados, s.sistema AS area
											FROM ocorrencias AS o, sistemas AS s
											WHERE o.sistema = s.sis_id AND o.data_abertura >= '".$d_ini_completa."' AND 
											o.data_abertura <= '".$d_fim_completa."' and s.sis_id in (".$row['sis_id'].") and
											status in (12) group by area";
						$exec_ca_sw = mysql_query($query_ca_sw);
						$row_ca_sw = mysql_fetch_array($exec_ca_sw);		
				
						
						$totalAbertos+=$tt_ab = $row_ab_sw['abertos'];
						$totalFechados+=$tt_fe = $row_fe_sw['fechados'];
						$totalCancelados+=$tt_ca = $row_ca_sw['cancelados'];
						
						//------------TABELA 1 ---------------------//
				
						print "<tr><td>".$row['sistema']."</td><td>".$row_ab_sw['abertos']."</td><td>".$row_fe_sw['fechados']."</td><td>".$row_ca_sw['cancelados']."</td></tr>";			
			}
		    
				
				print "<tr><td><b>TOTAL</b></td><td><b>".$totalAbertos."</b></td><td><b>".$totalFechados."</b></td><td><b>".$totalCancelados."</b></td></tr>";
				print "</table><br><br>";
				//print "</blockquote>";
				//------------FINAL DA TABELA 1------------//
			
			//print "<br>".$query_areas;
			
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
    <?

        
        
}//if $ok==Pesquisar
?>
