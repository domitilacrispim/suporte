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
	
	
// 	if (is_file( "./.root_dir" )) {
//  		require("./includes/phpmailer/class.phpmailer.php");
//  	} else {
//  		require("../../includes/phpmailer/class.phpmailer.php");
//  	}
		

	define ( "BODY_COLOR", "#F6F6F6");
	define ( "TAB_COLOR",  "#ECECDB");
	define ( "TD_COLOR", "#ECECDB");
	define ( "MENU_USUARIO","<link rel=stylesheet type=\"text/css\" href=\"../includes/css/estilos.css\">");
	define ( "MENU_ADMIN","");


################################################################################

function conecta($host, $bd, $user, $senha, $sistema)
{
        // $host = servidor do mysql, $bd = nome do banco de dados, $user = usu�rio do mysql, $senha = senha dp mysql, $sistema = sistema que esta sendo usado
    $conexao = new PDO('mysql:host='.$host.';dbname='.$bd.';charset=utf8', $user, $senha);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($conexao == 0)
        {
                $retorno = "ERRO DE CONEXÃO - Servidor $host - Sistema $sistema<br>";
        }
        else {
            $retorno = "ok";
        }
        return  $retorno;
}


function desconecta($conexao){
	$conexao=null;
}

##################################################################################
function senha_system($user,$password,$tabela)
{
        // $user = usu�rio para login, $senha = senha do usu�rio, $tabela = nome da tabela de usu�rios

        $login = md5($password);
        $conec = new conexao;
        $PDO = $conec->conectaPDO();
		
		$query = "SELECT * from ".$tabela." where (login = '".$user."' and password = '".$login."')";
			
        $resultado = $PDO->query($query) or die();

        if ($resultado == 0)
        {
                $retorno = "ERRO DE LOGIN - Tabela ".$tabela."<br>";
        }
        else if ($resultado->rowCount() == 0)
                {
                        $retorno = "ERRO DE LOGIN - Usuário ".$user."<br>";
                }
                else
                {
                        $retorno = "ok";
                }

        return $retorno;
}



function senha_ldap($user,$password,$tabela)
{
        // $user = usu�rio para login, $senha = senha do usu�rio, $tabela = nome da tabela de usu�rios
		
        $login = md5($password);
	        $query = "SELECT * from $tabela where (login = '$user')";
    $conec = new conexao;
    $PDO = $conec->conectaPDO();

    $resultado = $PDO->query($query) or die();
        if ($resultado == 0)
        {
                $retorno = "ERRO DE LOGIN - Tabela $tabela<br>";
        }
        else if ($resultado->rowCount() == 0)
                {
                        $retorno = "ERRO DE LOGIN - Usuário $user<br>";
                }
                else
                {
                        $retorno = "ok";
                }
		
        return $retorno;
}


################################################################################
function geraLog($filename,$data,$usuario,$pagina,$acao){
	$conteudo = "DATA: $data\t";
	$conteudo.= "USU�RIO: $usuario\t";
	$conteudo.= "PAGINA: $pagina\t";
	$conteudo.= " A��O: $acao\t";
	$conteudo.="\n";
	
	if (is_writable($filename)) {
	
	    if (!$handle = fopen($filename, 'a')) {
	         $warning = "O arquivo n�o pode ser aberto (".$filename.")!";
	         exit;
	    }
	    if (!fwrite($handle, $conteudo)) {
	        $warning = "O arquivo n�o pode ser escrito (".$filename.")!";
	        exit;
	    }
	    $warning= "Sucesso, (".$conteudo.") escrito no arquivo (".$filename.")!";
	    fclose($handle);
						
	} else {
	    $warning = "O arquivo ".$filename." n�o tem permiss�o de escrita!";
	}

	return $warning;
}

###############################################################################

