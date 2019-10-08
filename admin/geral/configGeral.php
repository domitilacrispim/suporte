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
	
	
	
	$s_page_admin = "configGeral.php";
	session_register("s_page_admin");	

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);


    print "<BR><B>Configurações gerais do sistema:</b>&nbsp;".
	"<img src='../../includes/icons/help-16.png' width='16' height='16' onClick=\"return mini_popup('".HELP_PATH."helpconfiggeral.php')\"><BR>";

		$query = "SELECT * FROM config ";
        	$resultado = mysql_query($query) or die ("ERRO AO TENTAR ACESSAR A TABELA CONFIG! CERTIFIQUE-SE DE QUE A TABELA EXISTE!");;
		$row = mysql_fetch_array($resultado);
		
		
	if ((empty($_GET['action'])) and empty($_POST['submit'])){
        
        print "<TD align='right'><a href='configGeral.php?action=alter'>Alterar configuração.</a></TD><BR>";
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
			print "<TR class='header'><TD>Diretiva</TD><TD>Valor</TD></TD></tr>";
			print "<tr><td colspan='2'>&nbsp;</td></tr>";
			print "<tr><td colspan='2'><b>UPLOAD DE IMAGENS</b></td></tr>";
				$emKbytes = $row['conf_upld_size']/1024;
			print "<tr><td>TAMANHO MÁXIMO</td><td>".$row['conf_upld_size']."&nbsp;bytes (".$emKbytes." kbytes)</td></tr>";
			print "<tr><td>LARGURA MÁXIMA</td><td>".$row['conf_upld_width']."px</td></tr>";
			print "<tr><td>ALTURA MÁXIMA</td><td>".$row['conf_upld_height']."px</td></tr>";
			print "<tr><td colspan='2'>&nbsp;</td></tr>";
			print "<tr><td></td><td></td></tr>";
			
			print "</TABLE>";
        }
		 
	} else 
	
	if (($_GET['action']=="alter") && empty($_POST['submit'])){
		
		
		print "<form name='alter' action='".$PHP_SELF."' method='post' onSubmit=\"return valida()\">"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<TR class='header'><TD>Diretiva</TD><TD>Valor</TD></TD></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td colspan='2'><b>UPLOAD DE IMAGENS</b></td></tr>";
		
		print "<tr><td>TAMANHO MÁXIMO</td>";//.transbool($row['conf_user_opencall'])."</td></tr>";		
		print "<td><input type='text' class='text' id='idSize' name='size' value='".$row['conf_upld_size']."'</td></tr>";

		print "<tr><td>LARGURA MÁXIMA</td>";//.transbool($row['conf_user_opencall'])."</td></tr>";		
		print "<td><input type='text' class='text' id='idWidth' name='width' value='".$row['conf_upld_width']."'</td></tr>";

		print "<tr><td>ALTURA MÁXIMA</td>";//.transbool($row['conf_user_opencall'])."</td></tr>";		
		print "<td><input type='text' class='text' id='idHeight' name='height' value='".$row['conf_upld_height']."'</td></tr>";


		print "<tr><td><input type='submit' name='submit' value='Alterar'></td>";
		print "<td><input type='reset' name='reset' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";
		
		print "</table>";
		print "</form>";
	} else

	if ($_POST['submit'] = "Alterar"){
			
		$qry = "UPDATE config SET ".
				"conf_upld_size= '".$_POST['size']."', conf_upld_width = '".$_POST['width']."', ".
				"conf_upld_height = '".$_POST['height']."'";
				
		//print $qry;
		//exit;
		$exec= mysql_query($qry) or die('Não foi possível alterar os dados do registro! '.$qry);
			
		print "<script>mensagem('Configuração alterada com sucesso!'); redirect('configGeral.php');</script>";
	}
?>
<script type="text/javascript">
<!--
	function valida(){
	
		var ok = validaForm('idSize','INTEIROFULL','TAMANHO MAXIMO',1);
		if (ok) var ok =  validaForm('idWidth','INTEIROFULL','LARGURA MAXIMA',1);
		if (ok) var ok =  validaForm('idHeight','INTEIROFULL','ALTURA MÁXIMA',1);
		 
		return ok;
	}
-->	
</script>
<?	
print "</body>";
print "</html>";

?>