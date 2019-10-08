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


$hoje = date("Y-m-d H:i:s");



     $cor=TAB_COLOR;
     $cor1=TD_COLOR;
     $percLimit = 20; //Toler�ncia em percentual
     $imgSlaR = 'sla1.png'; 
     $imgSlaS = 'checked.png'; 

	$dtS = new dateOpers; //objeto para Solu��o
    $dtR = new dateOpers; //objeto para Resposta
     
     
     
?>
<HTML>
<head>
<script type="text/javascript">
	 function popup(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+100, window.parent.screenY+100);	  	
		return false
     }

	 function popup_alerta(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'_blank','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);	  	
		return false
     }
     </script>

</head>
<BODY>
<?
	$auth = new auth;
	if ($popup) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
?>

<BR>
<B>Consulta de ocorrências</B>
<BR>

<?

         if ($rodou == "sim")
        {
                

		$query_ini = $QRY["ocorrencias_full_ini"];				
                $query = "";
				
                if (!empty($numero_inicial) )
                        $query.=" and o.numero>='$numero_inicial' ";

                if (!empty($numero_final))
                        $query.=" and o.numero<='$numero_final' ";

                if ($problema != -1)
                {
                        if (!empty($problema) and $problema != -1)
                        {
                                $query.=" and o.problema=$problema ";
                        }
                }

                if (!empty($descricao))
                {
                        $query.=" and o.descricao LIKE '%$descricao%' ";
                }
				
 				if ($instituicao != -1)
                {
                        if (!empty($instituicao) and $instituicao != -1)
                        {
                                $query.=" and o.instituicao=$instituicao ";
                        }
                }
                if (!empty($equipamento))
                {
                        $query.=" and o.equipamento in (".$equipamento.") ";
                }

                if (!empty($sistema) and $sistema != -1)
                {
                        $query.=" and o.sistema=$sistema ";
                }


                if (!empty($contato))
                {
                        $query.=" and o.contato LIKE '%$contato%' ";
                }


                if (!empty($telefone))
                {
                        $query.=" and o.telefone='$telefone' ";
                }


                if (!empty($local) and $local != -1)
                {
                        $query.=" and o.local=$local ";
                }

                if (!empty($operador) and $operador != -1)
                {
                        //$query.=" and o.operador like '%$operador%' ";
						 
						if($opAbertura)
							$query.=" and o.aberto_por = $operador "; else
							$query.=" and o.operador = $operador ";
                }

                //####################################################################

                if ($tipo_data=="abertura")
                {
                        if (!empty($data_inicial) )
                        {
                                $data_inicial = str_replace("-","/",$data_inicial);
								$data_inicial = substr(datam($data_inicial),0,10);
                                $data_inicial.=" 00:00:01";
                                $query.=" and o.data_abertura>='$data_inicial' ";
                        }

                        if (!empty($data_final))
                        {
                                $data_final = str_replace("-","/",$data_final);
								$data_final = substr(datam($data_final),0,10);
                                $data_final.=" 23:59:59";
                                $query.=" and o.data_abertura<='$data_final'";
                        }

                }
                else
                {
                        if (!empty($data_inicial) )
                        {
                                $data_inicial = str_replace("-","/",$data_inicial);
                                
								$data_inicial = substr(datam($data_inicial),0,10);
                                $data_inicial.=" 00:00:01";
                                $query.=" and o.data_fechamento>='$data_inicial' ";
                        }

                        if (!empty($data_final))
                        {
                                $data_final = str_replace("-","/",$data_final);
                                
								$data_final = substr(datam($data_final),0,10);
                                $data_final.=" 23:59:59";
                                $query.=" and o.data_fechamento<='$data_final'";
                        }

                }


                //###########################################################################

                if ($status == "Em aberto")
                {
                        $query.=" and o.status not in (4,12,18)";
                }
                else
                if ($status !=15) {
                        $query.=" and o.status=$status ";
                }

                if ($ordem == "data")
                        if ($tipo_data == "abertura")
                                $query.="  ORDER BY data_abertura";
                        else
                                $query.="  ORDER BY data_fechamento";
                else
                        $query.=" ORDER BY $ordem";


				if (strlen($query)>0) {
					$query_ini.=" WHERE o.numero = o.numero ".$query;
				}	

				$resultado = mysql_query($query_ini) or die('ERRO NA TENTATIVA DE RODAR A CONSULTA! '.$query_ini);
				$linhas = mysql_numrows($resultado);
                        //print $query; //COMENTAR
                        //exit;  //COMENTAR
                        
				if ($linhas==0)
				{
								$aviso = "Nenhuma ocorrência localizada.";
								$origem = "consultar.php";
								session_register("aviso");
								session_register("origem");
								//echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
								print "<script>alert('Nenhuma ocorrência localizada!'); history.back();</script>";
				} 
                


                print "<table>";
                if ($linhas>1) {
                        print "<TR><TD><B>Foram encontradas $linhas ocorrências.</B></TD></TR>";
				}
                else
                        print "<TR><TD><B>Foi encontrada somente 1 ocorrência.</B></TD></TR>";
                
				print "</table>";
                //print "<TD>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'";
				
				$valign = " valign=top ";
                print "<TR bgcolor='#DDDCC5'>";
				echo "<TD $valign>N�mero</TD>";
				echo "<TD $valign>Problema</TD>";
				echo "<TD $valign>Contato<BR>Operador</TD>";
				echo "<TD $valign>Local</TD>";
				echo "<TD $valign>Data de abertura</TD>";
				echo "<TD $valign>Data de fechamento</TD>";
				echo "<TD $valign>Status</TD>";
				echo "<TD $valign>RESP.</TD>";
				echo "<TD $valign>SOLUC.</TD>";                
				echo "</TR>";
				
				$i=0;
                $j=2;
                while ($row = mysql_fetch_array($resultado))
                {
						$i++;
                        if ($j % 2)
                        {
                                $color =  BODY_COLOR;
								$trClass= "lin_par";
                        }
                        else
                        {
                                $color = white;
								$trClass = "lin_impar";
                        }
						
                        if ($row['status']==4 and $destaque) { $color =  "#F1FD4A";}
						//if (($row['status'] != 4) and ($row['status'] != 14) and ($row['status'] != 18)) { $calcula = true;} else $calcula = false;
                        if (($row['status'] == 1)) { $calcula = true;} else $calcula = false;
						$j++;

                          $texto = trim( $row['descricao']);
						  $limite = 220;
                          if (strlen($texto)>$limite){
                               $texto = substr($texto,0,($limite-3))."...";
                          };


					if ($calcula) {
						
						// if (array_key_exists($row['cod_area'],$H_horarios)){  //verifica se o c�digo da �rea possui carga hor�ria definida no arquivo config.inc.php
							// $areaChamado = $row['cod_area']; //Recebe o valor da �rea de atendimento do chamado
						// } else $areaChamado = 1; //Carga hor�ria default definida no arquivo config.inc.php
						$areaChamado=testaArea($areaChamado,$row['cod_area'],$H_horarios);
						
                            //------------------------------------------------
							$dtR->setData1($row['data_abertura']);
							if ($row['data_atendimento'] =="") {
                                 $dtR->setData2($hoje) ;  
                            } else {
                                    $dtR->setData2($row['data_atendimento']) ;   
                           } 
                           $dtR->tempo_valido($dtR->data1,$dtR->data2,$H_horarios[$areaChamado][0],$H_horarios[$areaChamado][1],$H_horarios[$areaChamado][2],$H_horarios[$areaChamado][3],"H");
                           $diffR = $dtR->tValido;
                           $diff2R = $dtR->diff["hValido"];
                           $segundosR = $dtR->diff["sValido"]; //segundos v�lidos
                            //------------------------------------------------
							
                            $diff = data_diff($row['data_abertura'],$hoje);
                            $sep = explode ("dias",$diff);
                            if ($sep[0]>20) { //Se o chamado estiver aberto a mais de 20 dias o tempo � mostrado em dias para n�o ficar muito pesado.
                                $diff = $sep[0]." dias";
                                $segundosS = ($sep[0]*86400);
                            }  else {
                               $dtS->setData1($row['data_abertura']);
                                if ($row['data_fechamento'] =="") {
                                     $dtS->setData2($hoje) ;  
                                } else {
                                    $dtS->setData2($row['data_fechamento']) ;   
                               } 
                               $dtS->tempo_valido($dtS->data1,$dtS->data2,$H_horarios[$areaChamado][0],$H_horarios[$areaChamado][1],$H_horarios[$areaChamado][2],$H_horarios[$areaChamado][3],"H");
                               $diffS = $dtS->tValido;
                               $diff2S = $dtS->diff["hValido"];
                               $segundosS = $dtS->diff["sValido"]; //segundos v�lidos
                            }

                         //------------------------------------
                        if ($row['data_atendimento'] ==""){//Controle das bolinhas de SLA de Resposta 
                            if ($segundosR<=($row['resposta']*60)){
                                $imgSlaR = 'sla1.png';
                            } else if ($segundosR  <=(($row['resposta']*60) + (($row['resposta']*60) *$percLimit/100)) ){
                                 $imgSlaR = 'sla2.png';
                            } else {
                                $imgSlaR = 'sla3.png';
                            }
                        } else 
                            $imgSlaR = 'checked.png';
                      //-----------------------------------------
                        
                    $sla_tempo = $row['tempo'];
                    if (($sla_tempo !="") && ($row['data_fechamento']=="")) { //Controle das bolinhas de SLA de solu��o
                        if ($segundosS <= ($row['tempo']*60)){
                            $imgSlaS = 'sla1.png';
                        } else if ($segundosS  <=(($row['tempo']*60) + (($row['tempo']*60) *$percLimit/100)) ){
                            $imgSlaS = 'sla2.png';
                        } else 
                            $imgSlaS = 'sla3.png';
                    } else
                        $imgSlaS = 'checked.png';
                     //-----------------------------------------------------   
                           
                           
                 } else {

						$imgSlaR = 'checked.png';
						$imgSlaS = 'checked.png';
						
				}
                           


                          
                        //print "<tr class='".$trClass."' id='linha".$i."' onMouseOver=\"destaca('linha".$i."');\" onMouseOut=\"libera('linha".$i."');\">";
                        print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
			
			
/*			$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." or dep_filho=".$row['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			if ($regSub > 0) {
				$imgSub = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='Chamado com v�nculos'>";
			} else
				$imgSub = "";*/
			$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." or dep_filho=".$row['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			if ($regSub > 0) {
				#� CHAMADO PAI?
				$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
				$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
				$regSub = mysql_num_rows($execSubCall);
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
					$imgSub = "<img src='".ICONS_PATH."view_tree_red.png' width='16' height='16' title='Chamado com v�nculos pendentes'>";
				} else
					$imgSub =  "<img src='".ICONS_PATH."view_tree_green.png' width='16' height='16' title='Chamado com v�nculos mas sem pend�ncias'>";
			} else
				$imgSub = "";
			
			
			
			print "<TD $valign><a onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><font color='blue'>".$row['numero']."</font></a>".$imgSub."</TD>";
                        print "<TD $valign>".$row['problema']."</TD>";						
                        print "<TD $valign><b>".$row['contato']."</b><br>".$row['nome']."</TD>";
                        print "<TD $valign><b>".$row['setor']."</b><br>".$texto."</TD>";
                //        if ($tipo_data == "abertura")
							print "<TD $valign>".$row['data_abertura']."</TD>";// else
							print "<TD $valign>".$row['data_fechamento']."</TD>";
							
                        print "<TD $valign><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\">".$row['chamado_status']."</a></TD>";
                        print "<TD $valign align='center'><a onClick=\"javascript:popup('sla_popup.php?sla=r')\"><img height='14' width='14' src='../../includes/imgs/imgs/".$imgSlaR."'></a></TD>";
                        print "<TD $valign align='center'><a onClick=\"javascript:popup('sla_popup.php?sla=s')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaS."'></a></TD>";
                        print "</TR>";
                
                } //while
                print "</TABLE>";
                //print "<br>".$query;
        }
?>


</body>
</html>
