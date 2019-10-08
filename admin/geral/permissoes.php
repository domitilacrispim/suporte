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

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	
	$s_page_admin = "permissoes.php";
	session_register("s_page_admin");		

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);
	?>

        <BR>
	<B>Permiss�es de Acesso:</B>
        <BR>

<?
		$query = "SELECT p.*, s.*, m.* FROM permissoes p, sistemas s, modulos m WHERE p.perm_area = s.sis_id and
					p.perm_modulo = m.modu_cod order by s.sistema";
        $resultado = mysql_query($query);

	if ((empty($action)) and empty($submit)){
        
        print "<TD align='right'><a href='permissoes.php?action=incluir'>Incluir permiss�o.</a></TD><BR>";
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem("N�o existem permiss�es cadastradas no sistema.");
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<TD>";
                print "Existe(m) <b>$linhas</b> permiss�o(oes) cadastrada(s) no sistema.<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "<TR class='header'><TD><b>�rea</b></TD><TD><b>M�dulo</b></TD><TD><b>Excluir</b></TD>";
                $j=2;
                while ($row=mysql_fetch_array($resultado))
                {
                        if ($j % 2)
                        {
								$trClass = "lin_par";
                        }
                        else
                        {
								$trClass = "lin_impar";
                        }
                        $j++;
                        
                        print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
                        print "<td>".$row['sistema']."</TD>";
                        print "<td>".strtoupper($row['modu_nome'])."</TD>";
                        print "<td><a onClick=\"confirma('Tem Certeza que deseja excluir essa permiss�o?','permissoes.php?action=excluir&cod=".$row['perm_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
                        print "</TR>";
				}
                print "</TABLE>";
        }
		 
	} else 
	if (($action == "incluir")&& empty($submit)){
		print "<B>Cadastro de permiss�es:<br>";
		print "<form name='incluir' action='".$PHP_SELF."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr><td>�rea:</td><td><select class='select' name='area' id='idArea'>";
		print "<option value=-1>�rea</option>";
			$qry = "select * from sistemas order by sistema";
			$exec = mysql_query($qry);
			while ($row_area = mysql_fetch_array($exec)){
				print "<option value=".$row_area['sis_id'].">".$row_area['sistema']."</option>";
			}
		print "</select>";
		print "</td></tr>";
		print "<tr><td>M�dulo:</td><td><select class='select' name='modulo' id='idModulo'>";
		print "<option value=-1>M�dulo</option>";
			$qry = "select * from modulos order by modu_nome";
			$exec = mysql_query($qry);
			while ($row_modulo = mysql_fetch_array($exec)){
				print "<option value=".$row_modulo['modu_cod'].">".$row_modulo['modu_nome']."</option>";
			}
		print "</select>";
		print "</td></tr>";
		print "<tr><td><input type='submit' name='submit' value='Incluir'></td>";
		print "<td><input type='reset' name='reset' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";
		
		print "</table>";
		print "</form>";
	} else
	if ($action=="excluir"){
		$qry = "DELETE FROM permissoes where perm_cod = ".$cod."";
		$exec = mysql_query($qry) or die ('Erro na tentativa de deletar o registro!');
		?>
		<script language="javascript">
		<!--
			mensagem('Registro exclu�do com sucesso!');
			window.location.href='permissoes.php';
		//-->
		</script>
		<?					
	} else
	if ($submit=="Incluir"){
		if (($area!=-1)&& ($modulo!=-1)){
			$qry = "select * from permissoes where perm_area=".$area." and perm_modulo=".$modulo."";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				?>
				<script language="javascript">
				<!--
					mensagem('Essas permiss�es j� existem!');
					history.go(-2)();
				//-->
				</script>
				<?					
			} else {
			
				$qry = "INSERT INTO permissoes (perm_area,perm_modulo,perm_flag) values (".$area.",".$modulo.",1)";
				$exec = mysql_query($qry) or die ('Erro na inclus�o da permiss�o!'.$qry);
				?>
				<script language="javascript">
				<!--
					mensagem('Dados inclu�dos com sucesso!');
					history.go(-2)();
				//-->
				</script>
				<?					
			}
		} else {
			?>
			<script language="javascript">
			<!--
				mensagem('Dados incompletos!');
				history.go(-2)();
			//-->
			</script>
			<?												
		}
		
	}
        


print "</body>";
?>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idArea','COMBO','�rea',1);
		if (ok) var ok = validaForm('idModulo','COMBO','M�dulo',1);


		return ok;
	}		
-->	
</script>
<?
print "</html>";

?>