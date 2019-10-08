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
	
	print "<BR><B>Administração de tipos de Problemas</B><BR>";
		
	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
	
	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";




		$query = "select * from problemas as p left join sistemas as s on prob_area = sis_id left join sla_solucao as sl on ".
				"sl.slas_cod = p.prob_sla ";
		if (isset($_GET['cod'])) {
			$query.= " WHERE p.prob_id = ".$_GET['cod']." ";
		}
		$query .=" ORDER  BY s.sistema, p.problema";
		$resultado = mysql_query($query) or die('ERRO NA EXECUÇÃO DA QUERY DE CONSULTA!');
		$registros = mysql_num_rows($resultado);
        
	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {			
		
		print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Incluir novo tipo de Problema</a></TD></TR>";
        if (mysql_num_rows($resultado) == 0)
        {
                echo mensagem("Não há nenhum tipo de problema cadastrado.");
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                print "<tr><TD>";
                print "Existe(m) <b>".$registros."</b> tipos de Problemas cadastrados.</td>";
                print "</tr>";
                //print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
                print "<TR class='header'><TD>Problema</TD><TD>Área</TD><TD>SLA</TD>".
                	"<TD>Alterar</TD><TD>Excluir</TD></tr>";
                
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
			print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
			
			print "<td>".$row['problema']."</td>";
			print "<td>".$row['sistema']."</td>";
			print "<td>".$row['slas_desc']."</td>";
			print "<td><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['prob_id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></td>";
			print "<td><a onClick=\"confirmaAcao('Tem Certeza que deseja excluir esse registro do sistema?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['prob_id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
			
			print "</TR>";
		}
                //print "</TABLE>";
         }
        
	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {
	
		print "<BR><B>Cadastro de tipos de Problemas</B><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Problema:</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='problema' class='text' id='idProblema'></td>";
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Área:</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
		
			print "<select class='select' name='area' id='idArea'>";
				print "<option value=-1>Selecione a área</option>";
					$sql="select * from sistemas where sis_status not in (0) and sis_atende=1 order by sistema";
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['sis_id'].">".$row["sistema"]."</option>";
						$i++;
					} // while
			print "</select>";
		
		print "</td>";
		print "</tr>";
		
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>SLA:</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select' name='sla' id='idSla'>";
			print "<option value=-1>Selecione o SLA</option>";
				
				$sql="select * from sla_solucao order by slas_tempo";
				$commit = mysql_query($sql);
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['slas_cod'].">".$row["slas_desc"]."</option>";
				} // while
		print "</select>";
		print "</td>";					
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
		
		print "<BR><B>Edição de tipos de Problemas</B><BR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Problema:</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='problema' id='idProblema' value='".$row['problema']."'></td>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Área:</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='area' id='idArea'>";
				
			$sql = "select * from sistemas where sis_id=".$row["prob_area"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >Selecione a área</option>";
					$sql="select * from sistemas order by sistema";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["sis_id"].""; 
                        			if ($rowB['sis_id'] == $row['prob_area'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["sistema"]."</option>";
					} // while
		
		print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>SLA:</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='sla' id='idSla'>";
				
			$sql = "select * from sla_solucao where slas_cod=".$row["slas_cod"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >Selecione o SLA</option>";
					$sql="select * from sla_solucao order by slas_tempo";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["slas_cod"].""; 
                        			if ($rowB['slas_cod'] == $row['slas_cod'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["slas_desc"]."</option>";
					} // while
		
		print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Status:</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='status'>";
				
			print "<option value=1";
			if ($row['prob_status']==1) print " selected";
			print ">ATIVO</option>";
			print"<option value=0";
			if ($row['prob_status']==0) print " selected";
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
		
		$sql_2 = "SELECT * FROM ocorrencias where problema ='".$_GET['cod']."'";
		$exec_2 = mysql_query($sql_2);
		$total+= mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.="ocorrencias, ";
		
		if ($total!=0)
		{
			print "<script>mensagem('Este tipo de Problema não pode ser excluído, existem pendências nas tabelas: ".$texto." associados a ela!'); 
				redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
		else
		{
			$query2 = "DELETE FROM problemas WHERE prob_id='".$_GET['cod']."'";
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

		$qryl = "SELECT * FROM problemas WHERE problema='".$_POST['problema']."'";
		$resultado = mysql_query($qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = "Já existe um tipo de Problema com esse nome cadastro no sistema!!";
				$erro = true;;
		}

		if (!$erro)
		{
			
			$query = "INSERT INTO problemas (problema, prob_area, prob_sla,prob_status) values ('".
                                  noHtml($_POST['problema'])."', ".$_POST['area'].", ".$_POST['sla'].", ".$_POST['status'].")";
			$resultado = mysql_query($query);
			if ($resultado == 0)
			{
				$aviso = "ERRO NA TENTATIVA DE INCLUIR O REGISTRO!";
			}
			else
			{
				$aviso = "OK. REGISTRO INCLUÍDO COM SUCESSO!.";
			}
		}
		
		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
	
	} else
	
	if ($_POST['submit'] == "Alterar"){
		
		$query2 = "UPDATE problemas SET problema='".noHtml($_POST['problema']).
                             "', prob_area = ".$_POST['area'].", prob_sla = ".$_POST['sla']."  ".", prob_status = ".$_POST['status']."  ".
					"WHERE prob_id='".$_POST['cod']."'";
  	       $resultado2 = mysql_query($query2);

		if ($resultado2 == 0)
		{
			$aviso =  "ERRO NA TENTATIVA DE ALTERAR O REGISTRO!";
		}
		else
		{
			$aviso =  "REGISTRO ALTERADO COM SUCESSO!";
		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
	
	}						
		
	print "</table>";

?>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idProblema','','Problema',1);
		if (ok) var ok = validaForm('idArea','COMBO','Área',1);
		if (ok) var ok = validaForm('idSla','COMBO','SLA',1);
		
		return ok;
	}		

-->	
</script>


<?		
print "</body>";
print "</html>";
