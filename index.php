<html>
<?php
 /*                        Copyright 2005 Fl?vio Ribeiro
  
         This file is part of OCOMON.
  
         OCOMON is free software; you can redistribute it and/or modify
         it under the terms of the GNU General Public License as published by
         the Free Software Foundation; either version 2 of the License, or
         (at your option) any later version.
  
         OCOMON is distributed in the hope that it will be useful,
         but WITHOUT ANY WARRANTY; without even the implied warranty of
         MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
         GNU General Public License for more details.
  
         You should have received a copy of the GNU General Public License
         along with Foobar; if not, write to the Free Software
         Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  */

  	is_file( "./includes/config.inc.php" )
  	or die( "Voc? precisa configurar o arquivo config.inc.php em OCOMON/INCLUDES/para iniciar o uso do OCOMON!<br>Leia o arquivo <a href='README.TXT'>README.TXT</a> para obter as principais informa??es sobre a instala??o do OCOMON!" );
		
	session_start();
	session_destroy();
                
    include ("PATHS.php");
	include ("".$includesPath."var_sessao.php");
    include ("includes/functions/funcoes.inc");
	include ("includes/queries/queries.php");
    include ("".$includesPath."config.inc.php");
	include ("".$includesPath."versao.php");

 	$uLogado = $_SESSION['s_usuario'];
	if (empty($uLogado)) {
		$uLogado = "N?o logado";
		$logInfo = "Logon";
	} else {
		$logInfo = "Sair";
	}
?>
<head>
	<title>OCOMON</title>
	  <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
      	<link rel=stylesheet type='text/css' href='includes/css/estilos.css'>
      	<link rel=stylesheet type='text/css' href='includes/css/common.css'>

        <!-- Bootstrap core CSS -->
        <link href="includes/css/bootstrap.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="includes/css/dashboard.css" rel="stylesheet">

      	<script type='text/javascript' src='includes/javascript/funcoes1.js'></script>

    <style type="text/css">
        body{background:url(includes/imgs/bg.png) repeat; padding-top:0;}
    </style>
</head>
<body onLoad='setHeight()'>

<?php if ($_SESSION['s_logado']){ ?>

    <nav class="navbar navbar-fixed-top green">
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

<?php } else { ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center mt-50 mb-20">
                <img src="includes/imgs/hc-logo.png" >
            </div>
        </div>
    </div>

<?php } ?>

<div class="container-fluid" >
    <div class="row">


