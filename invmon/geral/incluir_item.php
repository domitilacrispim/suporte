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


		if ($popup) {
			$auth->testa_user_hidden($s_usuario,$s_nivel,$s_nivel_desc,2);
		} else
			$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);

?>

<BR>
<B>Inclusão de Componentes:</B>
<BR>

<FORM method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="40%"  >

        <TR>
                <TD width="30%"  bgcolor=<?print TD_COLOR?>>Tipo:</TD>
                <TD width="70%"  bgcolor=<?print BODY_COLOR?>>
				<?
					$select = "select * from itens order by item_nome";
					$exec = mysql_query($select);
					print "<select  class='select' name=item_tipo id='idItem'>";
					print "<option value=-1 selected>Selecione o tipo de ítem</option>";
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['item_cod']."";
						if ($row['item_cod']==$tipo) print " selected";
						print ">".$row['item_nome']."</option>";
					} // while
				?>				
				
				</TD>
        </TR>

        <TR>
                <TD width="30%"  bgcolor=<?print TD_COLOR?>>Fabricante*:</TD>
                <TD width="70%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="item_fabricante" id="idFabricante"></TD>
        </TR>

        <TR>
                <TD width="30%"  bgcolor=<?print TD_COLOR?>><a title='modelo do componente'>Modelo*:</a></TD>
                <TD width="70%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text"  class="text" name="item_descricao" id="idModelo"></TD>
        </TR>

        <TR>
                <TD width="30%"  bgcolor=<?print TD_COLOR?>><a title='Entre com um valor inteiro correspondente à capacidade do componente'>Capacidade:</a></TD>
                <TD width="70%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text"  name="item_capacidade" id="idCapacidade"></TD>
        </TR>
        <TR>
                <TD width="30%"  bgcolor=<?print TD_COLOR?>>Sufixo (MB,GB, MHZ...):</TD>
                <TD width="70%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text"  class="text" name="item_sufixo" id="idSufixo"></TD>
        </TR>
		
		
		
        <TR>
                <BR>
                <TD align="center" width="30%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="Cadastrar" name="submit">

                </TD>
                <TD align="center" width="70%" bgcolor=<?print BODY_COLOR?>>
				<? 
				if ($popup) {
				    print "<INPUT type='button' value='Fechar' onClick=\"javascript:window.close()\"></TD>";
				} else
					print "<INPUT type='button' value='Voltar' onClick=\"javascript:history.back()\"></TD>";
				print "</TR>";

                if ($submit == "Cadastrar")
                {
                        $erro="não";

                        if (empty($item_tipo) or empty($item_descricao))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

/*
                        $query = "SELECT * FROM modelos_itens WHERE mdit_desc='$item_descricao' and mdit_fabricante='$item_fabricante' and (mdit_desc_capacidade=$item_capacidade or mdit_desc_capacidade is null)";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas > 0)
                        {
                                $aviso = "Esse ítem já está cadastrado!";
                                $erro = "sim";
                        }
*/

                        if ($erro == "não")
                        {
                                
		                         if ($item_capacidade == "") {
										$item_capacidade = 'null';
		                         }
		                         if ($item_sufixo == "") {
										$item_sufixo = "null";
		                         } else
								 	$item_sufixo = "'$item_sufixo'";
								
								$query = "INSERT INTO modelos_itens (mdit_fabricante, mdit_desc, mdit_desc_capacidade,mdit_sufixo, mdit_tipo ) 
											values ('".noHtml($item_fabricante)."','".noHtml($item_descricao)."', '".noHtml($item_capacidade)."', '".noHtml($item_sufixo)."', '$item_tipo')";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso= "ERRO ao incluir item.";
								}
                                else
                                {
                                        $aviso= "OK. Item incluido com sucesso.";
										print "<script>window.opener.location.reload();</script>";
								}
                        }
                        
					print "<script>mensagem('".$aviso."'); window.close(); window.opener.location.reload();</script>";
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
