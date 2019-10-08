<HTML>
<HEAD>
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

        if ($s_nivel!=1)        {
              print "<script>window.open('../../index.php','_parent','')</script>";
		exit;
	}      
		
	$hoje = date("Y-m-d H:i:s");
       $hoje2 = date("d/m/Y");
		
	$query = "select o.*, u.* from ocorrencias o, usuarios u where o.operador=u.user_id and numero=$numero";
       $resultado = mysql_query($query);
	$row = mysql_fetch_array($resultado);
       $linhas = mysql_numrows($resultado);

	//flavio
	$data_atend = $row['data_atendimento']; //Data de atendimento!!!
	//flavio

	$query2 = "select * from assentamentos where ocorrencia=$numero";
       $resultado2 = mysql_query($query2);
	$linhas2 = mysql_numrows($resultado2);
?>

</HEAD>
<BODY bgcolor=<?print BODY_COLOR?>>

	<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
        	<TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TAB_COLOR?>>
            	<TR>
                	<?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>OcoMon - M�dulo de Administra��o</b></TD>";
                        echo menu_usuario();
                        if ($s_nivel==1)
                        {
                        	echo menu_admin();
                        }
                    ?>
                </TR>
            </TABLE>
        </TD>
	</TABLE>

	<BR><B>Editar ocorrência:</B><BR>

	<FORM method="POST" action="<?PHP_SELF?>" onSubmit="return valida()">

		<TABLE border="0"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        	<TR>
                	<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>N�mero:</TD>
                	<TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row['numero'];?></TD>

			<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Status:</TD>
			<TD colspan='3' align="left" bgcolor=<?print BODY_COLOR?>>
            	
						<?
							if ($row['status'] == 4){$stat_flag="";} else $stat_flag =" where stat_id<>4 ";
							
							print "<SELECT class='select' name='status' id='idStatus' size=1>";
	        		        		print "<option value= '-1'>Selecione o status</option>";
	                				$query_stat = "SELECT * from status ".$stat_flag." order by status";  
			                		$exec_stat = mysql_query($query_stat);
        		    	    			while ($row_stat = mysql_fetch_array($exec_stat))
                					{
									print "<option value=".$row_stat['stat_id']."";
										if ($row_stat['stat_id'] == $row['status']) {
											print " selected";
										}
									print " >".$row_stat['status']." </option>";
            		    				}
							print "</SELECT>";
						?>
			</TD>					
        	
		</TR>
<!--*********************************************************************************************-->				
        	<TR>

                	<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>�rea:</TD>
	              <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
						<?
						

						print "<SELECT class='select' name='sistema' id='idSistema' size=1 ";
			
			if ($rowconf['conf_scr_prob'] || !isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
				print " onChange=\"fillSelectFromArray(this.form.problema, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));fillSelectFromArray(this.form.instituicao, ((this.form.sistema.selectedIndex == -1) ? null : listUnidade[this.form.sistema.selectedIndex-1]));\"";
			}
			print ">";

		        		       print "<option value= '-1'>Selecione a �rea</option>";
	                			$query = "SELECT * from sistemas where sis_status NOT in (0) and sis_atende = 1 order by sistema";
			                	$exec_sis = mysql_query($query);
        		    	    		while ($row_sis = mysql_fetch_array($exec_sis))
                				{
									print "<option value=".$row_sis['sis_id']."";
									if ($row_sis['sis_id'] == $row['sistema']) {
											print " selected";
									}
									print " >".$row_sis['sistema']." </option>";
            		    			}
						print "</select>";
						?>
             	    	</TD>          		
