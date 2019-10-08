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

	include ("PATHS.php");
	include ("".$includesPath."var_sessao.php");
	include ("includes/functions/funcoes.inc");
	include ("includes/javascript/funcoes.js");
	include ("includes/queries/queries.php");
	include ("".$includesPath."config.inc.php");
	include ("".$includesPath."languages/".LANGUAGE."");
	include ("".$includesPath."versao.php");
	
	include("includes/classes/conecta.class.php");
	
	session_register("s_logado");
    $conec = new conexao;
    $PDO = $conec->conectaPDO();
	if ($s_logado==0)
	{
	        print "<script>window.open('index.php','_parent','')</script>";
	}	
	
	


 	//$s_page_home = "home.php";
 	//session_register("s_page_home");

	$hoje = date("d-m-Y H:i:s");
	$hoje2 = date("d/m/Y");


    $query2 = "SELECT ver_nao_atribuidos FROM usuarios WHERE user_id = ".$_SESSION['s_uid']." LIMIT 1";
    $resultado2 = $PDO->query($query2) or die ('ERRO AO TENTAR RECUPERAR AS INFORMA��ES DE USU�RIO! '.$query2);
    $linha2 = $resultado2->fetch(PDO::FETCH_ASSOC);

    $nao_atrib = $linha2['ver_nao_atribuidos'];

