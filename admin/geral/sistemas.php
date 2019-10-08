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
  */session_start();


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);	
	
	print "<BR><B>Administração de Áreas de Atendimento</B><BR>";
		
	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor=".BODY_COLOR.">";




		$query = "SELECT * FROM sistemas ";
		if (isset($_GET['cod'])) {
			$query.= "WHERE sis_id = ".$_GET['cod']." ";
		}
		$query .=" ORDER  BY sistema";
		$resultado = mysql_query($query) or die('ERRO NA EXECUÇÃO DA QUERY DE CONSULTA!');
		$registros = mysql_num_rows($resultado);
        
	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {			
		
		print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Incluir nova Área de Atendimento</a></TD></TR>";
        if (mysql_num_rows($resultado) == 0)
        {
                echo mensagem("Não há nenhuma área de atendimento cadastrada.");
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                print "<TD>";
                print "Existe(m) <b>".$registros."</b> áreas cadastrada(s).<br>";
                //print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
                print "<TR class='header'><TD>Área</TD><TD>Atende Chamados</TD><TD>E-mail</TD>".
                	"<TD>Status</TD><TD>Alterar</TD><TD>Excluir</TD></tr>";
                
		$j=2;
                while ($row = mysql_fetch_array($resultado))
                {
			if ($j % 2)
			{
					$color =  BODY_COLOR;
					$trClass = "lin_par";
			}
			else
			{
					$color = 'white';
					$trClass = "lin_impar";
			}
			$j++;
			if ($row['sis_status'] == 0) $lstatus ='INATIVO'; else $lstatus = 'ATIVO';
			print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
			
			print "<td>".$row['sistema']."</td>";
			print "<td>".transbool($row['sis_atende'])."</td>";
			print "<td>".$row['sis_email']."</td>";
			print "<td>".$lstatus."</td>";
			print "<td><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['sis_id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></td>";
			print "<td><a onClick=\"confirmaAcao('Tem Certeza que deseja excluir esse registro?','".$_SERVER['PHP_SELF']."','action=excluir&cod=".$row['sis_id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
			
			print "</TR>";
		}
                //print "</TABLE>";
         }
        
	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {
	
		print "<BR><B>Cadastro de Áreas de Atendimento</B><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Área:</TD>";
		print "<TD width='80%' align='left' bgcolor=".BODY_COLOR."><INPUT type='text' name='area' class='text' id='idArea'>".
				"<input type='checkbox' name='areaatende' value='1' checked>Presta atendimento</TD>";
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">E-mail:</TD>";
		print "<TD width='80%' align='left' bgcolor=".BODY_COLOR."><INPUT type='text' name='email' class='text' id='idEmail'></td>";
		print "</tr>";
		
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Status:</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select' name='status' id='idStatus'>";
			print "<option value=-1>Selecione o Status</option>";
			print "<option value=1>ATIVO</option>";
			print "<option value=0>INATIVO</option>";
		print "</select>";					
		print "</tr>";

		print "<TR>";
		
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='Cadastrar' name='submit'>";
		print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' value='Cancelar' name='cancelar' onClick=\"javascript:history.back()\"></TD>";
	
		print "</TR>";
	
	} else
	
	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {
	
		$row = mysql_fetch_array($resultado);
		
		print "<BR><B>Edição da Área</B><BR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Área:</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='sistema' id='idArea' value='".$row['sistema']."'>";
		 if ($row['sis_atende']) $check = " checked"; else $check = "";
		print "<input type='checkbox' name='areaatende' value='1'  ".$check.">Presta atendimento</TD>";
        	print "</TR>";
        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>E-mail:</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='email' id='idEmail' value='".$row['sis_email']."'></TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Status:</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='status'>";
				
			print "<option value=1";
			if ($row['sis_status']==1) print " selected";
			print ">ATIVO</option>";
			print"<option value=0";
			if ($row['sis_status']==0) print " selected";
			print">INATIVO</option>";
		print "</select>";
		print "</TD>";
        	print "</TR>";


		print "<TR>";
		print "<BR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='Alterar' name='submit'>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' value='Cancelar' name='cancelar' onClick=\"javascript:history.back()\"></TD>";
	
		print "</TR>";
	
	
	} else
	
	if (isset($_GET['action']) && $_GET['action'] == "excluir"){
		
		$total = 0; $texto = "";
		$sql_1 = "SELECT * from usuarios where AREA='".$_GET['cod']."'";
		$exec_1 = mysql_query($sql_1);
		$total+=mysql_numrows($exec_1);
		if (mysql_numrows($exec_1)!=0) $texto.="usuarios, ";
		
		$sql_2 = "SELECT * FROM ocorrencias where sistema ='".$_GET['cod']."'";
		$exec_2 = mysql_query($sql_2);
		$total+= mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.="ocorrencias, ";

		$sql_3 = "SELECT * FROM problemas where prob_area ='".$_GET['cod']."'";
		$exec_3 = mysql_query($sql_3);
		$total+= mysql_numrows($exec_3);
		if (mysql_numrows($exec_3)!=0) $texto.="problemas, ";
		
		if ($total!=0)
		{
			print "<script>mensagem('Esta área não pode ser excluída, existem pendências nas tabelas: ".$texto." associados a ela!'); 
				redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
		else
		{
			$query2 = "DELETE FROM sistemas WHERE sis_id='".$_GET['cod']."'";
			$resultado2 = mysql_query($query2);
			
			if ($resultado2 == 0)
			{
					$aviso = "ERRO NA TENTATIVA DE EXCLUIR O REGISTRO!";
			}
			else
			{
					$aviso = "OK. REGISTRO EXCLUÍDO COM SUCESSO!";
			}
			print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
					
		}
	
	
	} else
	
	if ($_POST['submit'] == "Cadastrar"){
	
		$erro=false;

		$qryl = "SELECT * FROM sistemas WHERE sistema='".$_POST['area']."'";
		$resultado = mysql_query($qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = "Esta Área já está cadastrada!";
				$erro = true;;
		}

		if (!$erro)
		{
			if (isset($_POST['areaatende'])) {
				$areaatende = 1;
			} else
				$areaatende = 0;
			
			$query = "INSERT INTO sistemas (sistema,sis_status,sis_email,sis_atende) values ".
				"('".noHtml($_POST['area'])."',".$_POST['status'].",'".$_POST['email']."','".$areaatende."')";
			$resultado = mysql_query($query);
			if ($resultado == 0)
			{
				$aviso = "ERRO AO INCLUIR REGISTRO!";
			}
			else
			{
				$aviso = "OK. REGISTRO INCLUÍDO COM SUCESSO!";
			}
		}
		
		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
	
	} else
	
	if ($_POST['submit'] == "Alterar"){
		
		if (isset($_POST['areaatende'])) {
			$areaatende = 1;
		} else
			$areaatende = 0;
		$query2 = "UPDATE sistemas SET sistema='".noHtml($_POST['sistema'])."',sis_status=".$_POST['status'].", ".
							"sis_email='".$_POST['email']."', sis_atende='".$areaatende."' WHERE sis_id='".$_POST['cod']."'";
		$resultado2 = mysql_query($query2);

		if ($resultado2 == 0)
		{
			$aviso =  "ERRO AO TENTAR ALTERAR OS DADOS DO REGISTRO!";
		}
		else
		{
			$aviso =  "OK. REGISTRO ALTERADO COM SUCESSO!";
		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
	
	}						
		
	print "</table>";

?>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idArea','','Área',1);
		if (ok) var ok = validaForm('idEmail','EMAIL','Email',1);
		if (ok) var ok = validaForm('idStatus','COMBO','Status',1);
		
		return ok;
	}		

-->	
</script>


<?		
print "</body>";
print "</html>";
