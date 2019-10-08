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
	
	
	
	$s_page_admin = "feriados.php";
	session_register("s_page_admin");	

	print "<html><head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar1.js\"></script></head>";
	print "<body>";
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);	
	
?>

        <BR>
	<B>Feriados:</B>
        <BR>

<?
		$query = "SELECT * from feriados order by data_feriado";
        $resultado = mysql_query($query);

	if ((empty($action)) and empty($submit)){
        
        print "<TD align='right'><a href='feriados.php?action=incluir'>Incluir feriado.</a></TD><BR>";
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem("Não existem feriados cadastrados no sistema!");
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<TD>";
                print "Existe(m) <b>$linhas</b> Feriado(s) cadastrado(s) no sistema.<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "<TR class='header'><TD>DATA</TD><TD>DESCRIÇÃO</TD><TD><b>Alterar</b></TD><TD><b>Excluir</b></TD>";
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
                        print "<td>".datab2($row['data_feriado'])."</TD>";
						print "<td>".$row['desc_feriado']."</TD>";
                        print "<td><a onClick=\"redirect('feriados.php?action=alter&cod=".$row['cod_feriado']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
                        print "<td><a onClick=\"confirma('Tem Certeza que deseja excluir esse feriado do sistema?','feriados.php?action=excluir&cod=".$row['cod_feriado']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
                        print "</TR>";
				}
                print "</TABLE>";
        }
		 
	} else 
	if (($action == "incluir")&& empty($submit)){
	
		print "<B>Cadastro de Feriados:<br>";
		print "<form name='incluir' action='".$PHP_SELF."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td>Descrição</td><td><input type='text' class='text' name='descricao' id='idDesc'></td>";
		print "</tr>";

		print "<tr>";
		print "<td>Data</td><td><input type='text' class='data' name='data' id='idData'><a href=\"javascript:cal1.popup();\"><img height='16' width='16' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
		print "</tr>";
		print "<tr><td><input type='submit' name='submit' value='Incluir'></td>";
		
		print "<td><input type='reset' name='reset' value='Cancelar' onClick=\"javascript:history.back()\"></td></tr>";
		
		print "</table>";
		print "</form>";
	?>
			<script language="JavaScript"> 
			 // create calendar object(s) just after form tag closed
				 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
				 // note: you can have as many calendar objects as you need for your application
				var cal1 = new calendar1(document.forms['incluir'].elements['data']);
				cal1.year_scroll = true;
				cal1.time_comp = false;
			//-->				
			</script>
	
	<?		
	} else 
	
	if (($action=="alter") && empty($submit)){
		$qry = "SELECT * from feriados where cod_feriado = ".$cod."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);
		
		print "<B>Alteração do feriado:<br>";
		print "<form name='alter' action='".$PHP_SELF."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor=".TD_COLOR."><b>Descrição</b></td><td><input type='text' class='text' name='descricao' id='idDesc' value='".$rowAlter['desc_feriado']."'>";
		print "<td bgcolor=".TD_COLOR."><b>Data</b></td><td><input type='text' class='text' name='data' id='idData' value='".str_replace("/","-",datab2($rowAlter['data_feriado']))."'>";
		//$data = str_replace("-","/",$data);
		print " <input type='hidden' name='cod' value='".$cod."'></td>";
		print "</tr>";

		print "<tr><td><input type='submit' name='submit' value='Alterar'></td>";
		print "<td><input type='reset' name='reset' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";
		
		print "</table>";
		print "</form>";
	} else
	
	if ($action=="excluir"){
			$qry = "DELETE FROM feriados where cod_feriado = ".$cod."";
			$exec = mysql_query($qry) or die ('Erro na tentativa de deletar o registro!');
			?>
			<script language="javascript">
			<!--
				mensagem('Registro excluído com sucesso!');
				window.location.href='feriados.php';
			//-->
			</script>
			<?					
		
	} else
	
	if ($submit=="Incluir"){
		if ((!empty($descricao)) && (!empty($data))){
			$qry = "select * from feriados where desc_feriado = '".$descricao."' and data_feriado = '".$data."'";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				?>
				<script language="javascript">
				<!--
					mensagem('Esse Feriado já está cadastrado no sistema!');
					history.go(-2)();
				//-->
				</script>
				<?					
			} else {
				
				$data = str_replace("-","/",$data);
				$data = converte_dma_para_amd($data);
				
				$qry = "INSERT INTO feriados (desc_feriado,data_feriado) values ('".noHtml($descricao)."','".$data."')";
				$exec = mysql_query($qry) or die ('Erro na inclusão do feriado!'.$qry);
				print "<script>mensagem('Dados incluídos com sucesso!'); redirect('feriados.php');</script>";
				}
		} else {
				print "<script>mensagem('Dados incompletos!'); redirect('feriados.php');</script>";
		}
		
	} else

	if ($submit = "Alterar"){
		if ((!empty($descricao)) && (!empty($data))){
			
				$data = str_replace("-","/",$data);
				$data = converte_dma_para_amd($data);			
			
			//$qry = "UPDATE feriados set desc_feriado='".noHtml($descricao)."', data_feriado='".$data."' where cod_feriado=".$cod."";
			$qry = "UPDATE feriados set desc_feriado='".noHtml($descricao)."', data_feriado='".$data."' where cod_feriado=".$cod."";
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

        


print "</body>";
?>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idDesc','','Descrição',1);
		if (ok) var ok = validaForm('idData','DATA-','Data',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);
		
		return ok;
	}		
-->	
</script>
<?
print "</html>";

?>