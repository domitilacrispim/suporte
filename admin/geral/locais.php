<HTML>
<HEAD>
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

	$s_page_admin = "locais.php";
	session_register("s_page_admin");

	print "\n</HEAD>
       <BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);	
	
	print "<BR><B>Administra��o de Locais/Setores</B><BR>";

		$query = $QRY['locais'];
		if ($_GET['cod']) {
			$query.= "WHERE l.loc_id = ".$_GET['cod']." ";
		}
		$query .="ORDER  BY reit_nome, LOCAL";
		$resultado = mysql_query($query) or die('ERRO NA EXECU��O DA QUERY DE CONSULTA!');
		$registros = mysql_num_rows($resultado);
        
	if ((!$_GET['action']) && empty($_POST['submit'])) {			
		
		print "<TD align=right bgcolor='".$cor1."'><a href='locais.php?action=incluir'>Incluir local</a></TD><BR>";
        if (mysql_num_rows($resultado) == 0)
        {
                echo mensagem("N�o h� nenhum local cadastrado.");
        }
        else
        {
                $cor=TAB_COLOR;
                $cor1=TD_COLOR;
                print "<TD>";
                print "Existe(m) <b>".$registros."</b> local(is) cadastrado(s).<br>";
                print "\n<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
                print "<TR class='header'>
                           <TD><b>Local</b></TD>
                           <TD><b>Pr�dio</b></TD>                           
                           <TD><b>Centro Custo</b></TD>
                           <TD><b>Status</b></TD>
                           <TD><b>Alterar</b></TD>
                           <TD><b>Excluir</b></TD>";
                
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
						$color = white;
						$trClass = "lin_impar";
				}
				$j++;
				if ($row['loc_status'] == 0) $lstatus ='INATIVO'; else $lstatus = 'ATIVO';
				print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
				
				print "<td>".$row['local']."</td>";
				print "<td>".$row['predio']."</td>";
				//print "<td>".$row['reit_nome']."</td>";
				print "<td>".$row['centro_custo']."</td>";
				print "<td>".$lstatus."</td>";
				print "<td><a onClick=\"redirect('locais.php?action=alter&cod=".$row['loc_id']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></td>";
				print "<td><a onClick=\"confirmaAcao('Tem Certeza que deseja excluir esse local?','locais.php','action=excluir&cod=".$row['loc_id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
					
				print "</TR>";
		}
              print "\n</TABLE>";
         }
        
	} else
	if (($_GET['action'] == "incluir")&& empty($_POST['submit'])) {
	
		print "<BR><B>Cadastro de locais</B><BR>";

		print "\n<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
		print "\n<TABLE border='0' align='left' width='100%' bgcolor=".BODY_COLOR.">";
		print "<TR>";
        	print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Local:</TD>";
        	print "<TD width='80%' align='left' bgcolor=".BODY_COLOR."><INPUT type='text' name='local' class='text' id='idLocal'></TD>";
        	print "</TR>";

        	print "<TR>";
        	print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Pr�dio:</TD>";
        	print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">
			<select class='select' name='predio' id='idPredio'>";
			print "<option value='-1'>Selecione o Pr�dio</option>";
					$sql="select * from predios order by pred_desc";
					$commit = mysql_query($sql);
					while($rowp = mysql_fetch_array($commit)){
						 print "<option value=".$rowp['pred_cod'].">".$rowp['pred_desc']."</option>";
					} // while
			print "</select>";
			print "<input type='button' name='predio' value='Novo' class='minibutton' onClick=\"javascript:mini_popup('predios.php?action=incluir&popup=true')\"></td>";
	
		print "</TR>";

		print "<TR>";
        	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Reitoria:</TD>";
        	print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='reitoria' id='idReitoria'>";
			print "<option value=-1>Selecione a reitoria</option>";
					$sql="select * from reitorias order by reit_nome";
					$commit = mysql_query($sql);
					$i=0;
					while($rowr = mysql_fetch_array($commit)){
						print "<option value=".$rowr['reit_cod'].">".$rowr["reit_nome"]."</option>";
						$i++;
					} // while
				
				print "</select>";
		print "<input type='button' name='reitoria' value='Novo' class='minibutton' onClick=\"javascript:mini_popup('reitorias.php?action=incluir&popup=true')\"></td>";
		print "</TR>";

        	print "<TR>";
        	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Dom�nio:</TD>";
        	print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>"; 
			print "<select class='select' name='dominio' id='idDominio'>";
			print "<option value='-1'>Selecione o Dom�nio</option>";
					$sql="select * from dominios order by dom_desc";
					$commit = mysql_query($sql);
				
					while($rowd = mysql_fetch_array($commit)){
						print "<option value=".$rowd['dom_cod'].">".$rowd["dom_desc"]."</option>";
					
					} // while
				
		print "</select>";
		print "<input type='button' name='dominio' value='Novo' class='minibutton' onClick=\"javascript:mini_popup('dominios.php?action=incluir&popup=true')\"></td>";
		print "</TR>";

        	print "<TR>";
        	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Centro de Custo:</TD>";
        	print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>"; 
			print "<select class='select' name='centroCusto' id='idCentroCusto'>";
			print "<option value='-1'>Selecione o Centro de Custo</option>";
					$sql="select * from CCUSTO order by descricao";
					$commit = mysql_query($sql);
				
					while($rowd = mysql_fetch_array($commit)){
						print "<option value=".$rowd['codigo'].">".$rowB["descricao"]." ".$rowB["codccusto"]."</option>";
					
					} // while
				
		print "</select>";
		print "<input type='button' name='dominio' value='Novo' class='minibutton' onClick=\"javascript:mini_popup('dominios.php?action=incluir&popup=true')\"></td>";
		print "</TR>";

	       print "<tr>";
       	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Prioridade de Resposta:</TD>";
        	print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>
			<select class='select' name='sla' id='idSla'>";
			print "<option value='-1'>Selecione o SLA</option>";
					$sql="select * from prioridades order by prior_nivel";
					$commit = mysql_query($sql);
				
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['prior_cod'].">".$row["prior_nivel"]."</option>";
					
					} // while
				
				print "</select>";		
        	print "</td>";
		print "</tr>";

		print "<TR>";
        	print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='Cadastrar' name='submit'>";
        	print "</TD>";
        	print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' value='Cancelar' name='cancelar' onClick=\"javascript:history.back()\"></TD>"; 
        	print "</TR>";
	
	} else
	
	if (($_GET['action']=="alter") && empty($_POST['submit'])){
	
		$row = mysql_fetch_array($resultado);
		print "<BR><B>Alterar local</B><BR>";

		print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
		print "<TABLE border='0'  align='left' width='80%' bgcolor='".BODY_COLOR."'>";
        	print "<TR>";
        	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Local:</TD>";
        	print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='select' name='local' id='idLocal' value='".$row['local']."'></TD>";
        	print "</TR>";

        	print "<TR>";
        	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Reitoria:</TD>";
        	print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='reitoria' id='idReitoria'>";
			print "<option value=-1 selected>Selecione a reitoria</option>";
			$sql = "select * from reitorias where reit_cod=".$row["loc_reitoria"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				//print "<option value=".$row["loc_reitoria"]." selected>".$rowR["reit_nome"]."</option>";
			
					$sql="select * from reitorias order by reit_nome";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["reit_cod"]." ";
						if ($rowB['reit_cod'] == $row["loc_reitoria"])
							print " selected";
						print ">".$rowB["reit_nome"]."</option>";
					} // while
		
		print "</select>";
		print "<input type='button' name='reitoria' value='Novo' class='minibutton' onClick=\"javascript:mini_popup('reitorias.php?action=incluir&popup=true')\"></td>";
        	print "</TR>";

        	print "<TR>";
        	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Pr�dio:</TD>";
        	print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
        	print "<select class='select'  name='predio'>";
			$sql = "select * from predios where pred_cod=".$row["loc_predio"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value='-1'>Pr�dio</option>";
					$sql="select * from predios order by pred_desc";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
					    print "<option value=".$rowB["pred_cod"]."";      
					    if ($rowB['pred_cod'] == $row['loc_predio'] ) {
                            		print " selected";
                        		    }
                        		    print " >".$rowB["pred_desc"]."</option>";
                    			} // while		
		print "</select>";
		print "<input type='button' name='predio' value='Novo' class='minibutton' onClick=\"javascript:mini_popup('predios.php?action=incluir&popup=true')\"></td>";		
        	print "</TR>";
		
        	print "<TR>";
        	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Dom�nio:</TD>";
        	print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select  class='select' name='dominio'>";
			$sql = "select * from dominios where dom_cod=".$row["loc_dominio"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value='-1'>Dom�nio</option>";
					$sql="select * from dominios order by dom_desc";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
					    print "<option value=".$rowB["dom_cod"]."";      
					    if ($rowB['dom_cod'] == $row['loc_dominio'] ) {
                            		print " selected";
                        		    }
                        		    print " >".$rowB["dom_desc"]."</option>";
                    			} // while		
		print "</select>";
		print "<input type='button' name='dominio' value='Novo' class='minibutton' onClick=\"javascript:mini_popup('dominios.php?action=incluir&popup=true')\"></td>";
        	print "</TR>";
       	
        	print "<TR>";
        	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Centro de Custo:</TD>";
        	print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select  class='select' name='centroCusto'>";
			$sql = "select * from CCUSTO where codigo=".$row["cod_custo"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value='-1'>Centro de Custo</option>";
				$sql="select * from CCUSTO order by descricao";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
					    print "<option value=".$rowB["codigo"]."";      
					    if ($rowB['codigo'] == $row['cod_custo'] ) {
                            		print " selected";
                        		    }
                        		    print " >".$rowB["descricao"]." ".$rowB["codccusto"]."</option>";
                    			} // while		
			print "</select>";
		print "<input type='button' name='centrocusto' value='Novo' class='minibutton' onClick=\"javascript:mini_popup('ccustos.php?action=incluir&popup=true')\"></td>";
        	print "</TR>";

		print "<TR>";
        	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Prioridade:</TD>";
        	print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select  class='select' name='p_nivel'>";
			$sql = "select * from prioridades where prior_cod=".$row["loc_prior"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value='-1'>Prioridade</option>";
			
					$sql="select * from prioridades  order by prior_nivel";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
					    print "<option value=".$rowB["prior_cod"]."";      
					    if ($rowB['prior_cod'] == $row['loc_prior'] ) {
                            		print " selected";
                        		    }
		                        print " >".$rowB["prior_nivel"]."</option>";
                    			} // while
			print "</select>";
		print "</td>";
        	print "</TR>";

       	print "<TR>";
        	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Status:</TD>";
        	print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select  class='select' name='lstatus'>";
					print"<option value=1";
					if ($row['loc_status']==1) print " selected";
					print ">ATIVO</option>";
					print"<option value=0";
					if ($row['loc_status']==0) print " selected";
					print">INATIVO</option>";
			print "</select>";
		print "</td>";
        	print "</TR>";

        	print "<TR>";
        	print "<BR>";
        	print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='Alterar' name='submit'>";
        	print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
		print "</TD>";
        	print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' value='Cancelar' name='cancelar' onClick=\"javascript:history.back()\"></TD>"; 
        	print "</TR>";
	
	
	} else
	
	if ($_GET['action'] == "excluir"){
		
		$sql_3 = "SELECT * FROM ocorrencias where local ='".$_GET['cod']."'";
		$exec_3 = mysql_query($sql_3) or die('N�O FOI POSS�VEL RECUPERAR AS INFORMA��ES DE CHAMADOS PARA ESSE LOCAL!');
		$total= mysql_num_rows($exec_3);
		
		if ($total!=0)
		{
			print "<script>mensagem('Este local n�o pode ser exclu�do, existem ocorrências associadas a ele!'); 
				redirect('locais.php');</script>";
		}
		else
		{
			$query2 = "DELETE FROM localizacao WHERE loc_id=".$_GET['cod']."";
			$resultado2 = mysql_query($query2) or die('ERRO NA TENTATIVA DE EXCLUIR O REGISTRO!');
			
			$aviso = "OK. Registro excluido com sucesso!";
			
			print "<script>mensagem('".$aviso."'); redirect('locais.php');</script>";
					
		}
	
	
	} else
	
	if ($_POST['submit'] == "Cadastrar"){
	
		$erro=false;

		$qryl = "SELECT local FROM localizacao WHERE local='".$_POST['local']."'";
		$resultado = mysql_query($qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = "Este local j� est� cadastrado!";
				$erro = true;;
		}

		if (!$erro)
		{
			$query = "INSERT INTO localizacao (local,loc_reitoria, loc_prior, loc_dominio, loc_predio, cod_custo) ".
						"values ('".noHtml($_POST['local'])."',".$_POST['reitoria'].",".$_POST['sla'].", ".$_POST['dominio'].",".$_POST['predio'].", ".$_POST['centroCusto'].")";
			$resultado = mysql_query($query) or die('ERRO NA INCLUS�O DO LOCAL! '.$query);
			$aviso = "OK. Local cadastrado com sucesso!";
		}
		
		echo "<script>mensagem('".$aviso."'); redirect('locais.php');</script>";
	
	} else
	
	if ($_POST['submit'] == "Alterar"){
		
		$query2 = "UPDATE localizacao SET local='".noHtml($_POST['local'])."', loc_reitoria=".$_POST['reitoria'].", ".
				"loc_prior=".$_POST['p_nivel'].", loc_dominio=".$_POST['dominio'].", loc_predio=".$_POST['predio'].", ".
				"cod_custo=".$_POST['centroCusto'].", ".
				"loc_status=".$_POST['lstatus']." WHERE loc_id=".$_POST['cod']."";
		$resultado2 = mysql_query($query2) or die('ERRO DURANTE A TENTATIVA DE ATUALIZAR O REGISTRO! '.$query2);

		$aviso = "Local alterado com sucesso.";
		echo "<script>mensagem('".$aviso."'); redirect('locais.php');</script>";
	
	}						
		


?>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idLocal','','Local',1);
		//if (ok) var ok = validaForm('idReitoria','COMBO','Reitoria',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);
		
		return ok;
	}		
-->	
</script>


</BODY>
</HTML>

