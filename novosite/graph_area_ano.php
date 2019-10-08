<?php // content="text/plain; charset=ISO-8859-1"
   require_once ('../includes/jpgraph/src/jpgraph.php');
  require_once ('../includes/jpgraph/src/jpgraph_bar.php');
  include ("../includes/classes/headers.class.php");
  include ("../includes/classes/conecta.class.php");
  include ("../includes/classes/auth.class.php");
  include ("../includes/classes/dateOpers.class.php");
  include ("../includes/var_sessao.php");
  include ("../includes/functions/funcoes.inc");
  include ("../includes/config.inc.php");
  include ("../includes/versao.php");
  include ("../includes/languages/".LANGUAGE."");
  include ("../includes/menu/menu.php");
  include ("../includes/queries/queries.php");
  include ("../includes/logado.php");
		
  $ano = $_GET['ano']; 
  $query = 	"SELECT distinct month(ocorrencias.data_abertura) as mes,count(*) as quant
			 FROM ocorrencias
				JOIN problemas on ocorrencias.problema = problemas.prob_id
	           	JOIN sistemas on sistemas.sis_id = problemas.prob_area
			WHERE year(ocorrencias.data_abertura) = ".$ano.
	        " GROUP BY month(ocorrencias.data_abertura)
	         ORDER BY month(ocorrencias.data_abertura)";
	
//  print_r($query);
  $resultado = mysql_query($query);     
  $linhas = mysql_num_rows($resultado);  // Some (random) data

//   print_r($resultado);
  $legenda = array();
  
  $ydata = array();
  $xdata = array();
  while ($row = mysql_fetch_array($resultado)) {
	array_push($ydata,array() );
	array_push($xdata,$row['mes']);
  }

	$querySistema ="SELECT sistemas.sistema, sistemas.sis_id
			FROM  sistemas 
			ORDER BY sistemas.sistema"; 
		//Percorre todos os sistemas e imprime ano a ano.
		$resultadoSistema = mysql_query($querySistema); 
		$y = 0;
		while ($linhaSistema = mysql_fetch_array($resultadoSistema)) { 
			array_push($legenda,$linhaSistema['sistema']);
			$queryAplicacao ="SELECT sistemas.sis_id,sistemas.sistema,
						month(ocorrencias.data_abertura) as mes,
						count(*) as quant
				FROM ocorrencias
					JOIN problemas on ocorrencias.problema = problemas.prob_id
		            JOIN sistemas on sistemas.sis_id = problemas.prob_area
				WHERE sistemas.sistema = '".$linhaSistema['sistema']."'
				  AND year(ocorrencias.data_abertura) = ".$ano.
			"	GROUP BY sistemas.sis_id,sistemas.sistema, mes
				ORDER BY sistemas.sistema, mes"; 
				
			$resultadoAplicacao = mysql_query($queryAplicacao); 
			$linhaAplicacao = mysql_fetch_array($resultadoAplicacao);
			$total = 0;			
			for ($i = 0; $i <count($xdata); $i++){					
      			if ($xdata[$i] == $linhaAplicacao['mes']) {			
					if (isset($linhaAplicacao['quant'])) {
						array_push($ydata[$y],$linhaAplicacao['quant'] );
					} else {
						array_push($ydata[$y],0 );
					}
					$linhaAplicacao = mysql_fetch_array($resultadoAplicacao);
				} else {
					array_push($ydata[$y],0 );
				}
				
			}
			$y = $y + 1;
		}				
  

  // Size of the overall graph
  $width=700;
  $height=500;
  // Create the graph and set a scale.
  // These two calls are always required
  $graph = new Graph($width,$height,'auto');
  $graph->SetScale('textlin');

  $theme_class=new UniversalTheme;
  $graph->SetTheme($theme_class);
  $graph->SetShadow();
  $graph->xaxis->title->Set('�reas');
  $graph->xaxis->SetTickLabels($xdata);
  $graph->yaxis->title->Set('Quant');

  
  $p = array();  
  for ($i = 0; $i <$y; $i++) {	
	$g = new BarPlot($ydata[$i]);
	$g->SetLegend($legenda[$i]);
	array_push($p , $g );  
  }

$graph->SetBox(false);
	
$graph->ygrid->SetFill(false);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);
	
// Create the grouped bar plot
$gbplot = new GroupBarPlot($p);
// ...and add it to the graPH
$graph->Add($gbplot);

$graph->title->Set("Evolu��o dos chamados por �rea ".$ano);

// Display the graph
$graph->Stroke();

?>
