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

<HTML>
<HEAD>
</head>

<BODY bgcolor=<?print BODY_COLOR?>>

<?	
	$auth = new auth;
	if ($popup) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

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

		if ($s_nivel == 1) $linkEdita = "<br><b><a href='altera_dados_ocorrencia.php?numero=".$numero."'>Editar ocorrência como admin:</a></b><br>"; else
			$linkEdita = "<br><b>Editar ocorrência:</b><br>";			
	
	
	print $linkEdita;
?>

	<FORM method="POST" action="<?$PHP_SELF?>" ENCTYPE="multipart/form-data" onSubmit="return valida()">

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
							print "</select>";
						?>
					</TD>					
        	
			</TR>
<!--*********************************************************************************************-->				
        	<TR>
            		<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Problema:</TD>
	                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
						<?
							print "<SELECT class='select' name='problema' id='idProblema' size=1>";
	        		        print "<option value= '-1'>Selecione o problema</option>";
	                		$query = "SELECT * from problemas order by problema";
			                $exec_prob = mysql_query($query);
        		    	    while ($row_prob = mysql_fetch_array($exec_prob))
                				{
									print "<option value=".$row_prob['prob_id']."";
										if ($row_prob['prob_id'] == $row['problema']) {
											print " selected";
										}
									print " >".$row_prob['problema']." </option>";
            		    		}
							print "</select>";
						?>
					</TD>
				
