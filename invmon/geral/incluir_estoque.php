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
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);

	$hojeLog = date ("d-m-Y H:i:s");


?>		
		
<script type="text/javascript">
<!--			
	team = new Array(
<?
$sql="select * from itens order by item_nome";
$sql_result=mysql_query($sql);
echo mysql_error();
$num=mysql_numrows($sql_result);
while ($row_A=mysql_fetch_array($sql_result)){
$conta=$conta+1;
	$cod_item=$row_A["item_cod"];
		echo "new Array(\n";
		$sub_sql="select * from modelos_itens where mdit_tipo='$cod_item' order by mdit_tipo, mdit_fabricante, mdit_desc, mdit_desc_capacidade";
		$sub_result=mysql_query($sub_sql);
		$num_sub=mysql_numrows($sub_result);
		if ($num_sub>=1){
			echo "new Array(\"Todos\", -1),\n";
			while ($rowx=mysql_fetch_array($sub_result)){
				$codigo_sub=$rowx["mdit_cod"];
				$sub_nome=$rowx["mdit_fabricante"]." ".$rowx["mdit_desc"]." ".$rowx["mdit_desc_capacidade"]." ".$rowx["mdit_sufixo"];
			$conta_sub=$conta_sub+1;
				if ($conta_sub==$num_sub){
					echo "new Array(\"$sub_nome\", $codigo_sub)\n";
					$conta_sub="";
				}else{
					echo "new Array(\"$sub_nome\", $codigo_sub),\n";
				}
			}
		}else{
			echo "new Array(\"Qualquer\", -1)\n";
		}
	if ($num>$conta){
		echo "),\n";
	}
}
echo ")\n";
echo ");\n";
?>

function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
	var i, j;
	var prompt;
	// empty existing items
	for (i = selectCtrl.options.length; i >= 0; i--) {
		selectCtrl.options[i] = null; 
	}
	prompt = (itemArray != null) ? goodPrompt : badPrompt;
	if (prompt == null) {
		j = 0;
	}
	else {
		selectCtrl.options[0] = new Option(prompt);
		j = 1;
	}
	if (itemArray != null) {
		// add new items
		for (i = 0; i < itemArray.length; i++) {
			selectCtrl.options[j] = new Option(itemArray[i][0]);
			if (itemArray[i][1] != null) {
				selectCtrl.options[j].value = itemArray[i][1]; 
			}
			j++;
		}
	// select first item (prompt) for sub list
	selectCtrl.options[0].selected = true;
   }
}
//  End --> 
</script>
		







<BR>
<B><?print $TRANS["head_inc_estoq"]?>:</B>
<BR>

<FORM method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="40%">

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><?print $TRANS["cx_tipo_item"]?>:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>>
				<?
					$select = "select * from itens order by item_nome";
					$exec = mysql_query($select);
					
					?>
					<select class='select' name='estoque_tipo' id="idTipo" onChange="fillSelectFromArray(this.form.estoque_desc, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));">
					<?
					print "<option value=-1>".$TRANS["cmb_selec_item"]."</option>";
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['item_cod'].">".$row['item_nome']."</option>";
					} // while
					print "</select>";
					print "<input type='button' value='Novo' name='fab' class='minibutton' onClick=\"javascript:popup_alerta('incluir_itens.php?popup=true')\"></td>";
				?>				
				
				</TD>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><?print $TRANS["cx_modelo"]?>:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>>
				<?
					print "<select class='select' name='estoque_desc' id='idDesc'>";
					print "<option value=null selected>".$TRANS["cmb_selec_modelo"]."</option>";
					$select ="select * from itens, modelos_itens where mdit_tipo = item_cod order by
						item_nome, mdit_fabricante, mdit_desc, mdit_desc_capacidade";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade']." ".$row['mdit_sufixo']."</option>";
					} // while				
					print "</select>";
					print "<input type='button' value='Novo' name='fab' class='minibutton' onClick=\"javascript:popup_alerta('incluir_item.php?popup=true')\"></td>";
				?>				
				</TD>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><?print $TRANS["cx_qnt"]?>:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="estoque_qnt" id="idSN"></TD>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><?print $TRANS["cx_local"]?>:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>>
				<?
					print "<select class='select' name='estoque_local' id='idLocal'>";
					print "<option value=null selected>".$TRANS["cmb_selec_local"]."</option>";
					$select = "select * from localizacao order by local";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['loc_id'].">".$row['local']."</option>";
					} // while
					print "</select>";
				?>
				</TD>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>><?print $TRANS["cx_coment"]?>:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="estoque_comentario" id="idComent"></TD>
        </TR>

		
        <TR>
                <BR>
                <TD align="center" width="20%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="<?print $TRANS["bt_cadastrar"]?>" name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="80%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="<?print $TRANS["bt_cancelar"]?>" onClick="javascript:history.back()" name="cancelar"></TD>
        </TR>

        <?
                if ($rodou == "sim")
                {
                        $erro="não";

                        if (empty($estoque_desc) or empty($estoque_local)or empty($estoque_tipo))
                        {
                                $aviso = $TRANS["alerta_dados_incompletos"];
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM estoque WHERE estoq_qnt='$estoque_qnt' and estoq_tipo='$estoque_tipo'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas > 0)
                        {
                                $aviso = $TRANS["alerta_ja_cadastrado"];
                                $erro = "sim";
                        }


                        if ($erro == "não")
                        {
                                
		                         if ($estoque_comentario == "") {
										$estoque_comentario = "null";
		                         } else
								 	$estoque_comentario = "'$estoque_comentario'";

		                        							
								$query = "INSERT INTO estoque (estoq_tipo, estoq_desc, estoq_local,estoq_qnt, estoq_comentario ) 
											values ('$estoque_tipo', '$estoque_desc', '$estoque_local', '$estoque_qnt', '".noHtml($estoque_comentario)."')";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = $TRANS["alerta_erro_incluir"];
                                }
                                else
                                {
                                        $aviso = $TRANS["alerta_sucesso_incluir"];
                                }
                        }
                       	print "<script>mensagem('$aviso'); redirect('estoque.php')</script>";
                }
        ?>


</TABLE>
</FORM>

</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idTipo','COMBO','Tipo',1);
		if (ok) var ok = validaForm('idSN','INTEIRO','Quantidade',1);
		if (ok) var ok = validaForm('idDesc','COMBO','Modelo',1);
		//if (ok) var ok = validaForm('idSN','','SN',1);
		if (ok) var ok = validaForm('idLocal','COMBO','Local',1);
		//if (ok) var ok = validaForm('idComent','','Comentário',1);
		
		return ok;
	}		
-->	
</script>
</html>
