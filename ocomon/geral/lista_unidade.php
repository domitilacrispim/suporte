<?php // content="text/plain; charset=ISO-8859-1"
  header('Cache-Control: no-cache');
  header('Content-type: application/xml; charset="ISO-8859-1"',true);

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

	
  $area = $_GET["areaid"];	
  $query = 	"SELECT inst_cod, 
				inst_nome,
				sistema
	FROM instituicao 
	WHERE inst_status = 1
	    AND sistema = ".$area;
  $resultado = mysql_query($query);     

  $linhas = mysql_num_rows($resultado);  // Some (random) data

  $cod_unidades = array();
  $desc_unidades = array();

  echo "<select  class='select' name='institui' size=1>";
  while ($row = mysql_fetch_array($resultado)) {
	echo "<option value=".$row['inst_cod'].">"; 
       echo $row['inst_nome'];
       echo "</option>";
	
  }
  echo "</select>";
?>
