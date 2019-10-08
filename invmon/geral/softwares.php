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

			$sql = "select s.*, l.*, c.*, f.*, fo.* from licencas as l, categorias as c, fabricantes as f, softwares as s
			left join fornecedores as fo on fo.forn_cod = s.soft_forn
			where s.soft_tipo_lic = l.lic_cod and s.soft_cat = c.cat_cod and s.soft_fab = f.fab_cod
			order by f.fab_nome, s.soft_desc, s.soft_versao";
			$commit = mysql_query($sql) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE SOFTWARES!<br>'.$sql);
			$linhas = mysql_num_rows($commit);
	
	if ((empty($acao)) && !isset($alt)){
		if ($linhas == 0)
		{
            echo "<b><p align=center>Nenhum software cadastrado no sistema!<br><a href='incluir_sw.php'>Incluir software</a></b></p>";
            exit;
		}
       
		if ($linhas>0){
			print "<br>";
			print "<table class=corpo>";
			print "<tr>";
			print "<TD width='400' align='left'><B>Foram encontrados <font color=red>$linhas</font> softwares cadastrados.</B></TD>";
			print "<TD width='200' align='left'><input type='button' value='Novo' class='minibutton' onClick=\"javascript:redirect('incluir_sw.php')\"></td>";
			print "<TD width='224' align='left'><input type='button' value='Padrão' class='minibutton' onClick=\"javascript:redirect('sw_padrao.php')\"></td>";
			print "</tr>";
			print "</table><br>";
		}
	
		print "<table class=corpo2 >";
		print "<TR class='header'><Td><b>Software</TD><TD><b>Categoria</TD><TD><b>Licença</TD><TD><b>Nº de licenças</TD><TD><b>Disponíveis</TD><TD>Fornecedor</TD><TD>N.F.</TD><TD><b>Alterar</TD><TD><b>Excluir</TD>";
       
       $j=2;
	   $i=0;
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
			 print "<TD>".$row['forn_nome']."</TD>";
			 print "<TD>".$row['soft_nf']."</TD>";
             print "<TD><a onClick=\"redirect('softwares.php?acao=alt&id=".$row['soft_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></TD>";
             print "<TD><a onClick=\"javascript:confirmaAcao('Deletar ".$row['fab_nome']." ".$row['soft_desc']."?','".$PHP_SELF."', 'acao=del&id=".$row['soft_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></TD>";
          print "</TR>";
		$i++;
	   }
       print "</TABLE>";
	}
	else
	if ($acao == "del"){
			
			$sql = "select * from hw_sw where hws_sw_cod = $id";
			$commit = mysql_query($sql);
			$linhas = mysql_num_rows($commit);
			if ($linhas >0) {
				$aviso = "Esse software não pode ser excluido pois está associado a $linhas equipamentos!";
			} else {
			
				$sql = "delete from softwares where soft_cod=".$id."";
				$commit = mysql_query($sql);
				if ($commit==0) {
			    	$aviso = "ERRO NA EXCLUSÃO DO REGISTRO!";
				} else
					$aviso = "Arquivo excluído com sucesso!";
			}	
			print "<script>mensagem('$aviso'); redirect('softwares.php');</script>";	
		} else
		if (($acao == "alt") && (!isset($alt))) {
		 	
			print "<p>Alterar dados do registro:</p>";
			print "<form name='alterar' action='".$PHP_SELF."' method='post' onSubmit='return valida()'>";
			
			$qry_soft = "select s.*, l.*, c.*, f.*, fo.* from softwares as s, licencas as l, categorias as c, fabricantes as f 
			left join fornecedores as fo on fo.forn_cod = s.soft_forn
			where s.soft_tipo_lic = l.lic_cod and s.soft_cat = c.cat_cod and s.soft_fab = f.fab_cod and s.soft_cod = ".$id." 
			order by f.fab_nome, s.soft_desc, s.soft_versao";
			
			//$qry_soft = "SELECT * FROM softwares where soft_cod = ".$id."";
			$exec_soft = mysql_query($qry_soft);
			$row_soft = mysql_fetch_array($exec_soft);
			
			
			print "<table>";
			print "<tr><td>Fabricante</td><td><select name='fabricante' class='select' id='idFabricante'>";
			print "<option value=-1 selected>Selecione o fabricante</option>";
			$qry = "SELECT * from fabricantes order by fab_nome";
			$exec_qry = mysql_query($qry);
			while ($row_fab = mysql_fetch_array($exec_qry)){
				print "<option value='".$row_fab['fab_cod']."'";
				if ($row_fab['fab_cod']==$row_soft['soft_fab']) print " selected";
				print ">".$row_fab['fab_nome']."</option>";
			
			}
			print "</select>";
			print "</td></tr>";
			print "<tr><td>Software</td><td><input type='text' class='text' name='software' id='idSoftware' value='".$row_soft['soft_desc']."'></td></tr>";

			print "<tr><td>Versão</td><td><input type='text' class='text' name='versao' id='idVersao' value='".$row_soft['soft_versao']."'></td></tr>";
			
			print "<tr><td>Categoria</td><td><select name='categoria' class='select' id='idCategoria'>";
			print "<option value=-1 selected>Selecione a categoria</option>";
			$qry = "SELECT * from categorias order by cat_desc";
			$exec_qry = mysql_query($qry);
			while ($row_cat = mysql_fetch_array($exec_qry)){
				print "<option value='".$row_cat['cat_cod']."'";
				if ($row_cat['cat_cod']==$row_soft['soft_cat']) print " selected";
				print ">".$row_cat['cat_desc']."</option>";
			
			}
			print "</select>";
			print "</td></tr>";

			print "<tr><td>Licença</td><td><select name='licenca' class='select' id='idLicenca'>";
			print "<option value=-1 selected>Selecione o tipo de licença</option>";
			$qry = "SELECT * from licencas order by lic_desc";
			$exec_qry = mysql_query($qry);
			while ($row_lic = mysql_fetch_array($exec_qry)){
				print "<option value='".$row_lic['lic_cod']."'";
				if ($row_lic['lic_cod']==$row_soft['soft_tipo_lic']) print " selected";
				print ">".$row_lic['lic_desc']."</option>";
			
			}
			print "</select>";
			print "</td></tr>";

			print "<tr><td>Nº de licenças</td><td><input type='text' class='text' name='n_licencas' id='idQtd' value='".$row_soft['soft_qtd_lic']."'></td></tr>";
			
			print "<tr><td>Fornecedor</td><td><select name='fornecedor' class='select' id='idFornecedor'>";
			print "<option value=-1 selected>Selecione o fornecedor</option>";
			$qry = "SELECT * from fornecedores order by forn_nome";
			$exec_qry = mysql_query($qry);
			while ($row_forn = mysql_fetch_array($exec_qry)){
				print "<option value='".$row_forn['forn_cod']."'";
				if ($row_forn['forn_cod']==$row_soft['soft_forn']) print " selected";
				print ">".$row_forn['forn_nome']."</option>";
			
			}
			print "</select>";
			print "</td></tr>";			
			
			print "<tr><td>N.F.</td><td><input type='text' class='text' name='nf' id='idNf' value='".$row_soft['soft_nf']."'></td></tr>";
			
			print "<tr><td><input type='submit' name='alt' value='Alterar'></td><td><input type='reset' name='reset' value='Cancelar' onClick=\"redirect('softwares.php')\"></td></tr>";
			print "<input type='hidden' name='id' value='".$id."'>";
			print "</table>";
			
			
			print "</form>";
			
			//print "<script>mensagem('$aviso')</script>";	
		 //--   
		}	else
		if (isset($alt)){
		
			$qry_update = "UPDATE softwares set soft_fab=".noHtml($fabricante).", soft_desc='".noHtml($software)."', soft_versao='".noHtml($versao)."', soft_cat=".$categoria.", soft_tipo_lic=".$licenca.", 
				soft_qtd_lic=".$n_licencas.", soft_forn=".$fornecedor.", soft_nf='".noHtml($nf)."' where soft_cod=".$id."";
			$exec_update = mysql_query($qry_update);
			if ($exec_update==0) $aviso = "Erro durante a tentativa de alterar os dados do registro!"; else
				$aviso = "Dados alterados com sucesso no registro!";
		
			print "<script>mensagem('".$aviso."'); redirect('softwares.php');</script>";
			//print $aviso."<br>".$qry_update;
		}
		
		//unset($acao);

	
	/*select s.*, l.*, c.*, f.* from softwares as s, licencas as l, categorias as c, fabricantes as f
			where s.soft_tipo_lic = l.lic_cod and s.soft_cat = c.cat_cod and s.soft_fab = f.fab_cod
			order by f.fab_nome, s.soft_desc, s.soft_versao*/
	
?>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idSoftware','','Software',1);
		if (ok) var ok = validaForm('idVersao','','Versão',1);
		if (ok) var ok = validaForm('idFabricante','COMBO','Fabricante',1);
		if (ok) var ok = validaForm('idCategoria','COMBO','Categoria',1);
		if (ok) var ok = validaForm('idLicenca','COMBO','Licença',1);
		if (ok) var ok = validaForm('idQtd','INTEIRO','Quantidade',1);
		if (ok) var ok = validaForm('idFornecedor','COMBO','Fornecedor',1);
		if (ok) var ok = validaForm('idNf','INTEIRO','NF',1);
		
		return ok;
	}		
-->	
</script>
<?	

	
	$cab->set_foot();
	
?>