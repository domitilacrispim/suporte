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


$areasExcluidas = "2,6,10";
if ($ok != 'Pesquisar')   
{
	print testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);
	print "<html>";
    print "	<BR><BR>";
	print "	<B><center>::: Relatório de problemas por área :::</center></B><BR><BR>";
	print "		<FORM action=' ' method='post'>";
	print "		<TABLE border=2 align='center'  bgcolor=".BODY_COLOR.">";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Área Responsável:</td>";
	print "					<td><Select name='area' size=1>";
	print "							<OPTION value=-1 selected>-->Todos<--</OPTION>";
									$query="select * from sistemas where sis_id not in ($areasExcluidas) order by sistema";
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
	print "					<td><INPUT name='d_ini' size=10>  (Exemplo: 20/01/2004)</td>";
	print "				</tr>";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Data Final:</td>";
	print "					<td><INPUT name='d_fim' size=10>  (Exemplo: 27/01/2004)</td>";
	print "				</tr>";
	
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Tipo de relatório:</td>";
	print "					<td><select name='saida' size=1>";
	print "							<option value=-1 selected>Normal</option>";
//	print "							<option value=1>Relatório 1 linha</option>";
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
	print "</BODY>";
    print "</html>"; 
}//if $ok!=Pesquisar

else //if $ok==Pesquisar
{
    
	$hora_inicio = ' 00:00:00';
	$hora_fim = ' 23:59:59';            
    
    $query = "select o.numero, o.data_abertura, o.data_atendimento, o.data_fechamento, o.sistema as cod_area, s.sistema, 
            p.problema as problema, sl.slas_desc as sla, sl.slas_tempo as tempo , l.*, pr.*, res.slas_tempo as resposta
            from localizacao as l left join prioridades as pr on pr.prior_cod = l.loc_prior left join sla_solucao as res on res.slas_cod = pr.prior_sla, problemas as p left join sla_solucao as sl on p.prob_sla = sl.slas_cod,
            ocorrencias as o, sistemas as s 
            where o.status=4 and s.sis_id=o.sistema and p.prob_id = o.problema  and o.local =l.loc_id";
	
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
        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
	}
	else
	{
       $d_ini_nova = converte_dma_para_amd($d_ini);
       $d_fim_nova = converte_dma_para_amd($d_fim);
	   
	   $d_ini_completa = $d_ini_nova.$hora_inicio;
       $d_fim_completa = $d_fim_nova.$hora_fim;
	
	
		if($d_ini_completa <= $d_fim_completa)
	    { 
			//$dias_va  //Alterado de data_abertura para data_fechamento -- ordena mudou de fechamento para abertura
		   $query .= " and o.data_fechamento >= '$d_ini_completa' and o.data_fechamento <= '$d_fim_completa' and
					    o.data_atendimento is not null order by o.data_abertura";
		   $resultado = mysql_query($query);       // print "<b>Query--></b> $query<br><br>";
		   $linhas = mysql_num_rows($resultado);  //print "Linhas: $linhas";
		  // $row = mysql_fetch_array($resultado);		
		
		    if($linhas==0) 
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
							print "<H1 align='center'>RELATÓRIO DE SLAS: INDICADORES DE RESPOSTA e INDICADORES DE SOLUÇÃO</H1>";
                            print "<table class='centro' cellspacing='0' border='1' >";
							
                            print "<tr><td bgcolor=$background><B>NUMERO</td>
									   <td bgcolor=$background ><B>PROBLEMA</td>
                                       <td bgcolor=$background ><B>ABERTURA</td>
									   <td bgcolor=$background ><B>1ª RESPOSTA</td>
									   <td bgcolor=$background ><B>FECHAMENTO</td>
									   <td bgcolor=$background ><b>T RESPOSTA VALIDO</td>
									   <td bgcolor=$background ><b>T SOLUCAO VALIDO</td></B>
                                       <td bgcolor=$background ><b>SLA Resposta</td></B>
                                       <td bgcolor=$background ><b>SLA Solução</td></B>
                                       <td bgcolor=$background ><b>Resposta</td></B>
                                       <td bgcolor=$background ><b>Solução</td></B>
                                       <td bgcolor=$background ><b>SOL - RESP</td></B>                                       
								  </tr>";
                                  
						  
			
							print "<tr ><td colspan=5><b>MÉDIAS -></td><td><b>$media_resposta_valida</td><td><B>$media_solucao_valida</td></tr>";	
							
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
