<!DOCTYPE html>
<html>
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

		$conec = new conexao;
		$PDO=$conec->conectaPDO();



$hoje = date("Y-m-d H:i:s");



     $cor=TAB_COLOR;
     $cor1=TD_COLOR;
     $percLimit = 20; //Toler�ncia em percentual
     $imgSlaR = 'sla1.png'; 
     $imgSlaS = 'checked.png'; 

	$dtS = new dateOpers; //objeto para Solu��o
    $dtR = new dateOpers; //objeto para Resposta
     
     
     
?>
    <title>OCOMON</title>
    <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">

    <link href="includes/css/bootstrap.css" rel="stylesheet">
    <link rel=stylesheet type='text/css' href='includes/css/dashboard.css'>
    <link rel=stylesheet type='text/css' href='includes/css/estilos.css'>
    <link rel=stylesheet type='text/css' href='includes/css/common.css'>
    <link rel=stylesheet type='text/css' href='includes/css/menu.css'>

    <script language="JavaScript" src="includes/javascript/calendar1.js"></script>

    <style type="text/css">
        body{background:url(includes/imgs/bg.png) repeat; padding-top:0;}
    </style>
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
<body>

<nav class="navbar navbar-fixed-top green border">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <a href="#"><img  class="mt-5" src="includes/imgs/logo.png"></a>
            </div>

            <div class="col-sm-6">
                <div style="float:right; margin-top: 25px">
                    <a href='<?php print $commonPath?>logout.php' class="logoff">
                        <?php print $logInfo?> <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <?php include("menuChamado.php"); ?>

        <div class="main">

                    <div class="row">
                        <div class="col-md-6">
                            <h1 class="aw-page-title">Ocomon - M�dulo de ocorrências</h1>
                        </div>
                        <div class="col-md-6 text-right">
                            <p><?php echo transvars(date ("l d/m/Y H:i"),$TRANS_WEEK) ?></p>
                        </div>
                    </div>

<?

        if ($rodou == "sim")
        {

		$query_ini = $QRY["ocorrencias_full_ini"];				
                $query = "";

                if (!empty($numero_inicial) )
                        $query.=" and o.numero='$numero_inicial' ";

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

/*              if ($ordem == "data")
                        if ($tipo_data == "abertura")
                                $query.="  ORDER BY data_abertura";
                        else
                                $query.="  ORDER BY data_fechamento";
                else*/

				if (strlen($query)>0) {
					$query_ini.=" WHERE o.numero = o.numero ".$query;
				}

                $resultado = $PDO->query($query_ini) or die('ERRO NA TENTATIVA DE RODAR A CONSULTA! '.$query_ini);
                $linhas = $resultado->rowCount();

                $query_ini.=" ORDER BY o.numero DESC LIMIT 40";

				$resultado = $PDO->query($query_ini) or die('ERRO NA TENTATIVA DE RODAR A CONSULTA! '.$query_ini);

				if ($linhas==0)
				{
                    $aviso = "Nenhuma ocorrência localizada.";
                    $origem = "consultar.php";
                    session_register("aviso");
                    session_register("origem");
                    //echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                    print "<script>alert('Nenhuma ocorrência localizada!'); history.back();</script>";
				}

				$titulo = ($linhas>1) ? "Foram encontradas $linhas ocorrências." : "Foi encontrada somente 1 ocorrência.";

				if ($linhas > 40) $titulo .= " (Mostrando apenas os primeiros 40 resultados.)";
                
?>

                <div class="alert alert-info">
                    <?php echo $titulo ?>
                </div>

        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 class="panel-title">Consulta de ocorrências:</h4>
            </div>

            <table class='table'>
				
                <tr class='header'>
                    <td>N�mero</td>
                    <td>Problema</td>
                    <td width="180">Contato / Operador</td>
                    <td>Local</td>
                    <td>Data <?php echo $tipo_data?></td>
                    <td>Status</td>
                    <td>RESP.</td>
                    <td>SOLUC.</td>
				</tr>

				<?php
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
						  $limite = 250;
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
                           
?>

                    <tr class='<?php echo $trClass?>' id='linha<?php echo $j?>' onMouseOver="destaca('linha<?php echo $j?>');" onMouseOut="libera('linha<?php echo $j?>');"  onMouseDown="marca('linha<?php echo $j?>');">
<?php
			
/*			$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." or dep_filho=".$row['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			if ($regSub > 0) {
				$imgSub = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='Chamado com v�nculos'>";
			} else
				$imgSub = "";*/
			$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." or dep_filho=".$row['numero']."";
			$execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
			$regSub = $execSubCall->rowCount();
			if ($regSub > 0) {
				#� CHAMADO PAI?
				$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
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
					$imgSub = "<img src='includes/icons/view_tree_red.png' width='16' height='16' title='Chamado com v�nculos pendentes'>";
				} else
					$imgSub =  "<img src='includes/icons/view_tree_green.png' width='16' height='16' title='Chamado com v�nculos mas sem pend�ncias'>";
			} else
				$imgSub = "";
			
			?>
					<td><a href="#" onClick="javascript: popup_alerta('mostra_consulta.php?popup=true&numero=<?php echo $row['numero']?>')"><?php echo $row['numero']?></a><?php echo $imgSub?></td>
				    <td><a href="#" onClick="javascript: popup_alerta('mostra_consulta.php?popup=true&numero=<?php echo $row['numero']?>')"><?php echo $row['problema']?></a></td>
				    <td><a href="#" onClick="javascript: popup_alerta('mostra_consulta.php?popup=true&numero=<?php echo $row['numero']?>')"><b><?php echo $row['contato']?></b><br><?php echo $row['nome']?></a></td>
				    <td><a href="#" onClick="javascript: popup_alerta('mostra_consulta.php?popup=true&numero=<?php echo $row['numero']?>')"><b><?php echo $row['setor']?></b><br><?php echo $texto?></a></td>
				    <?php if ($tipo_data == "abertura"){?>
						<td><a href="#" onClick= "javascript: popup_alerta('mostra_consulta.php?popup=true&numero=<?php echo $row['numero']?>')"><?php echo $row['data_abertura']?></a></td>
                    <?php }else{ ?>
						<td><a href="#" onClick= "javascript: popup_alerta('mostra_consulta.php?popup=true&numero=<?php echo $row['numero']?>')"><?php echo $row['data_fechamento']?></a></td>
                    <?php } ?>
				    <td><a href="#" onClick= "javascript: popup_alerta('mostra_consulta.php?popup=true&numero=<?php echo $row['numero'] ?>')"><?php echo $row['chamado_status']?></a></td>
				    <td align='center'><a href="#" onClick= "javascript: popup_alerta('mostra_consulta.php?popup=true&numero=<?php echo $row['numero']?>')"><img height='14' width='14' border=0 src='includes/imgs/imgs/<?php echo $imgSlaR ?>'></a></td>
				    <td align='center'><a href="#" onClick= "javascript: popup_alerta('mostra_consulta.php?popup=true&numero=<?php echo $row['numero']?>')"><img height='14' width='14' border=0 src='includes/imgs/<?php echo $imgSlaS ?>'></a></td>
                </tr>
            <?php
                } //while
                ?>
                </TABLE>

        </div>
<?php
        }
?>





</div>

    </div>
       <a href="consultaChamado.php">Voltar</a>

</div>



</body>
</html>
