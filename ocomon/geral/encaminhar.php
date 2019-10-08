<!DOCTYPE html>
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
	include ("../../includes/include_geral_II.inc.php");;		

?>

</head>
<body>
<div class="container-fluid">

<?
	$auth = new auth;
	if ($popup) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	} else {
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	}
	$hoje = date("Y-m-d H:i:s");
       $hoje2 = date("d/m/Y");
		
	$query = "select o.*, u.* from ocorrencias as o, usuarios as u where o.operador = u.user_id and numero=".$numero."";
       $resultado = mysql_query($query);
	$row = mysql_fetch_array($resultado);
       $linhas = mysql_numrows($resultado);

	//flavio
	$data_atend = $row['data_atendimento']; //Data de atendimento!!!
	//flavio

	$query2 = "select * from assentamentos where ocorrencia=".$numero."";
       $resultado2 = mysql_query($query2);
	$linhas2 = mysql_num_rows($resultado2);

	if ($s_nivel == 1){
   		$linkEdita = "<a href='altera_dados_ocorrencia.php?numero=".$numero."'>Editar ocorrência como admin:</a>";
	} else {
		$linkEdita = "Editar ocorrência:";
	}
?>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h4 class="panel-title"><?php echo $linkEdita ?></h4>
        </div>

        <form name='form1' method='POST' action="" enctype='multipart/form-data'  onSubmit='return valida()'>

            <div class="panel-body">

                <div class="row">

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>N�mero:</label>

                            <input type="text" class="form-control" disabled value="<?print $row['numero'];?>">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="idArea">�rea respons�vel:</label>
                            <?php
                            $query = "SELECT * from sistemas where sis_status NOT in (0) and sis_atende = 1 order by sistema ";
                            $exec_sis = mysql_query($query);
                            ?>
                            <SELECT class='form-control' name='sistema' id='idArea' size=1 onChange='fillSelectFromArray(this.form.problema, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));fillSelectFromArray(this.form.institui, ((this.form.sistema.selectedIndex == -1) ? null : listUnidade[this.form.sistema.selectedIndex-1]));'>
                                <option value= '-1'>Selecione a �rea</option>
                                <?php
                                while ($row_sis = mysql_fetch_array($exec_sis)) {
                                    $selected = ($row_sis['sis_id'] == $row['sistema']) ? ' selected' : '';
                                    ?>
                                    <option value="<?php echo $row_sis['sis_id']?>" <?php echo $selected?>><?php echo $row_sis['sistema']?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Tipo:</label>
                            <?php
                            $instituicao = $row['instituicao'];

                            if ($instituicao != null)
                                $query = "SELECT * FROM instituicao WHERE inst_cod=$instituicao and sistema=".$row['sistema'];
                            else
                                $query = "SELECT * FROM instituicao WHERE inst_cod is null and sistema=".$row['sistema'];

                            $resultado3 = mysql_query($query);
                            $nomeinst = "";

                            if (mysql_numrows($resultado3) > 0)
                                $nomeinst=mysql_result($resultado3,0,1);

                            $query_todas="select * from instituicao where inst_status = 1 and sistema=".$row['sistema']."  order by inst_cod";
                            $result_todas=mysql_query($query_todas);
                            ?>
                            <select class='form-control' name='institui' size=1>
                                <option value= '-1'>Selecione o problema</option>
                                <?php
                                while($row_todas=mysql_fetch_array($result_todas)) {
                                    $selected = ($row_todas[inst_cod]==$instituicao) ? ' selected' : '';
                                    ?>
                                    <option value="<?php echo $row_todas[inst_cod]?>" <?php echo $selected?>><?php echo $row_todas[inst_nome]?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="idProblema">Problema:</label>
                            <?php
                            $query = "SELECT * from problemas where prob_status = 1 and prob_area = ".$row['sistema']." order by problema";
                            $exec_prob = mysql_query($query);
                            ?>
                            <select class='form-control' name='problema' id='idProblema' size=1>
                                <option value= '-1'>Selecione o problema</option>
                                <?php
                                while ($row_prob = mysql_fetch_array($exec_prob)) {
                                    $selected = ($row_prob['prob_id'] == $row['problema']) ? ' selected' : '';
                                    ?>
                                    <option value="<?php echo $row_prob['prob_id']?>" <?php echo $selected?>><?php echo $row_prob['problema']?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Etiqueta do equip.:</label>
                            <input type="text" class='form-control' name="etiq" id="idEtiqueta" value="<?php echo $row['equipamento'];?>">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="idContato">Contato:</label>
                            <input type="text" class='form-control' name="contato" id="idContato" disabled value="<?php echo $row['contato']?>">
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Telefone:</label>
                            <input type="text" class='form-control' disabled value="<?php echo $row['telefone'];?>">
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="idLocal">Local:</label>
                            <button onClick="checa_por_local()" style="float: right" type="button" class="btn btn-warning btn-xs" title='Consulta os equipamentos cadastrados para esse local!'>
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            </button>
                            <select class='form-control' name="local" id='idLocal'>
                                <option value=-1>Selecione o local</option>
                                <?php
                                $query = "SELECT * from localizacao order by local";
                                $exec_loc = mysql_query($query);
                                while ($row_loc = mysql_fetch_array($exec_loc)){
                                    $selected = ($row_loc['loc_id'] == $row['local']) ?' selected' : '';
                                    ?>
                                    <option value="<?php echo $row_loc['loc_id']?>" <?php echo $selected?>><?php echo $row_loc['local']?></option>
                                    <?php
                                } // while
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="idStatus">Status:</label>
                            <?php
                            if ($row['status'] == 4){$stat_flag="";} else $stat_flag =" where stat_id<>4 ";

                            $query_stat = "SELECT * from status ".$stat_flag." order by status";
                            $exec_stat = mysql_query($query_stat);
                            ?>
                            <SELECT class='form-control' name='status' id='idStatus' size=1>
                                <option value= '-1'>Selecione o status</option>
                                <?php
                                while ($row_stat = mysql_fetch_array($exec_stat)) {
                                    $selected = ($row_stat['stat_id'] == $row['status']) ? ' selected' : '';
                                    ?>
                                    <option value="<?php echo $row_stat['stat_id']?>" <?php echo $selected?>><?php echo $row_stat['status']?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Operador:</label>
                            <select class='form-control' name='operador'>
                            <?php
                            $query = "SELECT u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and a.sis_atende=1 and u.nivel not in (3,4,5) order by login";
                            $exec_oper = mysql_query($query);
                            while ($row_oper = mysql_fetch_array($exec_oper)){
                                $selected = ($row_oper['user_id']== $_SESSION['s_uid']) ? ' selected' : '';
                            ?>
                                <option value="<?php echo $row_oper['user_id']?>" <?php echo $selected ?>><?php echo $row_oper['nome']?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Data de abertura:</label>
                            <input type="text" class='form-control' disabled value="<?php echo datab($row['data_abertura']);?>">
                        </div>
                    </div>
                <?php
                    $antes = $row['status'];
                    if ($row['status'] == 4) {//Encerrado
                        $antes = 4;
                ?>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Data de Encerramento:</label>
                            <input type="text" class='form-control' disabled value=<?php echo datab($row['data_fechamento']);?>>
                        </div>
                    </div>

                <?php } ?>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Descri��o:</label>
                            <div class="form-control" ><?php echo nl2br($row['descricao']);?></div>
                        </div>
                    </div>
                </div>


                <?php
                    if ($linhas2!=0){

                        $i = ($linhas2==1 ) ? $linhas2-1 : $linhas2-2;

                        while ($i < $linhas2){
                        ?>
                            <div class="alert alert-warning" style="margin-bottom: 5px; padding: 5px">
                                <strong>
                                    Assentamento <?print $i+1;?> de <?print $linhas2;?> por <?print mysql_result($resultado2,$i,4);?> em <?print datab(mysql_result($resultado2,$i,3));?>
                                </strong>
                                 - <?print nl2br(mysql_result($resultado2,$i,2));?>
                            </div>
                            <?
                            $i++;
                        }
                    }
                ?>
                <div class="row">

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Assentamento:</label>
                            <textarea class="form-control" id="idAssentamento" name="assentamento" >ocorrência encaminhada/alterada por <?echo $s_usuario;?></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12">
                    <?php
                        $qryTela = "select * from imagens where img_oco = ".$row['numero'];
                        $execTela = mysql_query($qryTela) or die ("N�o foi poss�vel recuperar as informa��es da tabela imagens");
                        //$rowTela = mysql_fetch_array($execTela);
                        $isTela = mysql_num_rows($execTela);
                        $cont = 0;
                        while ($rowTela = mysql_fetch_array($execTela)) {
                        //if ($isTela !=0) {
                            $cont++;
                            print "<label>Anexo ".$cont."</label>";
                            print "<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$row['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela['img_nome']."</a>";
                        }
                    ?>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Anexar imagem:</label>
                            <input type='file' class='form-control' name='img' id='idImg'/>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <?php
                                $qrymail = "SELECT u.*, a.*,o.* from usuarios u, sistemas a, ocorrencias o where ".
                                    "u.AREA = a.sis_id and o.aberto_por = u.user_id and o.numero = ".$numero."";
                                $execmail = mysql_query($qrymail);
                                $rowmail = mysql_fetch_array($execmail);
                                $habilita = ($rowmail['sis_atende']==0) ? "" : "disabled";
                            ?>
                            <p>Enviar e-mail para:</p>
                            <label class="checkbox-inline">
                                <input type="checkbox" value='ok' name="mailAR" title='Envia e-mail para a �rea selecionada para esse chamado'>�rea respons�vel
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value='ok' name="mailOP" title='Envia e-mail para o operador selecionado no chamado'>Operador
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value='ok' name='mailUS' <?php echo $habilita?> ><a title='Essa op��o s� fica habilitada para chamados abertos pelo pr�prio usu�rio'>Usu�rio</a>
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="checkbox">
                        <?php if ($data_atend =="") {
                        print "<input type='checkbox' value='ok' name='resposta' checked title='Desmarque essa op��o se esse assentamento n�o corresponder a uma primeira resposta do chamado'>1.� Resposta";
                        } ?>
                        </div>
                    </div>

                    <input type="hidden" name="rodou" value="sim">
                    <input type='hidden' name='data_gravada' value='<?php echo date("Y-m-d H:i:s")?>'>
                    <input type="hidden" name="abertopor" value="<?print $rowmail['user_id']?>">

                </div>
            </div>


            <div class="form-actions right">

                <input type="submit" value="  Ok  " name="ok" class="btn btn-success"/>

                <button type="button" class="btn btn-default"name='cancelar' OnClick="javascript:history.back()">Cancelar</button>

            </div>

        </form>

    </div>

         <?php

         	if ($rodou == "sim") {

                	$depois = $status;
                   	$erro= "nao";
                    if ($erro == "nao")  {
	                    
	                    //showArray($_FILES); showArray($_POST); exit;
	                    
			$gravaImg = false;
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
	                    
	                    
	                    
	                    $data = datam($hoje2);
                            $responsavel = $s_uid;
                            //$assentamento = addslashes($assentamento);
                            
                            $queryA = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel)".
													" values ($numero,'".noHtml($assentamento)."', '$data', $responsavel)";
							
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
						$exec = mysql_query($SQL);// or die ("N�O FOI POSS�VEL GRAVAR O ARQUIVO NO BANCO DE DADOS! ".$SQL);
						if ($exec == 0) $aviso.= "N�O FOI POSS�VEL ANEXAR A IMAGEM!<br>";
						
					}						
					
					
				$sqlMailLogado = "select * from usuarios where login = '".$s_usuario."'";
				$execMailLogado = mysql_query($sqlMailLogado) or die('ERRO AO TESTAR RECUPERAR AS INFORMA��ES DO USU�RIO!');
				$rowMailLogado = mysql_fetch_array($execMailLogado);
				
				$qryLocal = "select * from localizacao where loc_id=".$local."";
				$execLocal = mysql_query($qryLocal);
				$rowLocal = mysql_fetch_array($execLocal);
							
			
						
							
								
							$qryfull = $QRY["ocorrencias_full_ini"]." WHERE o.numero = ".$numero."";
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
							$VARS['%operador%'] = $rowfull['nome'];
							$VARS['%editor%'] = $rowMailLogado['nome'];
							$VARS['%problema%'] = $rowfull['problema'];
							$VARS['%versao%'] = VERSAO;
							
							$qryconf = "SELECT * FROM mailconfig";
							$execconf = mysql_query($qryconf) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE ENVIO DE E-MAIL!');
							$rowconf = mysql_fetch_array($execconf);
							
							if ($mailOP){ 
								$event = 'edita-para-operador';
								$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
								$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
								$rowmsg = mysql_fetch_array($execmsg);
								
								$sqlMailOper = "select * from usuarios where user_id =".$operador."";
								$execMailOper = mysql_query($sqlMailOper);
								$rowMailOper = mysql_fetch_array($execMailOper);
								
/*								$flag = envia_email_operador($numero, $rowMailOper['email'],$rowMailLogado['email'] ,$row['descricao'], 
														$assentamento, $row['contato'], $rowLocal['local'], $row['telefone'], $rowMailOper['nome'], 
														$rowMailLogado['nome'], OCOMON_SITE);							*/
								$VARS['%operador%'] = $rowMailOper['nome'];
								send_mail($event, $rowMailOper['email'], $rowconf, $rowmsg, $VARS);
							}
							if ($mailAR){
								$event = 'edita-para-area';
								$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
								$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
								$rowmsg = mysql_fetch_array($execmsg);
								
								$sqlMailArea = "select * from sistemas where sis_id = ".$sistema."";
								$execMailArea = mysql_query($sqlMailArea);
								$rowMailArea = mysql_fetch_array($execMailArea);
								
/*								$flag = envia_email_area($numero, $rowMailArea['sis_email'], $row['descricao'], 
														$assentamento, $row['contato'], $rowLocal['local'], $row['telefone'], 
														$rowMailLogado['nome'], $rowMailArea['sistema'], OCOMON_SITE);*/
								
								send_mail($event, $rowMailArea['sis_email'], $rowconf, $rowmsg, $VARS);
							}
							if ($mailUS){
								$event = 'edita-para-usuario';
								$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
								$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
								$rowmsg = mysql_fetch_array($execmsg);

                                if ($_POST['abertopor'] != "") {
                                    $sqlMailUs = "select * from usuarios where user_id = ".$_POST['abertopor']."";
                                    $execMailUs = mysql_query($sqlMailUs) or die('N�O FOI POSS�VEL ACESSAR A BASE DE USU�RIOS PARA O ENVIO DE EMAIL!');
                                    $rowMailUs = mysql_fetch_array($execMailUs);
                                    $emailus = $rowMailUs['email'];
                                }else{
                                    $regrex = '/Email: (.*?) IP/';
                                    preg_match_all($regrex, $rowfull['descricao'], $emailus);
                                    $emailus = trim($emailus[1][0]);
                                }
								
								$qryresposta = "select u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and u.user_id = ".$_SESSION['s_uid'].""; 
								$execresposta = mysql_query($qryresposta) or die ('N�O FOI POSS�VEL IDENTIFICAR O EMAIL PARA RESPOSTA!');
								$rowresposta = mysql_fetch_array($execresposta);
								
/*								$flag = mail_user_assentamento($rowMailUs['email'], $rowresposta['sis_email'], $rowMailUs['nome'],$_GET['numero'],
														$assentamento,OCOMON_SITE);*/
								send_mail($event, $emailus, $rowconf, $rowmsg, $VARS);
							}

														
							$resultado3 = mysql_query($queryA) or die('N�O FOI POSS�VEL GRAVAR AS INFORMA��ES DE EDI��O DO CHAMADO!<br>'.$queryA);
								
                            	if ($antes != $depois) //Status alterado!!
				{   //$status!=1 and 
					if (($data_atend==null) and ($status!=4) and ($resposta == "ok")) //para verificar se j� foi setada a data do inicio do atendimento. //Se eu incluir um assentamento seto a data de atendimento
                                    	{    
						$query = "UPDATE ocorrencias SET operador=".$operador.", problema = ".$problema.", instituicao='$institui', equipamento = '".$etiq."', sistema = '".$sistema."', local=".$local.", data_fechamento=NULL, status=".$status.", data_atendimento='".$data."' WHERE numero=".$numero."";
	                                	$resultado4 = mysql_query($query);
					}  else
				 	{							
            	    	                 	$query = "UPDATE ocorrencias SET operador=".$operador.", problema = ".$problema." , instituicao='".$institui."', equipamento = '".$etiq."', sistema = '".$sistema."', local=".$local.", data_fechamento=NULL, status=".$status." WHERE numero=".$numero."";
                        	        	$resultado4 = mysql_query($query);
					}
				} else
				{
					if (($data_atend==null) and ($status!=4) and ($resposta == "ok")) //para verificar se j� foi setada a data do inicio do atendimento. //Se eu incluir um assentamento seto a data de atendimento
                                    	{    
						$query = "UPDATE ocorrencias SET operador=".$operador.", problema = ".$problema.", instituicao='".$institui."', equipamento = '".$etiq."', sistema = '".$sistema."', local=".$local.", data_fechamento=NULL, status=".$status.", data_atendimento='".$data."' WHERE numero=".$numero."";
	                                	$resultado4 = mysql_query($query);
					} else {
						$query = "UPDATE ocorrencias SET operador=".$operador.", problema = ".$problema.", instituicao='".$institui."', equipamento = '".$etiq."', sistema = '".$sistema."', local=".$local.", status=".$status." WHERE numero=".$numero."";
						$resultado4 = mysql_query($query);
					}
				}		

                                if (($resultado3==0) OR ($resultado4 == 0))
	                       	{
                                 	$aviso = "ERRO DE ACESSO. Um erro ocorreu ao tentar alterar ocorrência no sistema. - $query";
    	                        }  else
        	               {
        	               
 					$sqlDoc1 = "select * from doc_time where doc_oco = ".$numero." and doc_user=".$_SESSION['s_uid']."";
 					$execDoc1 = mysql_query($sqlDoc1) or die('ERRO<br>'.$sqlDoc1);
 					$regDoc1 = mysql_num_rows($execDoc1);
 					$rowDoc1 = mysql_fetch_array($execDoc1);
 					if ($regDoc1 >0) {
 						$sqlDoc  = "update doc_time set doc_edit=doc_edit+".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." where doc_id = ".$rowDoc1['doc_id']."";
 						$execDoc =mysql_query($sqlDoc) or die ('ERRO NA TENTATIVA DE ATUALIZAR O TEMPO DE DOCUMENTA��O DO CHAMADO!<br>').$sqlDoc;
 					} else {
 						$sqlDoc = "insert into doc_time (doc_oco, doc_open, doc_edit, doc_close, doc_user) values (".$numero.", 0, ".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." , 0, ".$_SESSION['s_uid'].")";
 						$execDoc = mysql_query($sqlDoc) or die ('ERRO NA TENTATIVA DE ATUALIZAR O TEMPO DE DOCUMENTA��O DO CHAMADO!!<br>').$sqlDoc;
 					}	
        	               
        	               		##ROTINAS PARA GRAVAR O TEMPO DO CHAMADO EM CADA STATUS
                               		 if ($status != $row['status']) { //O status foi alterado
					##TRATANDO O STATUS ANTERIOR
					//Verifica se o status 'atual' j� foi gravado na tabela 'tempo_status' , em caso positivo, atualizo o tempo, sen�o devo gravar ele pela primeira vez.
						$sql_ts_anterior = "select * from tempo_status where ts_ocorrencia = ".$row['numero']." and ts_status = ".$row['status']." ";
						$exec_sql = mysql_query($sql_ts_anterior);
						
						if ($exec_sql == 0) $error= " erro 1";
						
						$achou = mysql_num_rows($exec_sql);
						if ($achou >0){ //esse status j� esteve setado em outro momento
							$row_ts = mysql_fetch_array($exec_sql); 
							
						// if (array_key_exists($row['sistema'],$H_horarios)){  //verifica se o c�digo da �rea possui carga hor�ria definida no arquivo config.inc.php
							// $areaT = $row['sistema']; //Recebe o valor da �rea de atendimento do chamado
						// } else $areaT = 1; //Carga hor�ria default definida no arquivo config.inc.php
						
						$areaT=testaArea($areaT,$row['sistema'],$H_horarios);	
							
							$dt = new dateOpers; 
							$dt->setData1($row_ts['ts_data']);
							$dt->setData2($hoje);					
							$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$areaT][0],$H_horarios[$areaT][1],$H_horarios[$areaT][2],$H_horarios[$areaT][3],"H");
							$segundos = $dt->diff["sValido"]; //segundos v�lidos
							
							$sql_upd = "update tempo_status set ts_tempo = (ts_tempo+$segundos) , ts_data ='$hoje' where ts_ocorrencia = ".$row['numero']." and 
									ts_status = ".$row['status']." ";
							$exec_upd = mysql_query($sql_upd);
							if ($exec_upd ==0) $error.= " erro 2";
							
						} else {
							$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$row['numero'].", ".$row['status'].", 0, '$hoje' )";
							$exec_ins = mysql_query ($sql_ins); 
							if ($exec_ins == 0) $error.= " erro 3 ";
						}
						##TRATANDO O NOVO STATUS
						//verifica se o status 'novo' j� est� gravado na tabela 'tempo_status', se estiver eu devo atualizar a data de in�cio. Sen�o estiver gravado ent�o devo gravar pela primeira vez
						$sql_ts_novo = "select * from tempo_status where ts_ocorrencia = ".$row['numero']." and ts_status = $status ";
						$exec_sql = mysql_query($sql_ts_novo);
						if ($exec_sql == 0) $error.= " erro 4";
						
						$achou_novo = mysql_num_rows($exec_sql);
						if ($achou_novo > 0) { //status j� existe na tabela tempo_status
							$sql_upd = "update tempo_status set ts_data = '$hoje' where ts_ocorrencia = ".$row['numero']." and ts_status = $status ";
							$exec_upd = mysql_query($sql_upd);
							if ($exec_upd == 0) $error.= " erro 5";
						} else {//status novo na tabela tempo_status
							$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$row['numero'].", ".$status.", 0, '$hoje' )";
							$exec_ins = mysql_query($sql_ins);
							if ($exec_ins == 0) $error.= " erro 6 ";
						}
					}
			
			
					$aviso = "ocorrência alterada com sucesso! ";
	
				}
                        } //fecha if erro=nao
			print "<script>mensagem('".$aviso."'); redirect('mostra_consulta.php?numero=".$numero."');</script>";
						
		}//fecha if rodou=sim
        ?>
</div>
</body>
<script type="text/javascript">
<!--			
	
	function valida(){
		var ok = validaForm('idStatus','COMBO','Status',1);
		if (ok) var ok = validaForm('idProblema','COMBO','Problema',1);
		if (ok) var ok = validaForm('idArea','COMBO','�rea',1);
		
		if (ok) var ok = validaForm('idEtiqueta','INTEIROFULL','Etiqueta',0);
		if (ok) var ok = validaForm('idLocal','COMBO','Local',1);
		if (ok) var ok = validaForm('idAssentamento','','Assentamento',1);
		
		return ok;
	
	}	
	listUnidade = new Array(
<?
$sql="select * from sistemas where sis_status NOT in (0) and sis_atende = 1 order by sistema";//Somente as �reas ativas
$sql_result=mysql_query($sql);
echo mysql_error();
$num=mysql_numrows($sql_result);
$conta=0;
$conta_sub=0;
while ($row_A=mysql_fetch_array($sql_result)){
$conta=$conta+1;
	$cod_item=$row_A["sis_id"];
		echo "new Array(\n";
		$sub_sql="select * from instituicao i  join sistemas s on i.sistema = s.sis_id where inst_status = 1 and i.sistema='$cod_item' order by inst_nome";
		$sub_result=mysql_query($sub_sql);
		$num_sub=mysql_numrows($sub_result);
		if ($num_sub>=1){
			echo "new Array(\"- Selecione a unidade -\", -1),\n";
			while ($rowx=mysql_fetch_array($sub_result)){
				$codigo_sub=$rowx["inst_cod"];
				$sub_nome=$rowx["inst_nome"];
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
?>
);
	
	
	
	team = new Array(
<?
$sql="select * from sistemas where sis_status NOT in (0) and sis_atende = 1 order by sistema";//Somente as �reas ativas
$sql_result=mysql_query($sql);
echo mysql_error();
$num=mysql_numrows($sql_result);
$conta=0;
$conta_sub=0;
while ($row_A=mysql_fetch_array($sql_result)){
$conta=$conta+1;
	$cod_item=$row_A["sis_id"];
		echo "new Array(\n";
		$sub_sql="select * from problemas p left join sistemas s on p.prob_area = s.sis_id where prob_status = 1 and prob_area='$cod_item' or prob_area is null order by problema";
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
?>
);


	

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



-->	
	
</script>

</html>
