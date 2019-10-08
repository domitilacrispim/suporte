<!DOCTYPE html>
<HTML>
<HEAD>
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
	
	
	
	$s_page_admin = "unidades.php";
	session_register("s_page_admin");	
?>

</HEAD>
<BODY bgcolor=<?print BODY_COLOR?>>
<?	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);
?>

<BR>
	<B>Unidades</B>
<BR>

<?
		$query = "SELECT i.inst_cod, i.inst_nome, i.sistema, i.inst_status, s.sistema
from instituicao i 
join sistemas s on s.sis_id = i.sistema order by inst_nome";
        $resultado = mysql_query($query);

	if ((empty($action)) and empty($submit)){
?>

<a href='unidades.php?action=incluir'>Incluir unidade.</a><BR>

<?        
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem("Não existem unidades cadastradas no sistema!");
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<br>";
                print "Existe(m) <b>$linhas</b> unidade(s) cadastrado(s) no sistema.<br>";
                print "\n<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "\n<TR class='header'>\n<TD>Unidade</TD><TD>Área</TD><TD>Status</TD><TD><b>Alterar</b></TD><TD><b>Excluir</b></TD>";
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
                        if ($row['inst_status'] == 0) $status ='INATIVO'; else $status = 'ATIVO';
                        print "\n<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
                        print "<td>".$row['inst_nome']."</TD>";
                        print "<td>".$row['sistema']."</TD>";
						print "<td>".$status."</TD>";
                        print "<td><a onClick=\"redirect('unidades.php?action=alter&cod=".$row['inst_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
                        print "<td><a onClick=\"confirma('Tem Certeza que deseja excluir esse unidade do sistema?','unidades.php?action=excluir&cod=".$row['inst_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
                        print "</TR>";
				}
                print "\n</TABLE>";
        }
		 
	} else 
	if (($action == "incluir")&& empty($submit)){
	
		print "<B>Cadastro de Unidades:<br>";
		print "\n<form name='incluir' action='".$PHP_SELF."' onSubmit='return valida()'>";
		print "\n<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor=".TD_COLOR." >Descrição:</td><td><input type='text' class='text' name='descricao' id='idDesc'></td>";
		print "</tr>";
        print "<TR>";
        print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Área:</TD>";
        print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">
		<select class='select' name='area' id='idArea'>";
		print "<option value='-1'>Selecione a Área</option>";
				$sql="SELECT * from sistemas where sis_status NOT in (0) and sis_atende = 1 order by sistema"; //NOT in (0) = INATIVO 
				$commit = mysql_query($sql);
				while($rowp = mysql_fetch_array($commit)){
						 print "<option value=".$rowp['sis_id'].">".$rowp['sistema']."</option>";
				} // while
		print "</select>
		<input type='button' name='area' value='Novo' class='minibutton' onClick=\"javascript:mini_popup('sistemas.php?action=incluir&popup=true')\"></td>";
	
		print "</TR>";

		print "<tr><td><input type='submit' name='submit' value='Incluir'></td>";
		
		print "<td><input type='reset' name='reset' value='Cancelar' onClick=\"javascript:history.back()\"></td></tr>";
		
		print "\n</table>";
		print "\n</form>";
	
	} else 
	
	if (($action=="alter") && empty($submit)){
		$qry = "SELECT * from instituicao where inst_cod = ".$cod."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);
		
		print "<B>Alteração da descrição da unidade:<br>";
		print "<form name='alter' action='".$PHP_SELF."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor=".TD_COLOR."><b>Descrição</b></td><td><input type='text' class='text' name='descricao' id='idDesc' value='".$rowAlter['inst_nome']."'>";
		print "</tr>";
        	print "<TR>";
		print "<td bgcolor=".TD_COLOR."><b>Status</b></td><td><select name='status' class='select'>";
		
		//<input type='text' class='text' name='data' value='".$rowAlter['data_feriado']."'>";
					print"<option value=1";
					if ($rowAlter['inst_status']==1) print " selected";
					print ">ATIVO</option>";
					print"<option value=0";
					if ($rowAlter['inst_status']==0) print " selected";
					print">INATIVO</option>";
		
		print "</select>";
		print " <input type='hidden' name='cod' value='".$cod."'></td>";
		print "</tr>";
        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Área:</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>
			<select class='select' name='area' >";
				
			$sql = "select * from sistemas where sis_id=".$rowAlter["sistema"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >Selecione a área</option>";
					$sql="select * from sistemas where sis_status NOT in (0) and sis_atende = 1 order by sistema";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["sis_id"].""; 
                        			if ($rowB['sis_id'] == $rowAlter['sistema'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["sistema"]."</option>";
					} // while
		
		print "</select>";
		print "</TD>
		</TR>";

		print "<tr><td><input type='submit' name='submit' value='Alterar'></td>";
		print "<td><input type='reset' name='reset' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";
		
		print "</table>";
		print "</form>";
	} else
	
	if ($action=="excluir"){
			$qryAcha = "select * from equipamentos where comp_inst = ".$cod."";
			$execAcha = mysql_query($qryAcha);
			$achou = mysql_numrows($execAcha);
			
			if ($achou){
				print "<script>mensagem('Esse registro não pode ser excluído por existirem equipamentos associados!');".
						" redirect('unidades.php');</script>";
				exit;
			} else {
				
				$qry = "DELETE FROM instituicao where inst_cod = ".$cod."";
				$exec = mysql_query($qry) or die ('Erro na tentativa de deletar o registro!');
				print "<script>mensagem('Registro excluído com sucesso!');".
						" redirect('unidades.php');</script>";				 
			}
	} else
	
	if ($submit=="Incluir"){
		if (!empty($descricao)){
			$qry = "select * from instituicao where inst_nome = '".$descricao."'";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				?>
				<script language="javascript">
				<!--
					mensagem('Esse unidade já está cadastrado no sistema!');
					redirect('unidades.php');
				//-->
				</script>
				<?					
			} else {
				
				
				$qry = "INSERT INTO instituicao (inst_nome,sistema) values ('".noHtml($descricao)."',".$area.")";
				$exec = mysql_query($qry) or die ('Erro na inclusão do unidade!'.$qry);
				print "<script>mensagem('Dados incluídos com sucesso!'); redirect('unidades.php');</script>";
				}
		} else {
				print "<script>mensagem('Dados incompletos!'); redirect('unidades.php');</script>";
		}
		
	} else

	if ($submit = "Alterar"){
		if (!empty($descricao)){
		
			$qry = "UPDATE instituicao set inst_nome='".noHtml($descricao)."', inst_status='".$status."', sistema=".$area." where inst_cod=".$cod."";
			$exec= mysql_query($qry) or die('Não foi possível alterar os dados do registro!'.$qry);
				?>
				<script language="javascript">
				<!--
					mensagem('Dados alterados com sucesso!');
					redirect('unidades.php');
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
</BODY>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idDesc','','Descrição',1);
		return ok;
	}		
-->	
</script>

</HTML>

