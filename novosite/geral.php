<?php
        include ("../PATHS.php");
		include ("../includes/var_sessao.php");
        include ("../includes/functions/funcoes.inc");
		include ("../includes/javascript/funcoes.js");
		include ("../includes/queries/queries.php");
        include ("../includes/config.inc.php");
		include ("../includes/versao.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
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

<div class="topo"><img style="width: 627px; height: 100px;" alt="" src="logo2.png"></div>

<div class="menu">
<table style="text-align: left; background-color: rgb(45, 183, 229); width: 700px; height: 32px;" border="0" cellpadding="2" cellspacing="2">
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

<div class="corpo" id="corpo">
<form method="post" action="geral_b.php">
  <center>
  <table style="text-align: left; left: 51px; width: 413px;" border="0" cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td> <span style="font-weight: bold;">Todas as ocorr&ecirc;ncias do per&iacute;odo:</span> </td>
      </tr>

      <tr>

        <td>M&ecirc;s inicio:</td>

        <td>
        <select size="1" name="mes_ini">
        <option value="01">janeiro</option>
        <option value="02">fevereiro</option>
        <option value="03">mar&ccedil;o</option>
        <option value="04">abril</option>
        <option value="05">maio</option>
        <option value="06">junho</option>
        <option value="07">julho</option>
        <option value="08">agosto</option>
        <option value="09">setembro</option>
        <option value="10">outubro</option>
        <option value="11">novembro</option>
        <option value="12">dezembro</option>
        </select>

        </td>

      </tr>

      <tr>

        <td>M&ecirc;s Final:</td>

        <td>
        <select size="1" name="mes_fim">
        <option value="01">janeiro</option>
        <option value="02">fevereiro</option>
        <option value="03">mar&ccedil;o</option>
        <option value="04">abril</option>
        <option value="05">maio</option>
        <option value="06">junho</option>
        <option value="07">julho</option>
        <option value="08">agosto</option>
        <option value="09">setembro</option>
        <option value="10">outubro</option>
        <option value="11">novembro</option>
        <option value="12" selected>dezembro</option>
        </select>

        </td>

      </tr>

      <tr>

        <td>Ano:</td>

        <td>
        <select size="1" name="ano">
        <option value="2015">2015</option>
        <option value="2014">2014</option>
        <option value="2013">2013</option>
        <option value="2012">2012</option>
        <option value="2011">2011</option>
        <option value="2010">2010</option>
        <option value="2009">2009</option>
        <option value="2008">2008</option>
        <option value="2007">2007</option>
        <option value="2006">2006</option>
        </select>

        </td>

      </tr>

      <tr>

        <td></td>

        <td><br>

        <input value="Pesquisar" type="submit"></td>

      </tr>

    </tbody>
  </table>

  </center>

</form>

</div>

<div class="bottom">
<table>

  <tbody>

    <tr>

      <td> <a href="mailto:suporte@hc.ufu.br">suporte@hc.ufu.br</a></td>

    </tr>

  </tbody>
</table>

</div>

</body>
</html>
