<html>
<head>
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

	$s_page_admin = "ccustos.php";
	session_register("s_page_admin");	
	
	print "\n</head>";
	print "\n<body bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);	
	
	
?>

        <BR>
		<B>Centros de Custo:</B>
        <BR>

<?
		$query = "SELECT * from `".DB_CCUSTO."`.".TB_CCUSTO." order by ".CCUSTO_DESC."";
		$resultado = mysql_query($query);

	if ((empty($action)) and empty($submit)){
        print "\n<TD align='right'><a href='ccustos.php?action=incluir'>Incluir Centro de Custo.</a></TD><BR>";
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem("Não existem Centros de Custo cadastrados no sistema!".$query);
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<TD>";
                print "Existe(m) <b>$linhas</b> Centro(s) de Custo cadastrado(s) no sistema.<br>";
                print "\n<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "\n<TR class='header'>
				<TD>Descricão</TD>
				<TD>Código</TD>
				<TD><b>Alterar</b></TD>
				<TD><b>Excluir</b></TD>";
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
                        
                        print "\n<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
                        print "<td>".$row[CCUSTO_DESC]."</TD>";
						print "<td>".$row['codccusto']."</TD>";
                        print "<td><a onClick=\"redirect('ccustos.php?action=alter&cod=".$row['codigo']."')\"><img height='16' width='16'  src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
                        print "<td><a onClick=\"confirma('Tem Certeza que deseja excluir esse Centro de Custo?','ccustos.php?action=excluir&cod=".$row['codigo']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
                        print "</TR>";
				}
                print "\n</TABLE>";
				
        }
		 
	} else 
	if (($action == "incluir")&& empty($submit)){

		print "\n<B>Cadastro de Centros de Custo:<br>";
		print "\n<form name='incluir' action='".$PHP_SELF."' onSubmit='return valida()'>";
?>		
		<table border='0' cellpadding='5' cellspacing='0' width='50%'>
		<tr>
		  <td>Descrição</td>
		  <td><input type='text' class='text' name='descricao' id='idDesc'></td>
		</tr>

		<tr>
		  <td>Código</td>
		  <td><input type='text' class='text' name='codigo' id='idCodigo'></td>
		</tr>
		
		<tr>
		  <td><input type='submit' name='submit' value='Incluir'></td>
		  <td><input type='reset' name='reset' value='Cancelar' onclick='javascript:history.back()'></td>
		</tr>		
		
		</table>
		
		</form>
<?		
	} else 
	
	if (($action=="alter") && empty($submit)){	
		$qry = "SELECT * from `".DB_CCUSTO."`.".TB_CCUSTO." where codigo = ".$_GET['cod']."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);
		
		print "<B>Alteração do Centro de Custo:<br>";
		print "<form name='alter' action='".$PHP_SELF."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor=".TD_COLOR."><b>Descrição</b></td>
		<td><input type='text' class='text' name='descricao' id='idDesc' value='".$rowAlter[CCUSTO_DESC]."'>";
		print "<td bgcolor=".TD_COLOR."><b>Código</b></td>
		<td><input type='text' class='text' name='codigo' id='idCodigo' value='".$rowAlter['codccusto']."'>";
		
		print " <input type='hidden' name='cod' value='".$cod."'></td>";
		print "</tr>";

		print "<tr><td><input type='submit' name='submit' value='Alterar'></td>";
		print "<td><input type='reset' name='reset' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";
		
		print "</table>";
		print "</form>";
		
	} else
	
	//if ($_GET['action']=="excluir"){
	if ($action=="excluir"){
		$qryBusca = "SELECT C.*, E.* from equipamentos E, `".DB_CCUSTO."`.".TB_CCUSTO." C where E.comp_ccusto = C.codigo and C.codigo = ".$cod."";
		$execBusca = mysql_query($qryBusca) or die ('ERRO NA BUSCA DE REGISTRO PARA ESSE CENTRO DE CUSTO! '.$qryBusca);
		$achou = mysql_numrows($execBusca);
		if ($achou) {
			?>
			<script language="javascript">
			<!--
				mensagem('Esse registro não pode ser excluído por existirem <?php echo $achou;?> etiquetas associadas!');
				window.location.href='ccustos.php';
			//-->
			</script>			
			<?
			exit;
		} else {
		
			$qry = "DELETE FROM `".DB_CCUSTO."`.".TB_CCUSTO." where codigo = ".$cod."";
			$exec = mysql_query($qry) or die ('Erro na tentativa de deletar o registro!');
			?>
			<script language="javascript">
			<!--
				mensagem('Registro excluído com sucesso!');
				window.location.href='ccustos.php';
			//-->
			</script>
			<?					
		}

	
	} else
	
	//if ($_GET['submit']=="Incluir"){
	if ($submit=="Incluir"){
		if ((!empty($descricao)) && (!empty($codigo))){
		//if ((isset($_POST['descricao'])) && (isset($_POST['codigo']))){
			$qry = "select * from `".DB_CCUSTO."`.".TB_CCUSTO." where ".CCUSTO_DESC."='".$descricao."' and codccusto = ".$codigo."";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				?>
				<script language="javascript">
				<!--
					mensagem('Esse Centro de Custo já está cadastrado no sistema!');
					history.go(-2)();
				//-->
				</script>
				<?					
			} else {
			
				$qry = "INSERT INTO `".DB_CCUSTO."`.".TB_CCUSTO." (".CCUSTO_DESC.",codccusto) values ('".noHtml($descricao)."','".noHtml($codigo)."')";
				$exec = mysql_query($qry) or die ('Erro na inclusão do Centro de Custo!'.$qry);
				?>
				<script language="javascript">
				<!--
					mensagem('Dados incluídos com sucesso!');
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
	

			
	} else

	if ($submit = "Alterar"){
		if ((!empty($descricao)) && (!empty($codigo))){
			$qry = "UPDATE `".DB_CCUSTO."`.".TB_CCUSTO." set ".CCUSTO_DESC."='".noHtml($descricao)."', codccusto='".noHtml($codigo)."' where codigo=".$cod."";
			$exec= mysql_query($qry) or die('Não foi possível alterar os dados do registro!'.$qry);
				?>
				<script language="javascript">
				<!--
					mensagem('Dados alterados com sucesso!');
					history.go(-2)();
				//-->
				</script>
				<?					
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
?>

</body>

<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idDesc','','Descrição',1);
		if (ok) var ok = validaForm('idCodigo','','Código',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);
		
		return ok;
	}		
	window.onload = function() {
    document.getElementById("idDesc").focus();
	};
-->	
</script>
</html>
