<?php // content="text/plain; charset=ISO-8859-1"
  require_once ('../../includes/jpgraph/src/jpgraph.php');
  require_once ('../../includes/jpgraph/src/jpgraph_pie.php');
  require_once ('../../includes/jpgraph/src/jpgraph_pie3d.php');
  include ("../../includes/classes/headers.class.php");
  include ("../../includes/classes/conecta.class.php");
  include ("../../includes/classes/auth.class.php");
  include ("../../includes/classes/dateOpers.class.php");
  include ("../../includes/var_sessao.php");
  include ("../../includes/functions/funcoes.inc");
  include ("../../includes/config.inc.php");
  include ("../../includes/versao.php");
  include ("../../includes/languages/".LANGUAGE."");
  include ("../../includes/menu/menu.php");
  include ("../../includes/queries/queries.php");
  include ("../../includes/logado.php");

  $hora_inicio = ' 00:00:00';
  $hora_fim    = ' 23:59:59';            
  $d_ini = str_replace("-","/",$_GET["d_ini"]);
  $d_fim = str_replace("-","/",$_GET["d_fim"]);         
  $d_ini_nova = converte_dma_para_amd($d_ini);
  $d_fim_nova = converte_dma_para_amd($d_fim);
	   
  $d_ini_completa = $d_ini_nova.$hora_inicio;
  $d_fim_completa = $d_fim_nova.$hora_fim;
	
  $area = $_GET["areaid"];	
  $query = 	"SELECT  s.sistema, count( * ) as quantidade
FROM `ocorrencias` o
JOIN sistemas s ON o.sistema = s.sis_id
WHERE o.status NOT
IN ( 4, 12, 29 )
AND o.data_fechamento IS NULL
GROUP BY  s.sistema";
$conec = new conexao;
$PDO = $conec->conectaPDO();
  $resultado = $PDO->query($query);
  $linhas = $resultado->rowCount();  // Some (random) data

//   print_r($resultado);
  $ydata = array();
  $xdata = array();
  while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
	array_push($ydata,$row['quantidade']);
    array_push($xdata,$row['quantidade'].' - '.$row['sistema']);
  }
  $width=600;
  $height=250+12*$linhas;
 
  // Create the graph and set a scale.
  // These two calls are always required
  $graph = new PieGraph($width,$height);
  $graph->SetShadow();
  $graph->title->Set("Chamados em aberto por área");
  $graph->legend->SetPos(0.05,0.98,'left','bottom');
  $graph->legend->SetFont(FF_FONT0,FS_NORMAL,8);
  //$graph->legend->SetColumns(2);
  $p1 = new PiePlot($ydata);
  $p1->SetLegends($xdata);
  $p1->SetCenter(0.25,0.5);
  $p1->SetSize(0.20);
  $graph->Add($p1);
  $graph->Stroke();
?>