<!--*********************************************************************************************-->				

            		<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Problema:</TD>
                	<TD colspan='3' width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
				<SELECT class='select' name='problema' id='idProblema' size=1>
						<?
	        		        	print "<option value= '-1'>Selecione o problema</option>";
	                			$query = "SELECT * from problemas where prob_status = 1 order by problema";
			                	$exec_prob = mysql_query($query);
        		    	    		while ($row_prob = mysql_fetch_array($exec_prob))
                				{
									print "<option value=".$row_prob['prob_id']."";
									if ($row_prob['prob_id'] == $row['problema']) {
											print " selected";
									}
									print " >".$row_prob['problema']." </option>";
            		    			}
						?>
				</SELECT >
			</TD>
				
        	</TR>
	       <TR>
            	    	<TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Descri��o:</TD>
                	<TD colspan='5' width="80%" align="left" bgcolor=<?print BODY_COLOR?>><TEXTAREA class="textarea" name="descricao" id="idDescricao"><?print nl2br($row['descricao']);?></textarea></b></TD>
        	</TR>
	       <TR>
			 <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Tipo:</TD>
			 <TD  width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
					<?
					 	$instituicao = $row['instituicao'];
						
						if ($instituicao != null)
						{
							$query = "SELECT * FROM instituicao WHERE inst_cod=$instituicao";    
						}
						else
						{
							$query = "SELECT * FROM instituicao WHERE inst_cod is null";   
						}
 						$resultado3 = mysql_query($query);
						$nomeinst = "";
						if (mysql_numrows($resultado3) > 0) 
						{
							$nomeinst=mysql_result($resultado3,0,1);
						}
						print "<select  class='select' name='instituicao' size='1'>";
						$query_todas="select * from instituicao order by inst_cod";
						$result_todas=mysql_query($query_todas);

						if ($nomeinst=="") 
						{
							print "<option value='2' selected>HOSPITAL</option>";	    
						}

						while($row_todas=mysql_fetch_array($result_todas))
						{
							if ($row_todas[inst_cod]==$instituicao) 
							{
							 	$s='selected ';
							}
							else
							{
								$s='';
							}
								print "<option value=".$row_todas[inst_cod]." $s>".$row_todas[inst_nome]."</option>";	
						} // while
						print "</select>";
				 	?>
	                </TD>


	                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Etiqueta do equipamento:</TD>
        	        <TD colspan='3' width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="etiq" id="idEtiqueta" value ="<?print $row['equipamento']?>" size="15"></TD>
        	</TR>
        	<TR>
                	<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Contato:</TD>
	                <TD  width="30%" align="left" bgcolor=<?print BODY_COLOR?>><input type='text' class='text' name='contato' id="idContato" value="<?print $row['contato'];?>"></TD>
    	            <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Ramal:</TD>
        	        <TD colspan='3' width="30%" align="left" bgcolor=<?print BODY_COLOR?>><input type='text' class='text' name='ramal' id="idRamal" value="<?print $row['telefone']?>"></TD>
	        </TR>
    	    <TR>
                	<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Local:</TD>
					<TD  width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
					<?
							print "<SELECT  class='select' name='local' id='idLocal' size=1>";
	        		        print "<option value= '-1'>Selecione o setor</option>";
	                		$query = "SELECT * from localizacao order by local";
			                $exec_loc = mysql_query($query);
        		    	    while ($row_loc = mysql_fetch_array($exec_loc))
                				{
									print "<option value=".$row_loc['loc_id']."";
										if ($row_loc['loc_id'] == $row['local']) {
											print " selected";
										}
									print " >".$row_loc['local']." </option>";
            		    		}
							print "</select>";
						?>
					</TD>
            	    <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Operador:</TD>
                	<TD colspan='3' width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
		                <?
            	            print "<SELECT class='select' name='operador' size=1>";
                	        print "<option value=".$row['user_id']." selected>".$row['nome']."</option>";
                    	    $query = "SELECT * from usuarios order by nome";
                        	$exec_oper = mysql_query($query);
        	                while ($row_oper = mysql_fetch_array($exec_oper))
            	            {
                                    print "<option value=".$row_oper['user_id'].">".$row_oper['nome']." ";
                                    print "</option>";
            	            }
                        ?>
                	        </SELECT>
                    </TD>
	        </TR>
    	    <?
				$antes = $row['status'];
				if ($row['status'] == 4) //Encerrado
	    	    {
            	    $antes = 4;
            ?>
            <TR>
                	<TD align="left" bgcolor=<?print TD_COLOR?>>Data de abertura:</TD>
                    <TD align="left" bgcolor=<?print BODY_COLOR?>><?print datab($row['data_abertura']);?></TD>
                    <TD align="left" bgcolor=<?print TD_COLOR?>>Data de encerramento:</TD>
                    <TD colspan='3' align="left" bgcolor=<?print BODY_COLOR?>><?print datab($row['data_fechamento']);?></TD>
          </TR>
          <?
		      }
        		else //chamado n�o encerrado
    		  {
	      ?>
          <TR>
        	      <TD align="left" bgcolor=<?print TD_COLOR?>>Data de abertura:</TD>
                  <TD colspan='5' align="left" bgcolor=<?print BODY_COLOR?>><?print datab($row['data_abertura']);?></TD>
		  </TR>
                
			
		<?
        	}
		        if ($linhas2!=0)
        		{
		            if ($linhas2==1)
        		        {
            			    $i=$linhas2-1;
		                }
        	        else
            		    {
                    	    $i=$linhas2-2;
		                }
        	        while ($i < $linhas2)
            		    {
          ?>
									<TR>
	                                	<TD align="left" bgcolor=<?print TD_COLOR?> valign="top">Assentamento <?print $i+1;?> de <?print $linhas2;?> por <?print mysql_result($resultado2,$i,4);?> em <?print datab(mysql_result($resultado2,$i,3));?></TD>
		                                <TD colspan='5' align="left" bgcolor=<?print BODY_COLOR?> valign="top"><?print nl2br(mysql_result($resultado2,$i,2));?></TD>
                    				</TR>
            	                <?
                			        $i++;
		                }//Fecha o while
                       
                } 
                //fecha o if
        						?>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Assentamento:</TD>
                <TD colspan='5' width="80%" align="left" bgcolor=<?print BODY_COLOR?>><TEXTAREA  class="textarea"  name="assentamento" id="idAssentamento">ocorrência encaminhada/alterada por <?echo $s_usuario;?></textarea> <?
                            if ($data_atend =="") { 
                                print "<input type='checkbox' value='ok' name='resposta' checked title='Desmarque essa op��o se esse assentamento n�o corresponder a uma primeira resposta do chamado'>1.� Resposta";
                            } ?>
                                
                            </TD>
        </TR>
