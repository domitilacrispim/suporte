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

	$s_page_admin = "tipo_equipamentos.php";
	session_register("s_page_admin");		
	
	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);		

?>

        <BR>
	<B>Tipos de equipamentos:</B>
        <BR>

<?
		$query = "SELECT * from tipo_equip order by tipo_nome";
        $resultado = mysql_query($query);

	if ((empty($action)) and empty($submit)){
        
        print "<TD align='right'><a href='tipo_equipamentos.php?action=incluir'>Incluir tipo de equipamento.</a></TD><BR>";
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem("Não existem tipos de equipamentos cadastradas no sistema.");
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<TD>";
                print "Existe(m) <b>$linhas</b> tipo(s) de equipamento(s) cadastrado(s) no sistema.<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "<TR class='header'><TD><b>Tipo</b></TD><TD><b>Alterar</b></TD><TD><b>Excluir</b></TD>";
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
                        print "<td>".$row['tipo_nome']."</TD>";
                        print "<td><a onClick=\"redirect('tipo_equipamentos.php?action=alter&cod=".$row['tipo_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
                        print "<td><a onClick=\"confirma('Tem Certeza que deseja excluir esse tipo de equipamento?','tipo_equipamentos.php?action=excluir&cod=".$row['tipo_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
						print "</TR>";
				}
                print "</TABLE>";
        }
		 
	} else 
	if (($action == "incluir")&& empty($submit)){
	
		print "<B>Cadastro de tipo de equipamentos:<br>";
		print "<form name='incluir' action='".$PHP_SELF."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td>Tipo</td><td><input type='text' class='text' name='tipo' id='idTipo'></td>";
		print "</tr>";

		print "<tr><td><input type='submit' name='submit' value='Incluir'></td>";
		print "<td><input type='reset' name='reset' value='Cancelar' onclick=\"redirect('tipo_equipamentos.php')\"></td></tr>";
		
		print "</table>";
		print "</form>";
	} else 
	
	if (($action=="alter") && empty($submit)){
		$qry = "SELECT * from tipo_equip where tipo_cod = ".$cod."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);
		
		print "<B>Alterar o nome do de tipo:<br>";
		print "<form name='alter' action='".$PHP_SELF."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor=".TD_COLOR."><b>Tipo</b></td><td><input type='text' class='text' name='tipo' id='idTipo' value='".$rowAlter['tipo_nome']."'>";
		print " <input type='hidden' name='cod' value='".$cod."'></td>";
		print "</tr>";

		print "<tr><td><input type='submit' name='submit' value='Alterar'></td>";
		print "<td><input type='reset' name='reset' value='Cancelar' onclick=\"redirect('tipo_equipamentos.php')\"></td></tr>";
		
		print "</table>";
		print "</form>";
	} else
	
	if ($action=="excluir"){
		$qryBusca = "SELECT E.*, T.* from equipamentos E, tipo_equip T where E.comp_tipo_equip = T.tipo_cod and T.tipo_cod = ".$cod."";
		$execBusca = mysql_query($qryBusca);
		$achou = mysql_numrows($execBusca);
		if ($achou) {
			?>
			<script language="javascript">
			<!--
				mensagem('Esse registro não pode ser excluído por existirem <?print $achou;?> etiquetas associadas!');
				window.location.href='tipo_equipamentos.php';
			//-->
			</script>			
			<?
			exit;
		} else {
		
			$qry = "DELETE FROM tipo_equip where tipo_cod = ".$cod."";
			$exec = mysql_query($qry) or die ('Erro na tentativa de deletar o registro!');
			?>
			<script language="javascript">
			<!--
				mensagem('Registro excluído com sucesso!');
				window.location.href='tipo_equipamentos.php';
			//-->
			</script>
			<?					
		}
	} else
	
	if ($submit=="Incluir") {
		if (!empty($tipo)){
			$qry = "select * from tipo_equip where tipo_nome='".$tipo."'";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				?>
				<script language="javascript">
				<!--
					mensagem('Esse tipo de equipamento já está cadastrado no sistema!');
					redirect('tipo_equipamentos.php');
				//-->
				</script>
				<?					
			} else {
			
				$qry = "INSERT INTO tipo_equip (tipo_nome) values ('".noHtml($tipo)."')";
				$exec = mysql_query($qry) or die ('Erro na inclusão do tipo de equipamento!'.$qry);
				?>
				<script language="javascript">
				<!--
					mensagem('Dados incluídos com sucesso!');
					redirect('tipo_equipamentos.php');
				//-->
				</script>
				<?					
			}
		} else {
			?>
			<script language="javascript">
			<!--
				mensagem('Dados incompletos!');
				redirect('tipo_equipamentos.php');
			//-->
			</script>
			<?												
		}
		
	} else

	if ($submit = "Alterar"){
		if (!empty($tipo)){
			$qry = "UPDATE tipo_equip set tipo_nome='".noHtml($tipo)."' where tipo_cod=".$cod."";
			$exec= mysql_query($qry) or die('Não foi possível alterar os dados do registro!'.$qry);
				?>
				<script language="javascript">
				<!--
					mensagem('Dados alterados com sucesso!');
					redirect('tipo_equipamentos.php');
				//-->
				</script>
				<?					
		} else {
			?>
			<script language="javascript">
			<!--
				mensagem('Dados incompletos!');
				redirect('tipo_equipamentos.php');
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
		var ok = validaForm('idTipo','','Tipo de equipamento',1);
		//if (ok) var ok = validaForm('idData','DATA-','Data',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);
		
		return ok;
	}		
-->	
</script>
<?
print "</html>";

?>