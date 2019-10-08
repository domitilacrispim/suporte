<?

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

    include ("../../includes/classes/conecta.class.php");
	//include ("../../includes/classes/auth.class.php");
	
	include ("../../includes/functions/funcoes.inc");
    include ("../../includes/javascript/funcoes.js");
	//include ("../../includes/javascript/calendar1.js");
 	
	include ("../../includes/config.inc.php");
	include ("../../includes/languages/".LANGUAGE."");
	include ("../../includes/queries/queries.php");			

	print "<link rel=stylesheet type='text/css' href='../../includes/css/estilos.css'>";
	print "<link rel='shortcut icon' href='../../includes/icons/favicon.ico'>";
	

	print "<html><head></head>";
	print "<body>";

?>
<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TAB_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor='".$cor1."' nowrap><b>OcoMon - M�dulo de ocorrências</b></TD>";
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>


<?
		
		$conec = new conexao;
		$conec->conecta('MYSQL');	
			
		$query = "SELECT * from utmp_usuarios WHERE utmp_rand = '".$_GET['rand']."'";
		$exec = mysql_query($query);
		$regs = mysql_num_rows($exec);
		if ($regs > 0) {
			$row = mysql_fetch_array($exec);
			$data = date("Y-m-d H:i:s");
			
			$qryconfig = $QRY["useropencall"];
			$execconfig = mysql_query($qryconfig);
			$rowconfig = mysql_fetch_array($execconfig);
			
			
			$qry = "INSERT INTO usuarios (user_id,login,nome,password,data_inc,data_admis,email,fone,nivel,AREA, user_admin) ".
						"values ('','".$row['utmp_login']."','".$row['utmp_nome']."','".$row['utmp_passwd']."', ".
						"'".$data."','".$data."','".$row['utmp_email']."',null,3,".$rowconfig['conf_ownarea'].", 0) ";	
			$exec_qry = mysql_query($qry);
			if ($exec_qry) {
				if ($_GET['fromAdmin']) {
					$msg = "Cadastro confirmado com sucesso para o usu�rio ".$row['utmp_nome']." com o login ".$row['utmp_login']."";
					
				} else {
					$msg = "Cadastro conclu�do com sucesso!<br>Seu usu�rio para abertura de chamados �: <b>".$row['utmp_login']."</b>".
							"<br>Para abrir chamados basta acessar a p�gina:<br>".
							"<a href='".OCOMON_SITE."'>".OCOMON_SITE."</a>";
				}
				$qrydel = "DELETE FROM utmp_usuarios WHERE utmp_rand = ".$_GET['rand']."";
				$execdel = mysql_query($qrydel);
				if (!$execdel) {
					$msg.= "ERRO NA EXCLUSAO DO REGISTRO TEMPOR�RIO!";
				}
			
			} else {
				$msg = "HOUVE UM ERRO NA INCLUS�O DOS DADOS!<br>".$qry."";
			}
			
		} else {
			$msg = "A CONFIRMA��O ATUAL N�O � MAIS V�LIDA! CADASTRA-SE NOVAMENTE EM: <BR><a href='".OCOMON_SITE."'>".OCOMON_SITE."</a>";
		}
	
        if ($_GET['fromAdmin']) {
			
			$VARS = array();
			$VARS['%login%'] = $row['utmp_login'];
			$VARS['%usuario%'] = $row['utmp_nome'];
			$VARS['%site%'] = "<a href='".OCOMON_SITE."'>".OCOMON_SITE."</a>";
			
			$qryconf = "SELECT * FROM mailconfig";
			$execconf = mysql_query($qryconf) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DE ENVIO DE E-MAIL!');
			$rowconf = mysql_fetch_array($execconf);
			
			$event = 'cadastro-usuario-from-admin';
			$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
			$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
			$rowmsg = mysql_fetch_array($execmsg);

			send_mail($event, $row['utmp_email'], $rowconf, $rowmsg, $VARS);

				
			
			print "<script>mensagem('".$msg."'); redirect('../../admin/geral/usuarios.php?action=stat');</script>";
		} else
			print $msg;		
		
	

print "</body>";
print "</html>";

?>