###############################################################################
function testa_user($s_usuario, $s_nivel, $s_nivel_desc, $permissao){
	if ($s_nivel>$permissao)
        {
                $saida= "<META HTTP-EQUIV=REFRESH   CONTENT=\"0;
                URL=index.php\">";
        		
		} else
		{
	$saida="
<body bgcolor=".BODY_COLOR.">
			<TABLE  bgcolor='black' cellspacing='1' border='1' cellpadding='1' align='center' width='100%'>
	            	<TR>
			  <TD bgcolor=".TD_COLOR.">
	                  <TABLE  cellspacing='0' border='0' cellpadding='0' bgcolor=".TAB_COLOR.">
	                        <TR>";
		               	$cor1 = TD_COLOR;
		                     $saida.= "<TD bgcolor=".$cor1." nowrap width=80%><p align left><b>OcoMon - M�dulo de ocorrências</b></TD>";
		                     $saida.=menu_usuario();
		                     if ($s_nivel==1) {
		                        $saida.= menu_admin(TD_COLOR);
		                     }
								
	$saida.="               </TR>
	                  </TABLE>
	                </TD>
 			</TR>
	 		</TABLE>";	
		}	
	return $saida;
}


function testa_user_hidden($s_usuario, $s_nivel, $s_nivel_desc, $permissao){


		if ($s_nivel>$permissao)
		{
		        $saida= "<META HTTP-EQUIV=REFRESH   CONTENT=\"0;
		        URL=index.php\">";
		} else {
			//$cor1 = TD_COLOR;
			$saida =  "<link rel=stylesheet type=\"text/css\" href=\"./css/estilos.css\"> <TABLE class=header>
		        		<tr class=topo>
						<TD>
		                <TABLE class=menu>
		                        <TR class=topo>
		                        <TD><b>InvMon - Controle de invent�rio para equipamentos de inform�tica</td>";
								
		                        
		                        $saida.= "</TR>
		                	</TABLE>
		        		</TD>
					</tr>
					</TABLE>";	
		}
	return $saida;

}

#################################################################################

#################################################################################
function cabecalho($logo,$msg1,$msg2,$titulo)
{
	return "
		<table width=80% border=0 cellspacing=1 cellpadding=1 align=center bgcolor=black>
		  <tr bgcolor=#FFFFFF>
		    <td width=30%> 
		      <div align=center><font size=3 face=Arial, sans-serif><img src=./$logo></font></div>
		    </td>
		    <td width=40%> 
		      <div align=center><font size=3 face=Arial, sans-serif> <b>$msg1</b></font></div>
		    </td>
		    <td width=30%> 
		      <div align=center><font size=3 face=Arial, sans-serif><b><font size=2>$msg2</font></b></font></div>
		    </td>
		  </tr>
		  <tr bgcolor=#FFFFFF>
		    <td colspan=3>
		      <div align=center><font size=2 face=Arial, sans-serif><b>$titulo</b></font></div>
		    </td>
		  </tr>
		</table>
			
	";
}


function testaArea($area,$rowArea,$horarios){

	if (array_key_exists($rowArea,$horarios)){  //verifica se o c�digo da �rea possui carga hor�ria definida no arquivo config.inc.php
		$area = $rowArea; //Recebe o valor da �rea de atendimento do chamado
	} else $area= 1; //Carga hor�ria default definida no arquivo config.inc.php
	return $area;
}




