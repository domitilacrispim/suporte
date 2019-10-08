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
	
	print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
    $conec = new conexao;
    $PDO = $conec->conectaPDO();
	$s_page_admin = "avisos.php";
	session_register("s_page_ocomon");

	$hoje = date("d-m-Y H:i:s");
	$hoje2 = date("d/m/Y");	

 	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);		
		
	print "<BR><B>Mural de Avisos</B><BR><br>";

	
	//Todas as �reas que o usu�rio percente
	$uareas = $_SESSION['s_area'];
	if ($_SESSION['s_uareas']) {
		$uareas.=",".$_SESSION['s_uareas'];
	}		
	
	$query = "SELECT a.*, u.*, ar.* from usuarios u, avisos a left join sistemas ar on a.area = ar.sis_id where (a.area in (".$uareas.") or a.area=-1) and a.origem=u.user_id";
	
	if ($_GET['aviso_id']) {
		$query.=" and a.aviso_id = ".$_GET['aviso_id']."";
	}
	$query.=" ORDER BY u.nome";
	
	//print $query; exit;
	$resultado = $PDO->query($query);
	$registros =$resultado->rowCount();
		
		
	if ((!$_GET['action']) && empty($_POST['submit'])) {
		
		print "<TD align=right bgcolor=".$cor1."><a href='avisos.php?action=incluir'>Incluir Aviso</a></TD><BR>";
	          
        if ($registros == 0) {
        	echo mensagem("N�o h� nenhum aviso cadastrado no sistema.");
        }
        else {
			$cor=TAB_COLOR;
			$cor1=TD_COLOR;
			print "<TD>";
			print "Existe(m) <b>".$registros."</b> aviso(s) cadastrado(s) no Mural:<br>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='".$cor."'>";
			print "<TR class='header'><TD>Data</TD><TD>Aviso</TD><TD>Respons�vel</td><TD>�rea</TD>";
				print "<TD>Prioridade</TD><TD>Alterar</TD><TD>Excluir</TD></TR>";
			$j=2;
			while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
				if ($j % 2) {
					$trClass = "lin_par";
				} else {
					$trClass = "lin_impar";
				}
				$j++;
				print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
				print "<td>".datab($row['data'])."</td>";
				print "<td>".$row['avisos']."</td>";
				print "<td>".$row['nome']."</td>";
				
				if (isIn($row['sis_id'],$uareas)) 
					$area = $row['sistema']; else
					$area = "TODAS";
				
				print "<TD>".$area."</TD>";
				print "<TD>".$row['status']."</TD>";
				print "<td><a onClick=\"redirect('avisos.php?action=alter&aviso_id=".$row['aviso_id']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Alterar o registro'></a></td>";
				print "<TD><a onClick=\"javascript:confirmaAcao('Tem certeza que deseja excluir esse aviso?','avisos.php','action=excluir&aviso_id=".$row['aviso_id']."');\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></TD>";						
				print "</TR>";
			}
			print "</TABLE>";
		}
	
	
	
	} else
	
	if (($_GET['action'] == "incluir")&& empty($_POST['submit'])) {
	
		print "<BR><B>Inclus�o de avisos no Mural</B><BR>";
	
		print "<FORM method='POST' action='".$PHP_SELF."'>";
		print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
        print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Aviso:</TD>";
			print "<TD colspan='3' width='80%' align='left' bgcolor='".BODY_COLOR."'>"; 
			
			?>
			<script type="text/javascript">
				var oFCKeditor = new FCKeditor( 'aviso2' ) ;
				oFCKeditor.BasePath = '../../includes/fckeditor/';
				oFCKeditor.ToolbarSet = 'ocomon';
				oFCKeditor.Width = '570px';
				oFCKeditor.Height = '100px';
				oFCKeditor.Create() ;
			</script>		
			<?
			
			print "</TD>";
        print "</TR>";
        print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Prioridade:</TD>";
			print "<TD width='30%' width='80%' align='left' bgcolor='".BODY_COLOR."'>"; 
				print "<SELECT class='select' name='status'>";
				print "<option value='Normal'>Normal</option>";
				print "<option value='Alta' selected>Alta</option>";
	            print "</SELECT>";
			print "</td>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Para a �rea:</TD>";
			print "<TD width='30%' width='80%' align='left' bgcolor='".BODY_COLOR."'>"; 
				print "<Select class='select' name='area'>";
						print "<OPTION value=-1>-->Todas<--</OPTION>";
							$qry="select * from sistemas where sis_status not in (0) and sis_atende not in (0) order by sistema";
							$exec=$PDO->query($qry);
						while($rowarea=$exec->fetch(PDO::FETCH_ASSOC)) {
							print "<option value=".$rowarea['sis_id']."";
							if ($rowarea['sis_id'] == $_SESSION['s_area'])
								print " selected";
							print ">".$rowarea['sistema']."</option>";
						} // while
			 	print "</Select>";
			print "</td>";
        print "</TR>";
        print "<TR>";
            print "<TD align='center' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='Cadastrar' name='submit'>";
                      print "<input type='hidden' name='aviso_id' value='".$_GET['aviso_id']."'>";
            print "</TD>";
            print "<TD colspan='3' align='center' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' value='Cancelar' name='cancelar' onclick=\"javascript:redirect('avisos.php');\"></TD>";
        print "</TR>";
		print "</table>";
	
	} else
	
	if (($_GET['action']=="alter") && empty($_POST['submit'])){
		
		$row = $resultado->fetch(PDO::FETCH_ASSOC);
		
		print "<BR><B>Alterar dados do aviso</B><br>";
		
		print "<FORM method='POST' action='".$PHP_SELF."'>";
		print "<TABLE border='0' align='center' width='100%' bgcolor=".BODY_COLOR.">";
			print "<TR>";
			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Aviso:</TD>";
			print "<TD colspan='3' width='80%' align='left' bgcolor=".BODY_COLOR.">";
			//print "<TEXTAREA class='textarea' name='avisos' id='idAviso'>".$row['avisos']."</TEXTAREA>";

			?>
			<script type="text/javascript">
				var oFCKeditor = new FCKeditor( 'avisos' ) ;
				oFCKeditor.BasePath = '../../includes/fckeditor/';
				oFCKeditor.Value = '<?print $row['avisos'];?>';
				oFCKeditor.ToolbarSet = 'ocomon';
				oFCKeditor.Width = '570px';
				oFCKeditor.Height = '100px';
				oFCKeditor.Create() ;
			</script>		
			<?
			
			print "</TD>";
			print "</tr>";
			print "<TR>";
			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Prioridade:</TD>";
			print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";
			print "<SELECT class='select' name='status' size=1>";
				//print "<OPTION value=".$row['status']." selected>".$row['status']."</option>";
				print "<option value='alta' ";
					if (strtoupper($row['status'])=='ALTA') 
						print " selected";
				print ">Alta</option>";
				print "<option value='normal' ";
					if (strtoupper($row['status'])=='NORMAL') 
						print " selected";				
				print ">Normal</option>";
			print "</select>";
			print "</TD>";
		
		print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">�rea:</TD>";
		print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";					
		
			$query="select * from sistemas where sis_status not in (0) and sis_atende not in (0) order by sistema";
			$result=$PDO->query($query);
		print "<select class='select' name='area_esc' size=1>";
			print "<option value=-1 selected>-->Todas<--</option>";	
			while ($rowarea = $result->fetch(PDO::FETCH_ASSOC)) {
				print "<option value=".$rowarea['sis_id']." ";
				if ($rowarea['sis_id']==$row['sis_id'])
					print " selected";
				print ">".$rowarea['sistema']."</option>";		    
			} // while
		print "</select>";
		print "</td>";
		print "</TR>";
		print "<TR>";
		print "<BR>";
		print "<TD align='center' width='20%' bgcolor=".BODY_COLOR."><input type='submit' value='Alterar' name='submit'>";
		print "<input type='hidden' name='aviso_id' value='".$_GET['aviso_id']."'>";
		print "</TD>";
		print "<TD colspan='3' align='center' width='80%' bgcolor=".BODY_COLOR."><INPUT type='reset' value='Cancelar' name='cancelar' onclick=\"javascript:redirect('avisos.php')\"></TD>";
		print "</TR>";
		print "</table>";
	
	
	} else
	
	if ($_GET['action'] == "excluir"){
		$row =$resultado->fetch(PDO::FETCH_ASSOC);
	
		$query = "DELETE FROM avisos WHERE aviso_id=".$_GET['aviso_id']."";
		$resultado = $PDO->query($query) or die('Erro ao excluir o aviso do mural'.$query);

		$texto = "Exclu�do: Aviso= ".$row['avisos']."";
			geraLog(LOG_PATH.'ocomon.txt',$hoje,$_SESSION['s_usuario'],'avisos.php?action=excluir',$texto);	
		
		print "<script>mensagem('Aviso exclu�do com sucesso!'); redirect('avisos.php'); </script>";
	
	} else
	
	if ($_POST['submit'] == "Cadastrar"){	
                       
		$data = datam($hoje);
		$query = "INSERT INTO avisos (avisos, data, origem, status, area) values ('".$_POST['aviso2']."','".date("Y-m-d H:i:s")."',".$_SESSION['s_uid'].",'".$_POST['status']."', ".$_POST['area'].")";
		$resultado = $PDO->query($query) or die ('ERRO AO TENTAR INCLUIR NOVO AVISO! '.$query);

		print "<script>mensagem('Aviso inclu�do com sucesso no mural!'); redirect('avisos.php'); </script>";
	
	} else
	
	if ($_POST['submit'] == "Alterar") {	
	
		$query = "UPDATE avisos SET avisos='".$_POST['avisos']."', status='".$_POST['status']."', area=".$_POST['area_esc']." WHERE aviso_id = ".$_POST['aviso_id']."";
		$resultado = $PDO->query($query) or die ('ERRO AO TENTAR ALTERAR OS DADOS DO REGISTRO! '.$query);
		
		print "<script>mensagem('Registro alterado com sucesso!'); redirect('avisos.php'); </script>";

	}		
	

print "</body>";
print "</html>";	
		       
?>
