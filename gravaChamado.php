<!DOCTYPE html>
<html>
<head>
<?php
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

	$data = date ("d/m/Y H:i"); //,$TRANS_WEEK);

	$query = "INSERT INTO ocorrencias (problema, descricao, instituicao, equipamento, sistema, contato, telefone, local, operador, data_abertura, data_fechamento, status, data_atendimento, aberto_por ) values (54,'".noHtml($_POST['descricao'])."  Email: ".noHtml($_POST['email']). " IP da m�quina: ".$_POST['IP']."',1,NULL,11,'".noHtml($_POST['contato'])."',' ".$_POST['telefone']."',$local,1,NOW(),NULL,1,NULL,1)";
	
	$resultado = mysql_query($query) or die ("ERRO NA TENTATIVA DE INCLUIR A ocorrência NO SISTEMA!");
	$numero = mysql_insert_id();
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
    <script type="text/javascript">
        function popup(pagina)	{ //Exibe uma janela popUP
            x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
            //x.moveTo(100,100);
            x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
            return false
        }

        function popup_alerta(pagina)	{ //Exibe uma janela popUP
            x = window.open(pagina,'_blank','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
            //x.moveTo(100,100);
            x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
            return false
        }
    </script>
</head>
<body>
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
                    <h1 class="aw-page-title">Ocomon - M�dulo de ocorrências</h1>
                </div>
                <div class="col-md-6 text-right">
                    <p><?php echo transvars(date ("l d/m/Y H:i"),$TRANS_WEEK) ?></p>
                </div>
            </div>

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4 class="panel-title">Abertura de ocorrências: <?php echo $subCallMsg?></h4>
                </div>

                <div class="panel-body">

                    <? if ($numero > 0 ){?>
                    <div class="alert alert-success" style="font-size: 14px">
                        ocorrência incluida com sucesso! N�mero: <strong><?echo $numero ?></strong>
                        <br>Anote este n�mero para consultar o andamento desta ocorrência.
                    </div>


                    <?}else{?>
                        <div class="alert alert-danger" style="font-size: 14px">
                            Erro ao incluir ocorrência!
                            <br> Tente novamente e se o erro persistir contate o Suporte.
                        </div>

                    <?}?>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
