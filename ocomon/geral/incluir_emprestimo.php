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
	
	$s_page_ocomon = "incluir_emprestimo.php";
	session_register("s_page_ocomon");		

$hoje = date("Y-m-d H:i:s");

?>

<HTML>
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
<B>Inclus�o de empr�stimos</B>
<BR>
TODOS os campos devem ser preenchidos

<FORM name="form1" method="POST" action="<?PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="center" width="100%">
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Material:</TD>
                <TD colspan='3' width="80%" align="left" bgcolor=<?print BODY_COLOR?>><TEXTAREA class='textarea' name="material" id="idMaterial"></textarea></TD>
        </TR>
        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Para quem:</TD>
                <TD colspan='3' width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="quem" id="idQuem" ></TD>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Local *:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>

                <?print "<SELECT class='select' name='local' size='1' id='idLocal'>";
                print "<option value=-1 selected>-  Selecione um local -</option>";
                $query = "SELECT * from localizacao order by local";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>

                </TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Ramal *:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="ramal" id="idRamal"></TD>
        </TR>

        <TR>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Data de sa�da:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text"  class='text' name="saida" id="idDataSaida" value=<? print datab($hoje);?> ></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Data de devolu��o:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text"  class='text' name="volta" id="idDataDevolucao" value=<? print datab($hoje);?> ></TD>
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
                        $erro="n�o";

                        if (empty($material) or empty($quem) or empty($saida) or empty($volta) or empty($ramal))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

                        if ($erro == "n�o")
                        {
                                $saida = datam($saida);
                                $volta = datam($volta);
                                $query = "INSERT INTO emprestimos (material, responsavel, data_empr, data_devol, quem, local, ramal) values ('$material',$s_uid,'$saida','$volta','$quem', $local, '$ramal')";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao incluir empr�stimo no sistema.";
                                }
                                else
                                {
                                        $aviso = "OK. Empr�stimo incluido com sucesso.";
                                }
                        }
                $origem = "incluir_emprestimo.php";
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
		
		if (ok) var ok = validaForm('idLocal','COMBO','Local',1);		
		
		if (ok) var ok = validaForm('idRamal','INTEIRO','Ramal',1);
		if (ok) var ok = validaForm('idDataSaida','DATA','Data Sa�da',1);
		if (ok) var ok = validaForm('idDataDevolucao','DATA','Data Devolu��o',1);

		return ok;
	
	}		

-->	
	
</script>
</html>
