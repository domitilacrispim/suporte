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
  <title>Informatica</title>


  <style type="text/css">
div {left: 50px; position: absolute;}
div.topo {top: 0px; }
div.menu {top: 106px; }
div.corpo {top: 200px; }
div.bottom {top: 540px; } :link {color: white; text-decoration: none;}
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

<div class="topo">
   <img style="width: 627px; height: 100px;" alt="" src="logo2.png">
</div>

<div class="menu">
<table style="text-align: left; background-color: rgb(45, 183, 229); width: 700px; height: 32px;" border="0" cellpadding="2" cellspacing="2">

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
<?php 
		include("../includes/classes/conecta.class.php");
		$conexao= new conexao;
		$conexao->conecta('MYSQL');

		$query ="SELECT distinct year(ocorrencias.data_abertura) as ano,count(*) as quant
			 FROM ocorrencias
				JOIN localizacao ON ocorrencias.local = localizacao.loc_id
		     GROUP BY year(ocorrencias.data_abertura)
		     ORDER BY year(ocorrencias.data_abertura)"; 
		$resultado = mysql_query($query); 
		$local = "";

		echo "<tr>";
		echo "<td>".$local."</td>";
		$anos = array(); 
		$total_ano = array();
		while ($linha = mysql_fetch_array($resultado)) { 
			echo "<td style='background-color: rgb(45, 183, 229);color:white;'>".$linha['ano']."</td>";			
			array_push($anos,$linha['ano']);
			array_push($total_ano,$linha['quant']);
		}	
		echo "<td>Total</td>";
		echo "</tr>";		
		
		$queryLocal ="SELECT localizacao.local, localizacao.loc_id
			FROM  localizacao 
			ORDER BY localizacao.local"; 
		//Percorre todos os locais e imprime ano a ano.
		
		$resultadoLocal = mysql_query($queryLocal); 
		while ($linhaLocal = mysql_fetch_array($resultadoLocal)) { 
			echo "\n<tr>";
			echo "<td style='background-color: rgb(45, 183, 229);color:white;'>".$linhaLocal['local']."</td>";	
			$queryAplicacao ="SELECT localizacao.loc_id,localizacao.local,
						year(ocorrencias.data_abertura) as ano,
						count(*) as quant
				FROM ocorrencias
					JOIN localizacao on ocorrencias.local = localizacao.loc_id
				WHERE localizacao.loc_id = '".$linhaLocal['loc_id']."'
				GROUP BY localizacao.loc_id,localizacao.local, ano
				ORDER BY localizacao.local, ano"; 
			$resultadoAplicacao = mysql_query($queryAplicacao); 
			$linhaAplicacao = mysql_fetch_array($resultadoAplicacao);
			$total = 0;
			for ($i = 0; $i <count($anos); $i++){				
				echo "<td style='text-align: right;'>";
      				if ($anos[$i] == $linhaAplicacao['ano']) {
				echo "<a target='_self' href='local_b.php?local=".
					$linhaAplicacao['loc_id']."&mes_ini=01&mes_fim=12&ano=".$anos[$i]."'>".$linhaAplicacao['quant']."</a></td>";
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
