<?php // content="text/plain; charset=ISO-8859-1"
  require_once ('../../includes/jpgraph/src/jpgraph.php');
  require_once ('../../includes/jpgraph/src/jpgraph_pie.php');
  include ("../../includes/classes/headers.class.php");
  include ("../../includes/classes/conecta.class.php");
  include ("../../includes/classes/auth.class.php");
  include ("../../includes/classes/dateOpers.class.php");
  include ("../../includes/var_sessao.php");
  include ("../../includes/functions/funcoes.inc");
  //  include ("../../includes/javascript/funcoes.js");
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
		
  $query = 	"SELECT count(*)   AS quantidade, 
	          s.sistema  AS area,
       	   (count(*)*100)/(select count(*) from ocorrencias o1 
			          where o1.data_fechamento >= '".$d_ini_completa."' 
       			     and o1.data_fechamento <= '".$d_fim_completa."') as perc
	   	FROM ocorrencias AS o, 
              	  localizacao AS l, 
	                sistemas    AS s, 
       	         problemas   AS p
	   	WHERE p.prob_area = s.sis_id
           	  AND o.local     = l.loc_id 
           	  AND o.problema  = p.prob_id
           	  AND o.data_fechamento >= '".$d_ini_completa."'  and o.data_fechamento <= '".$d_fim_completa."'
           	  AND o.data_atendimento is not null 
		GROUP BY s.sistema 
		ORDER BY quantidade DESC";
		
  //print_r($query);
  $resultado = mysql_query($query);     
  $linhas = mysql_num_rows($resultado);  // Some (random) data

//   print_r($resultado);
  $ydata = array();
  $xdata = array();
  while ($row = mysql_fetch_array($resultado)) {
	array_push($ydata,$row['quantidade']);
    array_push($xdata,$row['quantidade'].' - '.$row['area']);
  }
//  print_r( $xdata);
//  $ydata = array(11,3,8,12,5,1,9,13,5,7,8);
 
  // Size of the overall graph
  $width=550;
  $height=300;
 
  // Create the graph and set a scale.
  // These two calls are always required
  $graph = new PieGraph($width,$height);
  $graph->SetShadow();
  $graph->title->Set("Chamados fechados por �rea ".$_GET["d_ini"]." a ".$_GET["d_fim"]);

  $p1 = new PiePlot($ydata);
  $p1->SetLegends($xdata);
  $p1->SetCenter(0.25,0.5);
  //$p1->SetSize(0.13);
  $graph->Add($p1);
  $graph->Stroke();
?>
