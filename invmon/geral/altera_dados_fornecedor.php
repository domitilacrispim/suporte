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


?>

<HTML>
<BODY>

<?
		if ($popup) {
			print testa_user_hidden($s_usuario,$s_nivel,$s_nivel_desc,2);
		} else
			print testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);


        $query = "select * from fornecedores where forn_cod='$forn_cod'";
        $resultado = mysql_query($query);
?>

<BR>
<B>Alterar dados do fornecedor</B>
<BR>

<FORM method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="40%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Fornecedor:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="forn_nome" id="idFornecedor" value="<?print mysql_result($resultado,0,1);?>" ></TD>
        </TR>
	<tr>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Telefone:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="forn_fone" id="idTelefone" value="<?print mysql_result($resultado,0,2);?>" ></TD>
        </TR>
        <TR>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar" onClick="javascript:history.back();"></TD>
        </TR>
        <?
                if ($rodou == "sim")
                {
                        $erro = "n�o";

                        if ($erro == "n�o")
                        {
                         $query = "UPDATE fornecedores SET forn_nome='".noHtml($forn_nome)."', forn_fone='$forn_fone' WHERE forn_cod='$forn_cod'";
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
                       print "<script>mensagem('".$aviso."'); redirect('fornecedores.php');</script>";
                }
        ?>

</TABLE>
</FORM>

</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idFornecedor','','Fornecedor',1);
		if (ok) var ok = validaForm('idTelefone','INTEIRO','Telefone',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);
		
		return ok;
	}		
-->	
</script>
</html>
