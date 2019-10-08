<!DOCTYPE html>
<HTML>
<head>
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
?>

</head>
<body>
<div class="container-fluid">

<?php
    $conec = new conexao;
    $PDO = $conec->conectaPDO();

$auth = new auth;
	if ($popup) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);	
	
	    
		$query = $QRY["ocorrencias_full_ini"]." where numero in (".$numero.") order by numero";
		$resultado = $PDO->query($query);
		$row = $resultado->fetch(PDO::FETCH_ASSOC);

        $query2 = "select a.*, u.* from assentamentos a, usuarios u where a.responsavel=u.user_id and a.ocorrencia='$numero'";
        $resultado2 =$PDO->query($query2);
        $linhas=$resultado2->rowCount();

		if ($_SESSION['s_nivel'] == 1)
		    $linkEdita = "<a class='btn btn-default' href='altera_dados_ocorrencia.php?numero=$numero'>Editar como admin</a>";
		else
			$linkEdita = "";
		
		$sqlPai = "select * from ocodeps where dep_filho = ".$_GET['numero']." ";
		$execpai = $PDO->query($sqlPai) or die ('N�O FOI POSS�VEL RECUPERAR AS INFORMA��ES DE DEPEND�NCIAS DO CHAMADO!');
		$rowPai = $execpai->fetch(PDO::FETCH_ASSOC);
		if ($rowPai['dep_pai']!=""){
			$msgPai = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='Chamado com v�nculos'><u><a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$rowPai['dep_pai']."')\">Esta ocorrência � um sub-chamado da ocorrência ".$rowPai['dep_pai']."</a></u>";
		} else
			$msgPai = "";
?>

<script type='text/javascript'>

	 function popup_alerta(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'_blank','dependent=yes,width=900,height=650,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);	  	
		return false
     }

	 function popup_alerta_mini(pagina)	{ //Exibe uma janela popUP
      	x=window.open(pagina,'_blank','dependent=yes,width=400,height=250,scrollbars=yes,statusbar=no,resizable=yes');
      	x.moveTo(100,100);
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);	  	
		return false
	 }
	 function popup(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+100, window.parent.screenY+100);	  	
		return false
     }
	 
