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

        $query = "SELECT e.*, u.* FROM emprestimos e, usuarios u WHERE e.responsavel = u.user_id and e.empr_id=$empr_id";
        $resultado = mysql_query($query);
        $resposta = mysql_fetch_array($resultado);

?>

<html>
<BODY bgcolor=<?print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TAB_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>OcoMon - M�dulo de ocorrências</b></TD>";
                        echo menu_usuario();
                        if ($s_usuario=='admin')
                        {
                                echo menu_admin();
                        }
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>
<BR>
<B>Alterar dados do empr�stimo</B>
<BR>

<FORM method="POST" action="<? PHP_SELF ?>" onSubmit="return valida()">
<TABLE border="0"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Material:</TD>
                <TD colspan='3' width="80%" align="left" bgcolor=<?print BODY_COLOR?>><TEXTAREA class='textarea' name="material" id="idMaterial"><?print $resposta[material];?></TEXTAREA></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Para quem:</TD>
                <TD colspan='3'width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="quem" id="idQuem" value="<?print $resposta[quem];?>" ></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Data de sa�da:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="saida" id="idDataSaida" value=<? print datab($resposta[data_empr]);?>></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Data de devolu��o:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="volta" class='text' id="idDataDevolucao" value=<? print datab($resposta[data_devol]);?>></TD>
		</tr>
		<tr>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Operador:</TD>
                <TD  colspan='3' width="80%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?
                        $query = "SELECT * FROM usuarios ORDER BY nome";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);
                        $i = 1;
                        print "<SELECT class='select' name='responsavel'>";
                        print "<option value=$resposta[user_id] selected>$resposta[nome]</option>";
                        while ($i <= $linhas)
                        {
                                $resposta2 = mysql_fetch_array($resultado);
                                ?>
                                <option value="<?print $resposta2['user_id'];?>">
                                        <?print $resposta2['nome'];?>
                                </option>
                                <?
                                mysql_data_seek($resultado,$i);
                                $i++;
                        }
                        ?>
                        </SELECT>
                        </TD>
        </TR>
        <TR>
                <TD colspan='2' align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok" onclick="ok=sim">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD colspan='2' align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" onClick="javascript:history.back()" name="cancelar"></TD>
        </TR>

        <?
                if ($rodou == "sim")
                {
                        $erro = "n�o";

                        if (empty($material) or empty($quem) or empty($saida) or empty($volta))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

                        if ($erro == "n�o")
                        {
                                $saida = datam($saida);
                                $volta = datam($volta);
                                $query = "UPDATE emprestimos SET material='$material', responsavel='$responsavel', data_empr='$saida', data_devol='$volta', quem='$quem' WHERE empr_id=$empr_id";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "Um erro ocorreu ao tentar alterar dados do empr�stimo.";
                                }
                                else
                                {
                                        $aviso = "Dados do empr�stimo alterados com sucesso.";
                                }
                        }
                        //$origem = "emprestimos.php";
                        //session_register("aviso");
                        //session_register("origem");
                        //echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
						print "<script>mensagem('".$aviso."'); redirect('emprestimos.php')</script>";
                }
        ?>

</TABLE>
</FORM>

</body>
<script type="text/javascript">
<!--			

	function valida(){
		var ok = validaForm('idMaterial','','Material',1);
		if (ok) var ok = validaForm('idQuem','','Para quem',1);
		if (ok) var ok = validaForm('idDataSaida','DATA','Data Sa�da',1);
		if (ok) var ok = validaForm('idDataDevolucao','DATA','Data Devolu��o',1);
		
		return ok;
	
	}		

-->	
</script>

</html>
