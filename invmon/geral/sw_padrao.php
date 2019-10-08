<?php

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
	
	
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);

	$hojeLog = date ("d-m-Y H:i:s");

	
	if ( !empty($acao) ) {
		if ($acao == "del"){
			
				$sql = "delete from sw_padrao where swp_sw_cod=".$id."";
				$commit = mysql_query($sql);
				if ($commit==0) {
			    	$aviso = "ERRO NA EXCLUSÃO DO REGISTRO!";
				} else
					$aviso = "Arquivo excluído com sucesso!";
			print "<script>mensagem('$aviso'); redirect('sw_padrao.php');</script>";
		} 
			
		unset($acao);
	} else {
	
	
	
		$sql = "select s.*, l.*, c.*, f.*, p.* from softwares as s, licencas as l, categorias as c, fabricantes as f,
				sw_padrao p where s.soft_tipo_lic = l.lic_cod and s.soft_cat = c.cat_cod and s.soft_fab = f.fab_cod
				and s.soft_cod = p.swp_sw_cod order by f.fab_nome, s.soft_desc, s.soft_versao";
		$commit = mysql_query($sql);
		$linhas = mysql_num_rows($commit);
		
		if ($linhas == 0)
		{
				echo "<b><p align=center>Nenhum software cadastrado no sistema!<br><a href=incluir_sw_padrao.php>Incluir software</a></b></p>";
				exit;
		}
		   
		if ($linhas>0){
				print "<br>";
				print "<table class=corpo>";
				print "<tr>";
				print "<TD width='400' align='left'><B>Foram encontrados <font color=red>$linhas</font> softwares cadastrados.</B></TD>";
				print "<TD width='200' align='left'><input type='button' value='Novo' class='minibutton' onClick= \"javascript: popup_alerta('incluir_sw_padrao.php?popup=true')\"></td>";
				//print "<TD width='224' align='left'><input type='button' value='Padrão' class='minibutton' onClick=\"javascript:redirect('incluir_sw.php')\"></td>";
				print "</tr>";
				print "</table><br>";
		}
		
		print "<table class=corpo2 >";
		print "<TR class='header'><Td><b>Software</TD><TD><b>Categoria</TD><TD><b>Licença</TD><TD><b>Nº de licenças</TD><TD><b>Disponíveis</TD><TD><b>Excluir</TD>";
		   
		   $j=2;
		   while ($row = mysql_fetch_array($commit))
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
				   
		   
			$sqlAux = "select count(*) total from hw_sw where hws_sw_cod = ".$row['soft_cod']." ";
			$commitAux = mysql_query($sqlAux);
			$rowAux = mysql_fetch_array($commitAux);
			$dispo = $row['soft_qtd_lic'] - $rowAux['total'];
	
		   print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">"; 
				 print "<TD>".$row['fab_nome']." ".$row['soft_desc']." ".$row['soft_versao']."</TD>";
				 print "<TD>".$row['cat_desc']."</TD>";			 
				 print "<TD>".$row['lic_desc']."</TD>";
				 print "<TD>".$row['soft_qtd_lic']."</TD>";
				 print "<TD>".$dispo."</TD>";
				 //print "<TD><a onClick=\"javascript:confirmaAcao('Alterar ".$row['fab_nome']." ".$row['soft_desc']."?','".$PHP_SELF."', 'acao=alt&id=".$row['soft_cod']."')\"><img src='".ICONS_PATH."edit.png' title='Editar o registro'></TD>";
				 print "<TD><a onClick=\"javascript:confirmaAcao('Deletar ".$row['fab_nome']." ".$row['soft_desc']."?','".$PHP_SELF."', 'acao=del&id=".$row['soft_cod']."')\"><img src='".ICONS_PATH."drop.png' title='Excluir o registro'></TD>";
			  print "</TR>";
		   }
		   print "</TABLE>";
	
	}

	
	

	
	$origem = "javascript:history.go(-2)";
    
	
	$cab->set_foot();
	
?>