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
<B>Inclusão de fornecedores:</B>
<BR><br>

<FORM method="POST" action="<?$PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="left" width="40%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Fornecedor:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="forn_nome" id="idFornecedor"></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Telefone:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class="text" name="forn_fone" id="idTelefone"></TD>
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
                        $erro="não";

                        if (empty($forn_nome) or empty($forn_fone))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM fornecedores WHERE forn_fone='$forn_fone' and forn_nome='$forn_nome'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas > 0)
                        {
                                $aviso = "Esse fornecedor já está cadastrado!";
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM fornecedores";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);
                        $num=0;
                        if ($linhas>0)
                                $num = mysql_result($resultado,$linhas-1,0);
                        $num++;

                        if ($erro == "não")
                        {
                                $query = "INSERT INTO fornecedores (forn_nome, forn_fone) values ('".noHtml($forn_nome)."','$forn_fone')";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao incluir fornecedor.";
                                }
                                else
                                {
                                        $aviso = "OK. fornecedor incluido com sucesso.";
                                }
                        }
                        print "<script>mensagem('".$aviso."'); redirect('fornecedores.php'); </script>";               				   		 	 	 	 	
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
