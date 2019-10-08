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
		
    print "<BR><B>Configuração para envio de e-mails:</b>&nbsp;".
	"<img src='../../includes/icons/help-16.png' width='16' height='16' onClick=\"return popup_alerta_wide('".HELP_PATH."helpconfigmsg.php')\"><BR><br>";
		
		
		$query = "SELECT * FROM msgconfig ";
		if ($_GET['event']) {
			$query .= "WHERE msg_cod=".$_GET['event']."";
		}
		
        $resultado = mysql_query($query) or die ('ERRO NA TENTATIVA DE BUSCAR AS INFORMAÇÕES DE CONFIGURAÇÃO'.$query);
		//$row = mysql_fetch_array($resultado);
		
		
	
	if ((empty($_GET['action'])) and empty($_POST['submit'])){
        
       
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem("Alerta! A tabela de configuração não possui dados!");
        }
        else
        {
			$cor=TAB_COLOR;
			$cor1=TD_COLOR;
			$linhas = mysql_numrows($resultado);
        	
			print "<TABLE border='0' cellpadding='1' cellspacing='0' width='100%'>";			
			print "<tr class='header'>";
			print "<td>Evento</td><td>FROM</td><td>Responder para</td><td>Assunto</td><td>Msg HTML</td><td>Msg Alternativa</td><td>Editar</td>";
			print "</tr>";
			
			$j = 2;
			while ($row = mysql_fetch_array($resultado)) {
				if ($j % 2) {
						$trClass = "lin_par";
				}
				else {
						$trClass = "lin_impar";
				}
				$j++;
				print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
				print "<td>".$row['msg_event']."</td><td>".$row['msg_fromname']."</td>".
					"<td>".$row['msg_replyto']."</td><td>".$row['msg_subject']."</td><td>".$row['msg_body']."</td>".
					"<td>".$row['msg_altbody']."</td>";
				print "<td><a onClick=\"redirect('configmsgs.php?action=alter&event=".$row['msg_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Alterar o registro'></a></td>";	
				print "</tr>";
			}
		
			print "</table>";
		}
		 
	} else 
	
	if (($_GET['action']=="alter") && empty($_POST['submit'])){
		
		$row = mysql_fetch_array($resultado);	
	
		print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
			
		print "<form name='alter' action='".$PHP_SELF."' method='post'>"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='70%'>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr class='header'><td>EVENTO:</td><td>".$row['msg_event']."</td></tr>";
		print "<tr><td>FROM</td><td><input type='text' class='text' name='from' value='".$row['msg_fromname']."'></td></tr>";
		print "<tr><td>Responder para</td><td><input type='text' class='text' name='replyto' value='".$row['msg_replyto']."'></td></tr>";
		
		
		print "<tr><td>Assunto</td><td><input type='text' class='text' name='subject' value='".$row['msg_subject']."'></td></tr>";
		//print "<tr><td>Msg HTML</td><td><textarea name='body' class='textarea2'>".$row['msg_body']."</textarea></td></tr>";
		print "<tr><td>Msg HTML</td><td>";
		?>
		<script type="text/javascript">
  			var oFCKeditor = new FCKeditor( 'body' ) ;
  			oFCKeditor.BasePath = '../../includes/fckeditor/';
			oFCKeditor.Value = '<?print $row['msg_body'];?>';
			oFCKeditor.ToolbarSet = 'ocomon';
			oFCKeditor.Width = '400px';
			oFCKeditor.Height = '100px';
			oFCKeditor.Create() ;
		</script>		
		<?
		print "</td></tr>";
		print "<tr><td>Msg Alternativa</td><td><textarea name='altbody' class='textarea2'>".$row['msg_altbody']."</textarea></td></tr>";
		
		
		print "<tr><td><input type='submit' name='submit' value='Alterar'></td>";
		print "<input type='hidden' value='".$_GET['event']."' name='event'>";
		print "<td><input type='reset' name='reset' value='Cancelar' onclick=\"redirect('configmsgs.php')\"></td></tr>";
		
		print "</table>";
		print "</form>";
	} else

	if ($_POST['submit'] = "Alterar"){
			
		
		$qry = "UPDATE msgconfig SET ".
				"msg_fromname= '".$_POST['from']."', msg_replyto = '".noHtml($_POST['replyto'])."', ".
				"msg_subject = '".$_POST['subject']."', msg_body = '".$_POST['body']."', ".
				"msg_altbody = '".noHtml($_POST['altbody'])."' WHERE msg_cod = ".$_POST['event']."";
				
		$exec= mysql_query($qry) or die('Não foi possível alterar os dados do registro! '.$qry);
			
		print "<script>mensagem('Configuração alterada com sucesso!'); redirect('configmsgs.php');</script>";
	}

	
print "</body>";
print "</html>";

?>