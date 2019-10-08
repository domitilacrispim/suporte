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
  <title>Inform�tica</title>
  <style type="text/css">
div {left: 40px; position: absolute;}
div.topo {top: 0px; }
div.corpo {top: 50px; }
div.bottom {top: 1300px; } 
:link {color: white; text-decoration: none;}
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
<?php 
		include("../includes/classes/conecta.class.php");
		$conexao= new conexao;
		 $PDO = $conexao->conectaPDO();

$query ="SELECT distinct year(ocorrencias.data_abertura) as ano,count(*) as quant
			 FROM ocorrencias
				JOIN problemas on ocorrencias.problema = problemas.prob_id
	           	JOIN sistemas on sistemas.sis_id = problemas.prob_area
	         GROUP BY year(ocorrencias.data_abertura)
	         ORDER BY year(ocorrencias.data_abertura)"; 
		$resultado = $PDO->query($query);
		$sistema = "";

		echo "<td>".$sistema."</td>";
		$anos = array(); 
		$total_ano = array();
		while ($linha = $resultado->fetch(PDO::FETCH_ASSOC)) {
			echo "<td style='background-color: #016867;color:white;'>";
			echo "\n<a target='_self' style='color:white;' href='area_ano.php?ano=".$linha['ano']."'>".$linha['ano']."</a>";
			echo "\n</td>";			
			array_push($anos,$linha['ano']);
			array_push($total_ano,$linha['quant']);
		}	
		echo "<td style='color:#016867'>Total</td>";
		echo "</tr>";		
		
		$querySistema ="SELECT sistemas.sistema, sistemas.sis_id
			FROM  sistemas 
			ORDER BY sistemas.sistema"; 
		//Percorre todos os sistemas e imprime ano a ano.
		
		$resultadoSistema = $PDO->query($querySistema);
		while ($linhaSistema = $resultadoSistema->fetch(PDO::FETCH_ASSOC)) {
			echo "\n<tr>";
			echo "<td style='background-color: #016867;color:white;'>".$linhaSistema['sistema']."</td>";	
			$queryAplicacao ="SELECT sistemas.sis_id,sistemas.sistema,
						year(ocorrencias.data_abertura) as ano,
						count(*) as quant
				FROM ocorrencias
					JOIN problemas on ocorrencias.problema = problemas.prob_id
		            JOIN sistemas on sistemas.sis_id = problemas.prob_area
				WHERE sistemas.sistema = '".$linhaSistema['sistema']."'
				GROUP BY sistemas.sis_id,sistemas.sistema, ano
				ORDER BY sistemas.sistema, ano"; 
				
			$resultadoAplicacao = $PDO->query($queryAplicacao);
			$linhaAplicacao = $resultadoAplicacao->fetch(PDO::FETCH_ASSOC);
			$total = 0;
			for ($i = 0; $i <count($anos); $i++){	
				echo "<td style='text-align: right;'>";
      			if ($anos[$i] == $linhaAplicacao['ano']) {			
					echo "<a target='_self' href='area_b.php?areas=".
					$linhaAplicacao['sis_id']."&mes_ini=01&mes_fim=12&ano=".$anos[$i]."'>".$linhaAplicacao['quant']."</a></td>";
					if (isset($linhaAplicacao['quant'])) {
						$total = $total + $linhaAplicacao['quant'];
					}
					$linhaAplicacao = $resultadoAplicacao->fetch(PDO::FETCH_ASSOC);
				}
			}
			echo "<td style='text-align: right;'>".$total."</td>";
			echo "</tr>";
		}				
		echo "\n<tr>";
		echo "<td style='color:#016867'>Total</td>";
		$soma_ano = 0;
		for ($i = 0; $i <count($total_ano); $i++){	
			echo "<td style='text-align: right;background-color: #016867;color:white;'>".$total_ano[$i]."</td>";			
			$soma_ano+=$total_ano[$i];
		}	
		echo "<td style='color:#016867'>".$soma_ano."</td>";
		echo "</tr>";		
		
?>
</table>

</div>

<div style='text-align:center;top: 260px;'>
  <img src='graph_area.php'>
</div>

</body>
</html>