###################################################################################################

		//TIPO: tipo de relat�rio - formata��o espec�fica
		//SQL: Query no banco de dados
		//CAMPOS: Array com o nome dos campos que eu quero imprimir no relat�rio
		//HEADERS: Array com os cabe�alhos de cada coluna do relat�rio
		function gera_relatorio($tipo,$sql,$campos,$headers,$logo,$msg1,$msg2,$msg3){
			//Estilo aplicado nos relat�rios
			print "<style type=\"text/css\"><!--";
			print "table.relatorio_1 {width:80%; margin-left:auto; margin-right: auto; text-align:left; 
					border: 0px; border-spacing:1 ;background-color:white; padding-top:10px; 
					page-break-after: auto;}";
			print "td.linha {font-family:arial; font-size:12px; line-height:0.8em;}";			
			print "td.linha_par {font-family:arial; font-size:12px; line-height:0.8em; background-color:#EAEAEA}";
			print "td.linha_impar {font-family:arial; font-size:12px; line-height:0.8em;background-color:#C8C8C8}";
			print "td.cabs {font-family:arial; font-size:12px; font-weight:bold; background-color: #A3A352;}";
			print "td.foot {font-family:arial; font-size:12px; font-weight:bold; line-height:0.8em; background-color: #A8A8A8;}";
			//print "{page-break-after: always;}";
			print "--></STYLE>";			
			
			if (count($campos) != count($headers))  {//Verifica se cada campo da tabela possui um header!
				print "O n�mero de campos n�o fecha com o n�mero de headers!";
				exit;
			}//if campos == headers
			
			
			$commit = mysql_query($sql);
        	$linhas = mysql_num_rows($commit);
			$k=0;
			while($k < mysql_num_fields($commit)){ //quantidade de campos retornados da consulta
				$field = mysql_fetch_field($commit,$k);//Retorna um objeto com informa��es dos campos
				$fields.=$field->name; //Joga os nomes dos campos para uma string
				$k++;
			} // while
			
			
			
			
			if ($linhas==0) {
			    print "Nenhuma linha retornada pela consulta";
			}else{
				print cabecalho($logo,$msg1,$msg2,$msg3);
				
				
				if ($tipo==1|| $tipo==0) {//Tipo definido de relat�rio //
					print "<TABLE class=\"relatorio_1\" cellpadding=4>";
					print "<tr>";		
					for ($i=0; $i<count($headers); $i++){
						print "<td class=\"cabs\">".$headers[$i]."</td>";	
					}//for
					print "</tr>";				
					$l = 0; //vari�vel que controla se a linha � par ou impar
					while($row=mysql_fetch_array($commit)){
						if ($l % 2) {
							$par_impar = "_par";
						} else {
							$par_impar = "_impar";
						}//if - else
						print "<tr>";		
						for ($i=0; $i<count($campos); $i++){ //IMPRIME CAMPO A CAMPO
							print "<td class=\"linha$par_impar\">";
							$sep = explode(",",$campos[$i]); //Se algum campo passado tem mais de um argumento � separado
							for ($j=0; $j<count($sep); $j++){
								$pos = strpos($fields,$sep[$j]); //Verifica na string gerada se o argumento passado existe como um nome de campo
								if ($pos===false) {
									print $sep[$j]; //Se o campo n�o existe � impresso literalmente
								} else
									print $row[$sep[$j]]; //Se o campo existe � impresso seu valor;
							} //for J//
							print "</td>";
						}//for i//
						print "</tr>";
						$l++;
					} // while	
						//RODAP�				
						print "<tr>";		
						for ($i=0; $i<count($campos); $i++){ //IMPRIME CAMPO A CAMPO
							if ($i==count($campos)-1) {
							    $total = $linhas;
							} else
							if ($i==count($campos)-2) {
							    $total = "TOTAL";
							}
							print "<td class=\"foot\">$total</td>";	
						}//for
						print "</tr>";
					print "</table>";
				} else 
				
				if ($tipo==2) {//Outra formata��o para sa�da do relat�rio//
					print "<table class=\"relatorio_1\">";
					while($row=mysql_fetch_array($commit)){
						//print "<tr>";		
						for ($i=0; $i<count($campos); $i++){ //IMPRIME CAMPO A CAMPO
							print "<tr>";
							//print "<td>".$headers[$i]."</td>";
							print "<td>";
							$sep = explode(",",$campos[$i]); //Se algum campo passado tem mais de um argumento � separado
							for ($j=0; $j<count($sep); $j++){
								$pos = strpos($fields,$sep[$j]); //Verifica na string gerada se o argumento passado existe como um nome de campo
								if ($pos===false) {
									print $sep[$j]; //Se o campo n�o existe � impresso literalmente
								} else {
									print $row[$sep[$j]]; //Se o campo existe � impresso seu valor;
								}
								//print "</td>";
							} //for J//
							print "</td>";
						
						
						}//for i//
						print "</tr>";
					}//while
					print "</table>";
				} //fim do tipo==2 //
			
			
			
			} //else linhas != 0 //
		}//fun��o







#################################################################################

