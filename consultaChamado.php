<!DOCTYPE html>
<html>
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

	$s_page_ocomon = "consultaChamado.php";
	session_register("s_page_ocomon");		
    $conec = new conexao;
    $PDO = $conec->conectaPDO();


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

                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title">Consulta de ocorrências: <?php echo $subCallMsg?></h4>
                    </div>

                    <form method="POST"  name="form1" action='mostra_resultado_consulta.php' onSubmit="return valida()">

                        <div class="panel-body">

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="">C�digo do chamado:</label>
                                        <input type="text" class='form-control'  name="numero_inicial" id="idNumeroInicial" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="">Setor:</label>
                                        <select class='form-control' name='local' id='idlocal' size=1>
                                            <option value='' selected>-  Selecione um local -</option>
                                            <?php

                                                $query = "SELECT l .  * , r.reit_nome, pr.prior_nivel AS prioridade, d.dom_desc AS dominio, pred.pred_desc as predio
                                                            FROM localizacao AS l
                                                            LEFT  JOIN reitorias AS r ON r.reit_cod = l.loc_reitoria
                                                            LEFT  JOIN prioridades AS pr ON pr.prior_cod = l.loc_prior
                                                            LEFT  JOIN dominios AS d ON d.dom_cod = l.loc_dominio
                                                            LEFT JOIN predios as pred on pred.pred_cod = l.loc_predio
                                                            WHERE loc_status not in (0) 
                                                            ORDER  BY LOCAL ";

                                                $resultado = $PDO->query($query);
                                                $linhas = $resultado->rowCount();
                                                $i=0;
                                                while ($aux=$resultado->fetch(PDO::FETCH_BOTH))
                                                {
                                                    ?>
                                                    <option value="<?print $aux[0];?>"><?print $aux[1];?></option>
                                                    <?php
                                                    $i++;
                                                }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="">Nome:</label>
                                        <INPUT type="text" class='form-control' name="contato" id="idContato">

                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="">Status</label>
                                        <select class='form-control' name='status' size=1>
                                            <option value='Em aberto'>Em aberto</option>
                                            <?php
                                            $query = "SELECT * from status order by status";
                                            $resultado = $PDO->query($query);
                                            $linhas = $resultado->rowCount();
                                            $i=0;
                                            while ($aux=$resultado->fetch(PDO::FETCH_BOTH))
                                            {
                                                ?>
                                                <option value="<?print mysql_result($resultado,$i,0);?>"<?
                                                if ($aux[0]==15)/*Todos*/ {
                                                    print " selected>";
                                                } else print ">"?>
                                                <?print $aux[1];?>
                                                </option>
                                                <?php
                                                $i++;
                                            }?>
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="form-actions right" style="padding: 10px; margin-top: 0">

                            <input type="hidden" name="rodou" value="sim">
                            <input type="hidden" name="ordem" value="data">

                            <button type="submit" class="btn btn-info btn-sm" name="ok">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                            <input type="button" class="btn btn-success btn-sm" value="Abrir Novo Chamado" name="button_abrirchamado" onClick="window.location='abrirChamado.php'">
                            <button type="button" class="btn btn-danger btn-sm" name='fecha' value='Fechar' onClick="javascript:window.close()">Cancelar</button>

                        </div>
                    </form>
                </div>

                <?php

                $query_ini = $QRY["ocorrencias_full_ini"];
                $query_ini.="  ORDER BY numero desc LIMIT 20 ";

                $resultado = $PDO->query($query_ini) or die('ERRO NA TENTATIVA DE RODAR A CONSULTA! '.$query_ini);
                $linhas = $resultado->rowCount();
                //print $query; //COMENTAR
                //exit;  //COMENTAR

                if ($linhas==0)
                {
                    $aviso = "Nenhuma ocorrência localizada.";
                    $origem = "consultaChamado.php";
                    session_register("aviso");
                    session_register("origem");
                }
                $titulo = ($linhas>1) ? "As �ltimas $linhas ocorrências cadastradas." : "Foi encontrada somente 1 ocorrência.";
                ?>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <?php echo $titulo ?>
                        </h4>
                    </div>


                    <table class='table'>



                        <tr class='header'>
                            <td>N�mero</td>
                            <td>Problema</td>
                            <td width="150">Contato / Operador</td>
                            <td>Local</td>
                            <td>Data <?php echo$tipo_data ?></td>
                            <td>Status</td>
                            <td>RESP.</td>
                            <td>SOLUC.</td>
                        </tr>

                        <?php
                        $i=0;
                        $j=2;
                        while ($row = $resultado->fetch(PDO::FETCH_BOTH))
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
                            $execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
                            $regSub = $execSubCall->rowCount();
                            if ($regSub > 0) {
                                #� CHAMADO PAI?
                                $sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
                                $execSubCall = $PDO->query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
                                $regSub = $execSubCall->rowCount();
                                $comDeps = false;
                                while ($rowSubPai =$execSubCall->fetch(PDO::FETCH_BOTH)){
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



                            print "<TD $valign><a href=\"#\" onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\">".$row['numero']."</a>".$imgSub."</TD>";
                            print "<TD $valign><a href=\"#\" onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\">".$row['problema']."</a></TD>";
                            print "<TD $valign><a href=\"#\" onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><b>".$row['contato']."</b><br>".$row['nome']."</a></TD>";
                            print "<TD $valign><a href=\"#\" onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><b>".$row['setor']."</b><br>".$texto."</a></TD>";
                            if ($tipo_data == "abertura")
                                print "<TD $valign><a href=\"#\" onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\">".$row['data_abertura']."</a></TD>"; else
                                print "<TD $valign><a href=\"#\" onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\">".$row['data_fechamento']."</a></TD>";

                            print "<TD $valign><a href=\"#\" onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\">".$row['chamado_status']."</a></TD>";
                            print "<TD $valign align='center'><a href=\"#\" onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><img height='14' width='14' border=0 src='includes/imgs/imgs/".$imgSlaR."'></a></TD>";
                            print "<TD $valign align='center'><a href=\"#\" onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><img height='14' width='14' border=0 src='includes/imgs/".$imgSlaS."'></a></TD>";
                            print "</TR>";

                        } //while


                        //print "<br>".$query;
                        ?>
                    </table>


                </div>


        </div>

    </div>
</div>

			<script language="JavaScript"> 
			
			
			function valida(){
				var ok = validaForm('idNumeroInicial','INTEIRO','N�mero inicial',0);
				
				if (document.form1.numero_inicial.value=="" && document.form1.local.value=="" && document.form1.contato.value=="" && document.form1.status.value=="15"){
					alert('Selecione de qual Setor deseja consultar.');
					return false;
				}
				
				return ok;
			
			}					
			
			//-->				
			</script>
</body>
</HTML>
