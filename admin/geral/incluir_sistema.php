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

	$s_page_admin = "incluir_sistema.php";
	session_register("s_page_admin");	

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";	
	
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);	
	?>

<BR>
<B>Inclusão de áreas de atendimento:</B>
<BR>

<FORM method="POST" action="<?PHP_SELF?>" onSubmit="return valida()">
<TABLE border="0"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>Área:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="sistema" id="idArea">
					<input type='checkbox' name='areaatende' value='1' checked>Presta atendimento			
				</TD>
        </TR>
        <TR>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>E-mail:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" class='text' name="email" id="idEmail"></TD>
        </TR>

        
		<TR>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>Status:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><select class='select' name="status" id="idStatus">
				<option value=-1>Selecione o Status</option>
				<option value=1>ATIVO</option>
				<option value=0>INATIVO</option>
				</select>
				</TD>
        </TR>

        <TR>
                <TD align="left" width="10%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="left" width="80%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" onClick="javascript:history.back()" name="cancelar"></TD>
        </TR>

        <?
                if ($rodou == "sim")
                {
                        $erro="não";

                        if (empty($sistema))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }
                        if (veremail($email)!="ok")
                        {
                                $aviso = "E-mail invalido.";
                                $erro = "sim";
                        }

						
                        $query = "SELECT sistema FROM sistemas WHERE sistema='$sistema'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas > 0)
                        {
                                $aviso = "Esta área já está cadastrada!";
                                $erro = "sim";
                        }

                        
                        if ($erro == "não")
                        {
                                $query = "INSERT INTO sistemas (sistema,sis_status,sis_email,sis_atende) values ('".noHtml($sistema)."',".$status.",'".$email."','".$areaatende."')";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao incluir área";
                                }
                                else
                                {
                                        $aviso = "OK. Área incluida com sucesso.";
                                }
                        }
                        $origem = "incluir_sistema.php";
                        session_register("aviso");
                        session_register("origem");
                        //echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                        ?>
						<script language="javascript">
						<!--
							mensagem('<?print $aviso;?>');
							history.go(-2)();
						//-->
						</script>
						<?												

						}
        ?>


</TABLE>
</FORM>

</body>
<script type="text/javascript">
<!--			
	function valida(){
		var ok = validaForm('idArea','','Área',1);
		if (ok) var ok = validaForm('idEmail','EMAIL','Email',1);
		if (ok) var ok = validaForm('idStatus','COMBO','Status',1);
		
		return ok;
	}		
-->	
</script>
</html>