function menu_usuario_old() //sem dhtml
{
        
		return" 
     <TITLE>-=[InvMon - Monitor de Invent�rio]=-</TITLE>
	 <link rel=stylesheet type=\"text/css\" href=\"./css/estilos.css\">
	<TABLE border=0 cellpadding=0 cellspacing=0 align=center width=100%  bgcolor=\"$TD_COLOR\">
		<tr>
		
		<td>
		<TABLE border=0 cellpadding=0 cellspacing=0 align=left width=99% bgcolor=\"$TD_COLOR\">
              <TD align=center bgcolor=\"$TD_COLOR\" font=\"$FONT_TIPO\" width=11% nowrap><a class=nav href=abertura.php title=In�cio><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>[ Inicio ]</a></TD>
                <TD align=center bgcolor=\"$TD_COLOR\" width=11% nowrap><a class=nav href=materiais.php title='Documentos cadastrados'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>[ Documentos ]</a></TD>
				<TD align=center bgcolor=\"$TD_COLOR\" width=11% nowrap><a class=nav href=incluir_computador.php title='Cadastrar equipamento'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>[ Cadastrar ]</a></TD>
				<TD align=center bgcolor=\"$TD_COLOR\" width=11% nowrap><a class=nav href=mostra_computadores.php title='Exibir listagem de equipamentos cadastrados'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>[ Equipamentos ]</a></TD>
                <TD align=center bgcolor=\"$TD_COLOR\" width=11% nowrap><a class=nav href=consulta_inv.php title='Consulta um equipamento por seu n�mero de etiqueta'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>[ Consultar ]</a></TD>
  	           <TD align=center bgcolor=\"$TD_COLOR\" width=11% nowrap><a class=nav href=consulta_comp.php title='Personalize a sua consulta'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>[ Montar Consulta ]</a></TD>
               <TD align=center bgcolor=\"$TD_COLOR\" width=11% nowrap><a class=nav href=relatorios.php title='Estat�sticas e Relat�rios'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>[ Estat�sticas/Relat�rios ]</a></TD>
                <TD align=center bgcolor=\"$TD_COLOR\" width=11% nowrap>[<a class=negrito href=logout.php title='Sair do sistema'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><font color=red> Sair </font> </a>]</TD>
		</table>
		</td>	
		<td>
		<TABLE border=0 cellpadding=0 cellspacing=0 align=right width=1% bgcolor=\"$TD_COLOR\">			
				
				<TD align=right bgcolor=\"$TD_COLOR\"  nowrap><img  valign=top title='InvMon - Monitor de invent�rio' height=25 src=\"../includes/imgs/invmon.gif\"</TD>
        </TABLE>
		</td>
		</tr>
		
	</table>

"
        ;
}

################################################################################






function menu_admin(){
	return "";
}


################################################################################

function consulta($rodou,$numero,$titulo,$descricao,$equipamento)
{
        if ($rodou == "sim")
        {
                $query = "SELECT * FROM ocorrencias WHERE (";

                if (!empty($numero))
                        $query.="numero='$numero' ";

                if (!empty($titulo))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="titulo='$titulo' ";
                }

                if (!empty($descricao))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="descricao='$descricao' ";
                }

                if (!empty($equipamento))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="equipamento='$equipamento' ";
                }

                $query.=" )";

//                $numero="";
//                $titulo="";
//                $descricao="";
//                $equioamento="";

                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);

                print "<BR>";
                print "<TD>";
                print "<TABLE border='1' align='center' width='100%'>";
                print "<TR><TD>N�mero</TD><TD>Titulo</TD><TD>Contato</TD><TD>Local</TD>
                <TD>Abertura</TD><TD>Status</TD></TR>";
                $i=0;
                while ($i < $linhas)
                {
                        print "<TR>";?>
                        <TD><?print mysql_result($resultado,$i,0);?></TD>
                        <TD><?print mysql_result($resultado,$i,1);?></TD>
                        <TD><?print mysql_result($resultado,$i,5);?></TD>
                        <TD><?print mysql_result($resultado,$i,7);?></TD>
                        <TD><?print mysql_result($resultado,$i,9);?></TD>
                        <TD><?print mysql_result($resultado,$i,11);?></TD>
                        <?print "</TR>";
                        $i++;
                }
                print "</TABLE>";
        }
}