<?php if (!empty($_SESSION['s_permissoes']) || $_SESSION['s_nivel']==1){
	
		include("includes/classes/conecta.class.php");
    $conec = new conexao;
    $PDO = $conec->conectaPDO();

    $qryconf = $QRY["useropencall"];
		$execconf = $PDO->query($qryconf) or die('N?o foi poss?vel ler as informa??es de configura??o do sistema!');
		$rowconf =  $execconf->fetch(PDO::FETCH_ASSOC);
		
		//$home = "";
?>
    <table class='barra' border='0px' id='barra'>

        <tr>

            <td id='HOME' width='5%' style='{border-right: thin solid white;}'>
                <a class='barra' onMouseOver="destaca('HOME')" onMouseOut="libera('HOME')" onclick="loadIframe('menu.php?sis=h','menu','home.php', 'centro',3,'HOME')" >Home</a>
            </td>

<?php
		$sis="";
		$sisPath="";
		$sistem="home.php";
		$marca = "HOME";

		if (($_SESSION['s_ocomon']==1) && ($_SESSION['s_area'] != $rowconf['conf_ownarea'])) {
?>

			<td id='OCOMON' width='7%' style='{border-right: thin solid white;}'>
                <a class='barra'  onMouseOver="destaca('OCOMON')" onMouseOut="libera('OCOMON')" onclick="loadIframe('menu.php?sis=o','menu',' <?php echo $ocoDirPath ?>abertura.php','centro',2,'OCOMON')">Ocorr?ncias</a>
            </td>

<?php

        if ($sis=="") $sis="sis=o";
            $sisPath = $ocoDirPath;
            $sistem = "abertura.php";
            $marca = "OCOMON";
            //$home = "home=true";
		} else {    // incluir para usuario simples.
            if (($_SESSION['s_ocomon'] == 1) && ($_SESSION['s_area'] == $rowconf['conf_ownarea'])) {
                $sis = "sis=s";
                $sisPath = $ocoDirPath;
                $sistem = "abertura_user.php";
                $marca = "OCOMON";
                ?>

                <td id='OCOMON' width='7%' STYLE='{border-right: thin solid white;}'>
                    <a class='barra' onMouseOver="destaca('OCOMON')" onMouseOut="libera('OCOMON')"
                       onclick="loadIframe('menu.php?sis=s','menu','<?php echo $ocoDirPath ?>abertura_user.php','centro',3,'OCOMON')">Ocorr?ncias</a>
                </td>


<?php       } else { ?>

                <td width='7%' STYLE='{border-right: thin solid #C7C8C6; color:#C7C8C6}'>
                    Ocorr?ncias
                </td>

<?php
            }
        }

        if ($_SESSION['s_invmon']==1){ ?>

            <td id='INVMON' width='7%' STYLE='{border-right: thin solid white;}'>
                <a class='barra' onMouseOver="destaca('INVMON')" onMouseOut="libera('INVMON')" onclick="loadIframe('menu.php?sis=i','menu','<?php echo $invDirPath ?>abertura.php','centro',2,'INVMON')">Invent?rio</a>
			</td>

<?php
            //abertura.php   -   ".$invDirPath."".$invHome."
			if ($sis=="") $sis="sis=i";
			if ($sisPath=="") $sisPath=$invDirPath;
			$sistem = "abertura.php";
			if ($marca=="") $marca = "INVMON";
			//$home = "home=true";
		} else {
?>
			<td width='7%' STYLE='{border-right: thin solid #C7C8C6; color:#C7C8C6}'>
				Inventário
			</td>

<?php	}

        if ($_SESSION['s_nivel']==1) { ?>

			<td id='ADMIN' width='5%' STYLE='{border-right: thin solid white;}'>
			    <a class='barra' onMouseOver="destaca('ADMIN')" onMouseOut="libera('ADMIN')" onclick="loadIframe('menu.php?sis=a','menu','<?php echo $ocoDirPath ?>abertura_user.php','centro','1','ADMIN')">Admin</a>
			</td>

<?php
			if ($sis=="") $sis="sis=a";
			if ($sisPath=="") $sisPath="";
			if ($sistem=="") $sistem = "menu.php";
			if ($marca=="")$marca = "ADMIN";
			//$home = "home=true";
		} else {
?>

<td width='72%'></td>

<?php
        }


?>
        </tr>
    </table>
<?php
	}
?>



<?php if ($_SESSION['s_logado']){ ?>
    <table width="100%">
	    <tr>
            <td width='220px' id='centro'>
	            <iframe class='frm_menu' src='menu.php?<?php echo $sis ?>' name='menu' align='left' width='100%' height='100%' frameborder='0'
                        style='border-right: 1px solid #eaeaea;'></iframe>
	        </td>
	        <td id='centro2'>
                <iframe class='frm_centro' src='<?php echo $sisPath.$sistem ?>'  name='centro'  width='100%' height='100%' frameborder='0' style='{border-bottom: thin solid #999999;}'></iframe>
            </td>
        </tr>
    </table>
<?php } else { ?>


    <div class="container">
        <form class="form-signin" name='logar' method='post' action='<?php echo $commonPath ?>login.php?<?php echo session_id()?>' onSubmit="return valida()">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>?rea Restrita</h3>
                </div>
                <div class="panel-body">

                    <?php if ($_GET['inv']=="1") { ?>
                        <div class="alert alert-danger" role="alert">
                            <li>Usu?rio,senha ou permiss?o inv?lida! AUTH_TYPE: <?php echo AUTH_TYPE ?></li>
                        </div>
                    <?php } ?>

                    <div class="input-group mb-10">
                        <span class="input-group-addon glyphicon glyphicon-user" style="top:0" id="basic-addon1"></span>
                        <input type='text' class='form-control help' placeholder="Usu?rio" name='login' value='<?php echo $_GET['usu'] ?>' id='idLogin' required>
                    </div>

                    <div class="input-group top15">
                        <span class="input-group-addon glyphicon glyphicon-lock" style="top:0" id="basic-addon1"></span>
                        <input type='password' class='form-control help' placeholder="Senha" name='password' id='idSenha' required>
                    </div>

                    <h6>Abertura de Conta para Acesso aos Aplicativos do Hospital? Clique <strong><a href='./ocomon/geral/newUser.php' target='_blank'>Aqui!</a></strong></h6>

                </div>

                <div class="panel-footer text-right">
                    <button type="button" class="btn btn-default">Cancelar</button>
                    <button type="submit" class="btn btn-success ">Acessar</button>
                </div>

            </div>

        </form>

    </div> <!-- /container -->


<?php					 		
	}