<!--*********************************************************************************************-->				
                	<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>�rea respons�vel:</TD>
	                <TD colspan='3' width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
						<?
							print "<SELECT class='select' name='sistema' id='idArea'>";
	        		        print "<option value= '-1'>Selecione a �rea</option>";
	                		$query = "SELECT * from sistemas order by sistema";
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
          		
        	</TR>
	        <TR>
					<TD width="20%" align="left" bgcolor="<?print TD_COLOR?>" valign="top">Descri��o:</TD>
                	<TD colspan='5' width="80%" align="left" bgcolor=<?print BODY_COLOR?>><b><?print nl2br($row['descricao']);?></b></TD>
        	</TR>
	        <TR>
				 <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Unidade:</TD>
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
						print "<select  class='select' name='institui' size=1>";
						$query_todas="select * from instituicao order by inst_cod";
						$result_todas=mysql_query($query_todas);

						if ($nomeinst=="") 
						{
							print "<option value='' selected> </option>";	    
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
        	        <TD colspan='3' width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text"  class='text' name="etiq" id="idEtiqueta" value ="<?print $row['equipamento'];?>" size="15"></TD>
        	</TR>
        	<TR>
                	<TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Contato:</TD>
	                <TD  width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row['contato'];?></TD>
    	            <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Ramal:</TD>
        	        <TD colspan='3' width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print $row['telefone'];?></TD>
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
            	    <TD width="20%" align="left" bgcolor='<?print TD_COLOR?>'>Operador:</TD>
                	<TD colspan='3' width="30%" align="left" bgcolor='<?print BODY_COLOR?>'>
		                <?
            	            print "<SELECT class='select' name='operador'>";
                	        //print "<option value=".$row['user_id']." selected>".$row['nome']."</option>";
                    	    $query = "SELECT u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and a.sis_atende=1 and u.nivel not in (3,4,5) order by login";
                        	$exec_oper = mysql_query($query);
        	                while ($row_oper = mysql_fetch_array($exec_oper))
            	            {
								print "<option value=".$row_oper['user_id']." ";
								if ($row_oper['user_id']== $_SESSION['s_uid'])
									print " selected";
								print ">".$row_oper['nome']."</option>";
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
                <TD colspan='5' width="80%" align="left" bgcolor=<?print BODY_COLOR?>><TEXTAREA id="idAssentamento" class="textarea"  name="assentamento">ocorrência encaminhada/alterada por <?echo $s_usuario;?></textarea> 
						<?
                            //if ($data_atend =="") { 
                                //print "<input type='checkbox' value='ok' name='resposta' checked title='Desmarque essa op��o se esse assentamento n�o corresponder a uma primeira resposta do chamado'>1.� Resposta";
                            //} 
						?>
                                
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
				print "<td colspan='5' bgcolor='".BODY_COLOR."'><a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$row['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela['img_nome']."</a></TD>";
				print "</tr>";
			}
			
			
			print "<tr>";			
				print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Anexar imagem:</TD>";
				print "<TD colspan='5' align='left' bgcolor=".BODY_COLOR."><INPUT type='file' class='text' name='img' id='idImg'></TD>";
			print "</tr>";
			
			
			$qrymail = "SELECT u.*, a.*,o.* from usuarios u, sistemas a, ocorrencias o where ".
						"u.AREA = a.sis_id and o.aberto_por = u.user_id and o.numero = ".$numero."";
			$execmail = mysql_query($qrymail);
			$rowmail = mysql_fetch_array($execmail);
			if ($rowmail['sis_atende']==0){
				$habilita = "";
			} else $habilita = "disabled";
			
			print "<tr><td bgcolor='".TD_COLOR."'>Enviar e-mail para:</td>".
					"<td colspan='2'><input type='checkbox' value='ok' name='mailAR' title='Envia email para a �rea selecionada para esse chamado'>�rea Respons�vel&nbsp;&nbsp;".
									"<input type='checkbox' value='ok' name='mailOP' title='Envia e-mail para o operador selecionado no chamado'>Operador&nbsp;&nbsp;".
									"<input type='checkbox' value='ok' name='mailUS' title='teste' ".$habilita."><a title='Essa op��o s� fica habilitada para chamados abertos pelo pr�prio usu�rio'>Usu�rio</a></td>".
					"</tr>";
			
			print "<tr><td colspan='3'>&nbsp;</td></tr>";					
			print "<tr><td colspan='3' align='center'>";
                            if ($data_atend =="") { 
                                print "<input type='checkbox' value='ok' name='resposta' checked title='Desmarque essa op��o se esse assentamento n�o corresponder a uma primeira resposta do chamado'>1.� Resposta";
                            } 
			print "</td><td colspan='3'></td></tr>";

		?>
		<tr>
		<TD colspan='3' align="center" width="50%" bgcolor=<?print BODY_COLOR?>>
	                <input type="hidden" name="rodou" value="sim">
			<?
				print "<input type='hidden' name='data_gravada' value='".date("Y-m-d H:i:s")."'>";				
			?>
					<input type="submit" value="  Ok  " name="ok">
					<input type="hidden" name="abertopor" value="<?print $rowmail['user_id']?>">
                </TD>
                <TD colspan='3' align="center" width="25%" bgcolor=<?print BODY_COLOR?>> 
					<INPUT type="reset" value="Cancelar" onClick="javascript:history.back()" name="cancelar">
				</TD>
				
         </TR>
         <?
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
								
								
								$sqlMailUs = "select * from usuarios where user_id = ".$_POST['abertopor']."";
								$execMailUs = mysql_query($sqlMailUs) or die('N�O FOI POSS�VEL ACESSAR A BASE DE USU�RIOS PARA O ENVIO DE EMAIL!');
								$rowMailUs = mysql_fetch_array($execMailUs);
								
								$qryresposta = "select u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and u.user_id = ".$_SESSION['s_uid'].""; 
								$execresposta = mysql_query($qryresposta) or die ('N�O FOI POSS�VEL IDENTIFICAR O EMAIL PARA RESPOSTA!');
								$rowresposta = mysql_fetch_array($execresposta);
								
/*								$flag = mail_user_assentamento($rowMailUs['email'], $rowresposta['sis_email'], $rowMailUs['nome'],$_GET['numero'],
														$assentamento,OCOMON_SITE);*/
								send_mail($event, $rowMailUs['email'], $rowconf, $rowmsg, $VARS);
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

</TABLE>

</FORM>
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


-->	
	
</script>

</html>
