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
	
	$hojeLog = date ("d-m-Y H:i:s");
        
		$query = "SELECT * FROM estoque, itens, modelos_itens, localizacao where estoq_tipo = item_cod
					and estoq_tipo = mdit_tipo and estoq_desc = mdit_cod and estoq_local = loc_id and 
					estoq_cod = $estoq_cod
					order by item_nome, estoq_desc";
        $resultado = mysql_query($query);
		$row = mysql_fetch_array($resultado);


?>
<BR>
<B>Alterar dados de ítens em estoque:</B>
<BR><br>

<FORM method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="40%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Tipo:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
				<?
					print "<select class='select' name='estoque_tipo' id='idTipo'>";
					print "<option value=".$row['estoq_tipo']." selected>".$row['item_nome']."</option>";
					$select = "select * from itens order by item_nome";
					$exec = mysql_query($select);
					while($tipos = mysql_fetch_array($exec)){
						print "<option value =".$tipos['item_cod'].">".$tipos['item_nome']."</option>";
					} // while
					print "</select>";
				?>
				</TD>
		</tr>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Descrição:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
				<?
					print "<select class=select name='estoque_desc' id='idDesc'>";
					print "<option value=".$row['mdit_cod']." selected>".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade']." ".$row['mdit_sufixo']."</option>";
					$select ="select * from modelos_itens order by mdit_tipo, mdit_fabricante, mdit_desc, mdit_desc_capacidade";
					$exec = mysql_query($select);
					while($desc = mysql_fetch_array($exec)){
						print "<option value=".$desc['mdit_cod'].">".$desc['mdit_fabricante']." ".$desc['mdit_desc']." ".$desc['mdit_desc_capacidade']." ".$desc['mdit_sufixo']."</option>";
					} // while				
					print "</select>";
				
				?>
				</TD>
		</tr>
        <TR>
                
      <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Quantidade:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="estoque_qnt" id="idSN" value="<?print $row['estoq_qnt'];?>" maxlength="100" size="100"></TD>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Localização:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
				<?
					print "<select class=select name='estoque_local' id='idLocal'>";
					print "<option value=".$row['estoq_local']." selected>".$row['local']."</option>";
					$select = "select * from localizacao order by local";
					$exec = mysql_query($select);
					while($locais = mysql_fetch_array($exec)){
						print "<option value =".$locais['loc_id'].">".$locais['local']."</option>";
					} // while
					print "</select>";
				?>
				
				</TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Comentário:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="estoque_comentario" id="idComent" value="<?print $row['estoq_comentario'];?>" maxlength="250" size="100"></TD>
        </TR>


		
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
	                         if ($estoque_comentario == "") {
									$estoque_comentario = "null";
	                         } else
							 	$estoque_comentario = "'$estoque_comentario'";
	                       
							 
							 $query = "UPDATE estoque SET estoq_tipo = '$estoque_tipo' , estoq_desc = '$estoque_desc', 
							 				estoq_qnt = '$estoque_qnt', estoq_local = '$estoque_local', estoq_comentario = '".noHtml($estoque_comentario)."' 
											WHERE estoq_cod='$estoq_cod'";
	                         $resultado = mysql_query($query);
	                                if ($resultado == 0)
	                                {
	                                        $aviso = "Um erro ocorreu ao tentar alterar dados da unidade.";
	                                }
	                                else
	                                {
	                                        $aviso = "Dados da unidade alterados com sucesso. ";
											
											$texto = "Estoque de código ".$row['estoq_cod']." alterado!";
											geraLog(LOG_PATH.'invmon.txt',$hojeLog,$s_usuario,'altera_dados_estoque.php',$texto);	   
	                                
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
		if (ok) var ok = validaForm('idDesc','COMBO','Modelo',1);
		if (ok) var ok = validaForm('idSN','INTEIRO','Quantidade',1);
		//if (ok) var ok = validaForm('idSN','','SN',1);
		if (ok) var ok = validaForm('idLocal','COMBO','Local',1);
		//if (ok) var ok = validaForm('idComent','','Comentário',1);
		
		return ok;
	}		
-->	
</script>
</html>
