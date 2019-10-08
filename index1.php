<?php
 /*                        Copyright 2005 Fl�vio Ribeiro
  
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
	or die( "Voc� precisa configurar o arquivo config.inc.php em OCOMON/INCLUDES/para iniciar o uso do OCOMON!<br>Leia o arquivo <a href='README.TXT'>README.TXT</a> para obter as principais informa��es sobre a instala��o do OCOMON!" );  
  
				
				session_start();
				session_destroy();
                
                include ("PATHS.php");
				include ("".$includesPath."var_sessao.php");
                include ("includes/functions/funcoes.inc");
				include ("includes/javascript/funcoes.js");
				include ("includes/queries/queries.php");
                include ("".$includesPath."config.inc.php");
				include ("".$includesPath."versao.php");

 $uLogado = $_SESSION['s_usuario'];
 if (empty($uLogado)) {
	$uLogado = "N�o logado";
	$logInfo = "<font color='white'>Logon</font>";
 } else
	$logInfo = "<font color='red'>Logoff</font>";

	
//print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"http://www.w3.org/TR/html4/loose.dtd\">";
print "<html>";
print "<head>";

print "<title>OCOMON ".VERSAO."</title>";
print "<link rel=stylesheet type='text/css' href='includes/css/estilos.css'>";
print "<link rel=stylesheet type='text/css' href='includes/css/common.css'>";
print "</head><body onLoad=\"setHeight()\">";

print "<table width='100%' border='0px' id='geral'><tr><td colspan='2'>";

//print "<table class='topo' border='0px' id='cabecalho'>
//	<tr>
//		<td><img src='MAIN_LOGO.png' height='112' width='700'></td>
//		<td align='center'><font color='white'> Usu�rio:</font><b> ".$uLogado."</b></td><td>|</td>
//		<td><a href='".$commonPath."logout.php'>".$logInfo."<img src='includes/icons/password2.png' style=\"{vertical-align:middle;}\" height='15' width='15' border='0'></a></td><td>|</td>
//		<td><select class='help' name='help' onChange=\"popup('sobre.php')\">
//		<option value=1 selected>Ajuda</option>
//		<option value=2>Sobre</option>
//		</select>
//		</td>
//	</tr></table>";


print "<table class='topo' border='0px' id='cabecalho'>
   
    <td rowspan='2'><img src='pixture_reloaded_logo.png' height='67' width='600'>
	<td><font color='white'> Usu�rio:</font><b> ".$uLogado."</b> 
    <td><a href='".$commonPath."logout.php'>".$logInfo."<img src='includes/icons/password2.png' style=\"{vertical-align:middle;}\" height='15' width='15' border='0'></a>
  <tr> 
    <td colspan='2'><div align='right'><img src='MAIN_LOGO2.png' height='30' width='90'></div>  
  <tr></tr>
</table>";

print "<table class='barra' border='0px' id='barra'><tr>";
        

	if (empty($_SESSION['s_permissoes'])&& $_SESSION['s_nivel']!=1){
		print "<td width='5%'>&nbsp;</td>";		
		print "<td width='7%'>&nbsp;</td>";		
		print "<td width='7%'>&nbsp;</td>";		
		print "<td width='5%' >&nbsp;</td>";		
		print "<td width='76%'>&nbsp;</td>";
						
	} else{
	
		include("includes/classes/conecta.class.php");
		$conec = new conexao;
		$conec->conecta('MYSQL');			

		$qryconf = $QRY["useropencall"];
		$execconf = mysql_query($qryconf) or die('N�o foi poss�vel ler as informa��es de configura��o do sistema!');
		$rowconf = mysql_fetch_array($execconf);
		
		//$home = "";
	
		print "<td id='HOME'width='5%' STYLE='{border-right: thin solid white;}'><a class='barra' onMouseOver=\"destaca('HOME')\" onMouseOut=\"libera('HOME')\" onclick=\"loadIframe('menu.php?sis=h','menu','home.php', 'centro',3,'HOME')\" >&nbsp;Home&nbsp;</a></td>";
		$sis="";
		$sisPath="";
		$sistem="home.php";
		$marca = "HOME";
		if (($_SESSION['s_ocomon']==1) && ($_SESSION['s_area'] != $rowconf['conf_ownarea'])) {
			print "<td id='OCOMON' width='7%' STYLE='{border-right: thin solid white;}'><a class='barra'  onMouseOver=\"destaca('OCOMON')\" onMouseOut=\"libera('OCOMON')\" onclick=\"loadIframe('menu.php?sis=o','menu','".$ocoDirPath."abertura.php','centro',2,'OCOMON')\">&nbsp;ocorrências&nbsp;</a></td>";
			if ($sis=="") $sis="sis=o";
			$sisPath = $ocoDirPath;
			$sistem = "abertura.php";
			$marca = "OCOMON";
			//$home = "home=true";
		} else 	// incluir para usuario simples.
		if (($_SESSION['s_ocomon']==1) && ($_SESSION['s_area'] == $rowconf['conf_ownarea'])) {
			print "<td id='OCOMON' width='7%' STYLE='{border-right: thin solid white;}'><a class='barra'  onMouseOver=\"destaca('OCOMON')\" onMouseOut=\"libera('OCOMON')\" onclick=\"loadIframe('menu.php?sis=s','menu','".$ocoDirPath."abertura_user.php','centro',3,'OCOMON')\">&nbsp;ocorrências&nbsp;</a></td>";
			$sis="sis=s";
			$sisPath = $ocoDirPath;
			$sistem = "abertura_user.php";
			$marca = "OCOMON";
		} else 		
			print "<td width='7%' STYLE='{border-right: thin solid #C7C8C6; color:#C7C8C6}'>&nbsp;ocorrências&nbsp;</td>";
		
		if ($_SESSION['s_invmon']==1){
			print "<td id='INVMON' width='7%' STYLE='{border-right: thin solid white;}'><a class='barra' onMouseOver=\"destaca('INVMON')\" onMouseOut=\"libera('INVMON')\" onclick=\"loadIframe('menu.php?sis=i','menu','".$invDirPath."abertura.php','centro',2,'INVMON')\">&nbsp;Invent�rio&nbsp;</a></td>"; //abertura.php   -   ".$invDirPath."".$invHome."
			if ($sis=="") $sis="sis=i";
			if ($sisPath=="") $sisPath=$invDirPath;
			$sistem = "abertura.php";
			if ($marca=="") $marca = "INVMON";
			//$home = "home=true";
		} else
			print "<td width='7%' STYLE='{border-right: thin solid #C7C8C6; color:#C7C8C6}'>&nbsp;Invent�rio&nbsp;</td>";
		if ($_SESSION['s_nivel']==1) {
			print "<td id='ADMIN' width='5%' STYLE='{border-right: thin solid white;}'><a class='barra' onMouseOver=\"destaca('ADMIN')\" onMouseOut=\"libera('ADMIN')\" onclick=\"loadIframe('menu.php?sis=a','menu','','','1','ADMIN')\">&nbsp;Admin&nbsp;</a></td>";
			if ($sis=="") $sis="sis=a";
			if ($sisPath=="") $sisPath="";
			if ($sistem=="") $sistem = "menu.php";
			if ($marca=="")$marca = "ADMIN";
			//$home = "home=true";
		} else	
			print "<td width='5%' STYLE='{border-right: thin solid #C7C8C6; color:#C7C8C6}'>&nbsp;Admin&nbsp;</td>";	
				
		print "<td width='72%'></td>";
		$conec->desconecta('MYSQL');
	}
	print "</tr></table>";

print "</td></tr>";



if ($_SESSION['s_logado']){
	print "<tr>";
       print "<td width='15%' id='centro'>";
	print "<iframe class='frm_menu' src='menu.php?".$sis."' name='menu' align='left' width='100%' height='100%' frameborder='0' style='{border-right: thin solid #999999; border-bottom: thin solid #999999;}'></iframe>";
	print "</td>";
	print "<td width='85%'>";
       print "<iframe class='frm_centro' src='".$sisPath."".$sistem."'  name='centro' align='center' width='100%' height='100%' frameborder='0' style='{border-bottom: thin solid #999999;}'></iframe>";
       print "</td>";
	print "</tr>";
	} else {
		print "<form name='logar' method='post' action='".$commonPath."login.php?".session_id()."' onSubmit=\"return valida()\">";
		print "<tr><td><table id='login'>";
		if ($_GET['inv']=="1") {
			print "<tr align=\"center\">".
				"<td colspan=2 align=\"center\"><font color='red'>Usu�rio,senha ou permiss�o inv�lida! <br>AUTH_TYPE: ".AUTH_TYPE."<font></td>".
				"</tr>";
		}
		//print "<br><br><br>";
		print "<tr><td>Usu�rio:</td><td><input type='text' class='help' name='login' value='".$_GET['usu']."' id='idLogin'></td></tr>
			<tr><td>Senha:</td><td><input type='password' class='help' name='password'  id='idSenha'></td><td><input type='submit' class='blogin' value='login'></td></tr>";
		
			print "<tr><td colspan='3'>&nbsp;</td></tr>";
			print "<tr><td colspan='3'>Abertura de Conta para Acesso aos Aplicativos do Hospital? Clique <a href='./ocomon/geral/newUser.php' target='_blank'><b><u><font color='#0000FF'>Aqui!</font></u></b></a></td></tr>";		
		print "</table></td></tr>";		
		print "</form>";
	}
print "<tr><td colspan='2' align='center'><a href='http://ocomonphp.sourceforge.net' target='_blank'>OcoMon</a> - Monitor de ocorrências e Invent�rio de equipamentos de inform�tica.<br>Vers�o: ".VERSAO." - Licen�a GPL</td></tr>";
print "</table>";

?>
<script type="text/javascript">
<!--
var GLArray = new Array();
function loadIframe(url1,iframeName1, url2,iframeName2,ACCESS,ID) {
	
	var nivel_user = '<?php echo $_SESSION['s_nivel'];?>';
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
		window.alert('Acesso indispon�vel!');
		return true;
	
	}
	
}	


		function setHeight(){
		var obj = document.getElementById('centro');
		var logado = '<?php echo $_SESSION['s_logado'];?>';
			if (logado!=1)
				document.logar.login.focus();		
			if (obj!=null)
				obj.style.height = screen.availHeight - 300;
			marca('<?php echo $marca;?>');
		
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
		
		var ok = validaForm('idLogin','ALFANUM','Usu�rio',1) 
		if (ok) var ok = validaForm('idSenha','ALFANUM','Senha',1);
		
		return ok;
	}		
		
-->	 
</script>

<!--
var obj = document.getElementById('tabela_ficha');
           var objOpcoes = document.getElementById('opcoesSel');
                     var valor = objOpcoes.style.height;
           valor = valor.replace('px', '');
           obj.style.height = screen.availHeight - valor - 300;
                     var form = document.forms[0];
           form.acao.value = 'EXIBE_FICHA';
           form.target = 'ficha'; 
	   

		   
-->

<?php
print "</body></html>";
