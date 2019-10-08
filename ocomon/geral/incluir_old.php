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
	
	$s_page_ocomon = "incluir.php";
	session_register("s_page_ocomon");		
	
	$imgsPath = "../../includes/imgs/";
	$hoje = date("Y-m-d H:i:s");

print "<HTML>";
print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	if ($popup) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
			
		
	$qry = $QRY["useropencall"];
	$execqry = mysql_query($qry);
	$rowconf = mysql_fetch_array($execqry);
	
	$qryarea = "SELECT * FROM sistemas where sis_id = ".$_SESSION['s_area']."";
	$execarea = mysql_query($qryarea);
	$rowarea = mysql_fetch_array($execarea);
		
	if (!$rowconf['conf_user_opencall'] and !$rowarea['sis_atende']){
		print "<script>mensagem('A abertura de chamados est� desabilitada no sistema!'); redirect('abertura.php');</script>";
	} 
		

	if ($_GET['pai']) {
		
		$sql = "select o.*, s.* from ocorrencias o, `status` s where o.`status` = s.stat_id and s.stat_painel not in (3) and o.numero = ".$_GET['pai']."";
		$execSql = mysql_query($sql) or die ('N�O FOI POSS�VEL ACESSAR AS INFORMA��ES DA ocorrência PAI!');
		$ocoOK = mysql_num_rows ($execSql);
		if ($ocoOK != 0) {
			$subCallMsg = "<font color='red'>Essa ocorrência ser� um sub-chamado da ocorrência ".$_GET['pai']."</font>";
		} else {
			//$subCallMsg = "<font color='red'>A ocorrencia ".$_GET['pai']." n�o pode possuir subchamados pois n�o est� aberta no sistema!</font>";
			print "<script>mensagem('A ocorrencia ".$_GET['pai']." n�o pode possuir subchamados pois n�o est� aberta no sistema!'); window.close();</script>";
			exit;
		}
	
	} else $subCallMsg = "";


