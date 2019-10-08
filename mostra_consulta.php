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

    include ("includes/classes/headers.class.php");
    include ("includes/classes/conecta.class.php");
    include ("includes/classes/auth.class.php");
	include ("includes/classes/dateOpers.class.php");
	include ("includes/var_sessao.php");
    include ("includes/functions/funcoes.inc");
    include ("includes/javascript/funcoes.js");
	//include ("includes/javascript/calendar1.js");
 	include ("includes/config.inc.php");
	include ("includes/versao.php");
	
	include ("includes/languages/".LANGUAGE."");
	include ("includes/menu/menu.php");
	
	include ("includes/queries/queries.php");			

	print "<link rel=stylesheet type='text/css' href='includes/css/estilos.css'>";
		$conec = new conexao;
		$PDO=$conec->conectaPDO();

	

print "<HTML><BODY bgcolor='".BODY_COLOR."'>";    
/*	$auth = new auth;
	if ($popup) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);	
	*/
	    
		$query = $QRY["ocorrencias_full_ini"]." where numero in (".$numero.") order by numero";
		$resultado = $PDO->query($query);
		$row = $resultado->fetch(PDO::FETCH_ASSOC);

        $query2 = "select a.*, u.* from assentamentos a, usuarios u where a.responsavel=u.user_id and a.ocorrencia='$numero'";
        $resultado2 = $PDO->query($query2);
        $linhas=$resultado2->rowCount();

		if ($_SESSION['s_nivel'] == 1) $linkEdita = "<td align='right'><a href='altera_dados_ocorrencia.php?numero=".$numero."'>Editar como admin</a>&nbsp;|&nbsp;</td>"; else
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
      	x = window.open(pagina,'_blank','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
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



				
<?php
	print "<BR><B>Consulta de ocorrências</B><BR>".$msgPai."</br>";

	/*
	if ($row['status_cod']!=4 && $_SESSION['s_nivel'] < 3) {
		print "<TD align='right' width='25%'><a href=encerramento.php?numero=".$row['numero'].">Encerrar ocorrência</a>&nbsp;|&nbsp;</TD>";
	}
	
	print "<TD align='right' width='25%'><a href='mostra_relatorio_individual.php?numero=".$row['numero']."' target='_blank'>Imprimir ocorrência</a>&nbsp;|&nbsp;</TD>";
	if ($_SESSION['s_nivel'] < 3)
		print "<TD align='right' width='25%'><a href='encaminhar.php?numero=".$row['numero']."'>Editar ocorrência</a>&nbsp;|&nbsp;</TD>".$linkEdita."";
	 
	if (($row['status_cod']!=2) && ($row['status_cod']!=4) && ($_SESSION['s_nivel'] < 3)) {
		print "<TD align='right' width='25%' bgcolor= BODY_COLOR><a href=atender.php?numero=".$numero.">Atender</a>&nbsp;|&nbsp;</TD>";
	 }

	print "<TD align='right' width='25%' bgcolor= BODY_COLOR><a onClick=\"javascript:popup('mostra_sla_definido.php?popup=true&numero=".$row['numero']."')\">SLA</a>&nbsp;|&nbsp;</TD>";

	if ($row['status_cod']!=4 && $_SESSION['s_nivel'] < 3) {
		print "<TD align='right' width='25%' bgcolor='".BODY_COLOR."'><a onClick=\"javascript:popup_alerta('incluir.php?popup=true".
				"&pai=".$row['numero']."&invTag=".$row['etiqueta']."&invInst=".$row['unidade_cod']."&invLoc=".$row['setor_cod']."".
				"&telefone=".$row['telefone']."')\">Abrir sub-chamado</a>&nbsp;|&nbsp;</TD>";
	}
	
	print "<TD align='right' width='25%' bgcolor='".BODY_COLOR."'><a onClick=\"javascript:popup('tempo_doc.php?popup=true".
			"&cod=".$row['numero']."')\">Tempo de documenta��o</a>&nbsp;|&nbsp;</TD>";
	
	*/
?>



<TABLE border="0"  align="center" width="100%" bgcolor=<?php print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>N�mero:</TD>
                <TD width="30%" align="left" bgcolor="white"><?php print $row['numero'];?></TD>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>�rea Respons�vel:</TD>
                <TD colspan='3' width="30%" align="left" bgcolor="white"><?php print $row['area'];?></TD>
		</TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Problema:</TD>
                <TD width="30%" align="left" bgcolor="white"><?php print $row['problema'];?></TD>
				<TD  width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Aberto por:</TD>
                <TD colspan='3' width="30%" align="left" bgcolor="white"><?php print $row['aberto_por'];?><TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor="<?php print TD_COLOR?>" valign="top">Descri��o:</TD>
                <TD colspan='5' width="80%" align="left" bgcolor="white"><?php print nl2br($row['descricao']);?></TD>
        </TR>

        <?php
        if ($linhas!=0)
        {
                $i=0;
				while ($rowAS=mysql_fetch_array($resultado2))
                {
                        ?>
                        <tr>        
								<TD align="left" bgcolor="<?php print TD_COLOR?>" valign="top">Assentamento <?php echo $i+1;?> de <?php echo $linhas;?> por <b><?php echo $rowAS['nome'];?></b> em <?php echo datab($rowAS['data']);?></TD>
                                <TD colspan='5' align="left" bgcolor="white" valign="top"><?php echo nl2br($rowAS['assentamento']);?></TD>
                        </TR>
                        <?php
					$i++;
				}
        }
        ?>

        <TR>
				<TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Unidade:</TD>
                <TD width="30%" align="left" bgcolor="white"><?php echo $row['unidade'];?></TD>
		
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Etiqueta do equipamento:</TD>
                <TD colspan='3' width="30%" align="left" bgcolor="white"><a href="" onClick="popup_alerta('../../invmon/geral/mostra_consulta_inv.php?comp_inst=<?php echo $row['unidade_cod']?>&comp_inv=<?php echo $row['etiqueta'];?>&popup=true')"><font color='blue'><u><?php echo $row['etiqueta'];?></u></font></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Contato:</TD>
                <TD width="30%" align="left" bgcolor="white"><?php echo $row['contato'];?></TD>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Ramal:</TD>
                <TD colspan='3' width="30%" align="left" bgcolor="white"><?php echo $row['telefone'];?></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Local:</TD>
                <TD width="30%" align="left" bgcolor="white"><?php echo $row['setor'];?></TD>
                <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>�ltimo operador:</TD>
                <TD colspan='3' width="30%" align="left" bgcolor="white"><?php echo $row['nome'];?></TD>
        </TR>
        <?
        if ($row['status_cod'] == 4)
        {
        ?>
                <TR>
                        <TD align="left" bgcolor=<?php echo TD_COLOR?>>Data de abertura:</TD>
                        <TD align="left" bgcolor="white"><?php echo datab($row['data_abertura']);?></TD>
                        <TD align="left" bgcolor=<?php echo TD_COLOR?>>Data de encerramento:</TD>
                        <TD align="left" bgcolor="white"><?php echo datab($row['data_fechamento']);?></TD>
                        <TD align="left" bgcolor=<?php echo TD_COLOR?>>Status:</TD>
                        <TD align="left" bgcolor="white"><font color='blue'><u><a  onClick="popup_alerta_mini('mostra_hist_status.php?numero=<?php echo $numero;?>&popup=true')"><?php echo $row['chamado_status'];?></u></a></font></TD>
                </TR>
        <?
        }
        else
        {
                ?>
                <TR>
                        <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Data de abertura:</TD>
                        <TD width="30%" align="left" bgcolor="white"><?php echo datab($row['data_abertura']);?></TD>
                        <TD width="20%" align="left" bgcolor=<?php echo TD_COLOR?>>Status:</TD>
                        <TD colspan='3' width="30%" align="left" bgcolor="white"><b><font color='blue'><u><?php echo $row['chamado_status'];?></u></font></b></TD>
                </TR>

                
                <?
        }
        
        
	$qryTela = "select * from imagens where img_oco = ".$row['numero']."";
	$execTela = mysql_query($qryTela) or die ("N�O FOI POSS�VEL RECUPERAR AS INFORMA��ES DA TABELA DE IMAGENS!");
	//$rowTela = mysql_fetch_array($execTela);
	$isTela = mysql_num_rows($execTela);
	$cont = 0;
	while ($rowTela = mysql_fetch_array($execTela)) {
	//if ($isTela !=0) {		
		$cont++;
		print "<tr>";
	
		print "<TD  bgcolor='".TD_COLOR."' >Anexo ".$cont.":</td>";
		print "<td colspan='5' bgcolor='white'><a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$row['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela['img_nome']."</a></TD>";
		print "</tr>";
	}
        
        
        
        $qrySubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
        $execSubCall = mysql_query($qrySubCall) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE SUB-CHAMADOS!<br>'.$qrySubCall);
	$existeSub = mysql_num_rows($execSubCall);
	if ($existeSub>0) {
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
			$imgSub = ICONS_PATH."view_tree_red.png";
		} else {
			$imgSub = ICONS_PATH."view_tree_green.png";
		}
		
		print "<tr><td  colspan='6'><IMG ID='imgSubCalls' SRC='../../includes/icons/open.png' width='9' height='9' ".
				"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('SubCalls')\">&nbsp;<b><img src='".$imgSub."' width='16' height='16' title='Chamado com v�nculos'>Sub-Chamados / Depend�ncias:</b></td></tr>";//<span style=\"background:yellow\">
	
		print "<tr><td colspan='6'></td></tr>";
		print "<tr><td colspan='6'><div id='SubCalls' style='{display:none}'>"; //style='{display:none}'	//style='{padding-left:5px;}'
		
		print "<TABLE border='0' style='{padding-left:10px;}' cellpadding='5' cellspacing='0' align='left' width='100%'>";	
		print "<tr class='header'><td>N�mero<br>�rea</td><td>Problema</td><td>Contato<br>ramal</td><td>Local<br>Descric�o</td><td>�ltimo operador<br>Status</td></tr>";
		$j=2;
		$execSubCall = mysql_query($qrySubCall);
		while ($rowSub = mysql_fetch_array($execSubCall)) {
			if ($j % 2) {
					$trClass = "lin_par";
			}
			else {
					$trClass = "lin_impar";
			}
			$j++;
										
			$qryDetail = $QRY["ocorrencias_full_ini"]." WHERE  o.numero = ".$rowSub['dep_filho']." ";
			$execDetail = mysql_query($qryDetail) or die ('ERRO NA TENTATIVA DE RECUPERAR OS DADOS DAS ocorrênciaS! '.$qryDetail);		
			$rowDetail = mysql_fetch_array($execDetail);
			
			print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
			print "<TD><a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$rowDetail['numero']."')\"><b>".$rowDetail['numero']."</b></a><br>".$rowDetail['area']."</TD>";
			print "<TD>".$rowDetail['problema']."</TD>";
			print "<TD><b>".$rowDetail['contato']."</b><br>".$rowDetail['telefone']."</TD>";
			$texto = trim($rowDetail['descricao']);
			if (strlen($texto)>200){
				$texto = substr($texto,0,195)." ..... ";
			};
			print "<TD><b>".$rowDetail['setor']."</b><br>".$texto."</TD>";
			print "<TD><b>".$rowDetail['nome']."</b><br>".$rowDetail['chamado_status']."</TD>";
	
			
			
			print "</tr>";
		}
		print "</tr>";
		print "</table>";
		print "</div>";
	}
        
        
        ?>


</TABLE>
<TABLE border="0"  align="center" width="100%" bgcolor=<?php echo BODY_COLOR?>>
    <tr align='center'>
            <TD class="topo" WIDTH='50%' ALIGN='center' VALIGN='center' bgcolor='#ECECDB'><br><a href='#' onClick="javascript:window.close()">Fechar</a></TD>
    </tr>
</table>
  
</body>
</html>
