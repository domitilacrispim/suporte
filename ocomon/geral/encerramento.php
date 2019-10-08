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

	
	$imgsPath = "../../includes/imgs/";
	$hoje = date("Y-m-d H:i:s");
    $hoje2 = date("d/m/Y");
?>
</head>
<body>
<div class="container-fluid">
<?php
	$auth = new auth;
	if ($popup) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);	

	$sqlSub = "select * from ocodeps where dep_pai = ".$_GET['numero']." ";

	$execSub = mysql_query ($sqlSub) or die ('N�O FOI POSS�VEL RECUPERAR AS INFORMA��ES DE DEPEND�NCIAS DO CHAMADO!'.$sqlSub);
	$deps = array();
	while ($rowSub = mysql_fetch_array($execSub)) {
		
		$sqlStatus = "select o.*, s.*  from ocorrencias as o, `status` as s where o.numero = ".$rowSub['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3)  ";
		$execStatus = mysql_query($sqlStatus) or die ('N�O FOI POSS�VEL ACESSAR A LISTAGEM DE CHAMADOS FILHOS!'.$sqlStatus);
		$achou = mysql_num_rows ($execStatus);
		if ($achou > 0) {
			$deps[] = $rowSub['dep_filho'];	
		}
	
	}
		
	if(sizeof($deps)) {
		$saida = "ALERTA: Essa ocorrência n�o pode ser encerrada pois possui as seguintes depend�ncias:</b><br><br>";
		foreach($deps as $err) {
			$saida.="Chamado <a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$err."')\"><font color='blue'>".$err."</font></a><br>";
		}
		$saida.="<br><a align='center' onClick=\"redirect('mostra_consulta.php?numero=".$_GET['numero']."');\"><img src='".ICONS_PATH."/back.png' width='16px' height='16px'>&nbsp;Voltar</a>";
		print "</table>";
		print "<div class='alerta' id='idAlerta'>
		<table bgcolor='#999999'><tr><td colspan='2' bgcolor='yellow'>".$saida."</td></tr></table>
		</div>";
		exit;
	}
		
    $query = $QRY["ocorrencias_full_ini"]." where numero in (".$_GET['numero'].") order by numero";
	$resultado = mysql_query($query);
	$rowABS = mysql_fetch_array($resultado);
		
	$atendimento = mysql_result($resultado,0,12);
        if (mysql_numrows($resultado)>0)
        {
                $linhas = mysql_numrows($resultado)-1;
        }
        else
        {
                $linhas = mysql_numrows($resultado);
        }

        $query2 = "select a.*, u.* from assentamentos as a, usuarios as u where a.responsavel = u.user_id and ocorrencia='".$_GET['numero']."'";
        $resultado2 = mysql_query($query2);
        $linhas2 = mysql_numrows($resultado2);
		
?>

    <div class="panel panel-danger">
        <div class="panel-heading">
            <h4 class="panel-title">Encerramento de ocorrências</h4>
        </div>


<form method="POST" action="" name="form1" onSubmit="return valida()">

    <div class="panel-body">
        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label>N�mero:</label>
                    <div class="form-control"><?php echo $rowABS['numero'];?></div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label>�rea respons�vel:</label>
                    <div class="form-control"><?php echo $rowABS['area'];?></div>
                </div>
            </div>
            <?php
            $qryinst = "select * from instituicao where  sistema=".$rowABS['area_cod']." order by inst_nome";
            $exec_inst = mysql_query($qryinst);

            ?>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="idInstituicao">Tipo:</label>
                    <select class='form-control' name='inst'  id="idInstituicao">
                        <option value=-1>Selecione a unidade</option>
                        <?php
                        while($row=mysql_fetch_array($exec_inst)){
                            $selected = ($row['inst_cod']== $rowABS['unidade_cod']) ? ' selected' : '';
                            ?>
                            <option value="<?php echo $row['inst_cod']?>" <?php echo $selected ?>><?php echo $row['inst_nome']?></option>
                            <?php
                        } // while
                        ?>
                    </select>
                </div>
            </div>

            <?php
            //$problemas = mysql_result($resultado,0,1);
            $query_problema = "SELECT * FROM problemas WHERE prob_status = 1 and prob_area = ".$rowABS['area_cod']." ORDER BY problema";
            $exec_problema = mysql_query($query_problema);
            ?>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="idProb">Problema:</label>
                    <select class="form-control" name="prob" id="idProb">
                        <option value=-1>Selecione o problema</option>
                        <?php
                        while($row=mysql_fetch_array($exec_problema)){
                            $selected = ($row['prob_id']== $rowABS['prob_cod']) ? ' selected' : '';
                            ?>
                            <option value="<?php echo $row['prob_id']?>" <?php echo $selected?>><?php echo $row['problema'] ?></option>
                            <?php
                        } // while
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group">
                    <label><a onClick="checa_etiqueta()" title='Consulta a configura��o do equipamento!'><b>Etiqueta</b></a> equip.:</label>
                    <input type="text" class='form-control' name="etiqueta" id="idEtiqueta" value="<?php echo ($rowABS['etiqueta'] != 0) ? $rowABS['etiqueta'] : '';?>">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="idContato">Contato:</label>
                    <input type="text" class='form-control' name="contato" id="idContato" value="<?php echo $rowABS['contato'];?>">
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group">
                    <label>Telefone:</label>
                    <div class='form-control'><?php echo $rowABS['telefone'];?></div>
                </div>
            </div>

            <div class="col-sm-5">
                <div class="form-group">
                    <label for="idLocal">Local:</label>
                    <button onClick="checa_por_local()" style="float: right" type="button" class="btn btn-warning btn-xs" title='Consulta os equipamentos cadastrados para esse local!'>
                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    </button>
                    <select class='form-control' name="loc" id='idLocal'>
                        <option value=-1>Selecione o local</option>
                        <?php
                        $qrylocal = "select * from localizacao where loc_status not in (0) order by local";
                        $exec_local = mysql_query($qrylocal);
                        while($row=mysql_fetch_array($exec_local)){
                            $selected = ($row['loc_id']== $rowABS['setor_cod']) ?' selected' : '';
                        ?>
                            <option value="<?php echo $row['loc_id']?>" <?php echo $selected?>><?php echo $row['local']?></option>
                        <?php
                        } // while
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label>Operador:</label>
                    <div class='form-control'><?php echo $rowABS['nome'];?></div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label>Data de abertura:</label>
                    <div class='form-control'><?php echo datab($rowABS['data_abertura']);?></div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label>Status:</label>
                    <div class='form-control'><?php echo $rowABS['chamado_status'];?></div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label>Data de Fechamento:</label>
                    <input type="text" class='form-control' name="data_fechamento" id="idData_fechamento" value=<?php echo datab($hoje);?>>
                </div>
            </div>



            <div class="col-sm-12">
                <div class="form-group">
                    <label>Descri��o:</label>
                    <div class="form-control"><?php echo $rowABS['descricao'];?></div>
                </div>
            </div>
        </div>
            <?php
            if ($linhas2!=0){

                $i = ($linhas2==1) ? $i=$linhas2-1 : $i=$linhas2-2;

                while ($i < $linhas2) {
                    ?>

                    <div class="alert alert-warning" style="margin-bottom: 5px; padding: 5px">
                        <strong>
                            Assentamento <?print $i+1;?> de <?print $linhas2;?> por <?print mysql_result($resultado2,$i,7);?> em <?print datab(mysql_result($resultado2,$i,3));?>
                        </strong>
                        - <?print nl2br(mysql_result($resultado2,$i,2));?>
                    </div>
                    <?php
                    $i++;
                }
            }
            ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Problema:</label>
                    <textarea id="idProblema" name="problema" class="form-control" >Citado na descri��o</textarea>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label>Solu��o:</label>
                    <textarea class="form-control" id="idSolucao" name="solucao" placeholder="Solu��o para este problema" ></textarea>
                </div>
            </div>

            <?php

                $qrymail = "SELECT u.*, a.*,o.* from usuarios u, sistemas a, ocorrencias o where ".
                    "u.AREA = a.sis_id and o.aberto_por = u.user_id and o.numero = ".$_GET['numero']."";
                $execmail = mysql_query($qrymail);
                $rowmail = mysql_fetch_array($execmail);
                if ($rowmail['sis_atende']==0){
                    $habilita = "checked";
                } else $habilita = "disabled";

            ?>

            <div class="col-sm-3">
                <div class="form-group">
                    <p>Enviar e-mail para:</p>
                    <label class="checkbox-inline">
                        <input type="checkbox" value="ok" name="mailAR" checked title='Envia e-mail para a �rea de atendimento do chamado'>�rea respons�vel
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" value="ok" name="mailUS" <?php echo $habilita?> ><a title='Essa op��o s� fica habilitada para chamados abertos pelo pr�prio usu�rio'>Usu�rio</a>
                    </label>
                </div>
            </div>

            <input type='hidden' name='data_gravada' value='<?php echo date("Y-m-d H:i:s")?>'>
            <input type="hidden" name="rodou" value="sim">
            <input type="hidden" name="abertopor" value="<?php echo $rowmail['user_id']?>">

        </div>
    </div>


    <div class="form-actions right">

        <button type="submit" class="btn btn-success">Enviar</button>

        <button type="button" class="btn btn-default"name='desloca' OnClick="javascript:history.back()">Cancelar</button>

    </div>

</form>

</div>

        <?php

                if ($rodou == "sim")
                {		
						
						
#########################################################################################
// By Fl�vio - Helpdesk
        				$queryB = "SELECT sis_id,sistema, sis_email FROM sistemas WHERE sis_id = ".$rowABS['area_cod']."";
       					$sis_idB = mysql_query($queryB);
						//$sis_id = mysql_result($sis_idB,0);
						$rowSis = mysql_fetch_array($sis_idB);
						
						
						
						$queryC = "SELECT local from localizacao where loc_id = $loc";
						$loc_idC = mysql_query($queryC);
						$setor = mysql_result($loc_idC,0);
						
						$queryD = "SELECT nome from usuarios where login like '$s_usuario'";
						$loginD = mysql_query($queryD);
						$nome = mysql_result($loginD,0);						

						
##########################################################################################									
														
						
						
						if (($problema == "Descri��o t�cnica do problema") AND ($solucao == "Solu��o para este problema"))
                        {
                                $aviso = "ERRO. Preencha corretamente os campos \"Problema\" e \"Solu��o\".";
                                $origem = "encerramento.php";
                                session_register("aviso");
                                session_register("origem");
                                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                        }
                        $data = datam($hoje2);
                        $responsavel = $s_uid;

                        $query = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel) values ($numero,'".noHtml($problema)."', '$data', $responsavel)";
                        $resultado = mysql_query($query) or die ('ERRO AO TENTAR INCLUIR ASSENTAMENTO! '.$query);

                        $query = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel) values ($numero,'".noHtml($solucao)."', '$data', $responsavel)";
                        $resultado = mysql_query($query)or die ('ERRO AO TENTAR INCLUIR ASSENTAMENTO! '.$query);

                        $query1 = "INSERT INTO solucoes (numero, problema, solucao, data, responsavel) values ($numero, '".noHtml($problema)."','".noHtml($solucao)."', '$data', $responsavel)";
			   $query1s = "update solucoes set problema = " .noHtml($problema).", solucao= ".noHtml($solucao).", data = ".$data.", responsavael=".$responsavel." where numero = ".$numero;
                        $resultado1 = mysql_query($query1)or mysql_query($query1s);

						$status = 4; //encerrado
						// print "UPDATE ocorrencias SET status=".$status.", local=".$loc.", problema =".$prob.",operador=".$s_uid.", instituicao='".$inst."', equipamento='".$etiqueta."', contato='".noHtml($contato)."', data_fechamento='".$data."', data_atendimento='".$data."' WHERE numero='".$numero."'";

						if ($atendimento==null) {
							$query2 = "UPDATE ocorrencias SET status=".$status.", local=".$loc.", problema =".$prob.",operador=".$s_uid.", instituicao='".$inst."', equipamento='".$etiqueta."', contato='".noHtml($contato)."', data_fechamento='".$data."', data_atendimento='".$data."' WHERE numero='".$numero."'";
                        	
						} else {
							$query2 = "UPDATE ocorrencias SET status=".$status.", local=".$loc.",problema =".$prob.", operador=".$s_uid.", instituicao='".$inst."', equipamento='".$etiqueta."', contato='".noHtml($contato)."', data_fechamento='".$data."' WHERE numero='".$numero."'";
                        	
						}
                                          // echo "Query de dele��o com erro: ".$query2;
						$resultado2 = mysql_query($query2);


                        if (($resultado == 0)  or ($resultado2 == 0))
                        {
                                $aviso = "Um erro ocorreu ao tentar incluir dados no sistema."."Resultado: ".$resultado." Resultado1: ".$resultado1." Resultado2: ".$resultado2;
                        }
                        else {
							
 					$sqlDoc1 = "select * from doc_time where doc_oco = ".$numero." and doc_user=".$_SESSION['s_uid']."";
 					$execDoc1 = mysql_query($sqlDoc1);
 					$regDoc1 = mysql_num_rows($execDoc1);
 					$rowDoc1 = mysql_fetch_array($execDoc1);
 					if ($regDoc1 >0) {
 						$sqlDoc  = "update doc_time set doc_close=doc_close+".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." where doc_id = ".$rowDoc1['doc_id']."";
 						$execDoc =mysql_query($sqlDoc) or die ('ERRO NA TENTATIVA DE ATUALIZAR O TEMPO DE DOCUMENTA��O DO CHAMADO!<br>').$sqlDoc;
 					} else {
 						$sqlDoc = "insert into doc_time (doc_oco, doc_open, doc_edit, doc_close, doc_user) values (".$numero.", 0, 0, ".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." ,".$_SESSION['s_uid'].")";
 						$execDoc = mysql_query($sqlDoc) or die ('ERRO NA TENTATIVA DE ATUALIZAR O TEMPO DE DOCUMENTA��O DO CHAMADO!!<br>').$sqlDoc;
 					}	
							
							
							
							##ROTINAS PARA GRAVAR O TEMPO DO CHAMADO EM CADA STATUS
							if ($status != $rowABS['status_cod']) { //O status foi alterado
								##TRATANDO O STATUS ANTERIOR (atual) -antes da mudan�a
								//Verifica se o status 'atual' j� foi gravado na tabela 'tempo_status' , em caso positivo, atualizo o tempo, sen�o devo gravar ele pela primeira vez.
								$sql_ts_anterior = "select * from tempo_status where ts_ocorrencia = ".$rowABS['numero']." and ts_status = ".$rowABS['status_cod']." ";
								$exec_sql = mysql_query($sql_ts_anterior);
								
								if ($exec_sql == 0) $error= " erro 1".$sql_ts_anterior;
								
								$achou = mysql_num_rows($exec_sql);
								if ($achou >0){ //esse status j� esteve setado em outro momento
									$row_ts = mysql_fetch_array($exec_sql); 
									
								// if (array_key_exists($rowABS['sistema'],$H_horarios)){  //verifica se o c�digo da �rea possui carga hor�ria definida no arquivo config.inc.php
									// $areaT = $rowABS['sistema']; //Recebe o valor da �rea de atendimento do chamado
								// } else $areaT = 1; //Carga hor�ria default definida no arquivo config.inc.php
								$areaT=testaArea($areaT,$rowABS['area_cod'],$H_horarios);	
									
								$dt = new dateOpers; 
								$dt->setData1($row_ts['ts_data']);
								$dt->setData2($hoje);					
								$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$areaT][0],$H_horarios[$areaT][1],$H_horarios[$areaT][2],$H_horarios[$areaT][3],"H");
								$segundos = $dt->diff["sValido"]; //segundos v�lidos
								
								$sql_upd = "update tempo_status set ts_tempo = (ts_tempo+$segundos) , ts_data ='$hoje' where ts_ocorrencia = ".$rowABS['numero']." and 
										ts_status = ".$rowABS['status_cod']." ";
								$exec_upd = mysql_query($sql_upd);
								if ($exec_upd ==0) $error.= " erro 2";
									
								} else {
									$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$rowABS['numero'].", ".$rowABS['status_cod'].", 0, '$hoje' )";
									$exec_ins = mysql_query ($sql_ins); 
									if ($exec_ins == 0) $error.= " erro 3 ".$sql_ins;
								}
									/*
									##TRATANDO O NOVO STATUS  - NAO ARMAZANAREI QUANDO O STATUS FOR DE ENCERRAMENTO!!
									//verifica se o status 'novo' j� est� gravado na tabela 'tempo_status', se estiver eu devo atualizar a data de in�cio. Sen�o estiver gravado ent�o devo gravar pela primeira vez
									$sql_ts_novo = "select * from tempo_status where ts_ocorrencia = ".$rowABS['numero']." and ts_status = $status ";
									$exec_sql = mysql_query($sql_ts_novo);
									if ($exec_sql == 0) $error.= " erro 4";
									
									$achou_novo = mysql_num_rows($exec_sql);
									if ($achou_novo > 0) { //status j� existe na tabela tempo_status
										$sql_upd = "update tempo_status set ts_data = '$hoje' where ts_ocorrencia = ".$rowABS['numero']." and ts_status = $status ";
										$exec_upd = mysql_query($sql_upd);
										if ($exec_upd == 0) $error.= " erro 5";
									} else {//status novo na tabela tempo_status
										$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$rowABS['numero'].", ".$status.", 0, '$hoje' )";
										$exec_ins = mysql_query($sql_ins);
										if ($exec_ins == 0) $error.= " erro 6 ";
									}  */
						}
                        

						$qryfull = $QRY["ocorrencias_full_ini"]." WHERE o.numero = ".$_GET['numero']."";
						$execfull = mysql_query($qryfull) or die('ERRO, N�O FOI POSS�VEL RECUPERAR AS VARI�VEIS DE AMBIENTE!'.$qryfull);
						$rowfull = mysql_fetch_array($execfull);
						
						$VARS = array();
						$VARS['%numero%'] = $rowfull['numero'];
						$VARS['%usuario%'] = $rowfull['contato'];
						$VARS['%contato%'] = $rowfull['contato'];
						$VARS['%descricao%'] = $rowfull['descricao'];
						$VARS['%setor%'] = $rowfull['setor'];
						$VARS['%ramal%'] = $rowfull['telefone'];
						$VARS['%assentamento%'] = $assentamento;
						$VARS['%site%'] = "<a href='".OCOMON_SITE."'>".OCOMON_SITE."</a>";
						$VARS['%area%'] = $rowfull['area'];
						$VARS['%operador%'] = $nome;
						$VARS['%problema%'] = $problema;
						$VARS['%solucao%'] = $solucao;
						$VARS['%versao%'] = VERSAO;
						
						$qryconf = "SELECT * FROM mailconfig";
						$execconf = mysql_query($qryconf) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE ENVIO DE E-MAIL!');
						$rowconf = mysql_fetch_array($execconf);
						
						
						
						if ($mailAR){
							$event = 'encerra-para-area';
							$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
							$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
							$rowmsg = mysql_fetch_array($execmsg);
							send_mail($event, $rowSis['sis_email'], $rowconf, $rowmsg, $VARS);
							
							//$flag = envia_email_fechamento($numero, $rowSis['sis_email'], $nome, $rowSis['sistema'], $problema, $solucao);
						}
						if ($mailUS){
							$event = 'encerra-para-usuario';
							$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
							$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
							$rowmsg = mysql_fetch_array($execmsg);

                            if ($_POST['abertopor'] != "") {
                                $sqlMailUs = "select * from usuarios where user_id = ".$_POST['abertopor']."";
                                //$execMailUs = mysql_query($sqlMailUs);// or die('N�O FOI POSS�VEL ACESSAR A BASE DE USU�RIOS PARA O ENVIO DE EMAIL!');
                                $rowMailUs = mysql_fetch_array($execMailUs);
                                $emailus = $rowMailUs['email'];
                            }else{
                                $regrex = '/Email: (.*?) IP/';
                                preg_match_all($regrex, $rowfull['descricao'], $emailus);
                                $emailus = trim($emailus[1][0]);
                            }

							$sqlMailUs = "select * from usuarios where user_id = ".$_POST['abertopor']."";
							//$execMailUs = mysql_query($sqlMailUs);// or die('N�O FOI POSS�VEL ACESSAR A BASE DE USU�RIOS PARA O ENVIO DE EMAIL!');
							$rowMailUs = mysql_fetch_array($execMailUs);
							
							$qryresposta = "select u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and u.user_id = ".$_SESSION['s_uid'].""; 
							//$execresposta = mysql_query($qryresposta) or die ('N�O FOI POSS�VEL IDENTIFICAR O EMAIL PARA RESPOSTA!');
							$rowresposta = mysql_fetch_array($execresposta);
							
/*							$flag = mail_user_encerramento($rowMailUs['email'], $rowresposta['sis_email'], $rowMailUs['nome'],$_GET['numero'],
													$assentamento,OCOMON_SITE);*/
							send_mail($event, $emailus, $rowconf, $rowmsg, $VARS);
						}
						
						$aviso = "ocorrência encerrada com sucesso!";
				}
				print "<script>mensagem('".$aviso."'); redirect('abertura.php');</script>";

        	}

        ?>
