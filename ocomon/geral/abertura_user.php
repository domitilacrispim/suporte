<?
 /*                        Copyright 2005 Fl�vio Ribeiro
  
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

	//$s_page_home = "abertura_user.php";
	//session_register("s_page_home");	
	
	$imgsPath = "../../includes/imgs/";

	$hoje = date("Y-m-d H:i:s");
	$valign = " VALIGN = TOP ";
    $conec = new conexao;
    $PDO = $conec->conectaPDO();
	$auth = new auth;
	if ($popup) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);	

?>
<HTML>
<head>
<META HTTP-EQUIV=Refresh CONTENT="120; URL=abertura_user.php">
<script type="text/javascript">
	 function popup(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+100, window.parent.screenY+100);	  	
		return false
     }
</script>
</head>
<?

	$dt = new dateOpers; //Criado o objeto $dt
	$dta = new dateOpers;

	$cor  = TAB_COLOR;
    $cor1 = TD_COLOR;
    $cor3 = BODY_COLOR;
        
	$percLimit = 20; //Toler�ncia em percentual
	$imgSlaR = 'sla1.png'; 
	$imgSlaS = 'checked.png'; 
	
		print "<br>";

        //ocorrênciaS VINCULADAS AO OPERADOR
        //PAINEL 1 � O PAINEL SUPERIOR DA TELA DE ABERTURA
        $query = $QRY["ocorrencias_full_ini"]." where o.aberto_por = ".$_SESSION['s_uid']." and s.stat_painel not in(3) order by numero";
		$resultado_oco = $PDO->query($query);
        $linhas = $resultado_oco->rowCount();
		
		if ($linhas == 0)
        {
                echo mensagem("N�o foi encontrada nenhuma ocorrência ativa aberta por voc�.");
        }
        else
        {//OCORRENCIAS ABERTAS PELO USU�RIO
			if ($linhas>1)
			{
				print "<TR class='header'><TD>Foram encontradas ".$linhas." ocorrências <font color=red><b>ativas</b></font> abertas por voc�.</TD></TR>";
					//print "</TD>";
			}
			else
			{
				print "<TR class='header'><TD>Foi encontrada ".$linhas." ocorrência <font color=red><b>ativa</b></font> aberta por voc�.</TD></TR>";
			}
			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='".$cor."'>";
			print "<TR class='header'>";
			print "	<TD>Chamado</TD>";
			print "	<TD>Problema</TD>";
			print "	<TD>Contato<BR>Ramal</TD>";
			print " <TD>Local<br>Descri��o</TD>";
			print " <TD>�rea</TD>";
			print "	<TD>Status</TD>";
			print "	<TD>Tempo<br>v�lido</TD>";
			print "	<TD>RESP.</TD>";
			print "	<TD>SOLUC.</TD>";
			print "</TR>";
        }
        $i=0;
        $j=2;
        while ($rowAT = $resultado_oco->fetch(PDO::FETCH_ASSOC))
        {
		if ($j % 2)
		{
				$color =  BODY_COLOR;
				$trClass = "lin_par";
		}
		else
		{
				$color = white;
				$trClass = "lin_impar";
		}
		$j++;
		print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 				
		
			
			
			$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']." or dep_filho=".$rowAT['numero']."";
			$execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
			$regSub = $execSubCall->rowCount();
			if ($regSub > 0) {
				#� CHAMADO PAI?
				$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']."";
				$execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
				$regSub = $execSubCall->rowCount();
				$comDeps = false;
				while ($rowSubPai = $execSubCall->fetch(PDO::FETCH_ASSOC)){
					$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
					$execStatus = $PDO->query($sqlStatus) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE STATUS DOS CHAMADOS FILHOS<br>'.$sqlStatus);
					$regStatus = $execStatus->rowCount();
					if ($regStatus > 0) {
						$comDeps = true;
					}
				}
				if ($comDeps) {
					$imgSub = "<img src='".ICONS_PATH."view_tree_red.png' width='16' height='16' title='Chamado com v�nculos pendentes'>";
				} else
					$imgSub =  "<img src='".ICONS_PATH."view_tree_green.png' width='16' height='16' title='Chamado com v�nculos mas sem pend�ncias'>";
			} else
				$imgSub = "";
		
		
		
		
		
		print "<TD ".$valign."><a href='mostra_consulta.php?numero=".$rowAT['numero']."'>".$rowAT['numero']."</a>".$imgSub."</TD>";
		print "<TD ".$valign.">".$rowAT['problema']."</TD>";
		print "<TD ".$valign."><b>".$rowAT['contato']."</b><br>".$rowAT['telefone']."</TD>";
		print "<TD ".$valign."><b>".$rowAT['setor']."</b><br>";
		$texto = trim($rowAT['descricao']);
		if (strlen($texto)>200){
			$texto = substr($texto,0,195)." ..... ";
		};
		print $texto;
            	print "</TD>";
            	print "<TD ".$valign.">".$rowAT['area']."</TD>";
            	print "<TD ".$valign.">".$rowAT['chamado_status']."</TD>";
                        
						
			// if (array_key_exists($rowAT['cod_area'],$H_horarios)){  //verifica se o c�digo da �rea possui carga hor�ria definida no arquivo config.inc.php
					//$areaChamado = $rowAT['cod_area']; //Recebe o valor da �rea de atendimento do chamado
			// } else $areaChamado = 1; //Carga hor�ria default definida no arquivo config.inc.php
			$areaChamado=testaArea($areaChamado,$rowAT['cod_area'],$H_horarios);

			$data = $rowAT['data_abertura'];
			
			$diff = data_diff($data,$hoje);
			$sep = explode ("dias",$diff);
			if ($sep[0]>20) { //Se o chamado estiver aberto a mais de 20 dias o tempo � mostrado em dias para n�o ficar muito pesado.
				$diff = $sep[0]." dias";
				$segundos = ($sep[0]*86400);
			} else {
//-----------------------------------------------------------                        
				$dta->setData1($data);
				$dta->setData2($hoje);

				$dta->tempo_valido($dta->data1,$dta->data2,$H_horarios[$areaChamado][0],$H_horarios[$areaChamado][1],$H_horarios[$areaChamado][2],$H_horarios[$areaChamado][3],"H");
				$diff = $dta->tValido;
				$diff2 = $dta->diff["hValido"];
				$segundos = $dta->diff["sValido"]; //segundos v�lidos
			}
//-----------------------------------------------------------						
            print "<TD ".$valign.">".$diff."</TD>";

				//------------------------------------
			if ($rowAT['data_atendimento'] ==""){//Controle das bolinhas de SLA de Resposta 
				if ($segundos<=($rowAT['sla_resposta_tempo']*60)){
					$imgSlaR = 'sla1.png';
				} else if ($segundos  <=(($rowAT['sla_resposta_tempo']*60) + (($rowAT['sla_resposta_tempo']*60) *$percLimit/100)) ){
						$imgSlaR = 'sla2.png';
				} else {
					$imgSlaR = 'sla3.png';
				}
			} else 
				$imgSlaR = 'checked.png';
                      //-----------------------------------------
                        
			$sla_tempo = $rowAT['sla_solucao_tempo'];
			if ($sla_tempo !="") { //Controle das bolinhas de SLA de solu��o
				if ($segundos <= ($rowAT['sla_solucao_tempo']*60)){
					$imgSlaS = 'sla1.png';
				} else if ($segundos  <=(($rowAT['sla_solucao_tempo']*60) + (($rowAT['sla_solucao_tempo']*60) *$percLimit/100)) ){
					$imgSlaS = 'sla2.png';
				} else 
					$imgSlaS = 'sla3.png';
			} else
				$imgSlaS = 'checked.png';
				//-----------------------------------------------------   
                      
			print "<TD $valign align='center'><a onClick=\"javascript:popup('sla_popup.php?sla=r')\"><img height='14' width='14' src='".$imgsPath."".$imgSlaR."'></a></TD>";
			print "<TD $valign align='center'><a onClick=\"javascript:popup('sla_popup.php?sla=s')\"><img height='14' width='14' src='".$imgsPath."".$imgSlaS."'></a></TD>";

			print "</TR>";
			$i++;
        }
        print "</TABLE>";
        print "<HR>";
        
		
		
	if ($_REQUEST['action'] == 'listall') {
        //TODAS ocorrênciaS VINCULADAS AO OPERADOR
        $query = $QRY["ocorrencias_full_ini"]." where o.aberto_por = ".$_SESSION['s_uid']." and s.stat_painel in(3) order by numero DESC";

		$qrytmp = $query;
		$exectmp = $PDO->query($qrytmp) or die ('N�O FOI POSS�VEL RODAR A QUERY TEMPOR�RIA!'.$qrytmp);
		$linhasTotal = $exectmp->rowCount();
		$page = 5;
		
		if (empty($min))  {
			$min = 0; //Posso passar esse valor direto por par�metro se eu quiser!!
		};
		if (empty($max))  {
			$max = $page; //Posso passar esse valor direto por par�metro se eu quiser!!
			if ($max>$linhasTotal) {$max=$linhasTotal;};
			$maxAux = $max;
		};

		if ($avanca==">") {
			$min+=$max;
			if ($min >($linhasTotal-$max)) {$min=($linhasTotal-$max);};
		}else
		if ($avanca==">>") {
			$min=$linhasTotal-$max;
		} else
		if ($avanca=="Todas") {
			$max=$linhasTotal;
			$min=0;
		} else
		if ($avanca=="<") {
			if (($max==$linhasTotal)and ($min==0)) {$max=$maxAux; $min=$linhasTotal;}; //Est� exibindo todos os registros na tela!
			$min-=$max;
			if ($min<0) {$min=0;};
		} else
		if ($avanca=="<<") {
			$min=0;
			$max=$maxAux;
		}
	
		$query.=" LIMIT $min,$max";	   
		
		$resultado_oco = $PDO->query($query);
        $linhas = $resultado_oco->rowCount();
		
		
		if ($linhas == 0) {
                echo mensagem("N�o existe nenhuma ocorrência inativa aberta por voc� no sistema.");
        }
        else {
        if ($linhas == 1) {
			print "<TR class='header'><TD>Existe ".$linhas." ocorrência <font color=red><b>inativa</b></font> aberta por voc� no sistema.</TD></TR>";		
		} else {	
			print "<FORM name='form1' method='POST' action='".$PHP_SELF."'>";
			
			$min++;
			if ($avanca=="Todos") {$top=$linhasTotal;} else $top=$min+($max-1);
			
			print "<tr>";
			print "<TD witdh='700' align=left><B>Existem <font color=red>$linhasTotal</font> ocorrências <font color=red><b>inativas</b></font> abertas por voc�. Mostradas as mais recentes de <font color=red>$min</font> a <font color=red>$top</font>. </B></TD>";
			print "<TD width='100' align='left' ></td>";
			print "<TD width='224' align='left' ><input  type='submit' name='avanca' value='<<' title='Visualiza os $max primeiros registros.'> <input  type='submit' name='avanca' value='<' title='Visualiza os $max registros anteriores.'> <input  type='submit' name='avanca' value='>' title='Visualiza os pr�ximos $max registros.'> <input  type='submit' name='avanca' value='>>' title='Visualiza os �ltimos $max registros.'> <input  type='submit' name='avanca' value='Todas' title='Visualiza todos os $linhasTotal registros.'></td>";
			print "</tr>";
			$min--;
			print "<input type='hidden' value=".$min." name='min'>";
			print "<input type='hidden' value=".$max." name='max'>";
			print "<input type='hidden' value=".$maxAux." name='maxAux'>";
			print "<input type='hidden' value='listall' name='action'>";
			print "</form>";
			print "</table>"; 
			
		}
		
			//print "<TD>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='".$cor."'>";
			print "<TR class='header'>";
			print "	<TD>Chamado</TD>";
			print "	<TD>Problema</TD>";
			print "	<TD>Contato<BR>Ramal</TD>";
			print " <TD>Local<br>Descri��o</TD>";
			print "	<TD>Status</TD>";
			print "</TR>";
        }
        $i=0;
        $j=2;
        while ($rowAT = $resultado_oco->fetch(PDO::FETCH_ASSOC))
        {
		if ($j % 2)
		{
				$color =  BODY_COLOR;
				$trClass = "lin_par";
		}
		else
		{
				$color = white;
				$trClass = "lin_impar";
		}
		$j++;
		print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."');\">"; 				
		
			
			
		$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']." or dep_filho=".$rowAT['numero']."";
		$execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
		$regSub = $execSubCall->rowCount();
		if ($regSub > 0) {
			#� CHAMADO PAI?
			$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']."";
			$execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
			$regSub = $execSubCall->rowCount();
			$comDeps = false;
			while ($rowSubPai = $execSubCall->fetch(PDO::FETCH_ASSOC)){
				$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
				$execStatus = $PDO->query($sqlStatus) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE STATUS DOS CHAMADOS FILHOS<br>'.$sqlStatus);
				$regStatus = $execStatus->rowCount();
				if ($regStatus > 0) {
					$comDeps = true;
				}
			}
			if ($comDeps) {
				$imgSub = "<img src='".ICONS_PATH."view_tree_red.png' width='16' height='16' title='Chamado com v�nculos pendentes'>";
			} else
				$imgSub =  "<img src='".ICONS_PATH."view_tree_green.png' width='16' height='16' title='Chamado com v�nculos mas sem pend�ncias'>";
		} else
			$imgSub = "";
		
		
		
		print "<TD ".$valign."><a href='mostra_consulta.php?numero=".$rowAT['numero']."'>".$rowAT['numero']."</a>".$imgSub."</TD>";
		print "<TD ".$valign.">".$rowAT['problema']."</TD>";
	        print "<TD ".$valign."><b>".$rowAT['contato']."</b><br>".$rowAT['telefone']."</TD>";
        	print "<TD ".$valign."><b>".$rowAT['setor']."</b><br>";
		$texto = trim($rowAT['descricao']);
		if (strlen($texto)>200){
			$texto = substr($texto,0,195)." ..... ";
		};
		print $texto;
            	print "</TD>";
            	print "<TD ".$valign.">".$rowAT['chamado_status']."</TD>";
		print "</TR>";
		$i++;
        }
        print "</TABLE>";
        print "<HR>";
	}
		
		
	print "</body>";
	print "</html>";        

?>
