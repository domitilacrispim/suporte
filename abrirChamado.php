<!DOCTYPE html>
<html lang="pt-br">
<head>
<?
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
	
	include ("includes/languages/".LANGUAGE."");
	include ("includes/menu/menu.php");
	
	include ("includes/queries/queries.php");			

    $conec = new conexao;
    $PDO = $conec->conectaPDO();

?>
    <title>OCOMON</title>
    <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">

    <link href="includes/css/bootstrap.css" rel="stylesheet">
    <link rel=stylesheet type='text/css' href='includes/css/dashboard.css'>
    <link rel=stylesheet type='text/css' href='includes/css/estilos.css'>
    <link rel=stylesheet type='text/css' href='includes/css/common.css'>
    <link rel=stylesheet type='text/css' href='includes/css/menu.css'>



    <script language="JavaScript" src="includes/javascript/calendar1.js"></script>

    <style type="text/css">
        body{background:url(includes/imgs/bg.png) repeat; padding-top:0;}
    </style>

    <script language=javascript>

        function mascara(o,f){
            v_obj=o
            v_fun=f
            setTimeout("execmascara()",1)
        }

        function execmascara(){
            v_obj.value=v_fun(v_obj.value)
        }
        function leech(v){
            v=v.replace(/o/gi,"0")
            v=v.replace(/i/gi,"1")
            v=v.replace(/z/gi,"2")
            v=v.replace(/e/gi,"3")
            v=v.replace(/a/gi,"4")
            v=v.replace(/s/gi,"5")
            v=v.replace(/t/gi,"7")
            return v
        }

        function soNumeros(v){
            return v.replace(/\D/g,"")
        }


        function Validar(){

            if(form1.contato.value == ""){
                alert("Informe seu nome por gentileza.");
                form1.contato.focus();
                return false;
            }

            if(form1.telefone.value == ""){
                alert("Informe seu ramal por gentileza.");
                form1.telefone.focus();
                return false;
            }

            if(form1.email.value == ""){
                alert("Informe seu Email por gentileza.");
                form1.email.focus();
                return false;
            }

            if(form1.local.value == ""){
                alert("Informe seu setor por gentileza.");
                form1.local.focus();
                return false;
            }

            if(form1.descricao.value == ""){
                alert("Informe o problema/servi�o por gentileza.");
                form1.descricao.focus();
                return false;
            }
        }

    </script>

</head>
<body onLoad="javascript:form1.contato.focus();"  onSubmit="return Validar();">

<nav class="navbar navbar-fixed-top green border">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <a href="#"><img  class="mt-5" src="includes/imgs/logo.png"></a>
            </div>

            <div class="col-sm-6">
                <div style="float:right; margin-top: 25px">
                    <a href='<?php print $commonPath?>logout.php' class="logoff">
                        <?php print $logInfo?> <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</nav>


<div class="container-fluid">
    <div class="row">

        <?php include("menuChamado.php"); ?>

        <div class="main">

                <div class="row">
                    <div class="col-md-6">
                        <h1 class="aw-page-title">Ocomon - Módulo de ocorrências</h1>
                    </div>
                    <div class="col-md-6 text-right">
                        <p><?php echo transvars(date ("l d/m/Y H:i"),$TRANS_WEEK) ?></p>
                    </div>
                </div>

                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="panel-title">Abertura de ocorrências:</h4>
                    </div>

                    <form name='form1' method='POST' action='gravaChamado.php'  onSubmit="return Validar();">

                        <div class="panel-body">

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="idContato">Nome Completo:</label>
                                    <input type='text' class='form-control' name='contato' id='idContato' required value='<?php echo $contato ?>'>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="idTelefone">Ramal:</label>
                                    <input type='text' class='form-control' name='telefone' id='idTelefone' required onkeypress="mascara(this,soNumeros)">
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="idTelefone">Email:</label>
                                    <input type='text' class='form-control' name='email' required id='idEmail'>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="idTelefone">Setor:</label>

                                    <SELECT class='form-control' name='local' id='idLocal' size='1' required>
                                        <option value="" selected>- Selecione um setor -</option>
                                        <?			    $data = $conec->$conPDO->query("SELECT l .  * , r.reit_nome, pr.prior_nivel AS prioridade, d.dom_desc AS dominio, pred.pred_desc as predio
						FROM localizacao AS l
						LEFT  JOIN reitorias AS r ON r.reit_cod = l.loc_reitoria
						LEFT  JOIN prioridades AS pr ON pr.prior_cod = l.loc_prior
						LEFT  JOIN dominios AS d ON d.dom_cod = l.loc_dominio
						LEFT JOIN predios as pred on pred.pred_cod = l.loc_predio
						WHERE loc_status not in (0) 
						ORDER  BY LOCAL ");
                                        foreach($data as $row) {
                                            print_r($row);
                                        }
                                        // while ($rowi = mysql_fetch_array($resultado))
                                        //{
                                          //  print "<option value='".$rowi['loc_id']."'";
                                           // if ($rowi['loc_id'] == $invLoc) print " selected";
                                            //print ">".$rowi['local']." - ".$rowi['predio']."</option>";
                                        //}
                                        ?>
                                    </SELECT>


                                </div>
                            </div>

      <!--tr>
      <td colspan=2 bgcolor=#ECECDB><table width="100%" border="0" class="style1">
        <tr>
          <td class="style1" bgcolor=#ECECDB> <b> Selecione o Sistema:<br>
            </b>
              <table width="99%" border="0" class="style1">
                <tr>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Sih </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">            
                  Sysmat </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Siaf </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Custo </td>
                </tr>
                <tr>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Escala </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">            
                  Contabilidade </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Ocomon </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Refeitorio </td>
                </tr>
                <tr>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            SGE </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">                    Sistema de Folha </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Siaenf </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton"> 
                    Nosologia</td>
                </tr>
                <tr>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Intranet </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">            
                  Ouvidoria </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
            Cseih </td>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
                    Servi&ccedil;os Prestados </td>
                </tr>
                <tr>
                  <td><input name="radiobutton" type="radio" value="radiobutton">
                    Site do HC </td>
                  <td colspan="3"><input name="radiobutton" type="radio" value="radiobutton">
                    Outros (especificar na descri&ccedil;&atilde;o qual o nome do sistema) </td>
                </tr>
              </table>            </td>
        </tr>
      </table></td>
    </tr-->

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="idTelefone">Problema/servi�o solicitado:</label>
                                    <textarea class='form-control' name='descricao' id='idDescricao' required></textarea>
                                </div>
                            </div>
                                <p class="text-danger pull-right mr-10">Obs: Todos os campos s�o obrigat&oacute;rios </p>

</div>


                        <div class="form-actions right">

                            <input type="hidden" name="IP" value="<?echo $_SERVER['REMOTE_ADDR'];?>">

                            <input type="submit" name="enviar" value="Enviar" class="btn btn-success" />

                            <button type="button" name='fecha' class="btn btn-default" OnClick="javascript:window.close()">Fechar</button>

                        </div>


                    </form>

                </div>

                <div class="alert alert-danger">Para cria��o de usu�rios no sistema, favor deixar o nome completo de um usu�rio que j� tenha conta para ser feito a c�pia </div>

        </div>

    </div>
</div>
</body>
</html>