<script type="text/javascript">
<!--			

	function valida(){
		var ok = validaForm('idProb','COMBO','Problema',1);
		if (ok) var ok = validaForm('idEtiqueta','INTEIROFULL','Etiqueta',0);
		if (ok) var ok = validaForm('idContato','','Contato',1);
		if (ok) var ok = validaForm('idLocal','COMBO','Local',1);
		if (ok) var ok = validaForm('idData_fechamento','DATA','Data',1);
		if (ok) var ok = validaForm('idProblema','','Descri��o t�cnica',1);
		if (ok) var ok = validaForm('idSolucao','','Solu��o',1);
		if (ok) var ok = validaForm('idInstituicao','COMBO','Tipo',1);
		
		return ok;
	
	}		


	 function popup_alerta(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'Alerta','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);	  	
		return false
     }

	 function checa_etiqueta(){
	 	var inst = document.form1.inst.value;
		var inv = document.form1.etiqueta.value;
		if (inst=='null' || !inv){
			window.alert('Os campos Unidade e etiqueta devem ser preenchidos!');
		} else
			popup_alerta('../../invmon/geral/mostra_consulta_inv.php?comp_inst='+inst+'&comp_inv='+inv+'&popup='+true);

		return false;
	 }
	 
	function checa_chamados(){
	 	var inst = document.form1.inst.value;
		var inv = document.form1.etiqueta.value;
		if (inst=='null' || !inv){
			window.alert('Os campos Unidade e etiqueta devem ser preenchidos!');
		} else
			popup_alerta('../../invmon/geral/ocorrencias.php?comp_inst='+inst+'&comp_inv='+inv+'&popup='+true);

		return false;
	}
	
	 function checa_por_local(){
	 	var local = document.form1.loc.value;
		if (local==-1){
			window.alert('O local deve ser preenchido!');
		} else
			popup_alerta('../../invmon/geral/mostra_consulta_comp.php?comp_local='+local+'&popup='+true);

		return false;
	 }


</script>


</div>
</body>
</HTML>
