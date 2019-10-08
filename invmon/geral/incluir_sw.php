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

		if ($popup) {
			$auth->testa_user_hidden($s_usuario,$s_nivel,$s_nivel_desc,2);
		} else
			$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);

	$hojeLog = date ("d-m-Y H:i:s");

	
	print "<br>";
	print "<table class=corpo>";
	print "<tr>";
	print "<TD width='400' align='left'><B>Cadastro de softwares:</B></TD>";
	print "<TD width='200' align='left'><a href='softwares.php'>Listar Softwares Cadastrados.</a></td>";
	print "<TD width='224' align='left'></td>";
	print "</tr>";
	print "</table><br>";
	
	
	
	
	print "<form method='post' action=".$PHP_SELF." onSubmit='return valida()'>";
	print "<table border='0' align='left' width='40%'>";
		
		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">Software:</TD>";	
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR."><input type='text' class='text' name='software' id='idSoftware'>";
			print "</td>";
    	print "</tr>";

		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">Versão:</TD>";	
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR."><input type='text' class='text' name='versao' id='idVersao'>";
			print "</td>";
    	print "</tr>";

		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">Fabricante:</TD>";	
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR.">";			
					print "<select class='select' name=fabricante id='idFabricante'>";
					print "<option value=".null." selected>Selecione o fabricante</option>";
					$select = "select * from fabricantes where fab_tipo in (2,3) order by fab_tipo,fab_nome";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['fab_cod'].">".$row['fab_nome']."</option>";
					} // while
					print "</select>";
			print "<input type='button' value='Novo' name='fab' class='minibutton' onClick=\"javascript:popup_alerta('incluir_fabricante.php?popup=true')\"></td>";
		print "</tr>";

		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">Categoria:</TD>";	
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR.">";			
					print "<select class='select' name='categoria' id='idCategoria'>";
					print "<option value=".null." selected>Selecione a categoria</option>";
					$select = "select * from categorias order by cat_desc";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['cat_cod'].">".$row['cat_desc']."</option>";
					} // while
					print "</select>";
			print "<input type='button' value='Novo' name='cat' class='minibutton' onClick=\"javascript:popup_alerta('incluir_categoria.php?popup=true')\"></td>";
		print "</tr>";
	
		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">Licença:</TD>";	
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR.">";			
					print "<select class='select' name='licenca' id='idLicenca'>";
					print "<option value='-1' selected>Selecione o tipo de licença</option>";
					$select = "select * from licencas order by lic_desc";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['lic_cod'].">".$row['lic_desc']."</option>";
					} // while
					print "</select>";
			print "<input type='button' value='Novo' name='lic' class='minibutton' onClick=\"javascript:popup_alerta('incluir_licenca.php?popup=true')\"></td>";
		print "</tr>";
	
		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">Quantidade:</TD>";	
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR."><input type='text' class='text' name='quantidade' id='idQtd'>";
			print "</td>";
    	print "</tr>";
	
	
		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">Fornecedor:</TD>";	
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR.">";			
					print "<select class='select' name='fornecedor' id='idFornecedor'>";
					print "<option value=".null." selected>Selecione o fornecedor</option>";
					$select = "select * from fornecedores order by forn_nome";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['forn_cod'].">".$row['forn_nome']."</option>";
					} // while
					print "</select>";
			print "<input type='button' value='Novo' name='forn' class='minibutton' onClick=\"javascript:popup_alerta('incluir_fornecedor.php?popup=true')\"></td>";
		print "</tr>";	
	
		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">N.F.:</TD>";	
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR."><input type='text' class='text' name='nf' id='idNf'>";
			print "</td>";
    	print "</tr>";
	

		print "<tr>";

			print "<td align='center' width='20%' bgcolor=".BODY_COLOR."><input type='submit' value='Cadastrar' name='submit'></td>";	
			print "<td align='center' width='80%' bgcolor=".BODY_COLOR."><input type='reset' value='Cancelar' onClick=\"javascript:redirect('softwares.php')\" name='cancelar'></td>";		
		print "</tr>";		
	
			//print "<input type='hidden' name='popup' value=$popup";	
	print "</table>";
	print "</form>";

	
	if ($submit=='Cadastrar'){
		if (empty($software)|| empty($versao) || $fabricante ==null || $categoria == null || $licenca == null) {
	    	print "<script>mensagem('Dados incompletos, verifique os campos obrigatórios!')</script>";
		} else {
			//Verifica se o software já está cadastrado//
			$sql = "select s.*, l.*, c.*, f.* from softwares as s, licencas as l, categorias as c, fabricantes as f
					where s.soft_tipo_lic = l.lic_cod and s.soft_cat = c.cat_cod and s.soft_fab = f.fab_cod
					and s.soft_desc like ('$software') and s.soft_fab = $fabricante and s.soft_versao = '$versao'
					order by f.fab_nome, s.soft_desc, s.soft_versao";
					$commit = mysql_query($sql);
					$linhas = mysql_num_rows($commit);
			if ($linhas>0) {
			    print "<script>mensagem('Software já cadastrado!')</script>";
			}else {
				$sql = "insert into softwares (soft_desc, soft_versao, soft_fab, soft_cat, soft_tipo_lic, soft_qtd_lic,soft_forn,soft_nf) values 
						('".noHtml($software)."', '".noHtml($versao)."', $fabricante, $categoria, $licenca, $quantidade, $fornecedor, ".noHtml($nf).")";
				$commit = mysql_query($sql);
				if ($commit == 0) {
					$aviso = "ERRO NA INCLUSÃO DOS DADOS!";    
				} else
					$aviso = "Software cadastrado com sucesso!";
		
			print "<script>mensagem('$aviso')</script>";
			}
		}
	
     }                  
	 		 			$origem = "javascript:history.go(-2)";
                        session_register("aviso");
                        session_register("origem");
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