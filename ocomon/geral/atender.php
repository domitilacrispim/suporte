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

    //$query = "select o.*, u.* from ocorrencias as o, usuarios as u where o.operador = u.user_id and numero=$numero";
    $query = $QRY["ocorrencias_full_ini"]." where numero in (".$numero.") order by numero";
    $resultado = mysql_query($query);
    $row = mysql_fetch_array($resultado);

    if (mysql_numrows($resultado)>0)
        $linhas1 = mysql_numrows($resultado)-1;
    else
        $linhas1 = mysql_numrows($resultado);

    //flavio
    $data_atend = mysql_result($resultado,$linhas1,12); //Data de atendimento!!!
    //flavio

    $query2 = "select * from assentamentos where ocorrencia='$numero'";
    $resultado2 = mysql_query($query2);
    $linhas=mysql_numrows($resultado2);
    $hoje = date("Y-m-d H:i:s");
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
?>

    <div class="panel panel-info">

        <div class="panel-heading">
            <h4 class="panel-title">
                Atendimento de ocorrências
            </h4>
        </div>

        <form method="POST" action="" onSubmit="return valida()">



            <div class="panel-body">

                <div class="row">

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>N�mero:</label>
                            <div class="form-control" ><?php echo $row['numero'];?></div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>�rea respons�vel:</label>
                            <div class="form-control" ><?php echo $row['area'];?></div>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Tipo:</label>
                            <div class="form-control"><?php echo $row['unidade'];?></div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Problema:</label>
                            <div class="form-control"><?php echo $row['problema'];?></div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Etiqueta do equip.:</label>
                            <div class="form-control"><?php echo $row['etiqueta'];?></div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Contato:</label>
                            <div class="form-control"><?php echo $row['contato'];?></div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Telefone:</label>
                            <div class="form-control"><?php echo $row['telefone'];?></div>
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="form-group">
                            <label >Local:</label>
                            <div class="form-control"><?php echo $row['setor'];?></div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Operador:</label>
                            <div class="form-control"><?php echo $row['nome'];?></div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Data de abertura:</label>
                            <div class="form-control"><?php echo datab($row['data_abertura']);?></div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Status:</label>
                            <div class='form-control'><?php echo $row['chamado_status'];?></div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Descri��o:</label>
                            <div class="form-control" ><?php echo $row['descricao'];?></div>
                        </div>
                    </div>
                </div>

                <?php
                if ($linhas!=0){
                    $i=0;
                    while ($i < $linhas){
                        ?>
                        <div class="alert alert-warning" style="margin-bottom: 5px; padding: 5px">
                            <strong>
                                Assentamento <?print $i+1;?> de <?print $linhas;?> por <?print mysql_result($resultado2,$i,4);?> em <?print datab(mysql_result($resultado2,$i,3));?>
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
                            <label>Assentamento:</label>
                            <textarea id="idAssentamento" name="assentamento" class="form-control" >Em atendimento por <?print $s_usuario;?></textarea>
                        </div>
                    </div>

                    <input type='hidden' name='data_gravada' value='<?php echo date("Y-m-d H:i:s") ?>'>
                    <input type="hidden" name="rodou" value="sim">

                </div>
            </div>


            <div class="form-actions right">
                <input type="submit" class="btn btn-success" value="  Ok  " name="ok" />
                <button type="button" class="btn btn-default"name='desloca' OnClick="javascript:history.back()">Cancelar</button>
            </div>

        </form>

    </div>

        <?
        //$data = $hoje;
		//print "DATA: ".$data."<br>";
		//print "HOJE: ".$hoje."<br>";
			if ($rodou == "sim")
                {
                        $data = $hoje;
                        $responsavel=$s_uid;
                        $query = "INSERT INTO assentamentos (numero, ocorrencia, assentamento, data, responsavel) values (0,'$numero','".noHtml($assentamento)."', '$data', $responsavel)";
                        $resultado = mysql_query($query);

                        $status = 2; //Em atendimento
						if ($data_atend!=null) {
							$query2 = "UPDATE ocorrencias SET status=$status, operador=$s_uid WHERE numero='$numero'";
                        } else
							$query2 = "UPDATE ocorrencias SET status=$status, operador=$s_uid, data_atendimento='$hoje' WHERE numero='$numero'";
						
						$resultado2 = mysql_query($query2);


                        if (($resultado == 0) or ($resultado2 == 0))
                        {
                                if ($resultado == 0)
                                        $aviso = "Um erro ocorreu ao tentar incluir dados no sistema - INSERT.";
                                if ($resultado2 == 0)
                                        $aviso = "Um erro ocorreu ao tentar incluir dados no sistema - UPDATE.";
                        }
                        else
                        {
 					
 					
 					$sqlDoc1 = "select * from doc_time where doc_oco = ".$numero." and doc_user=".$_SESSION['s_uid']."";
 					$execDoc1 = mysql_query($sqlDoc1);
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
                                        if ($status != $row['status_cod']) { //O status foi alterado
												##TRATANDO O STATUS ANTERIOR
												//Verifica se o status 'atual' j� foi gravado na tabela 'tempo_status' , em caso positivo, atualizo o tempo, sen�o devo gravar ele pela primeira vez.
												$sql_ts_anterior = "select * from tempo_status where ts_ocorrencia = ".$row['numero']." and ts_status = ".$row['status_cod']." ";
												$exec_sql = mysql_query($sql_ts_anterior);
												
												if ($exec_sql == 0) $error= " erro 1";
												
												$achou = mysql_num_rows($exec_sql);
												if ($achou >0){ //esse status j� esteve setado em outro momento
													$row_ts = mysql_fetch_array($exec_sql); 
													
												// if (array_key_exists($row['sistema'],$H_horarios)){  //verifica se o c�digo da �rea possui carga hor�ria definida no arquivo config.inc.php
													// $areaT = $row['sistema']; //Recebe o valor da �rea de atendimento do chamado
												// } else $areaT = 1; //Carga hor�ria default definida no arquivo config.inc.php
												testaArea($areaT,$row['area_cod'],$H_horarios);	
													
													$dt = new dateOpers; 
													$dt->setData1($row_ts['ts_data']);
													$dt->setData2($hoje);					
													$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$areaT][0],$H_horarios[$areaT][1],$H_horarios[$areaT][2],$H_horarios[$areaT][3],"H");
													$segundos = $dt->diff["sValido"]; //segundos v�lidos
													
													$sql_upd = "update tempo_status set ts_tempo = (ts_tempo+$segundos) , ts_data ='$hoje' where ts_ocorrencia = ".$row['numero']." and 
															ts_status = ".$row['status_cod']." ";
													$exec_upd = mysql_query($sql_upd);
													if ($exec_upd ==0) $error.= " erro 2";
													
												} else {
													$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$row['numero'].", ".$row['status_cod'].", 0, '$hoje' )";
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
								
								
								
								$aviso = "OK. Assentamento de atendimento incluido com sucesso.<br><a href='encerramento.php?numero=".$numero."'>Encerrar</a>";
                        }
                        $origem = "abertura.php";
                        session_register("aviso");
                        session_register("origem");
                        //echo "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0;URL=mensagem.php\">";
						print "<script>redirect('mensagem.php')</script>";
                }

        ?>

</div>

</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idAssentamento','','Assentamento',1);
		
		return ok;
	}		
-->	
</script>
</HTML>