################################################################################

// function veremail($email)
// {
//         if(!eregi("^[A-Za-z._-]+@[A-Za-z]{1,}\.[a-z]{2,3}",$email))
//                 $passou = "erro";
//         else
//                 $passou = "ok";
// 
//         return $passou;
// }

################################################################################

function veremail($email)
{
        if(!eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$",$email))
                $passou = "erro";
        else
                $passou = "ok";

        return $passou;
}

function mensagem($aviso="AVISO",$msg="",$tipo="info")
{
        return "
        <div class=\"alert alert-$tipo\" role=\"alert\"><strong>$aviso</strong> $msg</div>
        ";
}

################################################################################

function putComma ($vetor){
	$chamados="";
	if (count($vetor) > 1) {
		
		for ($i=0; $i<count($vetor); $i++){
			$chamados.= "$vetor[$i],";
		}
		if (strlen($chamados)>0) {
			$chamados = substr($chamados,0,-1);
		}									
	} else
		$chamados = $vetor[0];
	return $chamados;
}


##############################################33



function mes($data)
{

        $vmes = date(n,$data);

        switch ($vmes)
        {
                case 1:
                        $retorno = "Janeiro";
                        break;
                case 2:
                        $retorno = "Fevereiro";
                        break;
                case 3:
                        $retorno = "Mar�o";
                        break;
                case 4:
                        $retorno = "Abril";
                        break;
                case 5:
                        $retorno = "Maio";
                        break;
                case 6:
                        $retorno = "Junho";
                        break;
                case 7:
                        $retorno = "Julho";
                        break;
                case 8:
                        $retorno = "Agosto";
                        break;
                case 8:
                        $retorno = "Setembro";
                        break;
                case 10:
                        $retorno = "Outubro";
                        break;
                case 11:
                        $retorno = "Novembro";
                        break;
                case 12:
                        $retorno = "Dezembro";
                        break;
                default:
                        $retorno = "ERRO";
                        break;
        }

        return  $retorno;
}


###############################################################################

function converte_dma_para_amd($dataform)//converte a data do formato dd/mm/aaaa para aaaa-mm-dd
{
        // if (strpos("-",$dataform)){
			// $datatransf = explode("-",$dataform);
		// } else
			$datatransf = explode("/",$dataform);
        $data = "$datatransf[2]-$datatransf[1]-$datatransf[0]";
        return $data;
}


function converte_datacomhora($dataform)//pega a data do formato aaaa-mm-dd +hora, e transforma p/ dd-mm-aaaa +hora
{
	//separando o dia e a hora
        $data_hora = explode(" ",$dataform);
		$data="$data_hora[0]";
		$hora="$data_hora[1]";
	
	//formatando o dia em dd-mm-aaaa
		$datatransf = explode("-",$data);
        $data = "$datatransf[2]/$datatransf[1]/$datatransf[0]";
		$datacompleta = $data." ".$hora;

        return $datacompleta;
}


function diff_em_segundos($data1, $data2)
{
        $s = strtotime($data2)-strtotime($data1);
        $d = intval($s/86400);
        $s -= $d*86400;
        $h = intval($s/3600);
        $s -= $h*3600;
        $m = intval($s/60);
        $s -= $m*60;

        $v = $d."-".$h."-".$m."-".$s;
        
		$dados = explode("-",$v);
		
		$dias = $dados[0];
		//Verificar dias v�lidos
		$horas = $dados[1];
		//Verificar horas v�lidas
		$minutos = $dados[2];
		$segundos = $dados[3];
		
		$dias *=86400; //Dia de 24 horas
		//$dias *=50400; //Dia de 14 horas
		$horas *=3600;
		$minutos *=60;
		$segundos +=$dias+$horas+$minutos;
		
		return $segundos;
}

################################################################################

function data_diff($data1, $data2)
{
        $s = strtotime($data2)-strtotime($data1);
        $d = intval($s/86400);
        $s -= $d*86400;
        $h = intval($s/3600);
        $s -= $h*3600;
        $m = intval($s/60);
        $s -= $m*60;

        $v = $d." dias, ".$h.":".$m.":".$s;
        return $v;
}


function date_diff2($data1, $data2)
{
        $s = strtotime($data2)-strtotime($data1);
        $d = intval($s/86400);
        $s -= $d*86400;
        $h = intval($s/3600);
        $s -= $h*3600;
        $m = intval($s/60);
        $s -= $m*60;

        $v = $d;
        return $v;
}

function date_diff_dias($data1, $data2)
{
        $s = strtotime($data2)-strtotime($data1);
        $d = intval($s/86400);
        $s -= $d*86400;
        $h = intval($s/3600);
        $s -= $h*3600;
        $m = intval($s/60);
        $s -= $m*60;

        $v = $d;
        return $v;
}



function segundos_em_horas($segundos){

	$h=0;
	$m=0;

	while($segundos >=60){
		
	
		if ($segundos >= 3600) {
			while ($segundos >= 3600) { //ORDEM DE HORAS
			    	$segundos-=3600;   
			   		$h+=1;
			   }
		} else
		if ($segundos >= 60) {
			while ($segundos >= 60) {//ORDEM DE MINUTOS
				$segundos-=60;			    
				$m+=1;
			}
		}
					

	}
		
		if(strlen($h) == 1){$h = "0".$h;}; //Coloca um zero antes
		if(strlen($m) == 1){$m = "0".$m;}; //Coloca um zero antes
		if(strlen($segundos) == 1){$segundos = "0".$segundos;}; //Coloca um zero antes		

		$horas = $h.":".$m.":".$segundos;
		
		
		return $horas;						
	
}

################################################################################
################################################################################

function datam($dataform)//pega a data informada, e formata dd-mm-aaaa c/ a hora atual
{
        if (strpos("-",$dataform)){
			$datatrans = explode("-",$dataform);
		} else
			$datatrans = explode("/",$dataform);
        $data = "$datatrans[2]-$datatrans[1]-$datatrans[0]";
        $hora = strftime("%H:%M:%S");
        $data = $data." ".$hora;

        return $data;
}

function datam2($dataform)
{
        $datatrans = explode("-",$dataform);
        $data = "$datatrans[2]/$datatrans[1]/$datatrans[0]";
        $hora = strftime("%H:%M:%S");
        $data = $data." ".$hora;

        return $data;
}




function inteiro ($string){
	
	settype ($string,"integer");
	return $string;

}


function horaAhora($valor,$hora){
	
	for ($i=1; $i<=$valor; $i++){
		if ($hora>=23) {
			$hora=-1;
		}		
	
	
		$hora++;
	}
	
	return $hora;
}
################################################################################

function datab($dataform)
{ 
        $hora = substr($dataform,11,17);
        $data = substr($dataform,0,10);
        $datatrans = explode("-",$data);
        $data = "$datatrans[2]/$datatrans[1]/$datatrans[0]";
        $data = $data." ".$hora;

        return $data;
}
function datab2($dataform)
{ 
        $hora = substr($dataform,11,17);
        $data = substr($dataform,0,10);
        $datatrans = explode("-",$data);
        $data = "$datatrans[2]/$datatrans[1]/$datatrans[0]";
        //$data = $data." ".$hora;

        return $data;
}


################################################################################

function dataRED($dataform)
{ 
		//Formato de Data Reduzido
        $hora = substr($dataform,11,17);
        $data = substr($dataform,0,10);

        $hora = substr($hora,0,5);

        $datatrans = explode("-",$data);
		$anored= substr($datatrans[0],2,2);
        $data = "$datatrans[2]/$datatrans[1]/$anored";
        $data = $data."-".$hora;

        return $data;
}
#################################################################################


function ajusta ($palavra,$num) {
         $limite = $num;
         $i = strlen($palavra);
         while ($i < $limite) {
                $palavra.='-';
                $i++;
         }

    return $palavra;

    }

################################################################################

function envia_email_new_user($login, $nome, $email, $rand, $site)
{
        //$ocomon = '"http://www.intranet.unilasalle.edu.br/sistemas/ocomon"';
		$msg = "SISTEMA OCOMON\t\n";
		$msg .= "Prezado ".$nome."\t\n";
        $msg .= "Sua solicita��o para cria��o do login: ".$login." foi bem sucedida!\t\n";
		$msg .= "Para confirmar sua inscri��o clique no link abaixo: \t\n";
		$msg .= "".$site."/ocomon/geral/confirma.php?rand=".$rand." \t\n";		
       		
	   
        $mailheaders = "From: SISTEMA_OCOMON\n";
        $mailheaders .= "Replay-to: ".$email."\n\n";
				
        mail($email, "OCOMON - CONFIRMA��O DE CADASTRO", $msg, $mailheaders);
		return "OK";
}



function noHtml ($string) {
	
	$string=addslashes($string);
	$string=addslashes($string);
	return stripslashes(htmlspecialchars($string));

}

function toHtml ($string){

   $trans_tbl = get_html_translation_table (HTML_ENTITIES);
   $trans_tbl = array_flip ($trans_tbl);
   return strtr ($string, $trans_tbl);

}

function isIn ($pattern, $values){
	
	$found = false;
	if (strpos($values,",")){
		$valuesArray = explode(",",$values);
		
		for ($i = 0; $i < count($valuesArray); $i++){
			if ($valuesArray[$i] == (int)$pattern) $found = true;
		
		}
	
	} else 
	if ($values == (int)$pattern) $found = true;
	
	return $found;
	
}

function sepComma($value,$array){
	if (strpos($value,",")) {
		$array = explode(",",$value);
	
	} else $array = $value;
	
	return $array;
}



function random (){
	
	for ($i=0; $i<10; $i++) {
		$rand.= mt_rand(1,300);
	}
	
	return ($rand);
}

function transbool ($bool){
	if ($bool == 0) $trans = "N�O"; else
	if ($bool == 1) $trans = "SIM"; else
	$trans = $bool;
	
	return $trans;
}


function transvars ($msg, $arrayEnv) { 

	foreach ($arrayEnv as $id=> $var){
		$msg = str_replace($id, $var, $msg);
	}
	
	return $msg;
}


function send_mail($event, $e_destino,$mailConf, $msgConf, $envVars){


//$event: Tipo de evento, os eventos s�o definidos pela situa��o (abertura, edi��o ou assentamento) 
//        e pelo destino (usu�rio, operador ou �rea)
//$e_destino: e-mail de destino
//$mailConf: array com as informa��es de conex�o smtp
//$msgConf: array com as informa��es de mensagem
//$envVar: array com as vari�veis de ambiente
	if (is_file( "./.root_dir" )) {
 		require_once("./includes/phpmailer/class.phpmailer.php");
 	} else {
 		require_once("../../includes/phpmailer/class.phpmailer.php");
 	}	
	
	$mail = new PHPMailer();
	
	if ($mailConf['mail_issmtp']) {
		$mail->IsSMTP();
	}                                      // set mailer to use SMTP
	$mail->Host = $mailConf['mail_host'];  // specify main and backup server
	$mail->SMTPAuth = $mailConf['mail_isauth'];     // turn on SMTP authentication
	$mail->Username = $mailConf['mail_user'];  // SMTP username
	$mail->Password = $mailConf['mail_pass']; // SMTP password
	
	$mail->From = $mailConf['mail_from'];
	//$mail->IsHTML(false);  
	$mail->IsHTML($mailConf['mail_ishtml']);                 //$mail->IsHTML(true); // set email format to HTML 
	
	
	
	$mail->FromName = $msgConf['msg_fromname'];
	$mail->AddAddress($e_destino);
	//$mail->AddBCC("copiaoculta@exemplo.ufu.br");
	//$mail->AddAddress("josh@example.net", "Josh Adams");
	//$mail->AddAddress("ellen@example.com");                  // name is optional
	$mail->AddReplyTo($msgConf['msg_replyto'], "SISTEMA OCOMON");
	
	//$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
	$mail->Subject = transvars($msgConf['msg_subject'],$envVars);
	
	if ($mailConf['mail_ishtml']){
		$mail->Body = transvars($msgConf['msg_body'],$envVars);
	} else {	
		$mail->Body = nl2br(transvars($msgConf['msg_altbody'],$envVars));
	}
	
	$mail->AltBody = nl2br(transvars($msgConf['msg_altbody'],$envVars));
	
	if(!$mail->Send())
	{
		echo "A mensagem n�o p�de ser enviada. <p>";
		echo "Mailer Error: " . $mail->ErrorInfo;
		exit;
	}

	return true;
}

function showArray($array){
	print "<pre>";
	print_r ($array);
	print "</pre>";
	return true;
}


function upload($img_file,$config) {
	// Prepara a vari�vel caso o formul�rio tenha sido postado
	$arquivo = isset($_FILES[$img_file]) ? $_FILES[$img_file] : FALSE;
	
/*	$config = array();
	// Tamano m�ximo da imagem, em bytes
	$config["tamanho"] = 307200; //Aproximadamente 300k
	// Largura M�xima, em pixels
	$config["largura"] = 800;
	// Altura M�xima, em pixels
	$config["altura"] = 600;*/
	
	$saida = "OK";

	if($arquivo) {
		$erro = array();
		
		// Verifica o mime-type do arquivo para ver se � de imagem.
		// Caso fosse verificar a extens�o do nome de arquivo, o c�digo deveria ser:
		//
		// if(!eregi("\.(jpg|jpeg|bmp|gif|png){1}$", $arquivo["name"])) {
		//      $erro[] = "Arquivo em formato inv�lido! A imagem deve ser jpg, jpeg, bmp, gif ou png. Envie outro arquivo"; }
		//
		// Mas, o que ocorre � que alguns usu�rios mal-intencionados, podem pegar um v�rus .exe e simplesmente mudar a extens�o
		// para alguma das imagens e enviar. Ent�o, n�o adiantaria em nada verificar a extens�o do nome do arquivo.
		if(!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $arquivo["type"])) {
			$erro[] = "Arquivo em formato inv�lido! A imagem deve ser jpg, jpeg, bmp, gif ou png.";
		} else  {
			// Verifica tamanho do arquivo
			if($arquivo["size"] > $config["conf_upld_size"]) {
				$kbytes = $config["conf_upld_size"]/1024;
				
				$erro[] = "Arquivo em tamanho muito grande! A imagem deve ser de no m�ximo " . $kbytes . " Kbytes.";
			}
			// Para verificar as dimens�es da imagem
			$tamanhos = getimagesize($arquivo["tmp_name"]);
			
			// Verifica largura
			if($tamanhos[0] > $config["conf_upld_width"]) {
				$erro[] = "Largura da imagem n�o deve ultrapassar " . $config["conf_upld_width"] . " pixels";
			}
	
			// Verifica altura
			if($tamanhos[1] > $config["conf_upld_height"]) {
				$erro[] = "Altura da imagem n�o deve ultrapassar " . $config["conf_upld_height"] . " pixels";
			}
		}
		
		if(sizeof($erro)) {
			$saida = "<b>ALERTA:</b><br />";
			foreach($erro as $err) {
				$saida.=" - ".$err."<br>";
			}
		}
		
		if($arquivo && !sizeof($erro)) {
			//echo "<BR><BR>UPLOAD REALIZADO COM SUCESSO!";
			$saida = "OK";
		}	
		
	} else print "ERRO NO ARQUIVO!";
	
	return $saida;

}


function montaArray($mysql_result,$mysql_fetch_array){
	$k=0;
	$valores = array();
	while($k < mysql_num_fields($mysql_result)){ //quantidade de campos retornados da consulta
		$field = mysql_fetch_field($mysql_result,$k);//Retorna um objeto com informa��es dos campos
		$valores[$field->name] = $mysql_fetch_array[$field->name];
		$k++;
	} // while
	return $valores;
}


?>
