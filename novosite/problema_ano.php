<?php
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
   	include ("../PATHS.php");
	include ("../includes/var_sessao.php");
   	include ("../includes/functions/funcoes.inc");
	include ("../includes/queries/queries.php");
   	include ("../includes/config.inc.php");
	include ("../includes/versao.php");
?>
<!DOCTYPE html>
<html>
<head>

  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>Informática</title>
  <style type="text/css">
div {left: 50px; position: absolute;}
div.topo {top: 0px; }
div.corpo {top: 60px; }
div.bottom {top: 1300px; } :link {color: white; text-decoration: none;}
:visited{color: white; text-decoration: none;}
:hover {color: rgb(230, 230, 230); text-decoration: none;}
td { font-family: Arial; font-weight: bold; text-align: center; color: rgb(255, 255, 255); }
div.bottom table {text-align: left; background-color: rgb(45, 183, 229); width: 627px; height: 32px;} 
</style>
<style type="text/css">
div.corpo td { font-family: Arial; font-weight: bold; text-align: left; color: rgb(45, 183, 229); }
div.corpo :link {color: rgb(45, 183, 229); text-decoration: none;}
div.corpo :visited { color: rgb(45,183,229); text-decoration: none;}
div.corpo :hover {color: rgb(45,90, 255); text-decoration: none;}
</style>
</head>


<body>

<p/>
<div class="menu">
<table style="text-align: left; background-color: #016867; width: 700px; height: 32px;" border="0" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>

      <td><a target="_self" href="area.php">Area</a></td>

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

<div class="corpo" id="corpo">
<center>
<table border='1'>
	<tr>
		<td>Area</td>
		<td>Problema</td>
<?php 
		include("../includes/classes/conecta.class.php");
		$conexao= new conexao;
		$conexao->conecta('MYSQL');
		$ano = $_GET['ano']; 
		$query ="SELECT distinct month(ocorrencias.data_abertura) as mes,count(*) as quant
			 FROM ocorrencias
					JOIN problemas on ocorrencias.problema = problemas.prob_id
		            JOIN sistemas on sistemas.sis_id = problemas.prob_area
			 WHERE year(ocorrencias.data_abertura) = ".$ano.
	         " GROUP BY month(ocorrencias.data_abertura)
	          ORDER BY month(ocorrencias.data_abertura)"; 
		$resultado = mysql_query($query); 
		$problema = "";

		$meses = array(); 
		$total_ano = array();
		while ($linha = mysql_fetch_array($resultado)) { 
			echo "<td style='background-color: #016867;color:white;'>".$linha['mes'];
			echo "</td>";			
			array_push($meses,$linha['mes']);
			array_push($total_ano,$linha['quant']);
		}	
		echo "<td style='color:#016867'>Total</td>";
		echo "</tr>";		
		
		$queryProblema ="SELECT sistemas.sistema, problemas.problema, problemas.prob_id
			FROM  problemas 
			  JOIN sistemas on sistemas.sis_id = problemas.prob_area
			ORDER BY problemas.prob_area, problemas.problema"; 
		//Percorre todos os problemas e imprime ano a ano.
		
		$resultadoProblema = mysql_query($queryProblema); 
		while ($linhaProblema = mysql_fetch_array($resultadoProblema)) { 
			echo "\n<tr>";
			echo "<td style='background-color: rgb(45, 183, 229);color:white;'>".$linhaProblema['sistema']."</td>";	
			echo "<td style='background-color: rgb(45, 183, 229);color:white;'>".$linhaProblema['problema']."</td>";	
			$queryAplicacao ="SELECT sistemas.sistema, 
						problemas.prob_id,problemas.problema,
						month(ocorrencias.data_abertura) as mes,
 						count(*) as quant
				FROM ocorrencias
					JOIN problemas on ocorrencias.problema = problemas.prob_id
		            JOIN sistemas on sistemas.sis_id = problemas.prob_area
				WHERE problemas.prob_id = '".$linhaProblema['prob_id']."'
				    and year(ocorrencias.data_abertura) = ".$ano.
			"	GROUP BY problemas.prob_id,problemas.problema,mes
				ORDER BY problemas.prob_area, problemas.problema, mes"; 
			$resultadoAplicacao = mysql_query($queryAplicacao); 
			$linhaAplicacao = mysql_fetch_array($resultadoAplicacao);
			$total = 0;
			for ($i = 0; $i <count($meses); $i++){				
				echo "<td style='text-align: right;'>";
				if ($meses[$i] == $linhaAplicacao['mes']) {	
					echo "<a target='_self' href='problema_c.php?problema=".
					$linhaAplicacao['prob_id']."&mes_ini=".$meses[$i]."&ano=".$ano."'>".$linhaAplicacao['quant']."</a></td>";
					if (isset($linhaAplicacao['quant'])) {
						$total = $total + $linhaAplicacao['quant'];
					}
					$linhaAplicacao = mysql_fetch_array($resultadoAplicacao);
				}				
				echo "</td>";
			}	
			echo "<td style='text-align: right;'>".$total."</td>";
			echo "</tr>";
		}
		echo "\n<tr>";
		echo "<td>Total</td>";
		echo "<td></td>";
		$soma_ano = 0;
		for ($i = 0; $i <count($total_ano); $i++){	
			echo "<td style='text-align: right;background-color: rgb(45, 183, 229);color:white;'>".$total_ano[$i]."</td>";			
			$soma_ano+=$total_ano[$i];
		}	
		echo "<td>".$soma_ano."</td>";
		echo "</tr>";		
		
?>
</table>
</center>

</div>



</body>
</html>