<?
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
				print "<td colspan='5' bgcolor='".BODY_COLOR."'><a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$row['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela['img_nome']."</a>";
				print "<input type='checkbox' name='delImg[".$cont."]' value='".$rowTela['img_cod']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></TD>";
				print "</tr>";
			}

			
			//VERIFICA SE EXISTE UM CHAMADO ORIGEM
			$sqlPaiCall = "select * from ocodeps where dep_filho = ".$row['numero']." ";// or dep_filho=".$row['numero']."";
			$execPaiCall = mysql_query($sqlPaiCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlPaiCall);
 			$regPai = mysql_num_rows($execPaiCall);
 			$rowPai = mysql_fetch_array($execPaiCall);
 			if ($regPai > 0) {
				$headerLine = "<tr><td colspan='5'>V�nculos com outros chamados:</td></tr>"; 				
 				$imgPai = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='Chamado com v�nculos'>";
 			} else {
 				$imgPai = "";
 				$headerLine = "";
 			}


			//VERIFICA SE EXISTEM SUB-CHAMADOS
			$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." ";// or dep_filho=".$row['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
 			$regSub = mysql_num_rows($execSubCall);
 			if ($regSub > 0) {
				if ($headerLine=="" ) $headerLine = "<tr><td colspan='5'>V�nculos com outros chamados:</td></tr>"; 				
 				$imgSub = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='Chamado com v�nculos'>";
 			} else {
 				$imgSub = "";
 				//$headerLine = "";
 			}
			
			
			print $headerLine;
			
			if ($regPai>0){
				print "<tr>";
				print "<td colspan='5' bgcolor='".BODY_COLOR."'><img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='Chamado com v�nculos'>".
					"<a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$rowPai['dep_pai']."')\">".$rowPai['dep_pai']."</a>";
				print "<input type='checkbox' name='delPai'  value='".$rowPai['dep_pai']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o v�nculo'></TD>";
				print "</tr>";
			}
			
			$contSub = 0;
			while ($rowSub = mysql_fetch_array($execSubCall)) {
				$contSub++;
				print "<tr>";
				print "<td colspan='5' bgcolor='".BODY_COLOR."'><img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='Chamado com v�nculos'>".
					"<a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$rowSub['dep_filho']."')\">".$rowSub['dep_filho']."</a>";
				print "<input type='checkbox' name='delSub[".$contSub."]' value='".$rowSub['dep_filho']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o v�nculo'></TD>";
				print "</tr>";
			
			}

