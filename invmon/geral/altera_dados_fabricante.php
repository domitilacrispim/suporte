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


        $query = "select * from fabricantes where fab_cod='$fab_cod'";
        $resultado = mysql_query($query);
		$rowMain = mysql_fetch_array($resultado);
?>

<BR>
<B>Alterar dados do fabricante:</B>
<BR>

<FORM method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="40%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>fabricante:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="fab_nome" id="idFabricante" value="<?print mysql_result($resultado,0,1);?>"></TD>
        </TR>
        
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Tipo:</TD>
								
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><select name="tipo" id="idTipo">
				<?
				$sqlA = "select * from tipo_item where tipo_it_cod=".$rowMain["fab_tipo"]."";
				$commit = mysql_query($sqlA);
				$rowR = mysql_fetch_array($commit);
					print "<option value=".$rowMain["fab_tipo"]." selected>".$rowR["tipo_it_desc"]."</option>";
					
					
					$sql="select * from tipo_item order by tipo_it_desc";
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['tipo_it_cod'].">".$row["tipo_it_desc"]."</option>";
						$i++;
					} // while
				
				?>
				</td>
				</select>				
		
		
		
		<TR>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar" onClick="javascript:history.back()"></TD>
        </TR>

        </TABLE>

        <?
                if ($rodou == "sim")
                {
                        $erro = "não";

                        if ($erro == "não")
                        {
                         $query = "UPDATE fabricantes SET fab_nome='".noHtml($fab_nome)."', fab_tipo=".noHtml($tipo)." WHERE fab_cod='$fab_cod'";
                         $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "Um erro ocorreu ao tentar alterar dados do registro.";
                                }
                                else
                                {
                                        $aviso = "Dados do registro alterados com sucesso.";
                                }
                        }
                    print "<script>mensagem('".$aviso."'); redirect('fabricantes.php');</script>";
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
