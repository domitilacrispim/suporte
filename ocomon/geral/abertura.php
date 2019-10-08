<HTML>
<head>
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

	$s_page_ocomon = "abertura.php";
	session_register("s_page_ocomon");	
	
	$imgsPath = "../../includes/imgs/";
		
	$hoje = date("Y-m-d H:i:s");
	//$time = transvars(date ("l d/m/Y H:i"),$TRANS_WEEK);	
	
	
	
	$valign = " VALIGN = TOP ";

	if ($_SESSION['s_nivel']>2){
			print "<script>window.open('../../index.php','_parent','')</script>";
	}
$conec = new conexao;
$PDO = $conec->conectaPDO();
?>

<!-- <META HTTP-EQUIV="Refresh" CONTENT="840; URL=abertura.php"> -->
<script type="text/javascript">
	 function popup(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+100, window.parent.screenY+100);	  	
		return false
     }
	 
	 window.setInterval("redirect('abertura.php')",120000);
	 
</script>
</head>
<BODY>
<div class="container-fluid">
    <div class="row">

        <div class="col-md-6">
            <h1 class="aw-page-title">Ocomon - M�dulo de ocorrências</h1>
        </div>
        <div class="col-md-6 text-right">
            <p><?php echo transvars(date ("l d/m/Y H:i"),$TRANS_WEEK) ?></p>
        </div>
    </div>

<?
				
		$dt = new dateOpers; //Criado o objeto $dt
		$dta = new dateOpers;

		$cor  = TAB_COLOR;
		$cor1 = TD_COLOR;
		$cor3 = BODY_COLOR;
        
        $percLimit = 20; //Toler�ncia em percentual
        $imgSlaR = 'sla1.png'; 
        $imgSlaS = 'checked.png'; 
		
		//Todas as �reas que o usu�rio percente
		$uareas = $_SESSION['s_area'];
		if ($_SESSION['s_uareas']) {
			$uareas.=",".$_SESSION['s_uareas'];
		}		
		
        
		//$query = "SELECT a.*, u.* from avisos as a, usuarios as u where ((a.area=".$_SESSION['s_area']." and a.status='alta') or (a.area=-1 and a.status='alta')) and a.origem=u.user_id order by data";
		$query = "SELECT a.*, u.*, ar.* from usuarios u, avisos a left join sistemas ar on a.area = ar.sis_id where (a.area in (".$uareas.") or a.area=-1) and a.origem=u.user_id and upper(a.status) = 'ALTA' ORDER BY a.data DESC";
		$resultado = $PDO->query($query) or die ('ERRO AO TENTAR RECUPERAR AS INFORMA��ES DE AVISOS DO MURAL! '.$query);
        $linhas = $resultado->rowCount();
        if ($linhas>0)
        {
?>

        <div class="panel panel-danger">
            <div class="panel-heading">
                <h4 class="panel-title">Aviso(s) Urgente(s):</h4>

            </div>


                <table class="table">

                    <tr class='header'>
			            <td>Data</td>
                        <td>Aviso</td>
                        <td>Respons�vel</td>
                        <td>Para �rea</td>
			        </tr>
<?php

			$j=2;
			while ($resposta = $resultado->fetch(PDO::FETCH_ASSOC))
			{
				if ($j % 2) {
					$color =  BODY_COLOR;
				} else	{
					$color = "white";
				}
				$j++;
				
				print "\n<TR>";
				print "<TD bgcolor='".$color."'>".datab($resposta['data'])."</TD>";
				print "<TD bgcolor='".$color."'>".nl2br($resposta['avisos'])."</TD>";
				print "<TD bgcolor='".$color."'>".$resposta['login']."</TD>";
				if (isIn($resposta['sis_id'],$uareas)) 
					$area_aviso = $resposta['sistema']; else
					$area_aviso = "TODAS";
				print "<TD bgcolor='".$color."'>".$area_aviso."</TD>";
				print "</TR>";
        	}
?>
			    </table>

        </div>

<?php
        }

        $query = "SELECT aviso_id FROM avisos WHERE upper(status) = 'NORMAL' and area in (".$uareas.")"; 

