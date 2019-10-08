<?

 /*                        Copyright 2005 Flávio Ribeiro
	
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

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	
	
	
	$s_page_admin = "configmail.php";
	session_register("s_page_admin");	

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);
    
	print "<BR><B>Configuração global para envio de e-mails:</b>&nbsp;".
	"<img src='../../includes/icons/help-16.png' width='16' height='16' onClick=\"return mini_popup('".HELP_PATH."helpconfigmail.php')\"><BR>";


		$query = "SELECT * FROM mailconfig";
        $resultado = mysql_query($query) or die('ERRO AO TENTAR ACESSAR AS INFORMAÇÕES DE CONFIGURAÇÃO DE E-MAIL');
		$row = mysql_fetch_array($resultado);
		
		
		
	if ((empty($_GET['action'])) and empty($_POST['submit'])){
        
        print "<TD align='right'><a href='configmail.php?action=alter'>Alterar configuração.</a></TD><BR>";
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem("Alerta! A tabela de configuração não possui dados!");
        }
        else
        {
			$cor=TAB_COLOR;
			$cor1=TD_COLOR;
			$linhas = mysql_numrows($resultado);
			print "<TD>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
			print "<tr><td colspan='2'><b>Configuração:</b></td></tr>";
			print "<TR class='header'><TD>Diretiva</TD><TD>Valor</TD></TD></tr>";
			print "<tr><td colspan='2'>&nbsp;</td></tr>";
			print "<tr><td>Utiliza SMTP</td><td>".transbool($row['mail_issmtp'])."</td></tr>";
			print "<tr><td>Endereço SMTP</td><td>".$row['mail_host']."</td></tr>";
			print "<tr><td>Precisa de autenticação</td><td>".transbool($row['mail_isauth'])."</td></tr>";
			print "<tr><td>Usuário</td><td>".$row['mail_user']."</td></tr>";
			print "<tr><td>Endereço de envio (FROM)</td><td>".$row['mail_from']."</td></tr>";
			print "<tr><td>Conteúdo HTML</td><td>".transbool($row['mail_ishtml'])."</td></tr>";
			
			print "<tr><td></td><td></td></tr>";
			
			print "</TABLE>";
        }
		 
	} else 
	
	if (($_GET['action']=="alter") && empty($_POST['submit'])){
		
		
		print "<form name='alter' action='".$PHP_SELF."' method='post'>"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<TR class='header'><TD>Diretiva</TD><TD>Valor</TD></TD></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		
		print "<tr><td>Utiliza SMTP</td><td>";//.transbool($row['conf_user_opencall'])."</td></tr>";		
		print "<select name='issmtp' class='select'>";
		print "<option value='0'";
		if ($row['mail_issmtp'] == 0) print " selected";
		print ">NÃO</option>";
		print "<option value='1'";
		if ($row['mail_issmtp'] == 1) print " selected";
		print ">SIM</option>";				
		print "</select></td></tr>";

		
		print "<tr><td>Endereço de SMTP</td><td>";
		print "<input type='text' class='text' name='host' value='".$row['mail_host']."'>";
		print "</td></tr>";
		
		print "<tr><td>Precisa de autenticação</td><td>";
		print "<select name='isauth' class='select'>";
		print "<option value='0'";
		if ($row['mail_isauth'] == 0) print " selected";
		print ">NÃO</option>";
		print "<option value='1'";
		if ($row['mail_isauth'] == 1) print " selected";
		print ">SIM</option>";				
		print "</select></td></tr>";

		print "<tr><td>Usuário para autenticação</td><td>";
		print "<input type='text' class='text' name='user' value='".$row['mail_user']."'>";
		print "</td></tr>";
		
		print "<tr><td>Senha para autenticação</td><td>";
		print "<input type='password' class='text' name='pass' value='".$row['mail_pass']."'>";
		print "</td></tr>";

		print "<tr><td>Endereço de envio (FROM)</td><td>";
		print "<input type='text' class='text' name='from' value='".$row['mail_from']."'>";
		print "</td></tr>";						
		
		print "<tr><td>Envia em formato HTML</td><td>";
		print "<select name='ishtml' class='select'>";
		print "<option value='0'";
		if ($row['mail_ishtml'] == 0) print " selected";
		print ">NÃO</option>";
		print "<option value='1'";
		if ($row['mail_ishtml'] == 1) print " selected";
		print ">SIM</option>";				
		print "</select></td></tr>";
		


		print "<tr><td><input type='submit' name='submit' value='Alterar'></td>";
		print "<td><input type='reset' name='reset' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";
		
		print "</table>";
		print "</form>";
	} else

	if ($_POST['submit'] = "Alterar"){
			
		
		$qry = "UPDATE mailconfig SET ".
				"mail_issmtp= ".$_POST['issmtp'].", mail_host = '".noHtml($host)."', ".
				"mail_isauth = ".$_POST['isauth'].", mail_user = '".noHtml($_POST['user'])."', ".
				"mail_pass = '".noHtml($_POST['pass'])."', mail_from = '".noHtml($_POST['from'])."', ".
				"mail_ishtml = ".$_POST['ishtml'].""; 
				
		$exec= mysql_query($qry) or die('Não foi possível alterar os dados do registro! '.$qry);
			
		print "<script>mensagem('Configuração alterada com sucesso!'); redirect('configmail.php');</script>";
	}

	
print "</body>";
print "</html>";

?>