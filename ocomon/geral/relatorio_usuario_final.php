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
		print "	<B><center>::: CHAMADOS ABERTOS PELO USU�RIO-FINAL :::</center></B><BR><BR>";
		print "		<FORM action='".$PHP_SELF."' method='post' name='form1' onSubmit=\"return valida();\">";
		print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR." >";
		print "					<td bgcolor=".TD_COLOR.">�rea Respons�vel:</td>";
		print "					<td><Select name='area' class='select'>";
		print "							<OPTION value=-1 selected>-->Todos<--</OPTION>";
										$qryArea="select * from sistemas where sis_status not in (0) order by sistema";
										$execArea=mysql_query($qryArea);
										$regAreas = mysql_num_rows($execArea);
										while($rowArea=mysql_fetch_array($execArea))
										{
											print "<option value=".$rowArea['sis_id']."";
											if ($rowArea['sis_id']==$_SESSION['s_area']) print " selected";
											print ">".$rowArea['sistema']."</option>";
										} // while
		print "		 				</Select>";
		print "					 </td>";
		print "				</tr>";	
		
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
		
		$query = "select count(*) as qtd, o.*, u.*, a.*, n.* from ocorrencias as o left join usuarios as u on o.aberto_por = u.user_id 
					left join sistemas as a on a.sis_id = u.AREA left join nivel as n on nivel_cod = u.nivel 
					WHERE a.sis_atende=0 AND n.nivel_cod=3 ";
						
		if (!empty($area) and ($area != -1) and (($area == $s_area)||($s_nivel==1))) // variavel do select name
		{ 
			$query .= " and o.sistema = $area";
			$getAreaName = "select * from sistemas where sis_id = ".$area."";
			$exec = mysql_query($getAreaName);
			$rowAreaName = mysql_fetch_array($exec);
			$nomeArea = $rowAreaName['sistema'];

		} else 
		if ($s_nivel!=1){
			print "<script>window.alert('Voc� s� pode consultar os dados da sua �rea!');</script>";
			print "<script>history.back();</script>";
			exit;
		} else {
			$nomeArea = "TODAS";
		}
		
					
					
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
			
			
			$query .= " and o.data_abertura >= '$d_ini_completa' and o.data_abertura <= '$d_fim_completa'  
									group by u.nome ORDER BY qtd desc,nome";
								
			//print $query; exit;	
			$resultado = mysql_query($query) or die('ERRO NA TENTATIVA DE RECUPERAR OS DADOS!');     
			$linhas = mysql_num_rows($resultado);  
			
				if ($linhas==0) {
					$aviso = "N�o h� dados no per�odo informado. Refa�a sua pesquisa. ";
					//$origem = 'javascript:history.back()';
					session_register("aviso");
					session_register("origem");
					echo "<script>mensagem('".$aviso."'); redirect('relatorio_usuario_final.php');</script>";
				} else { //if($linhas==0)
					echo "<br><br>";
					$background = '#CDE5FF';	 
					print "<p align='center'>Verifique os <a onClick=\"javascript:popup_alerta('relatorio_slas_usuario_final.php?ini=".$d_ini_completa."&end=".$d_fim_completa."&area=".$area."')\"><font color='blue'>SLAs</font></a> atingidos.</p>";
					print "<p align='center'><b>CHAMADOS ABERTOS PELO USU�RIO-FINAL PARA A �REA: ".$nomeArea." </b></p>";
					print "<table class='centro' cellspacing='0' border='1' align='center'>";
					
					print "<tr><td bgcolor='".$background."'><B>QUANTIDADE</td>
								<td bgcolor='".$background."' ><B>USU�RIO</td>
								<td bgcolor='".$background."' ><B>�REA DE ATENDIMENTO</td>
							</tr>";
					$total = 0;    
					while ($row = mysql_fetch_array($resultado)) {
					$qryRow = "SELECT numero FROM ocorrencias where aberto_por = ".$row['user_id']." order by numero";
					$execqryRow = mysql_query($qryRow) or die('ERRO NA BUSCA DAS ocorrênciaS DO USU�RIO!');
						while ($chamados = mysql_fetch_array($execqryRow)) {
							//$chamadosId[$row['user_id']].=$chamados['numero'];
							$chamadosUser[]= $chamados['numero'];
						}
						
					$listaChamados = putcomma($chamadosUser);
					$chamadosUser = "";
							
					print "<tr>";
					print "<td><a onClick=\"javascript: popup_alerta('mostra_chamados.php?numero=".$listaChamados."')\">".$row['qtd']."</a></td><td>".$row['nome']."</td><td>".$row['sistema']."</td>";
					
					print "</tr>";
					$total+=$row['qtd'];
					}							 
					
					print "<tr><td colspan='2'><b>TOTAL</b></td><td><b>".$total."</b></td></tr>";
					
				} //if($linhas==0)
			//if  $d_ini_completa <= $d_fim_completa
			} else { 
			
				$aviso = "A data final n�o pode ser menor do que a data inicial. Refa�a sua pesquisa.";
				print "<script>mensagem('".$aviso."'); redirect('relatorio_usuario_final.php');</script>";
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
