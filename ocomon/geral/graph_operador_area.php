<?php // content="text/plain; charset=ISO-8859-1"
  require_once ('../../includes/jpgraph/src/jpgraph.php');
  require_once ('../../includes/jpgraph/src/jpgraph_bar.php');
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
		
  $query = 	"SELECT count(*)   AS quantidade, 
            s.sistema,
			u.nome,
       	   (count(*)*100)/(select count(*) from ocorrencias o1 
			          where o1.data_fechamento >= '".$d_ini_completa."' 
       			     and o1.data_fechamento <= '".$d_fim_completa."') as perc
	   	FROM ocorrencias AS o, 
			localizacao AS l, 
	        sistemas    AS s, 
       	    problemas   AS p,
			usuarios    AS u
	   	WHERE u.AREA = s.sis_id
           	AND o.local     = l.loc_id 
           	AND o.problema  = p.prob_id
			AND o.operador  = u.user_id
           	AND o.data_fechamento >= '".$d_ini_completa."'  and o.data_fechamento <= '".$d_fim_completa."'
           	AND o.data_atendimento is not null ";
	if (!empty($_GET["area"]) and ($_GET["area"] != -1)) // variavel do select name
	{ 
	    $query .= " and o.sistema = ".$_GET["area"];
	} 
				  
	$query .= "	GROUP BY s.sistema, u.nome
		ORDER BY s.sistema,quantidade DESC";
		
  //print_r($query);
  $resultado = mysql_query($query);     
  $linhas = mysql_num_rows($resultado);  // Some (random) data

//   print_r($resultado);
  $ydata = array();
  $xdata = array();
  while ($row = mysql_fetch_array($resultado)) {
	array_push($ydata,$row['quantidade']);
    array_push($xdata,$row['nome']);
  }
//  print_r( $xdata);
//  $ydata = array(11,3,8,12,5,1,9,13,5,7,8);
 
  // Size of the overall graph
  $width=800;
  $height=1000;
 
  // Create the graph and set a scale.
  // These two calls are always required
  $graph = new Graph($width,$height,'auto');
  $graph->SetScale('textlin');
  $graph->Set90AndMargin(250,20,100,30);
  //$graph->img->SetMargin(80,30,30,40);
  $graph->SetShadow();
  $graph->title->Set("Chamados fechados por colaborador ".$_GET["d_ini"]." a ".$_GET["d_fim"]);
  $graph->xaxis->title->Set('Operadores');
  $graph->xaxis->SetTickLabels($xdata);
  $graph->yaxis->title->Set('Ocor');
  
  $p1 = new BarPlot($ydata);  
  $p1->SetFillColor('orange');
  $p1->SetFillGradient('orange','white',GRAD_VERT);
 // $p1->SetLegends($xdata);

  $graph->Add($p1);
  $graph->Stroke();
?>
