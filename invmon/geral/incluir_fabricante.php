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
			$fecha = "window.close()";
		} else {
			$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);
			$fecha = "history.back()";
		}
?>

<BR>
<B>Inclusão de fabricantes de equipamento:</B>
<BR><br>

<FORM method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="40%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Fabricante:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="fab_nome" id="idFabricante"></TD>
        </TR>

        <TR>
                <TD width="20%" align="right" bgcolor=<?print TD_COLOR?>>Tipo:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>>
				<?
					print "<select class='select' name='tipo' id='idTipo'>";
					print "<option value=-1 selected>Selecione o tipo</option>";
					$select = "select * from tipo_item order by tipo_it_desc";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['tipo_it_cod'].">".$row['tipo_it_desc']."</option>";
					} // while
					print "</select>";
				?>
				</TD>
        </TR>
		
		
		<tr><td colspan="2"></td></tr>
        <TR>
                <TD align="center" width="20%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
				<TD align="center" width="30%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar" onClick="javascript:<?print $fecha;?>;"></TD>
        </TR>

        <?
                if ($rodou == "sim")
                {
                        $erro="não";

                        if (empty($fab_nome) || $tipo==-1)
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM fabricantes WHERE fab_nome='$fab_nome'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas > 0)
                        {
                                $aviso = "Esse fabricante já está cadastrado!";
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM fabricantes";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);
                        $num=0;
                        if ($linhas>0)
                                $num = mysql_result($resultado,$linhas-1,0);
                        $num++;

                        if ($erro == "não")
                        {
                                $query = "INSERT INTO fabricantes (fab_nome, fab_tipo) values ('".noHtml($fab_nome)."', $tipo)";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao incluir fabricante.";
                                }
                                else
                                {
                                        $aviso = "OK. fabricante incluido com sucesso.";
                                }
                        }
				   		print "<script>mensagem('$aviso'); window.opener.location.reload(); window.close();</script>";               				   		 	 	 	 	
                }
       

	   
	    ?>


</TABLE>
</FORM>

</body>

<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idFabricante','','Fabricante',1);
		if (ok) var ok = validaForm('idTipo','COMBO','Tipo',1);
		
		return ok;
	}		
-->	
</script>

</html>