print "<BR><B>Abertura de ocorrências: ".$subCallMsg."</B><BR>";
print "<FORM name='form1' method='POST' action='".$PHP_SELF."'  ENCTYPE='multipart/form-data'  onSubmit=\"return valida()\">";
print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";

	
	if (isset($carrega)){
		$sqlTag = "select c.*, l.* from equipamentos c, localizacao l where c.comp_local=l.loc_id and c.comp_inv=".$equipamento." and c.comp_inst=".$instituicao."";
		$execTag = mysql_query($sqlTag);
		$rowTag = mysql_fetch_array($execTag);
		
		$invTag = $rowTag['comp_inv'];
		$invInst = $rowTag['comp_inst'];
		$invLoc = $rowTag['comp_local'];
	}												
		print "<TR>";
		
		if ($rowconf['conf_scr_area'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">�rea Respons�vel:</TD>";
        	print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";			
			print "<SELECT class='select' name='sistema' id='idSistema' size=1 ";
			
			if ($rowconf['conf_scr_prob'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
				print "onChange=\"fillSelectFromArray(this.form.problema, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));\"";
			}
			print ">";
    
            $query = "SELECT * from sistemas where sis_status NOT in (0) and sis_atende = 1 order by sistema"; //NOT in (0) = INATIVO 
			$resultado = mysql_query($query);
            print "<option value=-1 selected>-  Selecione a �rea -</option>";

                while ($rowArea=mysql_fetch_array($resultado)){	
					print "<option value='".$rowArea['sis_id']."'";
						if ($rowArea['sis_id']==$sistema) print " selected";
					print ">".$rowArea['sistema']."</option>";
				}
            print "</select>"; 
			print "</td>";   
		} else  $sistema = $rowconf['conf_opentoarea'];  //$sistema = -1;
			
                
		if ($rowconf['conf_scr_prob'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
            print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Problema:</TD>";
            print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";
            print "<SELECT class='select' name='problema' id='idProblema' size=1>";
            print "<option value=-1 selected>-  Selecione o problema -</option>";
                $query = "SELECT * from problemas order by problema";
                $resultado = mysql_query($query);
                while ($rowProb = mysql_fetch_array($resultado))
                {
					print "<option value='".$rowProb['prob_id']."'";
						if ($rowProb['prob_id']== $problema) print " selected";
					print ">".$rowProb['problema']."</option>";
                }
                
           	print "</SELECT>";
            print "</TD>";
		} else $problema = -1;
		print "</TR>";		
		print "<TR>";
		
		if ($rowconf['conf_scr_desc'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
            print "<TD width='20%' align='left' bgcolor=".TD_COLOR." valign='top'>Descri��o do problema:</TD>";
            print "<TD colspan='3' align='left' bgcolor=".BODY_COLOR.">";
			print "<TEXTAREA class='textarea' name='descricao' id='idDescricao'>".noHtml($descricao)."</textarea>";
			print "</td>";
			
		} else $descricao = "Sem descri��o";
		print "</tr>";
		print "<TR>";
		if ($rowconf['conf_scr_unit'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
	   		
			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Unidade:</TD>";
            print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";
			print "<SELECT class='select' name='instituicao' id='idUnidade' size=1>";
			print "<option value=null selected>Selecione a unidade</option>";
                
				$query2 = "SELECT * from instituicao WHERE inst_status not in (0) order by inst_cod";
                $resultado2 = mysql_query($query2);
                $linhas = mysql_numrows($resultado2);
                while ($rowInst = mysql_fetch_array($resultado2))
                {
                       print "<option value=".$rowInst['inst_cod']."";
						if ($rowInst['inst_cod']== $invInst) print " selected";
					   print ">".$rowInst['inst_nome']."</option>";
                }
                
            print "</SELECT>";
			print "</td>";	
		} else $instituicao = -1;
                
		if ($rowconf['conf_scr_tag'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">"; 
			if ($rowconf['conf_scr_chktag'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
				print "<b><a onClick=\"checa_etiqueta()\" title='Consulta a configura��o do equipamento!'><font color='#5E515B'>";
			} 
			print "Etiqueta";
			if ($rowconf['conf_scr_chktag'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
				print "</font></a></b>";
			}
			print " do equipamento:</TD>";
            print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text2' name='equipamento' id='idEtiqueta' value='".$invTag."'>";//
			if ($rowconf['conf_scr_chkhist'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
				print "<a onClick=\"checa_chamados()\" title='Consulta outros chamados desse equipamento!'><font color='#5E515B'><b>Hist�rico</b></font></a>";
			}
			print "</TD>";
		} else $equipamento = null;
			
		print "</tr>";	
       
        print "<TR>";
		if ($rowconf['conf_scr_contact'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Contato:</TD>";
            print "<TD width='30%' align='left' bgcolor=".BODY_COLOR."><INPUT type='text' class='text' name='contato' id='idContato' value='".$contato."'></TD>";
		} else {
			$qry = "select nome from usuarios where user_id = ".$_SESSION['s_uid']."";
			$exec = mysql_query($qry);
			$r_user = mysql_fetch_array($exec);
			$contato = $r_user['nome'];
		}            
		if ($rowconf['conf_scr_fone'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Ramal:</TD>";
            print "<TD width='30%' align='left' bgcolor=".BODY_COLOR."><INPUT type='text' class='text2' name='telefone' id='idTelefone' value='".$telefone."'></TD>";
        } else $telefone = null;
		print "</TR>";
        
		print "<TR>";
                
		if ($rowconf['conf_scr_local'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Local: ";
				if ($rowconf['conf_scr_btloadlocal'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
					print "<input type='submit' class='btPadrao' id='idBtCarrega' title='Carrega o local desse equipamento!' ".
						"style=\"{align:center; valign:middle; width:19px; height:19px; background-image: url('../../includes/icons/reload3.png'); background-repeat:no-repeat;}\" value='' name='carrega'>";
				}
			print "</TD>";
		
                
				//<!--{ background-image: url('/images/css.gif');} -->
			print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";

            print "<SELECT class='select' name='local' id='idLocal' size=1>";
            print "<option value=-1 selected>-  Selecione um local -</option>";
				$query ="SELECT l .  * , r.reit_nome, pr.prior_nivel AS prioridade, d.dom_desc AS dominio, pred.pred_desc as predio
						FROM localizacao AS l
						LEFT  JOIN reitorias AS r ON r.reit_cod = l.loc_reitoria
						LEFT  JOIN prioridades AS pr ON pr.prior_cod = l.loc_prior
						LEFT  JOIN dominios AS d ON d.dom_cod = l.loc_dominio
						LEFT JOIN predios as pred on pred.pred_cod = l.loc_predio
						WHERE loc_status not in (0) 
						ORDER  BY LOCAL ";
				$resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                while ($rowi = mysql_fetch_array($resultado))
                {
					print "<option value='".$rowi['loc_id']."'";
						if ($rowi['loc_id'] == $invLoc) print " selected";
					print ">".$rowi['local']." - ".$rowi['predio']."</option>";
                }
               
				if ($rowconf['conf_scr_searchbylocal'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
					print "</SELECT><a onClick=\"checa_por_local()\"><img title='Consulta os equipamentos cadastrados para esse local!' width='15' height='15' src='".$imgsPath."consulta.gif' border='0'></a>";
				}
                print "</TD>";
			} else $local = -1;
			
			if ($rowconf['conf_scr_operator'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
               	print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Operador:</TD>";
                print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">".$s_usuario."</TD>";
			} else $operador = $s_usuario;
        	print "</TR>";
        	print "<TR>";
            
			if ($rowconf['conf_scr_date'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
				print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Data de abertura:</TD>";
                print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">".datab($hoje)."</TD>";
			}
			if ($rowconf['conf_scr_status'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
				print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Status:</TD>";
                print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">Aguardando atendimento</TD>";
			}
        	print "</TR>";
        	print "<TR>";
        
			if ($rowconf['conf_scr_upload'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
				print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Anexar imagem:</TD>";
				print "<TD width='30%' align='left' bgcolor=".BODY_COLOR."><INPUT type='file' class='text' name='img' id='idImg'></TD>";
			}
        
			if ($rowconf['conf_scr_replicate'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
				print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Replicar este chamado mais</TD>";
       			print "<TD  bgcolor=".BODY_COLOR."><INPUT type='text' class='mini' name='replicar' id='idReplicar' value='0' maxlength='2'> vezes.</TD> ";
			} else $replicar = 0;				
        
        	print "</TR>";
        
			print "<tr>";
			/* if ($rowconf['conf_scr_mail'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
				print "<td bgcolor='".TD_COLOR."'>Enviar e-mail para:</td>".
					"<td colspan='2'><input type='checkbox' value='ok' name='mailAR' checked>�rea Respons�vel&nbsp;&nbsp;".
									"<input type='checkbox' value='ok' name='mailUS' disabled>Usu�rio</td>";
			
			}
			*/					
			print "</tr>";


			if (!empty($invTag)){
				$saida = "javascript:window.close()";
			} else
				$saida = "javascript:location.href='abertura.php'";
		
		
		
		print "<TR>";
        print "<BR>";
        
	if ($_GET['pai']) {        
        	print "<input type='hidden' name='pai' value='".$_GET['pai']."'>";
        }
        
        print "<input type='hidden' name='data_gravada' value='".date("Y-m-d H:i:s")."'>";
        
        print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'><input type='submit' value='OK' name='OK'>";
        print "</TD>";
        print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'><INPUT type='button' value='Cancelar' name='desloca' OnClick=".$saida."></TD>";
        print "</TR>";

		$aviso="";
		if ($OK=="OK") {
					

			$queryB = "SELECT sis_id,sistema, sis_email FROM sistemas WHERE sis_id = ".$sistema."";
			$sis_idB = mysql_query($queryB);
			$rowSis = mysql_fetch_array($sis_idB);

			if ($rowconf['conf_scr_local'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
				$queryC = "SELECT local from localizacao where loc_id = $local";
				$loc_idC = mysql_query($queryC);
				$setor = mysql_result($loc_idC,0);
			}
				
			$queryD = "SELECT u.*,a.* from usuarios u, sistemas a where u.AREA = a.sis_id and user_id=".$_SESSION['s_uid']."";
			$loginD = mysql_query($queryD);
			$rowqryD = mysql_fetch_array($loginD);
			$nome = $rowqryD['nome'];

			//showArray($_REQUEST);
			//showArray($_FILES);
						
			//exit;			
			
			
			if (isset($_FILES['img']) and $_FILES['img']['name']!="") {
				$qryConf = "SELECT * FROM config";
				$execConf = mysql_query($qryConf) or die ("N�O FOI POSS�VEL ACESSAR AS INFORMA��ES DE CONFIGURA��O, A TABELA CONF FOI CRIADA?");
				$rowConf = mysql_fetch_array($execConf);
				$arrayConf = array();
				$arrayConf = montaArray($execConf,$rowConf);
				
				$upld = upload('img',$arrayConf);	
				if ($upld =="OK") {
					$gravaImg = true;
				} else { 
					$upld.="<br><a align='center' onClick=\"exibeEscondeImg('idAlerta');\"><img src='".ICONS_PATH."/stop.png' width='16px' height='16px'>&nbsp;Fechar</a>";
					print "</table>";
					print "<div class='alerta' id='idAlerta'><table bgcolor='#999999'><tr><td colspan='2' bgcolor='yellow'>".$upld."</td></tr></table></div>";
					exit;
				}
			}
			
			
			$data = $hoje;
			$i = 0;
			while ($i<=$replicar)
			{
					$query = "INSERT INTO ocorrencias (problema, descricao, instituicao, equipamento, sistema, contato, telefone, local, operador, data_abertura, data_fechamento, status, data_atendimento, aberto_por ) values ($problema,'".noHtml($descricao)."',$instituicao,'$equipamento',$sistema,'".noHtml($contato)."','$telefone',$local,$s_uid,'$data',NULL,1,NULL,$s_uid)";
					$resultado = mysql_query($query) or die ("ERRO NA TENTATIVA DE INCLUIR A ocorrência NO SISTEMA!");
					
					$numero = mysql_insert_id();
					
					//INSER��O PARA ARMAZENAR O TEMPO DO CHAMADO EM CADA STATUS
					$sql = " insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values ($numero, 1, 0, '$data')  ";
					$exec_sql = mysql_query($sql);
					if ($exec_sql == 0) $error = " erro na tabela TEMPO_STATUS ";
					
					$i++;
			}
		
			if ($resultado == 0) {
				$aviso.= "ERRO na inclus�o dos dados.".$query;
			} else {
				//$numero = mysql_insert_id();
				
				$sqlDoc = "insert into doc_time (doc_oco, doc_open, doc_edit, doc_close, doc_user) values (".$numero.",".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s")).", 0, 0, ".$_SESSION['s_uid'].")";
				$execDoc = mysql_query($sqlDoc) or die ('ERRO NA TENTATIVA DE ATUALIZAR O TEMPO DE DOCUMENTA��O DO CHAMADO!!<br>').$sqlDoc;
					
				
				
				
				
				
				
				if ($_POST['pai']) {
					$sqlDep = "insert into ocodeps (dep_pai, dep_filho) values (".$_POST['pai'].", ".$numero.")";
					$execDep = mysql_query($sqlDep) or die ('ERRO NA VINCULA��O DA SUB-ocorrência!<br>'.$sqlDep);
					if ($execDep == 0) $aviso.= "N�o foi poss�vel vincular a ocorrência como depend�ncia!";
				}
				
				
				if ($gravaImg) {
					//INSER��O DA IMAGEM NO BANCO
					$fileinput=$_FILES['img']['tmp_name'];
					$tamanho = getimagesize($fileinput);
					
					if(chop($fileinput)!=""){
						// $fileinput should point to a temp file on the server
						// which contains the uploaded image. so we will prepare
						// the file for upload with addslashes and form an sql
						// statement to do the load into the database.
						$image = addslashes(fread(fopen($fileinput,"r"), 1000000));
						$SQL = "Insert Into imagens (img_nome, img_oco, img_tipo, img_bin, img_largura, img_altura) values ".
								"('".$_FILES['img']['name']."',".$numero.", '".$_FILES['img']['type']."', '".$image."', ".$tamanho[0].", ".$tamanho[1].")";
						// now we can delete the temp file
						unlink($fileinput);
					} /*else {
						echo "NENHUMA IMAGEM FOI SELECIONADA!";
						exit;
					}*/
					$exec = mysql_query($SQL); //or die ("N�O FOI POSS�VEL GRAVAR O ARQUIVO NO BANCO DE DADOS! ");
					if ($exec == 0) $aviso.= "N�O FOI POSS�VEL ANEXAR A IMAGEM!<br>";
					
				}				
				
				
				$qryfull = $QRY["ocorrencias_full_ini"]." WHERE o.numero = ".$numero."";
				$execfull = mysql_query($qryfull) or die('O CHAMADO FOI ABERTO NO SISTEMA POR�M N�O FOI POSS�VEL RECUPERAR AS VARI�VEIS DE AMBIENTE!'.$qryfull);
				$rowfull = mysql_fetch_array($execfull);
				
				$VARS = array();
				$VARS['%numero%'] = $rowfull['numero'];
				$VARS['%usuario%'] = $rowfull['contato'];
				$VARS['%contato%'] = $rowfull['contato'];
				$VARS['%descricao%'] = $rowfull['descricao'];
				$VARS['%setor%'] = $rowfull['setor'];
				$VARS['%ramal%'] = $rowfull['telefone'];
				$VARS['%assentamento%'] = $rowfull['descricao'];
				$VARS['%site%'] = "<a href='".OCOMON_SITE."'>".OCOMON_SITE."</a>";
				$VARS['%area%'] = $rowfull['area'];
				$VARS['%operador%'] = $rowfull['nome'];
				$VARS['%editor%'] = $rowfull['nome'];
				$VARS['%problema%'] = $rowfull['problema'];
				$VARS['%solucao%'] = '';
				$VARS['%versao%'] = VERSAO;
				
				$qryconfmail = "SELECT * FROM mailconfig";
				$execconfmail = mysql_query($qryconfmail) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE ENVIO DE E-MAIL!');
				$rowconfmail = mysql_fetch_array($execconfmail);
				
				
				if ($mailAR || isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
					//$flag = envia_email($numero, $rowSis['sis_email'], $descricao, $contato, $setor, $telefone, $nome, $rowSis['sistema'], OCOMON_SITE);
					//$flag = send_email('abertura-para-area',$rowSis['sis_email']);
					$event = 'abertura-para-area';
					$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
					$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
					$rowmsg = mysql_fetch_array($execmsg);
					
					//send_mail($event, $rowSis['sis_email'], $rowconfmail, $rowmsg, $VARS);
				}	
					
														
				$aviso.= "ocorrência incluida com sucesso!". 
							"N�mero: <font color=red>".$numero."</font><BR>".
							"<a href='atender.php?numero=".$numero."'>Atender</a><br>".
							"<a href='encaminhar.php?numero=".$numero."'>Encaminhar</a><br>".
							"<a href='encerramento.php?numero=".$numero."'>Encerrar</a>";
				$i = 0;
				while ($i<=$replicar)
				{
					//INSER��O PARA ARMAZENAR O TEMPO DO CHAMADO EM CADA STATUS
					$sql = " insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values ($numero, 1, 0, '$data')  ";
					$exec_sql = mysql_query($sql);
					if ($exec_sql == 0) $error = " erro na tabela TEMPO_STATUS ";
					$i++;
				}
			}

	
			if ($rowqryD['sis_atende']==1){
				$origem = "abertura.php";
				session_register("aviso");
				session_register("origem");
				//echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=mensagem.php\">";
				
				if ($_POST['pai']) {
					print "<script>mensagem('OK, Chamado aberto com sucesso! N�mero ".$numero."'); window.opener.location.reload(); window.close();</script>";
				} else				
					print "<script>redirect('mensagem.php')</script>";
			
			
			} else {
				$qrymail = "SELECT * FROM usuarios WHERE user_id = ".$_SESSION['s_uid']."";
				$execmail = mysql_query($qrymail) or die('CHAMADO ABERTO COM SUCESSO! POR�M N�O � POSS�VEL ENVIAR EMAIL"');
				$rowmail = mysql_fetch_array($execmail);
				//ENVIA E-MAIL PARA O PR�PRIO USU�RIO QUE ABRIU O CHAMADO
				
				//$flag = mail_user($rowmail['email'],$rowconf['sis_email'],$rowmail['nome'],$numero,OCOMON_SITE);
				$event = 'abertura-para-usuario';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
				$rowmsg = mysql_fetch_array($execmsg);
				
				//ENVIA E-MAIL PARA O PR�PRIO USU�RIO QUE ABRIU O CHAMADO
				//send_mail($event, $rowSis['sis_email'], $rowconfmail, $rowmsg, $VARS);
				//send_mail($event, $rowmail['email'], $rowconfmail, $rowmsg, $VARS);
				
				$mensagem = str_replace("%numero%",$numero,$rowconf['conf_scr_msg']);
				print "<script>mensagem('".$mensagem."'); redirect('abertura_user.php');</script>";	
			}
		
/*				if (isset($_FILES['img'])) {
				
					if (upload('img')) {
						//INSER��O DA IMAGEM NO BANCO
						$fileinput=$_FILES['img']['tmp_name'];
						$tamanho = getimagesize($fileinput);
						
						if(chop($fileinput)!=""){
							// $fileinput should point to a temp file on the server
							// which contains the uploaded image. so we will prepare
							// the file for upload with addslashes and form an sql
							// statement to do the load into the database.
							$image = addslashes(fread(fopen($fileinput,"r"), 1000000));
							$SQL = "Insert Into imagens (img_nome, img_oco, img_tipo, img_bin, img_largura, img_altura) values ".
									"('".$_FILES['img']['name']."',".$numero." , '".$_FILES['img']['type']."', '".$image."', ".$tamanho[0].", ".$tamanho[1].")";
							// now we can delete the temp file
							unlink($fileinput);
						}
						else{
							echo "NENHUMA IMAGEM FOI SELECIONADA!";
							exit;
						}
						$exec = mysql_query($SQL) or die ("N�O FOI POSS�VEL GRAVAR O ARQUIVO NO BANCO DE DADOS!");
						echo "ARQUIVO GRAVADO COM SUCESSO";
					}	
				}*/
		
		}
        
		$qrylogado = "SELECT sis_atende FROM sistemas where sis_id = ".$_SESSION['s_area'].""; 
		$execlogado = mysql_query($qrylogado) or die('N�O FOI POSS�VEL ACESSAR A BASE DE USU�RIOS');
		$rowlogado = mysql_fetch_array($execlogado);
		?>

        
        
<script type="text/javascript">
<!--			
	
	function valida(){
		var ok = false;
		var operador = <?print $rowlogado['sis_atende']?>;
		var unit = document.getElementById('idUnidade');
		var tag = document.getElementById('idEtiqueta');
		//var carreg = '<?//print $carrega?>';
		if (unit != null){
			if (operador == 0){
				var ok = validaForm('idUnidade','COMBO','Unidade',1);
			} else ok = true;
		} else ok = true;
		
		if (ok) {	
			if (tag != null){
				if (operador == 1){
					var ok = validaForm('idEtiqueta','INTEIRO','Etiqueta',0);
				} else {
					var ok = validaForm('idEtiqueta','INTEIRO','Etiqueta',1);
				}
			} else ok = true;
		} 
		if (ok){
			var fone = document.getElementById('idTelefone');
			//if (carreg){
			if (fone != null){
				var ok = validaForm('idTelefone','INTEIRO','ramal',1);
			} else ok = true;
			//}
		}
		if (ok){
		 	var replicate = document.getElementById('idReplicar');
		 	if (replicate != null){
		 		var ok = validaForm('idReplicar','INTEIROFULL','replicar',0);
			} else ok = true;
		}
		
		return ok;
	
	}		
	
	
	
	
	team = new Array(
<?
$sql="select * from sistemas where sis_status NOT in (0) and sis_atende = 1 order by sistema";//Somente as �reas ativas
$sql_result=mysql_query($sql);
echo mysql_error();
$num=mysql_numrows($sql_result);
while ($row_A=mysql_fetch_array($sql_result)){
$conta=$conta+1;
	$cod_item=$row_A["sis_id"];
		echo "new Array(\n";
		$sub_sql="select * from problemas p left join sistemas s on p.prob_area = s.sis_id where prob_area='$cod_item' or prob_area is null order by problema";
		$sub_result=mysql_query($sub_sql);
		$num_sub=mysql_numrows($sub_result);
		if ($num_sub>=1){
			echo "new Array(\"- Selecione o problema -\", -1),\n";
			while ($rowx=mysql_fetch_array($sub_result)){
				$codigo_sub=$rowx["prob_id"];
				$sub_nome=$rowx["problema"];
			$conta_sub=$conta_sub+1;
				if ($conta_sub==$num_sub){
					echo "new Array(\"$sub_nome\", $codigo_sub)\n";
					$conta_sub="";
				}else{
					echo "new Array(\"$sub_nome\", $codigo_sub),\n";
				}
			}
		}else{
			echo "new Array(\"Qualquer\", -1)\n";
		}
	if ($num>$conta){
		echo "),\n";
	}
}
echo ")\n";
echo ");\n";
?>

function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
	var i, j;
	var prompt;
	// empty existing items
	for (i = selectCtrl.options.length; i >= 0; i--) {
		selectCtrl.options[i] = null; 
	}
	prompt = (itemArray != null) ? goodPrompt : badPrompt;
	if (prompt == null) {
		j = 0;
	}
	else {
		selectCtrl.options[0] = new Option(prompt);
		j = 1;
	}
	if (itemArray != null) {
		// add new items
		for (i = 0; i < itemArray.length; i++) {
			selectCtrl.options[j] = new Option(itemArray[i][0]);
			if (itemArray[i][1] != null) {
				selectCtrl.options[j].value = itemArray[i][1]; 
			}
			j++;
		}
	// select first item (prompt) for sub list
	selectCtrl.options[0].selected = true;
   }
}


	 function popup_alerta(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'Alerta','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);	  	
		return false
     }

	 
	 function checa_etiqueta(){
	 	var inst = document.getElementById('idUnidade');
		var inv = document.getElementById('idEtiqueta');
		if (inst != null && inv != null){
			if (inst.value=='null' || !inv.value){
			window.alert('Os campos Unidade e etiqueta devem ser preenchidos!');
			} else
			popup_alerta('../../invmon/geral/mostra_consulta_inv.php?comp_inst='+inst.value+'&comp_inv='+inv.value+'&popup='+true);
		}
		return false;
	 }	 
	 
	
	function checa_chamados(){
	 	var inst = document.getElementById('idUnidade');
		var inv = document.getElementById('idEtiqueta');
		if (inst != null && inv != null){		
			if (inst.value=='null' || !inv.value){
				window.alert('Os campos Unidade e etiqueta devem ser preenchidos!');
			} else
			popup_alerta('../../invmon/geral/ocorrencias.php?comp_inst='+inst.value+'&comp_inv='+inv.value+'&popup='+true);
		}
		return false;
	}	
	
	 function checa_por_local(){
	 	//var local = document.form1.local.value;
		var local = document.getElementById('idLocal');
		if (local != null) {
			if (local.value==-1){
				window.alert('O local deve ser preenchido!');
			} else
				popup_alerta('../../invmon/geral/mostra_consulta_comp.php?comp_local='+local.value+'&popup='+true);
		}
		return false;
	 }


	
	
	function desabilita(v)
	{
		document.form1.OK.disabled=v;
	}
 
 	function desabilitaCarrega(v){
		//document.form1.carrega.disabled=v;
		var btLoad = document.getElementById('idBtCarrega');
		if (btLoad != null){
			btLoad.disabled = v;
		}
	}
 
	
	function Habilitar(){
		var descricao = document.getElementById('idDescricao');
		var ramal = document.getElementById('idTelefone');
		var contato = document.getElementById('idContato');
		var sel_area = document.getElementById('idSistema');
		var sel_problema = document.getElementById('idProblema');
		var sel_local = document.getElementById('idLocal');
		
		var ok = false;
		if (descricao != null){
			if (descricao.value == "" ) {ok = true;} 
		}
		if (sel_area != null){
			if (sel_area.value ==-1) { ok = true;}
		}
		if (sel_problema != null){
			if (sel_problema.value ==-1) { ok = true;}
		}		
		if (sel_local != null){
			if (sel_local.value ==-1) { ok = true;}
		}
		if (ramal != null){
			if (ramal.value =="") { ok = true;}
		}
		if (contato != null){
			if (contato.value =="") {ok = true;}
		}						
		if (ok)
		{
			desabilita(true);
		
		} else {
			desabilita(false);
		}
	}	
	
	function HabilitarCarrega(){
		var sel_inst = document.getElementById('idUnidade');
		var etiqueta = document.getElementById('idEtiqueta');
		
		if (sel_inst != null && etiqueta != null){		
			if ((sel_inst.value=="null")||(etiqueta.value=="")) {
				desabilitaCarrega(true);
			} else{
				desabilitaCarrega(false);
			}
		}
	}	
	
	
	window.setInterval("Habilitar()",100);
	window.setInterval("HabilitarCarrega()",100);
//-->
</script>
</TABLE>

</FORM>

</body>
</html>
