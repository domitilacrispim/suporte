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
	
	
	
	$s_page_admin = "dominios.php";
	session_register("s_page_admin");	

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);		
	
?>

        <BR>
	<B>Domínios:</B>
        <BR>

<?
		$query = "SELECT * from dominios order by dom_desc";
        $resultado = mysql_query($query);

	if ((empty($action)) and empty($submit)){
        
        print "<TD align='right'><a href='dominios.php?action=incluir'>Incluir domínio.</a></TD><BR>";
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem("Não existem dominios cadastradas no sistema!");
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<TD>";
                print "Existe(m) <b>$linhas</b> domínio(s) cadastrado(s) no sistema.<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "<TR class='header'><TD>Reitoria</TD><TD><b>Alterar</b></TD><TD><b>Excluir</b></TD>";
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
						print "<td>".$row['dom_desc']."</TD>";
                        print "<td><a onClick=\"redirect('dominios.php?action=alter&cod=".$row['dom_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
                        print "<td><a onClick=\"confirma('Tem Certeza que deseja excluir essa domínio do sistema?','dominios.php?action=excluir&cod=".$row['dom_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
                        print "</TR>";
				}
                print "</TABLE>";
        }
		 
	} else 
	if (($action == "incluir")&& empty($submit)){
	
		print "<B>Cadastro de Domínios:<br><a href='dominios.php'>Listagem Geral</a>  <br>";
		print "<form name='incluir' action='".$PHP_SELF."' id='idDesc'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td width='10%'bgcolor=".TAB_COLOR.">Domínio</td><td><input type='text' class='text' name='descricao'></td>";
		print "</tr>";

		print "<tr><td><input type='submit' name='submit' value='Incluir'></td>";
		
		print "<td><input type='reset' name='reset' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";
		
		print "</table>";
		print "</form>";
	} else 
	
	if (($action=="alter") && empty($submit)){
		$qry = "SELECT * from dominios where dom_cod = ".$cod."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);
		
		print "<B>Alteração do nome do domínio de rede:<br>";
		print "<form name='alter' action='".$PHP_SELF."' onSubmit='return valida()' >";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor=".TD_COLOR."><b>Domínio</b></td><td><input type='text' class='text' name='descricao' id='idDesc' value='".$rowAlter['dom_desc']."'>";
		
		print " <input type='hidden' name='cod' value='".$cod."'></td>";
		print "</tr>";

		print "<tr><td><input type='submit' name='submit' value='Alterar'></td>";
		print "<td><input type='reset' name='reset' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";
		
		print "</table>";
		print "</form>";
	} else
	
	if ($action=="excluir"){
			
		$qry = "select * from localizacao where loc_dominio = ".$cod."";
		$exec = mysql_query($qry);
		$linhas = mysql_numrows($exec);
		if ($linhas!=0) {
			print "<script>mensagem('Esse domínio não pode ser excluído pois existem departamentos associados a ele');
					redirect('dominios.php')</script>";
		} else {

			
			$qry = "DELETE FROM dominios where dom_cod = ".$cod."";
			$exec = mysql_query($qry) or die ('Erro na tentativa de deletar o registro!');
			?>
			<script language="javascript">
			<!--
				mensagem('Registro excluído com sucesso!');
				window.opener.location.reload();
				window.location.href='dominios.php';
			//-->
			</script>
			<?					
		}
	} else
	
	if ($submit=="Incluir"){
		if (!empty($descricao)){
			$qry = "select * from dominios where dom_desc = '".$descricao."'";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				?>
				<script language="javascript">
				<!--
					mensagem('Essa domínio já está cadastrado no sistema!');
					history.go(-2)();
				//-->
				</script>
				<?					
			} else {
				
				$data = str_replace("-","/",$data);
				$data = converte_dma_para_amd($data);
				
				$qry = "INSERT INTO dominios (dom_desc) values ('".noHtml($descricao)."')";
				$exec = mysql_query($qry) or die ('Erro na inclusão do registro!'.$qry);
				print "<script>mensagem('Dados incluídos com sucesso!');window.opener.location.reload(); redirect('dominios.php');</script>";
				}
		} else {
				print "<script>mensagem('Dados incompletos!'); redirect('dominios.php');</script>";
		}
		
	} else

	if ($submit = "Alterar"){
		if ((!empty($descricao))){
			
				//$data = str_replace("-","/",$data);
				//$data = converte_dma_para_amd($data);			
			$qry = "UPDATE dominios set dom_desc='".noHtml($descricao)."' where dom_cod=".$cod."";
			$exec= mysql_query($qry) or die('Não foi possível alterar os dados do registro!'.$qry);
				?>
				<script language="javascript">
				<!--
					mensagem('Dados alterados com sucesso!');
					window.opener.location.reload();
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

        


print "</body>";
?>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idDesc','','Descrição',1);
		return ok;
	}		
-->	
</script>
<?
print "</html>";

?>