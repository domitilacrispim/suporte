<?php

/*
 * Created on 27/10/2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include ("includes/classes/headers.class.php");
include ("includes/classes/conecta.class.php");
include ("includes/classes/auth.class.php");
include ("includes/classes/dateOpers.class.php");
include ("includes/var_sessao.php");
include ("includes/functions/funcoes.inc");
include ("includes/javascript/funcoes.js");
//include ("includes/javascript/calendar1.js");
include ("includes/config.inc.php");
include ("includes/versao.php");

include ("includes/languages/" . LANGUAGE . "");
include ("includes/menu/menu.php");

$conec = new conexao;
$PDO = $conec->conectaPDO();
?>
<body>
<h1 align=center>Busca ramais cadastrados no OCOMON</h1>

  <form action="consultaRamal.php" method="post">
  
Nome do contato:  <input type="text" name="contato" size="20" maxlength="20"/>
  
  <input type="submit" name="Enviar" value="Enviar"/>
  
  
  
  
  </form>
 
 <?

 if ($_POST['contato']<>''){
 ?>
<table>
        <TR>
                <TD width="40%" align="left" bgcolor=<?print TD_COLOR?>>Contato</TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Telefone</TD>
                <TD width="40%" align="left" bgcolor=<?print TD_COLOR?>>Setor</TD>
<?

$query = "SELECT distinct o.contato, o.telefone, l.local as setor " .
		" from ocorrencias o left join localizacao as l on l.loc_id = o.local" .
		" where o.telefone not like '%2024%' and o.contato like '%".$_POST['contato']."%' order by o.contato";
$resultado = $PDO->query($query);
$linhas = $resultado->rowCount();
$i = 0;
$j = 2;
while ($row = $resultado->fetch(PDO::FETCH_BOTH)) {
	$i++;
	if ($j % 2) {
		$color = BODY_COLOR;
		$trClass = "lin_par";
	} else {
		$color = white;
		$trClass = "lin_impar";
	}
	$j++;
	print "<tr class=" . $trClass . " id='linha" . $j . "' onMouseOver=\"destaca('linha" . $j . "');\" onMouseOut=\"libera('linha" . $j . "');\"  onMouseDown=\"marca('linha" . $j . "');\">";
	print "<TD $valign><b>" . $row['contato'] . "</b></TD>";
	print "<TD $valign><b>" . $row['telefone'] . "</b></TD>";
	print "<TD $valign><b>" . $row['setor'] . "</b></TD>";
	print "</TR>";
}
?>
        </TR>
</table>
<?}?>
</body>
</html>