</script>

    <div class="btn-group btn-group-sm mb-10" role="group" >

    <?php if ($row['status_cod']!=4 && $_SESSION['s_nivel'] < 3) { ?>
        <a class="btn btn-default" href='encerramento.php?numero=<?php echo $row['numero']?>'>Encerrar ocorrência</a>
    <?php } ?>
        <a class="btn btn-default" href='mostra_relatorio_individual.php?numero=<?php echo $row['numero']?>' target='_blank'>Imprimir ocorrência</a>
    <?php if ($_SESSION['s_nivel'] < 3) {?>
        <a class="btn btn-default" href='encaminhar.php?numero=<?php echo $row['numero'] ?>'>Editar ocorrência</a>
        <?php echo $linkEdita ?>
    <?php }
          if (($row['status_cod']!=2) && ($row['status_cod']!=4) && ($_SESSION['s_nivel'] < 3)) {
    ?>
        <a class="btn btn-default" href='atender.php?numero=<?php echo $numero?>'>Atender</a>
    <?php }?>
        <a class="btn btn-default" onClick="javascript:popup('mostra_sla_definido.php?popup=true&numero=<?php echo $row['numero'] ?>')">SLA</a>
    <?php if ($row['status_cod']!=4 && $_SESSION['s_nivel'] < 3) { ?>
        <a class="btn btn-default" onClick="javascript:popup_alerta('incluir.php?popup=true&pai=<?php echo $row['numero'] ?>&invTag=<?php echo $row['etiqueta'] ?>&invInst=<?php echo $row['unidade_cod'] ?>&invLoc=<?php echo $row['setor_cod'] ?>&telefone=<?php echo $row['telefone']?>&problema=<?php echo $row['prob_cod'] ?>')">Abrir sub-chamado</a>
    <?php } ?>
        <a class="btn btn-default" onClick="javascript:popup('tempo_doc.php?popup=true&cod=<?php echo $row['numero']?>')">Tempo de documenta��o</a>

    </div>

    <div class="panel panel-success">
        <div class="panel-heading">
            <h4 class="panel-title">Consulta de ocorrências: <?php echo $msgPai ?></h4>
        </div>

        <table class="table">

            <tr>
                <td width="20%" align="left" bgcolor=<?print TD_COLOR?>>N�mero:</td>
                <td width="30%" align="left" bgcolor="white"><?print $row['numero'];?></td>
                <td width="20%" align="left" bgcolor=<?print TD_COLOR?>>�rea Respons�vel:</td>
                <td width="30%" align="left" bgcolor="white"><?print $row['area'];?></td>
		    </tr>
            <tr>
                <td width="20%" align="left" bgcolor=<?print TD_COLOR?>>Problema:</td>
                <td width="30%" align="left" bgcolor="white"><?print $row['problema'];?></td>
				<td width="20%" align="left" bgcolor=<?print TD_COLOR?>>Aberto por:</td>
                <td width="30%" align="left" bgcolor="white"><?print $row['aberto_por'];?></td>
            </tr>
            <tr>
                <td width="20%" align="left" bgcolor="<?print TD_COLOR?>" valign="top">Descri��o:</td>
                <td colspan='3' width="80%" align="left" bgcolor="white"><?print nl2br($row['descricao']);?></td>
            </tr>

        <?php
        if ($linhas!=0)
        {
                $i=0;
				while ($rowAS=$resultado2->fetch(PDO::FETCH_ASSOC))
                {
                        ?>
                        <tr>        
								<td align="left" bgcolor="<?print TD_COLOR?>" valign="top">Assentamento <?print $i+1;?> de <?print $linhas;?> por <b><?print $rowAS['nome'];?></b> em <?print datab($rowAS['data']);?></td>
                                <td colspan='3' align="left" bgcolor="white" valign="top"><?print nl2br($rowAS['assentamento']);?></td>
                        </tr>
                        <?
					$i++;
				}
        }
        ?>

            <tr>
				<td width="20%" align="left" bgcolor=<?print TD_COLOR?>>Unidade:</td>
                <td width="30%" align="left" bgcolor="white"><?print $row['unidade'];?></td>
                <td width="20%" align="left" bgcolor=<?print TD_COLOR?>>Etiqueta do equipamento:</td>
                <td width="30%" align="left" bgcolor="white"><a href="" onClick="popup_alerta('../../invmon/geral/mostra_consulta_inv.php?comp_inst=<?print $row['unidade_cod']?>&comp_inv=<?print $row['etiqueta'];?>&popup=true')"><font color='blue'><u><?print $row['etiqueta'];?></u></font></td>
            </tr>
            <tr>
                <td width="20%" align="left" bgcolor=<?print TD_COLOR?>>Contato:</td>
                <td width="30%" align="left" bgcolor="white"><?print $row['contato'];?></td>
                <td width="20%" align="left" bgcolor=<?print TD_COLOR?>>Ramal:</td>
                <td width="30%" align="left" bgcolor="white"><?print $row['telefone'];?></td>
            </tr>
            <tr>
                <td width="20%" align="left" bgcolor=<?print TD_COLOR?>>Local:</td>
                <td width="30%" align="left" bgcolor="white"><?print $row['setor'];?></td>
                <td width="20%" align="left" bgcolor=<?print TD_COLOR?>>�ltimo operador:</td>
                <td width="30%" align="left" bgcolor="white"><?print $row['nome'];?></td>
            </tr>
        <?
        if ($row['status_cod'] == 4)
        {
        ?>
                <tr>
                        <td align="left" bgcolor="white">Abertura: <?print datab($row['data_abertura']);?></td>
                        <td align="left" bgcolor="white">Encerramento: <?print datab($row['data_fechamento']);?></td>
                        <td align="left" bgcolor=<?print TD_COLOR?>>Status:</td>
                        <td align="left" bgcolor="white"><font color='blue'><u><a  onClick="popup_alerta_mini('mostra_hist_status.php?numero=<?print $numero;?>&popup=true')"><?print $row['chamado_status'];?></u></a></font></td>
                </tr>
        <?
        }
        else
        {
                ?>
                <tr>
                        <td width="20%" align="left" bgcolor=<?print TD_COLOR?>>Data de abertura:</td>
                        <td width="30%" align="left" bgcolor="white"><?print datab($row['data_abertura']);?></td>
                        <td width="20%" align="left" bgcolor=<?print TD_COLOR?>>Status:</td>
                        <td width="30%" align="left" bgcolor="white"><b><font color='blue'><u><a  onClick="popup_alerta_mini('mostra_hist_status.php?numero=<?print $numero;?>&popup=true')"><?print $row['chamado_status'];?></u></a></font></b></td>
                </tr>

                <?
        }
        
        
	$qryTela = "select * from imagens where img_oco = ".$row['numero']."";
	$execTela = $PDO->query($qryTela) or die ("N�O FOI POSS�VEL RECUPERAR AS INFORMA��ES DA TABELA DE IMAGENS!");
	//$rowTela = mysql_fetch_array($execTela);
	$isTela = $execTela->rowCount();
	$cont = 0;
	while ($rowTela = $execTela->fetch(PDO::FETCH_ASSOC)) {
	//if ($isTela !=0) {		
		$cont++;
?>
		<tr>
		    <td  bgcolor='<?php echo TD_COLOR ?>' >Anexo <?php echo $cont ?>:</td>
		    <td colspan='3' bgcolor='white'>
                <a onClick="javascript:popupWH('../../includes/functions/showImg.php?file=<?php echo $row['numero']."&cod=".$rowTela['img_cod']?>',<?php echo $rowTela['img_largura']?>,<?php echo $rowTela['img_altura']?>)">
                    <img src='../../includes/icons/attach2.png'> <?php echo $rowTela['img_nome'] ?>
                </a>
            </td>";
		</tr>
<?php
	}
        
        
        
    $qrySubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
    $execSubCall = $PDO->query($qrySubCall) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE SUB-CHAMADOS!<br>'.$qrySubCall);
	$existeSub = $execSubCall->rowCount();
	if ($existeSub>0) {
		$comDeps = false;
		while ($rowSubPai = $execSubCall->fetch(PDO::FETCH_ASSOC)){
			$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
			$execStatus =$PDO->query($sqlStatus) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE STATUS DOS CHAMADOS FILHOS<br>'.$sqlStatus);
			$regStatus = $execStatus->fetch(PDO::FETCH_ASSOC);
			if ($regStatus > 0) {
				$comDeps = true;
			}
		}				
		if ($comDeps) {
			$imgSub = ICONS_PATH."view_tree_red.png";
		} else {
			$imgSub = ICONS_PATH."view_tree_green.png";
		}
?>
		<tr>
            <td  colspan='4'>

                <h4 class="form-section">Sub-Chamados / Depend�ncias:</h4>

                <div id='SubCalls'>
		
                    <table class="table">

                        <tr class='header'>
                            <td>N�mero</td>
                            <td>Problema</td>
                            <td>Contato / ramal</td>
                            <td>Descric�o</td>
                            <td>�ltimo operador</td>
                            <td>Status</td>
                        </tr>
<?php
		$j=2;
		$execSubCall = $PDO->query($qrySubCall);
		while ($rowSub = $execSubCall->fetch(PDO::FETCH_ASSOC)) {
			if ($j % 2) {
					$trClass = "lin_par";
			}
			else {
					$trClass = "lin_impar";
			}
			$j++;
										
			$qryDetail = $QRY["ocorrencias_full_ini"]." WHERE  o.numero = ".$rowSub['dep_filho']." ";
			$execDetail = $PDO->query($qryDetail) or die ('ERRO NA TENTATIVA DE RECUPERAR OS DADOS DAS ocorrênciaS! '.$qryDetail);
			$rowDetail = $execDetail->fetch(PDO::FETCH_ASSOC);
            $texto = trim($rowDetail['descricao']);
            if (strlen($texto)>200){
                $texto = substr($texto,0,195)." ..... ";
            }
?>
                        <tr class="<?php echo $trClass ?>" id='linha<?php echo $j ?>' onMouseOver="destaca('linha<?php echo $j?>')" onMouseOut="libera('linha<?php echo $j ?>')"  onMouseDown="marca('linha<?php echo $j ?>')">
                            <td><a onClick="javascript: popup_alerta('mostra_consulta.php?popup=true&numero=<?php echo $rowDetail['numero']?>')"><b><?php echo $rowDetail['numero']?></b></a></td>
                            <td><?php echo $rowDetail['problema'] ?></td>
                            <td><b><?php echo $rowDetail['contato'] ?></b> / <?php echo $rowDetail['telefone'] ?></td>
                            <td><?php echo $texto?></td>
                            <td><b><?php echo $rowDetail['nome']?></b></td>
                            <td><?php echo $rowDetail['chamado_status']?></td>
                        </tr>
<?php	} ?>

                    </table>

                </div>
            </td>
        </tr>

<?php  } ?>


</table>

    </div>
        <script type="text/javascript">
            desabilitaLinks(<?print $s_ocomon;?>);

            function invertView(id) {
                var element = document.getElementById(id);
                var elementImg = document.getElementById('img'+id);
                var address = '../../includes/icons/';

                if (element.style.display=='none'){
                    element.style.display='';
                    elementImg.src = address+'close.png';
                } else {
                    element.style.display='none';
                    elementImg.src = address+'open.png';
                }
            }
        </script>

</body>
</html>