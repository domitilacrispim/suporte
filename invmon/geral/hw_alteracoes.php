<?php

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	print "<HTML><BODY bgcolor='".BODY_COLOR."'>";    
		$auth = new auth;
		if ($popup) {
			$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
		} else
			$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);	

	if ($ok != 'Pesquisar') {  
	
		print "<script language=\"JavaScript\" src=\"../../includes/javascript/calendar1.js\"></script>";	
		print "	<BR><BR>";
		print "	<B><center>::: ALTERA��ES DE HARDWARE DOS EQUIPAMENTOS :::</center></B><BR><BR>";
		print "		<FORM action='".$PHP_SELF."' method='post' name='form1' onSubmit=\"return valida();\">";
		print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR." >";
		
		print "				<tr>";	
		print "					<td bgcolor=".TD_COLOR.">Data Inicial:</td>";
		print "					<td><INPUT name='d_ini' class='data' id='idData_ini'><a href=\"javascript:cal1.popup();\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">Data Final:</td>";
		print "					<td><INPUT name='d_fim' class='data' id='idData_fim'><a href=\"javascript:cal2.popup();\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
		print "				</tr>";

		print "				<tr>";	
		print "					<td bgcolor=".TD_COLOR.">M�s corrente</td>";
		print "					<td><INPUT type='checkbox' name='mesAtual' id='idMesAtual'></td>";
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
				
					function valida(){
						var ok = validaForm('idData_ini','DATA-','Data Inicial',0);
						if (ok) var ok = validaForm('idData_fim','DATA-','Data Final',0);
						return ok;
					}								
				
				
				//-->				
				</script>	
		<?
	//if $ok!=Pesquisar
	} else { //if $ok==Pesquisar
		
		$hora_inicio = ' 00:00:00';
		$hora_fim = ' 23:59:59';            
		
		$query = "SELECT count(*) as total, h.*, t.*, m.*, i.*, u.*
					FROM hw_alter as h, itens as t, modelos_itens as m, instituicao as i, usuarios as u
					WHERE h.hwa_inst = i.inst_cod and hwa_item = m.mdit_cod and hwa_user = u.user_id and t.item_cod = m.mdit_tipo ";
						
					
		if (((empty($d_ini)) and (empty($d_fim))) and !$_POST['mesAtual']) { 
	
			$aviso = "O per�odo deve ser informado.";
			$origem = 'javascript:history.back()';
			session_register("aviso");
			session_register("origem");
			print "<script>window.alert('O per�odo deve ser informado!'); history.back();</script>";
		
		} else {

			if ($_POST['mesAtual']) {
			//date("Y-m-d H:i:s");
				$d_ini = "01-".date("m-Y");
				$d_fim = date("d-m-Y");
			}
				
		$d_ini = str_replace("-","/",$d_ini);
		$d_fim = str_replace("-","/",$d_fim);
		$d_ini_nova = converte_dma_para_amd($d_ini);
		$d_fim_nova = converte_dma_para_amd($d_fim);
		
		$d_ini_completa = $d_ini_nova.$hora_inicio;
		$d_fim_completa = $d_fim_nova.$hora_fim;
		
		
		if($d_ini_completa <= $d_fim_completa) {
			
				print "<table class='centro' cellspacing='0' border='0' align='center' >";
					print "<tr><td colspan='2'><b>PER�ODO DE ".$d_ini." a ".$d_fim."</b></td></tr>";
				print "</table>";		   
			
			$query.= "	and hwa_data between '".$d_ini_completa."' and '".$d_fim_completa."' ";
					
			$query.="GROUP BY h.hwa_inst, h.hwa_inv
					ORDER BY h.hwa_data ";
					
			//print $query; exit;	
			$resultado = mysql_query($query) or die('ERRO NA TENTATIVA DE RECUPERAR OS DADOS!');     
			$linhas = mysql_num_rows($resultado);  
			
				if ($linhas==0) {
					$aviso = "N�o h� dados no per�odo informado. Refa�a sua pesquisa. ";
					//$origem = 'javascript:history.back()';
					session_register("aviso");
					session_register("origem");
					echo "<script>mensagem('".$aviso."'); redirect('hw_alteracoes.php');</script>";
				} else { //if($linhas==0)
					echo "<br><br>";
					$background = '#CDE5FF';	 
					
					print "<table class='centro' cellspacing='0' border='1' align='center'>";
					
					print "<tr><td bgcolor='".$background."'><B>QUANTIDADE</td>
								<td bgcolor='".$background."' ><B>EQUIPAMENTO</td>
							</tr>";
					$total = 0;    
					while ($row = mysql_fetch_array($resultado)) {
						
/*						$qryRow = "SELECT numero FROM ocorrencias where aberto_por = ".$row['user_id']." order by numero";
						$execqryRow = mysql_query($qryRow) or die('ERRO NA BUSCA DAS ocorrênciaS DO USU�RIO!');
						while ($chamados = mysql_fetch_array($execqryRow)) {
							$chamadosUser[]= $chamados['numero'];
						}*/
						
/*					$listaChamados = putcomma($chamadosUser);
					$chamadosUser = "";*/
							
					print "<tr>";
					print "<td><a class='botao' onClick= \"javascript: popup_alerta('hw_historico.php?inv=".$row['hwa_inv']."&inst=".$row['hwa_inst']."')\"><font color='#5E515B'>".$row['total']."</font></td>";
					//print "<td>".$row['total']."</td>";
//					print "<td>".$row['inst_nome']."&nbsp;".$row['hwa_inv']."</td>";
					print "<td><a onClick=\"javascript: popup_alerta('mostra_consulta_inv.php?comp_inv=".$row['hwa_inv']."&comp_inst=".$row['hwa_inst']."')\"><font color='#5E515B'>".$row['inst_nome']."&nbsp;".$row['hwa_inv']."</font></a></td>";

					print "</tr>";
					$total+=$row['total'];
					}							 
					
					print "<tr><td><b>TOTAL</b></td><td><b>".$total."</b></td></tr>";
					
				} //if($linhas==0)
			//if  $d_ini_completa <= $d_fim_completa
			} else { 
			
				$aviso = "A data final n�o pode ser menor do que a data inicial. Refa�a sua pesquisa.";
				print "<script>mensagem('".$aviso."'); redirect('hw_alteracoes.php');</script>";
			}
		}//if ((empty($d_ini)) and (empty($d_fim)))
		?>
			<script type='text/javascript'>
			<!--
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

								
			-->	
			</script>
		<?
	
			
			
	}//if $ok==Pesquisar
	print "</BODY>";
    print "</html>"; 
?>