?>



    </div>
</div>

<script type="text/javascript">
<!--
var GLArray = new Array();
function loadIframe(url1,iframeName1, url2,iframeName2,ACCESS,ID) {
	
	var nivel_user = '<?print $_SESSION['s_nivel'];?>';
	var HOM = document.getElementById('HOME');
	var OCO = document.getElementById('OCOMON');
	var INV = document.getElementById('INVMON');
	var ADM = document.getElementById('ADMIN');
	
	if (nivel_user <= ACCESS) {
  
	  marca(ID);
	  if (HOM != null)
		if (ID != "HOME") HOM.style.background ="";
	  if (OCO != null)
		if (ID != "OCOMON") OCO.style.background ="";
	  if (INV != null)
		if (ID != "INVMON") INV.style.background ="";
	  if (ADM != null)
		if (ID != "ADMIN") ADM.style.background ="";
  
  
	  if (iframeName2!=""){
		if ((window.frames[iframeName1]) && (window.frames[iframeName2])) {
			window.frames[iframeName1].location = url1;  
			window.frames[iframeName2].location = url2;
			return false;
		}
	  } else
		if (window.frames[iframeName1]) {
			window.frames[iframeName1].location = url1;  
			return false;
		}  
  
		else return true;
	} else {
		window.alert('Acesso indispon?vel!');
		return true;
	
	}
	
}	


		function setHeight(){
		
		var logado = '<?print $_SESSION['s_logado'];?>';
			if (logado!=1)
				document.logar.login.focus();		
			var obj = document.getElementById('centro');
			if (obj!=null) {				
				obj.style.height = screen.availHeight-170;
			}
			var obj2 = document.getElementById('centro2');
			if (obj2!=null) {				
				obj2.style.height = screen.availHeight-170;
			}
			marca('<?print $marca;?>');
		
		}

		 function popup(pagina)	{ //Exibe uma janela popUP
			x = window.open(pagina,'Sobre','width=800,height=600,scrollbars=yes,statusbar=no,resizable=no');
			x.moveTo(10,10);
			document.help.selectedIndex=0;
			return false
		 }

		function mini_popup(pagina)	{ //Exibe uma janela popUP
			x = window.open(pagina,'_blank','dependent=yes,width=400,height=260,scrollbars=yes,statusbar=no,resizable=yes');
			
			x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
			//x.moveTo(100,100);
			
			return false
		}		 

		function destaca(id){
				var obj = document.getElementById(id);
				obj.style.background = '#009966';//C7C8C6
		}			
		
		function libera(id){
			if ( verificaArray('', id) == false ) {
				var obj = document.getElementById(id);
				obj.style.background = '';
			}
		}					
		
		function marca(id){
			var obj = document.getElementById(id);
				verificaArray('guarda', id);
				if (obj!=null)
					obj.style.background = '#009966';
				verificaArray('libera',id);
		}		
		
		function verificaArray(acao, id) {
			var i;
			var tamArray = GLArray.length;
			var existe = false;
			
			for(i=0; i<tamArray; i++) {
				if ( GLArray[i] == id ) {
					existe = true;
					break;
				}
			}
			
			if ( (acao == 'guarda') && (existe==false) ) {  //
				GLArray[tamArray] = id;
			} else if ( (acao == 'libera') ) {
				var temp = new Array(tamArray-1); //-1
				var pos = 0;
				for(i=0; i<tamArray; i++) {
					if ( GLArray[i] == id ) {
						temp[pos] = GLArray[i];
						pos++;
					}
				}
				
				GLArray = new Array();
				var pos = temp.length;
				for(i=0; i<pos; i++) {
					GLArray[i] = temp[i];
				}
			}
			
			return existe;
		}

	function valida(){
		
		var ok = validaForm('idLogin','ALFANUM','Usu?rio',1) 
		if (ok) var ok = validaForm('idSenha','ALFANUM','Senha',1);
		
		return ok;
	}		
		
-->	 
</script>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="includes/js/jquery-2.1.0.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="includes/js/bootstrap.min.js"></script>

</body>
</html>