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

	$s_page_admin = "incluir_status.php";
	session_register("s_page_admin");
	
	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);
?>
<BR>
<B>Inclusão de status</B>
<BR>

<FORM method="POST" action="<?PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Status:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="status" id="idStatus"></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Dependência:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>>
				<?	
					$sql = "select * from status_categ order by stc_desc";
					$exec_sql = mysql_query($sql);
					print "<select class='text' name='categoria' id='idCategoria'>";
					print "<option value='null' selected>Selecione a dependência</option>";
					while ($rowCateg = mysql_fetch_array($exec_sql)) {
						print "<option value=".$rowCateg['stc_cod'].">".$rowCateg['stc_desc']."</option>";
					}
					print "</select>";
				?>
				</TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Painel:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>>
				<?
					print "<select class='select' name='painel' id='idPainel'>";
					print "<option value='null' selected>Selecione o painel de exibição</option>";
					print "<option value='1'>Superior</option>";
					print "<option value='2'>Inferior</option>";
					print "<option value='3'>Oculto</option>";
					print "</select>";
				?>
				</TD>
      
        </TR>
        <TR>
                <TD align="left"  width="20%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="left" width="80%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar" onclick="javascript:history.back()"></TD>
        </TR>

	
		
		
		
        <?
                if ($rodou == "sim")
                {
                        $erro="não";

                        if (empty($status) || $painel =="null" || $categoria == "null")
                        {
                                $aviso = "Dados incompletos";
                                $msg = "Preencha todos os dados.";
                                $erro = "sim";
                        }

                        $query = "SELECT status FROM status WHERE status='$status'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas > 0)
                        {
                                $aviso = "Este status já está cadastrado!";
                                $erro = "sim";
                        }

                        if ($erro == "não")
                        {
                                $query = "INSERT INTO status (status, stat_cat, stat_painel) values ('".noHtml($status)."',$categoria,$painel)";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO NA INCLUSÃO DO REGISTRO!";
                                        //$msg = "Um erro ocorreu ao tentar incluir local.";
                                }
                                else
                                {
                                        $aviso = "OK! Novo status cadastrado com sucesso!";
                                        //$msg = "Local incluido com sucesso.";
                                }
                        }
					print "<script>mensagem('".$aviso."'); redirect('status.php');</script>";
                }
        ?>


</TABLE>
</FORM>

</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idStatus','','Status',1);
		if (ok) var ok = validaForm('idCategoria','COMBO','Dependência',1);
		if (ok) var ok = validaForm('idPainel','COMBO','Painel',1);
		
		return ok;
	}		
-->	
</script>
</html>