//area=".$_SESSION['s_area'].")

        $resultado = $PDO->query($query) or die ('ERRO PARA RECUPERAR AVISOS COM STATUS NORMAL! '.$query);
        $linhas = $resultado->rowCount();
        if ($linhas==1)
                print "<TR><TD bgcolor='".$cor1."'><B>Existe ".$linhas." aviso n�o exibido nessa tela!  Verifique no mural.</B></TD></TR>";
        if ($linhas>1)
                print "<TR><TD bgcolor='".$cor1."'><B>Existe ".$linhas." avisos n�o exibidos nessa tela!  Verifique no mural.</B></TD></TR>";


        $query = "SELECT empr_id FROM emprestimos WHERE (data_devol <= '".$hoje."' AND responsavel='".$s_uid."')";
        $resultado = $PDO->query($query);
        $linhas = $resultado->rowCount();
        if ($linhas==1)
                print "<TR class='header'><TD>Foi encontrado ".$linhas." empr�stimo pendente para este usu�rio.</TD></TR>";
        if ($linhas>1)
                print "<TR class='header'><TD>Foram encontrados ".$linhas." empr�stimos pendentes para este usu�rio.</TD></TR>";

        //ocorrênciaS VINCULADAS AO OPERADOR
        //PAINEL 1 � O PAINEL SUPERIOR DA TELA DE ABERTURA
        
		$query = $QRY["ocorrencias_full_ini"]." WHERE s.stat_painel in (1) and o.operador = ".$_SESSION['s_uid']." ORDER BY numero";
		
		$resultado_oco = $PDO->query($query) or die ('ERRO NA BUSCA DAS ocorrênciaS DO USU�RIO!'.$query);
        $linhas = $resultado_oco->rowCount();
		
		if ($linhas == 0) {
        	echo mensagem("N�o existem ocorrências pendentes para o usu�rio ".$_SESSION['s_usuario'].".");
        }
        else {//OCORRENCIAS VINCULADAS AO OPERADOR
			if ($linhas>1) {
				$titulo = "Existem $linhas ocorrências <strong>pendentes</strong> para o usu�rio $_SESSION[s_usuario]";
			} else {
				$titulo = "Existe $linhas ocorrência <strong>pendentes</strong> para o usu�rio $_SESSION[s_usuario]";
			}
?>


    <div class="panel panel-info">

        <div class="panel-heading">
            <h4 class="panel-title"><?php echo $titulo; ?></h4>
        </div>


        <table class="table">

        <tr class='header'>
            <td>Chamado</td>
            <td>Problema</td>
            <td>Contato / Ramal</td>
            <td WIDTH=250>Local</td>
            <td>Status</td>
            <td>V�lido</td>
            <td>RESP.</td>
            <td>SOLUC.</td>
        </tr>

<?php

        $i=0;
        $j=2;
        while ($rowAT = mysql_fetch_array($resultado_oco)) {
        	if ($j % 2) {
            	$color =  BODY_COLOR;
				$trClass = "lin_par";				
            } else {
            	$color = "white";
				$trClass = "lin_impar";				
            }
            $j++;
			print "\n<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."');\">";

			$qryImg = "select * from imagens where img_oco = ".$rowAT['numero']."";
			$execImg = $PDO->query($qryImg) or die ("ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE IMAGENS!");
			$rowTela = $execImg->fetch(PDO::FETCH_ASSOC);
			$regImg = $execImg->rowCount();
			if ($regImg!=0) {
				$linkImg = "<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$rowAT['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='../../includes/icons/attach2.png'></a>";
			} else $linkImg = "";

// 			$sqlSubCall = "select * from ocodeps where dep_pai = ".$rowAT['numero']." or dep_filho=".$rowAT['numero']."";
// 			$execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
// 			$regSub = mysql_num_rows($execSubCall);
// 			if ($regSub > 0) {
// 				$imgSub = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='Chamado com v�nculos'>";
// 			} else
// 				$imgSub = "";
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
			print "<TD ".$valign.">".$linkImg."&nbsp;".$rowAT['problema']."</TD>";
            print "<TD ".$valign."><b>".$rowAT['contato']."</b><br>".$rowAT['telefone']."</TD>";
            print "<TD ".$valign."><b>".$rowAT['setor']."</b><br>";
			$texto = trim($rowAT['descricao']);
			if (strlen($texto)>200){
				$texto = substr($texto,0,195)." ..... ";
			};
			print $texto;
            print "</TD>";
            print "<TD ".$valign.">".$rowAT['chamado_status']."</TD>";
			
			
			
									
			// if (array_key_exists($rowAT['cod_area'],$H_horarios)){  //verifica se o c�digo da �rea possui carga hor�ria definida no arquivo config.inc.php
					//$areaChamado = $rowAT['cod_area']; //Recebe o valor da �rea de atendimento do chamado
			// } else $areaChamado = 1; //Carga hor�ria default definida no arquivo config.inc.php
			$areaChamado=testaArea($areaChamado,$rowAT['area_cod'],$H_horarios);

			$data = $rowAT['data_abertura'];
			
			$diff = data_diff($data,$hoje);
			$sep = explode ("dias",$diff);
			if ($sep[0]>=1) { //Se o chamado estiver aberto a mais de 20 dias o tempo � mostrado em dias para n�o ficar muito pesado.
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

			print "<TD ".$valign." align='center'><a onClick=\"javascript:popup('sla_popup.php?sla=r')\"><img height='14' width='14' src='".$imgsPath."".$imgSlaR."'></a></TD>";
			print "<TD ".$valign." align='center'><a onClick=\"javascript:popup('sla_popup.php?sla=s')\"><img height='14' width='14' src='".$imgsPath."".$imgSlaS."'></a></TD>";

			print "</TR>";
            $i++;
        }
        ?>
        </table>

    </div>
<?php
        }
//Ocorrencias gerais em aberto

//----------------------------------------------------------------------------------------------------						
	#####################################################         
			
		// $qry ="select * from usuarios_areas where uarea_uid=".$_SESSION['s_uid']."";
		// $exec = $PDO->query($qry);
		// $uareas = $_SESSION['s_area'].",";
		// while($row_areas = mysql_fetch_array($exec)){
			// $uareas.=$row_areas['uarea_sid'].",";
		// }
		// $uareas = substr($uareas,0,-1);

        
		//---------------------------- ocorrênciaS AGUARDANDO ATENDIMENTO ----------------------------------//
		//******** ocorrências aguardando atendimento => status=1 *********
		//PAINEL 2 � O PAINEL PRINCIPAL DA TELA DE ABERTURA, ONDE FICAM TODOS OS CHAMADOS PENDENTES DE ATENDIMENTO
        $query = $QRY["ocorrencias_full_ini"]." WHERE s.stat_painel in (2) and o.sistema in (".$uareas.") ORDER BY area ,numero";
		$resultado = $PDO->query($query);
        $linhas =$resultado->rowCount();
        if ($linhas == 0) {
        	echo mensagem("N�o existe nenhuma ocorrência pendente no sistema.");
            exit;
        } else
        if ($linhas>1)
        	$titulo = "Existem ".$linhas." ocorrências <strong>pendentes</strong> no sistema.";
        else
            $titulo = "Existe apenas 1 ocorrência <strong>pendente</strong> no sistema.";


        $query2 = "SELECT ver_nao_atribuidos FROM usuarios WHERE user_id = ".$_SESSION['s_uid']." LIMIT 1";
        $resultado2 = $PDO->query($query2) or die ('ERRO AO TENTAR RECUPERAR AS INFORMA��ES DE USU�RIO! '.$query2);
        $linha2 = $resultado2->fetch(PDO::FETCH_ASSOC);

        if($linha2['ver_nao_atribuidos'] == 1) {

?>

            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h4 class="panel-title"><?php echo $titulo; ?></h4>
                </div>

                <table class="table">

                    <tr class='header'>
                        <td>Chamado / �rea</td>
                        <td>Problema</td>
                        <td>Contato / Ramal</td>
                        <td WIDTH=250>Local</td>
                        <td>Tempo / v�lido</td>
                        <td>A��o</td>
                        <td>RESP.</td>
                        <td>SOLUC.</td>
                    </tr>

                    <?php

                    $i = 0;
                    $j = 2;
                    while ($row =$resultado->fetch(PDO::FETCH_ASSOC)) {
                        if ($j % 2) {
                            $color = BODY_COLOR;
                            $trClass = "lin_par";
                        } else {
                            $color = white;
                            $trClass = "lin_impar";
                        }
                        $j++;

                        print "<tr class=" . $trClass . " id='linha" . $j . "' onMouseOver=\"destaca('linha" . $j . "');\" onMouseOut=\"libera('linha" . $j . "');\"  onMouseDown=\"marca('linha" . $j . "');\">";

                        $sqlSubCall = "select * from ocodeps where dep_pai = " . $row['numero'] . " or dep_filho=" . $row['numero'] . "";
                        $execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>' . $sqlSubCall);
                        $regSub = $execSubCall->rowCount();
                        if ($regSub > 0) {
                            #� CHAMADO PAI?
                            $sqlSubCall = "select * from ocodeps where dep_pai = " . $row['numero'] . "";
                            $execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>' . $sqlSubCall);
                            $regSub = $execSubCall->rowCount();
                            $comDeps = false;
                            while ($rowSubPai = $execSubCall->fetch(PDO::FETCH_ASSOC)) {
                                $sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=" . $rowSubPai['dep_filho'] . " and o.`status`=s.stat_id and s.stat_painel not in (3) ";
                                $execStatus = $PDO->query($sqlStatus) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE STATUS DOS CHAMADOS FILHOS<br>' . $sqlStatus);
                                $regStatus = $execStatus->rowCount();
                                if ($regStatus > 0) {
                                    $comDeps = true;
                                }
                            }
                            if ($comDeps) {
                                $imgSub = "<img src='" . ICONS_PATH . "view_tree_red.png' width='16' height='16' title='Chamado com v�nculos pendentes'>";
                            } else
                                $imgSub = "<img src='" . ICONS_PATH . "view_tree_green.png' width='16' height='16' title='Chamado com v�nculos mas sem pend�ncias'>";
                        } else
                            $imgSub = "";


                        $qryImg = "select * from imagens where img_oco = " . $row['numero'] . "";
                        $execImg = $PDO->query($qryImg) or die ("ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE IMAGENS!");
                        $rowTela = $execImg->fetch(PDO::FETCH_ASSOC);
                        $regImg = $execImg->rowCount();
                        if ($regImg != 0) {
                            $linkImg = "<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=" . $row['numero'] . "&cod=" . $rowTela['img_cod'] . "'," . $rowTela['img_largura'] . "," . $rowTela['img_altura'] . ")\"><img src='" . ICONS_PATH . "attach2.png' width='16' height='16'></a>";
                        } else $linkImg = "";


                        print "<td " . $valign . "><b>" . $row['numero'] . "</b>" . $imgSub . "<br>" . $row['area'] . "<br></td>";
                        print "<TD " . $valign . ">" . $linkImg . "&nbsp;" . $row['problema'] . "</td>";
                        print "<TD " . $valign . "><b>" . $row['contato'] . "</b><br>" . $row['telefone'] . "</td>";

                        $limite = 150;
                        $texto = trim($row['descricao']);
                        if (strlen($texto) > $limite) {
                            $texto = substr($texto, 0, ($limite - 3)) . "... ";
                        };
                        print "<TD " . $valign . "><b>" . $row['setor'] . "</b><br>" . $texto . "</td>";

                        $data = dataRED($row['data_abertura']);

                        $areaT = testaArea($areaT, $row['area_cod'], $H_horarios);

                        $data = $row['data_abertura']; //data de abertura do chamado
                        $dataAtendimento = $row['data_atendimento']; //data da primeira resposta ao chamado

                        $diff = data_diff($data, $hoje);
                        $sep = explode("dias", $diff);

                        $dt->setData1($data);
                        $dt->setData2($hoje);
                        $dt->tempo_valido($dt->data1, $dt->data2, $H_horarios[$areaT][0], $H_horarios[$areaT][1], $H_horarios[$areaT][2], $H_horarios[$areaT][3], "H");
                        $horas = $dt->diff["hValido"];    //horas v�lidas
                        $segundos = $dt->diff["sValido"]; //segundos v�lidos

                        if ($sep[0] >= 1) { //Se o chamado estiver aberto a mais de 1 dias o tempo � mostrado em dias para n�o ficar muito pesado.
                            $imgSlaR = 'checked.png';
                            $imgSlaS = 'checked.png';
                            print "<TD " . $valign . "><font color=red><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=" . $row['numero'] . "')\">$sep[0] dias</a></font>" .
                                "<br>" . $row['chamado_status'] . "</TD>";
                        } else {

                            print "<TD " . $valign . " class='$diff'><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=" . $row['numero'] . "')\">" . $dt->tValido . "</a>" .
                                "<br>" . $row['chamado_status'] . "</TD>";

                        }

                        //------------------------------------
                        if ($dataAtendimento == "") {//Controle das bolinhas de SLA de Resposta
                            if ($segundos <= ($row['sla_resposta_tempo'] * 60)) {
                                $imgSlaR = 'sla1.png';
                            } else if ($segundos <= (($row['sla_resposta_tempo'] * 60) + (($row['sla_resposta_tempo'] * 60) * $percLimit / 100))) {
                                $imgSlaR = 'sla2.png';
                            } else {
                                $imgSlaR = 'sla3.png';
                            }
                        } else
                            $imgSlaR = 'checked.png';
                        //-----------------------------------------

                        $sla_tempo = $row['sla_solucao_tempo'];
                        if ($sla_tempo != "") { //Controle das bolinhas de SLA de solu��o
                            if ($segundos <= ($row['sla_solucao_tempo'] * 60)) {
                                $imgSlaS = 'sla1.png';
                            } else if ($segundos <= (($row['sla_solucao_tempo'] * 60) + (($row['sla_solucao_tempo'] * 60) * $percLimit / 100))) {
                                $imgSlaS = 'sla2.png';
                            } else
                                $imgSlaS = 'sla3.png';
                        } else
                            $imgSlaS = 'checked.png';
                        //-----------------------------------------------------


                        print "<TD " . $valign . " align=center>";
                        print "<a href=mostra_consulta.php?numero=" . $row['numero'] . "><img title='Consultar' width=15 height=15 src='" . $imgsPath . "consulta.gif' border=0></a>";
                        print "<font color='" . $color . "' " . $valign . " align='center' face='1'>.</font>";

                        print "</TD>";

                        print "<TD " . $valign . " align='center'><a onClick=\"javascript:popup('sla_popup.php?sla=r')\"><img height='14' width='14' src='" . $imgsPath . "" . $imgSlaR . "'></a></TD>";
                        print "<TD " . $valign . " align='center'><a onClick=\"javascript:popup('sla_popup.php?sla=s')\"><img height='14' width='14' src='" . $imgsPath . "" . $imgSlaS . "'></a></TD>";

                        echo "</TR>";
                        $i++;
                    }//while
                    ?>
                </table>
            </div>

<?php
        }
?>

    </div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="../../includes/js/jquery-2.1.0.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../../includes/js/bootstrap.min.js"></script>

</BODY>
</HTML>