?>        
		
		
		<TR>
	            <TD colspan='3' align="center" width="50%" bgcolor=<?print BODY_COLOR?>>
			<?
				print "<input type='hidden' name='data_gravada' value='".date("Y-m-d H:i:s")."'>";				
			
			?>		
					
					<input type="submit" value="  Ok  " name="ok">
	                <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD colspan='3' align="center" width="25%" bgcolor=<?print BODY_COLOR?>> 
					<INPUT type="button" value="Cancelar" name="desloca" ONCLICK="javascript:history.back()">
				</TD>
				
         </TR>
         <?
         	if ($rodou == "sim") {
				$depois = $status;
				$erro="nao";
                if ($erro = "nao") {
					$data = datam($hoje2);
					$responsavel = $s_uid;
					$queryA = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel) values ($numero,'".noHtml($assentamento)."', '$data', $responsavel)";
					$resultado3 = mysql_query($queryA);
			
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
			
			
			
					for ($j=1; $j<=$cont; $j++) {
						if ($_POST['delImg'][$j]){
							$qryDel = "DELETE FROM imagens WHERE img_cod = ".$_POST['delImg'][$j]."";
							$execDel = mysql_query($qryDel) or die ("N�O FOI POSS�VEL EXCLUIR A IMAGEM!");
						}
						
					}
					
					if ($_POST['delPai']){
						$qryDel = "DELETE FROM ocodeps WHERE dep_filho= ".$numero." and dep_pai = ".$_POST['delPai']."";
						$execDel = mysql_query($qryDel) or die ("N�O FOI POSS�VEL EXCLUIR O V�NCULO!".$qryDel);
					}
					
					for ($j=1; $j<=$contSub; $j++) {
						if ($_POST['delSub'][$j]){
							$qryDel = "DELETE FROM ocodeps WHERE dep_pai= ".$numero." and dep_filho = ".$_POST['delSub'][$j]."";
							$execDel = mysql_query($qryDel) or die ("N�O FOI POSS�VEL EXCLUIR O V�NCULO!".$qryDel);
						}
						
					}
					
					
					if ($antes != $depois) {
					   //$status!=1 and 
						if (($data_atend==null) and ($status!=4) and ($resposta == "ok")) { //para verificar se j� foi setada a data do inicio do atendimento. //Se eu incluir um assentamento seto a data de atendimento
							$query = "UPDATE ocorrencias SET operador='$operador', problema = $problema, instituicao='$institui', equipamento = $etiq, sistema = '$sistema', local=$local, data_fechamento=NULL, status=$status, data_atendimento='$data',descricao='".noHtml($descricao)."', contato='".noHtml($contato)."', telefone='$ramal' WHERE numero=$numero";
							$resultado4 = mysql_query($query);
						} else {							
							$query = "UPDATE ocorrencias SET operador='$operador', problema = $problema , instituicao='$institui', equipamento = $etiq, sistema = '$sistema', local=$local, data_fechamento=NULL, status=$status, descricao='".noHtml($descricao)."', contato='".noHtml($contato)."', telefone='$ramal' WHERE numero=$numero";
							$resultado4 = mysql_query($query);
							
						}
					} else {
						$query = "UPDATE ocorrencias SET operador='$operador', problema = $problema, instituicao='$institui', equipamento = $etiq, sistema = '$sistema', local=$local, status=$status,descricao='".noHtml($descricao)."', contato='".noHtml($contato)."', telefone='$ramal' WHERE numero=$numero";
						$resultado4 = mysql_query($query);
						
					}		

					if (($resultado3==0) OR ($resultado4 == 0)) {
						$aviso = "ERRO DE ACESSO. Um erro ocorreu ao tentar alterar ocorrência no sistema. - $query";
					} else {
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
										
										
						$aviso = "ocorrência alterada com sucesso!";
					}
                        
						
						}//fecha if erro=nao
					print "<script>mensagem('".$aviso."'); redirect('mostra_consulta.php?numero=".$numero."');</script>";
                
				}//fecha if rodou=sim
        ?>

</TABLE>

</FORM>
</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idStatus','COMBO','Status',1);
		if (ok) var ok = validaForm('idProblema','COMBO','Problema',1);
		if (ok) var ok = validaForm('idArea','COMBO','�rea',1);
		if (ok) var ok = validaForm('idDescricao','','Descri��o',1);
//		if (ok) var ok = validaForm('idEtiqueta','INTEIRO','Etiqueta',0);
		if (ok) var ok = validaForm('idContato','','Contato',1);
//		if (ok) var ok = validaForm('idRamal','INTEIRO','Ramal',1);
		if (ok) var ok = validaForm('idLocal','COMBO','Local',1);
		if (ok) var ok = validaForm('idAssentamento','','Assentamento',1);
		
		return ok;
	}	
	function loadDoc() {
  		var xhttp;
  		if (window.XMLHttpRequest) {
    			// code for modern browsers
    			xhttp = new XMLHttpRequest();
    		} else {
    			// code for IE6, IE5
    			xhttp = new ActiveXObject("Microsoft.XMLHTTP");
  		}
  		xhttp.onreadystatechange = function() {
    			if (xhttp.readyState == 4 && xhttp.status == 200) {
    		  		document.getElementById("idProblema").innerHTML = xhttp.responseText;
    			}
  		};
  		xhttp.open("GET", "ajax_info.txt", true);
 	 	xhttp.send();
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
</HTML>
