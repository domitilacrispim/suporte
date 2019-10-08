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

	$s_page_ocomon = "chamados_x_etiqueta.php";
	session_register("s_page_ocomon");	



if ($ok != 'Pesquisar')   
{
	print testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);
	print "<html>";
	print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar1.js\"></script></head>";				
	print "	<BR><BR>";
	print "	<B><center>::: Relatório de chamados por equipamentos :::</center></B><BR><BR>";
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
	print "				</tr><tr>";	
	
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
	$query = "SELECT count(*) AS total, I.inst_nome as instituicao, I.inst_cod as inst_cod, 
				O.equipamento as etiqueta, S.sistema as area, L.local as local
				FROM ocorrencias AS O, instituicao as I, sistemas as S, localizacao as L
				WHERE (instituicao IS NOT NULL OR instituicao NOT IN (0)) AND equipamento NOT IN (0) 
				and O.instituicao = I.inst_cod and S.sis_id =O.sistema and L.loc_id = O.local";
	
	if (!empty($area) and ($area != -1)) // variavel do select name
	{ 
	    $query .= " and O.sistema = $area";
	} 
	
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

		   $query .= " and O.data_abertura >= '$d_ini_completa' and O.data_abertura <= '$d_fim_completa'
					   GROUP BY inst_nome, equipamento,area ORDER BY total DESC";
		   
           //print $query; exit;
           $resultado = mysql_query($query);       // print "<b>Query--></b> $query<br><br>";
		   $linhas = mysql_num_rows($resultado);  //print "Linhas: $linhas";
		  // $row = mysql_fetch_array($resultado);		
		
		    if($linhas==0) 
			   {
		       		$aviso = "Não há dados no período informado. <br>Refaça sua pesquisa.";
			        $origem = 'javascript:history.back()';
			        session_register("aviso");
			        session_register("origem");
		            //print "<br>".$query;
					//echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
					print "<script>window.alert('Não há dados no período informado!'); history.back();</script>";
		       } 
		    else //if($linhas==0)
		   	   	{
			   		$campos=array();	
					switch($saida)
					{
						case -1: 
							echo "<br><br>";
							$background = '#CDE5FF';	
							print "<table class='centro' cellspacing='0' border='1' align='center'>";
							print "<tr><td bgcolor=$background colspan='5' align='center'><b>RELATÓRIO DE TOTAL DE CHAMADOS POR ETIQUETA</b><br>PERÍODO: ".$d_ini." a ".$d_fim."</td>";
							print "<tr><td bgcolor=$background width='255'><B>   QUANTIDADE</td>
									   <td bgcolor=$background ><B>INSTITUIÇÃO</td>
									   <td bgcolor=$background ><B>ETIQUETA</td>
									   <td bgcolor=$background ><B>LOCALIZAÇÃO</td>
									   <td bgcolor=$background ><B>AREA</td>
								  </tr>";
							$conta = 0;							 
							 while ($row = mysql_fetch_array($resultado)) 
							 {
								$conta +=$row['total'];
								print "<tr><td>$row[total]</td>
										   <td>$row[instituicao]</td>
										   <td><a onClick= \"popup_alerta('../../invmon/geral/mostra_consulta_inv.php?comp_inst=".$row['inst_cod']."&comp_inv=".$row['etiqueta']."&popup=true')\">$row[etiqueta]</a></td>
										   <td>$row[local]</td>
										   <td>$row[area]</td>
									  </tr>";
							 }//while
							
							print "<tr><td bgcolor=$background align='center'><b>TOTAL DE CHAMADOS:</b></TD><td bgcolor=$background colspan='4' align='center'><b>".$conta."</b></td></tr>"	;		
							print "</table>";

							break;
					} // switch		
				} //if($linhas==0)
				//print $query;
			}//if  $d_ini_completa <= $d_fim_completa
			else 
			{
				$aviso = "A data final não pode ser menor <br>do que a data inicial.<br>Refaça sua pesquisa.";
		        $origem = 'javascript:history.back()';
		        session_register("aviso");
		        session_register("origem");
		        
				//print "<br>".$query;
				echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
			}
		}//if ((empty($d_ini)) and (empty($d_fim)))
	//print "<br>".$query;
}//if $ok==Pesquisar
?>
<script type='text/javascript'>

	 function popup_alerta(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'Alerta','width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);	  	
		return false
     }

</script>