print "<html>";
print "<head>";
print "<title>OCOMON ".VERSAO."</title>";
print "<link rel=stylesheet type='text/css' href='includes/css/estilos.css'>";


	if ($_SESSION['s_nivel']>3) {
			print "<script>window.open('./index.php','_parent','')</script>";
	}

	print "<TABLE  bgcolor='black' cellspacing='1' border='1' cellpadding='1' align='center' width='100%'>".
			"<TD bgcolor='".TD_COLOR."'>".
					"<TABLE  cellspacing='0' border='0' cellpadding='0' bgcolor='".TAB_COLOR."'>".
							"<TR>";
							$cor1 = TD_COLOR;
							print  "<TD bgcolor='".$cor1."' nowrap><b>OcoMon - M�dulo de ocorrências</b></TD><td width='20%' nowrap><p class='parag'><b>".transvars(date ("l d/m/Y H:i"),$TRANS_WEEK)."</b></p></TD>"; 
							print "</TR>".
					"</TABLE>".
			"</TD>".
		"</TABLE>";

	//Todas as �reas que o usu�rio percente
	$uareas = $_SESSION['s_area'];
	if ($_SESSION['s_uareas']) {
		$uareas.=",".$_SESSION['s_uareas'];
	}	
	
	$qryTotal = "select a.sistema area, a.sis_id area_cod from ocorrencias o left join sistemas a on o.sistema = a.sis_id". 	
			" left join `status` s on s.stat_id = o.status where o.sistema in (".$uareas.") and s.stat_painel in (1,2) ";
	$execTotal = $PDO->query($qryTotal) or die ('ERRO NA TOTALIZA��O DAS ocorrênciaS!'.$qryTotal);
	$regTotal = $execTotal->rowCount();
	
	//Todas as �reas que o usu�rio percente
	$qryAreas = "select count(*) total, a.sistema area, a.sis_id area_cod from ocorrencias o left join sistemas a on o.sistema = a.sis_id". 	
			" left join `status` s on s.stat_id = o.status where o.sistema in (".$uareas.") and s.stat_painel in (1,2) ".
			"group by a.sistema";
	$execAreas = $PDO->query($qryAreas) or die('ERRO NA TENTATIVA DE RECUPERAR TODAS AS ocorrênciaS! '.$qryAreas);
	$regAreas =$execAreas->rowCount();
	
	print "<br>";
	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
	
	
	print "<tr><td colspan='7'><IMG ID='imggeral' SRC='./includes/icons/close.png' width='9' height='9' ".
			"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('geral')\">&nbsp;<b>Existem <font color='red'>".$regTotal."</font>".
			" ocorrências em aberto no sistema para as �reas que voc� faz parte.</b></td></tr>";

	print "<tr><td style='{padding-left:5px;}'><div id='geral' >"; //style='{display:none}'
	$a = 0;
	$b = 0;
	while ($rowAreas = $execAreas->fetch(PDO::FETCH_ASSOC)) {


			
			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
			print "<tr><td colspan='7'><IMG ID='imgocorrencias".$b."' SRC='./includes/icons/close.png' width='9' height='9' ".
						"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('ocorrencias".$b."')\">&nbsp;<b>Existem <font color='red'>".$rowAreas['total']."</font>".
						" ocorrências em aberto no sistema para a �rea: <font color='green'>".$rowAreas['area']."</font></b></td></tr>";

			print "<tr><td style='{padding-left:5px;}'><div id='ocorrencias".$b."'>"; //style='{display:none}'

				//TOTAL DE N�VEIS DE STATUS
				$qryStatus = "select count(*) total, o.*, s.* from ocorrencias o left join `status` s on o.status = s.stat_id where ".
						"o.sistema = ".$rowAreas['area_cod']." and s.stat_painel in (1,2) group by s.status";
				$execStatus = $PDO->query($qryStatus) or die ('ERRO NA QUERY DE STATUS! '.$qryStatus);
				//$a = 0;
				print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
				While ($rowStatus = $execStatus->fetch(PDO::FETCH_ASSOC)) {

                    if ($rowStatus['stat_id'] != 1 || $nao_atrib == 1) {

                        print "<tr><td colspan='7'><IMG ID='imgstatus" . $a . "' SRC='./includes/icons/open.png' width='9' height='9' " .
                            "STYLE=\"{cursor: pointer;}\" onClick=\"invertView('status" . $a . "')\">&nbsp;<b>Status: " . $rowStatus['status'] . " - " .
                            "" . $rowStatus['total'] . " ocorrências</b><br>";
                        print "<div id='status" . $a . "' style='{display:none}' >"; //style='{display:none}'

                        print "<TABLE border='0' style='{padding-left:10px;}' cellpadding='5' cellspacing='0' align='left' width='100%'>";


                        $qryDetail = $QRY["ocorrencias_full_ini"] . " WHERE o.sistema = " . $rowAreas['area_cod'] . " and s.stat_painel in (1,2) and " .
                            " o.status = " . $rowStatus['stat_id'] . "";
                        $execDetail = $PDO->query($qryDetail) or die ('ERRO NA TENTATIVA DE RECUPERAR OS DADOS DAS ocorrênciaS! ' . $qryDetail);

                        print "<tr class='header'><td>N�mero</td><td>Problema</td><td>Contato<br>ramal</td><td>Local<br>Descric�o</td><td>�ltimo Operador</td></tr>";

                        $j = 2;
                        while ($rowDetail =$execDetail->fetch(PDO::FETCH_ASSOC)) {
                            if ($j % 2) {
                                $trClass = "lin_par";
                            } else {
                                $trClass = "lin_impar";
                            }
                            $j++;

                            print "<tr class=" . $trClass . " id='linha" . $j . "" . $a . "' onMouseOver=\"destaca('linha" . $j . "" . $a . "');\" onMouseOut=\"libera('linha" . $j . "" . $a . "');\"  onMouseDown=\"marca('linha" . $j . "" . $a . "');\">";

                            $qryImg = "select * from imagens where img_oco = " . $rowDetail['numero'] . "";
                            $execImg = $PDO->query($qryImg) or die ("ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE IMAGENS!");
                            $rowTela = $execImg->fetch(PDO::FETCH_ASSOC);
                            $regImg = $execImg->rowCount();
                            if ($regImg != 0) {
                                $linkImg = "<a onClick=\"javascript:popupWH('includes/functions/showImg.php?file=" . $rowDetail['numero'] . "&cod=" . $rowTela['img_cod'] . "'," . $rowTela['img_largura'] . "," . $rowTela['img_altura'] . ")\"><img src='includes/icons/attach2.png'></a>";
                            } else $linkImg = "";


                            $sqlSubCall = "select * from ocodeps where dep_pai = " . $rowDetail['numero'] . " or dep_filho=" . $rowDetail['numero'] . "";
                            $execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>' . $sqlSubCall);
                            $regSub = $execSubCall->rowCount();
                            if ($regSub > 0) {
                                #� CHAMADO PAI?
                                $_sqlSubCall = "select * from ocodeps where dep_pai = " . $rowDetail['numero'] . "";
                                $_execSubCall = $PDO->query($_sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>' . $_sqlSubCall);
                                $_regSub = $_execSubCall->rowCount();
                                $comDeps = false;
                                while ($rowSubPai = $_execSubCall->fetch(PDO::FETCH_ASSOC)) {
                                    $_sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=" . $rowSubPai['dep_filho'] . " and o.`status`=s.stat_id and s.stat_painel not in (3) ";
                                    $_execStatus = $PDO->query($_sqlStatus) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE STATUS DOS CHAMADOS FILHOS<br>' . $_sqlStatus);
                                    $_regStatus = $_execStatus->rowCount();
                                    if ($_regStatus > 0) {
                                        $comDeps = true;
                                    }
                                }
                                if ($comDeps) {
                                    $imgSub = "<img src='includes/icons/view_tree_red.png' width='16' height='16' title='Chamado com v�nculos pendentes'>";
                                } else
                                    $imgSub = "<img src='includes/icons/view_tree_green.png' width='16' height='16' title='Chamado com v�nculos mas sem pend�ncias'>";
                            } else
                                $imgSub = "";


                            print "<TD><a onClick=\"javascript: popup_alerta('./ocomon/geral/mostra_consulta.php?popup=true&numero=" . $rowDetail['numero'] . "')\">" . $rowDetail['numero'] . "</a> " . $imgSub . "</TD>";

                            //print "<TD>".$rowDetail['numero']."</TD>";
                            print "<TD>" . $linkImg . "&nbsp;" . $rowDetail['problema'] . "</TD>";
                            print "<TD><b>" . $rowDetail['contato'] . "</b><br>" . $rowDetail['telefone'] . "</TD>";
                            $texto = trim($rowDetail['descricao']);
                            if (strlen($texto) > 200) {
                                $texto = substr($texto, 0, 195) . " ..... ";
                            };
                            print "<TD><b>" . $rowDetail['setor'] . "</b><br>" . $texto . "</TD>";
                            print "<TD>" . $rowDetail['nome'] . "</TD>";

                            print "</TR>";

                        }

                        print "</table>";
                        print "</div></td></tr>"; //status


                        $a++;

                    }

				}
			print "</table>";
			print "</div></td></tr>"; //ocorrencias
			print "</table>";
		$a++;
		$b++;
	}
	print "</div></td></tr>"; //geral
	print "</table>";
//print $qryDetail; exit;	
?>
<SCRIPT LANGUAGE=javaScript>
<!--				
			
	function invertView(id) {
		var element = document.getElementById(id);
		var elementImg = document.getElementById('img'+id);
		var address = './includes/icons/';
			
		if (element.style.display=='none'){
			element.style.display='';
			elementImg.src = address+'close.png';
		} else {
			element.style.display='none';
			elementImg.src = address+'open.png';
		}
	}

	window.setInterval("redirect('home.php')",120000);	
//-->
</script>
<?php
	
print "</body>";
print "</html>";


?>
