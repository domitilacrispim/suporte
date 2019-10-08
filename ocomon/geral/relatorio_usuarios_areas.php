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
	
	$s_page_ocomon = "relatorio_usuarios_areas.php";
	session_register("s_page_ocomon");		


if ($ok != 'Pesquisar')   
{
	print testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);
	print "<html>";
	print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar1.js\"></script></head>";	
    print "	<BR><BR>";
	print "	<B><center>::: USUÁRIOS X ÁREAS DE ATENDIMENTO :::</center></B><BR><BR>";
	print "		<FORM action='".$PHP_SELF."' method='post' name='form1'>";
	print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR." >";
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
	print "					<input type='submit' value='Pesquisar' class='btpadrao' name='ok' >";//onclick='ok=sim'
	print "	            </TD>";
	print "	            <TD>";
	print "					<INPUT type='reset' value='Limpar campos' class='btPadrao' name='cancelar'>";
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
    


	
    $query = "SELECT count(*)  AS quantidade, l.local AS setor, s.sistema AS area, lower(o.contato) as usuario
				FROM ocorrencias AS o, localizacao AS l, sistemas AS s
				WHERE o.sistema = s.sis_id AND o.local = l.loc_id ";
	
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
		   
			print "<table class='centro' cellspacing='0' border='0' align='center' >";
				print "<tr><td colspan='2'><b>PERÍODO DE ".$d_ini." a ".$d_fim."</b></td></tr>";
			print "</table>";		   
		   
		   
		   $query .= " and o.data_abertura >= '$d_ini_completa' and o.data_abertura <= '$d_fim_completa' and
					    o.data_atendimento is not null 
						GROUP  BY l.local, usuario, s.sistema order by l.local,quantidade desc";
							
		   $resultado = mysql_query($query);     
		   $linhas = mysql_num_rows($resultado);  
		
			
		    if ($linhas==0) 
			   {
		       		$aviso = "Não há dados no período informado. <br>Refaça sua pesquisa.";
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
							print "<p class='titulo'>USUÁRIOS X ÁREAS DE ATENDIMENTO</p>";
                            print "<table class='centro' cellspacing='0' border='1' align='center' >";
							
                            print "<tr><td bgcolor=$background><B>SETOR</td>
									   <td bgcolor=$background ><B>USUÁRIO</td>
									   <td bgcolor=$background ><B>ÁREA DE ATENDIMENTO</td>
                                       <td bgcolor=$background ><B>QUANTIDADE</td>
								  </tr>";
                              $total = 0;    
							 while ($row = mysql_fetch_array($resultado)) {
								print "<tr>";
								print "<td>".$row['setor']."</td><td>".$row['usuario']."</td><td>".$row['area']."</td><td>".$row['quantidade']."</td>";
								print "</tr>";
								$total+=$row['quantidade'];
							}							 
							
							print "<tr><td colspan='2'><b>TOTAL</b></td><td><b>".$total."</b></td></tr>";
							
							break;
			
						case 1: 
							$campos=array();
							$campos[]="numero";
							$campos[]="data_abertura";
							$campos[]="data_atendimento";
							$campos[]="data_fechamento";
							$campos[]="t_res_hora";
							$campos[]="t_sol_hora";								
							$campos[]="t_res_valida_hor";
							$campos[]="t_sol_valida_hor";
					
							$cabs=array();
							$cabs[]="Número";
							$cabs[]="Abertura";
							$cabs[]="1ª Resposta";
							$cabs[]="Fechamento";
							$cabs[]="T Resposta Total";
							$cabs[]="T Solução Total";								
							$cabs[]="T Resposta Válido";
							$cabs[]="T Solução Válido";
							
							$logo="logo_unilasalle.gif";
							$msg1="Centro de Informática";
							$msg2=date('d/m/Y H:m');
							$msg3= "Relatório de SLA's";
							
							gera_relatorio(1,$query,$campos,$cabs,$logo,$msg1, $msg2, $msg3);
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
    <?

        
        
}//if $ok==Pesquisar
?>
