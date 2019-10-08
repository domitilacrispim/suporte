<?php

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
	
	
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;

		if ($popup) {
			$auth->testa_user_hidden($s_usuario,$s_nivel,$s_nivel_desc,2);
		} else
			$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);
	
	
	$hojeLog = date ("d-m-Y H:i:s");


	
			print "<input type='hidden' name='comp_inv' value='$comp_inv'>";
			print "<input type='hidden' name='comp_inst' value='$comp_inst'>";
	
	
		$qry= "select comp_local from equipamentos where comp_inv = ".$comp_inv." and comp_inst=".$comp_inst."";
		$exec = mysql_query($qry);
		$rowLocal = mysql_fetch_array($exec);
	
	$sql = "SELECT o.numero as numero, o.data_abertura as abertura, o.data_fechamento as fechamento, o.telefone as ramal,
			 p.problema as problema, s.status as status, l.local as local, l.loc_id as loc_id 
			 FROM ocorrencias AS o left join problemas AS p on o.problema = p.prob_id, status AS s, localizacao as l
			WHERE o.status = s.stat_id AND o.local=l.loc_id and o.equipamento = $comp_inv 
			AND o.instituicao = $comp_inst order by o.numero";
	
	
	$commit = mysql_query($sql);
	//$rowA = mysql_fetch_array($commit);
	$linhas = mysql_num_rows($commit);
	
    if ($linhas == 0)
    {
            echo "<b><p align=center>Nenhum chamado cadastrado no OCOMON para esse equipamento!</b></p>";
			print "<table width='100%'>";
			print "<tr><td align='left' width='80%'><a onClick= \"javascript:popup_alerta_wide('../../ocomon/geral/incluir.php?invTag=".$comp_inv."&invInst=".$comp_inst."&invLoc=".$rowLocal['comp_local']."')\">Abrir nova ocorrência para esse equipamento</a></td><td align='right'><input type='button' class='minibutton' value='Fechar' onClick=\"javascript:window.close()\"</td></tr>";
			print "</table>";

			exit;
    } else
       
	if ($linhas>0){
			print "<br>";
			print "<table class=corpo>";
			print "<tr>";
			print "<TD width='500' align='left'><B>Equipamento $comp_inv: <font color='red'>$linhas</font> chamado(s) no OCOMON:</B></TD>";
			print "<TD width='100' align='left'></td>";
			print "<TD width='100' align='left'></TD>";
			
			print "<TD width='200' align='left'></TD>";
			print "</tr>";
			print "</table><br>";
	}
	
    print "<table class=corpo2 >";
	print "<TR class='header'><TD><b>N�mero</TD><Td><b>Problema</TD><Td><b>Abertura</TD><TD><b>Fechamento</TD><TD><b>Situa��o</TD>";
       
       $j=2;
       //$firstPlace = false;
	   $cont=0;
	   while ($row = mysql_fetch_array($commit))
       {
		  $cont++;
          if ($j % 2)
          {
                  $trClass = "lin_par";
          }
          else
          {
                  $trClass = "lin_impar";
          }
          $j++;
          //window.open('../../ocomon/mostra_consulta.php?numero=".$row['numero']."' ,'Alerta','width=600,height=300,scrollbars=yes,statusbar=no,resizable=yes')     
          //\"javascript: popup_alerta('../../ocomon/mostra_consulta.php?numero=".$row['numero']."')\"
		  print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
             
			
/*			$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." or dep_filho=".$row['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			if ($regSub > 0) {
				$imgSub = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='Chamado com v�nculos'>";
			} else
				$imgSub = "";*/
			
			$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." or dep_filho=".$row['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			if ($regSub > 0) {
				#� CHAMADO PAI?
				$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
				$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
				$regSub = mysql_num_rows($execSubCall);
				$comDeps = false;
				while ($rowSubPai = mysql_fetch_array($execSubCall)){
					$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
					$execStatus = mysql_query($sqlStatus) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE STATUS DOS CHAMADOS FILHOS<br>'.$sqlStatus);
					$regStatus = mysql_num_rows($execStatus);
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
			
			
			
			$qryImg = "select * from imagens where img_oco = ".$row['numero']."";
			$execImg = mysql_query($qryImg) or die ("ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE IMAGENS!");
			$rowTela = mysql_fetch_array($execImg);
			$regImg = mysql_num_rows($execImg);
			if ($regImg!=0) {
				$linkImg = "<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$row['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='".ICONS_PATH."attach2.png'></a>";
			} else $linkImg = "";
             
             
             print "<TD><a onClick= \"javascript:popup_alerta('../../ocomon/geral/mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><font color='blue'>".$row['numero']."</font></a>".$imgSub."</TD>";
			 print "<TD>".$linkImg."&nbsp; ".$row['problema']."</TD>";
             print "<TD>".$row['abertura']."</TD>";
             print "<TD>".$row['fechamento']."</TD>";			 
             print "<TD>".$row['status']."</TD>";
          print "</TR>";
			// if (!$firstPlace){
				$invRamal = $row['ramal'];
				// $firstPlace = true;
			// }
	   }
       print "</TABLE>";
	
	
		print "<table width='100%'>";
		print "<tr><td align='left' width='80%'><a onClick= \"javascript:popup_alerta_wide('../../ocomon/geral/incluir.php?invTag=".$comp_inv."&invInst=".$comp_inst."&invLoc=".$rowLocal['comp_local']."&telefone=".$invRamal."')\">Abrir nova ocorrência para esse equipamento</a></td><td align='right'><input type='button' class='minibutton' value='Fechar' onClick=\"javascript:window.close()\"</td></tr>";
		print "</table>";
	

	
	$origem = "javascript:history.go(-2)";
    session_register("aviso");
    session_register("origem");
	
	$cab->set_foot();
	
?>