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
	$cab->set_title(HTML_TITLE);
	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);

        $query = "select * from modelos_itens as m, itens as i where m.mdit_cod='$item_cod' 
					and m.mdit_tipo = i.item_cod";
        $resultado = mysql_query($query);
		$row = mysql_fetch_array($resultado);


?>
<BR>
<B>Alterar dados do componente:</B>
<BR><br>

<FORM method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="40%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Tipo:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
				<?
					print "<select class='select' name='item_tipo' id='idItem'>";
					print "<option value=".$row['item_cod']." selected>".$row['item_nome']."</option>";
					$select = "select * from itens order by item_nome";
					$exec = mysql_query($select);
					while($tipos = mysql_fetch_array($exec)){
						print "<option value =".$tipos['item_cod'].">".$tipos['item_nome']."</option>";
					} // while
				?>
				</TD>
		</tr>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Fabricante:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="item_fabricante" id="idFabricante" value="<?print $row['mdit_fabricante'] ;?>" maxlength="100" size="100"></TD>
		</tr>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Descrição/modelo:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="item_descricao" id="idModelo" value="<?print $row['mdit_desc'];?>" maxlength="100" size="100"></TD>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Capacidade:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="item_capacidade" id="idCapacidade" value="<?print $row['mdit_desc_capacidade'];?>" maxlength="100" size="100"></TD>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Sufixo:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="item_sufixo" id="idSufixo" value="<?print $row['mdit_sufixo'];?>" maxlength="100" size="100"></TD>
        </TR>

		<tr> <td colspan="2"></td></tr>		
        <TR>
                <TD align="center" width="20%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="30%" bgcolor=<?print BODY_COLOR?>><INPUT type="button" value="Voltar" onClick="javascript:history.back()"> </TD>
        </TR>

        <?
                if ($rodou == "sim")
                {
                        $erro = "não";

                        if ($erro == "não")
                        {
	                         if ($item_capacidade == "") {
									$item_capacidade = 'null';
	                         }
	                         if ($item_sufixo == "") {
									$item_sufixo = "null";
	                         } else
							 	$item_sufixo = "'$item_sufixo'";

							 
							 $query = "UPDATE modelos_itens SET mdit_fabricante = '".noHtml($item_fabricante)."' , mdit_desc = '".noHtml($item_descricao)."', 
							 				mdit_desc_capacidade = ".noHtml($item_capacidade).", mdit_sufixo = ".noHtml($item_sufixo)." ,mdit_tipo = $item_tipo 
											WHERE mdit_cod=$item_cod";
	                         $resultado = mysql_query($query);
	                                if ($resultado == 0)
	                                {
	                                        $aviso = "Um erro ocorreu ao tentar alterar dados da unidade.";
	                                }
	                                else
	                                {
	                                        $aviso = "Dados da unidade alterados com sucesso.";
	                                }
                        }
					print "<script>mensagem('".$aviso."'); redirect('itens.php?tipo=".$tipo."');</script>";
					}
        ?>

</TABLE>
</FORM>

</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idItem','COMBO','Tipo',1);
		if (ok) var ok = validaForm('idFabricante','','Fabricante',1);
		if (ok) var ok = validaForm('idModelo','','Modelo',1);
		if (ok) var ok = validaForm('idCapacidade','INTEIRO','Capacidade',0);
		//if (ok) var ok = validaForm('idSufixo','','Sufixo',0);
		
		return ok;
	}		
-->	
</script>

</html>
