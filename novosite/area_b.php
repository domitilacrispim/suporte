<?php
        include ("../PATHS.php");
		include ("../includes/var_sessao.php");
        include ("../includes/functions/funcoes.inc");
		include ("../includes/javascript/funcoes.js");
		include ("../includes/queries/queries.php");
        include ("../includes/config.inc.php");
		include ("../includes/versao.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>Inform�tica</title>
  <style type="text/css">
div {left: 50px; position: absolute;}
div.topo {top: 0px; }
div.corpo {top: 60px; }
div.bottom {top: 540px; } :link {color: white; text-decoration: none;}
:visited{color: white; text-decoration: none;}
:hover {color: rgb(230, 230, 230); text-decoration: none;}
td { font-family: Arial; font-weight: bold; text-align: center; color: rgb(255, 255, 255); }
div.bottom table {text-align: left; background-color: rgb(45, 183, 229); width: 627px; height: 32px;} </style>
  <style type="text/css">
div.corpo td { font-family: Arial; text-align: right; font-weight: bold; color: rgb(45, 183, 229); }
div.corpo :link {color: rgb(45, 183, 229); text-decoration: none;}
div.corpo :visited { color: rgb(45,183,229); text-decoration: none;}
div.corpo :hover {color: rgb(45,90, 255); text-decoration: none;}
  </style>
</head>


<body>

<div class="menu">
<table style="text-align: left; background-color: #016867; width: 627px; height: 32px;" border="0" cellpadding="2" cellspacing="2">

  <tbody>

    <tr>

      <td><a target="_self" href="area.php">&Aacute;rea</a></td>

      <td>|</td>

      <td><a target="_self" href="problema.php" class="mono">Problema</a></td>

      <td>|</td>

      <td><a target="_self" href="local.php">Local</a></td>

      <td>|</td>

      <td><a target="_self" href="geral.php">Geral</a></td>

    </tr>

  </tbody>
</table>

</div>

<div class="corpo">
Pesquisa por �REA: <span style="font-weight: bold;">

<?php //produz as datas formato "YYYY-MM-DD"
if(getenv("REQUEST_METHOD") == "GET") {
$area = $_GET['areas'];
$ano = $_GET['ano']; 
$data_ini = $ano."-".$_GET['mes_ini']."-01"; $data_fim = $ano."-".$_GET['mes_fim']."-31"; }
//conectar ao banco de dados e escolher o banco
		include("../includes/classes/conecta.class.php");
		$conexao= new conexao;
		$conexao->conecta('MYSQL');

/*$conexao = mysql_connect('localhost','root','') or die('Conexao falhou!');
mysql_select_db('ocomon',$conexao) or die('Nao selecionou o banco!');
*/

$query0 = 'SELECT s.sistema as area FROM sistemas s WHERE s.sis_id = '.$area;
$resultado0 = mysql_query($query0);
while($linha = mysql_fetch_array($resultado0)){
echo $linha['area'];
}
?>

</span><br>
<center>
<table border="1" style="text-align: right; width: 627px; height: 32px;">
	<tbody>
		<tr style="font-weight: bold;">
			<td> M�s: </td>
			<td> Total de chamados: </td>
			<td> Solu��es em tempo: </td>
			<td> Solu��es fora do tempo: </td>
			<td> Tempo m�dio (horas): </td>
		</tr>

<?php 
for ($i = $_GET['mes_ini']; $i <=$_GET['mes_fim'];$i++){
$data_ini= $ano."-".$i."-01";
$data_fim= $ano."-".($i+1)."-01";
switch ($i) {
case 01:
$s_mes = "janeiro";
break;
case 2:
$s_mes = "fevereiro";
break;
case 3:
$s_mes = "mar�o";
break;
case 4:
$s_mes = "abril";
break;
case 5:
$s_mes = "maio";
break;
case 6:
$s_mes = "junho";
break;
case 7:
$s_mes = "julho";
break;
case 8:
$s_mes = "agosto";
break;
case 9:
$s_mes = "setembro";
break;
case 10:
$s_mes = "outubro";
break;
case 11:
$s_mes = "novembro";
break;
case 12:
$s_mes = "dezembro";
break;
}
//cria a query
$query = 'SELECT COUNT(*) as quantidade FROM ocorrencias o, sistemas s, problemas p WHERE o.problema = p.prob_id AND  p.prob_area = s.sis_id AND o.data_atendimento >= "'.$data_ini.'" AND p.prob_area = '.$area.' AND o.data_atendimento < "'.$data_fim.'"' ;
//executa a query
$resultado = mysql_query($query);
$query2 = 'SELECT COUNT(*) as em_tempo FROM ocorrencias o, sla_solucao sla, problemas p WHERE o.problema = p.prob_id AND  o.data_atendimento >= "'.$data_ini.'" AND p.prob_area = '.$area.' AND o.data_atendimento < "'.$data_fim.'" AND  p.prob_id = o.problema AND p.prob_sla = sla.slas_cod AND TIMESTAMPDIFF(MINUTE,o.data_abertura,o.data_fechamento) < sla.slas_tempo';
$resultado2 = mysql_query($query2);
$query3 = 'SELECT COUNT(*) as fora_tempo FROM ocorrencias o, sla_solucao sla, problemas p WHERE o.problema = p.prob_id AND o.data_atendimento >= "'.$data_ini.'" AND p.prob_area = '.$area.' AND o.data_atendimento < "'.$data_fim.'" AND  p.prob_id = o.problema AND p.prob_sla = sla.slas_cod AND TIMESTAMPDIFF(MINUTE,o.data_abertura,o.data_fechamento) > sla.slas_tempo';
$resultado3 = mysql_query($query3);
$query4 = 'SELECT AVG(TIMESTAMPDIFF(HOUR,o.data_abertura,o.data_fechamento)) as media FROM ocorrencias o, sla_solucao sla, problemas p WHERE o.problema = p.prob_id AND   o.data_atendimento >= "'.$data_ini.'" AND p.prob_area = '.$area.' AND o.data_atendimento < "'.$data_fim.'" AND  p.prob_id = o.problema AND p.prob_sla = sla.slas_cod';
$resultado4 = mysql_query($query4);
while($linha = mysql_fetch_array($resultado))
{
echo '<tr><td> '.$s_mes.' </td><td>';
echo "<a target='_self' href='area_c.php?areas=".$area."&mes_ini=".$i."&ano=".$ano."'>".$linha['quantidade']."</a>";
echo '</td>';
}
while($linha = mysql_fetch_array($resultado2))
{
echo '<td>';
echo $linha['em_tempo'];
echo '</td>';
}
while($linha = mysql_fetch_array($resultado3))
{
echo '<td>';
echo $linha['fora_tempo'];
echo '</td>';
}
while($linha = mysql_fetch_array($resultado4))
{
echo '<td>';
echo $linha['media'];
echo '</td></tr>';
}
}

mysql_close();
?>
		</tbody>
	</table>
</center>
</div>

</div>

</body>
</html>
